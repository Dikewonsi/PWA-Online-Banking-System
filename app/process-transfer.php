<?php 
    session_start();
    include('db_connection.php');    

    if (!isset($_SESSION['userid']) || !isset($_SESSION['transfer_amount']) || !isset($_SESSION['rep_acc_num'])) {
        header("location:signin.php");
        exit();
    }

    $userid = $_SESSION['userid'];
    $rep_acc_num = $_SESSION['rep_acc_num'];
    $rep_fullname = $_SESSION['rep_fullname'];
    $transfer_type = $_SESSION['transfer_type'];
    $rep_bank_name = isset($_SESSION['rep_bank_name']) ? $_SESSION['rep_bank_name'] : '';
    $amount = $_SESSION['transfer_amount'];
    $remark = $_SESSION['remark'];
    $category = 'Bank_Transfer';

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Fetch sender's account details
        $stmt = $pdo->prepare("SELECT account_id, balance, account_number FROM accounts WHERE user_id = :userid");
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();
        $senderAccount = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$senderAccount) {
            throw new Exception("Sender's account not found.");
        }

        $balance = $senderAccount['balance'];
        $senderAccountId = $senderAccount['account_id'];
        $senderAccountNumber = $senderAccount['account_number'];

        // Update sender's balance
        $newBalance = $balance - $amount;
        $stmt = $pdo->prepare("UPDATE accounts SET balance = :new_balance WHERE account_id = :account_id");
        $stmt->bindParam(':new_balance', $newBalance, PDO::PARAM_STR);
        $stmt->bindParam(':account_id', $senderAccountId, PDO::PARAM_INT);
        $stmt->execute();

        // Handle intra-bank transfer
        if ($transfer_type == 'intra')
        {
            // Fetch recipient's account details
            $stmt = $pdo->prepare("SELECT account_id, balance FROM accounts WHERE account_number = :acc_num");
            $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
            $stmt->execute();
            $recipientAccount = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$recipientAccount) {
                throw new Exception("Recipient's account not found.");
            }

            $recipientAccountId = $recipientAccount['account_id'];
            $recipientBalance = $recipientAccount['balance'];

            // Update recipient's balance
            $newRecipientBalance = $recipientBalance + $amount;
            $stmt = $pdo->prepare("UPDATE accounts SET balance = :new_balance WHERE account_id = :account_id");
            $stmt->bindParam(':new_balance', $newRecipientBalance, PDO::PARAM_STR);
            $stmt->bindParam(':account_id', $recipientAccountId, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Insert transaction record for sender
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, account_id, type, amount, description, category) VALUES (:userid, :account_id, 'transfer', :amount, :remark, :category)");        
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $senderAccountId, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':remark', $remark, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();

        // Insert transaction record for recipient (intra-bank only)
        if ($transfer_type == 'intra') {
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, account_id, type, amount, description) VALUES ((SELECT user_id FROM accounts WHERE account_number = :acc_num), :account_id, 'deposit', :amount, :remark)");            
            $stmt->bindParam(':account_id', $recipientAccountId, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':remark', $remark, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);            
            $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
            $stmt->execute();
        }

        // Commit transaction
        $pdo->commit();

        // Clear session variables and redirect to success page
        unset($_SESSION['transfer_amount']);
        unset($_SESSION['rep_acc_num']);
        unset($_SESSION['rep_fullname']);
        unset($_SESSION['transfer_type']);
        unset($_SESSION['rep_bank_name']);
        unset($_SESSION['remark']);
        
        header("Location: transfer-success.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        error_log("Transfer process failed: " . $e->getMessage(), 0);
        $error_message = $e->getMessage();
        header("Location: complete_transfer.php?error=" . urlencode($error_message));
        exit();
    }