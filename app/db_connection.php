<?php
$host = 'localhost';
$dbname = 'capitalvista';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage(), 0);
    exit("An error occurred. Please try again later.");
}
