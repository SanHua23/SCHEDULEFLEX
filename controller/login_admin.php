<?php
session_start();
include '../connect.php'; // Adjust the path as necessary

$username = $_POST['username'];
$password = $_POST['password'];

// Check if the username exists
$sqlCheck = "SELECT * FROM admin_tbl WHERE username = :username";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':username', $username);
$checkQuery->execute();
$user = $checkQuery->fetch(PDO::FETCH_ASSOC);

$response = [];

if (!$user) {
    // Email not found
    $response['status'] = 'username';
} else {
    // Email found, now check the password
    if (password_verify($password, $user['password'])) {

        $_SESSION['user'] = $user;

        $response['status'] = 'success';
    } else {
        // Password is incorrect
        $response['status'] = 'password';
    }
}

echo json_encode($response);
?>
