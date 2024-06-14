<?php
    session_start();
    require_once 'db_connection.php';
    $emailError = false;
    $pinError = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize input
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $pin = htmlspecialchars($_POST['pin']);
   
        try {
            // Prepare statement to get user by email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Verify user and password
            if ($user && $pin == $user['password']) {
                // Successful login, redirect to dashboard or home page
                $_SESSION['userid'] = $user['user_id'];
                header("Location: dashboard.php");
                exit();
            } else {
                // Failed login
                $pinError = true;
            }
        } catch (PDOException $e) {
            error_log("Signin error: " . $e->getMessage(), 0);
            $pinError = true;
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

<body class="auth-body">
  <!-- header starts -->
  <div class="auth-header">
    <a href="index.html"> <i class="back-btn" data-feather="arrow-left"></i> </a>

    <img class="img-fluid img" src="assets/images/authentication/1.svg" alt="v1" />

    <div class="auth-content">
      <div>
        <h2>Welcome back !!</h2>
        <h4 class="p-0">Enter your details to login</h4>
      </div>
    </div>
  </div>
  <!-- header end -->

  <!-- login section start -->
  <form class="auth-form" method="POST">
    <div class="custom-container">
      <div class="form-group">
        <label class="form-label">Email address</label>
        <div class="form-input">
          <input type="email" class="form-control" name="email" placeholder="Enter Your Email" />
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Pin</label>
        <div class="form-input">
          <input type="password" class="form-control" id="newpin" name="pin" maxlength="4" placeholder="Enter Your pin" />
        </div>
      </div>
      <div class="remember-option mt-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
          <label class="form-check-label" for="flexCheckDefault">Remember me</label>
        </div>
        <a class="forgot" href="forgot-password.php">Forgot Pin?</a>
      </div>

      <button type="submit" class="btn theme-btn w-100">Sign In</button>
      <h4 class="signup">Don’t have an account ?<a href="signup.php"> Sign up</a></h4>
      
    </div>
  </form>
  <!-- login section start -->

   <!-- error modal starts -->
   <div class="modal fade" id="error" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/decline.png" style="width:30%;" alt="error" />
                    </div>
                    <h3>Invalid Credentials.</h3>     
                    <a href="signin.php" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try
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
    document.getElementById('newpin').addEventListener('input', function (e) {
        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4); // Trim to the first 4 characters
        }
    });

     // Check if there's a pin error and show the modal
     <?php if ($pinError) : ?>
        window.onload = function () {
            $('#error').modal('show');
        }
    <?php endif; ?>

    // Check if there's a pin error and show the modal
    <?php if ($emailError) : ?>
        window.onload = function () {
            $('#errorEmail').modal('show');
        }
    <?php endif; ?>
  </script>
</body>

</html>