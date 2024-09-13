<?php
session_start();
include '../connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
$email = $_POST['email'];
$response = [];

    if ($new_password != $confirm_password) {
        $response['status'] = 'cpassword';
    }else{
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $sqlUpdate = "UPDATE user_tbl SET password = :password WHERE email = :email";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':password', $hashedPassword);
        $updateQuery->bindParam(':email', $email);
        if ($updateQuery->execute()) {
            $response['status'] = 'success';
        }
    }
echo json_encode($response);
}

?>
