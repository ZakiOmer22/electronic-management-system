<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_sale'])) {
    // Fetch and sanitize input
    $provided_customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
    $cust_name      = trim($_POST['cust_name']);
    $cust_phone     = trim($_POST['cust_phone']);
    $cust_address   = trim($_POST['cust_address']);
    $total_amount   = floatval($_POST['total_amount']);

    $customer_id = null;

    // Case 1: Use provided customer_id directly (only if it exists in DB)
    if ($provided_customer_id > 0) {
        $check_query = "SELECT id FROM customers WHERE id = ? LIMIT 1";
        $stmt = $mysqli->prepare($check_query);
        $stmt->bind_param("i", $provided_customer_id);
        $stmt->execute();
        $stmt->bind_result($existing_id);
        if ($stmt->fetch()) {
            $customer_id = $existing_id;
        }
        $stmt->close();
    }

    // Case 2: Lookup customer by name/phone
    if (!$customer_id) {
        $check_query = "SELECT id FROM customers WHERE name = ? AND phone = ? LIMIT 1";
        $stmt = $mysqli->prepare($check_query);
        $stmt->bind_param("ss", $cust_name, $cust_phone);
        $stmt->execute();
        $stmt->bind_result($existing_id);
        if ($stmt->fetch()) {
            $customer_id = $existing_id;
        }
        $stmt->close();
    }

    // Case 3: Insert new customer if not found
    if (!$customer_id) {
        $insert_customer = "INSERT INTO customers (name, phone, address) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($insert_customer);
        $stmt->bind_param("sss", $cust_name, $cust_phone, $cust_address);
        if ($stmt->execute()) {
            $customer_id = $stmt->insert_id;
        } else {
            $err = "❌ Customer insert failed: " . $stmt->error;
            $stmt->close();
            return;
        }
        $stmt->close();
    }

    // Insert into sales
    $insert_sale = "INSERT INTO sales (customer_id, total_amount) VALUES (?, ?)";
    $stmt = $mysqli->prepare($insert_sale);
    $stmt->bind_param("id", $customer_id, $total_amount);  // int, double
    if ($stmt->execute()) {
        $success = "✅ Sale recorded successfully!";
    } else {
        $err = "❌ Sale insert failed: " . $stmt->error;
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