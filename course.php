<?php
/*
 * Created on Thu Apr 01 2021
 *
 * The MIT License (MIT)
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Module */
if (isset($_POST['add_module'])) {
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Modules WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Module With This Code Already Exists";
            } else {
                $err = "Module  Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $course_name = $_POST['course_name'];
            $course_id = $_POST['course_id'];
            $course_duration = $_POST['course_duration'];
            $exam_weight_percentage = $_POST['exam_weight_percentage'];
            $cat_weight_percentage = $_POST['cat_weight_percentage'];
            $lectures_number = $_POST['lectures_number'];
            $created_at = date('d M Y');
            $faculty_id = $_POST['faculty_id'];

            $query = "INSERT INTO ezanaLMS_Modules (id, name, code, details, course_name, course_id, course_duration, exam_weight_percentage, cat_weight_percentage, lectures_number, created_at, faculty_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssss', $id, $name, $code, $details, $course_name, $course_id, $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $created_at, $faculty_id);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Module Created";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/*  Update Course*/
if (isset($_POST['update_course'])) {
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

        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $email = $_POST['email'];
        $hod  = $_POST['hod'];

        $query = "UPDATE ezanaLMS_Courses SET   code =?, name =?, email =?, hod = ?,  details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $code, $name, $email, $hod,  $details, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Course Updated" && header("refresh:1; url=course.php?view=$id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Course HOD */
if (isset($_POST['update_course_head'])) {

    $id = $_POST['id'];
    $email = $_POST['email'];
    $hod  = $_POST['hod'];

    $query = "UPDATE ezanaLMS_Courses SET  email =?, hod = ? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $email, $hod, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Course Head Updated" && header("refresh:1; url=course.php?view=$id");
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
        while ($course = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
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
                                <a href="non_teaching_staff.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-secret"></i>
                                    <p>
                                        Non Teaching Staff
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
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        System Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>Reports</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="system_settings.php" class="nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>System Settings</p>
                                        </a>
                                    </li>
                                </ul>
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
                                <h1 class="m-0 text-dark"><?php echo $course->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="courses.php">Courses</a></li>
                                    <li class="breadcrumb-item active"><?php echo $course->name; ?></li>
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
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-course-<?php echo $course->id; ?>">Edit Course</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-course-head">Edit Course Head</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Module</button>
                                    </div>
                                    <!-- Add Module -->
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Fill All Required Values </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Add Module Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Module Name</label>
                                                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Module Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Course Name</label>
                                                                    <input type="text" value="<?php echo $course->name; ?>" required name="course_name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">

                                                                <div class="form-group col-md-4" style="display:none">
                                                                    <label for="">Course ID</label>
                                                                    <input type="text" readonly value="<?php echo $course->id; ?>" required name="course_id" class="form-control">
                                                                    <input type="text" readonly value="<?php echo $course->faculty_id; ?>" required name="faculty_id" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Teaching Duration</label>
                                                                    <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Number Of Lectures Per Week</label>
                                                                    <input type="text" required name="lectures_number" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Module CAT Weight Percentage</label>
                                                                    <input type="text" required name="cat_weight_percentage" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Module End Exam Weight Percentage</label>
                                                                    <input type="text" required name="exam_weight_percentage" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Module Details</label>
                                                                    <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Add Module -->
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Course Side Menu -->
                                <?php require_once('public/partials/_coursemenu.php'); ?>
                                <!-- End Course Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Update Course Modal -->
                                            <div class="modal fade" id="update-course-<?php echo $course->id; ?>">
                                                <div class="modal-dialog  modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Fill All Values</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Course Name</label>
                                                                            <input type="text" required name="name" value="<?php echo $course->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Course Number / Code</label>
                                                                            <input type="text" required name="code" value="<?php echo $course->code; ?>"" class=" form-control">
                                                                            <input type="hidden" required name="id" value="<?php echo $course->id; ?>"" class=" form-control">
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Course Head</label>
                                                                            <input type="text" required name="hod" value="<?php echo $course->hod; ?>" class="form-control" id="exampleInputEmail1">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Course Head Email</label>
                                                                            <input type="text" required name="email" value="<?php echo $course->email; ?>" class=" form-control">
                                                                        </div>
                                                                    </div> -->
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="exampleInputPassword1">Course Description</label>
                                                                            <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $course->details; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer text-right">
                                                                    <button type="submit" name="update_course" class="btn btn-primary">Update Course</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--End Update Course Modal -->
                                            <!-- Update Course HOD -->
                                            <div class="modal fade" id="modal-default">
                                                <div class="modal-dialog  modal-xl">
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
                                                                    <div class="row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Course Head </label>
                                                                            <select class='form-control basic' id="CourseHead" name="head" onchange="getCourseHeadDetails(this.value);">
                                                                                <option selected>Select Course Head</option>
                                                                                <?php
                                                                                $ret = "SELECT * FROM `ezanaLMS_Lecturers` ";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute(); //ok
                                                                                $res = $stmt->get_result();
                                                                                while ($course_hod = $res->fetch_object()) {
                                                                                ?>
                                                                                    <option><?php echo $course_hod->name; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Course HOD Email</label>
                                                                            <input type="text" required name="email" id="CourseHeadEmail" class="form-control">
                                                                            <input type="hidden" required name="id" value="<?php echo $course->id; ?>" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer text-right">
                                                                    <button type="submit" name="update_course_head" class="btn btn-primary">Update Course Head</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Update Course HOD -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card card-widget widget-user-2">
                                                        <div class="widget-user-header bg-primary">
                                                            <span>
                                                                <i class="fas fa-arrow-left"></i><a href="courses.php" class="text-white"> Back</a>
                                                                <h3 class="text-center"><?php echo $course->name; ?></h3>
                                                            </span>
                                                        </div>
                                                        <div class="card-footer p-0">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <ul class="nav flex-column">
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Code : <span class="float-right "><?php echo $course->code; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Head : <span class="float-right "><?php echo $course->hod; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Head Email : <a href="mailto:<?php echo $course->email; ?>"><span class="float-right "><?php echo $course->email; ?></a></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                No Of Enrolled Students :
                                                                                <span class="float-right ">
                                                                                    <?php
                                                                                    /* Get Enrolled Students To This Course */
                                                                                    $course_code = $course->code;
                                                                                    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Enrollments` WHERE course_code = '$course_code' ";
                                                                                    $stmt = $mysqli->prepare($query);
                                                                                    $stmt->execute();
                                                                                    $stmt->bind_result($studentenrollments);
                                                                                    $stmt->fetch();
                                                                                    $stmt->close();

                                                                                    echo $studentenrollments;
                                                                                    ?>
                                                                                </span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                No Of Modules :
                                                                                <span class="float-right ">
                                                                                    <?php
                                                                                    /* Get All Modules Under Respective Course */
                                                                                    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE course_id = '$view' ";
                                                                                    $stmt = $mysqli->prepare($query);
                                                                                    $stmt->execute();
                                                                                    $stmt->bind_result($modulescount);
                                                                                    $stmt->fetch();
                                                                                    $stmt->close();

                                                                                    echo $modulescount;
                                                                                    ?>
                                                                                </span>
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <ul class="nav flex-column">
                                                                        <?php
                                                                        /* Load Departmental Details Of This Course */
                                                                        $department_id = $course->department_id;
                                                                        $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id= '$department_id' ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($department = $res->fetch_object()) {
                                                                        ?>
                                                                            <li class="nav-item">
                                                                                <span class="nav-link text-primary">
                                                                                    Department Code : <span class="float-right "><?php echo $department->code; ?></span>
                                                                                </span>
                                                                            </li>

                                                                            <li class="nav-item">
                                                                                <span class="nav-link text-primary">
                                                                                    Department Name : <span class="float-right "><?php echo $department->name; ?></span>
                                                                                </span>
                                                                            </li>
                                                                        <?php }
                                                                        $faculty_id = $course->faculty_id;
                                                                        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$faculty_id' ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($faculty = $res->fetch_object()) {
                                                                        ?>
                                                                            <li class="nav-item">
                                                                                <span class="nav-link text-primary">
                                                                                    Faculty / School Code : <span class="float-right "><?php echo $faculty->code; ?></span>
                                                                                </span>
                                                                            </li>

                                                                            <li class="nav-item">
                                                                                <span class="nav-link text-primary">
                                                                                    Faculty / School Name : <span class="float-right "><?php echo $faculty->name; ?></span>
                                                                                </span>
                                                                            </li>
                                                                        <?php
                                                                        } ?>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="col-md-12">
                                                                <ul class="nav flex-column">
                                                                    <li class="nav-item">
                                                                        <span class="nav-link text-center text-primary">
                                                                            Course Details
                                                                        </span>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <?php echo $course->details; ?>
                                                                    </li>
                                                                </ul>
                                                            </div>
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
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>