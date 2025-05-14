<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['edit_customer'])) {
    // Retrieve form data
    $customer_id = $_POST['customer_id'];
    $cust_name = $_POST['cust_name'];
    $cust_phone = $_POST['cust_phone'];
    $cust_address = $_POST['cust_address'];

    // Update the customer in the database
    $update_query = "UPDATE customers SET name = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $mysqli->prepare($update_query);
    // Adjust the bind_param based on the number of placeholders
    $stmt->bind_param("sssi", $cust_name, $cust_phone, $cust_address, $customer_id);

    if ($stmt->execute()) {
        $success_message = "Customer updated successfully.";
    } else {
        $error_message = "Error updating customer: " . $stmt->error;
    }

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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Customers</a></li>
                                        <li class="breadcrumb-item active">Add Customers</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Customers Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Customer</h4>
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputCustomerID" class="col-form-label">Customer ID</label>
                                                <input type="text" name="customer_id" class="form-control" id="inputCustomerID" placeholder="Customer ID" required>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label for="inputCustomerName" class="col-form-label">Customer Name</label>
                                                <input type="text" name="cust_name" class="form-control" id="inputCustomerName" placeholder="Customer Name" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerPhone" class="col-form-label">Customer Phone</label>
                                                <input type="text" name="cust_phone" class="form-control" id="inputCustomerPhone" placeholder="Customer Phone" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputCustomerAddress" class="col-form-label">Address</label>
                                            <textarea name="cust_address" class="form-control" id="inputCustomerAddress" placeholder="Customer Address" required></textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" name="edit_customer" class="ladda-button btn btn-primary" data-style="expand-right">Update Customer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
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