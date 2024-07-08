<?php 
    session_start();
    include('db_connection.php');    

    if(!isset($_SESSION['userid']))
    {
      header("location:signin.php");
      exit();
    }

    $userid = $_SESSION['userid'];

    try
    {
      $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :userid");
      $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
      $stmt->execute();
      
      while ($rows = $stmt->fetch(PDO::FETCH_ASSOC))
      {          
          $fname = $rows['fullname'];
      }
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage(), 0);
        // Handle the error appropriately here, e.g., display an error message to the user
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
  <!-- side bar start -->
    <?php include('includes/sidebar.php'); ?>
  <!-- side bar end -->

  <!-- header start -->
  <header class="section-t-space">
    <div class="custom-container">
      <div class="header-panel">
        <a class="sidebar-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft">
          <i class="menu-icon" data-feather="menu"></i>
        </a>
        <h2>Profile</h2>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- profile section start -->
  <section class="section-b-space">
    <div class="custom-container">
      <div class="profile-section">
        <div class="profile-banner">
          <div class="profile-image">
            <img class="img-fluid profile-pic" src="assets/images/person/default.png" alt="p3" />
          </div>
        </div>
        <h2><?php echo $fname; ?></h2>
        <h5>
            <?php
                try
                {
                    // Prepare the SQL statement to fetch the balance from the account table
                    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE user_id = :userid");
                    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    // Fetch the result
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Check if the result is not empty and echo the balance
                    if ($result) {
                        $account_number = $result['account_number'];
                        echo $account_number;
                    } else {
                        // echo "No balance found for user ID " . htmlspecialchars($userid);
                    }
                } catch (PDOException $e) {
                    error_log("Query failed: " . $e->getMessage(), 0);
                    echo "An error occurred. Please try again later.";
                }
            ?>  
        </h5>
      </div>

      <ul class="profile-list">
        <li>
          <a href="my-account.php" class="profile-box">
            <div class="profile-img">
              <i class="icon" data-feather="user"></i>
            </div>
            <div class="profile-details">
              <h4>My Account</h4>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </a>
        </li>
        <li>
          <a href="cards.php" class="profile-box">
            <div class="profile-img">
              <i class="icon" data-feather="credit-card"></i>
            </div>
            <div class="profile-details">
              <h4>My Cards</h4>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </a>
        </li>
        <li>
          <a href="change-password.php" class="profile-box">
            <div class="profile-img">
              <i class="icon" data-feather="settings"></i>
            </div>
            <div class="profile-details">
              <h4>Change Password</h4>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </a>
        </li>
        <li>
          <a href="settings.php" class="profile-box">
            <div class="profile-img">
              <i class="icon" data-feather="lock"></i>
            </div>
            <div class="profile-details">
              <h4>Settings</h4>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </a>
        </li>

        <li>
          <a href="faq.php" class="profile-box">
            <div class="profile-img">
              <i class="icon" data-feather="help-circle"></i>
            </div>
            <div class="profile-details">
              <h4>Help Center</h4>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </a>
        </li>
        <li>
          <a href="logout.php" class="profile-box">
            <div class="profile-img">
              <i class="icon" data-feather="log-out"></i>
            </div>
            <div class="profile-details">
              <h4>Log Out</h4>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </section>
  <!-- profile section end -->

  <!-- panel-space start -->
  <section class="panel-space"></section>
  <!-- panel-space end -->

  <!-- bottom navbar start -->
  <?php include('includes/bottom-navbar.php'); ?>
  <!-- bottom navbar end -->

  <!-- swiper js -->
  <script src="assets/js/swiper-bundle.min.js"></script>
  <script src="assets/js/custom-swiper.js"></script>

  <!-- feather js -->
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/custom-feather.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>
</body>

</html>