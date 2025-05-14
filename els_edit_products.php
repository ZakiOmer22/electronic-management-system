<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['edit_product'])) {
    // Retrieve the product ID and form data
    $product_id = $_POST['product_id'];
    $prod_name = $_POST['prod_name'];
    $prod_category = $_POST['prod_category'];
    $prod_price = $_POST['prod_price'];
    $prod_stock = $_POST['prod_stock'];
    $prod_description = $_POST['prod_description'];
    $prod_image = $_FILES['prod_image'];

    // Initialize image name as an empty string
    $image_name = '';

    // Handle image upload (if a new image is uploaded)
    if ($prod_image['error'] == 0) {
        $image_name = time() . '_' . basename($prod_image['name']);
        $image_path = 'uploads/' . $image_name;
        if (move_uploaded_file($prod_image['tmp_name'], $image_path)) {
            // Image uploaded successfully
        } else {
            $error_message = "Error uploading image.";
        }
    } else {
        // If no new image is uploaded, keep the existing image
        $image_name = isset($product['image']) ? $product['image'] : '';
    }

    // Prepare the query to update product in the database
    $update_query = "UPDATE products SET name = ?, category_id = ?, price = ?, stock_quantity = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param("siidssi", $prod_name, $prod_category, $prod_price, $prod_stock, $prod_description, $image_name, $product_id);

    // Execute the query
    if ($stmt->execute()) {
        $success_message = "Product updated successfully.";  // Success message
    } else {
        $error_message = "Error updating product: " . $stmt->error;  // Error message if the query fails
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                        <li class="breadcrumb-item active">Add Products</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Products Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Product</h4>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputProductID" class="col-form-label">Product ID</label>
                                                <input type="text" name="product_id" class="form-control" id="inputProductID" placeholder="Product ID" required>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label for="inputProductName" class="col-form-label">Product Name</label>
                                                <input type="text" name="prod_name" class="form-control" id="inputProductName" placeholder="Product Name" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputCategory" class="col-form-label">Category</label>
                                                <select name="prod_category" class="form-control" id="inputCategory" required>
                                                    <!-- You can dynamically populate the categories here -->
                                                    <option value="1">Category 1</option>
                                                    <option value="2">Category 2</option>
                                                    <option value="3">Category 3</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputProductPrice" class="col-form-label">Price</label>
                                                <input type="number" name="prod_price" class="form-control" id="inputProductPrice" placeholder="Product Price" required step="0.01">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputProductStock" class="col-form-label">Stock Quantity</label>
                                                <input type="number" name="prod_stock" class="form-control" id="inputProductStock" placeholder="Stock Quantity" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputProductImage" class="col-form-label">Product Image</label>
                                                <input type="file" name="prod_image" class="form-control" id="inputProductImage">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputProductDescription" class="col-form-label">Description</label>
                                            <textarea name="prod_description" class="form-control" id="inputProductDescription" placeholder="Product Description" required></textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" name="edit_product" class="ladda-button btn btn-primary" data-style="expand-right">Update Product</button>
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