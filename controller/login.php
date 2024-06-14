<?php
    session_start();

    require_once 'db_connection.php'; //

    if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        header("location: admin_dashboard.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $sql = "SELECT id, username, password FROM admin WHERE username = :username";

        if($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            $param_username = $username;

            if($stmt->execute()) {
                if($stmt->rowCount() == 1) {
                    $row = $stmt->fetch();
                    $id = $row['id'];
                    $username = $row['username'];
                    $hashed_password = $row['password'];
                    if($password === $hashed_password) {
                        session_start();
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $id;
                        $_SESSION['admin_username'] = $username;
                        header("location: admin_dashboard.php");
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->closeCursor();
        }
    }
?>
<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Login | Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
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

        <!-- Google Icon -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    </head>

    <body>

    <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="container col-xxl-4 col-lg-5 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                        <a href="index.html" class="d-block auth-logo">
                                            <img src="assets/images/logo-sm.svg" alt="" height="28"> <span class="logo-txt">RAGNAROK</span>
                                        </a>
                                    </div>
                                    <div align="center">
                                        <img  src="assets/images/dashboard.svg" style="margin-bottom:20px; width: 50%;" alt="">
                                    </div>                                    
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Welcome Back!</h5>
                                            <p class="text-muted mt-2">Sign in to continue to Ragnarok.</p>
                                        </div>
                                        <?php 
                                            if(isset($login_err))
                                                {
                                                    ?>
                                                        <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                                                            <i class="mdi mdi-alert-outline align-middle me-3"></i><strong>Warning</strong> - <?php echo $login_err; ?>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>
                                                    <?php
                                                }
                                        ?>                                           
                                        <form class="mt-4 pt-2" action="" method="post">
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" placeholder="Enter username">
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Password</label>
                                                    </div>                                                   
                                                </div>
                                                
                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" name="password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><span class="material-symbols-outlined">visibility</span></button>
                                                </div>
                                            </div>                                            
                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit" name="login">Log In</button>
                                            </div>
                                        </form>                                        
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>                    
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>


        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <!-- pace js -->
        <script src="assets/libs/pace-js/pace.min.js"></script>
        <!-- password addon init -->
        <script src="assets/js/pages/pass-addon.init.js"></script>

        <!-- Alert init js -->
        <script src="assets/js/pages/alert.init.js"></script>

    </body>

</html>