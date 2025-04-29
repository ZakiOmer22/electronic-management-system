<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['edit_discount'])) {
    // Retrieve data from the form
    $discount_id = $_POST['discount_id']; // Get the discount ID from the hidden input
    $code = trim($_POST['code']); // Discount code (read-only, not editable)
    $discount_type = $_POST['discount_type']; // Discount type
    $discount_value = floatval($_POST['discount_value']); // Discount value
    $start_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $_POST['start_date']))); // Start date
    $end_date = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $_POST['end_date']))); // End date
    $description = trim($_POST['description']); // Description

    // Validations
    $valid_types = ['percentage', 'fixed']; // Valid types for the discount
    $errors = [];

    // Check if the discount type is valid
    if (!in_array($discount_type, $valid_types)) {
        $errors[] = "Invalid discount type.";
    }

    // Check if the discount value is valid
    if ($discount_type === 'percentage' && ($discount_value < 0 || $discount_value > 100)) {
        $errors[] = "Percentage discount must be between 0 and 100.";
    }

    if ($discount_value < 0) {
        $errors[] = "Discount value must be positive.";
    }

    if (strtotime($start_date) >= strtotime($end_date)) {
        $errors[] = "Start date must be before end date.";
    }

    // Proceed if no errors
    if (empty($errors)) {
        // Prepare the update query
        $query = "UPDATE discounts SET 
                    code = ?, 
                    discount_type = ?, 
                    discount_value = ?, 
                    start_date = ?, 
                    end_date = ?, 
                    description = ? 
                  WHERE id = ?";

        // Prepare the SQL statement
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssddsss", $code, $discount_type, $discount_value, $start_date, $end_date, $description, $discount_id);

            // Execute the query
            if ($stmt->execute()) {
                // Success
                $success = "Discount updated successfully.";
            } else {
                // Database error
                $err = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // If statement preparation fails
            $err = "Database error: Unable to prepare the update statement.";
        }
    } else {
        // Collect all errors
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
                                <h4 class="page-title">Edit Discounts Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Discount</h4>
                                    <!-- Edit Discount Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputCode" class="col-form-label">Discount Code</label>
                                                <input type="text" required name="code" class="form-control" id="inputCode" placeholder="Discount Code" value="<?php echo isset($discount['code']) ? $discount['code'] : ''; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputDiscountType" class="col-form-label">Discount Type</label>
                                                <select name="discount_type" class="form-control" id="inputDiscountType" required>
                                                    <option value="percentage" <?php echo isset($discount['discount_type']) && $discount['discount_type'] == 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                                                    <option value="fixed" <?php echo isset($discount['discount_type']) && $discount['discount_type'] == 'fixed' ? 'selected' : ''; ?>>Fixed</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputDiscountValue" class="col-form-label">Discount Value</label>
                                                <input type="number" required name="discount_value" class="form-control" id="inputDiscountValue" placeholder="Discount Value" step="0.01" value="<?php echo isset($discount['discount_value']) ? $discount['discount_value'] : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputStartDate" class="col-form-label">Start Date</label>
                                                <input type="datetime-local" required name="start_date" class="form-control" id="inputStartDate" value="<?php echo isset($discount['start_date']) ? date('Y-m-d\TH:i', strtotime($discount['start_date'])) : ''; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputEndDate" class="col-form-label">End Date</label>
                                                <input type="datetime-local" required name="end_date" class="form-control" id="inputEndDate" value="<?php echo isset($discount['end_date']) ? date('Y-m-d\TH:i', strtotime($discount['end_date'])) : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputDescription" class="col-form-label">Description</label>
                                                <textarea name="description" class="form-control" id="inputDescription" placeholder="Description" rows="3"><?php echo isset($discount['description']) ? $discount['description'] : ''; ?></textarea>
                                            </div>
                                        </div>

                                        <input type="hidden" name="discount_id" value="<?php echo isset($discount['id']) ? $discount['id'] : ''; ?>">

                                        <button type="submit" name="edit_discount" class="ladda-button btn btn-primary" data-style="expand-right">Update Discount</button>
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