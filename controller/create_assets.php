<?php
    require_once 'db_connection.php';

    session_start();

    if(isset($_SESSION['admin_id']))
    {
        $admin_username = $_SESSION['admin_username'];
    }
    else
    {
        header("Location: login.php");
    }  
    
    // Retrieve admin details based on session
    $admin_id = $_SESSION['admin_id'];

?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Create Asset | Admin Ragnarok</title>
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
                                    <h4 class="mb-sm-0 font-size-18">Asset</h4>

                                    <div class="page-title-right">

                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Create</a></li>
                                            <li class="breadcrumb-item active">Asset</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm order-2 order-sm-1">
                                                <div class="d-flex align-items-start mt-3 mt-sm-0">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xl me-3">
                                                            <img src="assets/images/admin.png" alt="" class="img-fluid rounded-circle d-block">
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            <h5 class="font-size-16 mb-1"><?= $admin_username; ?></h5>
                                                            <p class="text-muted font-size-13">Full Stack Controller</p>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                           
                                        </div>                                        
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->

                                <!-- Form for displaying admin details -->
                                <div class="row">
                                    <div class="col-12">
                                    <?php
                                        if (isset($_GET['success']) && $_GET['success'] == '1')
                                        {
                                    ?>
                                            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - Asset Created Successfully
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                    <?php   
                                        }
                                        else if (isset($_GET['error']) && $_GET['error'] == '1')
                                        {
                                            // Check if there's an error message
                                            $errorMessage = isset($_GET['message']) ? $_GET['message'] : 'Something went wrong.';
                                    ?>
                                            <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                <i class="mdi mdi-block-helper label-icon"></i><strong>Error</strong> - <?php echo htmlspecialchars($errorMessage); ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Create Asset</h4>
                                                <p class="card-title-desc">Here you can create an Asset.</p>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <form action="codes.php" method="post" enctype="multipart/form-data">
                                                            <div>
                                                                <div class="mb-3">
                                                                    <label for="example-text-input" class="form-label">Asset Class</label>
                                                                    <input type="hidden" name="admin_username" value="<?= $admin_username; ?>">
                                                                    <select class="form-select" name="asset_class" required>
                                                                        <option value="">Select One</option>
                                                                        <option value="FOREX">FOREX</option>
                                                                        <option value="CRYPTO">CRYPTO</option>
                                                                        <option value="STOCKS">STOCKS</option>
                                                                    </select>                                                                
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="stock_name">Asset Ticker</label>
                                                                    <input class="form-control" type="text" name="asset_ticker" placeholder="Enter in this format BTC/USD" required id="example-text-input">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="stock_name">Percentage</label>
                                                                    <input class="form-control" type="number" name="percentage" placeholder="Enter figure from 1-100" required id="example-text-input">
                                                                </div> 
                                                                <div class="mb-3">
                                                                    <label for="stock_name">Trade Outcome</label>
                                                                    <select class="form-select" name="trade_outcome" required>
                                                                        <option value="">Select One</option>
                                                                        <option value="win">win</option>
                                                                        <option value="loss">loss</option>
                                                                    </select> 
                                                                </div>                                                                                                                                                                                            
                                                                <div class="mb-3">
                                                                    <button class="btn btn-primary" type="submit" name="create_asset">Create</button>
                                                                </div>                                                                                                                                                                                
                                                            </div>
                                                        </form>                                                        
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                                <!-- Form for displaying admin details -->

                                <!-- end tab content -->
                            </div>
                            <!-- end col -->
                           
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Ragnarok.
                            </div>                           
                        </div>
                    </div>
                </footer>
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

        <script src="assets/js/app.js"></script>

    </body>
</html>
