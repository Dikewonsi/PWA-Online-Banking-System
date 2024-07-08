<?php 
    session_start();
    include('db_connection.php');    

    if (!isset($_SESSION['userid'])) {
        header("location: signin.php");
        exit();
    }

    $updateModal = false;

    $userid = $_SESSION['userid'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :userid");
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();

        while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {          
            $fname = $rows['fullname'];
            $email = $rows['email'];
            $dob = $rows['dob'];
            $ssn = $rows['ssn'];
            $residential_address = $rows['residential_address'];
            $mailing_address = $rows['mailing_address'];
            $phone = $rows['phone'];
            $employment = $rows['employment'];
            $citizenship = $rows['citizenship'];
            $marital_status = $rows['marital_status'];
            $profile_pic = $rows['profile_pic'];
        }
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage(), 0);
    }

    if (isset($_GET['update']))
    {
        if ($_GET['update'] == 'success')
        {
            $updateModal = true;
        }
        elseif ($_GET['update'] == 'none')
        {
            $updateModal = false;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CapitalVista">
    <meta name="keywords" content="CapitalVista">
    <meta name="author" content="CapitalVista">
    <link rel="manifest" href="./manifest.json">
    <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon">
    <title>CapitalVista App</title>
    <link rel="apple-touch-icon" href="assets/images/logo/favicon.png">
    <meta name="theme-color" content="#122636">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="CapitalVista">
    <meta name="msapplication-TileImage" content="assets/images/logo/favicon.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
    <header class="section-t-space">
        <div class="custom-container">
            <div class="header-panel">
                <a href="profile.php" class="back-btn">
                    <i class="icon" data-feather="arrow-left"></i>
                </a>
                <h2>My Account</h2>
            </div>
        </div>
    </header>

    <section class="section-b-space">
        <div class="custom-container">
            <div class="profile-section">
                <div class="profile-banner">
                    <div class="profile-image">
                        <img class="img-fluid profile-pic" src="helpers/<?php echo $profile_pic; ?>" alt="Profile Picture">                                                                        
                    </div>
                </div>
                <h2><?php echo htmlspecialchars($fname); ?></h2>
                <h5>
                    <?php
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM accounts WHERE user_id = :userid");
                            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
                            $stmt->execute();

                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($result) {
                                $account_number = $result['account_number'];
                                echo htmlspecialchars($account_number);
                            }
                        } catch (PDOException $e) {
                            error_log("Query failed: " . $e->getMessage(), 0);
                            echo "An error occurred. Please try again later.";
                        }
                    ?>
                </h5>
            </div>
            <br>             

            <form class="auth-form pt-0 mt-3" action="helpers/update-profile.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="dob" class="form-label">Profile Picture</label>
                    <input type="file"  class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="dob" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" disabled value="<?php echo htmlspecialchars($fname); ?>">
                </div>
                <div class="form-group">
                    <label for="dob" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" disabled value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="form-group">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" placeholder="Enter your date of birth">
                </div>
                <div class="form-group">
                    <label for="ssn" class="form-label">SSN/TIN</label>
                    <input type="text" class="form-control" id="ssn" name="ssn" value="<?php echo htmlspecialchars($ssn); ?>" placeholder="Enter your SSN or TIN">
                </div>
                <div class="form-group">
                    <label for="residential_address" class="form-label">Residential Address</label>
                    <input type="text" class="form-control" id="residential_address" name="residential_address" value="<?php echo htmlspecialchars($residential_address); ?>" placeholder="Enter your residential address">
                </div>
                <div class="form-group">
                    <label for="mailing_address" class="form-label">Mailing Address</label>
                    <input type="text" class="form-control" id="mailing_address" name="mailing_address" value="<?php echo htmlspecialchars($mailing_address); ?>" placeholder="Enter your mailing address (if different)">
                </div>
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label for="employment" class="form-label">Employment Information</label>
                    <input type="text" class="form-control" id="employment" name="employment" value="<?php echo htmlspecialchars($employment); ?>" placeholder="Enter your employment information">
                </div>
                <div class="form-group">
                    <label for="citizenship" class="form-label">Citizenship</label>
                    <input type="text" class="form-control" id="citizenship" name="citizenship" value="<?php echo htmlspecialchars($citizenship); ?>" placeholder="Enter your citizenship">
                </div>
                <div class="form-group">
                    <label for="marital_status" class="form-label">Marital Status</label>
                    <input type="text" class="form-control" id="marital_status" name="marital_status" value="<?php echo htmlspecialchars($marital_status); ?>" placeholder="Enter your marital status">
                </div>
                <button type="submit" class="btn theme-btn w-100">Update</button>
            </form>
        </div>
    </section>

    <!-- error modal starts -->
    <div class="modal fade" id="updateModal" tabindex="-1">
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
                    <h3>Your profile has been successfully updated.</h3>                                     
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                    <i class="icon" data-feather="x"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- error modal starts -->

    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/custom-feather.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
   // Check if there's an error and show the modal
     <?php if ($updateModal) : ?>
        window.onload = function (){
            $('#updateModal').modal('show');
        }
    <?php endif; ?>     
  </script>
</body>
</html>
