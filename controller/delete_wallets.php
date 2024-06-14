<?php 
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

    // Query to retrieve user data
    $sql = "SELECT * FROM `wallets`";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the statement
        $stmt->execute();
        // Fetch all rows as an associative array
        $wallets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: Could not execute query. " . $e->getMessage());
    }

?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Delete Wallets | Admin Ragnarok</title>
        <meta name="Deleteport" content="width=device-width, initial-scale=1.0">
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- DataTables -->
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" /> 

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
                                    <h4 class="mb-sm-0 font-size-18">Delete Wallets</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Wallets</a></li>
                                            <li class="breadcrumb-item active">DataTables</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Wallets Datatable</h4>                                         
                                    </div>
                                    <?php
                                        if (isset($_GET['success']) && $_GET['success'] == '1')
                                        {
                                    ?>
                                            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - Wallet Deleted Successfully
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
        
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Crypto</th>
                                                <th>Shortcode</th>
                                                <th>Wallet Address</th>
                                                <th>QR CODE</th>
                                                <th>Date Added</th>
                                                <th>Date Modified</th>
                                                <th>Added By</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>   
                                            <tbody>
                                                <?php foreach ($wallets as $wallet): ?>
                                                    <tr>
                                                        <td><?= $wallet['id'] ?></td>
                                                        <td><?= $wallet['crypto'] ?></td>
                                                        <td><?= $wallet['shortcode'] ?></td>
                                                        <td><?= $wallet['wallet_address'] ?></td>
                                                        <td><img style="width:50%;" src="<?= $wallet['qr_code_path']; ?>" alt=""></td>
                                                        <td><?= $wallet['date_added'] ?></td>
                                                        <td><?= $wallet['modified_at'] ?></td>
                                                        <td><?= $wallet['modified_by'] ?></td>  
                                                        <td>
                                                            <a class="btn btn-danger btn-sm edit" href="delete_wallet.php?id=<?= $wallet['id']; ?>">
                                                             DELETE
                                                            </a>
                                                        </td>                                                       
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                                
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->        

        <!-- Include jQuery -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Include DataTables JavaScript -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

        
    
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

        <!-- Required datatable js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <!-- Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>    

        <script src="assets/js/app.js"></script>

    </body>
</html>
