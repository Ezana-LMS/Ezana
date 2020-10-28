<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_enroll'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_name = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Cannot Be Empty";
    }
    if (isset($_POST['semester_enrolled']) && !empty($_POST['semester_enrolled'])) {
        $semester_enrolled = mysqli_real_escape_string($mysqli, trim($_POST['semester_enrolled']));
    } else {
        $error = 1;
        $err = "Semester Enrolled Number Cannot Be Empty";
    }
    if (isset($_POST['student_adm']) && !empty($_POST['student_adm'])) {
        $student_adm = mysqli_real_escape_string($mysqli, trim($_POST['student_adm']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Start / End Dates Cannot Be Empty";
    }
    if (isset($_POST['course_code']) && !empty($_POST['course_code'])) {
        $course_code = mysqli_real_escape_string($mysqli, trim($_POST['course_code']));
    } else {
        $error = 1;
        $err = "Course Code Cannot Be Empty";
    }
    if (!$error) {
        $update = $_GET['update'];
        $code = $_POST['code'];
        $student_adm = $_POST['student_adm'];
        $student_name = $_POST['student_name'];
        $semester_enrolled = $_POST['semester_enrolled'];
        $created_at = date('d M Y');
        $course_code = $_POST['course_code'];
        $course_name = $_POST['course_name'];
        $semester_start = $_POST['semester_start'];
        $semester_end = $_POST['semester_end'];
        $academic_year_enrolled = $_POST['academic_year_enrolled'];
        $module_name = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $query = "UPDATE ezanaLMS_Enrollments SET code =?, student_adm =?, student_name =?, semester_enrolled =?, updated_at =?, course_code =?, course_name =?, semester_start =?, semester_end =?, academic_year_enrolled =?, module_name =?, module_code =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssssss',  $code, $student_adm, $student_name, $semester_enrolled, $updated_at, $course_code, $course_name, $semester_start, $semester_end, $academic_year_enrolled, $module_name, $module_code, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Student Enrolled" && header("refresh:1; url=manage_student_enrollments.php");
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
        $update = $_GET['update'];
        $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE id ='$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($en = $res->fetch_object()) {
            require_once('partials/_sidebar.php');
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update Student Enrollment</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_student_enrollments.php">Enrollments</a></li>
                                    <li class="breadcrumb-item active">Update Enrollments</li>
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
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row" style="display:none">
                                            <div class="form-group col-md-6">
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Enroll Code</label>
                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Student Admission Number</label>
                                                <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="student_adm">
                                                    <option selected>Select Student Admission Number</option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Students`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($std = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $std->admno; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Student Name</label>
                                                <input type="text" id="StudentName" readonly required name="student_name" class="form-control">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="">Course Code</label>
                                                <select class='form-control basic' id="Coursecode" onchange="getCourseDetails(this.value);" name="course_code">
                                                    <option selected>Select Course Code</option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Courses`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($courses = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $courses->code; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Course Name</label>
                                                <input type="text" id="CourseName" readonly required name="course_name" class="form-control">
                                            </div>
                                            <hr>
                                            <div class="form-group col-md-6">
                                                <label for="">Module Name</label>
                                                <select class='form-control basic' id="ModuleName" onchange="getModuleDetails(this.value);" name="module_name">
                                                    <option selected>Select Module Name</option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Modules`   ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($mod = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $mod->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Module Code</label>
                                                <input type="text" id="ModuleCode" readonly required name="module_code" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Semester Enrolled</label>
                                                <select class='form-control basic' id="SemesterEnrolled" onchange="getSemesterDetails(this.value);" name="semester_enrolled">
                                                    <option selected>Select Semester Name</option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Calendar`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($cal = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $cal->semester_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Semester Start Date</label>
                                                <input type="text" id="SemesterStart" readonly required name="semester_start" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="">Semester End Date</label>
                                                <input type="text" id="SemesterEnd" readonly required name="semester_end" class="form-control">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="">Academic Year Enrolled</label>
                                                <input type="text" id="AcademicYear" readonly required name="academic_year_enrolled" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_enroll" class="btn btn-primary">Submit</button>
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