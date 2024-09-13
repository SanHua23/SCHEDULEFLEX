<?php
// session_start();
// include 'connect.php';



// if (isset($_SESSION['role'])) {
// 	if ($_SESSION['role'] === 'admin') {
// 		header('location: admin/dashboard');
// 	}else if ($_SESSION['role'] === 'teller') {
// 		header('location: teller/records');
// 	}
// }

?>

<?php include 'plugins-header.php';?>
   
<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-10 col-sm-8 col-md-6 col-xl-6 col-xxl-5 row mx-0 shadow-lg bg-white rounded flex-column-reverse flex-lg-row px-4">
        <div class="col-12 col-xl-6 d-none d-xl-flex px-0">
            <img src="images/logo.png" class="rounded-end m-auto" height="250" width="250">
        </div>
        <form id="register_account" class="col-12 col-xl-6 p-5 px-lg-4 d-flex flex-column justify-content-center bg-white">
            <div class="mb-4 d-flex flex-column align-items-center">
                <h4 class="text-center fw-bold">Register</h4>
                <div class="text-secondary">Create new account</div>
            </div>
            <div class="input-group mb-3 column-gap-2">
              <input type="text" class="form-control reg-fullname py-2" name="first_name" placeholder="your first name" required>
              <input type="text" class="form-control reg-fullname py-2" name="last_name" placeholder="your last name" required>
            </div>
            <!-- <small id="email-error" style="color: red; display: none;">Invalid email domain. Use @iskolarngbayan.pup.edu.ph</small> -->
            <div class="input-group mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="sample@gmail.com" required>
                <div class="input-group-append">
                    <div class="py-1 px-3 fs-5 border">
                        <span class="fas fa-solid fa-envelope"></span>
                    </div>
                </div>
            </div>
            <small class="text-secondary pass-validation"><i class="icon-valid fa-regular fa-face-smile me-1"></i>Password must be at least 8 characters long.</small>
            <div class="input-group">
                <input type="password" class="form-control" name="password"  id="password" placeholder="create password" required>
                <div class="input-group-append">
                    <div class="py-1 px-3 fs-5 border">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center my-3 column-gap-2">
                <div class="input-group">
                   <select class="form-select py-2" name="user_role" required>
                        <option selected hidden value="">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                   </select>
                </div>
                <div class="input-group">
                   <select class="form-select py-2" name="user_role" id="user_role" required>
                        <option selected hidden value="">Select Role</option>
                        <option value="2">Faculty</option>
                        <option value="3">Student</option>
                   </select>
                </div>
            </div>
            <div class="input-group mb-3 d-none" id="student_number_container">
                <input type="text" class="form-control" name="student_number" placeholder="Student Number">
            </div>
            <input type="submit" class="btn btn-warning fw-bold shadow" id="submit" value="REGISTER" disabled>
            <div class="mt-4 text-center">
                <small>Already had account? <a href="login">Login Here</a></small>
            </div>
      </form>

   </div>
</div>

<?php include 'loader.php' ?>
<?php include 'plugins-footer.php' ?>

      <script>
//         $(document).ready(function() {
//     $('#email').on('input', function() {
//         var emailValue = $(this).val();
//         var validDomain = "@iskolarngbayan.pup.edu.ph";
//         var emailError = $('#email-error');

//         if (emailValue.endsWith(validDomain)) {
//             emailError.hide();
//             $(this)[0].setCustomValidity('');
//         } else {
//             emailError.show();
//             $(this)[0].setCustomValidity('Invalid email domain');
//         }
//     });
// });
        $(document).ready(function() {
            $('#user_role').on('change', function() {
                var value = $(this).val();
                if (value === '3') {
                    $('#student_number_container').removeClass('d-none');
                    $('#student_number_container input').attr('required', true);
                } else {
                    $('#student_number_container').addClass('d-none');
                    $('#student_number_container input').removeAttr('required');
                }
            });

            $('#password').on('input', function(event) {
                var password = $('#password').val();
                var validationMessage = $('.pass-validation');
                var fa_regular = $('.icon-valid');
                if (password.length === 0) {
                    validationMessage
                        .addClass('text-secondary')
                        .removeClass('text-danger text-success');
                    fa_regular
                        .addClass('fa-face-smile')
                        .removeClass('fa-circle-check fa-circle-xmark');
                    $('#submit').prop('disabled', true);
                } else if (password.length < 8) {
                    validationMessage
                        .addClass('text-danger')
                        .removeClass('text-success text-secondary');
                    fa_regular
                        .addClass('fa-circle-xmark')
                        .removeClass('fa-circle-check fa-face-smile');
                    $('#submit').prop('disabled', true);
                } else {
                    validationMessage
                        .addClass('text-success')
                        .removeClass('text-danger text-secondary');
                    fa_regular
                        .addClass('fa-circle-check')
                        .removeClass('fa-circle-xmark fa-face-smile');
                    $('#submit').prop('disabled', false);
                }
            });

            $('.reg-fullname').on('keypress', function(event) {
                if (event.which === 32 && $(this).val().length === 0) {
                    event.preventDefault();
                }
            });

        });
    </script>