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
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/students/" . $_FILES["profile_pic"]["name"]);

    $query = "UPDATE ezanaLMS_Students  SET  profile_pic =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $profile_pic, $view);
    $stmt->execute();
    if ($stmt) {
        $success = "Profile Picture Updated" && header("Refresh: 0");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

//Change Password
if (isset($_POST['change_password'])) {
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
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email  Cannot Be Empty";
    }


    if (!$error) {
        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $err = "Passwords Do Not Match";
        } else {
            $view = $_GET['view'];
            $new_password  = sha1(md5($_POST['new_password']));
            $query = "UPDATE ezanaLMS_Students SET  password =? WHERE id =?";
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

/* Update Personal Info */
if (isset($_POST['update_personal_info'])) {
    $error = 0;
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $admno = $_POST['admno'];
    $idno = $_POST['idno'];
    $adr = $_POST['adr'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $updated_at = date('d M Y');

    if (!$error) {
        $query = "UPDATE ezanaLMS_Students SET name =?, email =?, phone =?, admno =?, idno =?, adr =?, dob =?, gender =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssss', $name, $email, $phone, $admno, $idno, $adr, $dob, $gender, $updated_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Student Add " && header("refresh:1; url=student_profile.php?view=$id");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Academic Details */
if (isset($_POST['update_academic_details'])) {
    $error = 0;
    $day_enrolled = $_POST['day_enrolled'];
    $school = $_POST['school'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $current_year = $_POST['current_year'];
    $id = $_POST['id'];

    if (!$error) {
        $query = "UPDATE ezanaLMS_Students SET day_enrolled =?, school =?, course =?, department =?, current_year =?,  WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $day_enrolled, $school, $course, $department, $current_year, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated " && header("refresh:1; url=student_profile.php?view=$id");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
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
        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($std = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($std->profile_pic == '') {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/dist/img/no-profile.png' alt='User profile picture'>";
            } else {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/uploads/UserImages/students/$std->profile_pic' alt='User profile picture'>";
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
                                <a href="non_teaching_staff.php" class=" nav-link">
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
                                <a href="students.php" class="active nav-link">
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
                                <h1><?php echo $std->name; ?> Profile</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="students.php">Students</a></li>
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
                                                        <h5 class="modal-title " id="exampleModalLabel">Update <?php echo $std->name; ?> Profile Picture</h5>
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


                                        <h3 class="profile-username text-center"></h3>

                                        <p class="text-muted text-center"></p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Student Name: </b> <a class="float-right"><?php echo $std->name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student Gender: </b> <a class="float-right"><?php echo $std->gender; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Admission Number: </b> <a class="float-right"><?php echo $std->admno; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student ID: </b> <a class="float-right"><?php echo $std->idno; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student Email : </b> <a class="float-right" href="mailto:<?php echo $std->email; ?>"><?php echo $std->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student Phone No: </b> <a class="float-right"><?php echo $std->phone; ?></a>
                                            </li>
                                            <hr>
                                            <li class="list-group-item">
                                                <b>Date Enrolled: </b> <a class="float-right"><?php echo $std->day_enrolled; ?></a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>School / Faculty: </b> <a class="float-right"><?php echo $std->school; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Department: </b> <a class="float-right"><?php echo $std->department; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Course: </b> <a class="float-right"><?php echo $std->course; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Current Year: </b> <a class="float-right"><?php echo $std->current_year; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>No Of Enrolled / Attempted Modules: </b> <a class="float-right">
                                                    <?php

                                                    $query = "SELECT COUNT(module_name)  FROM `ezanaLMS_Enrollments` WHERE student_adm = '$std->admno' ";
                                                    $stmt = $mysqli->prepare($query);
                                                    $stmt->execute();
                                                    $stmt->bind_result($enrolled_modules);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    echo $enrolled_modules;
                                                    ?>
                                                </a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>Account Status: </b> <a class="float-right"><?php echo $std->acc_status; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Current Address: </b> <a class="float-right"><?php echo $std->adr; ?></a>
                                            </li>
                                            <li class="list-group-item text-center">
                                                <a href="#update-modal" data-toggle="modal" class="badge badge-primary"><i class='fas fa-user-edit'></i> Edit Profile</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="modal fade" id="update-modal">
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

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Name</label>
                                                                <input type="text" required name="name" class="form-control" value="<?php echo $std->name; ?>">
                                                                <input type="hidden" required name="id" value="<?php echo $std->id; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Admission Number</label>
                                                                <input type="text" required name="admno" value="<?php echo $std->admno; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">ID / Passport Number</label>
                                                                <input type="text" required name="idno" value="<?php echo $std->idno; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Date Of Birth</label>
                                                                <input type="text" required name="dob" value="<?php echo $std->dob; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Gender</label>
                                                                <select type="text" required name="gender" class="form-control basic">
                                                                    <option><?php echo $std->gender; ?></option>
                                                                    <option>Male</option>
                                                                    <option>Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Email</label>
                                                                <input type="email" required value="<?php echo $std->email; ?>" name="email" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Phone Number</label>
                                                                <input type="text" required name="phone" value="<?php echo $std->phone; ?>" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Current Address</label>
                                                                <textarea required name="adr" rows="3" class="form-control"><?php echo $std->adr; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="update_personal_info" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Update Modal -->
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#enrolled_modules" data-toggle="tab">Enrolled / Attempted Modules </a></li>
                                            <li class="nav-item"><a class="nav-link" href="#school_details" data-toggle="tab">Edit Academic / School Details</a></li>
                                            <li class="nav-item"><a class="nav-link " href="#changePassword" data-toggle="tab">Password Reset</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="enrolled_modules">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Module </th>
                                                            <th>Grade / Marks Attained</th>
                                                            <th>Year</th>
                                                            <th>Sem Enrolled</th>
                                                            <th>Academic Yr</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE student_adm = '$std->admno' ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($en = $res->fetch_object()) {
                                                        ?>

                                                            <tr>
                                                                <td><?php echo $en->module_code . " " . $en->module_name; ?></td>
                                                                <td><?php echo $en->module_code; ?></td>
                                                                <td><?php echo $en->stage; ?></td>
                                                                <td><?php echo $en->semester_enrolled; ?></td>
                                                                <td><?php echo $en->academic_year_enrolled; ?></td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        } ?>
                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="tab-pane" id="school_details">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Enrolled</label>
                                                            <select class='form-control basic' id="CourseCode" onchange="getStudentCourseDetails(this.value);">
                                                                <option selected>Select Course Code </option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Courses` ";
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
                                                            <input type="text" required name="course" class="form-control" id="CourseName">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Department Name</label>
                                                            <input type="text" required name="department" class="form-control" id="DepartmentName">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Faculty / School Name</label>
                                                            <input type="text" required name="school" class="form-control" id="FacultyName">
                                                            <input type="hidden" required name="faculty_id" class="form-control" id="FacultyID">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Current Year</label>
                                                            <input type="text" value="<?php echo $std->current_year; ?>" required name="current_year" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Date Enrolled</label>
                                                            <input type="text" required value="<?php echo $std->day_enrolled; ?>" name="day_enrolled" class="form-control">
                                                        </div>

                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="update_academic_details" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
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
                                                            <input type="text" name="confirm_password" value="<?php echo $defaultPass; ?>" required class="form-control" id="inputName2">
                                                            <input type="hidden" name="email" required class="form-control" value="<?php echo $std->email; ?>">
                                                            <input type="hidden" required name="subject" value="Password Reset" class="form-control">
                                                            <input type="hidden" required name="message" value="Howdy, <?php echo $std->name . " " . $std->admno; ?>😊. <br> This is your new password: <b><?php echo $defaultPass; ?></b>. <br>  <b>PLEASE MAKE SURE YOU CHANGE IT UPOUN LOGIN.</b>" class="form-control">
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