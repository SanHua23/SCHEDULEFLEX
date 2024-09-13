<?php
include '../connect.php';

$ay_id = $_POST['ay_id'];
$start_year = $_POST['start_year'];
$end_year = $_POST['end_year'];

$response = [];

    if ($_POST['ay_id'] === "0") {

        // Check if course name already exists
        $sqlCheck = "SELECT * FROM ay_tbl WHERE start_year = :start_year AND end_year = :end_year";
        $checkQuery = $conn->prepare($sqlCheck);
        $checkQuery->bindParam(':start_year', $start_year);
        $checkQuery->bindParam(':end_year', $end_year);
        $checkQuery->execute();
        $existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $response['status'] = 'error';
            $response['message'] = 'This academic year already exists!';
        }else{
            // Insert new course
            $sqlInsert = "INSERT INTO ay_tbl (start_year, end_year) VALUES (:start_year, :end_year)";
            $insertQuery = $conn->prepare($sqlInsert);
            $insertQuery->bindParam(':start_year', $start_year);
            $insertQuery->bindParam(':end_year', $end_year);

            if ($insertQuery->execute()) {
                $lastInsertId = $conn->lastInsertId();

                $response['status'] = 'insert';
                $response['ay_id'] = $lastInsertId;
                $response['start_year'] = $start_year;
                $response['end_year'] = $end_year;
            }
        }
    } else {

        // Update existing course
        $sqlUpdate = "UPDATE ay_tbl SET start_year = :start_year, end_year = :end_year WHERE ay_id = :ay_id";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':start_year', $start_year);
        $updateQuery->bindParam(':end_year', $end_year);
        $updateQuery->bindParam(':ay_id', $ay_id);

        if ($updateQuery->execute()) {

            $response['status'] = 'update';
            $response['ay_id'] = $ay_id;
            $response['start_year'] = $start_year;
            $response['end_year'] = $end_year;
        }
    }

echo json_encode($response);
?>
