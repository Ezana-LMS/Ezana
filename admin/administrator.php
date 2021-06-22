<?php
/*
 * Created on Tue Jun 22 2021
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
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');
admin_checklogin();
$time =  time();

/* Update Profile Picture */
if (isset($_POST['update_picture'])) {
    $view = $_GET['view'];
    $profile_pic = $time . $_FILES['profile_pic']['name'];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../Data/User_Profiles/admins/" . $time . $_FILES["profile_pic"]["name"]);
    $query = "UPDATE ezanaLMS_Admins  SET  profile_pic =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $profile_pic, $view);
    $stmt->execute();
    if ($stmt) {
        $success = "Profile Picture Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Update School Details */
if (isset($_POST['update_school'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = mysqli_real_escape_string($mysqli, trim($_POST['id']));
    } else {
        $error = 1;
        $err = "ID Cannot Be Empty";
    }
    if (isset($_POST['school']) && !empty($_POST['school'])) {
        $school = mysqli_real_escape_string($mysqli, trim($_POST['school']));
    } else {
        $error = 1;
        $err = "Faculty Name Cannot Be Empty";
    }
    if (isset($_POST['school_id']) && !empty($_POST['school_id'])) {
        $school_id = mysqli_real_escape_string($mysqli, trim($_POST['school_id']));
    } else {
        $error = 1;
        $err = "School ID Cannot Be Empty";
    }
    if (!$error) {

        $query = "UPDATE ezanaLMS_Admins SET school =?, school_id =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sss', $school, $school_id, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "School Details Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Profile */
if (isset($_POST['update_non_teaching_staff'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = mysqli_real_escape_string($mysqli, trim($_POST['id']));
    } else {
        $error = 1;
        $err = "ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Name Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }

    if (isset($_POST['rank']) && !empty($_POST['rank'])) {
        $rank = mysqli_real_escape_string($mysqli, trim($_POST['rank']));
    } else {
        $error = 1;
        $err = "Rank Cannot Be Empty";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($mysqli, trim($_POST['phone']));
    } else {
        $error = 1;
        $err = "Phone Cannot Be Empty";
    }

    if (isset($_POST['adr']) && !empty($_POST['adr'])) {
        $adr = mysqli_real_escape_string($mysqli, trim($_POST['adr']));
    } else {
        $error = 1;
        $err = "Address Cannot Be Empty";
    }

    if (!$error) {

        $gender = $_POST['gender'];
        $employee_id = $_POST['employee_id'];
        $date_employed = $_POST['date_employed'];

        $query = "UPDATE ezanaLMS_Admins SET name =?, email =?,  rank =?, phone =?, adr =?, gender = ?, employee_id =?, date_employed =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssss', $name, $email, $rank, $phone, $adr, $gender, $employee_id, $date_employed,  $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Profile Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Change Password */
if (isset($_POST['change_password'])) {

    $error = 0;

    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = mysqli_real_escape_string($mysqli, trim((($_POST['new_password']))));
    } else {
        $error = 1;
        $err = "New Password Cannot Be Empty";
    }
    if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
        $confirm_password = mysqli_real_escape_string($mysqli, trim((($_POST['confirm_password']))));
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
            $mailed_password = $new_password;
            $hashed_password  = sha1(md5($_POST['new_password']));
            $query = "UPDATE ezanaLMS_Admins SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $hashed_password, $view);
            $stmt->execute();
            /* Mail New Password */
            require_once('../config/password_reset_mailer.php');
            if ($stmt && $mail->send()) {
                $success = "Password Changed";
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
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "../Data/Memos/" . $time . $_FILES["attachments"]["name"]);
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
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Departmental Documents */
if (isset($_POST['add_departmental_doc'])) {
    $id = $_POST['id'];
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "../Data/Departmental_Documents/" . $time . $_FILES["attachments"]["name"]);
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
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
        $view  = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($admin = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($admin->profile_pic == '') {
                $dpic = "<img height='150' width = '150' class='img-fluid' src='../assets/img/no-profile.png' alt='User profile picture'>";
            } else {
                $dpic = "<img class='img-fluid img-square' height='150' width = '150' src='../Data/User_Profiles/admins/$admin->profile_pic' alt='User profile picture'>";
            } ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <?php require_once('partials/aside.php');?>
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
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="non_teaching_staff">Non Teaching Staff</a></li>
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
                                                                <div class="col-sm-12">
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
                                                                            $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$admin->school_id'   ";
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
                                                                        <input type="text" readonly required name="department_name" id="DepartmentName" class="form-control">
                                                                        <input type="hidden" required name="department_id" id="DepartmentID" class="form-control">
                                                                        <input type="hidden" required name="faculty" value="<?php echo $admin->school_id; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Memo Notice Attachment (PDF Or Docx)</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input name="attachments" type="file" class="custom-file-input">
                                                                                <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Created By</label>
                                                                        <input type="text" required name="created_by" readonly value="<?php echo $admin->name; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Type</label>
                                                                        <select class='form-control basic' name="type">
                                                                            <option selected>Memo</option>
                                                                            <option>Notice</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="exampleInputPassword1">Type Departmental Memo / Notice</label>
                                                                        <textarea name="departmental_memo" rows="10" class="form-control Summernote"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="add_memo" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
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
                                                                            $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$admin->school_id'  ";
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
                                                                        <input type="text" readonly required name="department_name" id="DepName" class="form-control">
                                                                        <input type="hidden" required name="department_id" id="DepID" class="form-control">
                                                                        <input type="hidden" required name="faculty" id="DepFacID" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Departmental Document (PDF Or Docx)</label>
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
                                                            <div class="text-right">
                                                                <button type="submit" name="add_departmental_doc" class="btn btn-primary">Add Departmental Document</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->

                                        <!-- Allocated School Update -->
                                        <div class="modal fade" id="edit-school">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update <?php echo $admin->name; ?> Allocated School Details </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">School Code</label>
                                                                        <select class="form-control basic" onchange="OptimizedFacultyDetails(this.value);" id="FacultyCode">
                                                                            <option>Select School / Faculty Code</option>
                                                                            <?php
                                                                            $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($faculty = $res->fetch_object()) {
                                                                            ?>
                                                                                <option><?php echo $faculty->code; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Faculty Name </label>
                                                                        <input type="text" readonly required name="school" id="FacultyName" class="form-control">
                                                                        <input type="hidden" required name="school_id" id="AdminFacultyID" class="form-control">
                                                                        <input type="hidden" required name="id" value="<?php echo $admin->id; ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="update_school" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Allocated School Details Update -->

                                        <!-- Update Profile -->
                                        <div class="modal fade" id="edit-profile">
                                            <div class="modal-dialog  modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update <?php echo $admin->name; ?> Details </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Name</label>
                                                                        <input type="text" required value="<?php echo $admin->name; ?>" name="name" class="form-control" id="exampleInputEmail1">
                                                                        <input type="hidden" required name="id" value="<?php echo $admin->id; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Work Email</label>
                                                                        <input type="text" value="<?php echo $admin->email; ?>" required name="email" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Phone Number</label>
                                                                        <input type="text" value="<?php echo $admin->phone; ?>" required name="phone" class="form-control">
                                                                    </div>

                                                                    <div class="form-group col-md-3">
                                                                        <label for="">Rank</label>
                                                                        <select class="form-control basic" name="rank">
                                                                            <option>System Administrator</option>
                                                                            <option>Education Administrator</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-3">
                                                                        <label for="">Gender</label>
                                                                        <select class="form-control basic" name="gender">
                                                                            <option>Male</option>
                                                                            <option>Female</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-3">
                                                                        <label for="">Employee ID</label>
                                                                        <input type="text" value="<?php echo $admin->employee_id; ?>" required name="employee_id" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-3">
                                                                        <label for="">Date Employed</label>
                                                                        <input type="date" placeholder="DD-MM-YYYY" value="<?php echo $admin->date_employed; ?>" required name="date_employed" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="exampleInputPassword1">Address</label>
                                                                        <textarea  name="adr" rows="2" class="form-control Summernote"><?php echo $admin->adr; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="update_non_teaching_staff" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- End Update Profile -->

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
                                                <b>Allocated School / Faculty</b> <a class="float-right"> <?php echo $admin->school; ?></a>
                                            </li>
                                            <!-- <li class="list-group-item">
                                                <b>Previledge</b> <a class="float-right"><?php echo $admin->previledge; ?></a>
                                            </li> -->
                                            <li class="list-group-item">
                                                <b>Rank</b> <a class="float-right"><?php echo $admin->rank; ?></a>
                                            </li>
                                            <li class="list-group-item text-center">
                                                <a href="#edit-school" data-toggle="modal" class="badge badge-primary"><i class='fas fa-edit'></i> Edit Allocated School</a>
                                                <a href="#edit-profile" data-toggle="modal" class="badge badge-success"><i class='fas fa-user-edit'></i> Edit Profile</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-8">
                                <div class="card card-primary card-outline">
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
                                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE faculty_id = '$admin->school_id' AND  type = 'Memo' || type = 'Notice'  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
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
                                                                                    <h4 class="modal-title"><?php echo $memo->department_name; ?> <?php echo $memo->type; ?> Created On <span class='text-success'><?php echo date('d-M-Y, g:ia', strtotime($memo->created_at)); ?></span></h4>
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
                                                                                        "<a href='../Data/Memos/$memo->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download $memo->type </a>";
                                                                                    } else {
                                                                                        echo
                                                                                        "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $memo->type Attachment Not Available </a>";
                                                                                    }
                                                                                    ?>

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
                                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE  type = 'Departmental Document' AND faculty_id = '$admin->school_id'   ";
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
                                                                                    <h4 class="modal-title"><?php echo $doc->department_name . " " . $doc->type; ?> Created On <span class='text-success'><?php echo date('d-M-Y, g:ia', strtotime($doc->created_at)); ?></span></h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center">
                                                                                    <?php
                                                                                    if ($doc->attachments != '') {
                                                                                        echo
                                                                                        "<a href='../Data/Departmental_Documents/$doc->attachments' target='_blank' class='btn btn-outline-success'><i class='fas fa-download'></i> Download $memo->type </a>";
                                                                                    } else {
                                                                                        echo
                                                                                        "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $doc->type Attachment Not Available </a>";
                                                                                    }
                                                                                    ?>

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
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-right row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button type="submit" name="change_password" class="btn btn-primary">Change Password And Email Reset Instructions</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        <?php
            require_once("partials/footer.php");
            require_once("partials/scripts.php");
        }
        ?>
</body>

</html>