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
/* Add Lects */
if (isset($_POST['add_lec'])) {
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
    if (isset($_POST['faculty_id']) && !empty($_POST['faculty_id'])) {
        $faculty_id = mysqli_real_escape_string($mysqli, trim($_POST['faculty_id']));
    } else {
        $error = 1;
        $err = "Faculty Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Lecturers WHERE  email='$email' || phone ='$phone' || idno = '$idno' || number ='$number' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($email == $row['email']) {
                $err =  "Account With This Email Already Exists";
            } elseif ($phone == $row['phone']) {
                $err = "Account With That Phone Number Exists";
            } elseif ($idno == $row['idno']) {
                $err = "National ID Number  / Passport Number Already Exists";
            } else {
                $err = "Lecturer Number Already Exists";
            }
        } else {
            $faculty_id = $_POST['faculty_id'];
            $faculty_name = $_POST['faculty_name'];
            $gender = $_POST['gender'];
            $work_email = $_POST['work_email'];
            $employee_id = $_POST['employee_id'];
            $date_employed = $_POST['date_employed'];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $number = $_POST['number'];
            $idno  = $_POST['idno'];
            $adr = $_POST['adr'];
            $created_at = date('d M Y');
            $password = sha1(md5($_POST['password']));
            $profile_pic = $_FILES['profile_pic']['name'];
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "public/uploads/UserImages/lecturers/" . $_FILES["profile_pic"]["name"]);

            $query = "INSERT INTO ezanaLMS_Lecturers (id, faculty_id, gender, faculty_name, work_email, employee_id, date_employed, name, email, phone, idno, adr, profile_pic, created_at, password, number) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssssss', $id, $faculty_id, $gender, $faculty_name, $work_email, $employee_id, $date_employed, $name, $email, $phone, $idno, $adr, $profile_pic, $created_at, $password, $number);
            $stmt->execute();
            if ($stmt) {
                $success = "Lecturer Added" && header("refresh:1; url=lecturers.php");
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Lec */
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
            $success = "Lecturer Updated" && header("refresh:1; url=lecturers.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* On Leave */
if (isset($_GET['leave'])) {
    $leave = $_GET['leave'];
    $adn = "UPDATE  ezanaLMS_Lecturers SET status = 'On Leave' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $leave);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "On Leave" && header("refresh:1; url=lecturers.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* On Work */
if (isset($_GET['onwork'])) {
    $onwork = $_GET['onwork'];
    $adn = "UPDATE  ezanaLMS_Lecturers SET status = 'On Work' WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $onwork);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Lecturer Is On Work" && header("refresh:1; url=lecturers.php");
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
                            <a href="non_teaching_staff.php" class="nav-link">
                                <i class="nav-icon fas fa-user-secret"></i>
                                <p>
                                    Non Teaching Staff
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="lecturers.php" class="active nav-link">
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
                            <h1 class="m-0 text-dark">Lecturers</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Lecturers</li>
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
                                <a class="btn btn-primary" href="lecturers_bulk_import.php">Bulk Import Lecturers</a>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Lecturer</button>
                                <a class="btn btn-primary" href="lecturers_advance_search.php">Advance Search</a>
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

                                                        <div class="form-group col-md-3">
                                                            <label for="">Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">Gender</label>
                                                            <select class='form-control basic' name="gender">
                                                                <option selected>Male</option>
                                                                <option>Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">Number</label>
                                                            <input type="text" required name="number" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="">ID / Passport Number</label>
                                                            <input type="text" required name="idno" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="">Personal Email</label>
                                                            <input type="email" required name="email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Work Email</label>
                                                            <input type="email" required name="work_email" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Phone Number</label>
                                                            <input type="text" required name="phone" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="form-group col-md-6">
                                                            <label for="">Default Password</label>
                                                            <input type="text" value="Lecturer" required name="password" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Employee ID</label>
                                                            <input type="text" required name="employee_id" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Date Employed</label>
                                                            <input type="date" required name="date_employed" placeholder="DD - MM - YYYY" class="form-control">
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
                                                        <div class="form-group col-md-4">
                                                            <label for="">Faculty Code</label>
                                                            <select class='form-control basic' id="FacultyCode" onchange="OptimizedFacultyDetails(this.value);" id='F'>
                                                                <option selected>Select Faculty Code Name </option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($row = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $row->code; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-8">
                                                            <label for="">Faculty Name</label>
                                                            <input type="text" required name="faculty_name" class="form-control" id="FacultyName">
                                                            <input type="hidden" required name="faculty_id" id="FacultyID" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Address</label>
                                                            <textarea required name="adr" rows="2" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="add_lec" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Lec Modal -->
                        </nav>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Work Email</th>
                                        <th>Phone</th>
                                        <th>ID/Passport </th>
                                        <th>Faculty/School</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers`  ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    while ($lec = $res->fetch_object()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $lec->number; ?></td>
                                            <td><?php echo $lec->name; ?></td>
                                            <td><?php echo $lec->gender; ?></td>
                                            <td><?php echo $lec->work_email; ?></td>
                                            <td><?php echo $lec->phone; ?></td>
                                            <td><?php echo $lec->idno; ?></td>
                                            <td><?php echo $lec->faculty_name; ?></td>
                                            <td>
                                                <a class="badge badge-success" href="lecturers_profile.php?view=<?php echo $lec->id; ?>">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                                <a class="badge badge-primary" data-toggle="modal" href="#update-lecturer-<?php echo $lec->id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Update
                                                </a>

                                                <?php
                                                if ($lec->status == 'On Leave') {
                                                    echo
                                                    "
                                                        <a class='badge badge-danger' data-toggle='modal' href='#onwork-$lec->id'>
                                                            <i class='fas fa-user-lock'></i>
                                                            On Leave
                                                        </a>

                                                        ";
                                                } else {
                                                    echo
                                                    "
                                                        <a class='badge badge-primary' data-toggle='modal' href='#leave-$lec->id'>
                                                            <i class='fas fa-user-check'></i>
                                                            On Work
                                                        </a>
                                                        ";
                                                }
                                                ?>
                                                <!-- Update Lec Modal -->
                                                <div class="modal fade" id="update-lecturer-<?php echo $lec->id; ?>">
                                                    <div class="modal-dialog  modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Update <?php echo $lec->name; ?> Profile Details </h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="form-group col-md-3">
                                                                                <label for="">Name</label>
                                                                                <input type="text" required name="name" value="<?php echo $lec->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                                <input type="hidden" required name="id" value="<?php echo $lec->id; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label for="">Gender</label>
                                                                                <select class='form-control basic' name="gender">
                                                                                    <option selected><?php echo $lec->gender; ?></option>
                                                                                    <option>Female</option>
                                                                                    <option>Male</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label for="">Number</label>
                                                                                <input type="text" required name="number" value="<?php echo $lec->number; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label for="">ID / Passport Number</label>
                                                                                <input type="text" required name="idno" value="<?php echo $lec->idno; ?>" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Personal Email</label>
                                                                                <input type="email" required name="email" value=<?php echo $lec->email; ?> class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Work Email</label>
                                                                                <input type="email" required name="work_email" value="<?php echo $lec->work_email; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label for="">Phone Number</label>
                                                                                <input type="text" required name="phone" value=<?php echo $lec->phone; ?> class="form-control">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Employee ID</label>
                                                                                <input type="text" required name="employee_id" value="<?php echo $lec->employee_id; ?>" class="form-control">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label for="">Date Employed</label>
                                                                                <input type="date" required name="date_employed" value="<?php echo $lec->date_employed; ?>" placeholder="DD - MM - YYYY" class="form-control">
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-md-12">
                                                                                <label for="exampleInputPassword1">Address</label>
                                                                                <textarea required name="adr" rows="2" class="form-control"><?php echo $lec->adr; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
                                                                        <button type="submit" name="update_lec" class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>                                                               
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Onleave Or WOrk -->
                                                <div class="modal fade" id="leave-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title " id="exampleModalLabel">CONFIRM LEAVE STATUS</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center text-danger">
                                                                <h4>Give <?php echo $lec->name; ?> Leave ?</h4>
                                                                <br>
                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                <a href="lecturers.php?leave=<?php echo $lec->id; ?>" class="text-center btn btn-danger"> Yes </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- End -->

                                                <!-- Onleave Or WOrk -->
                                                <div class="modal fade" id="onwork-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title " id="exampleModalLabel">CONFIRM LEAVE STATUS</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center text-danger">
                                                                <h4>Set <?php echo $lec->name; ?> To Be On Work ?</h4>
                                                                <br>
                                                                <a href="lecturers.php?onwork=<?php echo $lec->id; ?>" class="text-center btn btn-success"> Confirm </a>
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
                </section>
                <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>