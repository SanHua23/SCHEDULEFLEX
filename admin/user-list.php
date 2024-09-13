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
	        	<div class="col-12">
	        		<div class="border bg-white d-flex flex-column row-gap-2 p-3">
	        			<div class="d-flex align-items-center justify-content-between">
	        				<strong class="mb-2">List of User</strong>
	        				<div>
	        					<input type="text" id="search-bar" class="form-control form-control-sm rounded-1 mb-3" placeholder="Search here">
	        				</div>
	        			</div>
		        		
		        		<div style="max-height: 70vh; overflow-y: auto;">
			        		<table id="user_table" class="table table-bordered table-striped">
			                    <thead class="position-sticky top-0">
			                        <tr class="py-5">
			                            <th>#</th>
			                            <th>Full Name</th>
			                            <th>Email</th>
			                            <th>Role</th>
			                            <th>Status</th>
			                            <th>Action</th>
			                            <!-- <th class="col-auto text-center">Action</th> -->
			                        </tr>
			                    </thead>
			                    
							    <tbody>
							    	<?php
	                                    $sql = "SELECT * FROM user_tbl";
	                                    $stmt = $conn->prepare($sql);
	                                    $stmt->execute();
	                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	                                    $row_count = 1;
	                                    if ($rows) {
	                                        foreach ($rows as $row):
	                                ?>
								    <tr data-id="<?php echo $row['user_id']; ?>">
								        <td width="50"><?php echo $row_count++?></td>
								        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
								        <td><?php echo $row['email']; ?></td>
								        <td>
								        	<?php 
								        		switch($row['user_role']){
								        			case '2' : echo "Teacher"; break;
								        			case '3' : echo "Student"; break;
								        			case null : echo "n/a"; break;
								        		}
								         	?>
								        </td>
								        <td>
								        	<?php 
								        		switch($row['status']){
								        			case "approved" : echo "<span class='badge text-bg-success'>Approved</span>"; break;
								        			case "disapproved" : echo "<span class='badge text-bg-danger'>Disapproved</span>"; break;
								        			case "pending" : echo "<span class='badge text-bg-warning'>Pending</span>"; break;

								        		}
								        	?>
								        		
								        	</td>
								        <td width="150">
								        	<div class="d-flex align-items-center column-gap-2">
								        		<div role="button" class="text-warning update_status" data-id="<?php echo $row['user_id']; ?>">
								        			<i class="fa-solid fa-pen-to-square"></i>
								        		</div>
								        		<div role="button" class="text-danger delete_data" data-id="<?php echo $row['user_id']; ?>" data-table="user_tbl" data-type="user_id" >
								        			<i class="fa-solid fa-trash"></i>
								        		</div>
								        	</div>
								        </td>
								    </tr>
								    <?php endforeach; }else{?>
							    	<tr>
							    		<td colspan="100" class="text-center">No User Register</td>
							    	</tr>
								    <?php }?>
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

   	<script type="text/javascript">
   		$('.update_status').click(async function() {
            var user_id = $(this).data('id');

            const inputOptions = new Promise((resolve) => {
                setTimeout(() => {
                    resolve({
                        "approved": "Approved",
                        "disapproved": "Disapproved"
                    });
                }, 300);
            });

            const { value: status } = await Swal.fire({
                title: "Select Status",
                input: "radio",
                inputOptions: await inputOptions,
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return "You need to choose something!";
                    }
                }
            });

            if (status) {
                // Make the AJAX call here
                $.ajax({
                    url: '../controller/update_status.php', // Replace with your server endpoint
                    type: 'POST',
                    data: {
                        user_id: user_id,
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {

                        	// Update the existing row
		                    var row = $(`#user_table tbody tr[data-id="${user_id}"]`);
                    		row.find('td:nth-child(5)').text(status);

                            Swal.fire({
                                title: 'Success',
                                text: `The user has been ${status}.`,
                                icon: 'success'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: `There was an error updating the status: ${error}`,
                            icon: 'error'
                        });
                    }
                });
            }
        });
   	</script>
</body>
</html>


