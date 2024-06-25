<?php
    //Check Update User


    include('db_connection.php');

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $user_id = htmlspecialchars($_POST['userid']);
        $fullname = htmlspecialchars($_POST['fullname']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $created_at = htmlspecialchars($_POST['created_at']);

        // Prepare and execute SQL query to update user data
        $sql = "UPDATE users SET fullname = :fullname, email = :email, password = :password, created_at = :created_at WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password,
            'created_at' => $created_at,            
            'user_id' => $user_id
        ]);        

        // Redirect to a success page or display a success message
        header("Location: edit_users.php?success=1");
        exit;
    }    
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) 
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $userid = htmlspecialchars($_POST['userid']);

        try {
            // Begin a transaction
            $pdo->beginTransaction();
    
            // Delete related accounts
            $stmt = $pdo->prepare("DELETE FROM accounts WHERE user_id = ?");
            $stmt->execute([$userid]);
    
            // Delete user
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->execute([$userid]);
    
            // Commit the transaction
            $pdo->commit();
    
            // Redirect to the success page with success query parameter
            header("Location: delete_users.php?success=1");
            exit; // Make sure to exit after redirection
    
        } catch (PDOException $e) {
            // Rollback the transaction if something went wrong
            $pdo->rollBack();
            error_log("Deletion error: " . $e->getMessage(), 0);
            // Redirect to the error page with error query parameter
            header("Location: delete_users.php?error=1");
            exit; // Make sure to exit after redirection
        }       
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) 
    {
        // Retrieve form data
        $admin_id = htmlspecialchars($_POST['id']);
        $new_username = htmlspecialchars($_POST['new_username']);
        $new_password = htmlspecialchars($_POST['new_password']);
        $current_timestamp = date("Y-m-d H:i:s"); // Current timestamp
       
        // Update admin information in the database
        try {
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL statement to update admin details
            $sql = "UPDATE admin SET username = :username, password = :password WHERE id = :admin_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $new_username);
            $stmt->bindParam(':password', $new_password);
            $stmt->bindParam(':admin_id', $admin_id);
            $stmt->execute();

            // Redirect to profile page or provide success message
            header("Location: admin_profile.php?success=1");
            exit();
        } catch (PDOException $e) {
            // Handle database connection errors
            header("Location: admin_profile.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_admin'])) 
    {
        //Retrieve Form Data
        $admin_id = htmlspecialchars($_POST['id']);
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        // Get the username of the admin who is creating the profile
        $created_by_username = getUsername($pdo, $admin_id);

        // Other form data retrieval and validation...

        // Insert new admin profile into the database
        try {
            // Prepare SQL statement to insert admin details
            $sql = "INSERT INTO admin (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username); // Assuming you have $username and $email variables from form data
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // Redirect to profile page or provide success message
            header("Location: create_admin.php?success=1");
            exit();
        } catch (PDOException $e) {
            // Handle database connection errors
            header("Location: create_admin.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_admin'])) 
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $admin_id = htmlspecialchars($_POST['admin_id']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM admin WHERE id = :admin_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['admin_id' => $admin_id]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_admin_profiles.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: delete_admin_profiles.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user_balance']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $account_id = $_POST['account_id']; // Selected account ID
        $new_balance = $_POST['new_balance']; // New balance

        // Prepare the SQL statement to update the balance
        $stmt = $pdo->prepare("UPDATE accounts SET balance = :new_balance WHERE account_id = :account_id");
        $stmt->bindParam(':new_balance', $new_balance, PDO::PARAM_STR);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Redirect to a success page or display a success message
        header("Location: update_user_balance.php?success=1");
        exit;
    } 
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user_card_balance']))
    {
        $account_id = $_POST['account_id']; // Selected account ID
        // Validate the inputs
        if (is_numeric($amount) && $amount > 0)
        {
            try
            {
                // Begin transaction
                $pdo->beginTransaction();

                // Fetch current balance
                $stmt = $pdo->prepare("SELECT balance FROM cards WHERE card_id = ?");
                $stmt->execute([$card_id]);
                $card = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($card) {
                    $new_balance = $card['balance'] + $amount;

                    // Update balance
                    $stmt = $pdo->prepare("UPDATE cards SET balance = ? WHERE card_id = ?");
                    $stmt->execute([$new_balance, $card_id]);

                    // Commit transaction
                    $pdo->commit();

                    echo "Card balance updated successfully.";
                } else {
                    echo "Card not found.";
                }
            } catch (Exception $e) {
                // Rollback transaction in case of error
                $pdo->rollBack();
                error_log("Failed to update card balance: " . $e->getMessage());
                echo "Failed to update card balance.";
            }
        }
        else
        {
            echo "Invalid amount.";
        }
    } 


    // Function to get the username of the admin who is creating the profile
    function getUsername($pdo, $admin_id) {
        $sql = "SELECT username FROM admin WHERE id = :admin_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }

    // Function to generate random alphanumeric string
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $max)];
        }
        return $randomString;
    }