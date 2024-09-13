<?php
session_start();

if (isset($_SESSION['user']['admin_id'])) {
    header('Location: admin/course');
    exit; // Ensure no further code is executed
} elseif (isset($_SESSION['user_id']) && $_SESSION['user_role'] === '2') {
    header('Location: faculty/faculty');
    exit; // Ensure no further code is executed
} elseif (isset($_SESSION['user_id']) && $_SESSION['user_role'] === '3') {
    header('Location: student/f2f-schedule');
    exit; // Ensure no further code is executed
}

include 'plugins-header.php';
?>

<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-10 col-sm-8 col-xl-7 col-xxl-5 row mx-0 shadow-lg bg-white rounded flex-column-reverse flex-lg-row p-4">
        <div class="col-12 col-xl-6 d-none d-xl-flex px-0">
            <img src="images/logo.png" class="rounded-end m-auto" height="250" width="250">
        </div>
        <form id="login_account" class="col-12 col-xl-6 p-5 px-lg-4 d-flex flex-column justify-content-center bg-white">
                <div class="mb-4 d-flex flex-column align-items-center">
                    <h4 class="text-center fw-bold">Login</h4>
                    <div class="text-secondary">Access to our dashboard</div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="email" placeholder="email" required>
                    <div class="input-group-append">
                        <div class="py-2 px-3 fs-5 border">
                            <span class="fas fa-solid fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="py-2 px-3 fs-5 border">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                   <select class="form-select py-2" name="user_role" id="user_role" required>
                        <option selected hidden value="">Select Role</option>
                        <option value="2">Faculty</option>
                        <option value="3">Student</option>
                   </select>
                </div>
                <div class="input-group mb-3 d-none" id="student_number_container">
                    <input type="text" class="form-control" name="student_number" placeholder="Student Number">
                </div>
                <input type="submit" class="btn btn-warning fw-bold shadow" value="LOGIN">
                <div class="mt-4 d-flex align-items-center justify-content-between">
                    <small><a href="send-instruction" target="_blank">Forgot Password?</a></small>
                    <small><a href="register">Register Here</a></small>
                </div>
        </form>
   </div>
</div>

<?php include 'plugins-footer.php'; ?>
<script>
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
    });
</script>
