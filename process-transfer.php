<?php 
    session_start();
    include('db_connection.php');    

    if (!isset($_SESSION['userid']) || !isset($_SESSION['transfer_amount']) || !isset($_SESSION['rep_acc_num']) || !isset($_SESSION['rep_fullname'])) {
        header("location:signin.php");
        exit();
    }

    $userid = $_SESSION['userid'];
    $rep_acc_num = $_SESSION['rep_acc_num'];
    $rep_fullname = $_SESSION['rep_fullname'];
    $amount = $_SESSION['transfer_amount'];
    $remark = $_SESSION['remark'];

    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Fetch sender's account balance and account_id
        $stmt = $pdo->prepare("SELECT account_id, balance, account_number FROM accounts WHERE user_id = :userid");
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();
        $senderAccount = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$senderAccount) {
            throw new Exception("Sender's account not found.");
        }
        
        $senderAccountId = $senderAccount['account_id'];
        $balance = $senderAccount['balance'];
        $senderAccountNumber = $senderAccount['account_number'];
        
        if ($amount > 0 && $amount <= $balance) {
            // Deduct amount from sender's account
            $stmt = $pdo->prepare("UPDATE accounts SET balance = balance - :amount WHERE user_id = :userid");
            $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch recipient's account_id
            $stmt = $pdo->prepare("SELECT account_id FROM accounts WHERE account_number = :acc_num");
            $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
            $stmt->execute();
            $recipientAccount = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$recipientAccount) {
                throw new Exception("Recipient's account not found.");
            }

            $recipientAccountId = $recipientAccount['account_id'];

            // Add amount to recipient's account
            $stmt = $pdo->prepare("UPDATE accounts SET balance = balance + :amount WHERE account_number = :acc_num");
            $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
            $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
            $stmt->execute();

            // Insert transaction record for sender
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, account_id, type, amount, description) VALUES (:userid, :account_id, 'transfer', :amount, :remark)");            
            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $stmt->bindParam(':account_id', $senderAccountId, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':remark', $remark, PDO::PARAM_STR);
            $stmt->execute();

            // Insert transaction record for recipient
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, account_id, type, amount, description) VALUES ((SELECT user_id FROM accounts WHERE account_number = :acc_num), :account_id, 'deposit', :amount, :remark)");            
            $stmt->bindParam(':account_id', $recipientAccountId, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':remark', $remark, PDO::PARAM_STR);
            $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
            $stmt->execute();

            // Commit transaction
            $pdo->commit();

            // Clear session variables and redirect to success page
            unset($_SESSION['transfer_amount']);
            unset($_SESSION['rep_acc_num']);
            unset($_SESSION['rep_fullname']);
            unset($_SESSION['remark']);
            
            header("Location: transfer-success.php");
            exit();
        } else {
            throw new Exception("Invalid transfer amount.");
        }
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        error_log("Transfer process failed: " . $e->getMessage(), 0);
        $error_message = $e->getMessage();
        header("Location: transfer-completed.php?error=" . urlencode($error_message));
        exit();
    }
?>