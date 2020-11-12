<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_enroll'])) {
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Enrollments WHERE  student_adm ='$student_adm ' AND semester_enrolled ='$semester_enrolled' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($student_adm && $semester_enrolled) == ($row['student_adm'] && $row['semester_enrolled'])) {
                $err =  "Student $student_name Already Enrolled On $semester_enrolled ";
            }
        } else {
            $faculty = $_GET['faculty'];
            $id = $_POST['id'];
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
            $query = "INSERT INTO ezanaLMS_Enrollments (id, faculty_id, code, student_adm, student_name, semester_enrolled, created_at, course_code, course_name, semester_start, semester_end, academic_year_enrolled, module_name, module_code) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssss', $id, $faculty, $code, $student_adm, $student_name, $semester_enrolled, $created_at, $course_code, $course_name, $semester_start, $semester_end, $academic_year_enrolled, $module_name, $module_code);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Enrolled" && header("refresh:1; url=add_student_enrollment.php?faculty=$faculty");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <?php
        require_once('partials/_faculty_nav.php');

        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($f = $res->fetch_object()) {
        ?>
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"> Enroll Student </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="students.php?faculty=<?php echo $f->id; ?>">Students</a></li>
                                    <li class="breadcrumb-item"><a href="enrolled_students.php?faculty=<?php echo $f->id; ?>">Enrolled Students</a></li>
                                    <li class="breadcrumb-item active"> Enroll </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="container">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Fill All Required Fields</h3>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" role="form">
                                            <div class="card-body">
                                                <div class="row" style="display:none">
                                                    <div class="form-group col-md-6">
                                                        <label for=""> Name</label>
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
                                                            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$f->id'  ";
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
                                                            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$f->id'  ";
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
                                                            <option selected>Select Module Name </option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE faculty_id = '$f->id'   ";
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
                                                        <input type="text" id="ModuleCode"  required name="module_code" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Semester Enrolled</label>
                                                        <select class='form-control basic' name="semester_enrolled">
                                                            <option selected>Select Semester Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($cal = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $cal->semester_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Academic Year Enrolled</label>
                                                        <select class='form-control basic' name="academic_year_enrolled">
                                                            <option selected>Academic Year Enrolled</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($cal = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $cal->academic_yr; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Semester Start Date</label>
                                                        <select class='form-control basic' name="semester_start">
                                                            <option selected>Semester Start Date</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($cal = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $cal->semester_start; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="">Semester End Date</label>
                                                        <select class='form-control basic' name="semester_end">
                                                            <option selected>Semester End Date</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($cal = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $cal->semester_end; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_enroll" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>