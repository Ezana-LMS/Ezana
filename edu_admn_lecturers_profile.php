<?php
/*
 * Created on Fri Apr 23 2021
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
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/lecturers/" . $_FILES["profile_pic"]["name"]);

    $query = "UPDATE ezanaLMS_Lecturers  SET  profile_pic =? WHERE id =?";
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

    //Change Password
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
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Confirmation Password Cannot Be Empty";
    }

    if (!$error) {
        if ($_POST['new_password'] != $_POST['confirm_password']) {
            $err = "Passwords Do Not Match";
        } else {
            $view = $_GET['view'];
            $mailed_password = $confirm_password;
            $hashed_password  = sha1(md5($mailed_password));
            $query = "UPDATE ezanaLMS_Lecturers SET  password =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $hashed_password, $view);
            $stmt->execute();
            /* Mail New Password */
            require_once('configs/password_reset_mailer.php');
            if ($stmt && $mail->send()) {
                $success = "Password Changed" && header("Refresh: 0");
            } else {
                $err = "Please Try Again Or Try Later $mail->ErrorInfo";
            }
        }
    }
}

/* Update Profile */
if (isset($_POST['update_lec'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['number']) && !empty($_POST['number'])) {
        $number = mysqli_real_escape_string($mysqli, trim($_POST['number']));
    } else {
        $error = 1;
        $err = "Lecturer Number Cannot Be Empty";
    }
    if (isset($_POST['idno']) && !empty($_POST['idno'])) {
        $idno = mysqli_real_escape_string($mysqli, trim($_POST['idno']));
    } else {
        $error = 1;
        $err = "National ID / Passport Number Cannot Be Empty";
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    } else {
        $error = 1;
        $err = "Email Cannot Be Empty";
    }
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($mysqli, trim($_POST['phone']));
    } else {
        $error = 1;
        $err = "Phone Number Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $number = $_POST['number'];
        $idno  = $_POST['idno'];
        $adr = $_POST['adr'];
        $gender = $_POST['gender'];
        $work_email = $_POST['work_email'];
        $employee_id = $_POST['employee_id'];
        $date_employed = $_POST['date_employed'];

        $query = "UPDATE ezanaLMS_Lecturers SET  name =?,  gender = ?, work_email =?, employee_id = ?, date_employed = ?,email =?, phone =?, idno =?, adr =?, number =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssss', $name,  $gender, $work_email, $employee_id, $date_employed, $email, $phone, $idno, $adr, $number, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Profile Updated";
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
        require_once('public/partials/_edu_admn_nav.php');
        $view  = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id ='$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($lec = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($lec->profile_pic == '') {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/dist/img/no-profile.png' alt='User profile picture'>";
            } else {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/uploads/UserImages/lecturers/$lec->profile_pic' alt='User profile picture'>";
            } ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <?php require_once('public/partials/_sidebar.php'); ?>
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $lec->name; ?> Profile </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="">Lecturers</a></li>
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
                                                        <h5 class="modal-title " id="exampleModalLabel">Update <?php echo $lec->name; ?> Profile Picture</h5>
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
                                                <b>Full Name: </b> <a class="float-right"><?php echo $lec->name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Gender: </b> <a class="float-right"><?php echo $lec->gender; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Employee ID: </b> <a class="float-right"><?php echo $lec->employee_id; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Work Email: </b> <a href="mailto:<?php echo $lec->work_email; ?>" class="float-right"><?php echo $lec->work_email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Phone Number: </b> <a class="float-right"><?php echo $lec->phone; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Personal Email: </b> <a href="mailto:<?php echo $lec->email; ?>" class="float-right"><?php echo $lec->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Address</b> <a class="float-right"><?php echo $lec->adr; ?></a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>Date Employed: </b> <a class="float-right"><?php echo $lec->date_employed; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>School / Faculty</b> <a class="float-right"><?php echo $lec->faculty_name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>No Of Modules Assigned</b>
                                                <a class="float-right">
                                                    <?php
                                                    $lecid = $lec->id;
                                                    $query = "SELECT COUNT(*)  FROM `ezanaLMS_ModuleAssigns` WHERE lec_id = '$lecid' ";
                                                    $stmt = $mysqli->prepare($query);
                                                    $stmt->execute();
                                                    $stmt->bind_result($allocated);
                                                    $stmt->fetch();
                                                    $stmt->close();

                                                    echo $allocated;
                                                    ?>
                                                </a>
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
                                            <li class="nav-item"><a class="nav-link active" href="#allocated_modules" data-toggle="tab">Allocated Modules</a></li>
                                            <li class="nav-item"><a class="nav-link " href="#update_profile" data-toggle="tab">Update Profile</a></li>
                                            <li class="nav-item"><a class="nav-link " href="#changePassword" data-toggle="tab">Password Reset</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class=" tab-pane" id="update_profile">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Name</label>
                                                            <input type="text" required name="name" value="<?php echo $lec->name; ?>" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $lec->id; ?>" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="">Number</label>
                                                            <input type="text" required name="number" value="<?php echo $lec->number; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">ID / Passport Number</label>
                                                            <input type="text" required name="idno" value="<?php echo $lec->idno; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Phone Number</label>
                                                            <input type="text" required name="phone" value=<?php echo $lec->phone; ?> class="form-control">
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Personal Email</label>
                                                            <input type="email" required name="email" value=<?php echo $lec->email; ?> class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Work Email</label>
                                                            <input type="email" required name="work_email" value="<?php echo $lec->work_email; ?>" class="form-control">
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="">Gender</label>
                                                            <select class='form-control basic' name="gender">
                                                                <option selected><?php echo $lec->gender; ?></option>
                                                                <option>Female</option>
                                                                <option>Male</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Employee ID</label>
                                                            <input type="text" required name="employee_id" value="<?php echo $lec->employee_id; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Date Employed</label>
                                                            <input type="date" required name="date_employed" value="<?php echo $lec->date_employed; ?>" placeholder="DD - MM - YYYY" class="form-control">
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Address</label>
                                                            <textarea required name="adr" rows="2" class="form-control Summernote"><?php echo $lec->adr; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="update_lec" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="tab-pane active" id="allocated_modules">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Module Name</th>
                                                            <th>Module Code</th>
                                                            <th>Lecturer Name</th>
                                                            <th>Date Allocated</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns`  WHERE lec_id = '$view'   ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($assigns = $res->fetch_object()) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $assigns->module_name; ?></td>
                                                                <td><?php echo $assigns->module_code; ?></td>
                                                                <td>
                                                                    <?php
                                                                    /* Indicate This Lec Is A Guest */
                                                                    if ($assigns->status != '') {
                                                                        echo "<span class='text-success' title='Guest Lecturer'>$assigns->lec_name</span>";
                                                                    } else {
                                                                        echo $assigns->lec_name;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php echo date('d M Y', strtotime($assigns->created_at)); ?></td>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="tab-pane" id="changePassword">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">New Password</label>
                                                                <input type="text" value="<?php echo $defaultPass; ?>" required name="new_password" class="form-control">
                                                                <input type="hidden" required name="faculty_id" value="<?php echo $faculty->id; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Confirm Password</label>
                                                                <input type="text" required value="<?php echo $defaultPass; ?>" name="confirm_password" class="form-control">
                                                                <input type="hidden" required name="id" value="<?php echo $lec->id; ?>" class="form-control">
                                                                <input type="hidden" required name="email" value="<?php echo $lec->work_email; ?>" class="form-control">
                                                                <input type="hidden" required name="subject" value="Password Reset" class="form-control">
                                                                <input type="hidden" required name="message" value="Howdy, <?php echo $lec->name; ?>😊. <br> This is your new password: <b><?php echo $defaultPass; ?></b>. <br>  <b>MAKE SURE YOU UPDATE IT UPOUN LOGIN.</b>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="change_password" class="btn btn-primary">Change Password And Email New Password</button>
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
                        </div>
                    </div>
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