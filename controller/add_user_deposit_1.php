<?php
    require_once 'db_connection.php';

    session_start();

    if(isset($_SESSION['admin_id']))
    {
        $admin_username = $_SESSION['admin_username'];

        // Retrieve admin details based on session
        $admin_id = $_SESSION['admin_id'];
    }
    else
    {
        header("Location: login.php");
    }    

    // Check if user ID is provided in the URL
    if (isset($_GET['userid'])) {
        $userid = $_GET['userid'];

        // Prepare and execute SQL query to fetch user data based on the provided ID
        $sql = "SELECT * FROM users WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid]);
        
        // Fetch user data
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user data is found
        if (!$user_data) {
            echo "User not found.";
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
        <title>Add Deposit | Admin Ragnarok</title>
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Edit</a></li>
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
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Add Deposit for - <?= $user_data['f_name']. ' '.  $user_data['l_name']?></h4>
                                                <p class="card-title-desc">Here you can add a deposit.</p>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <form action="codes.php" method="post" enctype="multipart/form-data">
                                                            <div> 
                                                            <div class="mb-3">
                                                                    <label for="stock_name">Method</label>
                                                                    <input class="form-control" type="text" name="method" placeholder="eg. Bitcoin, Ethereum etc." required id="example-text-input">
                                                                </div>                                                                
                                                                <div class="mb-3">
                                                                    <label for="stock_name">Amount to Deposit</label>
                                                                    <input type="hidden" name="userid" value="<?= $user_data['userid']; ?>">
                                                                    <input type="hidden" name="f_name" value="<?= $user_data['f_name']; ?>">
                                                                    <input type="hidden" name="l_name" value="<?= $user_data['l_name']; ?>">
                                                                    <input class="form-control" type="number" name="amount" placeholder="eg. 2000" required id="example-text-input">
                                                                </div>                                                                                                                                                                                                                                                          
                                                                <div class="mb-3">
                                                                    <button class="btn btn-success" type="submit" name="add_deposit">Add</button>
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
