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

/* Add Non Teaching Staff */
if (isset($_POST['add_non_teaching_staff'])) {
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
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = mysqli_real_escape_string($mysqli, trim(sha1(md5($_POST['password']))));
    } else {
        $error = 1;
        $err = "Password Cannot Be Empty";
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Admins WHERE  email='$email' || phone ='$phone' || employee_id = '$employee_id' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($phone == $row['phone']) {
                $err = "Account With That Phone Number Exists";
            } elseif ($employee_id == $row['employee_id']) {
                $err = "Account With That Employee ID Exists";
            } else {
                //Silence

            }
        } else {
            $gender = $_POST['gender'];
            $employee_id = $_POST['employee_id'];
            $date_employed = $_POST['date_employed'];
            $school  = $_POST['school'];

            $profile_pic = $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/admins/" . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Admins (id, name, email, password, rank, phone, adr, profile_pic, gender, employee_id, date_employed, school) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssss', $id, $name, $email, $password, $rank, $phone, $adr, $profile_pic, $gender, $employee_id, $date_employed, $school);
            $stmt->execute();
            if ($stmt) {
                $success = "Non Teaching Staff Added" && header("refresh:1; url=non_teaching_staff.php");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Non Teaching Staff */
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

        //$profile_pic = $_FILES['profile_pic']['name'];
        //move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/admins/" . $_FILES["profile_pic"]["name"]);
        $gender = $_POST['gender'];
        $employee_id = $_POST['employee_id'];
        $date_employed = $_POST['date_employed'];
        $school  = $_POST['school'];

        $query = "UPDATE ezanaLMS_Admins SET name =?, email =?,  rank =?, phone =?, adr =?, gender = ?, employee_id =?, date_employed =?, school =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssss', $name, $email, $rank, $phone, $adr, $gender, $employee_id, $date_employed, $school, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Non Teaching Staff Updated" && header("refresh:1; url=non_teaching_staff.php");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}



/* Suspend Account */
if (isset($_GET['suspend'])) {
    $suspend = $_GET['suspend'];
    $adn = "UPDATE  ezanaLMS_Admins SET status = 'Suspended' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $suspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Suspended" && header("refresh:1; url=non_teaching_staff.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Unsuspend Account  */
if (isset($_GET['unsuspend'])) {
    $unsuspend = $_GET['unsuspend'];
    $adn = "UPDATE  ezanaLMS_Admins SET status = '' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $unsuspend);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "UnSuspended" && header("refresh:1; url=non_teaching_staff.php");
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
        <?php require_once('public/partials/_nav.php'); ?>
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

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Non Teaching Staff</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Non Teaching Staff</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light bg-light col-md-12">
                            <form class="form-inline">
                            </form>
                            <div class="text-left">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">Add Non Teaching Staff</button>
                            </div>
                            <!-- Add Lec Modal -->
                            <div class="modal fade" id="modal-default">
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
                                                        <div class="form-group col-md-6">
                                                            <label for="">Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Work Email</label>
                                                            <input type="text" required name="email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Phone Number</label>
                                                            <input type="text" required name="phone" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Employee ID</label>
                                                            <input type="text" required name="employee_id" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Rank</label>
                                                            <select class="form-control basic" name="rank">
                                                                <option>System Administrator</option>
                                                                <option>Education Administrator</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Gender</label>
                                                            <select class="form-control basic" name="gender">
                                                                <option>Male</option>
                                                                <option>Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Date Employed</label>
                                                            <input type="date" placeholder="DD-MM-YYYY" required name="date_employed" class="form-control">
                                                        </div>
                                                        
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Password</label>
                                                            <input type="text" value="<?php echo $defaultPass; ?>" required name="password" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Profile Picture</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input  name="profile_pic" type="file" class="custom-file-input">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="">School / Faculty</label>
                                                            <select class="form-control basic" name="school">
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($faculty = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $faculty->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Address</label>
                                                            <textarea required name="adr" rows="2" class="form-control Summernote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="add_non_teaching_staff" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Non Teaching Staff Modal -->
                        </nav>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Faculty / School</th>
                                        <th>Rank</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM `ezanaLMS_Admins`  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($admin = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $admin->name; ?></td>
                                            <td><?php echo $admin->email; ?></td>
                                            <td><?php echo $admin->school; ?></td>
                                            <td><?php echo $admin->rank; ?></td>
                                            <td>
                                                <a class="badge badge-success" href="administrators_profile.php?view=<?php echo $admin->id; ?>">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>

                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-<?php echo $admin->id; ?>">
                                                    <i class="fas fa-user-edit"></i>
                                                    Update
                                                </a>
                                                <?php
                                                /* Suspend  */
                                                if ($admin->status == '') {
                                                    echo
                                                    "
                                                    <a class='badge badge-danger' data-toggle='modal' href='#suspend-$admin->id'>
                                                        <i class='fas fa-user-clock'></i>
                                                        Suspend
                                                    </a>
                                                    ";
                                                } else {
                                                    echo
                                                    "
                                                    <a class='badge badge-success' data-toggle='modal' href='#unsuspend-$admin->id'>
                                                        <i class='fas fa-user-check'></i>
                                                        UnSuspend
                                                    </a>
                                                    ";
                                                }
                                                ?>
                                              

                                                <!-- Suspend Modal -->
                                                <div class="modal fade" id="suspend-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM ACCOUNT SUSPEND</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center text-danger">
                                                                <h4>Suspend <?php echo $admin->name; ?> Account ?</h4>
                                                                <br>
                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                <a href="non_teaching_staff.php?suspend=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Suspend Account </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Suspend -->
                                                
                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="edit-<?php echo $admin->id; ?>">
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

                                                                            <div class="form-group col-md-3">
                                                                                <label for="">Rank</label>
                                                                                <select class="form-control basic" name="rank">
                                                                                    <option><?php echo $admin->rank; ?></option>
                                                                                    <option>System Administrator</option>
                                                                                    <option>Education Administrator</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label for="">Gender</label>
                                                                                <select class="form-control basic" name="gender">
                                                                                    <option><?php echo $admin->gender; ?></option>
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
                                                                                <textarea required name="adr" rows="2" class="form-control Summernote"><?php echo $admin->adr; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
                                                                        <button type="submit" name="update_non_teaching_staff" class="btn btn-primary">Submit</button>
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

                                                <!-- Unsuspend -->
                                                <div class="modal fade" id="unsuspend-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title " id="exampleModalLabel">CONFIRM ACCOUNT RESTORATION</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center text-danger">
                                                                <h4>UnSuspend <?php echo $admin->name; ?> Account ?</h4>
                                                                <br>
                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                <a href="non_teaching_staff.php?unsuspend=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Unsuspend Account </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Modal -->

                                            </td>
                                        </tr>
                                    <?php $cnt = $cnt + 1;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>