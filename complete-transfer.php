<?php 
session_start();
include('db_connection.php');    

// Check if user is logged in and transfer type is set
if (!isset($_SESSION['userid']) || !isset($_SESSION['transfer_type'])) {
    header("location:signin.php");
    exit();
}

$userid = $_SESSION['userid'];
$transfer_type = $_SESSION['transfer_type'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transfer']) && !empty($_POST['transfer'])) {
  // Your code here
    $rep_acc_num = $_POST['rep_acc_num'];  
    $rep_fullname = $_POST['rep_fullname'];     

    try{        
        if ($transfer_type == 'intra')
        {
          // Check if the recipient account exists
          $stmt = $pdo->prepare("SELECT * FROM accounts WHERE account_number = :rep_acc_num");
          $stmt->bindParam(':rep_acc_num', $rep_acc_num, PDO::PARAM_STR);
          $stmt->execute();
          $rep_account = $stmt->fetch(PDO::FETCH_ASSOC);

          if (!$rep_account) {
              header("Location: transfer.php?transfer_type=$transfer_type&error=accError");
              exit();
          }

          // Check if the user is trying to transfer to their own account in an intra-bank transaction
          if ($transfer_type == 'intra' && $rep_account['user_id'] == $userid) {
              header("Location: transfer.php?transfer_type=$transfer_type&error=userError");
              exit();
          }

          // Set session variables for recipient account number
          $_SESSION['rep_acc_num'] = $rep_acc_num;

            // Fetch recipient details
            $stmt = $pdo->prepare("SELECT fullname FROM users u JOIN accounts a ON u.user_id = a.user_id WHERE a.account_number = :acc_num");
            $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
            $stmt->execute();
            $recipient = $stmt->fetch(PDO::FETCH_ASSOC);                      
            
            // Set recipient's fullname in session
            $_SESSION['rep_fullname'] = $recipient['fullname'];

            // Redirect to enter-amount.php
            header("Location: enter-amount.php");
            exit();
        }
        elseif ($transfer_type == 'inter')
        {
            // Set inter-bank transfer details in session
            $rep_bank_name = $_POST['rep_bank_name'];
            $rep_fullname = $_POST['rep_fullname'];
            $_SESSION['rep_bank_name'] = $rep_bank_name;
            $_SESSION['rep_fullname'] = $rep_fullname;

            // Set session variables for recipient account number
            $_SESSION['rep_acc_num'] = $rep_acc_num;

            // Redirect to enter-amount.php
            header("Location: enter-amount.php");
            exit();
        }       
    } catch (Exception $e) {
        error_log("Recipient validation failed: " . $e->getMessage(), 0);
        $error_message = $e->getMessage();
    }
}
?>
