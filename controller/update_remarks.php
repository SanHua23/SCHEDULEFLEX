<?php
include '../connect.php';

$response = [];

if (isset($_POST['status']) AND isset($_POST['schedule_f2f_id'])) {
    $schedule_f2f_id = $_POST['schedule_f2f_id'];
    $status = $_POST['status'];

    $sqlUpdate = "UPDATE schedule_f2f_tbl SET remarks = :status WHERE schedule_f2f_id = :schedule_f2f_id";
    $updateQuery = $conn->prepare($sqlUpdate);
    $updateQuery->bindParam(':status', $status);
    $updateQuery->bindParam(':schedule_f2f_id', $schedule_f2f_id);
    $updateQuery->execute();
    $existing = $updateQuery->fetch(PDO::FETCH_ASSOC);

    if (!$existing) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
}

echo json_encode($response);
?>
