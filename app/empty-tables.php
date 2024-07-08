<?php
include('db_connection.php'); // Your database connection script

try {
    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Delete data from the transactions table
    $pdo->exec("DELETE FROM `transactions`");
    
    // Truncate the accounts table
    $pdo->exec("TRUNCATE TABLE `accounts`");
    
    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "Tables truncated successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
