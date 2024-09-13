<?php
// Include your database connection file
include "../connect.php";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $schedule_setup_id = $_POST['schedule_setup_id'];
    $time_id = $_POST['time_id'];

    try {
        // Check if the schedule setup exists
        $sql_check = "
            SELECT *, sc.year_level as yl FROM schedule_setup_tbl sst
            INNER JOIN course_tbl c ON c.course_id = sst.course_id
            INNER JOIN section_tbl sc ON sc.section_id = sst.section_id
            INNER JOIN subject_tbl sb ON sb.subject_id = sst.subject_id
            INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
            WHERE schedule_setup_id = :schedule_setup_id
        ";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':schedule_setup_id', $schedule_setup_id);

        if ($stmt_check->execute()) {
            $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($result_check) {
                // Check if the time slot already exists for the same schedule setup
                $sql_check2 = "
                    SELECT *, sc.year_level as yl FROM schedule_ol_tbl sot
                    INNER JOIN schedule_setup_tbl sst ON sst.schedule_setup_id = sot.schedule_setup_id
                    INNER JOIN course_tbl c ON c.course_id = sst.course_id
                    INNER JOIN section_tbl sc ON sc.section_id = sst.section_id
                    INNER JOIN subject_tbl sb ON sb.subject_id = sst.subject_id
                    INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
                    WHERE time_id = :time_id
                ";
                $stmt_check2 = $conn->prepare($sql_check2);
                $stmt_check2->bindParam(':time_id', $time_id);

                if ($stmt_check2->execute()) {
                    $result_check2 = $stmt_check2->fetch(PDO::FETCH_ASSOC);

                    if ($result_check2 && 
                        $result_check2['yl'] === $result_check['yl'] && 
                        $result_check2['course_name'] === $result_check['course_name'] && 
                        $result_check2['semester'] === $result_check['semester'] &&
                        $result_check2['faculty_id'] === $result_check['faculty_id'] &&
                        $result_check2['ay_id'] === $result_check['ay_id']) {

                        $response = [
                            'status' => 'exists',
                            'message' => '<small>Make sure that Course, Yr Section, and Semester are not the same</small>'
                        ];
                        echo json_encode($response); // Send JSON response back to JavaScript
                    } else {
                        // Prepare SQL statement for insertion
                        $sql_insert = "
                            INSERT INTO schedule_ol_tbl (schedule_setup_id, time_id) 
                            VALUES (:schedule_setup_id, :time_id)
                        ";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bindParam(':schedule_setup_id', $schedule_setup_id);
                        $stmt_insert->bindParam(':time_id', $time_id);

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
                    throw new Exception("Error executing second check query.");
                }
            } else {
                throw new Exception("Schedule setup not found.");
            }
        } else {
            throw new Exception("Error executing first check query.");
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
