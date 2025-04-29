<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$doc_id = $_SESSION['doc_id'];

?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

    <!-- Topbar Start -->
    <?php include("assets/inc/nav.php"); ?>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <?php include('assets/inc/sidebar.php'); ?>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $category_id = intval($_GET['id']);
    } else {
        die("Invalid or missing category ID.");
    }

    $query = "SELECT * FROM categories WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $category = $res->fetch_object();

    if (!$category) {
        die("Category not found.");
    }
    ?>

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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Catogeries</a></li>
                                    <li class="breadcrumb-item active">Generate Catogeries</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Catogeries</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <!-- Logo & title -->
                            <div class="clearfix">
                                <div class="float-left">
                                    <img src="assets/images/logo-dark.png" alt="" height="20">
                                </div>
                                <div class="float-right">
                                    <h4 class="m-0 d-print-none"><?php echo htmlspecialchars($category->name); ?> Category Report</h4>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <p><strong>Category Name:</strong> <?php echo htmlspecialchars($category->name); ?></p>
                                        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($category->description)); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-md-2">
                                    <div class="mt-3 float-right">
                                        <p><strong>Generated Date :</strong>
                                            <span class="float-right">
                                                <?php echo date("d-m-Y - h:i:s A", strtotime($category->created_at)); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Field</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Name</td>
                                                    <td><?php echo htmlspecialchars($category->name); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Description</td>
                                                    <td><?php echo nl2br(htmlspecialchars($category->description)); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Created At</td>
                                                    <td><?php echo date("d-m-Y h:i:s A", strtotime($category->created_at)); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive -->
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="clearfix pt-5">
                                        <h6 class="text-muted">Notes:</h6>
                                        <small class="text-muted">
                                            Automatically generated report for category records management.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="float-right">
                                        <h5 class="text-muted">Report ID: <?php echo intval($category->id); ?></h5>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="mt-4 mb-1">
                                <div class="text-right d-print-none">
                                    <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-printer mr-1"></i> Print
                                    </a>
                                </div>
                            </div>
                        </div> <!-- end card-box -->
                    </div> <!-- end col -->
                </div>

            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        <?php include("assets/inc/footer.php"); ?>
        <!-- end Footer -->

    </div> <!-- content-page -->

</div> <!-- wrapper -->

    <!-- END wrapper -->


    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>