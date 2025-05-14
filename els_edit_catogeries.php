<?php
session_start();
include('assets/inc/config.php');

// Handle form submission for editing the category
if (isset($_POST['edit_category'])) {
    // Retrieve the category ID from the POST data
    $category_id = intval($_POST['category_id']);
    $cat_name = trim($_POST['cat_name']);
    $cat_description = trim($_POST['cat_description']);

    // Initialize an array to hold any errors
    $errors = [];

    // Check if category name is provided
    if (empty($cat_name)) {
        $errors[] = "Category name is required.";
    }

    // Check if category description is provided
    if (empty($cat_description)) {
        $errors[] = "Category description is required.";
    }

    // Check if the category name already exists (excluding the current category ID)
    $check = $mysqli->prepare("SELECT id FROM categories WHERE name = ? AND id != ?");
    $check->bind_param("si", $cat_name, $category_id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $errors[] = "Category name already exists.";
    }
    $check->close();

    // If there are no errors, proceed with updating the category in the database
    if (empty($errors)) {
        $update = $mysqli->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $update->bind_param("ssi", $cat_name, $cat_description, $category_id);
        if ($update->execute()) {
            $success = "Category updated successfully.";  // Success message
        } else {
            $err = "Database error: " . $update->error;  // Error message if the query fails
        }
        $update->close();
    } else {
        // If there are errors, concatenate them into a string and display them
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Catogeries</a></li>
                                        <li class="breadcrumb-item active">Add Catogeries</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Edit Catogeries Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Edit Category</h4>
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputCategoryID" class="col-form-label">Category ID</label>
                                                <input type="text" name="category_id" class="form-control" id="inputCategoryID" placeholder="Category ID" required>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label for="inputCategoryName" class="col-form-label">Category Name</label>
                                                <input type="text" required name="cat_name" class="form-control" id="inputCategoryName" placeholder="Category Name">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputCategoryDescription" class="col-form-label">Description</label>
                                            <textarea required name="cat_description" class="form-control" id="inputCategoryDescription" placeholder="Category Description"></textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" name="edit_category" class="ladda-button btn btn-primary" data-style="expand-right">Update Category</button>
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