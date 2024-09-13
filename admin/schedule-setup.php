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
	        	<div class="col-12 col-lg-4">
	        		<!-- inserting -->

	        		<form id="add_schedule_setup" class="border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Set For Schedule</strong>
					        <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
					    </div>
					    <input type="text" name="section_id" id="section_id" class="form-control rounded-1" value="0" hidden>
					    <div class="form-floating">
						    <select required class="form-select rounded-1" name="course_id" id="course_id_select">
						        <option hidden selected value="">Choose Option Below</option>
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
						     <label for="course_id">Course Name:</label>
						</div>

						<div class="form-floating">
					        <select required class="form-select rounded-1" name="year_level" id="year_level_select" disabled>
					            <option hidden selected value="">Choose Option Below</option>
					            <option value="1">1st Year</option>
					            <option value="2">2nd Year</option>
					            <option value="3">3rd Year</option>
					            <option value="4">4th Year</option>
					        </select>
					        <label for="year_level">Year Level:</label>
					    </div>

						<div class="form-floating">
						    <select required class="form-select rounded-1" name="subject_id" id="subject_id_select" disabled>
						        <option hidden selected value="">Choose Option Below</option>
						    </select>
						    <label for="subject_id">Subject:</label>
						</div>

						<div class="form-floating">
						    <select required class="form-select rounded-1" name="section_id" id="section_id_select" disabled>
						        <option hidden selected value="">Choose Option Below</option>
						    </select>
						    <label for="section_id">Year and Section:</label>
						</div>

						<div class="form-floating">
						    <select required class="form-select rounded-1" name="faculty_id" id="faculty_id_select" disabled>
						        <option hidden selected value="">Choose Option Below</option>
						    </select>
						     <label for="course_id">Faculty Name:</label>
						</div>


					    <div class="form-floating">
						    <select required class="form-select rounded-1" name="semester" id="semester">
						        <option hidden selected value="">Choose Option Below</option>
						        <option value="1">1st semester</option>
						        <option value="2">2nd semester</option>
						    </select>
						    <label for="semester">Semester:</label>
						</div>

						<div class="form-floating">
						    <select required class="form-select rounded-1" name="ay_id" id="ay_id">
						        <option hidden selected value="">Choose Option Below</option>
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
						     <label for="ay_id">Academic Year:</label>
						</div>

					    <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
					</form>

	        	</div>
	        	<div class="col-12 col-lg-8 d-flex flex-column row-gap-2">
	        		<div class="border bg-white d-flex flex-column row-gap-2 p-3">
	        			<div class="d-flex align-items-center">
                            <strong class="mb-2">List of Schedule Set up</strong>
                            <div class="ms-auto">
                                <input type="text" id="search-bar" class="form-control form-control-sm rounded-1" placeholder="Search here">
                            </div>
                        </div>
		        		
		        		<div style="max-height: 70vh;">
						    <table id="schedule_setup_table" class="table table-bordered table-striped">
						        <thead class="position-sticky top-0">
						            <tr class="py-5">
						                <th>#</th>
						                <th>Course Yr. & Sec.</th>
						                <th>Subject</th>
						                <th>Teacher</th>
						                <th>Sem</th>
						                <th>AY</th>
						                <th>Action</th>
						            </tr>
						        </thead>
						        <tbody>
						            <?php
						                $sql = "SELECT *, sst.semester sem FROM schedule_setup_tbl sst 
						                        INNER JOIN course_tbl c ON c.course_id = sst.course_id
						                        INNER JOIN section_tbl sec ON sec.section_id = sst.section_id
						                        INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
						                        INNER JOIN subject_tbl sub ON sub.subject_id = sst.subject_id
						                        INNER JOIN ay_tbl ay ON ay.ay_id = sst.ay_id
						                        ORDER BY sst.schedule_setup_id ASC
						                        ";
						                $stmt = $conn->prepare($sql);
						                $stmt->execute();
						                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

						                $row_count = 1;
						                if ($rows) {
						                    foreach ($rows as $row):
						            ?>
						            <tr data-id="<?php echo $row['schedule_setup_id']; ?>">
						                <td width="50"><?php echo $row_count++?></td>
						                <td><?php echo $row['course_name'] ." - ". $row['year_level']."".$row['section']; ?></td>
						                <td><?php echo $row['subject_title']; ?></td>
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
						                <td><?php echo $row['start_year']." - ".$row['end_year']; ?></td>
						                <td width="50">
						                    <div class="dropdown">
						                        <div type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
						                            <i class="fa-solid fa-ellipsis-vertical"></i>
						                        </div>
						                        <small class="dropdown-menu " aria-labelledby="dropdownMenuButton">
						                           <li>
						                            	<a class="dropdown-item d-flex align-items-center gap-3 btn-f2f" 
						                            		href="#f2f-modal"
						                            		data-bs-toggle="modal"
						                            		data-id="<?php echo $row['schedule_setup_id']; ?>">
						                            	    <i class="fa-regular fa-calendar "></i>
						                            	    <span>F2F Schedule</span>
						                            	     
							                            </a>
							                        </li>
						                            <li>
						                            	<a class="dropdown-item d-flex align-items-center gap-3 btn-online" 
						                            		href="#online-modal"
						                            		data-bs-toggle="modal"
						                            		data-id="<?php echo $row['schedule_setup_id']; ?>">
						                            	    <i class="fa-solid fa-calendar"></i>
						                            	    <span>Online Schedule</span>
							                            </a>
							                        </li>
						                            <li>
						                                <a class="dropdown-item text-danger d-flex align-items-center gap-3 delete_data" href="#"
							                                data-id="<?php echo $row['schedule_setup_id']; ?>" 
							                                data-table="schedule_setup_tbl" 
							                                data-type="schedule_setup_id"
							                            >
						                                	<i class="fa-solid fa-trash"></i>
						                                	<span>Delete</span>
						                                </a>
						                            </li>
						                        </small>
						                    </div>
						                </td>
						            </tr>
						            <?php endforeach; } ?>
						        </tbody>
						    </table>
						</div>

					</div>
	        	</div>
	        </div>


	    </div>
	    <!-- content container end-->
    </div>

    <!-- MODAL FOR ONLINE SCHEDULE -->
    <div class="modal fade" id="online-modal" tabindex="-1">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	      		<div class="modal-body">
		        <form id="add_schedule_ol" class="d-flex flex-column row-gap-2 p-3">
				    <div class="mb-2 d-flex align-items-center justify-content-between">
				        <h5 class="modal-title">Online Schedule Form</h5>
		        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				    </div>
				    <input type="text" name="schedule_setup_id" class="schedule_setup_id" hidden>


				    <div class="form-floating">
					    <select required class="form-select rounded-1" name="days" id="days">
					        <option hidden selected value="">Choose Option Below</option>
					        <option value="M">Monday</option>
					        <option value="T">Tuesday</option>
					        <option value="W">Wednesday</option>
					        <option value="TH">Thursday</option>
					        <option value="F">Friday</option>
					        <option value="S">Saturday</option>
					    </select>
					    <label for="days">Day:</label>
					</div>

					<div class="form-floating">
					    <select required class="form-select rounded-1" name="time_id" id="time_id" disabled>
					        <option hidden selected value="">Choose Option Below</option>
					        <?php
					        $sql = "SELECT * FROM time_tbl";
					        $stmt = $conn->prepare($sql);
					        $stmt->execute();
					        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

					        if ($rows) {
					            foreach ($rows as $row):
					                ?>
					                <option value="<?php echo $row['time_id']; ?>" data-days="<?php echo $row['days']; ?>">
					                    <?php echo date("h:i A", strtotime($row['start_time'])); ?> - <?php echo date("h:i A", strtotime($row['end_time'])); ?> 
					                </option>
					                <?php
					            endforeach;
					        }
					        ?>
					    </select>
					    <label for="time_id">Time:</label>
					</div>




				    <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
				</form>
	      		</div>
	  		</div>
		</div>
	</div>




	<!-- MODAL FOR F2F SCHEDULE -->
    <div class="modal fade" id="f2f-modal" tabindex="-1">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
		      	<div class="modal-body">
			        <form id="add_schedule_f2f" class="d-flex flex-column row-gap-2 p-3" required>
                        <div class="mb-2 d-flex align-items-center justify-content-between">
					        <h5 class="modal-title">F2F Schedule Form</h5>
			        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					    </div>
					    <input type="text" name="schedule_setup_id" class="schedule_setup_id" hidden>

                        <div class="form-floating">
                            <select required class="form-select rounded-1" name="room_id" id="room_id">
                                <option hidden selected value="">Choose Option Below</option>
                                <?php
                                // PHP code to fetch courses from database
                                $sql = "SELECT * FROM room_tbl";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($rows) {
                                    foreach ($rows as $row):
                                        ?>
                                        <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_number']; ?></option>
                                    <?php
                                    endforeach;
                                }
                                ?>
                            </select>
                             <label for="room_id">Room:</label>
                        </div>

                        <div class="form-floating">
                            <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control rounded-1" placeholder="start time" required>
                            <label for="start_datetime">Start Time:</label>
                        </div>
                        <div class="form-floating">
                            <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control rounded-1" placeholder="start time" required>
                            <label for="end_datetime">End Time:</label>
                        </div>

                        <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
                    </form>
		      	</div>
	  		</div>
		</div>
	</div>

    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>
	<script>

		$(document).on('click', '.btn-online, .btn-f2f', function() {
		    var id = $(this).data('id');
		    $('.schedule_setup_id').val(id);
		});


		$(document).ready(function() {
		

		    $('#days').change(function() {
		        var selectedDay = $(this).val();

		        $('#time_id option').each(function() {
		            var dataDays = $(this).data('days');
		            if (selectedDay === dataDays) {
		                $(this).show();
		            } else {
		                $(this).hide();
		            }
		        });

		        // Enable the #time_id select element
		        $('#time_id').removeAttr('disabled');
		    });

		    // Reset form and disable #time_id on button click
		    $('.btn-close').click(function() {
		        $('#add_schedule_ol')[0].reset();
		        $('#time_id').prop('disabled', true);
		    });





		});
	</script>

<script>
$(document).ready(function() {
    // Set minimum value for start_datetime to current date and time
    $('#start_datetime').attr('min', getFormattedDateTime(new Date()));

    // Disable past dates and times in start_datetime
    $('#start_datetime').change(function() {
        var startDatetime = $(this).val();
        $('#end_datetime').attr('min', startDatetime);
    });

    // Example: Validate end_datetime is after start_datetime
    $('#end_datetime').change(function() {
        var startDatetime = $('#start_datetime').val();
        var endDatetime = $(this).val();
        if (startDatetime >= endDatetime) {
            alert('End time must be after start time.');
            $(this).val('');
        }
    });

    // Function to format datetime for min attribute
    function getFormattedDateTime(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
    }
});
</script>




</body>
</html>


