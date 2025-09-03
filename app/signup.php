<?php
  require_once 'db_connection.php';
  $pinError = false;
  $emailError = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Retrieve and sanitize input
      $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
      $pin = htmlspecialchars($_POST['pin']);
      $confirm_pin = htmlspecialchars($_POST['pin1']);
      $fullname = htmlspecialchars($_POST['fullname']);
      $account_type = htmlspecialchars($_POST['account_type']);

      // Check if the pins match
      if ($pin !== $confirm_pin)
      {
          $pinError = true;
      }
      else
      {               

          try {

              // Check if the email already exists
              $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
              $stmt->execute([$email]);
              $emailExists = $stmt->fetchColumn();

              if ($emailExists)
              {
                  $emailError = true;
              }
              else
              {
                // Begin transaction
                $pdo->beginTransaction();

                // Insert user data into the users table
                $stmt = $pdo->prepare("INSERT INTO users (email, password, fullname) VALUES (?, ?, ?)");
                $stmt->execute([$email, $pin, $fullname]);

                // Retrieve the user_id of the newly created user
                $user_id = $pdo->lastInsertId();

                // Generate a unique account number
                do {
                    $account_number = '392011' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE account_number = ?");
                    $stmt->execute([$account_number]);
                    $accountExists = $stmt->fetchColumn();
                } while ($accountExists);

                // Insert account data into the accounts table
                $stmt = $pdo->prepare("INSERT INTO accounts (user_id, account_number, account_type, balance) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $account_number, $account_type, 0.00]);

                // Commit transaction
                $pdo->commit();

                // Redirect to login page after successful signup
                header("Location: success_signup.php");
              }            
          } catch (PDOException $e) {
              // Rollback transaction in case of an error
              $pdo->rollBack();
              error_log("Signup error: " . $e->getMessage(), 0);
              echo "Signup error: " . $e->getMessage();
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
    <meta name="description" content="CapitalVentures" />
    <meta name="keywords" content="CapitalVentures" />
    <meta name="author" content="CapitalVentures" />
    <link rel="manifest" href="./manifest.json" />
    <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon" />
    <title>CapitalVentures App</title>
    <link rel="apple-touch-icon" href="assets/images/logo/favicon.png" />
    <meta name="theme-color" content="#122636" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="CapitalVentures" />
    <meta name="msapplication-TileImage" content="assets/images/logo/favicon.png" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />

    <!-- iconsax css -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/iconsax.css" />


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
        <a href="signin.php"> <i class="back-btn" data-feather="arrow-left"></i> </a>

        <img class="img-fluid img" src="assets/images/authentication/6.svg" alt="v1" />

        <div class="auth-content">
            <div>
                <h2>Welcome to CapitalVentures!</h2>
                <h4 class="p-0">Fill up the form to open an account.</h4>
            </div>
        </div>
    </div>
    <!-- header end -->

    <!-- login section start -->
    <form class="auth-form" method="post" action="signup.php">
        <div class="custom-container">
            <div class="form-group">
                <label for="inputname" class="form-label">Full name</label>
                <div class="form-input">
                    <input type="text" class="form-control" id="inputname" name="fullname" placeholder="Enter your name" required />
                </div>
            </div>

            <div class="form-group">
                <label for="inputemail" class="form-label">Email address</label>
                <div class="form-input">
                    <input type="email" class="form-control" id="inputemail" name="email" placeholder="Enter Your Email" required />
                </div>
            </div>

            <div class="form-group">
                <label for="inputaccounttype" class="form-label">Account Type</label>
                <div class="form-input">
                    <select id="inputaccounttype" name="account_type" class="form-select" required>
                        <option value="Savings" selected>Savings</option>
                        <option value="Checking">Checking</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="newpin" class="form-label">Enter login pin</label>
                <div class="form-input">
                    <input type="password" id="newpin" class="form-control" name="pin" placeholder="Enter a 4-digit pin" maxlength="4" required />
                </div>
            </div>

            <div class="form-group">
                <label for="confirmpin" class="form-label">Re-enter login pin</label>
                <div class="form-input">
                    <input type="password" id="confirmpin" class="form-control" name="pin1" placeholder="Re-enter your 4-digit pin" maxlength="4" required />
                </div>
            </div>
            <div class="remember-option mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required />
                    <label class="form-check-label" for="flexCheckDefault">I agree to all terms & conditions</label>
                </div>
            </div>

            <button type="submit" class="btn theme-btn w-100">Sign up</button>
            <h4 class="signup">Already have an account? <a href="signin.php">Sign in</a></h4>
        </div>
    </form>
    <!-- login section end -->

    <!-- error modal starts -->
    <div class="modal fade" id="error" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/cloud.png" style="width:30%;" alt="error" />
                    </div>
                    <h3>Both Passwords do not match.</h3>
                    <a href="signup.php" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try
                        again</a>
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts -->

    <!-- error modal starts -->
    <div class="modal fade" id="errorEmail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body" align="center">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/authentication/email.png" width="100px;" alt="error" />
                    </div>
                    <h3>A user already has this email registered.</h3>
                    <a href="signup.php" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try
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

        document.getElementById('confirmpin').addEventListener('input', function (e) {
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
