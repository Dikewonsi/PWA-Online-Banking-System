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
    // Fetch cards for the user
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE user_id = ?");
    $stmt->execute([$userid]);
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $e)
  {
      error_log("Fetch cards error: " . $e->getMessage(), 0);
      $cards = [];
  }

  // --------------------------------------
   
   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $userId = $_SESSION['userid'];
      $card_type = htmlspecialchars($_POST['card_type']);
      $cardNumber = htmlspecialchars($_POST['card_number']);
      $cardholderName = htmlspecialchars($_POST['cardholder_name']);
      $expiryDate = htmlspecialchars($_POST['expiry_date']);
      $cvv = htmlspecialchars($_POST['cvv']);
      $initialBalance = 0.00; // Set initial balance to zero

      try
      {
          // Prepare statement to insert card details
          $stmt = $pdo->prepare("INSERT INTO cards (user_id, card_type, card_number, cardholder_name, expiry_date, cvv, balance) 
                                VALUES (:user_id, :card_type, :card_number, :cardholder_name, :expiry_date, :cvv, :balance)");
          $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
          $stmt->bindParam(':card_type', $card_type, PDO::PARAM_STR);
          $stmt->bindParam(':card_number', $cardNumber, PDO::PARAM_STR);
          $stmt->bindParam(':cardholder_name', $cardholderName, PDO::PARAM_STR);
          $stmt->bindParam(':expiry_date', $expiryDate, PDO::PARAM_STR);
          $stmt->bindParam(':cvv', $cvv, PDO::PARAM_STR);
          $stmt->bindParam(':balance', $initialBalance, PDO::PARAM_STR);
          $stmt->execute();

          echo "Card added successfully.";

      } catch (PDOException $e) {
          error_log("Card insertion error: " . $e->getMessage(), 0);
          echo "Error adding card: " . $e->getMessage();
      }
    }

   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="description" content="Capital Vista" />
      <meta name="keywords" content="Capital Vista" />
      <meta name="author" content="Capital Vista" />
      <link rel="manifest" href="./manifest.json" />
      <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon" />
      <title>Capital Vista App</title>
      <link rel="apple-touch-icon" href="assets/images/logo/favicon.png" />
      <meta name="theme-color" content="#122636" />
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta name="apple-mobile-web-app-status-bar-style" content="black" />
      <meta name="apple-mobile-web-app-title" content="Capital Vista" />
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
               <h2>My cards</h2>
               <a href="#add-card" class="back-btn" data-bs-toggle="modal">
               <i class="icon" data-feather="plus"></i>
               </a>
            </div>
         </div>
      </header>
      <!-- header end -->
      <!-- cards section starts -->
      <section class="section-b-space">
         <div class="custom-container">
          <?php if (empty($cards)): ?>
            <p>No cards found. Please add a new card.</p>
          <?php else: ?>
            <ul class="card-list">
              <?php foreach ($cards as $card): ?> 
                <li class="credit-card-box color1">
                    <div class="card-logo">
                      <?php
                        $imageSrc = '';
                        if ($card['card_type'] == 'master_card') {
                            $imageSrc = 'mastercard.png';
                        } elseif ($card['card_type'] == 'visa_card') {
                            $imageSrc = 'visa.svg';
                        } elseif ($card['card_type'] == 'rupay_card') {
                          $imageSrc = 'rupay.png';
                        } elseif ($card['card_type'] == 'amex_card') {
                          $imageSrc = 'amex.png';
                        } else {
                            $imageSrc = 'default'; // Fallback image
                        }
                      ?>
                      <img class="img-fluid" src="assets/images/card/<?php echo $imageSrc; ?>" style="width:30px;" alt="icon" />
                      <div class="dropdown">
                          <a href="#" class="back-btn" role="button" data-bs-toggle="dropdown">
                          <i class="icon" data-feather="more-horizontal"></i>
                          </a>
                          <!-- <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#edit-card" data-bs-toggle="modal">Edit</a></li>
                            <li><a class="dropdown-item" href="#delate" data-bs-toggle="modal">Remove</a></li>
                          </ul> -->
                      </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                        <?php
                          // Mask all but the last four digits of the card number
                          $maskedCardNumber = str_repeat('*', 12) . substr($card['card_number'], -4);
                        ?>
                          <h6 class="card-number"><?php echo htmlspecialchars($maskedCardNumber); ?></h6>
                          <h5 class="card-name"><?php echo htmlspecialchars($card['cardholder_name']); ?></h5>
                      </div>
                      <img class="img-fluid chip" src="assets/images/svg/card-chip.svg" alt="card-chip" />
                    </div>
                    <div class="d-flex justify-content-between">
                      <h2 class="card-amount">$<?php echo htmlspecialchars($card['balance']); ?></h2>
                      <div class="card-date w-100">
                          <h6>Exp. date</h6>
                          <h6 class="text-white fw-semibold mt-1"><?php echo htmlspecialchars($card['expiry_date']); ?></h6>
                      </div>
                      <div class="card-numbers w-100">
                          <h6 class="cvv-code">Cvv</h6>
                          <h6 class="text-white fw-semibold mt-1"><?php echo htmlspecialchars($card['cvv']); ?></h6>
                      </div>
                    </div>
                </li>
              <?php endforeach; ?>        
            </ul>
          <?php endif; ?>
         </div>
      </section>
      <!-- cards section end -->

      <!-- add card modal starts -->
      <div class="modal add-money-modal fade" id="add-card" tabindex="-1">
         <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post">
              <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add Card</h2>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="inputcards" class="form-label mb-2">Card type</label>
                      <div class="d-flex gap-2">
                          <select id="inputcards" name="card_type" class="form-select">
                            <option selected>Select card type</option>
                            <option value="master_card">Master Card</option>
                            <option value="visa_card">VISA Card</option>
                            <option value="amex_card">AMEX Card</option>
                            <option value="rupay_card">RuPay Card</option>                            
                          </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Card Number</label>
                      <div class="form-input mb-3">                          
                          <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Enter card number" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Placeholder Name</label>
                      <div class="form-input mb-3">
                          <input type="text" name="cardholder_name" class="form-control" placeholder="Enter Placeholder number" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-7">
                          <div class="form-group">
                            <label class="form-label">Exp. Date</label>
                            <div class="form-input mb-3">
                                <input type="date" name="expiry_date" class="form-control" />
                            </div>
                          </div>
                      </div>
                      <div class="col-5">
                          <div class="form-group">
                            <label class="form-label">CVV</label>
                            <div class="form-input mb-3">
                              <input type="number" name="cvv" class="form-control" id="cvv" maxlength="4" placeholder="Enter cvv" />
                            </div>
                          </div>
                      </div>
                    </div>
                    <button type="submit"  class="btn theme-btn successfully w-100">Add Card</button>
                </div>
                <button type="button" class="btn close-btn" data-bs-dismiss="modal">
                <i class="icon" data-feather="x"></i>
                </button>
              </div>
            </form>            
         </div>
      </div>
      <!-- add card modal end -->

      <!-- edit card modal starts -->
      <!-- <div class="modal add-money-modal fade" id="edit-card" tabindex="-1">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h2 class="modal-title">Edit Card</h2>
               </div>
               <div class="modal-body">
                  <div class="form-group">
                     <label for="inputcard" class="form-label mb-2">Card type</label>
                     <div class="d-flex gap-2">
                        <select id="inputcard" class="form-select">
                           <option>Select card type</option>
                           <option>Master Card</option>
                           <option selected>Visa Card</option>
                           <option>RuPay Card</option>
                           <option>Business Card</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="inputcardnumber" class="form-label">Card Number</label>
                     <div class="form-input">
                        <input type="text" class="form-control" maxlength="16" id="inputcardnumber" value="**** **** **** 2563"
                           placeholder="Enter card number" />
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-7">
                        <div class="form-group">
                           <label class="form-label">Exp. Date</label>
                           <div class="form-input mb-3">
                              <input type="date" value="2024-12-31" class="form-control" />
                           </div>
                        </div>
                     </div>
                     <div class="col-5">
                        <div class="form-group">
                           <label class="form-label">CVV</label>
                           <div class="form-input mb-3"><input type="number" maxlength="3" id="cvv" class="form-control" placeholder="Enter cvv" /></div>
                        </div>
                     </div>
                  </div>
                  <a href="cards.php" class="btn theme-btn successfully w-100">Edit Card</a>
               </div>
               <button type="button" class="btn close-btn" data-bs-dismiss="modal">
               <i class="icon" data-feather="x"></i>
               </button>
            </div>
         </div>
      </div> -->
      <!-- edit card modal end -->
       
      <!-- delate card modal start -->
      <!-- <div class="modal error-modal fade" id="delate" tabindex="-1">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-body">
                  <div class="error-img">
                     <img class="img-fluid" src="assets/images/svg/delate.svg" alt="delate" />
                  </div>
                  <h3>Are you sure you want to delete this card ?</h3>
                  <a href="#done-delate" class="btn theme-btn successfully w-100" data-bs-toggle="modal">Delete card</a>
               </div>
               <button type="button" class="btn close-btn" data-bs-dismiss="modal">
               <i class="icon" data-feather="x"></i>
               </button>
            </div>
         </div>
      </div> -->
      <!-- delate card modal end -->

      <!--successful delate card modal start -->
      <!-- <div class="modal error-modal fade" id="done-delate" tabindex="-1">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h2 class="modal-title">Successfully Delete</h2>
               </div>
               <div class="modal-body">
                  <div class="error-img">
                     <img class="img-fluid" src="assets/images/svg/delate.svg" alt="delate" />
                  </div>
                  <h3>Are you sure you want to delete this card ?</h3>
               </div>
               <button type="button" class="btn close-btn" data-bs-dismiss="modal">
               <i class="icon" data-feather="x"></i>
               </button>
            </div>
         </div>
      </div> -->
      <!-- successful delate card modal end -->

      
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

      <script>
        document.getElementById('card_number').addEventListener('input', function (e) {
            if (this.value.length > 16) {
                this.value = this.value.slice(0, 16); // Trim to the first 16 characters
            }
        });

        document.getElementById('cvv').addEventListener('input', function (e) {
            if (this.value.length > 3) {
                this.value = this.value.slice(0, 3); // Trim to the first 16 characters
            }
        });
      </script>
   </body>
</html>