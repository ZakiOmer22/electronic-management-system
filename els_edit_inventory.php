<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['edit_inventory_transaction'])) {
    // Retrieve data from the form
    $inventory_transaction_id = $_POST['inventory_transaction_id']; // Get the inventory transaction ID
    $product_id = intval($_POST['product_id']); // Product ID (read-only, not editable)
    $quantity_change = intval($_POST['quantity_change']); // Quantity Change
    $transaction_type = $_POST['transaction_type']; // Transaction Type (sale, restock, or adjustment)

    // Validations
    $valid_transaction_types = ['sale', 'restock', 'adjustment']; // Valid types for transaction
    $errors = [];

    // Check if the transaction type is valid
    if (!in_array($transaction_type, $valid_transaction_types)) {
        $errors[] = "Invalid transaction type.";
    }

    // Ensure quantity change is a valid number
    if ($quantity_change <= 0) {
        $errors[] = "Quantity change must be a positive number.";
    }

    // Proceed if no errors
    if (empty($errors)) {
        // Prepare the update query
        $query = "UPDATE inventory_transactions SET 
                    product_id = ?, 
                    quantity_change = ?, 
                    transaction_type = ? 
                  WHERE id = ?";

        // Prepare the SQL statement
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param("iiis", $product_id, $quantity_change, $transaction_type, $inventory_transaction_id);

            // Execute the query
            if ($stmt->execute()) {
                // Success
                $success = "Inventory transaction updated successfully.";
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                                        <li class="breadcrumb-item active">Add Inventory</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Inventory Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Inventory Transaction</h4>

                                    <!-- Display Success/Error Messages -->
                                    <?php
                                    if (isset($success)) {
                                        echo "<div class='alert alert-success'>$success</div>";
                                    } elseif (isset($err)) {
                                        echo "<div class='alert alert-danger'>$err</div>";
                                    }
                                    ?>

                                    <!-- Edit Inventory Transaction Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputProductId" class="col-form-label">Product ID</label>
                                                <input type="number" required="required" name="product_id" class="form-control" id="inputProductId" placeholder="Product ID" value="<?php echo isset($inventory_transaction['product_id']) ? $inventory_transaction['product_id'] : ''; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputQuantityChange" class="col-form-label">Quantity Change</label>
                                                <input type="number" required="required" name="quantity_change" class="form-control" id="inputQuantityChange" placeholder="Quantity Change" value="<?php echo isset($inventory_transaction['quantity_change']) ? $inventory_transaction['quantity_change'] : ''; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputTransactionType" class="col-form-label">Transaction Type</label>
                                                <select name="transaction_type" class="form-control" id="inputTransactionType" required>
                                                    <option value="sale" <?php echo isset($inventory_transaction['transaction_type']) && $inventory_transaction['transaction_type'] == 'sale' ? 'selected' : ''; ?>>Sale</option>
                                                    <option value="restock" <?php echo isset($inventory_transaction['transaction_type']) && $inventory_transaction['transaction_type'] == 'restock' ? 'selected' : ''; ?>>Restock</option>
                                                    <option value="adjustment" <?php echo isset($inventory_transaction['transaction_type']) && $inventory_transaction['transaction_type'] == 'adjustment' ? 'selected' : ''; ?>>Adjustment</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="edit_inventory_transaction" class="ladda-button btn btn-primary" data-style="expand-right">Update Transaction</button>
                                        <input type="hidden" name="inventory_transaction_id" value="<?php echo isset($inventory_transaction['id']) ? $inventory_transaction['id'] : ''; ?>">
                                    </form>
                                    <!-- End Inventory Transaction Form -->
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