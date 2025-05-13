<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
//$aid=$_SESSION['ad_id'];
$doc_id = $_SESSION['doc_id'];
/*
  Doctor has no previledges to delete a patient record
  if(isset($_GET['delete']))
  {
        $id=intval($_GET['delete']);
        $adn="delete from his_patients where pat_id=?";
        $stmt= $mysqli->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();	 
  
          if($stmt)
          {
            $success = "Patients Records Deleted";
          }
            else
            {
                $err = "Try Again Later";
            }
    }
    */
// Check if a delete request has been made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);

    // SQL query to delete the sale item
    $query = "DELETE FROM sale_items WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        $success = "Sale item deleted successfully!";
    } else {
        $err = "Error deleting sale item: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch sale items to display (make sure this is after the deletion logic)
$sale_item_query = "
    SELECT si.id, s.id AS sale_id, p.name AS product_name, si.quantity, si.price
    FROM sale_items si
    JOIN sales s ON si.sale_id = s.id
    JOIN products p ON si.product_id = p.id
";
$sale_item_result = $mysqli->query($sale_item_query);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <script>
            function confirmDelete(id) {
                if (confirm('Are you sure you want to delete this sale item?')) {
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales Items</a></li>
                                        <li class="breadcrumb-item active">Manage Sales Items</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Sales Items Details</h4>
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
                                                <th data-hide="phone">Product Name</th>
                                                <th data-hide="phone">Quantity</th>
                                                <th data-hide="phone">Price</th>
                                                <th data-hide="phone">Total</th>
                                                <th data-hide="phone">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        // Get details of all sales items along with product info
                                        $ret = "SELECT sales_items.id, sales_items.sale_id, sales_items.quantity, sales_items.price, products.name as product_name 
                FROM sales_items 
                INNER JOIN products ON sales_items.product_id = products.id 
                ORDER BY sales_items.id DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                            $total = $row->quantity * $row->price; // Calculate the total for each item
                                        ?>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->sale_id; ?></td>
                                                    <td><?php echo $row->product_name; ?></td>
                                                    <td><?php echo $row->quantity; ?></td>
                                                    <td><?php echo number_format($row->price, 2); ?></td>
                                                    <td><?php echo number_format($total, 2); ?></td>
                                                    <td>
                                                        <!-- Action Buttons -->
                                                        <a href="els_view_sale_items.php?id=<?php echo $row->id; ?>" class="badge badge-success">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                        <a href="els_edit_sale_items.php?id=<?php echo $row->id; ?>" class="badge badge-primary">
                                                            <i class="mdi mdi-pencil"></i> Edit
                                                        </a>
                                                        <a href="#" class="badge badge-danger"
                                                            onclick="confirmDelete(<?php echo $row->id; ?>); return false;">
                                                            <i class="mdi mdi-trash-can-outline"></i> Delete
                                                        </a>
                                                        <!-- Report Button -->
                                                        <a href="els_report_sales_items.php?id=<?php echo $row->id; ?>" class="badge badge-warning">
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
                                                <td colspan="7">
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