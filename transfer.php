<?php 
  session_start();
  include('db_connection.php');    

  if (!isset($_SESSION['userid'])) {
      header("location:signin.php");
      exit();
  }

  if (!isset($_GET['transfer_type'])) {
      header("location:dashboard.php");
      exit();
  }

  $transfer_type = $_GET['transfer_type'];
  $_SESSION['transfer_type'] = $transfer_type;

  $userid = $_SESSION['userid'];
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
  <!-- header start -->
  <header class="section-t-space">
    <div class="custom-container">
      <div class="header-panel">
        <a href="dashboard.php" class="back-btn">
          <i class="icon" data-feather="arrow-left"></i>
        </a>
        <h2>Transfer</h2>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- transfer section start -->
  <section class="section-b-space">
    <div class="custom-container">

      <ul class="nav nav-tabs custom-selectjs tab-style1" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button"
            role="tab">To CapitalVista</button>
        </li>        
      </ul>

      <div class="tab-content tab w-100" id="pills-tabContent1">
        <div class="tab-pane fade show active" id="bank" role="tabpanel" tabindex="0">

          <div class="title mt-3">
            <h2>Selected Account</h2>
          </div>
          <ul class="select-bank">
            <li>
              <div class="balance-box active">
                <input class="form-check-input" type="radio" name="flexRadio" checked />
                <img class="img-fluid balance-box-img active" src="assets/images/svg/balance-box-bg-active.svg"
                  alt="balance-box" />
                <img class="img-fluid balance-box-img unactive" src="assets/images/svg/balance-box-bg.svg"
                  alt="balance-box" />
                <div class="balance-content">
                  <h6>Balance</h6>
                  <h3>$<?php
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
                                $balance = $result['balance'];
                                $acc_num = $result['account_number'];
                                echo number_format($balance);
                            } else {
                                // echo "No balance found for user ID " . htmlspecialchars($userid);
                            }
                        } catch (PDOException $e) {
                            error_log("Query failed: " . $e->getMessage(), 0);
                            echo "An error occurred. Please try again later.";
                        }
                    ?>  
                  </h3>
                  <h5>Account Number - <?php echo $acc_num; ?></h5>
                </div>
              </div>
            </li>            
          </ul>
          <div class="title mt-3">
            <h2>Transfer money to</h2>
          </div>
          <form class="auth-form p-0" method="post" action="complete-transfer.php">            

            <div class="form-group">
              <label for="inputcardnumber" class="form-label">Receipient Number</label>
              <div class="form-input">
                <input type="number" class="form-control" id="inputcardnumber" name="rep_acc_num" placeholder="Enter Account Number" required />
              </div>
            </div>

            <?php if ($transfer_type == 'inter'): ?>
              <div class="form-group">
                <label for="inputcardnumber" class="form-label">Receipient Name</label>
                <div class="form-input">
                  <input type="text" class="form-control" id="inputcardnumber" name="rep_fullname" placeholder="Enter Receipient Name" required />
                </div>
              </div>

              <div class="form-group">
                <label for="inputcardnumber" class="form-label">Bank Name</label>
                <div class="form-input">
                  <select name="rep_bank_name" class="form-control" required>
                      <option value="" disabled selected>Select Bank</option>
                      <option value="BNP">BNP Paribas</option>
                      <option value="HSBC">HSBC Bank</option>
                      <!-- (Other bank options) -->
                  </select>
                </div>
              </div>
            <?php endif; ?>            
            <button type="submit" name="transfer" class="btn theme-btn w-100" value="transfer">Transfer</button>
          </form>
        </div>        
      </div>
    </div>
  </section>
  <!-- transfer section end -->

  <!-- error transfer modal start -->
  <div class="modal error-modal fade" id="accError" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Error</h2>
        </div>
        <div class="modal-body">
          <div class="error-img">
            <img class="img-fluid" src="assets/images/authentication/decline.png" style="width:150px;" alt="error" />
          </div>
          <h3>No account found with this number, please verify and try again.</h3>
          <a href="transfer.php?transfer_type=<?php echo $transfer_type; ?>" class="btn theme-btn successfully w-100">Try again</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- error transfer modal end -->

  <!-- error transfer modal start -->
  <div class="modal error-modal fade" id="userError" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Error</h2>
        </div>
        <div class="modal-body">
          <div class="error-img">
            <img class="img-fluid" src="assets/images/authentication/decline.png" style="width:200px;" alt="error" />
          </div>
          <h3>You cannot transfer money to your own account, please verify and try again.</h3>
          <a href="transfer.php?transfer_type=<?php echo $transfer_type; ?>" class="btn theme-btn successfully w-100">Try again</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- error transfer modal end -->

  <!-- successful transfer modal start -->
  <!-- (Your successful transfer modal here) -->
  <!-- successful transfer modal end -->

  <!-- Scripts -->
   <!-- jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- feather js -->
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/custom-feather.js"></script>

  <!-- iconsax js -->
  <script src="assets/js/iconsax.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- homescreen popup js -->
  <script src="assets/js/homescreen-popup.js"></script>

  <!-- PWA offcanvas popup js -->
  <script src="assets/js/offcanvas-popup.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>


  <?php if (isset($_GET['error'])): ?>
    <script>
        $(document).ready(function() {
            <?php if ($_GET['error'] == 'accError'): ?>
                $('#accError').modal('show');
            <?php elseif ($_GET['error'] == 'userError'): ?>
                $('#userError').modal('show');
            <?php endif; ?>
        });
    </script>
  <?php endif; ?>
</body>
</html>