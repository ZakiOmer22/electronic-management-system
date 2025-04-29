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
        $product_id = intval($_GET['id']); // get from URL and sanitize

        // Fetch product info
        $query = "
SELECT products.*, categories.name AS category_name 
FROM products 
JOIN categories ON products.category_id = categories.id 
WHERE products.id = ?
";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_object();

        // Fetch a single inventory adjustment for this product
        $invQuery = "
    SELECT * FROM inventory 
    WHERE product_id = ? AND transaction_type = 'adjustment' 
    ORDER BY transaction_date DESC 
    LIMIT 1
";
        $invStmt = $mysqli->prepare($invQuery);
        $invStmt->bind_param('i', $product_id);
        $invStmt->execute();
        $invRes = $invStmt->get_result();
        $inventory = $invRes->fetch_object();

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
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">Inventory</a></li>
                                        <li class="breadcrumb-item active">Inventory Adjustment</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Inventory Adjustment</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php if ($inventory): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <!-- Logo & title -->
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <img src="assets/images/logo-dark.png" alt="" height="20">
                                        </div>
                                        <div class="float-right">
                                            <h4 class="m-0 d-print-none">Inventory Adjustment - ID: <?= $inventory->id ?></h4>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p><strong>Product Name:</strong> <?= htmlspecialchars($product->name) ?></p>
                                            <p><strong>Transaction Type:</strong> <?= htmlspecialchars($inventory->transaction_type) ?></p>
                                            <p><strong>Quantity Changed:</strong> <?= intval($inventory->quantity_change) ?></p>
                                        </div>

                                        <div class="col-md-4 offset-md-2">
                                            <p><strong>Transaction Date:</strong>
                                                <span class="float-right">
                                                    <?= date("d-m-Y - h:i:s A", strtotime($inventory->transaction_date)) ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Itemized Table -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h5>Inventory Change</h5>
                                            <div class="table-responsive">
                                                <table class="table table-centered table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Product Name</th>
                                                            <th>Quantity Changed</th>
                                                            <th>Transaction Type</th>
                                                            <th>Transaction Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td><?= htmlspecialchars($product->name) ?></td>
                                                            <td><?= intval($inventory->quantity_change) ?></td>
                                                            <td><?= htmlspecialchars($inventory->transaction_type) ?></td>
                                                            <td><?= date("d-m-Y - h:i:s A", strtotime($inventory->transaction_date)) ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes and ID -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h6 class="text-muted pt-4">Notes:</h6>
                                            <small class="text-muted">
                                                This is an automatically generated inventory adjustment report.
                                            </small>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="float-right">
                                                <h5 class="text-muted">Inventory ID: <?= intval($inventory->id) ?></h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Print Button -->
                                    <div class="mt-4 mb-1">
                                        <div class="text-right d-print-none">
                                            <a href="javascript:window.print()" class="btn btn-primary">
                                                <i class="mdi mdi-printer mr-1"></i> Print
                                            </a>
                                        </div>
                                    </div>
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    <?php else: ?>
                        <div class="alert alert-warning">No inventory adjustment found for this product.</div>
                    <?php endif; ?>

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include("assets/inc/footer.php"); ?>
            <!-- end Footer -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
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