<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_discount'])) {
    $code = trim($_POST['code']);
    $description = trim($_POST['description']);
    $discount_type = $_POST['discount_type'];
    $discount_value = floatval($_POST['discount_value']);
    $start_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $_POST['start_date'])));
    $end_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $_POST['end_date'])));

    $valid_types = ['percentage', 'fixed'];
    $errors = [];

    if (!in_array($discount_type, $valid_types)) {
        $errors[] = "Invalid discount type.";
    }

    if ($discount_type === 'percentage' && ($discount_value < 0 || $discount_value > 100)) {
        $errors[] = "Percentage discount must be between 0 and 100.";
    }

    if ($discount_value < 0) {
        $errors[] = "Discount value must be positive.";
    }

    if (strtotime($start_date) >= strtotime($end_date)) {
        $errors[] = "Start date must be before end date.";
    }

    $check = $mysqli->prepare("SELECT id FROM discounts WHERE code = ?");
    $check->bind_param("s", $code);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $errors[] = "Discount code already exists.";
    }
    $check->close();

    if (empty($errors)) {
        $query = "INSERT INTO discounts (code, description, discount_type, discount_value, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssdds", $code, $description, $discount_type, $discount_value, $start_date, $end_date);

        if ($stmt->execute()) {
            $success = "Discount added successfully.";
        } else {
            $err = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Discounts</a></li>
                                        <li class="breadcrumb-item active">Add Discounts</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Discounts Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Add Discount</h4>
                                    <!-- Add Discount Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputCode" class="col-form-label">Discount Code</label>
                                                <input type="text" required="required" name="code" class="form-control" id="inputCode" placeholder="Discount Code">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputDiscountType" class="col-form-label">Discount Type</label>
                                                <select name="discount_type" class="form-control" id="inputDiscountType" required>
                                                    <option value="percentage">Percentage</option>
                                                    <option value="fixed">Fixed</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputDiscountValue" class="col-form-label">Discount Value</label>
                                                <input type="number" required="required" name="discount_value" class="form-control" id="inputDiscountValue" placeholder="Discount Value" step="0.01">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputStartDate" class="col-form-label">Start Date</label>
                                                <input type="datetime-local" required="required" name="start_date" class="form-control" id="inputStartDate">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputEndDate" class="col-form-label">End Date</label>
                                                <input type="datetime-local" required="required" name="end_date" class="form-control" id="inputEndDate">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputDescription" class="col-form-label">Description</label>
                                                <textarea name="description" class="form-control" id="inputDescription" placeholder="Description" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <button type="submit" name="add_discount" class="ladda-button btn btn-primary" data-style="expand-right">Add Discount</button>
                                    </form>
                                    <!-- End Discount Form -->
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