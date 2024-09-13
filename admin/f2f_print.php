<?php
include "../connect.php";

// Retrieve parameters from $_GET
$semester = $_GET['semester'] ?? '';
$course_id = $_GET['course_id'] ?? '';
$ay_id = $_GET['ay_id'] ?? '';

// Prepare SQL query
$sql1 = "SELECT * FROM schedule_f2f_tbl sft 
        INNER JOIN schedule_setup_tbl sst ON sst.schedule_setup_id = sft.schedule_setup_id
        INNER JOIN course_tbl c ON c.course_id = sst.course_id
        INNER JOIN ay_tbl ay ON ay.ay_id = sst.ay_id
        WHERE sst.course_id = :course_id AND sst.ay_id = :ay_id 
        GROUP BY sst.course_id";

// Prepare and execute SQL1 statement
$stmt1 = $conn->prepare($sql1);
$stmt1->bindParam(':course_id', $course_id);
$stmt1->bindParam(':ay_id', $ay_id);
$stmt1->execute();
$rows1 = $stmt1->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Schedule Table</title>
    <style>
        .ff-roman {
            font-family: "Times New Roman", Times, serif;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
    <?php include "plugins-header.php"; ?>
</head>
<body>
    <div class="py-2 px">
        <div class="p-2 d-flex justify-content-between">
            <div>
                <div>
                   Face-To-Face Schedule
                </div>
                <div>
                    <span>Academic Year: </span><b><?php echo isset($rows1['ay_id']) ? $rows1['start_year'] . " - " . $rows1['end_year']  : "n/a"; ?></b>
                </div>
            </div>
            <div>
                <div>
                    <span>Course: </span><b><?php echo isset($rows1['course_name']) ? $rows1['course_name'] : "n/a";?></b>
                </div>
                <div>
                    <span>Semester: </span><b><?php echo $semester; ?></b>
                </div>
            </div>
        </div>
        <table id="schedule_table" class="table p-2 table-borderless">
            <thead>
                <tr>
                    <th>Time & Date</th>
                    <th>Subject</th>
                    <th>Year & Section</th>
                    <th>Room</th>
                    <th>Teacher</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // Prepare SQL query
                $sql = "SELECT * FROM schedule_f2f_tbl sft 
                        INNER JOIN schedule_setup_tbl sst ON sst.schedule_setup_id = sft.schedule_setup_id
                        INNER JOIN course_tbl c ON c.course_id = sst.course_id
                        INNER JOIN section_tbl sec ON sec.section_id = sst.section_id
                        INNER JOIN room_tbl r ON r.room_id = sft.room_id
                        INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
                        INNER JOIN subject_tbl sub ON sub.subject_id = sst.subject_id
                        WHERE sst.course_id = :course_id AND sst.semester = :semester AND sst.ay_id = :ay_id";
                    
                // Prepare and execute SQL statement
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':course_id', $course_id);
                $stmt->bindParam(':semester', $semester);
                $stmt->bindParam(':ay_id', $ay_id);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output rows in table body
                if ($rows) {
                    foreach ($rows as $row):
                ?>
                <tr data-id="<?php echo $row['schedule_f2f_id']; ?>" class="border border-2">
                    <td>
                        <?php echo date("F d, Y h:i a", strtotime($row['start_datetime'])); ?><br>
                        <?php echo date("F d, Y h:i a", strtotime($row['end_datetime'])); ?>
                    </td>
                    <td><?php echo $row['subject_title']; ?></td>
                    <td><?php echo $row['year_level']."-".$row['section']; ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
                </tr>
                <?php endforeach; }else{ ?>
                    <tr>
                        <td colspan="100"class="text-center">No data available</td>
                    </tr>
                <?php } ?>        
            </tbody>
        </table>
    </div>
    <?php include "plugins-footer.php"; ?>
    <script>
        // JavaScript for printing and closing window after print
        window.print();  
        window.onafterprint = window.close; 
    </script>
</body>
</html>
