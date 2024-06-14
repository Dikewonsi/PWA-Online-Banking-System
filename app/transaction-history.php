<?php 
    session_start();
    include('db_connection.php');    

    if(!isset($_SESSION['userid']))
    {
      header("location:signin.php");
      exit();
    }

    $userid = $_SESSION['userid'];

    // Fetch transactions for the user
    try {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = :userid ORDER BY transaction_date DESC");
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
    error_log("Failed to fetch transactions: " . $e->getMessage());
    $transactions = [];
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

<body>
  <!-- header start -->
  <header class="section-t-space">
    <div class="custom-container">
      <div class="header-panel">
        <a href="dashboard.php" class="back-btn">
          <i class="icon" data-feather="arrow-left"></i>
        </a>
        <h2>Transaction history</h2>

        <div class="dropdown">
          <a href="#" class="back-btn" role="button" data-bs-toggle="dropdown">
            <i class="icon" data-feather="settings"></i>
          </a>

          <!-- <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Most recent </a></li>
            <li><a class="dropdown-item" href="#period" data-bs-toggle="modal">Custom</a></li>
            <li><a class="dropdown-item" href="#">Last 1 month</a></li>
            <li><a class="dropdown-item" href="#">Remove all</a></li>
          </ul> -->
        </div>
      </div>
    </div>
  </header>
  <!-- header end -->


  <!-- person transaction list section starts -->
  <!-- Display Sections for Today, Yesterday, Last Week, and Last Month -->
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
                <a href="#" class="transaction-link d-flex gap-3"
                    data-bs-toggle="modal"
                    data-bs-target="#transaction-detail"
                    data-transaction-id="<?php echo $transaction['transaction_id']; ?>"
                    data-payment-status="<?php echo htmlspecialchars($transaction['type']); ?>"
                    data-transaction-date="<?php echo date('d M, Y', strtotime($transaction['transaction_date'])); ?>"
                    data-transaction-category="<?php echo htmlspecialchars($transaction['category']); ?>"
                    data-amount="<?php echo number_format($transaction['amount'], 2); ?>">            
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
  <!-- person transaction list section end -->


  <!-- transaction detail modal start -->
  <div class="modal successful-modal transfer-details fade" id="transaction-detail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Transaction Detail</h2>
            </div>
            <div class="modal-body">
                <ul class="details-list">
                    <li>
                        <h3 class="fw-normal dark-text">Transaction Type</h3>
                        <h3 class="fw-normal light-text" id="payment-status"></h3>
                    </li>
                    <li>
                        <h3 class="fw-normal dark-text">Date</h3>
                        <h3 class="fw-normal light-text" id="transaction-date"></h3>
                    </li>
                    <li>
                        <h3 class="fw-normal dark-text">From</h3>
                        <h3 class="fw-normal light-text" id="from"></h3>
                    </li>
                    <li>
                        <h3 class="fw-normal dark-text">To</h3>
                        <h3 class="fw-normal light-text" id="to"></h3>
                    </li>
                    <li>
                        <h3 class="fw-normal dark-text">Transaction Description</h3>
                        <h3 class="fw-normal light-text" id="transaction-category"></h3>
                    </li>
                    <li class="amount">
                        <h3 class="fw-normal dark-text">Amount</h3>
                        <h3 class="fw-semibold error-color" id="amount"></h3>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                <i class="icon" data-feather="x"></i>
            </button>
        </div>
    </div>
</div>
  <!-- successful transfer modal end -->

  <!-- feather js -->
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/custom-feather.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>

  <!-- script js -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const transactionLinks = document.querySelectorAll('.transaction-link');
        const modalPaymentStatus = document.getElementById('payment-status');
        const modalTransactionDate = document.getElementById('transaction-date');
        const modalFrom = document.getElementById('from');
        const modalTo = document.getElementById('to');
        const modalTransactionCategory = document.getElementById('transaction-category');
        const modalAmount = document.getElementById('amount');

        transactionLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                // Fetch data attributes
                const paymentStatus = this.getAttribute('data-payment-status');
                const transactionDate = this.getAttribute('data-transaction-date');
                const from = this.getAttribute('data-from');
                const to = this.getAttribute('data-to');
                const transactionCategory = this.getAttribute('data-transaction-category');
                const amount = this.getAttribute('data-amount');

                // Update modal content
                modalPaymentStatus.textContent = paymentStatus;
                modalTransactionDate.textContent = transactionDate;
                modalFrom.textContent = from;
                modalTo.textContent = to;
                modalTransactionCategory.textContent = transactionCategory;
                modalAmount.textContent = '$' + amount;
            });
        });
    });
</script>
</body>

</html>