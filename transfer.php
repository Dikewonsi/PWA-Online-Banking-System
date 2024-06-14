<?php 
    session_start();
    include('db_connection.php');    

    if(!isset($_SESSION['userid']))
    {
      header("location:signin.php");
      exit();
    }

    $userid = $_SESSION['userid'];
    $userError = false;
    $accError = false;

    try
    {
      $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :userid");
      $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
      $stmt->execute();
      
      while ($rows = $stmt->fetch(PDO::FETCH_ASSOC))
      {          
          $fname = $rows['fullname'];
      }
    } catch (PDOException $e)
    {
        error_log("Query failed: " . $e->getMessage(), 0);
        // Handle the error appropriately here, e.g., display an error message to the user
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $rep_acc_num = $_POST['rep_acc_num'];
        try
        {
            // Fetch the user's account number
            $stmt = $pdo->prepare("SELECT account_number FROM accounts WHERE user_id = :userid");
            $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();
            $user_account = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_account && $user_account['account_number'] == $rep_acc_num) {
                // $error = "You cannot transfer money to your own account.";
                $userError = true;
            } else {
                // Check if the recipient's account number exists
                $stmt = $pdo->prepare("SELECT u.fullname, a.account_number FROM accounts a JOIN users u ON a.user_id = u.user_id WHERE a.account_number = :acc_num");
                $stmt->bindParam(':acc_num', $rep_acc_num, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $_SESSION['rep_acc_num'] = $rep_acc_num;
                    $_SESSION['rep_fullname'] = $result['fullname'];
                    header("Location: complete-transfer.php");
                    exit();
                } else {
                    $accError = true;
                }
            }
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage(), 0);
            $error = "An error occurred. Please try again later.";
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
          <form class="auth-form p-0" method="post" action="transfer.php">            

            <div class="form-group">
              <label for="inputcardnumber" class="form-label">Receipt Number</label>
              <div class="form-input">
                <input type="number" class="form-control" id="inputcardnumber" name="rep_acc_num" placeholder="Enter Account Number" />
              </div>
            </div>

            <!-- <div class="form-group">
              <label for="inputamount" class="form-label">Amount</label>
              <input type="text" class="form-control" id="inputamount" />
            </div>

            <ul class="amount-list">
              <li>
                <div class="amount">$50</div>
              </li>
              <li>
                <div class="amount">$100</div>
              </li>
              <li>
                <div class="amount">$200</div>
              </li>
            </ul> -->
            <button type="subnmit" class="btn theme-btn w-100" data-bs-toggle="modal">Transfer</button>
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
          <a href="#done" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try again</a>
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
          <h3>You cannot transfer money to your own account., please verify and try again.</h3>
          <a href="#done" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Try again</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- error transfer modal end -->

  <!-- successful transfer modal start -->
  <!-- <div class="modal successful-modal fade" id="done" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Successfully Transfer</h2>
        </div>
        <div class="modal-body">
          <div class="done-img">
            <img class="img-fluid" src="assets/images/svg/done.svg" alt="done" />
          </div>
          <h2>$49.85</h2>
          <h5>#TR - 7859623</h5>
          <ul class="details-list">
            <li>
              <h3 class="fw-normal dark-text">Amount</h3>
              <h3 class="fw-normal theme-color">$49.85</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Date</h3>
              <h3 class="fw-normal light-text">10 Feb, 2023 | 9:02 pm</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Receive from</h3>
              <h3 class="fw-normal light-text">Dianne Christian</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Amount</h3>
              <h3 class="fw-normal light-text">HDFC bank</h3>
            </li>
          </ul>
          <a href="landing.html" class="btn theme-btn successfully w-100">Back to home</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div> -->
  <!-- successful transfer modal end -->

  <!-- swiper js -->
  <script src="assets/js/swiper-bundle.min.js"></script>
  <script src="assets/js/custom-swiper.js"></script>

   <!-- jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- feather js -->
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/custom-feather.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>

  <script>    
     // Check if there's a pin error and show the modal
     <?php if ($accError) : ?>
        window.onload = function () {
            $('#accError').modal('show');
        }
    <?php endif; ?>   
    
    // Check if there's a pin error and show the modal
    <?php if ($userError) : ?>
        window.onload = function () {
            $('#userError').modal('show');
        }
    <?php endif; ?>   
  </script>
</body>

</html>