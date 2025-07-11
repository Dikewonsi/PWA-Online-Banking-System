<?php
session_start();
include('../db_connection.php');

if (!isset($_SESSION['userid'])) {
    header("Location: ../signin.php");
    exit();
}

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fieldsToUpdate = [];
    $params = [':userid' => $userid];

    if (!empty($_POST['dob'])) {
        $fieldsToUpdate[] = "dob = :dob";
        $params[':dob'] = $_POST['dob'];
    }
    if (!empty($_POST['ssn'])) {
        $fieldsToUpdate[] = "ssn = :ssn";
        $params[':ssn'] = $_POST['ssn'];
    }
    if (!empty($_POST['residential_address'])) {
        $fieldsToUpdate[] = "residential_address = :residential_address";
        $params[':residential_address'] = $_POST['residential_address'];
    }
    if (!empty($_POST['mailing_address'])) {
        $fieldsToUpdate[] = "mailing_address = :mailing_address";
        $params[':mailing_address'] = $_POST['mailing_address'];
    }
    if (!empty($_POST['phone'])) {
        $fieldsToUpdate[] = "phone = :phone";
        $params[':phone'] = $_POST['phone'];
    }
    if (!empty($_POST['employment'])) {
        $fieldsToUpdate[] = "employment = :employment";
        $params[':employment'] = $_POST['employment'];
    }
    if (!empty($_POST['citizenship'])) {
        $fieldsToUpdate[] = "citizenship = :citizenship";
        $params[':citizenship'] = $_POST['citizenship'];
    }
    if (!empty($_POST['marital_status'])) {
        $fieldsToUpdate[] = "marital_status = :marital_status";
        $params[':marital_status'] = $_POST['marital_status'];
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/profile_pics/";
        $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $fieldsToUpdate[] = "profile_pic = :profile_pic";
                $params[':profile_pic'] = $target_file;
            }
        }
    }

    if (!empty($fieldsToUpdate)) {
        $sql = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE user_id = :userid";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            header("Location: ../my-account.php?update=success");
            exit();
        } catch (PDOException $e) {
            error_log("Update failed: " . $e->getMessage(), 0);
            echo "An error occurred. Please try again later.";
        }
    } else {
        header("Location: ../profile.php?update=none");
        exit();
    }
} else {
    header("Location: ../profile.php");
    exit();
}
?>
