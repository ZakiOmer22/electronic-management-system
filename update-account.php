<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['update_profile'])) {
    $doc_fname = $_POST['doc_fname'];
    $doc_lname = $_POST['doc_lname'];
    $doc_id = $_SESSION['doc_id'];
    $doc_dpic = $_FILES["doc_dpic"]["name"];

    // Move the uploaded file to the specified directory
    move_uploaded_file($_FILES["doc_dpic"]["tmp_name"], "assets/images/users/" . $doc_dpic);

    // SQL to update captured values
    $query = "UPDATE users SET firstname=?, lastname=?, profile_photo=? WHERE id=?";
    $stmt = $mysqli->prepare($query);

    // Bind parameters: three strings and one integer
    $rc = $stmt->bind_param('sssi', $doc_fname, $doc_lname, $doc_dpic, $doc_id);

    // Execute the statement
    $stmt->execute();

    // Check if the update was successful
    if ($stmt) {
        $success = "Profile Updated";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}

// Change Password
if (isset($_POST['update_pwd'])) {
    $doc_id = $_SESSION['doc_id'];
    $doc_pwd = sha1(md5($_POST['doc_pwd'])); // Keep double encryption for password

    // SQL to update the password
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('si', $doc_pwd, $doc_id);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt) {
        $success = "Password Updated";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('assets/inc/sidebar.php'); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        session_start(); // Start the session
        include('assets/inc/config.php'); // Include configuration file

        $doc_id = $_SESSION['doc_id'];
        $ret = "SELECT * FROM users WHERE id=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $doc_id);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_object()) {
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
                                            <li class="breadcrumb-item active">Profile</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><?php echo $row->firstname; ?> <?php echo $row->lastname; ?>'s Profile</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="card-box text-center">
                                    <img src="assets/images/users/<?php echo $row->profile_photo; ?>" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                    <div class="text-center mt-3">
                                        <p class="text-muted mb-2 font-13"><strong>User Full Name :</strong> <span class="ml-2"><?php echo $row->firstname; ?> <?php echo $row->lastname; ?></span></p>
                                        <p class="text-muted mb-2 font-13"><strong>User Role :</strong> <span class="ml-2"><?php echo $row->role; ?></span></p>
                                        <p class="text-muted mb-2 font-13"><strong>User ID :</strong> <span class="ml-2"><?php echo $row->id; ?></span></p>
                                    </div>
                                </div> <!-- end card-box -->
                            </div> <!-- end col-->

                            <div class="col-lg-8 col-xl-8">
                                <div class="card-box">
                                    <ul class="nav nav-pills navtab-bg nav-justified">
                                        <li class="nav-item">
                                            <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                                Update Profile
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                Change Password
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="aboutme">
                                            <form method="post" enctype="multipart/form-data">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstname">First Name</label>
                                                            <input type="text" name="doc_fname" class="form-control" id="firstname" value="<?php echo $row->firstname; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastname">Last Name</label>
                                                            <input type="text" name="doc_lname" class="form-control" id="lastname" value="<?php echo $row->lastname; ?>" required>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="profilepic">Profile Picture</label>
                                                            <input type="file" name="doc_dpic" class="form-control" id="profilepic">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="text-right">
                                                    <button type="submit" name="update_profile" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                                </div>
                                            </form>
                                        </div> <!-- end tab-pane -->

                                        <div class="tab-pane" id="settings">
                                            <form method="post">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Change Password</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="oldpassword">Old Password</label>
                                                            <input type="password" class="form-control" name="old_pwd" id="oldpassword" placeholder="Enter Old Password" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="newpassword">New Password</label>
                                                            <input type="password" class="form-control" name="doc_pwd" id="newpassword" placeholder="Enter New Password" required>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->

                                                <div class="form-group">
                                                    <label for="confirmpassword">Confirm Password</label>
                                                    <input type="password" class="form-control" name="confirm_pwd" id="confirmpassword" placeholder="Confirm New Password" required>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" name="update_pwd" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Update Password</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- end settings content-->
                                    </div> <!-- end tab-content -->
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include("assets/inc/footer.php"); ?>
                <!-- end Footer -->

            </div>
        <?php } ?>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>


</html>