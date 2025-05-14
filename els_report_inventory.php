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

        <?php include("assets/inc/nav.php"); ?>
        <?php include('assets/inc/sidebar.php'); ?>

        <?php
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $product_id = intval($_GET['id']);
        } else {
            die("Invalid or missing product ID.");
        }

        // Fetch product info
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_object();

        if (!$product) {
            die("Product not found.");
        }

        // Utility functions
        function safe_html($value)
        {
            return htmlspecialchars($value ?? 'N/A');
        }

        function safe_date($value)
        {
            return $value ? date("d-m-Y - h:i:s A", strtotime($value)) : 'N/A';
        }

        // Fetch inventory transactions
        $query = "SELECT * FROM inventory WHERE product_id = ? ORDER BY transaction_date DESC";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $inventory_res = $stmt->get_result();
        ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="#">Inventory</a></li>
                                        <li class="breadcrumb-item active">Inventory Report</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Inventory Report</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">

                                <div class="clearfix">
                                    <div class="float-left">
                                        <img src="assets/images/logo-dark.png" alt="" height="20">
                                    </div>
                                    <div class="float-right">
                                        <h4 class="m-0 d-print-none"><?php echo safe_html($product->name); ?> Inventory Report</h4>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <p><strong>Product Name:</strong> <?php echo safe_html($product->name); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 offset-md-2">
                                        <div class="mt-3 float-right">
                                            <p><strong>Report Date:</strong><span class="float-right"><?php echo date("d-m-Y h:i:s A"); ?></span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory History Table -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Transaction Type</th>
                                                        <th>Quantity Change</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($inventory_res->num_rows > 0): ?>
                                                        <?php $i = 1;
                                                        while ($inv = $inventory_res->fetch_object()): ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo safe_html(ucfirst($inv->transaction_type)); ?></td>
                                                                <td><?php echo safe_html($inv->quantity_change); ?></td>
                                                                <td><?php echo safe_date($inv->transaction_date); ?></td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No inventory adjustment found for this product.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Note -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="clearfix pt-5">
                                            <h6 class="text-muted">Notes:</h6>
                                            <small class="text-muted">
                                                This report shows the inventory movement history for the selected product. Ensure data accuracy before printing.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <h5 class="text-muted">Product ID: <?php echo intval($product->id); ?></h5>
                                    </div>
                                </div>

                                <!-- Print Button -->
                                <div class="mt-4 mb-1">
                                    <div class="text-right d-print-none">
                                        <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light">
                                            <i class="mdi mdi-printer mr-1"></i> Print
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div> <!-- container -->
            </div> <!-- content -->
            <?php include("assets/inc/footer.php"); ?>
        </div> <!-- content-page -->

    </div> <!-- wrapper -->



    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>