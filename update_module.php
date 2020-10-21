<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        $course_id = mysqli_real_escape_string($mysqli, trim($_POST['course_id']));
    } else {
        $error = 1;
        $err = "Course ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        $update = $_GET['update'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $course_name = $_POST['course_name'];
        $course_id = $_POST['course_id'];
        $course_duration = $_POST['course_duration'];
        $weight_percentage = $_POST['weight_percentage'];
        $lectures_number = $_POST['lectures_number'];
        $updated_at = date('d M Y');
        $query = "UPDATE ezanaLMS_Modules SET  name =?, code =?, details =?, course_name =?, course_id =?, course_duration =?, weight_percentage =?, lectures_number =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssss', $name, $code, $details, $course_name, $course_id, $course_duration, $weight_percentage, $lectures_number, $updated_at, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Module Updated" && header("refresh:1; url=manage_modules.php");
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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update <?php echo $mod->name; ?> </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_modules.php">Modules</a></li>
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
                                                <label for="">Module Name</label>
                                                <input type="text" value="<?php echo $mod->name; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Module Number / Code</label>
                                                <input type="text" required name="code" value="<?php echo $mod->code; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Course Name</label>
                                                <select class='form-control basic' id="CourseName" onchange="getCourseDetails(this.value);" name="course_name">
                                                    <option selected>Select Course Name</option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Courses`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($course = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $course->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Course Code</label>
                                                <input type="text" id="CourseCode" readonly required name="" class="form-control">
                                                <input type="hidden" id="CourseID" required name="course_id" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="">Teaching Duration</label>
                                                <input type="text" value="<?php echo $mod->course_duration; ?>" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Number Of Lectures Per Week</label>
                                                <input type="text" value="<?php echo $mod->lectures_number; ?>" required name="lectures_number" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Module CAT / End Exam Weight Percentage</label>
                                                <input type="text" value="<?php echo $mod->weight_percentage; ?>" required name="weight_percentage" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputPassword1">Module Details</label>
                                                <textarea required id="textarea" name="details" rows="10" class="form-control"><?php echo $mod->details; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_module" class="btn btn-primary">Update Module</button>
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