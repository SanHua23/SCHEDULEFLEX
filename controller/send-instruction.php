<?php
include '../connect.php'; // Adjust the path as necessary
use PHPMailer\PHPMailer\PHPMailer;

require_once "../phpmailer/PHPMailer.php";
require_once "../phpmailer/SMTP.php";
require_once "../phpmailer/Exception.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$email = $_POST['email'];

// Check if email already exists
$sqlCheck = "SELECT * FROM user_tbl WHERE email = :email";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':email', $email);
$checkQuery->execute();
$existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

$response = [];

if (!$existing) {
    // Email already exists
    $response['status'] = 'error';
} else {
    // Email user
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "kkit8588@gmail.com";
    $mail->Password = 'aiorrgpinpteusih';
    $mail->Port = 587;
    $mail->SMTPSecure = "tls";

    $mail->isHTML(true);
    $mail->setFrom('johnemmanueltmesa@iskolarngbayan.pup.edu.ph', 'PUP');
    $mail->addAddress($email);
    $mail->Subject = "PUP Account Password Reset";
    $mail->Body = "
                <div style='font-size: 15px;'>Hello " . $existing['first_name'] . " " . $existing['last_name'] . ",</div>
                <br>
                <div style='font-size: 15px;'>We received a request to reset the password for your PUP account. Please click the link below to reset your password:</div>
                <br>
                <div style='font-size: 15px;'><a href='http://localhost:8080/css/forgot_password.php?email=".$email."'>Reset Password</a></div>
                <br>
                <div style='font-size: 15px;'>If you did not request a password reset, please ignore this email.</div>
                <br>
                <div style='font-size: 15px;'>Thank you,</div>
                <div style='font-size: 15px;'>PUP School</div>";

    if ($mail->send()) {
        $response['status'] = 'success';
    }else{
        $response['status'] = 'error';
    }
}

echo json_encode($response);
}
?>
