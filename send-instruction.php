<?php
session_start();


if (isset($_SESSION['user_id'])) {
	header('location: admin/f2f-schedule');
}

?>

<?php include 'plugins-header.php';?>
   
<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-11 col-sm-8 col-md-7 col-lg-6 col-xl-4 col-xxl-3 shadow bg-white rounded p-4">
        <form id="sendinstructions" class="col-12 p-5 px-lg-4 d-flex flex-column justify-content-center bg-white">
    	      	<div class="mb-4 d-flex flex-column align-items-center">
    	            <h4 class="text-center fw-bold">Forgot Password</h4>
    	        </div>
                <div class="input-group form-text">
                    Enter your email address and we will send you a link to reset your password.
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="email" placeholder="email" required>
                    <div class="input-group-append">
                        <div class="py-2 px-3 fs-5 border">
                            <span class="fas fa-solid fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-warning fw-bold shadow" value="Send Instruction">
        </form>
   </div>
</div>
<?php include 'loader.php' ?>
<?php include 'plugins-footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#user_role').on('change', function(event) {
                var value = $(this).val();
                if (value === '3') {
                    $('#student_number').removeClass('d-none');
                } else {
                    $('#student_number').addClass('d-none');
                }
            });

        });
    </script>