<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$doc_id = $_SESSION['doc_id'];


?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <?php include("assets/inc/nav.php"); ?>
        <?php include('assets/inc/sidebar.php'); ?>

        <?php
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $customer_id = intval($_GET['id']);
        } else {
            die("Invalid or missing customer ID.");
        }

        $query = "SELECT * FROM customers WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $customer = $res->fetch_object();

        if (!$customer) {
            die("Customer not found.");
        }

        function safe_html($value)
        {
            return htmlspecialchars($value ?? 'N/A');
        }

        function safe_date($value)
        {
            return $value ? date("d-m-Y - h:i:s A", strtotime($value)) : 'N/A';
        }
        ?>

        <div class="content-page">
            <div class="content">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">Customers</a></li>
                                        <li class="breadcrumb-item active">Generate Customer</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Customer Report</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">

                                <div class="clearfix">
                                    <div class="float-left">
                                        <img src="assets/images/logo-dark.png" alt="" height="20">
                                    </div>
                                    <div class="float-right">
                                        <h4 class="m-0 d-print-none"><?php echo safe_html($customer->full_name); ?>'s Report</h4>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <p><strong>Full Name:</strong> <?php echo safe_html($customer->full_name); ?></p>
                                            <p><strong>Email:</strong> <?php echo safe_html($customer->email); ?></p>
                                            <p><strong>Phone:</strong> <?php echo safe_html($customer->phone); ?></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-md-2">
                                        <div class="mt-3 float-right">
                                            <p><strong>Registered Date:</strong>
                                                <span class="float-right"><?php echo safe_date($customer->created_at); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Field</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Full Name</td>
                                                        <td><?php echo safe_html($customer->full_name); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Email</td>
                                                        <td><?php echo safe_html($customer->email); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Phone</td>
                                                        <td><?php echo safe_html($customer->phone); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Registered At</td>
                                                        <td><?php echo safe_date($customer->created_at); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="clearfix pt-5">
                                            <h6 class="text-muted">Notes:</h6>
                                            <small class="text-muted">
                                                This is an auto-generated customer report. Please verify information before printing.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="float-right">
                                            <h5 class="text-muted">Customer ID: <?php echo intval($customer->id); ?></h5>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <div class="mt-4 mb-1">
                                    <div class="text-right d-print-none">
                                        <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light">
                                            <i class="mdi mdi-printer mr-1"></i> Print
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container -->

            </div> <!-- content -->

            <?php include("assets/inc/footer.php"); ?>
        </div> <!-- content-page -->

    </div> <!-- wrapper -->





    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>