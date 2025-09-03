<?php 
    session_start();
    include('db_connection.php');    

    if (!isset($_SESSION['userid']) || !isset($_SESSION['rep_acc_num'])) {
        header("location:signin.php");
        exit();
    }

    //Initiate Error
    $balanceError = false;

    $userid = $_SESSION['userid'];
    $rep_acc_num = $_SESSION['rep_acc_num'];
    $rep_fullname = $_SESSION['rep_fullname'];
    $transfer_type = $_SESSION['transfer_type'];
    $rep_bank_name = isset($_SESSION['rep_bank_name']) ? $_SESSION['rep_bank_name'] : '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $amount = $_POST['amount'];
        $remark = $_POST['remark'];
        
        try {
            // Fetch sender's account balance
            $stmt = $pdo->prepare("SELECT balance FROM accounts WHERE user_id = :userid");
            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();
            $senderAccount = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$senderAccount) {
                throw new Exception("Sender's account not found.");
            }
            
            $balance = $senderAccount['balance'];
            
            if ($amount > 0 && $amount <= $balance) {
                $_SESSION['transfer_amount'] = $amount;
                $_SESSION['remark'] = $remark;
                header("Location: verify-pin.php");
                exit();
            } else {
                    //Initiate Error
                    $balanceError = true;
            }
        } catch (Exception $e) {
            error_log("Transfer step 1 failed: " . $e->getMessage(), 0);
            $error_message = $e->getMessage();
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
        <a href="transfer.php" class="back-btn">
          <i class="icon" data-feather="arrow-left"></i>
        </a>
        <h2>Money Transfer</h2>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- pay money section starts -->
  <section class="pay-money section-b-space">
    <div class="custom-container">
        <form action="" method="POST">
            <div class="profile-pic">
                <img class="" src="assets/images/icons/user.png" alt="p3" />
            </div>
            <h3 class="person-name">Sending money to <?php echo $rep_fullname; ?></h3>
            <h5 class="upi-id">Account Number : <?php echo $rep_acc_num; ?></h5>
            <div class="form-group">
                <div class="form-input mt-4">
                <input type="text" class="form-control" name="amount" id="inputamount" />
                </div>
            </div>
            <div class="form-group">
                <div class="form-input mt-3">
                <input type="text" class="form-control reason" name="remark" id="inputreason" placeholder="What's this for? (Optional)" />
                </div>
            </div>

            <button type="submit" class="btn theme-btn w-100">Pay now</button>
        </form>      
    </div>
  </section>
  <!-- pay money section end -->


   <!-- error transfer modal start -->
  <div class="modal error-modal fade" id="balanceError" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Error</h2>
        </div>
        <div class="modal-body">
          <div class="error-img">
            <img class="img-fluid" src="assets/images/authentication/safety-box.png" style="width:150px;" alt="error" />
          </div>
          <h3>Not enough money in your account to make this transfer, please enter an amount lower or equal to your balance.</h3>
          <a href="#done" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try again</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- error transfer modal end -->

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
        // Check if there's a pin error and show the modal
        <?php if ($balanceError) : ?>
            window.onload = function () {
                $('#balanceError').modal('show');
            }
        <?php endif; ?>        
    </script>
</body>

</html>