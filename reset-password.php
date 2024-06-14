<?php
require_once 'db_connection.php';
$expiredTokenModal = false;
$passwordErrorModal = false;
$successModal = false;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $reset_token = $_GET['token'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
        $stmt->execute([$reset_token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);        
        if ($user) {
            // Token is valid and not expired, show reset form
        } else {
            $expiredTokenModal = true;
        }
    } catch (PDOException $e) {
        error_log("Token validation error: " . $e->getMessage(), 0);
        echo "An error occurred. Please try again.";
        exit;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'], $_POST['new_password'], $_POST['confirm_password'])) {
    $reset_token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $passwordErrorModal = true;
    }
    else
    {
        try
        {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
            $stmt->execute([$reset_token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {             
                $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
                $stmt->execute([$new_password, $reset_token]);

                $successModal = true;
            } else {
                $expiredTokenModal = true;
            }
        } catch (PDOException $e) {
            error_log("Password reset error: " . $e->getMessage(), 0);
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
        <div class="auth-header">
            <a href="forgot-password.php"><i class="back-btn" data-feather="arrow-left"></i></a>
            <img class="img-fluid img1" src="assets/images/authentication/4.svg" alt="v1">
            <div class="auth-content">
                <div>
                    <h2>Reset your pin</h2>
                    <h4 class="p-0">Create a new one</h4> 
                
                </div>
            </div>
        </div>

        <form class="auth-form" method="post" action="reset-password.php">
            <div class="custom-container">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <div class="form-group">
                    <h5>Enter your new pin, which must be different from your previous one.</h5>
                    <label for="newpin" class="form-label">Enter new pin</label>
                    <div class="form-input">
                        <input type="password" class="form-control" id="newpin" name="new_password" placeholder="Enter new pin" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmpin" class="form-label">Re-enter new pin</label>
                    <div class="form-input">
                        <input type="password" class="form-control" id="confirmpin" name="confirm_password" placeholder="Re-enter new pin" required>
                    </div>
                </div>
                <button type="submit" class="btn theme-btn w-100">Update pin</button>
            </div>
        </form>

        <!-- error modal starts -->
            <div class="modal fade" id="expiredTokenModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Error</h2>
                        </div>
                        <div class="modal-body" align="center">
                            <div class="error-img">
                                <img class="img-fluid" src="assets/images/authentication/expired.png" style="width:30%;" alt="error" />
                            </div>
                            <br>
                            <h3>Your token has expired. Please generate another one on the forgot password page.</h3>                                                             
                        </div>
                        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                            <i class="icon" data-feather="x"></i>
                        </button>
                    </div>
                </div>
            </div>
        <!-- error modal starts -->

        <!-- error modal starts -->
        <div class="modal fade" id="passwordErrorModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Error</h2>
                    </div>
                    <div class="modal-body" align="center">
                        <div class="error-img">
                            <img class="img-fluid" src="assets/images/authentication/cloud.png" style="width:30%;" alt="error" />
                        </div>
                        <br>
                        <h3>Your pins do not match. Please check them and try again.</h3>                                                             
                    </div>
                    <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                        <i class="icon" data-feather="x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- error modal starts -->

        <!--  modal starts -->
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
                        <h3>Your pins do not match. Please check them and try again.</h3>                                                             
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

            <?php if ($expiredTokenModal): ?>
            $(document).ready(function() {
                $('#expiredTokenModal').modal('show');
            });
            <?php endif; ?>

            <?php if ($passwordErrorModal): ?>
            $(document).ready(function() {
                $('#passwordErrorModal').modal('show');
            });
            <?php endif; ?>

            <?php if ($successModal): ?>
            $(document).ready(function() {
                $('#successModal').modal('show');
            });
            <?php endif; ?>
        </script>
    </body>
</html>
