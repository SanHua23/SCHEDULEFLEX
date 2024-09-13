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
	        		<form id="add_ay"  class="border d-flex flex-column row-gap-2 bg-white p-3">
					    <div class="mb-2 d-flex align-items-center justify-content-between">
					        <strong class="form-title">Set Academic Year Form</strong>
					        <button type="button" class="btn btn-sm btn-danger fw-bold d-none clear">Clear update</button>
					    </div>
					    <input type="text" name="ay_id" id="ay_id" class="form-control rounded-1" value="0" hidden>
					    <div class="form-floating">
					        <input required type="number" name="start_year" id="start_year" class="form-control rounded-1" placeholder="Type start year here" min="2024">
					        <label for="start_year">Start Year</label>
					    </div>
					    <div class="form-floating">
					        <input required type="number" name="end_year" id="end_year" class="form-control rounded-1" placeholder="Type end year here" min="2024">
					        <label for="end_year">End Year</label>
					    </div>
					    <button type="submit" class="save-button btn btn-primary fw-bold">Save</button>
					</form>

	        	</div>
	        	<div class="col-12 col-lg-8">
	        		<div class="border bg-white d-flex flex-column row-gap-2 p-3">
	        			<div class="d-flex align-items-center justify-content-between">
	        				<strong class="mb-2">List of Academic Year</strong>
	        				<div>
	        					<input type="text" id="search-bar" class="form-control form-control-sm rounded-1 mb-3" placeholder="Search here">
	        				</div>
	        			</div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="ay_table" class="table table-bordered table-striped">
			                    <thead class="position-sticky top-0">
			                        <tr class="py-5">
			                            <th>#</th>
			                            <th>Start Year</th>
			                            <th>End Year</th>
			                            <th>Action</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
	                                    $sql = "SELECT * FROM ay_tbl";
	                                    $stmt = $conn->prepare($sql);
	                                    $stmt->execute();
	                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                    $row_count = 1;
	                                    if ($rows) {
	                                        foreach ($rows as $row):
	                                ?>
								    <tr data-id="<?php echo $row['ay_id']; ?>">
								        <td width="50"><?php echo $row_count++?></td>
								        <td><?php echo $row['start_year']; ?></td>
								        <td><?php echo $row['end_year']; ?></td>
								        <td width="150">
								        	<div class="d-flex align-items-center column-gap-2">
								        		<div role="button" class="text-warning update_data">
								        			<i class="fa-solid fa-pen-to-square"></i>
								        		</div>
								        		<div role="button" class="text-danger delete_data" data-id="<?php echo $row['ay_id']; ?>" data-table="ay_tbl" data-type="ay_id" >
								        			<i class="fa-solid fa-trash"></i>
								        		</div>
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
    <!-- Include plugins-footer.php or necessary JS files directly -->
   	<?php include "plugins-footer.php"; ?>


</body>
</html>


