<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_dept'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Department Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Department Name Cannot Be Empty";
    }
    if (!$error) {
        $update = $_GET['update'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $faculty_name = $_POST['faculty_name'];
        $details = $_POST['details'];
        $hod = $_POST['hod'];

        $query = "UPDATE ezanaLMS_Departments SET code =?, name =?, faculty_name =?, details =?, hod =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $code, $name, $faculty_name, $details, $hod, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Faculty Department Updated" && header("refresh:1; url=manage_departments.php");
        } else {
            //inject alert that profile update task failed
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
        $ret = "SELECT * FROM `ezanaLMS_Departments`   WHERE id ='$update' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($dep = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update <?php echo $dep->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_departments.php">Departments</a></li>
                                    <li class="breadcrumb-item active">Update</li>
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
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Department Name</label>
                                                <input type="text" required name="name" value="<?php echo $dep->name; ?>" class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Department Number / Code</label>
                                                <input type="text" required name="code" value="<?php echo $dep->code; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Department HOD</label>
                                                <input type="text" required value="<?php echo $dep->hod; ?>" name="hod" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Faculty Name</label>
                                                <select type="text" required name="faculty_name" class="form-control">
                                                    <option><?php echo $dep->faculty_name; ?></option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($faculty = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $faculty->name; ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputPassword1">Department Description</label>
                                                <textarea name="details" id="textarea" rows="10" class="form-control"><?php echo $dep->details; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_dept" class="btn btn-primary">Update Department</button>
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