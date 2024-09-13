<?php
include '../connect.php';

$response = [];

if (isset($_POST['status']) AND isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $sqlUpdate = "UPDATE user_tbl SET status = :status WHERE user_id = :user_id";
    $updateQuery = $conn->prepare($sqlUpdate);
    $updateQuery->bindParam(':status', $status);
    $updateQuery->bindParam(':user_id', $user_id);
    $updateQuery->execute();
    $existing = $updateQuery->fetch(PDO::FETCH_ASSOC);

    if (!$existing) {
        $lastInsertId = $conn->lastInsertId();
        $response['status'] = 'success';
        $response['user_id'] = $lastInsertId;
        $response['updata_status'] = $status;
    } else {
        $response['status'] = 'error';
    }
}

echo json_encode($response);
?>
