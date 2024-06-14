<?php

    include('../db_connection.php');

    if(isset($_GET['id']))
    {
        $submission_id = $_GET['id'];
        $status = $_GET['status'];

        $userid = $_GET['userid'];



        if($_GET['status'] == 0)
        {
            // Update id_status information in the database
            try {
                // Set PDO error mode to exception
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare SQL statement to update admin details
                $sql = "UPDATE id_status SET status = 1 WHERE id = :submission_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':submission_id', $submission_id);
                $stmt->execute();

                if($stmt)
                {
                    try {
                        // Set PDO error mode to exception
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                        // Prepare SQL statement to update admin details
                        $sql = "UPDATE users SET id_status = id_status WHERE userid = :userid";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':userid', $userid);
                        $stmt->execute();

                        // Redirect to profile page or provide success message
                        header("Location: ../approve_id.php?success=1");
                        exit();
                    }   

                    catch (PDOException $e) {
                        // Handle database connection errors
                        header("Location: ../approve_id.php?error=1");
                        exit; // Make sure to exit after redirection
                    }               
                }

                
            } catch (PDOException $e) {
                // Handle database connection errors
                header("Location: ../approve_id.php?error=1");
                exit; // Make sure to exit after redirection
            }
        }
        else
        {
            header("location: ../approve_id.php");
            exit;
        }


    }
    else
    {
        header("location: approve_id.php");
        exit;
    }            

?>