<?php
session_start();


if (isset($_SESSION['user']['admin_id'])) {
	header('location: admin/course');
}

?>

<?php include 'plugins-header.php';?>
   
<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-10 col-sm-8 col-xl-7 col-xxl-5 row mx-0">
        <form id="login_admin" class="col-12 col-xl-6 p-5 px-lg-4 mx-auto d-flex flex-column justify-content-center bg-white shadow">
    	      	<div class="mb-4 d-flex flex-column align-items-center">
    	            <h4 class="text-center fw-bold">Admin - Login</h4>
    	        </div>
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="username" id="username" placeholder="username" required>
                        <label for="username">Username</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" id="password" placeholder="password" required>
                        <label for="password">Password</label>
                    </div>
                </div>
                <input type="submit" class="btn btn-warning fw-bold shadow" value="LOGIN">
        </form>
   </div>
</div>

<?php include 'plugins-footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#login_admin').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                      url: '../controller/login_admin.php',
                      type: 'post',
                      data: formData,
                      dataType: 'json',
                      success: function(output) {

                        if (output.status === 'success') {
                            window.location.href = "index"
                        } else if (output.status === 'username') {
                          Swal.fire({
                              position: "top-center",
                              icon: "error",
                              html: "<b>Username is Incorrect.</b>",
                              showConfirmButton: true
                          });
                        } else if (output.status === 'password') {
                          Swal.fire({
                              position: "top-center",
                              icon: "error",
                              html: "<b>Your Password is Incorrect.</b>",
                              showConfirmButton: true
                          });
                        }
                    }
                });
            });

        });
    </script>