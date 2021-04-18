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
/* Update Profile Picture */

if (isset($_POST['update_picture'])) {
    $view = $_GET['view'];
    $profile_pic = $_FILES['profile_pic']['name'];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/admins/" . $_FILES["profile_pic"]["name"]);
    $query = "UPDATE ezanaLMS_Admins  SET  profile_pic =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $profile_pic, $view);
    $stmt->execute();
    if ($stmt) {
        $success = "Profile Picture Updated" && header("Refresh: 0");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Change Password */
if (isset($_POST['change_password'])) {

    //Change Password
    $error = 0;

    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['confirm_password']))));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim((($_POST['email']))));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }

    if (!$error) {
        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $err = "Passwords Do Not Match";
        } else {
            $view = $_GET['view'];
            $new_password  = sha1(md5($_POST['new_password']));
            $query = "UPDATE ezanaLMS_Admins SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $new_password, $view);
            $stmt->execute();
            /* Mail New Password */
            require_once('configs/mail.php');
            if ($stmt && $mail->send()) {
                $success = "Password Changed" && header("Refresh: 0");
            } else {
                $err = "Please Try Again Or Try Later, $mail->ErrorInfo";
            }
        }
    }
}



/* Add Department Memo */
if (isset($_POST['add_memo'])) {
    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $departmental_memo = $_POST['departmental_memo'];
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, created_by, department_id, department_name, type, departmental_memo, attachments, faculty_id) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $id, $created_by, $department_id, $department_name, $type, $departmental_memo, $attachments, $faculty);
    $stmt->execute();
    if ($stmt) {
        $success = "Departmental $type Posted";
    } else {
        //inject alert that profile update task failed
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Departmental Documents */
if (isset($_POST['add_departmental_doc'])) {
    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $type = $_POST['type'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, created_by,  department_id, department_name, type, attachments,  faculty_id) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $created_by, $department_id, $department_name, $type,  $attachments, $faculty);
    $stmt->execute();
    if ($stmt) {
        $success = "$type Posted";
    } else {
        //inject alert that profile update task failed
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
        $view  = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($admin = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($admin->profile_pic == '') {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/dist/img/no-profile.png' alt='User profile picture'>";
            } else {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/uploads/UserImages/admins/$admin->profile_pic' alt='User profile picture'>";
            } ?>
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
                                <a href="faculties.php" class="nav-link">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Faculties
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="departments.php" class="nav-link">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Departments
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="courses.php" class="nav-link">
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
                                <a href="non_teaching_staff.php" class="active nav-link">
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

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $admin->name; ?> Profile</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="non_teaching_staff.php">Non Teaching Staff</a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <?php echo $dpic; ?>
                                            <span><a href="#edit-profile-pic" class="fas fa-pen text-primary" data-toggle="modal"></a></span>
                                        </div>

                                        <!-- Edit Profile Picture Modal -->
                                        <div class="modal fade" id="edit-profile-pic" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title " id="exampleModalLabel">Update <?php echo $admin->name; ?> Profile Picture</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method='post' enctype="multipart/form-data" class="form-horizontal">
                                                            <div class="form-group row">
                                                                <label for="inputSkills" class="col-sm-2 col-form-label">Profile Picture</label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input type="file" name="profile_pic" class="custom-file-input" id="exampleInputFile">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group text-right row">
                                                                <div class="offset-sm-2 col-sm-10">
                                                                    <button type="submit" name="update_picture" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->

                                        <!-- Add Department Memo Modal -->
                                        <div class="modal fade" id="add-memo">
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
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                        <input type="hidden" required name="department_id" value="<?php echo $department->id; ?>" class="form-control">
                                                                        <input type="hidden" required name="department_name" value="<?php echo $department->name; ?>" class="form-control">
                                                                        <input type="hidden" required name="faculty" value="<?php echo $department->faculty_id; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Department Code</label>
                                                                        <select class='form-control basic' onchange="OptimizedGetDepartmentDetails(this.value);" id="DeparmentCode">
                                                                            <option selected>Select Department Code</option>
                                                                            <?php
                                                                            $ret = "SELECT * FROM `ezanaLMS_Departments` ORDER BY `name` ASC   ";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($dep = $res->fetch_object()) {
                                                                            ?>
                                                                                <option><?php echo $dep->code; ?></option>
                                                                            <?php
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Department Name</label>
                                                                        <input type="text" required name="department_name" id="DepartmentName" class="form-control">
                                                                        <input type="hidden" required name="department_id" id="DepartmentID" class="form-control">
                                                                        <input type="hidden" required name="faculty_id" id="FacultyID" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Upload Memo / Notice Attachment (PDF Or Docx)</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input name="attachments" type="file" class="custom-file-input">
                                                                                <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Created By</label>
                                                                        <input type="text" required name="created_by" value="<?php echo $admin->name; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Type</label>
                                                                        <select class='form-control basic' name="type">
                                                                            <option selected>Memo</option>
                                                                            <option>Notice</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <h2 class="text-center"> Or </h2>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="exampleInputPassword1">Type Departmental Memo / Notice</label>
                                                                        <textarea name="departmental_memo" rows="10" class="form-control Summernote"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer text-right">
                                                                <button type="submit" name="add_memo" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->

                                        <!-- Add Departmental Document -->
                                        <div class="modal fade" id="add-document">
                                            <div class="modal-dialog  modal-lg">
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
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Department Code</label>
                                                                        <select class='form-control basic' onchange="getDepartmentDetailsOnDocuments(this.value);" id="DepCode">
                                                                            <option selected>Select Department Code</option>
                                                                            <?php
                                                                            $ret = "SELECT * FROM `ezanaLMS_Departments` ORDER BY `name` ASC   ";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($dep = $res->fetch_object()) {
                                                                            ?>
                                                                                <option><?php echo $dep->code; ?></option>
                                                                            <?php
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Department Name</label>
                                                                        <input type="text" required name="department_name" id="DepName" class="form-control">
                                                                        <input type="hidden" required name="department_id" id="DepID" class="form-control">
                                                                        <input type="hidden" required name="faculty" id="DepFacID" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Upload Departmental Document (PDF Or Docx)</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input name="attachments" type="file" class="custom-file-input">
                                                                                <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Created By</label>
                                                                        <input type="text" required name="created_by" value="<?php echo $admin->name; ?>" class="form-control">
                                                                    </div>
                                                                    <div style="display:none" class="form-group col-md-6">
                                                                        <label for="">Type</label>
                                                                        <select class='form-control basic' name="type">
                                                                            <option selected>Departmental Document</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer text-right">
                                                                <button type="submit" name="add_departmental_doc" class="btn btn-primary">Add Departmental Document</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->

                                        <h3 class="profile-username text-center"></h3>

                                        <p class="text-muted text-center"></p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Full Name: </b> <a class="float-right"><?php echo $admin->name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Gender: </b> <a class="float-right"><?php echo $admin->gender; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Employee ID: </b> <a class="float-right"><?php echo $admin->employee_id; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Work Email: </b> <a class="float-right" href="mailto:<?php echo $admin->email; ?>"><?php echo $admin->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Phone Number: </b> <a class="float-right"><?php echo $admin->phone; ?></a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>Date Employed: </b> <a class="float-right"><?php echo $admin->date_employed; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>School / Faculty</b> <a class="float-right"><?php echo $admin->school; ?></a>
                                            </li>
                                            <!-- <li class="list-group-item">
                                                <b>Previledge</b> <a class="float-right"><?php echo $admin->previledge; ?></a>
                                            </li> -->
                                            <li class="list-group-item">
                                                <b>Rank</b> <a class="float-right"><?php echo $admin->rank; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#notices" data-toggle="tab">Memos And Notices</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#dep_docs" data-toggle="tab">School / Departments Documents</a></li>
                                            <li class="nav-item"><a class="nav-link " href="#changePassword" data-toggle="tab">Password Reset</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="notices">
                                                <div class="text-right">

                                                    <a href="#add-memo" data-toggle="modal" class=" btn btn-outline-success">
                                                        <i class="fas fa-file"></i>
                                                        Add Memo / Notice
                                                    </a>
                                                </div>
                                                <hr>
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Department</th>
                                                            <th>Posted By</th>
                                                            <th>Date Posted</th>
                                                            <th>Type</th>
                                                            <th>Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE type !='Departmental Document' ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($memo = $res->fetch_object()) {
                                                        ?>

                                                            <tr>
                                                                <td><?php echo $memo->department_name; ?></td>
                                                                <td><?php echo $memo->created_by; ?></td>
                                                                <td><?php echo $memo->created_at; ?></td>
                                                                <td><?php echo $memo->type; ?></td>
                                                                <td>
                                                                    <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $memo->id; ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                        View
                                                                    </a>
                                                                    <!-- View Deptmental Memo Modal -->
                                                                    <div class="modal fade" id="view-<?php echo $memo->id; ?>">
                                                                        <div class="modal-dialog  modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title"><?php echo $memo->department_name; ?> <?php echo $memo->type; ?> Created On <span class='text-success'><?php echo $memo->created_at; ?></span></h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <?php echo $memo->departmental_memo; ?>
                                                                                    <hr>
                                                                                    <?php

                                                                                    if ($memo->attachments != '') {
                                                                                        echo
                                                                                        "<a href='public/uploads/EzanaLMSData/memos/$memo->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download $memo->type </a>";
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
                                                                </td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="tab-pane" id="dep_docs">
                                                <div class="text-right">
                                                    <a href="#add-document" data-toggle="modal" class=" pull-right btn btn-outline-success">
                                                        <i class="fas fa-file"></i>
                                                        Add Department Document
                                                    </a>
                                                </div>
                                                <hr>
                                                <table id="faculties" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Department </th>
                                                            <th>Posted By</th>
                                                            <th>Date Posted</th>
                                                            <th>Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE  type = 'Departmental Document'  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($doc = $res->fetch_object()) {
                                                        ?>

                                                            <tr>
                                                                <td><?php echo $doc->department_name; ?></td>
                                                                <td><?php echo $doc->created_by; ?></td>
                                                                <td><?php echo $doc->created_at; ?></td>
                                                                <td>
                                                                    <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $doc->id; ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                        View
                                                                    </a>
                                                                    <!-- View Deptmental Memo Modal -->
                                                                    <div class="modal fade" id="view-<?php echo $doc->id; ?>">
                                                                        <div class="modal-dialog  modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header text-center">
                                                                                    <h4 class="modal-title"><?php echo $doc->department_name . " " . $doc->type; ?> Created On <span class='text-success'><?php echo $doc->created_at; ?></span></h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center">
                                                                                    <?php
                                                                                    if ($doc->attachments != '') {
                                                                                        echo
                                                                                        "<a href='public/uploads/EzanaLMSData/memos/$doc->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download $memo->type </a>";
                                                                                    } else {
                                                                                        echo
                                                                                        "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $doc->type Attachment Not Available </a>";
                                                                                    }
                                                                                    ?>

                                                                                </div>
                                                                                <div class="modal-footer justify-content-between">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

                                            <div class="tab-pane" id="changePassword">
                                                <form method='post' class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 col-form-label">New Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" value="<?php echo $defaultPass; ?>" name="new_password" required class="form-control" id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Confirm New Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" value="<?php echo $defaultPass; ?>" name="confirm_password" required class="form-control" id="inputName2">
                                                            <input type="hidden" name="email" required class="form-control" value="<?php echo $admin->email; ?>">
                                                            <input type="hidden" required name="subject" value="Password Reset" class="form-control">
                                                            <input type="hidden" required name="message" value="Howdy, <?php echo $admin->name; ?>😊. <br> This is your new password: <b><?php echo $defaultPass; ?></b>. <br>  <b>MAKE SURE YOU UPDATE IT UPOUN LOGIN.</b>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-right row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button type="submit" name="change_password" class="btn btn-primary">Change Password And Email Reset Instructions</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>

        <?php
            require_once("public/partials/_footer.php");
            require_once("public/partials/_scripts.php");
        }
        ?>
</body>

</html>