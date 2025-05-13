<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
//$aid=$_SESSION['ad_id'];
$doc_id = $_SESSION['doc_id'];

// Check if a delete request has been made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    // SQL query to delete the payment
    $query = "DELETE FROM payments WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        $success = "Payment deleted successfully!";
    } else {
        $err = "Error deleting payment: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch payments to display (make sure this is after the deletion logic)
$payment_query = "
    SELECT p.id, s.id AS sale_id, p.amount_paid, p.payment_method, p.payment_date
    FROM payments p
    JOIN sales s ON p.sale_id = s.id
";
$payment_result = $mysqli->query($payment_query);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this payment?')) {
                    // Create a form and submit it
                    var form = document.createElement('form');
                    form.method = 'POST';

                    // Add the delete ID to the form
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_id';
                    input.value = id;
                    form.appendChild(input);

                    // Append the form to the body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Payements</a></li>
                                        <li class="breadcrumb-item active">Manage Payements</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Payements Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                    <option value="InPatients">InPatients</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th data-toggle="true">Sale ID</th>
                                                <th data-hide="phone">Amount Paid</th>
                                                <th data-hide="phone">Payment Method</th>
                                                <th data-hide="phone">Payment Date</th>
                                                <th data-hide="phone">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        // Get details of all payments along with sale info
                                        $ret = "SELECT payments.id, payments.amount_paid, payments.payment_method, payments.payment_date, sales.id as sale_id 
                FROM payments 
                INNER JOIN sales ON payments.sale_id = sales.id 
                ORDER BY payments.payment_date DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->sale_id; ?></td>
                                                    <td><?php echo number_format($row->amount_paid, 2); ?></td>
                                                    <td><?php echo ucfirst($row->payment_method); ?></td>
                                                    <td><?php echo $row->payment_date; ?></td>
                                                    <td>
                                                        <!-- Action Buttons -->
                                                        <a href="els_view_payment.php?id=<?php echo $row->id; ?>" class="badge badge-success">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                        <a href="els_edit_payment.php?id=<?php echo $row->id; ?>" class="badge badge-primary">
                                                            <i class="mdi mdi-pencil"></i> Edit
                                                        </a>
                                                        <a href="#" class="badge badge-danger"
                                                            onclick="confirmDelete(<?php echo $row->id; ?>); return false;">
                                                            <i class="mdi mdi-trash-can-outline"></i> Delete
                                                        </a>
                                                        <!-- Report Button -->
                                                        <a href="els_report_payments.php?id=<?php echo $row->id; ?>" class="badge badge-warning">
                                                            <i class="mdi mdi-file-pdf-box"></i> Report
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php
                                            $cnt++;
                                        }
                                        ?>
                                        <tfoot>
                                            <tr class="active">
                                                <td colspan="6">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>


                                <!-- end .table-responsive-->
                            </div> <!-- end card-box -->
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

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>