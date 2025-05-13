<?php
session_start();
include('assets/inc/config.php'); //get configuration file

if (isset($_POST['doc_login'])) {
    $username = $_POST['username']; // Get username from input
    $doc_pwd = $_POST['doc_pwd']; // Get password directly without hashing

    $stmt = $mysqli->prepare("SELECT username, password, id FROM users WHERE username=? AND password=?");
    $stmt->bind_param('ss', $username, $doc_pwd); // Bind parameters
    $stmt->execute(); // Execute the statement
    $stmt->bind_result($db_username, $db_password, $doc_id); // Bind result

    if ($stmt->fetch()) { // Check if a row was returned
        // Assign session variables
        $_SESSION['doc_id'] = $doc_id; // Keep doc_id unchanged
        $_SESSION['username'] = $db_username; // Use username for clarity
        header("location:dashboard.php"); // Redirect to dashboard
    } else {
        $err = "Access Denied Please Check Your Credentials"; // Set error message
    }
}
?>
<!--End Login-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Electronic Store Management System -A Super Responsive Information System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="MartDevelopers" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!--Load Sweet Alert Javascript-->

    <script src="assets/js/swal.js"></script>
    <!--Inject SWAL-->
    <?php if (isset($success)) { ?>
        <!--This code for injecting an alert-->
        <script>
            setTimeout(function() {
                    swal("Success", "<?php echo $success; ?>", "success");
                },
                100);
        </script>

    <?php } ?>

    <?php if (isset($err)) { ?>
        <!--This code for injecting an alert-->
        <script>
            setTimeout(function() {
                    swal("Failed", "<?php echo $err; ?>", "error");
                },
                100);
        </script>

    <?php } ?>



</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="index.php">
                                    <span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
                                </a>
                                <p class="text-muted mb-4 mt-3">Enter your email address and password to access Employee panel.</p>
                            </div>

                            <form method='post'>

                                <div class="form-group mb-3">
                                    <label for="username">Username</label>
                                    <input class="form-control" name="username" type="text" id="username" required placeholder="Enter your username">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input class="form-control" name="doc_pwd" type="password" required id="password" placeholder="Enter your password">
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-success btn-block" name="doc_login" type="submit">Log In</button>
                                </div>

                            </form>


                            <div class="text-center">
                                <h5 class="mt-3 text-muted">Sign in with</h5>
                                <ul class="social-list list-inline mt-3 mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github-circle"></i></a>
                                    </li>
                                </ul>
                            </div>


                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="#" class="text-white-50 ml-1">Forgot your password?</a></p>
                            <!-- <p class="text-white-50">Don't have an account? <a href="his_admin_register.php" class="text-white ml-1"><b>Sign Up</b></a></p>-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <?php include("assets/inc/footer1.php"); ?>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>