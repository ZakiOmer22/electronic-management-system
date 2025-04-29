<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_inventory_transaction'])) {
    $product_id = intval($_POST['product_id']);
    $quantity_change = intval($_POST['quantity_change']);
    $transaction_type = $_POST['transaction_type'];

    // Validate transaction type
    $valid_types = ['sale', 'restock', 'adjustment'];
    if (!in_array($transaction_type, $valid_types)) {
        $err = "Invalid Transaction Type";
    } else {
        // Begin transaction (optional but safer)
        $mysqli->begin_transaction();

        try {
            // 1. Insert into inventory log
            $insert_query = "INSERT INTO inventory (product_id, quantity_change, transaction_type) VALUES (?, ?, ?)";
            $stmt1 = $mysqli->prepare($insert_query);
            $stmt1->bind_param('iis', $product_id, $quantity_change, $transaction_type);
            $stmt1->execute();

            // 2. Update product stock
            // Get current stock
            $stock_query = "SELECT stock_quantity  FROM products WHERE id = ?";
            $stmt2 = $mysqli->prepare($stock_query);
            $stmt2->bind_param('i', $product_id);
            $stmt2->execute();
            $stmt2->bind_result($current_stock);
            $stmt2->fetch();
            $stmt2->close();

            // Calculate new stock
            $new_stock = $current_stock + $quantity_change;

            // Optional: Prevent stock from going below 0
            if ($new_stock < 0) {
                throw new Exception("Insufficient stock. Cannot perform transaction.");
            }

            // Update the stock
            $update_query = "UPDATE products SET stock_quantity  = ? WHERE id = ?";
            $stmt3 = $mysqli->prepare($update_query);
            $stmt3->bind_param('ii', $new_stock, $product_id);
            $stmt3->execute();

            // Commit transaction
            $mysqli->commit();
            $success = "Inventory transaction logged and stock updated successfully.";
        } catch (Exception $e) {
            // Rollback on failure
            $mysqli->rollback();
            $err = "Error: " . $e->getMessage();
        }
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
                                <h4 class="page-title">Add Inventory Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Add Inventory Transaction</h4>
                                    <!-- Add Inventory Transaction Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputProductId" class="col-form-label">Product ID</label>
                                                <input type="number" required="required" name="product_id" class="form-control" id="inputProductId" placeholder="Product ID">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputQuantityChange" class="col-form-label">Quantity Change</label>
                                                <input type="number" required="required" name="quantity_change" class="form-control" id="inputQuantityChange" placeholder="Quantity Change">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputTransactionType" class="col-form-label">Transaction Type</label>
                                                <select name="transaction_type" class="form-control" id="inputTransactionType" required>
                                                    <option value="sale">Sale</option>
                                                    <option value="restock">Restock</option>
                                                    <option value="adjustment">Adjustment</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="add_inventory_transaction" class="ladda-button btn btn-primary" data-style="expand-right">Add Transaction</button>
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