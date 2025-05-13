<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$doc_id = $_SESSION['doc_id'];

// Get payment ID from URL
$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$payment_id) {
    echo "<div class='alert alert-danger'>Invalid payment ID.</div>";
    exit;
}

// Fetch payment and customer details
$payment_query = "
    SELECT p.*
    FROM payments p
    WHERE p.id = ?
";
$payment_stmt = $mysqli->prepare($payment_query);
$payment_stmt->bind_param("i", $payment_id);
$payment_stmt->execute();
$payment_result = $payment_stmt->get_result();
$payment = $payment_result->fetch_object();

if (!$payment) {
    echo "<div class='alert alert-danger'>Payment not found.</div>";
    exit;
}

// Fetch all related payments (e.g., split payments for the same sale)
$payment_details = [];
$details_query = "SELECT * FROM payments WHERE sale_id = ?";
$details_stmt = $mysqli->prepare($details_query);
$details_stmt->bind_param("i", $payment->sale_id);
$details_stmt->execute();
$details_result = $details_stmt->get_result();

while ($row = $details_result->fetch_object()) {
    $payment_details[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('assets/inc/sidebar.php'); ?>
        <!-- Left Sidebar End -->


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Payment Details</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Payment Report - ID: <?php echo intval($payment->id); ?></h4>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">

                                <div class="clearfix">
                                    <div class="float-left">
                                        <img src="assets/images/logo-dark.png" alt="" height="20">
                                    </div>
                                    <div class="float-right">
                                        <h4 class="m-0 d-print-none">
                                            Payment Receipt
                                        </h4>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($payment->customer_name ?? 'Walk-in'); ?></p>
                                            <p><strong>Amount Paid:</strong> $<?php echo number_format($payment->amount_paid, 2); ?></p>
                                            <p><strong>Handled By (User ID):</strong> <?php echo intval($payment->id); ?></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-md-2">
                                        <div class="mt-3 float-right">
                                            <p><strong>Payment Date:</strong>
                                                <span class="float-right"><?php echo date("d-m-Y h:i:s A", strtotime($payment->payment_date)); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5>Payment Breakdown</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-centered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Payment Method</th>
                                                        <th>Amount Paid</th>
                                                        <th>Payment Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $counter = 1;
                                                    $total = 0;
                                                    foreach ($payment_details as $detail) {
                                                        $total += $detail->amount_paid;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $counter++; ?></td>
                                                            <td><?php echo htmlspecialchars($detail->payment_method); ?></td>
                                                            <td>$<?php echo number_format($detail->amount_paid, 2); ?></td>
                                                            <td><?php echo date("d-m-Y h:i:s A", strtotime($detail->payment_date)); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3" class="text-right">Total</th>
                                                        <th>$<?php echo number_format($total, 2); ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-sm-6">
                                        <h6 class="text-muted">Notes:</h6>
                                        <small class="text-muted">
                                            This report includes all payments made toward the associated sale transaction.
                                        </small>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <h5 class="text-muted">Payment ID: <?php echo intval($payment->id); ?></h5>
                                    </div>
                                </div>

                                <div class="mt-4 mb-1 text-right d-print-none">
                                    <a href="javascript:window.print()" class="btn btn-primary">
                                        <i class="mdi mdi-printer mr-1"></i> Print
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include("assets/inc/footer.php"); ?>
            <!-- end Footer -->

        </div> <!-- content-page -->
    </div> <!-- wrapper -->
    <!-- End page -->


    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>