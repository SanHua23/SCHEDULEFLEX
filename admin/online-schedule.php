<?php include "../connect.php"; ?>

<!DOCTYPE html>
<html>
<!-- header link -->
<?php include "plugins-header.php"; ?>
<body>
	<div class="main-div">
		<!-- sidebar and topbar start -->
		<?php include "sidebar.php"; ?>
		<!-- sidebar and topbar start -->

	 	<!-- content container start -->
	    <div class="content-div p-4">
	        <div class="row row-gap-3">
	        	<div class="col-12 d-flex flex-column row-gap-2">
	        		<div class="border bg-white d-flex flex-column row-gap-2 p-3">
	        			<div >
                            <strong class="mb-2">List of Online Schedule</strong>
                            <div class="row my-2 justify-content-between">
                                <form method="GET" action="online_print.php" class="col-12 col-lg-6 d-flex flex-column flex-lg-row align-items-center gap-2" target="_blank">
                                    <select required class="form-select form-select-sm rounded-1" name="course_id" id="course_id_filter">
                                        <option hidden selected value="">Choose Course</option>
                                        <?php
                                        // PHP code to fetch courses from database
                                        $sql = "SELECT * FROM course_tbl";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rows) {
                                            foreach ($rows as $row):
                                                ?>
                                                <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                    <select required class="form-select form-select-sm rounded-1" name="semester" id="semester">
                                        <option hidden selected value="">Choose Semester</option>
                                        <option value="1">1st semester</option>
                                        <option value="2">2nd semester</option>
                                    </select>

                                    <select required class="form-select form-select-sm rounded-1" name="ay_id" id="ay_id">
                                        <option hidden selected value="">Choose Academic Year</option>
                                        <?php
                                        // PHP code to fetch courses from database
                                        $sql = "SELECT * FROM ay_tbl";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rows) {
                                            foreach ($rows as $row):
                                                ?>
                                                <option value="<?php echo $row['ay_id']; ?>"><?php echo $row['start_year'] . " - " . $row['end_year']; ?></option>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap me-auto me-lg-0"><i class="fa-regular fa-folder"></i> Generate</button>
                                </form>

                                <div class="col-12 col-lg-6 d-flex mt-2 mt-lg-0">
                                	<form class="col col-lg-5 d-flex align-items-center column-gap-2 ms-auto">
	                                	<select required class="form-select form-select-sm rounded-1 me-auto" name="ay_id" id="ay_id">
	                                        <option hidden selected value="">Choose Academic Year</option>
	                                        <option value="">Select All</option>
	                                        <?php
	                                        // PHP code to fetch courses from database
	                                        $sql = "SELECT * FROM ay_tbl";
	                                        $stmt = $conn->prepare($sql);
	                                        $stmt->execute();
	                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                        if ($rows) {
	                                            foreach ($rows as $row):
	                                                ?>
	                                                <option value="<?php echo $row['ay_id']; ?>"><?php echo $row['start_year'] . " - " . $row['end_year']; ?></option>
	                                            <?php
	                                            endforeach;
	                                        }
	                                        ?>
	                                    </select>

	                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap"><i class="fa-solid fa-filter"></i> Filter</button>
	                                </form>
                                </div>
                            </div>

                            <div class="col-12 col-lg-3 mt-2">
                                <input type="text" id="search-bar" class="form-control form-control-sm rounded-1 py-2 " placeholder="Search here">
                            </div>

                                
                        </div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="schedule_table" class="table table-bordered table-striped">
			                    <thead class="position-sticky top-0">
			                        <tr class="py-5">
			                            <th>#</th>
			                            <th>Time</th>
			                            <th>Day</th>
			                            <th>Subject</th>
			                            <th>Year & Section</th>
			                            <th>Course</th>
			                            <th>Teacher</th>
			                            <th>Sem</th>
			                            <th>AY</th>
			                            <th>Action</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
							    	// PHP code to fetch courses from database

		                                $ay_id = isset($_GET['ay_id']) ? $_GET['ay_id'] : null;

	                                    $sql = "SELECT *, sst.semester sem FROM schedule_ol_tbl sot 
	                                    		INNER JOIN schedule_setup_tbl sst ON sst.schedule_setup_id = sot.schedule_setup_id
	                                    		INNER JOIN course_tbl c ON c.course_id = sst.course_id
	                                    		INNER JOIN section_tbl sec ON sec.section_id = sst.section_id
	                                    		INNER JOIN time_tbl t ON t.time_id = sot.time_id
	                                    		INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
	                                    		INNER JOIN subject_tbl sub ON sub.subject_id = sst.subject_id
	                                    		INNER JOIN ay_tbl ay ON ay.ay_id = sst.ay_id";
	                                   	
	                                   	if ($ay_id !== null && $ay_id !== "") {
										    $sql .= " WHERE sst.ay_id = :ay_id";
										}

	                                    $stmt = $conn->prepare($sql);

	                                    if ($ay_id !== null && $ay_id !== "") {
										    $stmt->bindParam(":ay_id", $ay_id, PDO::PARAM_INT);
										}

	                                    $stmt->execute();
	                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                    $row_count = 1;
	                                    if ($rows) {
	                                        foreach ($rows as $row):
	                                ?>
								    <tr data-id="<?php echo $row['schedule_id']; ?>">
								        <td width="50"><?php echo $row_count++?></td>
								        <td>
								        	<?php echo date("h:i a", strtotime($row['start_time'])); ?> - <?php echo date("h:i a", strtotime($row['end_time'])); ?>
								        	
								        </td>
								        <td>
								        	<?php
								                switch($row['days']) {
								                    case 'M': echo "Monday"; break;
								                    case 'T': echo "Tuesday"; break;
								                    case 'W': echo "Wednesday"; break;
								                    case 'TH': echo "Thursday"; break;
								                    case 'F': echo "Friday"; break;
								                    case 'S': echo "Saturday"; break;
								                }
								            ?>
								        </td>
								        <td><?php echo $row['subject_title']; ?></td>
								        <td><?php echo $row['course_name']."-".$row['year_level']."".$row['section']; ?></td>
								        <td><?php echo $row['course_name']; ?></td>
								        <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
								        <td>
								        	<?php
								                switch($row['sem']) {
								                    case '1': echo "1st"; break;
								                    case '2': echo "2nd"; break;
								                    case null: echo "none"; break;
								                }
								            ?>
								            semester
								        </td>
								        <td><?php echo $row['start_year'] . " - " . $row['end_year']; ?></td>
								        <td width="50">
								        	<div class="d-flex align-items-center column-gap-2">
								        		<div role="button" class="text-danger delete_data" data-id="<?php echo $row['schedule_id']; ?>" data-table="schedule_ol_tbl" data-type="schedule_id" >
								        			<i class="fa-solid fa-trash"></i>
								        		</div>
								        	</div>
								        </td>
								    </tr>
								    <?php endforeach; }else{ ?>
                                        <tr>
                                            <td colspan="100" class="text-center">No Online Schedule Available</td>
                                        </tr>
                                    <?php } ?>


							 	</tbody>
						 	</table>
						 </div>
					</div>
	        	</div>
	        </div>


	    </div>
	    <!-- content container end-->
    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>


</body>
</html>


