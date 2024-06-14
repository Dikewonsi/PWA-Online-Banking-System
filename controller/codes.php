<?php
    //Check Update User


    include('db_connection.php');

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $userid = htmlspecialchars($_POST['userid']);
        $f_name = htmlspecialchars($_POST['f_name']);
        $l_name = htmlspecialchars($_POST['l_name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $country = htmlspecialchars($_POST['country']);
        $acc_balance = htmlspecialchars($_POST['acc_balance']);
        $referral_bonus = htmlspecialchars($_POST['referral_bonus']);
        $total_referred = htmlspecialchars($_POST['total_referred']);
        $email_status = htmlspecialchars($_POST['email_status']);
        $residency_status = htmlspecialchars($_POST['residency_status']);
        $id_status = htmlspecialchars($_POST['id_status']);
        $registered_at = htmlspecialchars($_POST['registered_at']);
        $modified_at = htmlspecialchars($_POST['modified_at']);

        // Prepare and execute SQL query to update user data
        $sql = "UPDATE users SET f_name = :f_name, l_name = :l_name, email = :email, phone = :phone, country = :country, acc_balance = :acc_balance, referral_bonus = :referral_bonus, total_referred = :total_referred, email_status = :email_status, residency_status = :residency_status, id_status = :id_status, registered_at = :registered_at, modified_at = :modified_at WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'f_name' => $f_name,
            'l_name' => $l_name,
            'email' => $email,
            'phone' => $phone,
            'country' => $country,
            'acc_balance' => $acc_balance,
            'referral_bonus' => $referral_bonus,
            'total_referred' => $total_referred,
            'email_status' => $email_status,
            'residency_status' => $residency_status,
            'id_status' => $id_status,
            'registered_at' => $registered_at,
            'modified_at' => $modified_at,
            'userid' => $userid
        ]);        

        // Redirect to a success page or display a success message
        header("Location: edit_users.php?success=1");
        exit;
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user_trades_per_day']))
    {
        $userid = htmlspecialchars($_POST['userid']);
        $trades_per_day = htmlspecialchars($_POST['trades_per_day']);

        try {
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL statement to update admin details
            $sql = "UPDATE users SET trades_per_day = :trades_per_day WHERE userid = :userid";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':trades_per_day', $trades_per_day);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();

            // Redirect to profile page or provide success message
            header("Location: edit_trades_per_day.php?success=1");
            exit();
        } catch (PDOException $e) {
            // Handle database connection errors
            header("Location: edit_trades_per_day.php?error=1");
            exit; // Make sure to exit after redirection
        }                
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) 
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $userid = htmlspecialchars($_POST['userid']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM users WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_users.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
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
            $sql = "UPDATE admin SET username = :username, password = :password, modified_at = :modified_at WHERE id = :admin_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $new_username);
            $stmt->bindParam(':password', $new_password);
            $stmt->bindParam(':admin_id', $admin_id);
            $stmt->bindParam(':modified_at', $current_timestamp); // Bind current timestamp
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
            $sql = "INSERT INTO admin (username, password, created_by) VALUES (:username, :password, :created_by)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username); // Assuming you have $username and $email variables from form data
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':created_by', $created_by_username); // Bind the username of the admin who created the profile
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
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_wallet'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $crypto = htmlspecialchars($_POST['crypto']);
        $shortcode = htmlspecialchars($_POST['shortcode']);
        $wallet_address = htmlspecialchars($_POST['wallet_address']);


        $qr_code_file = $_FILES['qr_code'];
        $qr_code_name = basename($qr_code_file['name']);
        $qr_code_tmp_path = $qr_code_file['tmp_name'];
        $qr_code_upload_dir = '../uploads/wallets'; // Specify the upload directory

        $qr_code_path = $qr_code_upload_dir . $qr_code_name;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($qr_code_tmp_path, $qr_code_path)) {
            // File uploaded successfully, continue with database insertion
            try {
                // Prepare SQL statement to insert wallet details
                $sql = "INSERT INTO wallets (crypto, shortcode, wallet_address, qr_code_path, modified_by) VALUES (:crypto, :shortcode, :wallet_address, :qr_code_path, :admin_username)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':admin_username', $admin_username);
                $stmt->bindParam(':crypto', $crypto);
                $stmt->bindParam(':shortcode', $shortcode);
                $stmt->bindParam(':wallet_address', $wallet_address);
                $stmt->bindParam(':qr_code_path', $qr_code_path);
                $stmt->execute();

                // Redirect to a success page or provide a success message
                header("Location: create_wallet.php?success=1");
                exit; // Make sure to exit after redirection
            } catch (PDOException $e) {
                // Handle database connection errors or any other exceptions
                // Redirect to an error page or provide an error message
                // header("Location: create_wallet.php?error=1");
                // exit; // Make sure to exit after redirection
                echo "Error: " . $e->getMessage();
            }
        } else {
            // Failed to move the uploaded file, handle the error as needed
            header("Location: error.php");
            exit;
        }        
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_wallet'])) 
    {
        // Retrieve form data
        $admin_id = htmlspecialchars($_POST['id']);
        $new_username = htmlspecialchars($_POST['new_username']);
        $new_password = htmlspecialchars($_POST['new_password']);
        $current_timestamp = date("Y-m-d H:i:s"); // Current timestamp
       
        // Update admin information in the database
        try {
            $crypto = htmlspecialchars($_POST['crypto']);
            $shortcode = htmlspecialchars($_POST['shortcode']);
            $wallet_address = htmlspecialchars($_POST['wallet_address']);

            // Check if the wallet ID is provided
            if (!empty($_POST['wallet_id'])) {
                $wallet_id = $_POST['wallet_id'];

                // Check if a new image is uploaded
                if (!empty($_FILES['new_qr_code']['tmp_name'])) {
                    // Define the upload directory and move the uploaded file
                    $qr_code_file = $_FILES['new_qr_code'];
                    $qr_code_name = basename($qr_code_file['name']);
                    $qr_code_tmp_path = $qr_code_file['tmp_name'];
                    $qr_code_upload_dir = '../uploads/wallets/';
                    $qr_code_path = $qr_code_upload_dir . '/' . $qr_code_name;

                    // Move the uploaded file to the specified directory
                    if (move_uploaded_file($qr_code_tmp_path, $qr_code_path)) {
                        // Update the database with the new image path
                        $sql = "UPDATE wallets SET crypto = :crypto, shortcode = :shortcode, wallet_address = :wallet_address, qr_code_path = :qr_code_path WHERE id = :wallet_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':crypto', $crypto);
                        $stmt->bindParam(':shortcode', $shortcode);
                        $stmt->bindParam(':wallet_address', $wallet_address);
                        $stmt->bindParam(':qr_code_path', $qr_code_path);
                        $stmt->bindParam(':wallet_id', $wallet_id);
                        $stmt->execute();
                    } else {
                        // Handle the case where file upload failed
                        header("Location: error.php");
                        exit;
                    }
                } else {
                    // Update the database without changing the image path
                    $sql = "UPDATE wallets SET crypto = :crypto, shortcode = :shortcode, wallet_address = :wallet_address WHERE id = :wallet_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':crypto', $crypto);
                    $stmt->bindParam(':shortcode', $shortcode);
                    $stmt->bindParam(':wallet_address', $wallet_address);
                    $stmt->bindParam(':wallet_id', $wallet_id);
                    $stmt->execute();
                }

                // Redirect to the edit wallet page with a success message
                header("Location: edit_wallets.php?success=1");
                exit;
            } else {
                // Handle the case where wallet ID is not provided
                header("Location: error.php");
                exit;
            }
        } catch (PDOException $e) {
            // Display the actual error message
            echo "Error: " . $e->getMessage();
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_wallet'])) 
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $wallet_id = htmlspecialchars($_POST['wallet_id']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM wallets WHERE id = :wallet_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['wallet_id' => $wallet_id]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_wallets.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: delete_wallets.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_stock'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $stock_name = htmlspecialchars($_POST['stock_name']);
        $stock_symbol = htmlspecialchars($_POST['stock_symbol']);
        $stock_price = htmlspecialchars($_POST['stock_price']);


        $stock_image_file = $_FILES['stock_image'];
        $stock_image_name = basename($stock_image_file['name']);
        $stock_image_tmp_path = $stock_image_file['tmp_name'];
        $stock_image_upload_dir = '../uploads/stocks/'; // Specify the upload directory

        $stock_image_path = $stock_image_upload_dir . $stock_image_name;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($stock_image_tmp_path, $stock_image_path))
        {
            // File uploaded successfully, continue with database insertion
            try {
                // Prepare SQL statement to insert wallet details
                $sql = "INSERT INTO stocks (stock_name, stock_symbol, stock_price, stock_image_path, modified_by) VALUES (:stock_name, :stock_symbol, :stock_price, :stock_image_path, :admin_username)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':admin_username', $admin_username);
                $stmt->bindParam(':stock_name', $stock_name);
                $stmt->bindParam(':stock_symbol', $stock_symbol);
                $stmt->bindParam(':stock_price', $stock_price);
                $stmt->bindParam(':stock_image_path', $stock_image_path);
                $stmt->execute();

                // Redirect to a success page or provide a success message
                header("Location: create_stocks.php?success=1");
                exit; // Make sure to exit after redirection
            } catch (PDOException $e) {
                // Handle database connection errors or any other exceptions
                // Redirect to an error page or provide an error message
                // header("Location: create_wallet.php?error=1");
                // exit; // Make sure to exit after redirection
                echo "Error: " . $e->getMessage();
            }
        }
        else
        {
            // Failed to move the uploaded file, handle the error as needed
            echo "Error: " . $e->getMessage();
        }        
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_stock'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $new_stock_name = htmlspecialchars($_POST['new_stock_name']);
        $new_stock_symbol = htmlspecialchars($_POST['new_stock_symbol']);
        $new_stock_price = htmlspecialchars($_POST['new_stock_price']);
       
        // Update admin information in the database
        try {            
            // Check if the wallet ID is provided
            if (!empty($_POST['stock_id'])) {
                $stock_id = $_POST['stock_id'];

                // Check if a new image is uploaded
                if (!empty($_FILES['new_stock_image']['tmp_name'])) {
                    // Define the upload directory and move the uploaded file
                    $img_file = $_FILES['new_stock_image'];
                    $img_name = basename($img_file['name']);
                    $img_tmp_path = $img_file['tmp_name'];
                    $img_upload_dir = '../uploads/stocks/';
                    $img_path = $img_upload_dir . '/' . $img_name;

                    // Move the uploaded file to the specified directory
                    if (move_uploaded_file($img_tmp_path, $img_path)) {
                        // Update the database with the new image path
                        $sql = "UPDATE stocks SET stock_name = :stock_name, stock_symbol = :stock_symbol, stock_price = :stock_price, stock_image_path = :img_path, modified_by = :admin_username WHERE stock_id = :stock_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':stock_name', $new_stock_name);
                        $stmt->bindParam(':stock_symbol', $new_stock_symbol);
                        $stmt->bindParam(':stock_price', $new_stock_price);
                        $stmt->bindParam(':img_path', $img_path);
                        $stmt->bindParam(':stock_id', $stock_id);
                        $stmt->bindParam(':admin_username', $admin_username);
                        $stmt->execute();
                    } else {
                        // Handle the case where file upload failed
                        header("Location: error.php");
                        exit;
                    }
                } else {
                    // Update the database without changing the image path
                    $sql = "UPDATE stocks SET stock_name = :stock_name, stock_symbol = :stock_symbol, stock_price = :stock_price, modified_by = :admin_username WHERE stock_id = :stock_id";
                    $stmt = $pdo->prepare($sql);                    
                    $stmt->bindParam(':stock_name', $new_stock_name);
                    $stmt->bindParam(':stock_symbol', $new_stock_symbol);
                    $stmt->bindParam(':stock_price', $new_stock_price);
                    $stmt->bindParam(':stock_id', $stock_id);
                    $stmt->bindParam(':admin_username', $admin_username);
                    $stmt->execute();
                }

                // Redirect to the edit wallet page with a success message
                header("Location: edit_stocks.php?success=1");
                exit;
            } else {
                // Handle the case where wallet ID is not provided
                header("Location: error.php");
                exit;
            }
        } catch (PDOException $e) {
            // Display the actual error message
            echo "Error: " . $e->getMessage();
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_stock']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $stock_id = htmlspecialchars($_POST['stock_id']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM stocks WHERE stock_id = :stock_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['stock_id' => $stock_id]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_stocks.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: delete_stocks.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_commodity'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $commodity_name = htmlspecialchars($_POST['commodity_name']);
        $commodity_symbol = htmlspecialchars($_POST['commodity_symbol']);
        $commodity_price = htmlspecialchars($_POST['commodity_price']);


        $commodity_image_file = $_FILES['commodity_image'];
        $commodity_image_name = basename($commodity_image_file['name']);
        $commodity_image_tmp_path = $commodity_image_file['tmp_name'];
        $commodity_image_upload_dir = '../uploads/commodities/'; // Specify the upload directory

        $commodity_image_path = $commodity_image_upload_dir . $commodity_image_name;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($commodity_image_tmp_path, $commodity_image_path))
        {
            // File uploaded successfully, continue with database insertion
            try {
                // Prepare SQL statement to insert wallet details                
                $sql = "INSERT INTO commodities (commodity_name, commodity_symbol, commodity_price, commodity_image_path, modified_by) VALUES (:commodity_name, :commodity_symbol, :commodity_price, :commodity_image_path, :admin_username)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':admin_username', $admin_username);
                $stmt->bindParam(':commodity_name', $commodity_name);
                $stmt->bindParam(':commodity_symbol', $commodity_symbol);
                $stmt->bindParam(':commodity_price', $commodity_price);
                $stmt->bindParam(':commodity_image_path', $commodity_image_path);
                $stmt->execute();

                // Redirect to a success page or provide a success message
                header("Location: create_commodities.php?success=1");
                exit; // Make sure to exit after redirection
            } catch (PDOException $e) {
                // Handle database connection errors or any other exceptions
                // Redirect to an error page or provide an error message
                // header("Location: create_wallet.php?error=1");
                // exit; // Make sure to exit after redirection
                echo "Error: " . $e->getMessage();
            }
        }
        else
        {
            // Failed to move the uploaded file, handle the error as needed
            echo "Error: " . $e->getMessage();
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_commodity'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $new_commodity_name = htmlspecialchars($_POST['new_commodity_name']);
        $new_commodity_symbol = htmlspecialchars($_POST['new_commodity_symbol']);
        $new_commodity_price = htmlspecialchars($_POST['new_commodity_price']);

        //Generate time for modification
        $current_timestamp = time();
        $modified_datetime = date("Y-m-d H:i:s", $current_timestamp);
       
        // Update commodity information in the database
        try {            
            // Check if the wallet ID is provided
            if (!empty($_POST['commodity_id'])) {
                $commodity_id = $_POST['commodity_id'];

                // Check if a new image is uploaded
                if (!empty($_FILES['new_commodity_image']['tmp_name'])) {
                    // Define the upload directory and move the uploaded file
                    $img_file = $_FILES['new_commodity_image'];
                    $img_name = basename($img_file['name']);
                    $img_tmp_path = $img_file['tmp_name'];
                    $img_upload_dir = '../uploads/commodities/';
                    $img_path = $img_upload_dir . '/' . $img_name;

                    // Move the uploaded file to the specified directory
                    if (move_uploaded_file($img_tmp_path, $img_path)) {
                        // Update the database with the new image path
                        $sql = "UPDATE commodities SET commodity_name = :commodity_name, commodity_symbol = :commodity_symbol, commodity_price = :commodity_price, commodity_image_path = :img_path, modified_by = :admin_username, date_modified = :modified_datetime WHERE id = :commodity_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':commodity_name', $new_commodity_name);
                        $stmt->bindParam(':commodity_symbol', $new_commodity_symbol);
                        $stmt->bindParam(':commodity_price', $new_commodity_price);
                        $stmt->bindParam(':img_path', $img_path);
                        $stmt->bindParam(':commodity_id', $commodity_id);
                        $stmt->bindParam(':admin_username', $admin_username);
                        $stmt->bindParam(':modified_datetime', $modified_datetime);
                        $stmt->execute();
                    } else {
                        // Handle the case where file upload failed
                        header("Location: error.php");
                        exit;
                    }
                } else {
                    // Update the database without changing the image path
                    $sql = "UPDATE commodities SET commodity_name = :commodity_name, commodity_symbol = :commodity_symbol, commodity_price = :commodity_price, modified_by = :admin_username, date_modified = :modified_datetime WHERE id = :commodity_id";
                    $stmt = $pdo->prepare($sql);                    
                    $stmt->bindParam(':commodity_name', $new_commodity_name);
                    $stmt->bindParam(':commodity_symbol', $new_commodity_symbol);
                    $stmt->bindParam(':commodity_price', $new_commodity_price);
                    $stmt->bindParam(':commodity_id', $commodity_id);
                    $stmt->bindParam(':admin_username', $admin_username);
                    $stmt->bindParam(':modified_datetime', $modified_datetime);
                    $stmt->execute();
                }

                // Redirect to the edit wallet page with a success message
                header("Location: edit_commodities.php?success=1");
                exit;
            } else {
                // Handle the case where wallet ID is not provided
                header("Location: error.php");
                exit;
            }
        } catch (PDOException $e) {
            // Display the actual error message
            echo "Error: " . $e->getMessage();
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_commodity']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $commodity_id = htmlspecialchars($_POST['commodity_id']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM commodities WHERE id = :commodity_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['commodity_id' => $commodity_id]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_commodities.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: delete_commodities.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_copy_trader'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $copy_trader_name = htmlspecialchars($_POST['copy_trader_name']);
        $followers = htmlspecialchars($_POST['followers']);
        $total_trades = htmlspecialchars($_POST['total_trades']);
        $roi = htmlspecialchars($_POST['roi']);
        $days = htmlspecialchars($_POST['days']);
        $profit = htmlspecialchars($_POST['profit']);
        $amount = htmlspecialchars($_POST['amount']);
        $facebook = htmlspecialchars($_POST['facebook']);
        $twitter = htmlspecialchars($_POST['twitter']);
        $instagram = htmlspecialchars($_POST['instagram']);



        $copy_trader_image_file = $_FILES['trader_image'];
        $copy_trader_image_name = basename($copy_trader_image_file['name']);
        $copy_trader_image_tmp_path = $copy_trader_image_file['tmp_name'];
        $copy_trader_image_upload_dir = '../uploads/traders/'; // Specify the upload directory

        $copy_trader_image_path = $copy_trader_image_upload_dir . $copy_trader_image_name;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($copy_trader_image_tmp_path, $copy_trader_image_path))
        {
            // File uploaded successfully, continue with database insertion
            try {
                // Prepare SQL statement to insert data into the commodities table
                $sql = "INSERT INTO copy_traders (trader_name, trader_image, followers, total_trades, roi, days, profit, amount, facebook, twitter, instagram, modified_by) 
                        VALUES (:copy_trader_name, :copy_trader_image_path, :followers, :total_trades, :roi, :days, :profit, :amount, :facebook, :twitter, :instagram, :admin_username)";
                $stmt = $pdo->prepare($sql);
                // Bind parameters
                $stmt->bindParam(':admin_username', $admin_username);
                $stmt->bindParam(':copy_trader_name', $copy_trader_name);
                $stmt->bindParam(':followers', $followers);
                $stmt->bindParam(':total_trades', $total_trades);
                $stmt->bindParam(':roi', $roi);
                $stmt->bindParam(':days', $days);
                $stmt->bindParam(':profit', $profit);
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':facebook', $facebook);
                $stmt->bindParam(':twitter', $twitter);
                $stmt->bindParam(':instagram', $instagram);
                $stmt->bindParam(':copy_trader_image_path', $copy_trader_image_path);
                // Execute the statement
                $stmt->execute();
            
                // Redirect to a success page or provide a success message
                header("Location: view_copy_traders.php?success=1");
                exit; // Make sure to exit after redirection
            } catch (PDOException $e) {
                // Handle database connection errors or any other exceptions
                // Redirect to an error page or provide an error message
                // header("Location: create_wallet.php?error=1");
                // exit; // Make sure to exit after redirection
                echo "Error: " . $e->getMessage();
            }
            
        }
        else
        {
            // Failed to move the uploaded file, handle the error as needed
            echo "Error. Failed to move uploaded file";
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_copy_trader'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $copy_trader_name = htmlspecialchars($_POST['new_trader_name']);
        $followers = htmlspecialchars($_POST['followers']);
        $total_trades = htmlspecialchars($_POST['total_trades']);
        $roi = htmlspecialchars($_POST['roi']);
        $days = htmlspecialchars($_POST['days']);
        $profit = htmlspecialchars($_POST['profit']);
        $amount = htmlspecialchars($_POST['amount']);
        $facebook = htmlspecialchars($_POST['facebook']);
        $twitter = htmlspecialchars($_POST['twitter']);
        $instagram = htmlspecialchars($_POST['instagram']);

        //Generate time for modification
        $current_timestamp = time();
        $modified_datetime = date("Y-m-d H:i:s", $current_timestamp);

         // Update Copy Trader information in the database
         try {            
            // Check if the wallet ID is provided
            if (!empty($_POST['ct_id'])) {
                $ct_id = $_POST['ct_id'];

                // Check if a new image is uploaded
                if (!empty($_FILES['new_trader_image']['tmp_name'])) {
                    // Define the upload directory and move the uploaded file
                    $img_file = $_FILES['new_trader_image'];
                    $img_name = basename($img_file['name']);
                    $img_tmp_path = $img_file['tmp_name'];
                    $img_upload_dir = '../uploads/traders/';
                    $img_path = $img_upload_dir . '/' . $img_name;

                    // Move the uploaded file to the specified directory
                    if (move_uploaded_file($img_tmp_path, $img_path)) {
                        // Update the database with the new image path
                        $sql = "UPDATE copy_traders 
                        SET 
                            trader_name = :copy_trader_name, 
                            trader_image = :img_path, 
                            followers = :followers, 
                            total_trades = :total_trades, 
                            roi = :roi, 
                            days = :days, 
                            profit = :profit, 
                            amount = :amount, 
                            facebook = :facebook, 
                            twitter = :twitter, 
                            instagram = :instagram, 
                            modified_by = :admin_username
                        WHERE id = :ct_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':ct_id', $ct_id);
                        $stmt->bindParam(':copy_trader_name', $copy_trader_name);
                        $stmt->bindParam(':img_path', $img_path);
                        $stmt->bindParam(':followers', $followers);
                        $stmt->bindParam(':total_trades', $total_trades);
                        $stmt->bindParam(':roi', $roi);
                        $stmt->bindParam(':days', $days);
                        $stmt->bindParam(':profit', $profit);
                        $stmt->bindParam(':amount', $amount);
                        $stmt->bindParam(':facebook', $facebook);
                        $stmt->bindParam(':twitter', $twitter);
                        $stmt->bindParam(':instagram', $instagram);                        
                        $stmt->bindParam(':admin_username', $admin_username);
                        $stmt->execute();
                    } else {
                        // Handle the case where file upload failed
                        header("Location: error.php");
                        exit;
                    }
                } else {
                    // Update the database without changing the image path
                    $sql = "UPDATE copy_traders 
                    SET 
                        trader_name = :copy_trader_name,                         
                        followers = :followers, 
                        total_trades = :total_trades, 
                        roi = :roi, 
                        days = :days, 
                        profit = :profit, 
                        amount = :amount, 
                        facebook = :facebook, 
                        twitter = :twitter, 
                        instagram = :instagram, 
                        modified_by = :admin_username
                    WHERE id = :ct_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ct_id', $ct_id);                 
                    $stmt->bindParam(':admin_username', $admin_username);
                    $stmt->bindParam(':copy_trader_name', $copy_trader_name);
                    $stmt->bindParam(':followers', $followers);
                    $stmt->bindParam(':total_trades', $total_trades);
                    $stmt->bindParam(':roi', $roi);
                    $stmt->bindParam(':days', $days);
                    $stmt->bindParam(':profit', $profit);
                    $stmt->bindParam(':amount', $amount);
                    $stmt->bindParam(':facebook', $facebook);
                    $stmt->bindParam(':twitter', $twitter);
                    $stmt->bindParam(':instagram', $instagram);
                    $stmt->execute();
                }

                // Redirect to the edit wallet page with a success message
                header("Location: edit_copy_traders.php?success=1");
                exit;
            } else {
                // Handle the case where wallet ID is not provided
                header("Location: error.php");
                exit;
            }
        } catch (PDOException $e) {
            // Display the actual error message
            echo "Error: " . $e->getMessage();
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_copy_trader']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $ct_id = htmlspecialchars($_POST['ct_id']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM copy_traders WHERE id = :ct_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ct_id' => $ct_id]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_copy_traders.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: delete_copy_traders.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_asset'])) 
    {
         // Retrieve form data
         $admin_username = htmlspecialchars($_POST['admin_username']);
         $asset_class = htmlspecialchars($_POST['asset_class']);
         $asset_ticker = htmlspecialchars($_POST['asset_ticker']);
         $percentage = htmlspecialchars($_POST['percentage']);
         $trade_outcome = htmlspecialchars($_POST['trade_outcome']);


         // Check if asset_ticker exists
        $sql_check = "SELECT COUNT(*) AS count FROM assets WHERE asset_ticker = :asset_ticker";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':asset_ticker', $asset_ticker);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check['count'] > 0) {
            // Redirect to create_asset page with error message
            header("Location: create_assets.php?error=1&message=Asset ticker already exists.");
            exit;
        }

         try {
            // Prepare SQL statement to insert admin details
            $sql = "INSERT INTO assets (asset_class, asset_ticker, percentage, outcome) VALUES (:asset_class, :asset_ticker, :percentage, :trade_outcome)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':asset_class', $asset_class);
            $stmt->bindParam(':asset_ticker', $asset_ticker);
            $stmt->bindParam(':percentage', $percentage);
            $stmt->bindParam(':trade_outcome', $trade_outcome);
            $stmt->execute();

            // Redirect to profile page or provide success message
            header("Location: create_assets.php?success=1");
            exit();
        } catch (PDOException $e) {

            // Get the error message
            // $errorMessage = $e->getMessage();            

            header("Location: create_assets.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_asset'])) 
    {
        // Retrieve form data
        $admin_username = htmlspecialchars($_POST['admin_username']);
        $asset_id = htmlspecialchars($_POST['asset_id']);
        $asset_class = htmlspecialchars($_POST['asset_class']);
        $asset_ticker = htmlspecialchars($_POST['asset_ticker']);
        $percentage = htmlspecialchars($_POST['percentage']);
        $trade_outcome = htmlspecialchars($_POST['trade_outcome']);        
       
        // Update admin information in the database
        try {
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL statement to update admin details
            $sql = "UPDATE assets SET asset_class = :asset_class, asset_ticker = :asset_ticker, percentage = :percentage, outcome = :outcome WHERE id = :asset_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':asset_id', $asset_id);
            $stmt->bindParam(':asset_class', $asset_class);
            $stmt->bindParam(':asset_ticker', $asset_ticker);
            $stmt->bindParam(':percentage', $percentage);            
            $stmt->bindParam(':outcome', $trade_outcome);
            $stmt->execute();

            // Redirect to profile page or provide success message
            header("Location: edit_assets.php?success=1");
            exit();
        } catch (PDOException $e) {
            // Get the error message
            $errorMessage = $e->getMessage();  
            
            echo $errorMessage;

            // // Handle database connection errors
            // header("Location: edit_assets.php?error=1");
            // exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_asset']))
    {
        // Sanitize and validate form input data (you can add more validation as needed)
        $asset_id = htmlspecialchars($_POST['asset_id']);

        // Prepare and execute SQL query to delete user based on the provided ID
        $sql = "DELETE FROM assets WHERE id = :asset_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['asset_id' => $asset_id]);

        // Check if user was deleted successfully
        $delete_successful = $stmt->rowCount() > 0;

        if ($delete_successful) {
            // Redirect to the success page with success query parameter
            header("Location: delete_assets.php?success=1");
            exit; // Make sure to exit after redirection
        } else {
            // Redirect to the error page with error query parameter
            header("Location: delete_assets.php?error=1");
            exit; // Make sure to exit after redirection
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_deposit'])) 
    {
        // Retrieve form data
        $userid = htmlspecialchars($_POST['userid']);
        $f_name = htmlspecialchars($_POST['f_name']);
        $l_name = htmlspecialchars($_POST['l_name']);
        $method = htmlspecialchars($_POST['method']);
        $amount = htmlspecialchars($_POST['amount']);

        // Generate a random alphanumeric reference
        $reference = generateRandomString(40); // You can adjust the length as needed

        $status = 1;

        try {
            // Prepare SQL statement to insert data into the commodities table
            $sql = "INSERT INTO deposit_proof (userid, f_name, l_name, reference, method, amount, status) 
                    VALUES (:userid, :f_name, :l_name, :reference, :method, :amount, :status)";
            $stmt = $pdo->prepare($sql);
            // Bind parameters
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':f_name', $f_name);
            $stmt->bindParam(':l_name', $l_name);
            $stmt->bindParam(':reference', $reference);
            $stmt->bindParam(':method', $method);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':status', $status);
                        
            // Execute the statement
            $stmt->execute();
        
            // Redirect to a success page or provide a success message
            header("Location: add_user_deposit.php?success=1"); 
            exit; // Make sure to exit after redirection
        } catch (PDOException $e) {
            
            echo "Error: " . $e->getMessage();
        }



    }
    else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_deposit_details'])) 
    {
        // Retrieve form data
        $id = htmlspecialchars($_POST['id']);
        $amount = htmlspecialchars($_POST['amount']);
        $date = htmlspecialchars($_POST['date']);
        
        // Update admin information in the database
        try
        {
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL statement to update admin details
            $sql = "UPDATE deposit_proof SET amount = :amount, date = :date WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':date', $date);
            $stmt->execute();

            // Redirect to profile page or provide success message
            header("Location: edit_user_deposit.php?success=1&id=$id");
            exit();
        } catch (PDOException $e) {
            // Get the error message
            $errorMessage = $e->getMessage();  
            
            echo $errorMessage;            
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