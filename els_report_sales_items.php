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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Sales Items</a></li>
                                            <li class="breadcrumb-item active">Generate Sales Items</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Sales Items</h4>
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
                                            <h4 class="m-0 d-print-none">
                                                Sale Report with Items - ID: <?php echo intval($sale->id); ?>
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($sale->customer_name ?? 'Walk-in / Unknown'); ?></p>
                                                <p><strong>Sale Amount:</strong> $<?php echo number_format($sale->total_amount, 2); ?></p>
                                                <p><strong>Handled By (User ID):</strong> <?php echo intval($sale->user_id); ?></p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 offset-md-2">
                                            <div class="mt-3 float-right">
                                                <p><strong>Sale Date :</strong>
                                                    <span class="float-right">
                                                        <?php echo date("d-m-Y - h:i:s A", strtotime($sale->sale_date)); ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Itemized Sale Items Table -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h5>Sale Items</h5>
                                            <div class="table-responsive">
                                                <table class="table table-centered table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Product Name</th>
                                                            <th>Quantity</th>
                                                            <th>Price (Each)</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $counter = 1;
                                                        $total = 0;

                                                        // Fetch sale items
                                                        foreach ($sale_items as $item) {
                                                            $subtotal = $item->quantity * $item->price;
                                                            $total += $subtotal;
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $counter++; ?></td>
                                                                <td><?php echo htmlspecialchars($item->product_name); ?></td>
                                                                <td><?php echo intval($item->quantity); ?></td>
                                                                <td>$<?php echo number_format($item->price, 2); ?></td>
                                                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="4" class="text-right">Total</th>
                                                            <th>$<?php echo number_format($total, 2); ?></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div> <!-- end table-responsive -->
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="clearfix pt-5">
                                                <h6 class="text-muted">Notes:</h6>
                                                <small class="text-muted">
                                                    This is an automatically generated detailed sale report including individual item records.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="float-right">
                                                <h5 class="text-muted">Sale ID: <?php echo intval($sale->id); ?></h5>
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