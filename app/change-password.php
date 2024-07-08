<?php 
    session_start();
    include('db_connection.php');    

    if(!isset($_SESSION['userid']))
    {
      header("location:signin.php");
      exit();
    }

    $userid = $_SESSION['userid'];

    $errorModal = false;

    //Collect Form Details
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
      $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

      // Check if the email already exists
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
      $stmt->execute([$email]);
      $emailExists = $stmt->fetchColumn();

      if ($emailExists)
      {          
          header("Location: change-new-password.php");
          exit();
      }
      else
      {
        $errorModal = true;
      }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="CapitalVista" />
  <meta name="keywords" content="CapitalVista" />
  <meta name="author" content="CapitalVista" />
  <link rel="manifest" href="./manifest.json" />
  <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon" />
  <title>CapitalVista App</title>
  <link rel="apple-touch-icon" href="assets/images/logo/favicon.png" />
  <meta name="theme-color" content="#122636" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="CapitalVista" />
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
  <!-- header start -->
  <header class="section-t-space">
    <div class="custom-container">
      <div class="header-panel">
        <a href="profile.php" class="back-btn">
          <i class="icon" data-feather="arrow-left"></i>
        </a>
        <h2>Change Password</h2>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- change password section start -->
  <section>
    <div class="custom-container">
      <h4 class="fw-normal light-text lh-base">
        Enter your registered email to change your PIN.
      </h4>
      <form class="auth-form pt-0 mt-3" action="" method="POST">
        <div class="form-group">
            <label for="inputContact" class="form-label">Email Address</label>
            <input type="text" class="form-control" id="inputContact" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn theme-btn w-100">Send Verification Code</button>
    </form>
    </div>
  </section>
  <!-- change password section start -->

  <!-- error modal starts -->
  <div class="modal fade" id="errorModal" tabindex="-1">
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
                    <h3>Your email does not match our records. Please try again.</h3>                                     
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

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
  // Check if there's an error and show the modal
  <?php if ($errorModal) : ?>
      window.onload = function (){
          $('#errorModal').modal('show');
      }
  <?php endif; ?>     
  </script>  
</body>

</html>