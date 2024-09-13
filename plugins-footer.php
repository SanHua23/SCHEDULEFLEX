  
  <!-- JQUERY -->
  <script src="plugins/jquery/jquery-3.6.0.min.js"></script>
  <!-- BOOTSTRAP 5 JS -->
  <script type="text/javascript" src="plugins/bootstrap5/bootstrap.min.js"></script>
  <!-- FONT AWESOME OFFLINE -->
  <script src="plugins/fontawesome/all.min.js" crossorigin="anonymous"></script>
  <!-- sweetalert2 -->
  <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
  <script type="text/javascript">


    $(document).ready(function() {
      //========AJAX register account Start=========//
      $('#register_account').submit(function(e) {
            e.preventDefault();

            $('.loader-container').removeClass('d-none');
            var registerData = $(this).serialize();

            // Submit AJAX request
            $.ajax({
                url: 'controller/register_account.php', // Adjust URL as per your file structure
                type: 'POST',
                data: registerData,
                dataType: 'json',
                success: function(response) {

                    if (response.status === 'exists') { 
                        Swal.fire({
                            position: "top-center",
                            icon: "error",
                            html: "<b>Email is already used!</b>",
                            showConfirmButton: true
                        });
                    } else if (response.status === 'success') {
                        $('.loader-container').addClass('d-none');
                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            html: "<b>Check your email for verification code!</b>",
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Show AJAX error message
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: "<b>AJAX Error: " + error + "</b>",
                        showConfirmButton: true
                    });
                }
            });
      });




      //========AJAX login account Start=========//
      $('#login_account').submit(function(e) {
            e.preventDefault();

            var loginData = $(this).serialize();

            // Submit AJAX request
            $.ajax({
                url: 'controller/login_account.php', // Adjust URL as per your file structure
                type: 'POST',
                data: loginData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'password') { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Wrong password!</b>",
                          showConfirmButton: true
                      });
                    } else if(response.status === 'email') { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Email not found!</b>",
                          showConfirmButton: true
                      });
                    } else if(response.status === 'verification') { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Email not verified!</b>",
                          showConfirmButton: true
                      });
                    } else if(response.status === 'pending') { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Sorry but your account not already Approved!</b>",
                          showConfirmButton: true
                      });
                    }  else if(response.status === 'student_number') { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Your Student Number is wrong!</b>",
                          showConfirmButton: true
                      });
                    } else if(response.status === 'success') {
                        if (response.user_role === '2') {
                            window.location.href = "faculty/faculty";
                        }else if (response.user_role === '3') {
                            window.location.href = "student/f2f-schedule";
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error)
                    // Show AJAX error message
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: "<b>AJAX Error: " + error + "</b>",
                        showConfirmButton: true
                    });
                }
            });
      });



      //========AJAX verified account Start=========//
      $('#verification').submit(function(e) {
            e.preventDefault();

            var verfiyData = $(this).serialize();

            // Submit AJAX request
            $.ajax({
                url: 'controller/verified_account.php', // Adjust URL as per your file structure
                type: 'POST',
                data: verfiyData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') { 
                        Swal.fire({
                          position: "top-center",
                          icon: "success",
                          html: "<b>Account has been verified!</b>",
                          showConfirmButton: true
                        }).then(() => {
                          window.location.href = 'login';
                        });
                    } else { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Verification code is incorrect!</b>",
                          showConfirmButton: true
                      });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error)
                    // Show AJAX error message
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: "<b>AJAX Error: " + error + "</b>",
                        showConfirmButton: true
                    });
                }
            });
      });



      //========AJAX verified account Start=========//
      $('#sendinstructions').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            $('.loader-container').removeClass('d-none');
            // Submit AJAX request
            $.ajax({
                url: 'controller/send-instruction.php', // Adjust URL as per your file structure
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('.loader-container').addClass('d-none');
                    if (response.status === 'success') { 
                        Swal.fire({
                          position: "top-center",
                          icon: "success",
                          html: "<b>Please check your email!</b>",
                          showConfirmButton: true
                        })
                    } else { 
                      Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Email not found!</b>",
                          showConfirmButton: true
                      });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error)
                    // Show AJAX error message
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: "<b>AJAX Error: " + error + "</b>",
                        showConfirmButton: true
                    });
                }
            });
      });


      //========AJAX forgotpassword Start=========//
      $('#forgotpassword').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            // Submit AJAX request
            $.ajax({
                url: 'controller/update_password.php', // Adjust URL as per your file structure
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {

                    if (response.status === 'success') { 
                        Swal.fire({
                          position: "top-center",
                          icon: "success",
                          html: "<b>Change Password Successfully!</b>",
                          showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'login';
                            }
                        });
                    } else if (response.status === 'cpassword') { 
                        Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>New Password and Confirm Password not Samed!</b>",
                          showConfirmButton: true
                      });
                    }else{
                        Swal.fire({
                          position: "top-center",
                          icon: "error",
                          html: "<b>Something Error!</b>",
                          showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error)
                    // Show AJAX error message
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: "<b>AJAX Error: " + error + "</b>",
                        showConfirmButton: true
                    });
                }
            });
      });


        $(document).ready(function() {
            $('#password').on('input', function() {
                $(this).val($(this).val().replace(/\s+/g, ''));
            });
        });



    });
  </script>