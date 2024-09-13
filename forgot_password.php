<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('location: admin/f2f-schedule');
    exit();
}
?>

<?php include 'plugins-header.php';?>

<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-11 col-sm-8 col-md-7 col-lg-6 col-xl-4 col-xxl-3 shadow bg-white rounded p-4">
        <form id="forgotpassword" class="col-12 p-5 px-lg-4 d-flex flex-column justify-content-center bg-white">
            <div class="mb-4 d-flex flex-column align-items-center">
                <h4 class="text-center fw-bold">Reset Password</h4>
            </div>
            <input required type="email" name="email" id="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : 'sample@gmail.com'; ?>" hidden>
            <small class="text-secondary pass-validation"><i class="icon-valid fa-regular fa-face-smile me-1"></i>New password must be at least 8 characters long.</small>
            <div class="form-floating mb-3">
                <input required type="password" name="new_password" id="new_password" class="form-control rounded-1" placeholder="New Password" oninput="preventWhitespace(event);">
                <label for="new_password">New Password</label>
            </div>

            <div class="form-floating mb-3">
                <input required type="password" name="confirm_password" id="confirm_password" class="form-control rounded-1" placeholder="Confirm Password" oninput="checkPasswords(); preventWhitespace(event);">
                <label for="confirm_password">Confirm Password</label>
                <div id="passwordFeedback" style="color: red; display: none;">Passwords do not match.</div>
            </div>

            <div class="mb-3">
                <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> 
                <label for="showPassword">Show Passwords</label>
            </div>

            <input type="submit" class="btn btn-warning fw-bold shadow" value="Change Password">
        </form>
   </div>
</div>
<?php include 'plugins-footer.php' ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function checkPasswords() {
    var newPassword = $('#new_password').val();
    var confirmPassword = $('#confirm_password').val();

    if (newPassword !== confirmPassword) {
        $('#passwordFeedback').show();
    } else {
        $('#passwordFeedback').hide();
    }
}

function togglePasswordVisibility() {
    var newPassword = document.getElementById("new_password");
    var confirmPassword = document.getElementById("confirm_password");
    if (newPassword.type === "password" || confirmPassword.type === "password") {
        newPassword.type = "text";
        confirmPassword.type = "text";
    } else {
        newPassword.type = "password";
        confirmPassword.type = "password";
    }
}
function preventWhitespace(event) {
    var input = event.target;
    input.value = input.value.replace(/\s/g, '');
}

$(document).ready(function() {
    $('#sendinstructions').on('submit', function(event) {
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();

        if (newPassword !== confirmPassword) {
            event.preventDefault();
            alert("Passwords do not match.");
        }
    });


    $('#new_password').on('input', function(event) {
            var password = $('#new_password').val();
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
});
</script>
