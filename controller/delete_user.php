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
        $userid = $_GET['id'];

        // Prepare and execute SQL query to fetch user data based on the provided ID
        $sql = "SELECT * FROM users WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid]);
        
        // Fetch user data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user data is found
        if (!$userData) {
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
                                    <h4 class="mb-sm-0 font-size-18">Delete User - USERID = <?= $userData['userid'];?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                            <li class="breadcrumb-item active">Delete User</li>
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
                                        <p class="card-title-desc">This form contains user Information for <strong><?= $userData['f_name'].' ' . $userData['l_name']; ?></strong>, be careful how you proceed.</p>
                                    </div>
                                    <!-- end card header -->

                                    <div class="card-body">
                                        <div>
                                            <h5 class="card-title mb-4">Personal Information</h5>
                                            <form action="codes.php" method="POST">
                                                <input type="hidden" name="userid" value="<?= $userData['userid']; ?>"/>
                                                <div class="row">
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>First Name</label>
                                                            <input type="text" name="f_name" required data-pristine-required-message="Please Enter a name" class="form-control" value="<?= $userData['f_name'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Last Name</label>
                                                            <input type="text" name="l_name" required data-pristine-required-message="Please Enter a name" class="form-control" value="<?= $userData['l_name'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Email</label>
                                                            <input type="email" name="email" required data-pristine-required-message="Please Enter a Email" class="form-control" value="<?= $userData['email']; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Phone Number</label>
                                                            <input type="text" name="phone" required data-pristine-required-message="Please Enter a phone number" class="form-control" value="<?= $userData['phone'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Country</label>
                                                            <input type="text" name="country" required data-pristine-required-message="Please Enter a Country" class="form-control" value="<?= $userData['country'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Account Balance</label>
                                                            <input type="number" name="acc_balance" required data-pristine-required-message="Please Enter an Amount" class="form-control" value="<?= $userData['acc_balance'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Referral Bonus</label>
                                                            <input type="text" name="referral_bonus" required data-pristine-required-message="Please Enter an amount" class="form-control" value="<?= $userData['referral_bonus'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Total Referred</label>
                                                            <input type="text" name="total_referred" required data-pristine-required-message="Please Enter an amount" class="form-control" value="<?= $userData['total_referred'];?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Email Status</label>
                                                            <select class="form-control form-select" name="email_status">
                                                                <option value="<?= $userData['email_status']; ?>">Selected - <?= $userData['email_status'];?></option>
                                                                <option value="not_verified">Not Verified</option>
                                                                <option value="verified">Verified</option>
                                                                <option value="pending">Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Residency Status</label>
                                                            <select class="form-control form-select" name="residency_status">
                                                                <option value="<?= $userData['residency_status']; ?>">Selected - <?= $userData['residency_status'];?></option>
                                                                <option value="not_verified">Not Verified</option>
                                                                <option value="verified">Verified</option>
                                                                <option value="pending">Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>ID Status</label>
                                                            <select class="form-control form-select" name="id_status">
                                                                <option value="<?= $userData['id_status']; ?>">Selected - <?= $userData['id_status'];?></option>
                                                                <option value="not_verified">Not Verified</option>
                                                                <option value="verified">Verified</option>
                                                                <option value="pending">Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Registered At</label>
                                                            <input class="form-control" name="registered_at" type="datetime-local" value="<?= $userData['registered_at']; ?>" id="example-datetime-local-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Modified At</label>
                                                            <input class="form-control" name="modified_at" type="datetime-local" value="<?= $userData['modified_at']; ?>" id="example-datetime-local-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label>Reason For Last Modification</label>
                                                            <input class="form-control" name="reason_for_mod" type="text" value="<?= $userData['reason_for_mod']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->
                                                <div class="form-group">
                                                    <button  type="" name="delete_user" class="btn btn-danger">Delete User</button>
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
