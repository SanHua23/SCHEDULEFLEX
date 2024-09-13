<?php
// Include your database connection file
include "../connect.php"; // Adjust path as necessary

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $subject_id = $_POST['subject_id'];
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $faculty_id = $_POST['faculty_id'];
    $semester = $_POST['semester'];
    $ay_id = $_POST['ay_id'];

    $sql_check = "
                    SELECT COUNT(*) AS count FROM schedule_setup_tbl 
                    WHERE course_id = :course_id AND section_id = :section_id AND subject_id = :subject_id AND semester = :semester AND ay_id = :ay_id
                    ";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':course_id', $course_id);
    $stmt_check->bindParam(':section_id', $section_id);
    $stmt_check->bindParam(':subject_id', $subject_id);
    $stmt_check->bindParam(':semester', $semester);
    $stmt_check->bindParam(':ay_id', $ay_id);
    $stmt_check->execute();
    $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result_check['count'] > 0) {

        $response = [
            'status' => 'exists',
            'message' => '<small>This schedule set up already exists.<br>Can\'t same Course, Yr Section, Subject and Semester</small>'
        ];
        echo json_encode($response);
        exit;

    }else{

        // Prepare SQL statement for inserting new schedule
        $sql_insert = "INSERT INTO schedule_setup_tbl (course_id, section_id, subject_id, faculty_id, semester, ay_id) 
                       VALUES (:course_id, :section_id, :subject_id, :faculty_id, :semester, :ay_id)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':course_id', $course_id);
        $stmt_insert->bindParam(':section_id', $section_id);
        $stmt_insert->bindParam(':subject_id', $subject_id);
        $stmt_insert->bindParam(':faculty_id', $faculty_id);
        $stmt_insert->bindParam(':semester', $semester);
        $stmt_insert->bindParam(':ay_id', $ay_id);

        // Execute insert query
        if ($stmt_insert->execute()) {
            // Get the last inserted ID
            $lastInsertId = $conn->lastInsertId();
            
            $sql = "
                SELECT COUNT(*) AS row_count,  sst.schedule_setup_id, c.course_name, sc.year_level AS yl, sc.section, sb.subject_title, 
                       f.first_name, f.last_name, sst.semester, ay.start_year, ay.end_year
                FROM schedule_setup_tbl sst
                INNER JOIN course_tbl c ON c.course_id = sst.course_id
                INNER JOIN section_tbl sc ON sc.section_id = sst.section_id
                INNER JOIN subject_tbl sb ON sb.subject_id = sst.subject_id
                INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
                INNER JOIN ay_tbl ay ON ay.ay_id = sst.ay_id
                WHERE sst.schedule_setup_id = :schedule_setup_id
                ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':schedule_setup_id', $lastInsertId);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data['semester'] === '1') {
                $sem = "1st semester"; 
            }else if ($data['semester'] === '2') {
                $sem = "2nd semester"; 
            }
            // Prepare response data
            $response = [
                'status' => 'success',
                'row_count' => $data['row_count'],
                'schedule_setup_id' => $data['schedule_setup_id'],
                'ys' => $data['course_name'] . " - " . $data['yl'] . "" . $data['section'],
                'subject_title' => $data['subject_title'],
                'ay' => $data['start_year'] . " - " . $data['end_year'],
                'faculty' => $data['first_name'] . " " . $data['last_name'],
                'semester' => $sem
            ];

            // Send JSON response back to JavaScript
            echo json_encode($response);
        } else {
            // Handle insert failure
            echo json_encode(['status' => 'error', 'message' => 'Failed to add schedule']);
        }
    }
}

?>
