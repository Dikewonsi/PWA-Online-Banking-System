<div class="offcanvas sidebar-offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft">
    <div class="offcanvas-header sidebar-header">
      <div class="sidebar-logo">
        <img class="img-fluid logo" src="assets/images/logo/logo.png" style="width:100px;" alt="logo" />
      </div>
      <div class="balance">
        <img class="img-fluid balance-bg" src="assets/images/background/auth-bg.jpg" alt="auth-bg" />
        <h5>Balance</h5>        
        <h2>
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
        </h2>
      </div>
    </div>
    <div class="offcanvas-body">
      <div class="sidebar-content">
        <ul class="link-section">
          <li>
            <a href="dashboard.php" class="pages">
              <img src="assets/images/icons/home-fill.png" style="width:20px;" alt="">
              <h3>Home</h3>
            </a>
          </li>

          <li>
            <a href="services.php" class="pages">
              <img src="assets/images/icons/badge.png" style="width:20px;" alt="">
              <h3>Bills</h3>
            </a>
          </li>

          <li>
            <a href="loan.php" class="pages">
              <img src="assets/images/icons/money.png" style="width:20px;" alt="">
              <h3>Loans</h3>
            </a>
          </li>

          <li>
            <a href="cards.php" class="pages">
              <img src="assets/images/icons/credit-card.png" style="width:20px;" alt="">
              <h3>Cards</h3>
            </a>
          </li>

          <li>
            <a href="profile.php" class="pages">
            <img src="assets/images/icons/user.png" style="width:20px;" alt="">
              <h3>Profile</h3>
            </a>
          </li>

          <li>
            <a href="logout.php" class="pages">
            <img src="assets/images/icons/logout.png" style="width:20px;" alt="">
              <h3>Log out</h3>
            </a>
          </li>
        </ul>
        <div class="mode-switch">
          <ul class="switch-section">            
            <li>
              <h3>Dark</h3>
              <div class="switch-btn">
                <input id="dark-switch" type="checkbox" />
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>