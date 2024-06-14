<?php
// Adjust your database credentials accordingly
$host = 'localhost';
$dbname = 'capitalvista';
$username = 'root';
$password = '';

$user_id = 11;       // Replace with the desired user_id
$account_id = 5;    // Replace with the desired account_id

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Array of transaction types and categories
    $types = ['deposit', 'withdrawal', 'transfer'];
    $categories = ['subscription', 'purchase', 'bill_pay', 'others'];
    $descriptions = [
        'Payment for electricity',
        'School fees',
        'Groceries',
        'Monthly subscription',
        'Gym membership',
        'Internet bill',
        'Car insurance',
        'Mortgage payment',
        'Dining out',
        'Clothing purchase',
        'Medical expenses',
        'Travel expenses',
        'Gift purchase',
        'Charity donation',
        'Home maintenance',
        'Pet care expenses',
        'Entertainment expenses',
        'Loan repayment',
        'Utility bill',
        'Fuel expenses',
        'Education materials',
        'Phone bill',
        'Investment deposit',
        'Tax payment'
    ];

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, account_id, type, amount, description, transaction_date, category) 
                           VALUES (:user_id, :account_id, :type, :amount, :description, :transaction_date, :category)");

    // Generate 50 random transactions
    for ($i = 0; $i < 50; $i++) {
        $type = $types[array_rand($types)];
        $amount = mt_rand(10, 500); // Random amount between 10 and 500
        $transaction_date = date('Y-m-d H:i:s', mt_rand(strtotime('2018-06-25'), time()));
        $category = $categories[array_rand($categories)];
        $description = $descriptions[array_rand($descriptions)];

        // Validate category against ENUM values
        if (!in_array($category, $categories)) {
            die("Invalid category: $category");
        }

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':transaction_date', $transaction_date, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
    }

    echo "50 transactions inserted successfully for user_id = $user_id and account_id = $account_id.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
