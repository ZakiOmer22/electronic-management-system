<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_product'])) {
    // Fetch and sanitize input
    $prod_name        = trim($_POST['prod_name']);
    $prod_category    = intval($_POST['prod_category']);
    $prod_price       = floatval($_POST['prod_price']);
    $prod_stock       = intval($_POST['prod_stock']);
    $prod_description = trim($_POST['prod_description']);

    // Image upload settings
    $upload_dir = 'assets/uploads/';
    $prod_image = null;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (isset($_FILES['prod_image']) && $_FILES['prod_image']['error'] === 0) {
        $image_tmp  = $_FILES['prod_image']['tmp_name'];
        $image_name = basename($_FILES['prod_image']['name']);
        $ext        = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed_ext)) {
            $new_filename = uniqid('prod_', true) . '.' . $ext;
            $target_path = $upload_dir . $new_filename;

            if (move_uploaded_file($image_tmp, $target_path)) {
                $prod_image = $new_filename;
            } else {
                $err = "❌ Image upload failed.";
            }
        } else {
            $err = "❌ Invalid image format.";
        }
    }

    // Only insert if there's no error
    if (!isset($err) || $err === "") {
        $insert_product = "INSERT INTO products (category_id, name, description, price, stock_quantity, image)
                           VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insert_product);
        $stmt->bind_param("issdis", $prod_category, $prod_name, $prod_description, $prod_price, $prod_stock, $prod_image);

        if ($stmt->execute()) {
            $success = "✅ Product added successfully!";
        } else {
            $err = "❌ Product insert failed: " . $stmt->error;
        }

        $stmt->close();
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                        <li class="breadcrumb-item active">Add Products</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Products Details</h4>
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
                                    <!-- Add Product Form -->
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputProductName" class="col-form-label">Product Name</label>
                                                <input type="text" required="required" name="prod_name" class="form-control" id="inputProductName" placeholder="Product Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputProductCategory" class="col-form-label">Category</label>
                                                <select id="inputProductCategory" required="required" name="prod_category" class="form-control">
                                                    <option>Choose Category</option>
                                                    <?php
                                                    // Get categories for the product selection
                                                    $category_query = "SELECT id, name FROM categories ORDER BY name";
                                                    $category_result = $mysqli->query($category_query);
                                                    while ($category = $category_result->fetch_assoc()) {
                                                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputProductPrice" class="col-form-label">Price</label>
                                                <input type="number" required="required" name="prod_price" class="form-control" id="inputProductPrice" placeholder="Product Price" step="0.01">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputProductStock" class="col-form-label">Stock Quantity</label>
                                                <input type="number" required="required" name="prod_stock" class="form-control" id="inputProductStock" placeholder="Product Stock Quantity">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputProductDescription" class="col-form-label">Description</label>
                                            <textarea required="required" name="prod_description" class="form-control" id="inputProductDescription" placeholder="Product Description"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputProductImage" class="col-form-label">Product Image</label>
                                            <input type="file" name="prod_image" class="form-control" id="inputProductImage">
                                        </div>

                                        <button type="submit" name="add_product" class="ladda-button btn btn-primary" data-style="expand-right">Add Product</button>
                                    </form>
                                    <!-- End Product Form -->

                                    <?php if (isset($success)) {
                                        echo '<div class="alert alert-success">' . $success . '</div>';
                                    } ?>
                                    <?php if (isset($err)) {
                                        echo '<div class="alert alert-danger">' . $err . '</div>';
                                    } ?>
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