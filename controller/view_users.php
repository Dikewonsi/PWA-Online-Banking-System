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
    $sql = "SELECT * FROM `users`";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the statement
        $stmt->execute();
        // Fetch all rows as an associative array
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: Could not execute query. " . $e->getMessage());
    }

?>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>View User | Admin Ragnarok</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                    <h4 class="mb-sm-0 font-size-18">View Users</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
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
                                        <h4 class="card-title">Users Datatable</h4>                                        
                                    </div>
                                    <div class="card-body">
        
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Country</th>
                                                <th>Profile Photo</th>
                                                <th>Account Balance</th>
                                                <th>Referral Bonus</th>
                                                <th>User Status: </th>
                                                <th>Total Referred: </th>
                                                <th>Email Status: </th>
                                                <th>Residency Status: </th>
                                                <th>ID Status: </th>
                                                <th>Registered At: </th>
                                                <th>Modified At: </th>                                                
                                            </tr>
                                            </thead>   
                                            <tbody>
                                                <?php foreach ($users as $user): ?>
                                                    <tr>
                                                        <td><?= $user['userid'] ?></td>
                                                        <td><?= $user['f_name'] ?></td>
                                                        <td><?= $user['l_name'] ?></td>
                                                        <td><?= $user['email'] ?></td>
                                                        <td><?= $user['phone'] ?></td>
                                                        <td><?= $user['country'] ?></td>
                                                        <td><?= $user['profile_photo'] ?></td>
                                                        <td>$<?= number_format($user['acc_balance']) ?></td>
                                                        <td>$<?= number_format($user['referral_bonus']) ?></td>
                                                        <td>
                                                            <?php
                                                                $status = $user['user_status'];

                                                                if($status == 0)
                                                                {
                                                                    echo '<span style="color:red">Not Active</span>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<span style="color:green">Active</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?= $user['total_referred'] ?></td>
                                                        <td>                                                            
                                                            
                                                            <?php
                                                                $status = $user['user_status'];

                                                                if($status = 'not_verified')
                                                                {
                                                                    echo '<span style="color:red">Not Verified</span>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<span style="color:green">Verified</span>';
                                                                }
                                                            ?>

                                                        </td>
                                                        <td>
                                                            <?php
                                                                $status = $user['residency_status'];

                                                                if($status = 'not_verified')
                                                                {
                                                                    echo '<span style="color:red">Not Verified</span>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<span style="color:green">Verified</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $status = $user['id_status'];

                                                                if($status = 'not_verified')
                                                                {
                                                                    echo '<span style="color:red">Not Verified</span>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<span style="color:green">Verified</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?= $user['registered_at'] ?></td>
                                                        <td><?= $user['modified_at'] ?></td>                                                        
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
