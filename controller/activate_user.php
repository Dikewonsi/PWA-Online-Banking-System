<?php
    // Include database connection code or establish a database connection
    session_start();

    if(isset($_SESSION['admin_id']))
    {
        $admin_username = $_SESSION['admin_username'];
    }
    else
    {
        header('location: login.php');
    }

    include('db_connection.php');
    

    // Check if user ID is provided in the URL
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        // Prepare and execute SQL query to update user's activation status based on the provided ID
        $sql = "UPDATE users SET user_status = 1 WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        // Check if activation was successful
        $activation_successful = $stmt->rowCount() > 0;

        if ($activation_successful) {
            // Redirect to the success page with success query parameter
            header("Location: activate_users.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: activate_users.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
?>