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
  <title>CapitalVista</title>
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

  <!-- iconsax css -->
  <link rel="stylesheet" type="text/css" href="assets/css/vendors/iconsax.css" />


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
        <img class="img-fluid logo" src="assets/images/logo/logo.png" style="width:100px; height:50px;" alt="logo" />

        <a href="notification.php" class="notification">
          <i class="notification-icon" data-feather="bell"></i>
        </a>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- card start -->
  <section class="section-b-space">
    <div class="custom-container">
      <div class="card-box">
        <div class="card-details">
          <div class="d-flex justify-content-between">
            <h5 class="fw-semibold">Total Balance</h5>
            <img src="assets/images/svg/ellipse.svg" alt="ellipse" />
          </div>

          <h1 class="mt-2 text-white">
            $<?php
              try
              {
                  // Prepare the SQL statement to fetch the balance from the account table
                  $stmt = $pdo->prepare("SELECT balance FROM accounts WHERE user_id = :userid");
                  $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
                  $stmt->execute();
                  
                  // Fetch the result
                  $result = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  // Check if the result is not empty and echo the balance
                  if ($result) {
                      $balance = $result['balance'];
                      echo number_format($balance);
                  } else {
                      // echo "No balance found for user ID " . htmlspecialchars($userid);
                  }
              } catch (PDOException $e) {
                  error_log("Query failed: " . $e->getMessage(), 0);
                  echo "An error occurred. Please try again later.";
              }
            ?>
          </h1>

          <?php
                    
          // Initialize variables
          $expenses = 0;
          $income = 0;

          try {
              // Query to calculate total transfers (expenses)
              $stmt = $pdo->prepare("SELECT SUM(amount) as total_expenses FROM transactions WHERE user_id = :userid AND type = 'transfer'");
              $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
              $stmt->execute();
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              $expenses = $result['total_expenses'] !== null ? $result['total_expenses'] : 0;            

              // Query to calculate total deposits (income)
              $stmt = $pdo->prepare("SELECT SUM(amount) as total_income FROM transactions WHERE user_id = :userid AND type = 'deposit'");
              $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
              $stmt->execute();
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              $income = $result['total_income'] !== null ? $result['total_income'] : 0;              

          } catch (Exception $e) {
              error_log("Failed to fetch transaction data: " . $e->getMessage());
              // Handle the error, perhaps set expenses and income to 0 or display an error message
              $expenses = 0;
              $income = 0;
          }
          ?>

          <div class="amount-details">
            <div class="amount w-50 text-start">
              <div class="d-flex align-items-center justify-content-start">
                <img class="img-fluid icon" src="assets/images/svg/arrow-down-right.svg" alt="down" />
                <h5>Income</h5>
              </div>
              <h3 class="text-white">$<?php echo number_format($income, 2); ?></h3>
            </div>
            <div class="amount w-50 text-end border-0">
              <div class="d-flex align-items-center justify-content-end">
                <img class="img-fluid icon" src="assets/images/svg/arrow-up-right.svg" alt="up" />
                <h5>Expense</h5>
              </div>
              <h3 class="text-white">$<?php echo number_format($expenses, 2); ?></h3>
            </div>
          </div>
        </div>
        <a href="#add-money" class="add-money theme-color" data-bs-toggle="modal">+ Add Money</a>
      </div>
    </div>
  </section>
  <!-- card end -->

  <!-- categories section starts -->
  <section class="categories-section section-b-space">
    <div class="custom-container">
      <ul class="categories-list">
        <li>
          <a href="transfer.php?transfer_type=intra">
            <div class="categories-box">
              <i class="categories-icon" data-feather="repeat"></i>
            </div>
            <h5 class="mt-2 text-center">to CapitalV</h5>
          </a>
        </li>
        <li>
          <a href="transfer.php?transfer_type=inter">
            <div class="categories-box">
              <img src="assets/images/icons/bank.png" style="width:30px;" alt="">
            </div>
            <h5 class="mt-2 text-center">to Banks</h5>
          </a>
        </li>
        <li>
          <a href="cards.php">
            <div class="categories-box">
              <img src="assets/images/icons/credit-card1.png" style="width:30px;" alt="">
            </div>
            <h5 class="mt-2 text-center">Cards</h5>
          </a>
        </li>        
      </ul>
    </div>
  </section>
  <!-- categories section end -->

  <!-- service section starts -->
  <section>
    <div class="custom-container">
      <div class="title">
        <h2>Select service</h2>
        <a href="services.php">See all</a>
      </div>
      <div class="row gy-3">
        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="activity"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Electricity</h5>
          </a>
        </div>

        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="droplet"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Water</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="wifi"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Internet</h5>
          </a>
        </div>

        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="monitor"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Television</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="bar-chart-2"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Investment</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="tablet"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Mobile</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="plus-square"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Medical</h5>
          </a>
        </div>
        <div class="col-3">
          <a href="services.php">
            <div class="service-box">
              <i class="service-icon" data-feather="more-horizontal"></i>
            </div>
            <h5 class="mt-2 text-center dark-text">Other</h5>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- service section end -->

  <!-- Transaction section starts -->
  <section>
    <div class="custom-container">
      <div class="title">
        <h2>Transaction</h2>
        <a href="transaction-history.php">See all</a>
      </div>

      <?php
      // Fetch transactions for the user
        try {
          $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = :userid ORDER BY transaction_date DESC LIMIT 5");
          $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
          $stmt->execute();
          $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
          error_log("Failed to fetch transactions: " . $e->getMessage());
          $transactions = [];
        }
      ?>

      <div class="row gy-3">
        <?php if (empty($transactions)): ?>
          <div class="col-12">
            <div class="transaction-box">
              <div class="d-flex gap-3">
                <div class="transaction-details">
                  <div class="transaction-name">
                    <h5>No Transactions Found</h5>
                  </div>
                  <div class="d-flex justify-content-between">
                    <h5 class="light-text">You have no transactions yet.</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($transactions as $transaction): ?>
            <div class="col-12">
              <div class="transaction-box">
                <a href="transaction-history.php" class="d-flex gap-3">
                  <div class="transaction-image">
                    <?php
                      $imageSrc = '';
                      if ($transaction['type'] == 'deposit') {
                          $imageSrc = 'plus-money';
                      } elseif ($transaction['type'] == 'transfer') {
                          $imageSrc = 'minus-money';
                      } elseif ($transaction['type'] == 'withdrawal') {
                        $imageSrc = 'minus-money';
                      } else {
                          $imageSrc = 'default'; // Fallback image
                      }
                    ?>
                    <img class="img-fluid transaction-icon" src="assets/images/icons/<?php echo $imageSrc; ?>.png" alt="icon" />
                  </div>
                  <div class="transaction-details">
                    <div class="transaction-name">
                      <h5><?php echo htmlspecialchars($transaction['type']); ?></h5>
                      <h3 class="<?php echo $transaction['type'] == 'deposit' ? 'success-color' : 'error-color'; ?>">
                        $<?php echo number_format($transaction['amount'], 2); ?>
                      </h3>
                    </div>
                    <div class="d-flex justify-content-between">
                      <h5 class="light-text"><?php echo htmlspecialchars($transaction['description']); ?></h5>
                      <h5 class="light-text"><?php echo date('d M, h:i a', strtotime($transaction['transaction_date'])); ?></h5>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- Transaction section end -->

  <!-- bill details section starts -->
  <section>
    <div class="custom-container">
      <div class="title">
        <h2>Bills Detail</h2>
        <a href="javascript:void(0);">See all</a>
      </div>
      <div class="row g-3">
        <div class="col-md-3 col-6">
          <div class="bill-box">
            <div class="d-flex gap-3">
              <div class="bill-icon">
                <img class="img-fluid icon" src="assets/images/svg/6.svg" alt="p6" />
              </div>
              <div class="bill-details">
                <h5 class="dark-text">Airtel</h5>
                <h6 class="light-text mt-2">Pre-paid</h6>
              </div>
            </div>
            <div class="bill-price">
              <h5>$69.49</h5>
              <a href="#pay" data-bs-toggle="modal" class="btn bill-pay bill-paid">Pay</a>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="bill-box">
            <div class="d-flex gap-3">
              <div class="bill-icon">
                <img class="img-fluid icon" src="assets/images/svg/7.svg" alt="p7" />
              </div>
              <div class="bill-details">
                <h5 class="dark-text">Apple</h5>
                <h6 class="light-text mt-2">Subscription</h6>
              </div>
            </div>
            <div class="bill-price">
              <h5>$49.85</h5>
              <a href="#pay" data-bs-toggle="modal" class="btn bill-pay bill-paid">Pay</a>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="bill-box">
            <div class="d-flex gap-3">
              <div class="bill-icon">
                <img class="img-fluid icon" src="assets/images/svg/8.svg" alt="p8" />
              </div>
              <div class="bill-details">
                <h5 class="dark-text">TV</h5>
                <h6 class="light-text mt-2">Connection</h6>
              </div>
            </div>
            <div class="bill-price">
              <h5>$99.99</h5>
              <a href="#pay" data-bs-toggle="modal" class="btn bill-pay bill-paid">Pay</a>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="bill-box">
            <div class="d-flex gap-3">
              <div class="bill-icon">
                <img class="img-fluid icon" src="assets/images/svg/9.svg" alt="p9" />
              </div>
              <div class="bill-details">
                <h5 class="dark-text">Torrent</h5>
                <h6 class="light-text mt-2">Electricity</h6>
              </div>
            </div>
            <div class="bill-price">
              <h5>$60.49</h5>
              <a href="#pay" data-bs-toggle="modal" class="btn bill-pay bill-paid">Pay</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- bill details section starts -->

  <!-- saving plans section starts -->
  <section>
    <div class="custom-container">
      <div class="title">
        <h2>My Saving Plans</h2>
        <a href="javascript:void(0);">See all</a>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="saving-plan-box">
            <div class="saving-plan-icon">
              <img class="img-fluid" src="assets/images/svg/10.svg" alt="p10" />
            </div>
            <h3>New Car</h3>
            <h6>Amount left</h6>
            <div class="progress" role="progressbar" aria-label="progressbar" aria-valuenow="0" aria-valuemin="0"
              aria-valuemax="100">
              <div class="progress-bar bar1"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <h5 class="theme-color">$2,000.00</h5>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="saving-plan-box">
            <div class="saving-plan-icon">
              <img class="img-fluid" src="assets/images/svg/11.svg" alt="p11" />
            </div>
            <h3>Grand Home</h3>
            <h6>Amount left</h6>
            <div class="progress" role="progressbar" aria-label="progressbar" aria-valuenow="0" aria-valuemin="0"
              aria-valuemax="100">
              <div class="progress-bar bar2"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <h5 class="theme-color">$2,000.00</h5>
              <img class="img-fluid arrow" src="assets/images/svg/arrow.svg" alt="arrow" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- saving plans section end -->

  <!-- monthly statistics section starts -->
  <section>
    <div class="custom-container">
      <div class="statistics-banner">
        <div class="d-flex justify-content-center gap-3">
          <div class="statistics-image">
            <i class="icon" data-feather="bar-chart-2"></i>
          </div>
          <div class="statistics-content d-block">
            <h5>Monthly Statistics</h5>
            <h6>30% better performance than previous years.</h6>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- monthly statistics section end -->

  <!-- panel-space start -->
  <section class="panel-space"></section>
  <!-- panel-space end -->

  <!-- bottom navbar start -->
  <div class="navbar-menu elements-navbar position-relative">
      <ul>
          <li class="active">
              <a href="dashboard.php">
                  <div class="icon">
                      <img class="unactive" src="assets/images/icons/home.png" alt="Capital Vista" />
                      <img class="active" src="assets/images/icons/home.png" style="width:20px;" alt="Capital Vista" />
                  </div>
                  <h5 class="active">Home</h5>
              </a>
          </li>          

          <li>
              <a href="loans.php">
                  <div class="icon">
                      <img class="unactive" src="assets/images/svg/bar-chart.svg" alt="bag" />
                      <img class="active" src="assets/images/svg/bar-chart-fill.svg" alt="bag" />
                  </div>
                  <h5>Loans</h5>
              </a>
          </li>

          <li>
              <a href="cards.php">
                  <div class="icon">
                      <img class="unactive" src="assets/images/svg/credit-card.png" style="width:22px;" alt="profile" />
                      <img class="active" src="assets/images/svg/credit-card-fill.svg" style="width:22px;" alt="profile" />
                  </div>
                  <h5>Cards</h5>
              </a>
          </li>

          <li>
              <a href="profile.php">
                  <div class="icon">
                      <img class="unactive" src="assets/images/svg/user.svg" alt="profile" />
                      <img class="active" src="assets/images/svg/user-fill.svg" alt="profile" />
                  </div>
                  <h5>Me</h5>
              </a>
          </li>
      </ul>
  </div>
  <!-- bottom navbar end -->

  <!-- add money modal start -->
  <!-- <div class="modal add-money-modal fade" id="add-money" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Add Money</h2>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="inputcard" class="form-label mb-2">From</label>
            <div class="d-flex gap-2">
              <select id="inputcard" class="form-select">
                <option selected>**** **** **** 1566 - Saving a/c</option>
                <option>**** **** **** 1566 - Saving a/c</option>
                <option>**** **** **** 1566 - Saving a/c</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputamount" class="form-label mb-2">Amount</label>
            <div class="form-input">
              <input type="number" class="form-control" id="inputamount" />
            </div>
          </div>
          <a href="landing.html" class="btn theme-btn successfully w-100">Deposit</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div> -->
  <!-- add money modal end -->

  <!-- pay modal starts -->
  <!-- <div class="modal pay-modal fade" id="pay" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Apple Bill Detail</h2>
        </div>
        <div class="modal-body">
          <ul class="details-list">
            <li>
              <h3 class="fw-normal dark-text">Amount</h3>
              <h3 class="fw-semibold theme-color">$49.85</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Bill date</h3>
              <h3 class="fw-normal light-text">10 May, 2023</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Due Date</h3>
              <h3 class="fw-normal error-color">31 May, 2023</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Bill number</h3>
              <h3 class="fw-normal light-text">BM452695523</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Mobile number</h3>
              <h3 class="fw-normal light-text">+91 ***** **256</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Bill status</h3>
              <h3 class="fw-semibold error-color">Unpaid</h3>
            </li>
          </ul>
          <a href="make-payment.html" class="btn theme-btn successfully w-100">Pay</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div> -->
  <!-- pay modal end -->

  <!-- paid modal starts -->
  <!-- <div class="modal pay-modal fade" id="paid" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Mobile Bill Detail</h2>
        </div>
        <div class="modal-body">
          <ul class="details-list">
            <li>
              <h3 class="fw-normal dark-text">Amount</h3>
              <h3 class="fw-semibold theme-color">$69.49</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Bill date</h3>
              <h3 class="fw-normal light-text">10 May, 2023</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Due Date</h3>
              <h3 class="fw-normal light-text">22 May, 2023</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Bill number</h3>
              <h3 class="fw-normal light-text">BM452695523</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Mobile number</h3>
              <h3 class="fw-normal light-text">+91 ***** **256</h3>
            </li>
            <li>
              <h3 class="fw-normal dark-text">Bill status</h3>
              <h3 class="fw-semibold theme-color">Paid</h3>
            </li>
          </ul>
          <a href="landing.html" class="btn theme-btn successfully w-100">Paid - Thank you !</a>
        </div>
        <button type="button" class="btn close-btn" data-bs-dismiss="modal">
          <i class="icon" data-feather="x"></i>
        </button>
      </div>
    </div>
  </div> -->
  <!-- paid modal end -->

  <!-- pwa install app popup start -->
  <!-- <div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas" tabindex="-1" id="offcanvas">
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    <div class="offcanvas-body small">
      <div class="app-info">
        <img src="assets/images/logo/48.png" class="img-fluid" alt="" />
        <div class="content">
          <h4>CapitalVista App</h4>
          <a href="#">www.CapitalVista-app.com</a>
        </div>
      </div>
      <a href="#!" class="btn theme-btn install-app btn-inline home-screen-btn m-0" id="installapp">Add to Home
        Screen</a>
    </div>
  </div> -->
  <!-- pwa install app popup start -->

  <!-- swiper js -->
  <script src="assets/js/swiper-bundle.min.js"></script>
  <script src="assets/js/custom-swiper.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap Bundle JS (includes Popper.js) -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


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
</body>

</html>