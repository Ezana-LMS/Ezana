<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_course'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Couse  Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Courses WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Course With This Code Already Exists";
            } else {
                $err = "Course Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $department_id = $_POST['department_id'];
            $department_name = $_POST['department_name'];

            $query = "INSERT INTO ezanaLMS_Courses (id, code, name, details, department_id, department_name) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $code, $name, $details, $department_id, $department_name);
            $stmt->execute();
            if ($stmt) {
                $success = "Course Added" && header("refresh:1; url=add_course.php");
            } else {
                $info = "Please Try Again Or Try Later";
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
        <?php require_once('partials/_sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add New Course</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="add_course.php">Courses</a></li>
                                <li class="breadcrumb-item active">Add</li>
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
                                            <label for="">Course Name</label>
                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Course Number / Code</label>
                                            <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="">Department Name</label>
                                            <select class='form-control basic' id="DepartmentName" onchange="getDepartmentDetails(this.value);" name="department_name">
                                                <option selected>Select Department Name</option>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Departments`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($dep = $res->fetch_object()) {
                                                ?>
                                                    <option><?php echo $dep->name; ?></option>
                                                <?php } ?>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Department ID</label>
                                            <input type="text" id="DepartmentID" readonly required name="department_id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputPassword1">Faculty Description</label>
                                            <textarea required name="details" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_faculty" class="btn btn-primary">Add Faculty</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>