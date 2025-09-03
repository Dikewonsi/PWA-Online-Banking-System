<?php 
    session_start();
    include('db_connection.php');    

    if(!isset($_SESSION['userid']))
    {
      header("location:signin.php");
      exit();
    }

    $userid = $_SESSION['userid'];

    //PINs do not match
    $errorModal = false;

    //Invalid OLD PIN
    $errorModal1 = false;

    //Successful Update
    $successModal = false;
    
    //Collect Form Details
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $old_pin = htmlspecialchars($_POST['old_pin']);
        $new_pin = htmlspecialchars($_POST['new_pin']);
        $confirm_pin = htmlspecialchars($_POST['confirm_pin']);
        
        if ($new_pin !== $confirm_pin)
        {
            $errorModal = true;
            $errorMessage = 'New PIN and Confirm PIN do not match.';
        }
        else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :userid");
                $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && $user['password'] === $old_pin) {
                    // Update the password (PIN)
                    $update_stmt = $pdo->prepare("UPDATE users SET password = :new_pin WHERE user_id = :userid");
                    $update_stmt->bindParam(':new_pin', $new_pin, PDO::PARAM_STR);
                    $update_stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $update_stmt->execute();

                    $successModal = true;


                } else {
                    $errorModal1 = true;
                    $errorMessage = 'Invalid old PIN.';
                }
            } catch (PDOException $e) {
                error_log("Query failed: " . $e->getMessage(), 0);
                $errorModal = true;
                $errorMessage = 'An error occurred. Please try again later.';
            }
        }
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Capital Ventures" />
  <meta name="keywords" content="Capital Ventures" />
  <meta name="author" content="Capital Ventures" />
  <link rel="manifest" href="./manifest.json" />
  <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon" />
  <title>Capital Ventures App</title>
  <link rel="apple-touch-icon" href="assets/images/logo/favicon.png" />
  <meta name="theme-color" content="#122636" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="Capital Ventures" />
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
      <form class="auth-form pt-0" action="" method="POST">
        <div class="form-group">
          <label for="inputoldpassword" class="form-label">Old PIN</label>
          <input type="password" class="form-control" id="newpin" name="old_pin" placeholder="Enter your old pin"  maxlength="4" required />
        </div>

        <div class="form-group">
          <label for="inputnewpassword" class="form-label">New PIN</label>
          <input type="password" class="form-control" id="confirmpin" name="new_pin" placeholder="Enter your new pin"  maxlength="4" required />
        </div>

        <div class="form-group">
          <label for="inputconfirmpassword" class="form-label">Confirm PIN</label>
          <input type="password" class="form-control" id="inputconfirmpassword" name="confirm_pin"
            placeholder="Confirm your new pin"  maxlength="4" required />
        </div>

        <button class="btn theme-btn w-100">Update PIN</button>
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
                        <img class="img-fluid" src="assets/images/authentication/wrong-password.png" style="width:30%;" alt="error" />
                    </div>
                    <br>
                    <h3>Your two passwords do not match. Please try again.</h3>                                     
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts -->

    <!-- error modal starts -->
   <div class="modal fade" id="errorModal1" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/wrong-password.png" style="width:30%;" alt="error" />
                    </div>
                    <br>
                    <h3>Your old pin is incorrect. Please try again.</h3>                                     
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts -->

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
                    <h3>Your transaction pin has been changed successfully.</h3>                                     
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
    
    // Check if there's an error and show the modal
    <?php if ($errorModal1) : ?>
        window.onload = function (){
            $('#errorModal1').modal('show');
        }
    <?php endif; ?> 

    // Check if there's an error and show the modal
    <?php if ($successModal) : ?>
        window.onload = function (){
            $('#successModal').modal('show');
        }
    <?php endif; ?> 
  </script>
  <script>
      document.getElementById('newpin').addEventListener('input', function (e) {
          if (this.value.length > 4) {
              this.value = this.value.slice(0, 4); // Trim to the first 4 characters
          }
      });

      document.getElementById('confirmpin').addEventListener('input', function (e) {
          if (this.value.length > 4) {
              this.value = this.value.slice(0, 4); // Trim to the first 4 characters
          }
      });
  </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var pinInputs = document.querySelectorAll('input[type="password"]');
            
            pinInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Replace non-numeric characters with an empty string
            });
            });
        });
    </script>
</body>

</html>