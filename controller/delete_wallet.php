<?php
    // Include database connection code or establish a database connection
    session_start();
    
    if(isset($_SESSION['admin_id']))
    {
        $admin_username = $_SESSION['admin_username'];
    }
    else
    {
        header('location: login.php');
    }

    include('db_connection.php');

    // Check if user ID is provided in the URL
    if (isset($_GET['id'])) {
        $wallet_id = $_GET['id'];

        // Prepare and execute SQL query to fetch user data based on the provided ID
        $sql = "SELECT * FROM wallets WHERE id = :wallet_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['wallet_id' => $wallet_id]);
        
        // Fetch user data
        $wallet_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user data is found
        if (!$wallet_data) {
            echo "Wallet not found.";
            exit;
        }        

    } else {
        // If no user ID is provided in the URL, redirect the user or display an error message
        header("Location: error.php");
        exit;
    }
?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Delete User | Admin Ragnarok</title>
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
                                    <h4 class="mb-sm-0 font-size-18">Delete Wallet - WALLET_ID = <?= $wallet_data['id'];?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                            <li class="breadcrumb-item active">Delete Wallet</li>
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
                                        <h4 class="card-title">Wallet Information</h4>
                                        <p class="card-title-desc">This form contains wallet Information for <strong><?= $wallet_data['crypto']; ?></strong>, be careful how you proceed.</p>
                                    </div>
                                    <!-- end card header -->

                                    <div class="card-body">
                                        <div>
                                            <h5 class="card-title mb-4">Wallet Information</h5>
                                            <form action="codes.php" method="POST">
                                                <div class="mb-3">
                                                    <label for="example-text-input" class="form-label">Wallet Type</label>
                                                    <input type="hidden" name="wallet_id" value="<?= $wallet_data['id']; ?>">                                                    
                                                    <input class="form-control" type="text" name="crypto" value="<?= $wallet_data['crypto']; ?>" require id="example-text-input">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="example-search-input" class="form-label">Shortcode</label>
                                                    <input class="form-control" type="text" name="shortcode" value="<?= $wallet_data['shortcode']; ?>" require id="example-text-input">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="example-search-input" class="form-label">Wallet Address</label>
                                                    <input class="form-control" type="text" name="wallet_address" value="<?= $wallet_data['wallet_address']; ?>" require id="example-text-input">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="example-search-input" class="form-label">QR CODE</label>
                                                    <br>
                                                    <img src="<?= $wallet_data['qr_code_path'];  ?>" style="width:10%;" alt="">
                                                </div>
                                                <!-- end row -->
                                                <div class="form-group">
                                                    <button  type="" name="delete_wallet" class="btn btn-danger">Delete Wallet</button>
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
        <?php include('includes/right_sidebar.php');?>
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
