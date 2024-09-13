<?php
include "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['course_id']) && isset($_GET['year_level'])) {
        $course_id = $_GET['course_id'];
        $year_level = $_GET['year_level'];

        $sql_sections = "SELECT section_id, year_level, section FROM section_tbl WHERE course_id = :course_id AND year_level = :year_level";
        $stmt_sections = $conn->prepare($sql_sections);
        $stmt_sections->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt_sections->bindParam(':year_level', $year_level, PDO::PARAM_INT);
        $stmt_sections->execute();
        $sections = $stmt_sections->fetchAll(PDO::FETCH_ASSOC);

        $sql_subjects = "SELECT subject_id, subject_title FROM subject_tbl WHERE course_id = :course_id AND year_level = :year_level";
        $stmt_subjects = $conn->prepare($sql_subjects);
        $stmt_subjects->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt_subjects->bindParam(':year_level', $year_level, PDO::PARAM_INT);
        $stmt_subjects->execute();
        $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(array(
            'subjects' => $subjects,
            'sections' => $sections
        ));
    } elseif (isset($_GET['course_id']) && isset($_GET['subject_id'])) {
        $course_id = $_GET['course_id'];
        $subject_id = $_GET['subject_id'];

        $sql_facultys = "
                        SELECT f.faculty_id, f.first_name, f.last_name FROM faculty_tbl f 
                        INNER JOIN preferred_subject_tbl pst ON pst.faculty_id = f.faculty_id 
                        WHERE f.course_id = :course_id AND pst.subject_id = :subject_id
                        ";
        $stmt_facultys = $conn->prepare($sql_facultys);
        $stmt_facultys->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt_facultys->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt_facultys->execute();
        $facultys = $stmt_facultys->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(array('facultys' => $facultys));
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
    }
}
?>
