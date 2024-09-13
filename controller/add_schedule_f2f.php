<?php
// Include your database connection file
include "../connect.php";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $schedule_setup_id = $_POST['schedule_setup_id'];
    $room_id = $_POST['room_id'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];

    try {
        // Query to check for overlapping intervals
        $sql_check_overlap = "
            SELECT * FROM schedule_f2f_tbl
            WHERE room_id = :room_id
            AND (
                :start_datetime BETWEEN start_datetime AND end_datetime
                OR :end_datetime BETWEEN start_datetime AND end_datetime
                OR start_datetime BETWEEN :start_datetime AND :end_datetime
                OR end_datetime BETWEEN :start_datetime AND :end_datetime
            )
        ";
        $stmt_check_overlap = $conn->prepare($sql_check_overlap);
        $stmt_check_overlap->bindParam(':room_id', $room_id);
        $stmt_check_overlap->bindParam(':start_datetime', $start_datetime);
        $stmt_check_overlap->bindParam(':end_datetime', $end_datetime);

        if ($stmt_check_overlap->execute()) {
            $result_check_overlap = $stmt_check_overlap->fetch(PDO::FETCH_ASSOC);

            if ($result_check_overlap) {
                $response = [
                    'status' => 'exists',
                    'message' => '<small>The new schedule overlaps with an existing schedule.</small>'
                ];
                echo json_encode($response); // Send JSON response back to JavaScript
            } else {
                // Prepare SQL statement for insertion
                $sql_insert = "
                    INSERT INTO schedule_f2f_tbl (schedule_setup_id, room_id, start_datetime, end_datetime) 
                    VALUES (:schedule_setup_id, :room_id, :start_datetime, :end_datetime)
                ";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(':schedule_setup_id', $schedule_setup_id);
                $stmt_insert->bindParam(':room_id', $room_id);
                $stmt_insert->bindParam(':start_datetime', $start_datetime);
                $stmt_insert->bindParam(':end_datetime', $end_datetime);

                // Execute the statement
                if ($stmt_insert->execute()) {
                    $response = [
                        'status' => 'success'
                    ];
                    echo json_encode($response); // Send JSON response back to JavaScript
                } else {
                    throw new Exception("Error inserting schedule: " . $stmt_insert->errorInfo()[2]);
                }
            }
        } else {
            throw new Exception("Error executing overlap check query.");
        }
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
        echo json_encode($response); // Send JSON response back to JavaScript
    }
}
?>
