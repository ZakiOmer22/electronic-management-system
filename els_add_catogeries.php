<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['add_category'])) {
    // Capture form inputs
    $cat_name = $_POST['cat_name'];
    $cat_description = $_POST['cat_description'];

    // SQL query to insert captured values into the categories table
    $query = "INSERT INTO categories (name, description) VALUES (?, ?)";

    // Prepare statement
    $stmt = $mysqli->prepare($query);

    // Bind parameters
    $rc = $stmt->bind_param('ss', $cat_name, $cat_description);

    // Execute statement
    $stmt->execute();

    // Check if the category was added successfully
    if ($stmt) {
        $success = "Category Added Successfully"; // Success message
    } else {
        $err = "Please Try Again Or Try Later"; // Error message
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
                                <h4 class="page-title">Add Catogeries Details</h4>
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
                                    <!-- Add Category Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="inputCategoryName" class="col-form-label">Category Name</label>
                                                <input type="text" required="required" name="cat_name" class="form-control" id="inputCategoryName" placeholder="Category Name">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputCategoryDescription" class="col-form-label">Description</label>
                                            <textarea required="required" name="cat_description" class="form-control" id="inputCategoryDescription" placeholder="Category Description"></textarea>
                                        </div>

                                        <button type="submit" name="add_category" class="ladda-button btn btn-primary" data-style="expand-right">Add Category</button>
                                    </form>
                                    <!-- End Category Form -->
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