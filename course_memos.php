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
require_once('configs/codeGen.php');
check_login();

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

/* Add Course Notice / Memo */
if (isset($_POST['add_memo'])) {
    $id = $_POST['id'];
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $course_memo = $_POST['course_memo'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    /* Notify Me After Posting Memo / Notice */
    $notif_type = 'Course Memo';
    $status = 'Unread';
    $notification_detail = "Memo For $course_name";

    $query = "INSERT INTO ezanaLMS_CourseMemo (id, created_by, course_id, course_name, course_memo, attachments, faculty_id) VALUES(?,?,?,?,?,?,?)";
    $notif_querry = "INSERT INTO ezanaLMS_Notifications(type, status, notification_detail) VALUES(?,?,?)";

    $stmt = $mysqli->prepare($query);
    $notif_stmt = $mysqli->prepare($notif_querry);

    $rc = $stmt->bind_param('sssssss', $id, $created_by, $course_id, $course_name, $course_memo, $attachments, $faculty);
    $rc = $notif_stmt->bind_param('sss', $notif_type, $status, $notification_detail);

    $stmt->execute();
    $notif_stmt->execute();

    if ($stmt && $notif_stmt) {
        $success = "Course  Memo Added" && header("refresh:1; url=course_memos.php?view=$course_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Update Course Notices And Memo */
if (isset($_POST['update_memo'])) {
    $id = $_POST['id'];
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $course_memo = $_POST['course_memo'];
    $created_at = date('d M Y g:i');
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "UPDATE ezanaLMS_CourseMemo SET created_by =?, course_id =?, course_name =?, course_memo =?, attachments =?, faculty_id =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $created_by, $course_id, $course_name, $course_memo, $attachments, $faculty, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Course  Memo    Updated" && header("refresh:1; url=course_memos.php?view=$course_id");
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Course Memo */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_CourseMemo WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=course_memos.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$view'";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
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
                                <h1 class="m-0 text-dark"><?php echo $course->name; ?> Memos And Notices</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active">Modules</li>
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
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_memo">Add Memo</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Module</button>
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
                                                                    <input type="text" readonly value="<?php echo $course->name; ?>" required name="course_name" class="form-control">
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
                                                                    <textarea required id="textarea" name="details" rows="10" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                                                        </div>
                                                    </form>
                                                    <!-- End Module Form -->
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="add_memo">
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
                                                                    <label for="">Upload Memo (PDF Or Docx)</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input name="attachments" type="file" class="custom-file-input">
                                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                            <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                                                            <input type="hidden" required name="course_name" value="<?php echo $course->name; ?>" class="form-control">
                                                                            <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Created By</label>
                                                                    <input type="text" required name="created_by" class="form-control">
                                                                </div>
                                                                <div style="display:none" class="form-group col-md-6">
                                                                    <label for="">Type</label>
                                                                    <select class='form-control basic' name="type">
                                                                        <option selected>Memo</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <h2 class="text-center">Or </h2>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Type Memo</label>
                                                                    <textarea name="course_memo" rows="10" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_memo" class="btn btn-primary">Add Memo</button>
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
                                <!-- Course Side Menu -->
                                <?php require_once('public/partials/_coursemenu.php'); ?>
                                <!-- End Course Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-12">

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Posted By</th>
                                                        <th>Date Posted</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_CourseMemo` WHERE course_id = '$course->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($memo = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $memo->created_by; ?></td>
                                                            <td><?php echo date('d M Y g:ia', strtotime($memo->created_on)); ?></td>
                                                            <td>
                                                                <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $memo->id; ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                    View
                                                                </a>
                                                                <!-- View Course Memo Modal -->
                                                                <div class="modal fade" id="view-<?php echo $memo->id; ?>">
                                                                    <div class="modal-dialog  modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title"><?php echo $course->name; ?> Memo Created On <span class='text-success'><?php echo date('d M Y g:ia', strtotime($memo->created_on)); ?></span></h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <?php echo $memo->course_memo; ?>
                                                                                <hr>
                                                                                <?php

                                                                                if ($memo->attachments != '') {
                                                                                    echo
                                                                                    "<a href='public/uploads/EzanaLMSData/memos/$memo->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download Memo </a>";
                                                                                } else {
                                                                                    echo
                                                                                    "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $memo->type Attachment Not Available </a>";
                                                                                }
                                                                                ?>

                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $memo->id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update
                                                                </a>
                                                                <!-- Update Course Memo Modal -->
                                                                <div class="modal fade" id="update-<?php echo $memo->id; ?>">
                                                                    <div class="modal-dialog  modal-xl">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Fill All Values </h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                    <div class="card-body">
                                                                                        <div class="row">

                                                                                            <div class="form-group col-md-12">
                                                                                                <input type="hidden" required name="id" value="<?php echo $memo->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="course_id" value="<?php echo $memo->course_id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="course_name" value="<?php echo $memo->course_name; ?>" class="form-control">
                                                                                                <input type="hidden" required name="faculty" value="<?php echo $memo->faculty_id; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Created By</label>
                                                                                                <input type="text" name="created_by" class="form-control" value="<?php echo $memo->created_by; ?>">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Upload Memo | Notice (PDF r Docx)</label>
                                                                                                <div class="input-group">
                                                                                                    <div class="custom-file">
                                                                                                        <input name="attachments" type="file" class="custom-file-input">
                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                        <h2 class="text-center">Or </h2>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Type Memo | Notice</label>
                                                                                                <textarea name="course_memo" id="editor-<?php echo $memo->id; ?>" rows="10" class="form-control"><?php echo $memo->course_memo; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_memo" class="btn btn-primary">Update</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End Update Course Memo Modal -->
                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $memo->id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete
                                                                </a>
                                                                <!-- Delete Confirmation -->
                                                                <div class="modal fade" id="delete-<?php echo $memo->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete This Memo?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="course_memos.php?delete=<?php echo $memo->id; ?>&view=<?php echo $memo->course_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    } ?>
                                                </tbody>
                                            </table>
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