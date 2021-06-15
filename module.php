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

/*  Update Module*/
if (isset($_POST['update_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $course_duration = $_POST['course_duration'];
        $exam_weight_percentage = $_POST['exam_weight_percentage'];
        $cat_weight_percentage = $_POST['cat_weight_percentage'];
        $lectures_number = $_POST['lectures_number'];
        $updated_at = date('d M Y');
        $query = "UPDATE ezanaLMS_Modules SET  name =?, code =?, details =?,  course_duration =?, exam_weight_percentage =?, cat_weight_percentage=?,  lectures_number =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssss', $name, $code, $details,  $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $updated_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated" && header("refresh:1; url=module.php?view=$id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Add Module Notice */
if (isset($_POST['add_notice'])) {
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
        $id = $_POST['id'];
        $time = date("d-M-Y") . "-" . time();
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $faculty_id = $_POST['faculty_id'];
        $module_id = $_POST['module_id'];

        $attachments = $time.$_FILES['attachments']['name'];
        move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" .$time.$_FILES["attachments"]["name"]);
        $query = "INSERT INTO ezanaLMS_ModulesAnnouncements (id, module_name, module_code, announcements, created_by,attachments, faculty_id) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $module_name, $module_code, $announcements, $created_by, $attachments, $faculty_id);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated" && header("refresh:1; url=module_notices.php?view=$module_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
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
                                <a href="courses.php" class=" nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class="active nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="modules.php">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
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
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-module-<?php echo $mod->id; ?>">Edit Module</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Module Notice Or Memo</button>
                                    </div>

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
                                                    <!-- Add Module Notices Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Announcement Posted By</label>
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

                                                                <div class="form-group col-md-6">
                                                                    <label for="">Upload Module Memo (PDF Or Docx)</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h2 class="text-center">Or</h2>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Type Module Announcements</label>
                                                                    <textarea   name="announcements" placeholder="Type Module Announcement" rows="20" class="form-control Summernote"></textarea>
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                    <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_notice" class="btn btn-primary">Post</button>
                                                        </div>
                                                    </form>
                                                    <!-- End Module Notice Form -->
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Update Module Modal -->
                                    <div class="modal fade" id="update-module-<?php echo $mod->id; ?>">
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
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Module Name</label>
                                                                    <input type="text" value="<?php echo $mod->name; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Module Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $mod->code; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Course Name</label>
                                                                    <input type="text" name="c_name" readonly required value="<?php echo $mod->course_name; ?>" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->course_id; ?>" required name="course_id" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Teaching Duration</label>
                                                                    <input type="text" value="<?php echo $mod->course_duration; ?>" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Number Of Lectures Per Week</label>
                                                                    <input type="text" value="<?php echo $mod->lectures_number; ?>" required name="lectures_number" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">CAT Exam Weight Percentage</label>
                                                                    <input type="text" value="<?php echo $mod->cat_weight_percentage; ?>" required name="cat_weight_percentage" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">End Exam Weight Percentage</label>
                                                                    <input type="text" value="<?php echo $mod->exam_weight_percentage; ?>" required name="exam_weight_percentage" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Module Details</label>
                                                                    <textarea required  name="details" rows="10" class="form-control Summernote"><?php echo $mod->details; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="update_module" class="btn btn-primary">Update Module</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Update Module Modal -->
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('public/partials/_modulemenu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header text-center bg-primary">
                                                    <h3 class=""><?php echo $mod->name; ?></h3>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Module Name : <span class="float-right "><?php echo $mod->name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Module Code : <span class="float-right "><?php echo $mod->code; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Course Name : <span class="float-right "><?php echo $mod->course_name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Teaching Duration : <span class="float-right "><?php echo $mod->course_duration; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        No Of Lecturers Per Week : <span class="float-right "><?php echo $mod->lectures_number; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Exam Weight Percentage : <span class="float-right "><?php echo $mod->exam_weight_percentage; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Cat Weight Percentage : <span class="float-right "><?php echo $mod->cat_weight_percentage; ?></span>
                                                                    </span>
                                                                </li>
                                                                <!-- Course And Department  Module Registered To -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$mod->course_id'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($course = $res->fetch_object()) {
                                                                    $department_id = $course->department_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id = '$department_id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($department = $res->fetch_object()) {
                                                                        /* Number Of Students Registered To This Module */
                                                                        $query = "SELECT COUNT(*)  FROM `ezanaLMS_Enrollments` WHERE module_code = '$mod->code' ";
                                                                        $stmt = $mysqli->prepare($query);
                                                                        $stmt->execute();
                                                                        $stmt->bind_result($enrolled_students);
                                                                        $stmt->fetch();
                                                                        $stmt->close();
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
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Code : <span class="float-right "><?php echo $course->code; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Name : <span class="float-right "><?php echo $course->name; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Enrolled Students : <span class="float-right "><?php echo $enrolled_students ?></span>
                                                                            </span>
                                                                        </li>
                                                                <?php

                                                                    }
                                                                } ?>
                                                            </ul>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <!-- Assigned Lec Details -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($ass = $res->fetch_object()) {
                                                                    /* Lec Details */
                                                                    $lec = $ass->lec_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$lec'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($lecturer = $res->fetch_object()) {

                                                                ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Name : <span class="float-right "><?php echo $lecturer->name; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Email : <span class="float-right "><?php echo $lecturer->email; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer ID / Passport No : <span class="float-right "><?php echo $lecturer->idno; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Address : <span class="float-right "><?php echo $lecturer->adr; ?></span>
                                                                            </span>
                                                                        </li>
                                                                    <?php }
                                                                }
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code' AND status= 'Guest Lecturer'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($GuestLec = $res->fetch_object()) {
                                                                    $Guestlecture = $GuestLec->lec_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$Guestlecture'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($Glecturer = $res->fetch_object()) {
                                                                    ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Name : <span class="float-right "><?php echo $Glecturer->name; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Email : <span class="float-right "><?php echo $Glecturer->email; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer ID / Passport No : <span class="float-right "><?php echo $Glecturer->idno; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Phone : <span class="float-right "><?php echo $Glecturer->phone; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Address : <span class="float-right "><?php echo $Glecturer->adr; ?></span>
                                                                            </span>
                                                                        </li>
                                                                <?php
                                                                    }
                                                                } ?>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <span class="nav-link text-center text-primary">
                                                                    Module Details
                                                                </span>
                                                            </li>
                                                            <li class="nav-item">
                                                                <?php echo $mod->details; ?>
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
                    </section>
                    <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>