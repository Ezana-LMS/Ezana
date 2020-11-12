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

                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>