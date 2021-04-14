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

/* Update Profile */
if (isset($_POST['update_non_teaching_staff'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $id = mysqli_real_escape_string($mysqli, trim($_SESSION['id']));
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
        $school  = $_POST['school'];

        $query = "UPDATE ezanaLMS_Admins SET name =?, email =?,  rank =?, phone =?, adr =?, gender = ?, employee_id =?, date_employed =?, school =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssss', $name, $email, $rank, $phone, $adr, $gender, $employee_id, $date_employed, $school, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Profile Updated" && header("refresh:1; url=profile.php");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

//Change Password
if (isset($_POST['change_password'])) {

    //Change Password
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
        $sql = "SELECT * FROM  ezanaLMS_Admins  WHERE id = '$id'";
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
                $query = "UPDATE ezanaLMS_Admins SET  password =? WHERE id =?";
                $stmt = $mysqli->prepare($query);
                $rc = $stmt->bind_param('ss', $new_password, $id);
                $stmt->execute();
                if ($stmt) {
                    $success = "Password Changed";
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
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
        $id  = $_SESSION['id'];
        $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
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
                                <a href="dashboard.php" class="active nav-link">
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
                                    <li class="breadcrumb-item active">User Profile</li>
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

                                        <h3 class="profile-username text-center"><?php echo $admin->name; ?></h3>

                                        <p class="text-muted text-center"><?php echo $admin->rank; ?></p>

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
                                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Profile Settings</a></li>
                                            <li class="nav-item"><a class="nav-link " href="#changePassword" data-toggle="tab">Change Password</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="settings">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Name</label>
                                                            <input type="text" required value="<?php echo $admin->name; ?>" name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $admin->id; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Work Email</label>
                                                            <input type="text" value="<?php echo $admin->email; ?>" required name="email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Phone Number</label>
                                                            <input type="text" value="<?php echo $admin->phone; ?>" required name="phone" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="">School / Faculty</label>
                                                            <input type="text" value="<?php echo $admin->school; ?>" required name="school" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="">Rank</label>
                                                            <select class="form-control basic" name="rank">
                                                                <option><?php echo $admin->rank; ?></option>
                                                                <option>System Administrator</option>
                                                                <option>Education Administrator</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Gender</label>
                                                            <select class="form-control basic" name="gender">
                                                                <option><?php echo $admin->gender; ?></option>
                                                                <option>Male</option>
                                                                <option>Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Employee ID</label>
                                                            <input type="text" value="<?php echo $admin->employee_id; ?>" required name="employee_id" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Date Employed</label>
                                                            <input type="date" placeholder="DD-MM-YYYY" value="<?php echo $admin->date_employed; ?>" required name="date_employed" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Address</label>
                                                            <textarea required name="adr" rows="2" class="form-control"><?php echo $admin->adr; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="update_non_teaching_staff" class="btn btn-primary">Submit</button>
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