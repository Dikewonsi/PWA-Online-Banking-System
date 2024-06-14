<?php
    require_once 'db_connection.php';
    $resetModal = false;
    $emailSendFailedModal = false;
    $noUserModal = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        try
        {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $reset_token = bin2hex(random_bytes(50));
                $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
                $stmt->execute([$reset_token, $reset_expiry, $email]);

                $reset_link = "http://capitalvista.net/app/reset-password.php?token=$reset_token";
                $subject = "Password Reset Request";

                $message = "
                    <html>
                    <head>
                        <title>Password Reset Request</title>
                    </head>
                    <body>
                        <p>Dear {$user['fullname']},</p>
                        <p>We received a request to reset your password. Click the link below to reset your password:</p>
                        <p><a href='$reset_link'>Reset Password</a></p>
                        <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
                        <p>Thanks,<br>The Support Team</p>
                    </body>
                    </html>
                ";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: no-reply@yourdomain.com' . "\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    // echo "Password reset link has been sent to your email.";
                    $resetModal = true;
                } else {
                    // echo "Failed to send reset email.";
                    $emailSendFailedModal = true;
                }
            } else {
                // echo "No user found with this email address.";
                $noUserModal = true;
            }
        } catch (PDOException $e) {
            error_log("Forgot password error: " . $e->getMessage(), 0);
            echo "An error occurred. Please try again.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="mpay" />
  <meta name="keywords" content="mpay" />
  <meta name="author" content="mpay" />
  <link rel="manifest" href="./manifest.json" />
  <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon" />
  <title>mPay App</title>
  <link rel="apple-touch-icon" href="assets/images/logo/favicon.png" />
  <meta name="theme-color" content="#122636" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="mpay" />
  <meta name="msapplication-TileImage" content="assets/images/logo/favicon.png" />
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <!--Google font-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />

  <!-- bootstrap css -->
  <link rel="stylesheet" id="rtl-link" type="text/css" href="assets/css/vendors/bootstrap.min.css" />

  <!-- swiper css -->
  <link rel="stylesheet" type="text/css" href="assets/css/vendors/swiper-bundle.min.css" />

  <!-- Theme css -->
  <link rel="stylesheet" id="change-link" type="text/css" href="assets/css/style.css" />
</head>

<body>
  <!-- header starts -->
  <div class="auth-header">
    <a href="signin.php"> <i class="back-btn" data-feather="arrow-left"></i> </a>

    <img class="img-fluid img" src="assets/images/authentication/3.svg" alt="v1" />

    <div class="auth-content">
      <div>
        <h2>Forget your pin</h2>
        <h4 class="p-0">Don’t worry !</h4>
      </div>
    </div>
  </div>
  <!-- header end -->

  <!-- login section start -->
  <form class="auth-form" method="POST">
    <div class="custom-container">
      <div class="form-group">
        <h5>To reset your pin, enter your registered email.</h5>
        <label for="inputusername" class="form-label">Email</label>
        <div class="form-input">
          <input type="email" class="form-control" name="email" placeholder="Enter Your Email" />
        </div>
      </div>

      <button type="submit" class="btn theme-btn w-100">Recover pin</button>
    </div>
  </form>
  <!-- login section start -->

  <!-- error modal starts -->
  <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Success</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/verified.png" style="width:30%;" alt="error" />
                    </div>
                    <br>
                    <h3>A reset link has been sent to your email address, please check your inbox.</h3>                                     
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts -->   

    <!-- error modal starts -->
    <div class="modal fade" id="emailSendFailedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/decline.png" style="width:30%;" alt="error" />
                    </div>
                    <br>
                    <h3>Email was not sent.</h3>                                     
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts -->  
     
    <!-- error modal starts -->
    <div class="modal fade" id="noUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/no-spam.png" style="width:30%;" alt="error" />
                    </div>
                    <br>
                    <h3>No User with that email found.</h3>   
                    <a href="forgot-password.php" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try
                        again</a>                                  
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts --> 
    

  <!-- feather js -->
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/custom-feather.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>

  <script>
   // Check if there's an error and show the modal
     <?php if ($resetModal) : ?>
        window.onload = function () {
            $('#successModal').modal('show');
        }
    <?php endif; ?> 
    
    // Check if there's an error and show the modal
    <?php if ($emailSendFailedModal) : ?>
        window.onload = function () {
            $('#emailSendFailedModal').modal('show');
        }
    <?php endif; ?> 

     // Check if there's an error and show the modal
     <?php if ($noUserModal) : ?>
        window.onload = function () {
            $('#noUserModal').modal('show');
        }
    <?php endif; ?> 
  </script>
</body>

</html>