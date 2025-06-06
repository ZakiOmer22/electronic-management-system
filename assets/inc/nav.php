<?php
include('assets/inc/config.php'); // Include configuration file

$doc_id = $_SESSION['doc_id'];
$username = $_SESSION['username']; // Use 'username' instead of 'doc_number'

$ret = "SELECT * FROM users WHERE id = ? AND username = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('is', $doc_id, $username); // Bind parameters: id and username
$stmt->execute(); // Execute the statement
$res = $stmt->get_result();

while ($row = $res->fetch_object()) {
?>
    <div class="navbar-custom">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="d-none d-sm-block">
                <form class="app-search">
                    <div class="app-search-box">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <div class="input-group-append">
                                <button class="btn" type="submit">
                                    <i class="fe-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </li>

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/users/<?php echo $row->profile_photo; ?>" alt="pic" class="rounded-circle"> <!-- Updated to use profile_photo -->
                    <span class="pro-user-name ml-1">
                        <?php echo $row->firstname; ?> <?php echo $row->lastname; ?> <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                    </div>

                    <a href="update-account.php" class="dropdown-item notify-item">
                        <i class="fas fa-user-tag"></i>
                        <span>Update Account</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="logout.php" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="dashboard.php" class="logo text-center">
                <span class="logo-lg">
                    <img src="assets/images/logo-light.png" alt="" height="18">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm-white.png" alt="" height="24">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li class="dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    Create New
                    <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="els_add_customers.php" class="dropdown-item">
                        <i class="fe-activity mr-1"></i>
                        <span>Customers</span>
                    </a>
                    <a href="els_add_payments.php" class="dropdown-item">
                        <i class="fe-hard-drive mr-1"></i>
                        <span>Payments</span>
                    </a>
                    <a href="els_add_sales.php" class="dropdown-item">
                        <i class="fe-activity mr-1"></i>
                        <span>Sales</span>
                    </a>
                    <div class="dropdown-divider"></div>
                </div>
            </li>
        </ul>
    </div>
<?php } ?>