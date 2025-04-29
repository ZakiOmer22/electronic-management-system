<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['add_payment'])) {
    // Capture form inputs
    $sale_id = $_POST['sale_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_method = $_POST['payment_method'];

    // SQL query to insert captured values into the payments table
    $query = "INSERT INTO payments (sale_id, amount_paid, payment_method) VALUES (?, ?, ?)";

    // Prepare statement
    $stmt = $mysqli->prepare($query);

    // Bind parameters
    $rc = $stmt->bind_param('ids', $sale_id, $amount_paid, $payment_method);

    // Execute statement
    $stmt->execute();

    // Check if the payment was added successfully
    if ($stmt->affected_rows > 0) {
        $success = "Payment Added Successfully"; // Success message
    } else {
        $err = "Please Try Again Or Try Later"; // Error message
    }

    // Close the statement
    $stmt->close();
}

?>
<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">

<!--Head-->
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
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
                                        <li class="breadcrumb-item"><a href="his_doc_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Payments</a></li>
                                        <li class="breadcrumb-item active">Add Payments</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Payments Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Add Payment</h4>
                                    <!-- Add Payment Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputSaleId" class="col-form-label">Sale ID</label>
                                                <input type="number" required="required" name="sale_id" class="form-control" id="inputSaleId" placeholder="Sale ID">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputAmountPaid" class="col-form-label">Amount Paid</label>
                                                <input type="number" required="required" name="amount_paid" class="form-control" id="inputAmountPaid" placeholder="Amount Paid" step="0.01">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPaymentMethod" class="col-form-label">Payment Method</label>
                                                <select name="payment_method" class="form-control" id="inputPaymentMethod" required>
                                                    <option value="cash">Cash</option>
                                                    <option value="credit">Credit</option>
                                                    <option value="debit">Debit</option>
                                                    <option value="online">Online</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="add_payment" class="ladda-button btn btn-primary" data-style="expand-right">Add Payment</button>
                                    </form>
                                    <!-- End Payment Form -->
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>






                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>

</body>

</html>