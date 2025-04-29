<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['edit_sale_item'])) {
    // Retrieve form data
    $sale_item_id = $_POST['sale_item_id'];
    $sale_id = $_POST['sale_id'];  // The Sale ID (usually associated with a sale)
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Update the sale item in the database
    $update_query = "UPDATE sale_items SET sale_id = ?, product_id = ?, quantity = ?, price = ? WHERE id = ?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param("iiidi", $sale_id, $product_id, $quantity, $price, $sale_item_id);

    if ($stmt->execute()) {
        $success_message = "Sale item updated successfully.";
    } else {
        $error_message = "Error updating sale item: " . $stmt->error;
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales Items</a></li>
                                        <li class="breadcrumb-item active">Add Sales Items</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Sales Items Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Sale Item</h4>
                                    <!-- Edit Sale Item Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputSaleId" class="col-form-label">Sale ID</label>
                                                <input type="number" required="required" name="sale_id" class="form-control" id="inputSaleId" placeholder="Sale ID" value="<?php echo isset($sale_item['sale_id']) ? $sale_item['sale_id'] : ''; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputProductId" class="col-form-label">Product ID</label>
                                                <input type="number" required="required" name="product_id" class="form-control" id="inputProductId" placeholder="Product ID" value="<?php echo isset($sale_item['product_id']) ? $sale_item['product_id'] : ''; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputQuantity" class="col-form-label">Quantity</label>
                                                <input type="number" required="required" name="quantity" class="form-control" id="inputQuantity" placeholder="Quantity" value="<?php echo isset($sale_item['quantity']) ? $sale_item['quantity'] : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPrice" class="col-form-label">Price</label>
                                                <input type="number" required="required" name="price" class="form-control" id="inputPrice" placeholder="Price" value="<?php echo isset($sale_item['price']) ? $sale_item['price'] : ''; ?>" step="0.01">
                                            </div>
                                        </div>

                                        <button type="submit" name="edit_sale_item" class="ladda-button btn btn-primary" data-style="expand-right">Update Sale Item</button>
                                        <input type="hidden" name="sale_item_id" value="<?php echo isset($sale_item['id']) ? $sale_item['id'] : ''; ?>">
                                    </form>
                                    <!-- End Sale Item Form -->
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