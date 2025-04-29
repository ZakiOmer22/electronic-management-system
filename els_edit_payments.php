<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['edit_payment'])) {
    // Retrieve the payment ID from the form
    $payment_id = $_POST['payment_id'];
    $sale_id = $_POST['sale_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_method = $_POST['payment_method'];

    // Step 1: Retrieve the current payment details for backup
    $payment_query = "SELECT * FROM payments WHERE id = ?";
    $stmt = $mysqli->prepare($payment_query);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment_data = $result->fetch_assoc();
    $stmt->close();

    // Step 2: Insert the payment details into a backup table
    $backup_query = "INSERT INTO payments_backup (payment_id, sale_id, amount_paid, payment_method, backup_date)
                     VALUES (?, ?, ?, ?, NOW())";
    $stmt_backup = $mysqli->prepare($backup_query);
    $stmt_backup->bind_param("iisd", $payment_id, $sale_id, $amount_paid, $payment_method);

    // Step 3: Execute the backup insertion
    if ($stmt_backup->execute()) {
        // Now proceed with the actual update
        $update_query = "UPDATE payments SET amount_paid = ?, payment_method = ? WHERE id = ?";
        $stmt_update = $mysqli->prepare($update_query);
        $stmt_update->bind_param("dsi", $amount_paid, $payment_method, $payment_id);

        if ($stmt_update->execute()) {
            $success_message = "Payment updated successfully.";
        } else {
            $error_message = "Error updating payment: " . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        $error_message = "Error backing up payment data: " . $stmt_backup->error;
    }

    $stmt_backup->close();
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
                                <h4 class="page-title">Edit Payments Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Payment</h4>
                                    <!-- Edit Payment Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputSaleId" class="col-form-label">Sale ID</label>
                                                <input type="number" required name="sale_id" class="form-control" id="inputSaleId" placeholder="Sale ID" value="<?php echo isset($payment['sale_id']) ? $payment['sale_id'] : ''; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputAmountPaid" class="col-form-label">Amount Paid</label>
                                                <input type="number" required name="amount_paid" class="form-control" id="inputAmountPaid" placeholder="Amount Paid" step="0.01" value="<?php echo isset($payment['amount_paid']) ? $payment['amount_paid'] : ''; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPaymentMethod" class="col-form-label">Payment Method</label>
                                                <select name="payment_method" class="form-control" id="inputPaymentMethod" required>
                                                    <option value="cash" <?php echo isset($payment['payment_method']) && $payment['payment_method'] == 'cash' ? 'selected' : ''; ?>>Cash</option>
                                                    <option value="credit" <?php echo isset($payment['payment_method']) && $payment['payment_method'] == 'credit' ? 'selected' : ''; ?>>Credit</option>
                                                    <option value="debit" <?php echo isset($payment['payment_method']) && $payment['payment_method'] == 'debit' ? 'selected' : ''; ?>>Debit</option>
                                                    <option value="online" <?php echo isset($payment['payment_method']) && $payment['payment_method'] == 'online' ? 'selected' : ''; ?>>Online</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="payment_id" value="<?php echo isset($payment['id']) ? $payment['id'] : ''; ?>">
                                        <button type="submit" name="edit_payment" class="ladda-button btn btn-primary" data-style="expand-right">Update Payment</button>
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