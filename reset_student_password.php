
<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();

//Change Password
if (isset($_POST['change_password'])) {

    $error = 0;
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    if (!$error) {
        if ($new_password != $confirm_password) {
            $err = "Password Does Not Match";
        } else {
            $email = $_GET['email'];
            $new_password  = sha1(md5($_POST['new_password']));
            $status = 'Updated';
            $id = $_GET['id'];

            $query = "UPDATE ezanaLMS_Students SET  password =? WHERE email =?";
            $statusQry = "UPDATE ezanaLMS_PasswordResets SET status = ?  WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $updateStmt = $mysqli->prepare(($statusQry));
            $rc = $stmt->bind_param('ss', $new_password, $email);
            $rc = $updateStmt->bind_param('ss', $status, $id);
            $stmt->execute();
            $updateStmt->execute();
            if ($stmt && $updateStmt) {
                $success = "Password Changed" && header("refresh:1; url=student_password_resets.php");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    }
}

require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        require_once('partials/_sidebar.php');
        $id = $_GET['id'];
        $ret = "SELECT * FROM `ezanaLMS_PasswordResets` WHERE id ='$id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($lec = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Reset <?php echo $lec->email; ?> Password</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="student_password_resets.php">Students</a></li>
                                    <li class="breadcrumb-item"><a href="student_password_resets.php">Password Resets</a></li>
                                    <li class="breadcrumb-item active">Reset Password</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Update <?php echo $lec->email; ?> Authentication Credentials</h3>
                                </div>
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="">New Password</label>
                                                <input type="text" value="<?php echo $lec->new_pass; ?>" required name="new_password" class="form-control">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="">Confirm Password</label>
                                                <input type="text" value="<?php echo $lec->new_pass; ?>" required name="confirm_password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php');
        }
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>