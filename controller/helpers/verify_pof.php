<?php

    include('../db_connection.php');

    if(isset($_GET['id']))
    {
        // Retrieve form data
        $id = htmlspecialchars($_GET['id']);

        $userid = $_GET['userid'];
        $amount = $_GET['amount'];
        $deposit_status = $_GET['status'];

        if($deposit_status == 1)
        {
            // Redirect to profile page or provide success message
            header("Location: ../account_funding.php?error=201");
            exit();            
        }
        else
        {
             // New Status
            $status = 1;


            // Update deposit information in the database
            try {
                // Set PDO error mode to exception
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare SQL statement to update admin details
                $sql = "UPDATE deposit_proof SET status = 1 WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                // Redirect to profile page or provide success message
                header("Location: ../user_pop.php?success=1");
                exit();

                // if($stmt)
                // {
                //     try {
                //         // Set PDO error mode to exception
                //         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                //         // Prepare SQL statement to update admin details
                //         $sql = "UPDATE users SET acc_balance = acc_balance + :amount WHERE userid = :userid";
                //         $stmt = $pdo->prepare($sql);
                //         $stmt->bindParam(':userid', $userid);
                //         $stmt->bindParam(':amount', $amount);
                //         $stmt->execute();

                //         // Redirect to profile page or provide success message
                //         header("Location: ../account_funding.php?success=1");
                //         exit();
                //     }   

                //     catch (PDOException $e) {
                //         // Handle database connection errors
                //         header("Location: admin_profile.php?error=1");
                //         exit; // Make sure to exit after redirection
                //     }               
                // }

                
            } catch (PDOException $e) {
                // Handle database connection errors
                header("Location: admin_profile.php?error=1");
                exit; // Make sure to exit after redirection
            }
        } 

    }
    else
    {
        header("location: admin_dashbooard.php");
    }

?>