<?php
/*
 * Created on Thu Jul 01 2021
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
require_once('../config/std_checklogin.php');
require_once('../config/codeGen.php');
std_checklogin();
$time = date("d-M-Y") . "-" . time();


/* Update Profile Picture */
if (isset($_POST['update_picture'])) {
    $id = $_SESSION['id'];
    $profile_pic = $time . $_FILES['profile_pic']['name'];
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../Data/User_Profiles/students/" . $time . $_FILES["profile_pic"]["name"]);

    $query = "UPDATE ezanaLMS_Students  SET  profile_pic =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $profile_pic, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Profile Picture Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Change Password */
if (isset($_POST['change_password'])) {

    $error = 0;
    if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
        $old_password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['old_password']))));
    } else {
        $error = 1;
        $err = "Old Password Cannot Be Empty";
    }
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

    if (!$error) {
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM  ezanaLMS_Students  WHERE id = '$id'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($old_password != $row['password']) {
                $err =  "Please Enter Correct Old Password";
            } elseif ($new_password != $confirm_password) {
                $err = "Confirmation Password Does Not Match";
            } else {
                $id = $_SESSION['id'];
                $new_password  = sha1(md5($_POST['new_password']));
                $query = "UPDATE ezanaLMS_Students SET  password =? WHERE id =?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('ss', $new_password, $id);
                $stmt->execute();
                if ($stmt) {
                    $success = "Password Updated";
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
        }
    }
}

/* Update Personal Info */
if (isset($_POST['update_personal_info'])) {
    $error = 0;
    $id = $_SESSION['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $idno = $_POST['idno'];
    $adr = $_POST['adr'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $updated_at = date('d M Y');

    if (!$error) {
        $query = "UPDATE ezanaLMS_Students SET name =?, phone =?, idno =?, adr =?, dob =?, gender =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $name, $phone, $idno, $adr, $dob, $gender, $updated_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Profile Details Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
        require_once('partials/aside.php');
        $id = $_SESSION['id'];
        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($std = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($std->profile_pic == '') {
                $dpic = "<img height='150' width = '150' class='img-fluid' src='../assets/img/no-profile.png' alt='User profile picture'>";
            } else {
                $dpic = "<img class='img-fluid img-square' height='150' width = '150' src='../Data/User_Profiles/students/$std->profile_pic' alt='User profile picture'>";
            }
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="text-bold"><?php echo $std->name; ?> Profile</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
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
                                                                <div class="col-sm-12">
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input type="file" required name="profile_pic" class="custom-file-input" id="exampleInputFile">
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
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-8">
                                <div class="card card-primary card-outline">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#update_profile" data-toggle="tab">Update Profile </a></li>
                                            <li class="nav-item"><a class="nav-link " href="#changePassword" data-toggle="tab">Password Reset</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="update_profile">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Name</label>
                                                            <input type="text" required name="name" class="form-control" value="<?php echo $std->name; ?>">
                                                            <input type="hidden" required name="id" value="<?php echo $std->id; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Admission Number</label>
                                                            <input type="text" readonly required name="admno" value="<?php echo $std->admno; ?>" class="form-control">
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
                                                            <select type="text" style="width: 100%;" required name="gender" class="form-control basic">
                                                                <option>Male</option>
                                                                <option>Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Email</label>
                                                            <input type="email" readonly required value="<?php echo $std->email; ?>" name="email" class="form-control">
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
                                            <div class="tab-pane" id="changePassword">
                                                <form method='post' class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="inputName" class="col-sm-2 col-form-label">Old Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" name="old_password" required class="form-control" id="inputName">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 col-form-label">New Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" name="new_password" required class="form-control" id="inputEmail">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputName2" class="col-sm-2 col-form-label">Confirm New Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" name="confirm_password" required class="form-control" id="inputName2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
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