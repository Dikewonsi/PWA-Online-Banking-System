<?php
    // Include database connection code or establish a database connection
    session_start();

    $admin_username = $_SESSION['admin_username'];
    $dsn = 'mysql:host=localhost;dbname=capitalvista';
    $username = 'root';
    $password = '';
    

    /* Attempt to connect to MySQL database */
    try {
        $pdo = new PDO($dsn, $username, $password);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Set character set to utf8
        $pdo->exec("SET NAMES 'utf8'");
    } catch(PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
 
    // Fetch all cards
    try {
        $stmt = $pdo->prepare("SELECT * FROM cards");
        $stmt->execute();
        $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Failed to fetch cards: " . $e->getMessage());
        $cards = [];
    }
?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Update User Balance | Admin Ragnarok</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- preloader css -->
        <link rel="stylesheet" href="assets/css/preloader.min.css" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body>

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
        <?php include('includes/header.php'); ?>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <?php include('includes/sidebar_menu.php'); ?>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Generate User Transaction</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                            <li class="breadcrumb-item active">Edit User</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->                        

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">User Information</h4>                                        
                                    </div>
                                    <!-- end card header -->

                                    <?php
                                        if (isset($_GET['success']) && $_GET['success'] == '1')
                                        {
                                    ?>
                                            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - User balance Updated Successfully
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                    <?php   
                                        }
                                        else if (isset($_GET['error']) && $_GET['error'] == '1')
                                        {
                                    ?>
                                            <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                <i class="mdi mdi-block-helper label-icon"></i><strong>Error</strong> - Something Went Wrong
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                    <?php
                                        }
                                    ?>

                                    <div class="card-body">
                                        <div>
                                            <h5 class="card-title mb-4">Personal Information</h5>
                                            <form action="codes.php" method="POST">
                                                <input type="hidden" name="userid" value="<?= $userData['user_id']; ?>"/>
                                                <div class="row">
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Select User</label>
                                                            <select class="form-control" name="card_id" id="card_id" required>
                                                                <?php foreach ($cards as $card): ?>
                                                                    <?php
                                                                        // Mask all but the last four digits of the card number
                                                                        $maskedCardNumber = str_repeat('*', 12) . substr($card['card_number'], -4);
                                                                    ?>
                                                                     <option value="<?php echo $card['card_id']; ?>">
                                                                        <?php echo $maskedCardNumber; ?> - Balance: $<?php echo $card['balance']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>New Balance</label>
                                                            <input class="form-control" type="number" step="0.01" name="amount" id="amount" required>
                                                        </div>
                                                    </div>                                                                                                                                                                                                                                                             
                                                </div>
                                                <!-- end row -->
                                                <div class="form-group">
                                                    <button  type="" name="update_user_card_balance" class="btn btn-primary">Submit form</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->                        
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <?php include('includes/right_sidebar.php'); ?>
        <!-- /Right-bar -->
        

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <!-- pace js -->
        <script src="assets/libs/pace-js/pace.min.js"></script>

       <!-- pristine js -->
       <script src="assets/libs/pristinejs/pristine.min.js"></script>
        <!-- form validation -->
       <script src="assets/js/pages/form-validation.init.js"></script>

        <script src="assets/js/app.js"></script>

    </body>
</html>
