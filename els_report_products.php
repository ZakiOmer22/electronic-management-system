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
        $product_id = $_GET['id']; // get from URL

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

        // // Then check:
        if (!$product) {
            echo "<br><br><br><h1 style=' text-align:center;'>Product not found!</h1>";
            exit;
        }

        $cnt = 1;
        while ($row = $res->fetch_object()) {
            $mysqlDateTime = $row->pay_date_generated; //trim timestamp to DD/MM/YYYY formart

            //calculate salary total salary after 16% taxation
            $tax = 16 / 100;
            $salary = $row->pay_emp_salary;
            $total_salary = $tax * $salary;
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                            <li class="breadcrumb-item active">Generate Products</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Products</h4>
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
                                            <h4 class="m-0 d-print-none"><?php echo htmlspecialchars($product->name); ?> Report</h4>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product->name); ?></p>
                                                <p><strong>Category:</strong> <?php echo htmlspecialchars($product->category_name); ?></p>
                                                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($product->description)); ?></p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 offset-md-2">
                                            <div class="mt-3 float-right">
                                                <p><strong>Generated Date :</strong>
                                                    <span class="float-right">
                                                        <?php echo date("d-m-Y - h:i:s A", strtotime($product->created_at)); ?>
                                                    </span>
                                                </p>

                                                <p><strong>Status :</strong>
                                                    <span class="float-right">
                                                        <?php
                                                        if ($product->stock_quantity > 0) {
                                                            echo '<span class="badge badge-success">Available</span>';
                                                        } else {
                                                            echo '<span class="badge badge-danger">Out of Stock</span>';
                                                        }
                                                        ?>
                                                    </span>
                                                </p>

                                                <p><strong>Stock Quantity :</strong>
                                                    <span class="float-right"><?php echo intval($product->stock_quantity); ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12 text-center">
                                            <?php if (!empty($product->image)) { ?>
                                                <img src="uploads/products/<?php echo htmlspecialchars($product->image); ?>" alt="<?php echo htmlspecialchars($product->name); ?>" style="max-height:200px;">
                                            <?php } else { ?>
                                                <p class="text-muted">No Image Available</p>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Product Name</th>
                                                            <th>Price ($)</th>
                                                            <th>Stock Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td><?php echo htmlspecialchars($product->name); ?></td>
                                                            <td><?php echo number_format($product->price, 2); ?></td>
                                                            <td><?php echo intval($product->stock_quantity); ?></td>
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
                                                    Automatically generated report for product inventory management.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="float-right">
                                                <h3>Total Price: $ <?php echo number_format($product->price, 2); ?></h3>
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

                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include("assets/inc/footer.php"); ?>
                <!-- end Footer -->

            </div>
        <?php $cnt =  $cnt + 1;
        }
        ?>
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