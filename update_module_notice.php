<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_notice'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['announcements']) && !empty($_POST['announcements'])) {
        $announcements = mysqli_real_escape_string($mysqli, trim($_POST['announcements']));
    } else {
        $error = 1;
        $err = "Noctices Cannot Be Empty";
    }
    if (!$error) {
        $update = $_GET['update'];
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $updated_at = date('d M Y');
        $query = "UPDATE ezanaLMS_ModulesAnnouncements SET  module_name =?, module_code =?, announcements =?, created_by =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $module_name, $module_code, $announcements, $created_by, $updated_at, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Posted" && header("refresh:1; url=module_notices.php");
        } else {
            $info = "Please Try Again Or Try Later";
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
        $update = $_GET['update'];
        $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements` WHERE id = '$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($not = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Add New Module Notices / Announcements</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="module_notices.php">Modules</a></li>
                                    <li class="breadcrumb-item active">Update Notice</li>
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
                                    <h3 class="card-title">Fill All Required Fields</h3>
                                </div>
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="">Module Name</label>
                                                <select class='form-control basic' id="ModuleName" onchange="getModuleDetails(this.value);" name="module_name">
                                                    <option selected>Select Module Name </option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE ass_status != '0'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($mod = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $mod->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Module Code</label>
                                                <input type="text" id="ModuleCode" readonly required name="module_code" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Notice Posted By</label>
                                                <?php
                                                $id = $_SESSION['id'];
                                                $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id = '$id'  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($user = $res->fetch_object()) {
                                                ?>
                                                    <input type="text" required name="created_by" value="<?php echo $user->name; ?>" class="form-control" id="exampleInputEmail1">
                                                <?php
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputPassword1">Module Notices</label>
                                                <textarea required id="textarea" name="announcements" rows="20" class="form-control"><?php echo $not->announcements; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_notice" class="btn btn-primary">Update Notice</button>
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
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>