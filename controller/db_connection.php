<?php

    $dsn = 'mysql:host=localhost;dbname=capitalvista';
    $username = 'root';
    $password = '';
    

    /* Attempt to connect to MySQL database */
    try {
        $pdo = new PDO($dsn, $username, $password);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Set character set to utf8
        $pdo->exec("SET NAMES 'utf8'");
    } catch(PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
?>
