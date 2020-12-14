<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Time Table */
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
            $faculty = $_POST['faculty'];
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
            /* Course ID */
            $course_id = $_POST['course_id'];
            $query = "INSERT INTO ezanaLMS_Enrollments (id, faculty_id, code, student_adm, student_name, semester_enrolled, created_at, course_code, course_name, semester_start, semester_end, academic_year_enrolled, module_name, module_code) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssss', $id, $faculty, $code, $student_adm, $student_name, $semester_enrolled, $created_at, $course_code, $course_name, $semester_start, $semester_end, $academic_year_enrolled, $module_name, $module_code);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Enrolled";// && header("refresh:1; url=enrollments.php?view=$course_id");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/*  Update Enrollments*/


/* Delete Enrollment */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_TimeTable WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=timetables.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('public/partials/_analytics.php');
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($course = $res->fetch_object()) {
            $CourseCode = $course->code;
            /* Time Tables Under This Course */
            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE code = '$CourseCode'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($course = $res->fetch_object()) {
        ?>
                <!-- /.navbar -->
                <!-- Main Sidebar Container -->
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <a href="dashboard.php" class="brand-link">
                        <img src="public/dist/img/logo.png" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3">
                        <span class="brand-text font-weight-light">Ezana LMS</span>
                    </a>
                    <!-- Sidebar -->
                    <div class="sidebar">
                        <!-- Sidebar Menu -->
                        <nav class="mt-2">
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                                <li class="nav-item">
                                    <a href="dashboard.php" class=" nav-link">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>
                                            Dashboard
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="faculties.php" class=" nav-link">
                                        <i class="nav-icon fas fa-university"></i>
                                        <p>
                                            Faculties
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="departments.php" class=" nav-link">
                                        <i class="nav-icon fas fa-building"></i>
                                        <p>
                                            Departments
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="courses.php" class="active nav-link">
                                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                        <p>
                                            Courses
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="modules.php" class="nav-link">
                                        <i class="nav-icon fas fa-chalkboard"></i>
                                        <p>
                                            Modules
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="lecturers.php" class="nav-link">
                                        <i class="nav-icon fas fa-user-tie"></i>
                                        <p>
                                            Lecturers
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="students.php" class="nav-link">
                                        <i class="nav-icon fas fa-user-graduate"></i>
                                        <p>
                                            Students
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="settings.php" class="nav-link">
                                        <i class="nav-icon fas fa-cogs"></i>
                                        <p>
                                            System Settings
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $course->name; ?> Enrolled Students</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="courses.php">Courses</a></li>
                                        <li class="breadcrumb-item active"><?php echo $course->name; ?> TT</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="text-left">
                                    <nav class="navbar navbar-light bg-light col-md-12">
                                        <form class="form-inline" action="module_search_result.php" method="GET">
                                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Enrollment</button>
                                        <div class="modal fade" id="modal-default">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Fill All Required Values </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row" style="display:none">
                                                                    <div class="form-group col-md-6">
                                                                        <label for=""> Name</label>
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                        <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                                                        <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
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
                                                                            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$course->faculty_id'  ";
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
                                                                        <input type="text"  required name="course_code" value="<?php echo $course->code;?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Course Name</label>
                                                                        <input type="text"  readonly required value="<?php echo $course->name;?>" name="course_name" class="form-control">
                                                                    </div>
                                                                    <hr>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Module Name</label>
                                                                        <select class='form-control basic' id="ModuleName" onchange="getModuleDetails(this.value);" name="module_name">
                                                                            <option selected>Select Module Name </option>
                                                                            <?php
                                                                            $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE course_id = '$course->id'   ";
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
                                                                        <input type="text" id="ModuleCode" required name="module_code" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Semester Enrolled</label>
                                                                        <select class='form-control basic' name="semester_enrolled">
                                                                            <option selected>Select Semester Name</option>
                                                                            <?php
                                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$course->faculty_id'  ";
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
                                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$course->faculty_id'  ";
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
                                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$course->faculty_id'  ";
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
                                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$course->faculty_id'  ";
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
                                                            <div class="card-footer text-right">
                                                                <button type="submit" name="add_enroll" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="col-md-12">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title"><?php echo $course->name; ?></h3>
                                                    <div class="card-tools text-right">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <ul class="list-group">

                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="modules.php?view=<?php echo $course->id; ?>">
                                                                Modules
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="timetables.php?view=<?php echo $course->id; ?>">
                                                                TimeTable
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="enrollments.php?view=<?php echo $course->id; ?>">
                                                                Enrollments
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body">
                                                                <table id="export-dt" class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Admission</th>
                                                                            <th>Name</th>
                                                                            <th>Module</th>
                                                                            <th>Academic Yr</th>
                                                                            <th>Sem Enrolled</th>
                                                                            <th>Sem Start</th>
                                                                            <th>Sem End </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE course_code = '$CourseCode' ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        $cnt = 1;
                                                                        while ($en = $res->fetch_object()) {
                                                                        ?>

                                                                            <tr>
                                                                                <td><?php echo $cnt; ?></td>
                                                                                <td><?php echo $en->student_adm; ?></td>
                                                                                <td><?php echo $en->student_name; ?></td>
                                                                                <td><?php echo $en->module_name; ?></td>
                                                                                <td><?php echo $en->academic_year_enrolled; ?></td>
                                                                                <td><?php echo $en->semester_enrolled; ?></td>
                                                                                <td><?php echo date('d M Y', strtotime($en->semester_start)); ?></td>
                                                                                <td><?php echo date('d M Y', strtotime($en->semester_end)); ?></td>
                                                                            </tr>
                                                                        <?php $cnt = $cnt + 1;
                                                                        } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Main Footer -->
                <?php
                require_once('public/partials/_footer.php');
            }
        } ?>
        </div>
    </div>
    <!-- ./wrapper -->
    <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>