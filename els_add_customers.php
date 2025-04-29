<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['add_customer'])) {
    // Capture form inputs
    $cust_name    = $_POST['cust_name'];
    $cust_phone   = $_POST['cust_phone'];
    $cust_address = $_POST['cust_address'];

    // SQL query to insert values into the customers table
    $query = "INSERT INTO customers (name, phone, address) VALUES (?, ?, ?)";

    // Prepare statement
    $stmt = $mysqli->prepare($query);

    // Bind parameters
    $rc = $stmt->bind_param('sss', $cust_name, $cust_phone, $cust_address);

    // Execute statement
    $stmt->execute();

    // Check if the customer was added successfully
    if ($stmt) {
        $success = "Customer Added Successfully";
    } else {
        $err = "Please Try Again Or Try Later";
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
                                <h4 class="page-title">Add Customers Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>
                                    <!-- Add Customer Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputCustomerName" class="col-form-label">Customer Name</label>
                                                <input type="text" required="required" name="cust_name" class="form-control" id="inputCustomerName" placeholder="Customer Name">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerPhone" class="col-form-label">Phone Number</label>
                                                <input type="text" required="required" name="cust_phone" class="form-control" id="inputCustomerPhone" placeholder="Phone Number">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputCustomerAddress" class="col-form-label">Address</label>
                                                <input type="text" required="required" name="cust_address" class="form-control" id="inputCustomerAddress" placeholder="Address">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_customer" class="ladda-button btn btn-primary" data-style="expand-right">Add Customer</button>
                                    </form>
                                    <!-- End Customer Form -->
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