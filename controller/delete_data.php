<?php
include '../connect.php';

$id = $_POST['id'];
$tablename = $_POST['tablename'];
$id_type = $_POST['id_type'];


$response = [];

$deleteSQL = "DELETE FROM $tablename WHERE $id_type = :id";
$deleteStmt = $conn->prepare($deleteSQL);
$deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
if ($deleteStmt->execute()) {
    if ($tablename === 'schedule_setup_tbl') {
        $deleteSQL2 = "DELETE schedule_f2f_tbl, schedule_ol_tbl 
                       FROM schedule_f2f_tbl 
                       JOIN schedule_ol_tbl 
                       ON schedule_f2f_tbl.$id_type = schedule_ol_tbl.$id_type 
                       WHERE schedule_f2f_tbl.$id_type = :id";
        $deleteStmt2 = $conn->prepare($deleteSQL2);
        $deleteStmt2->bindParam(':id', $id);
        if ($deleteStmt2->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
    } else {
        $response['status'] = 'success';
    }
} else {
    $response['status'] = 'error';
}
echo json_encode($response);
?>
