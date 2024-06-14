<header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="admin_dashboard.php" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo-sm.svg" alt="" height="24">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">RAGNAROK</span>
                                </span>
                            </a>

                            <a href="admin_dashboard.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="assets/images/logo-sm.svg" alt="" height="24">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">RAGNAROK</span>
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                        
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item" id="mode-setting-btn">
                                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                            </button>
                        </div>

                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="grid" class="icon-lg"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <div class="p-2">
                                    <div class="row g-0">
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="view_wallets.php">
                                                <img src="assets/images/action/wallet.png" alt="Wallets">
                                                <span>Wallets</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="view_stocks.php">
                                                <img src="assets/images/action/stock.png" alt="Stocks">
                                                <span>Stocks</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="commodities.php">
                                                <img src="assets/images/action/commodity.png" alt="Commodity">
                                                <span>Commodities</span>
                                            </a>
                                        </div>                                        
                                    </div>

                                    <div class="row g-0">
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="bots.php">
                                                <img src="assets/images/action/bot.png" alt="Bots">
                                                <span>Bots</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="packages.php">
                                                <img src="assets/images/action/level-up.png" alt="Packages">
                                                <span>Packages</span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a class="dropdown-icon-item" href="view_users.php">
                                                <img src="assets/images/action/man.png" alt="Users">
                                                <span>Users</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="assets/images/admin.png"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium"><?= $admin_username; ?></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="admin_profile.php"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Profile</a>
                                <a class="dropdown-item" href="auth-lock-screen.html"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> Lock Screen</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                            </div>
                        </div>

                    </div>
                </div>
            </header>