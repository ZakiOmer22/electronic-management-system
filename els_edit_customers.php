<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['edit_customer'])) {
    $customer_id = $_POST['customer_id']; // Get the customer ID
    $cust_name = trim($_POST['cust_name']); // Get customer name
    $cust_phone = trim($_POST['cust_phone']); // Get phone number
    $cust_address = trim($_POST['cust_address']); // Get address

    $errors = [];

    // Validate input data
    if (empty($cust_name)) {
        $errors[] = "Customer name is required.";
    }

    if (empty($cust_phone)) {
        $errors[] = "Phone number is required.";
    }

    if (empty($cust_address)) {
        $errors[] = "Address is required.";
    }

    // If there are no errors, proceed with updating the customer
    if (empty($errors)) {
        // Prepare SQL query to update customer data
        $query = "UPDATE customers SET name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssi", $cust_name, $cust_phone, $cust_address, $customer_id);

        // Execute the update query
        if ($stmt->execute()) {
            $success = "Customer updated successfully.";
        } else {
            $err = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // If validation failed, show the errors
        $err = implode("<br>", $errors);
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Customer</h4>
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputCustomerID" class="col-form-label">Customer ID</label>
                                                <input type="text" class="form-control" id="inputCustomerID" value="<?php echo isset($customer['id']) ? $customer['id'] : ''; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label for="inputCustomerName" class="col-form-label">Customer Name</label>
                                                <input type="text" required name="cust_name" class="form-control" id="inputCustomerName" placeholder="Customer Name" value="<?php echo isset($customer['name']) ? $customer['name'] : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerPhone" class="col-form-label">Phone Number</label>
                                                <input type="text" required name="cust_phone" class="form-control" id="inputCustomerPhone" placeholder="Phone Number" value="<?php echo isset($customer['phone']) ? $customer['phone'] : ''; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerAddress" class="col-form-label">Address</label>
                                                <input type="text" required name="cust_address" class="form-control" id="inputCustomerAddress" placeholder="Address" value="<?php echo isset($customer['address']) ? $customer['address'] : ''; ?>">
                                            </div>
                                        </div>

                                        <input type="hidden" name="customer_id" value="<?php echo isset($customer['id']) ? $customer['id'] : ''; ?>">
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