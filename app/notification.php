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
        <h2>Notification</h2>

        <div class="dropdown">
          <a href="#" class="back-btn" role="button" data-bs-toggle="dropdown">
            <i class="icon" data-feather="settings"></i>
          </a>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Mute notification</a></li>
            <li><a class="dropdown-item" href="#">Mark as all read</a></li>
            <li><a class="dropdown-item" href="#">Remove all</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- notification section starts -->
  <section class="section-b-space">
    <div class="custom-container">
      <div class="title">
        <h2>Today</h2>
      </div>

      <ul class="notification-list">
        <li class="notification-box">
          <div class="notification-img">
            <img class="img-fluid icon" src="assets/images/person/p1.png" alt="p1" />
          </div>
          <div class="notification-details">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <a href="javascript:void(0);">
                  <h5 class="fw-semibold dark-text">Payment received</h5>
                </a>
                <h6 class="fw-normal light-text mt-1">Dianne Christian</h6>
              </div>
              <h6 class="time fw-normal light-text">9:02 pm</h6>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <h5 class="dark-text fw-normal">You received payment of <span
                  class="fw-semibold theme-color">$25.85</span></h5>
            </div>
          </div>
        </li>

        <li class="notification-box">
          <div class="notification-img">
            <img class="img-fluid icon" src="assets/images/person/p2.png" alt="p2" />
          </div>
          <div class="notification-details">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-semibold dark-text">Payment request</h5>
                <h6 class="fw-normal light-text mt-1">Connie Williams</h6>
              </div>
              <h6 class="time fw-normal light-text">8:45 pm</h6>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <h5 class="dark-text fw-normal">Send a pay request of <span class="fw-semibold theme-color">$56.48</span>
              </h5>
              <a href="javascript:void(0);" class="btn theme-btn pay-btn mt-0">Pay</a>
            </div>
          </div>
        </li>

        <li class="notification-box">
          <div class="notification-img img1">
            <img class="img-fluid notification-icon" src="assets/images/svg/alert.svg" alt="alert" />
          </div>
          <div class="notification-details">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-normal dark-text">Saving Alert</h5>
                <h6 class="fw-normal light-text mt-1">Bank</h6>
              </div>
              <h6 class="time fw-normal light-text">5:12 am</h6>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <h5 class="light-text fw-normal">Your monthly expense almost break the budget.</h5>
            </div>
          </div>
        </li>
      </ul>

      <div class="title mt-3">
        <h2>Yesterday</h2>
      </div>

      <ul class="notification-list">
        <li class="notification-box">
          <div class="notification-img">
            <img class="img-fluid icon" src="assets/images/person/p3.png" alt="p3" />
          </div>
          <div class="notification-details">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-semibold dark-text">Payment send</h5>
                <h6 class="fw-normal light-text mt-1">Kristina Johny</h6>
              </div>
              <h6 class="time fw-normal light-text">5:12 am</h6>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <h5 class="light-text fw-normal"><span class="fw-semibold theme-color">$25.85</span> amount has been send
                to the <span class="dark-text">Kristin Johny</span></h5>
            </div>
          </div>
        </li>

        <li class="notification-box">
          <div class="notification-img img2">
            <img class="img-fluid notification-icon" src="assets/images/svg/lock.svg" alt="alert" />
          </div>
          <div class="notification-details">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="fw-normal dark-text">Security Alert</h5>
                <h6 class="fw-normal light-text mt-1">Bank</h6>
              </div>
              <h6 class="time fw-normal light-text">5:12 am</h6>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <h5 class="light-text fw-normal">You have changed your password from samsung device.</h5>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </section>
  <!-- notification section end -->

  <!-- feather js -->
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/custom-feather.js"></script>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- script js -->
  <script src="assets/js/script.js"></script>
</body>

</html>