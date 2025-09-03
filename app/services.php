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

  <!-- iconsax css -->
  <link rel="stylesheet" type="text/css" href="assets/css/vendors/iconsax.css" />

  <!-- bootstrap css -->
  <link rel="stylesheet" id="rtl-link" type="text/css" href="assets/css/vendors/bootstrap.min.css" />

  <!-- Theme css -->
  <link rel="stylesheet" id="change-link" type="text/css" href="assets/css/style.css" />
</head>

<body>
  <!-- header start -->
  <header class="section-t-space">
    <div class="custom-container">
      <div class="header-panel">
        <a href="dashboard.php" class="back-btn">
          <i class="icon" data-feather="arrow-left"></i>
        </a>
        <h2>All services</h2>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- service section starts -->
  <section>
    <div class="custom-container">
      <div class="title w-border">
        <h2>Bills</h2>
      </div>

      <div class="row gy-3">
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="activity"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Electricity</h5>
          </a>          
        </div>

        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="droplet"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Water</h5>
          </a>           
        </div>
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="wifi"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Internet</h5>
          </a>
        </div>

        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="monitor"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Television</h5>
          </a>
        </div>

        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="tablet"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Mobile</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="iconsax service-icon" data-icon="wallet-open-tick"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">E-wallet</h5>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="custom-container">
      <div class="title w-border">
        <h2>Other option</h2>
      </div>

      <div class="row gy-3">
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="bar-chart-2"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Investment</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="check-circle"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Deals</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="plus-square"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Medical</h5>
          </a>
        </div>

        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="iconsax service-icon" data-icon="car"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Car</h5>
          </a>
        </div>

        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="service-icon" data-feather="shopping-bag"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Shopping</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="#error" data-bs-toggle="modal">
            <div class="service-box">
              <i class="iconsax service-icon" data-icon="game-controller"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Games</h5>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- service section end -->

  <!-- error modal starts -->
  <div class="modal error-modal fade" id="error" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Error</h2>
                </div>
                <div class="modal-body">
                    <div class="error-img">
                        <img class="img-fluid" src="assets/images/svg/error.svg" alt="error" />
                    </div>
                    <h3>Your account is not verified for this transaction.</h3>
                    <a href="element-modal.html" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try
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

  <!-- iconsax js -->
  <script src="assets/js/iconsax.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>
</body>

</html>