<?php
session_start();
include '../connect.php'; // Adjust the path as necessary

$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email exists
$sqlCheck = "SELECT * FROM user_tbl WHERE email = :email";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':email', $email);
$checkQuery->execute();
$user = $checkQuery->fetch(PDO::FETCH_ASSOC);

$response = [];

if (!$user) {
    // Email not found
    $response['status'] = 'email';
} else {
    // Email found, now check the password
    if (password_verify($password, $user['password'])) {

        // Password is correct, login successful
        if ($user['verification'] === "verified") { 
            if ($user['status'] === "approved") { 

                if ($user['user_role'] === "2") { 
                    // login success
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_role'] = $user['user_role'];


                    $response['status'] = 'success';
                    $response['user_role'] = $_SESSION['user_role'];
                }else if ($user['user_role'] === "3") { 
                    $student_number = $_POST['student_number'];

                    $sqlCheck2 = "SELECT * FROM user_tbl WHERE student_number = :student_number AND email = :email";
                    $checkQuery2 = $conn->prepare($sqlCheck2);
                    $checkQuery2->bindParam(':student_number', $student_number);
                    $checkQuery2->bindParam(':email', $email);
                    $checkQuery2->execute();
                    $user2 = $checkQuery2->fetch(PDO::FETCH_ASSOC);

                    if (!$user2) {
                        $response['status'] = 'student_number';
                    } else {
                        // login success
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['user_role'] = $user['user_role'];

                        $response['status'] = 'success';
                        $response['user_role'] = $_SESSION['user_role'];
                    }
                }
            }else{
                $response['status'] = 'pending';
            }
        }else{
            $response['status'] = 'verification';
        }
    } else {
        // Password is incorrect
        $response['status'] = 'password';
    }
}

echo json_encode($response);
?>
