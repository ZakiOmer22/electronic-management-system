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

    $product_query = "SELECT * FROM products WHERE id = ?";
    $stmt = $mysqli->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_object();

    if (!$product) {
        die("Product not found.");
    }

    function safe_html($value)
    {
        return htmlspecialchars($value ?? 'N/A');
    }

    function safe_date($value)
    {
        return $value ? date("d-m-Y - h:i:s A", strtotime($value)) : 'N/A';
    }
    ?>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="#">Inventory</a></li>
                                    <li class="breadcrumb-item active">Generate Inventory Report</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Inventory Report</h4>
                        </div>
                    </div>
                </div>

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

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <p><strong>Product Name:</strong> <?php echo safe_html($product->name); ?></p>
                                        <p><strong>Category:</strong> <?php echo safe_html($product->category ?? ''); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-md-2">
                                    <div class="mt-3 float-right">
                                        <p><strong>Created At:</strong>
                                            <span class="float-right"><?php echo safe_date($product->created_at); ?></span>
                                        </p>
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
                                                    <th>Transaction Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $inventory_query = "SELECT * FROM inventory WHERE product_id = ? ORDER BY transaction_date DESC";
                                                $stmt_inv = $mysqli->prepare($inventory_query);
                                                $stmt_inv->bind_param("i", $product_id);
                                                $stmt_inv->execute();
                                                $inventory_result = $stmt_inv->get_result();

                                                $counter = 1;
                                                while ($row = $inventory_result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $counter++ . "</td>";
                                                    echo "<td>" . safe_html(ucfirst($row['transaction_type'])) . "</td>";
                                                    echo "<td>" . intval($row['quantity_change']) . "</td>";
                                                    echo "<td>" . safe_date($row['transaction_date']) . "</td>";
                                                    echo "</tr>";
                                                }

                                                if ($inventory_result->num_rows === 0) {
                                                    echo "<tr><td colspan='4' class='text-center'>No inventory transactions found.</td></tr>";
                                                }

                                                $stmt_inv->close();
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Section -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="clearfix pt-5">
                                        <h6 class="text-muted">Notes:</h6>
                                        <small class="text-muted">
                                            This is an auto-generated inventory report. Ensure accuracy before using for audits or reports.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="float-right">
                                        <h5 class="text-muted">Product ID: <?php echo intval($product->id); ?></h5>
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

                        </div> <!-- card-box -->
                    </div> <!-- col-12 -->
                </div> <!-- row -->

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