<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['edit_sale'])) {
    // Retrieve form inputs
    $sale_id = intval($_POST['sale_id']);
    $customer_id = intval($_POST['customer_id']);
    $user_id = intval($_POST['user_id']);
    $total_amount = floatval($_POST['total_amount']);

    // Validation (optional but recommended)
    $errors = [];
    if ($customer_id <= 0) $errors[] = "Invalid customer ID.";
    if ($user_id <= 0) $errors[] = "Invalid user ID.";
    if ($total_amount <= 0) $errors[] = "Total amount must be greater than 0.";

    if (empty($errors)) {
        // Prepare the SQL statement
        $update_query = "UPDATE sales SET customer_id = ?, user_id = ?, total_amount = ? WHERE id = ?";
        $stmt = $mysqli->prepare($update_query);

        if ($stmt) {
            $stmt->bind_param("iidi", $customer_id, $user_id, $total_amount, $sale_id);

            if ($stmt->execute()) {
                $success_message = "Sale updated successfully.";
            } else {
                $error_message = "Error updating sale: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Prepare failed: " . $mysqli->error;
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales</a></li>
                                        <li class="breadcrumb-item active">Add Sales</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Sales Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Sale</h4>
                                    <!-- Edit Sale Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputCustomerName" class="col-form-label">Customer Name</label>
                                                <input type="text" required="required" name="cust_name" class="form-control" id="inputCustomerName" placeholder="Customer Name" value="<?php echo isset($sale['customer_name']) ? $sale['customer_name'] : ''; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerPhone" class="col-form-label">Phone Number</label>
                                                <input type="text" required="required" name="cust_phone" class="form-control" id="inputCustomerPhone" placeholder="Phone Number" value="<?php echo isset($sale['phone']) ? $sale['phone'] : ''; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerAddress" class="col-form-label">Address</label>
                                                <input type="text" required="required" name="cust_address" class="form-control" id="inputCustomerAddress" placeholder="Address" value="<?php echo isset($sale['address']) ? $sale['address'] : ''; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputTotalAmount" class="col-form-label">Total Amount</label>
                                                <input type="number" required="required" name="total_amount" class="form-control" id="inputTotalAmount" placeholder="Total Amount" value="<?php echo isset($sale['total_amount']) ? $sale['total_amount'] : ''; ?>" step="0.01">
                                            </div>
                                        </div>

                                        <button type="submit" name="edit_sale" class="ladda-button btn btn-primary" data-style="expand-right">Update Sale</button>
                                        <input type="hidden" name="sale_id" value="<?php echo isset($sale['id']) ? $sale['id'] : ''; ?>">
                                    </form>
                                    <!-- End Sale Form -->
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