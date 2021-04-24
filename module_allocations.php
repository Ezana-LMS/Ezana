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

/* Assign Module Lec */
if (isset($_POST['assign_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['lec_id']) && !empty($_POST['lec_id'])) {
        $lec_id = mysqli_real_escape_string($mysqli, trim($_POST['lec_id']));
    } else {
        $error = 1;
        $err = "Lec ID Cannot Be Empty";
    }
    if (isset($_POST['lec_name']) && !empty($_POST['lec_name'])) {
        $lec_name = mysqli_real_escape_string($mysqli, trim($_POST['lec_name']));
    } else {
        $error = 1;
        $err = "Lec Name Cannot Be Empty";
    }
    if (!$error) {

        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_ModuleAssigns WHERE  (lec_id='$lec_id' AND module_code ='$module_code') ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($lec_id == $row['lec_id']) && ($module_code == $row['module_code'])) {
                $err =  "Module Already Assigned Lecturer";
            }
        } else {
            $id = $_POST['id'];
            $module_code = $_POST['module_code'];
            $module_name = $_POST['module_name'];
            $lec_id = $_POST['lec_id'];
            $lec_name = $_POST['lec_name'];
            $created_at = date('d M Y');
            $view = $_GET['view'];
            $faculty = $_POST['faculty'];
            $academic_year = $_POST['academic_year'];
            $semester = $_POST['semester'];

            //On Assign, Update Module Status to Assigned
            $ass_status = 1;

            $query = "INSERT INTO ezanaLMS_ModuleAssigns (id, faculty_id, course_id, academic_year, semester, module_code , module_name, lec_id, lec_name, created_at) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $modUpdate = "UPDATE ezanaLMS_Modules SET ass_status =?  WHERE code = ?";
            $stmt = $mysqli->prepare($query);
            $modstmt = $mysqli->prepare($modUpdate);
            $rc = $stmt->bind_param('ssssssssss', $id, $faculty, $view, $academic_year, $semester, $module_code, $module_name, $lec_id, $lec_name, $created_at);
            $rc = $modstmt->bind_param('is', $ass_status, $module_code);
            $stmt->execute();
            $modstmt->execute();
            if ($stmt && $modstmt) {
                $success = "Module Assignment Added" && header("refresh:1; url=module_allocations.php?view=$view");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Guest Lec Allocation */
if (isset($_POST['assign_guest_lec'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['lec_id']) && !empty($_POST['lec_id'])) {
        $lec_id = mysqli_real_escape_string($mysqli, trim($_POST['lec_id']));
    } else {
        $error = 1;
        $err = "Lec ID Cannot Be Empty";
    }
    if (isset($_POST['lec_name']) && !empty($_POST['lec_name'])) {
        $lec_name = mysqli_real_escape_string($mysqli, trim($_POST['lec_name']));
    } else {
        $error = 1;
        $err = "Lec Name Cannot Be Empty";
    }
    if (!$error) {

        //prevent Double entries
        /* $sql = "SELECT * FROM  ezanaLMS_ModuleAssigns WHERE  (lec_id='$lec_id' AND module_code ='$module_code') ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($lec_id == $row['lec_id']) && ($module_code == $row['module_code'])) {
                $err =  "Module Already Assigned Lecturer";
            }
        } else { */
        $id = $_POST['id'];
        $module_code = $_POST['module_code'];
        $module_name = $_POST['module_name'];
        $lec_id = $_POST['lec_id'];
        $lec_name = $_POST['lec_name'];
        $created_at = date('d M Y');
        $view = $_GET['view'];
        $faculty = $_POST['faculty'];
        $status = 'Guest Lecturer';

        //On Assign, Update Module Status to Assigned
        $ass_status = 1;

        $academic_year = $_POST['academic_year'];
        $semester = $_POST['semester'];


        $query = "INSERT INTO ezanaLMS_ModuleAssigns (academic_year, semester, id, faculty_id, course_id, module_code , module_name, lec_id, lec_name, created_at, status) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $modUpdate = "UPDATE ezanaLMS_Modules SET ass_status =?  WHERE code = ?";
        $stmt = $mysqli->prepare($query);
        $modstmt = $mysqli->prepare($modUpdate);
        $rc = $stmt->bind_param('sssssssssss', $academic_year, $semester, $id, $faculty, $view, $module_code, $module_name, $lec_id, $lec_name, $created_at, $status);
        $rc = $modstmt->bind_param('is', $ass_status, $module_code);
        $stmt->execute();
        $modstmt->execute();
        if ($stmt && $modstmt) {
            $success = "Module Assignment Added" && header("refresh:1; url=module_allocations.php?view=$view");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Delete Module Alloca */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $code = $_GET['code'];
    $view = $_GET['view'];
    $status = 0;
    $adn = "DELETE FROM ezanaLMS_ModuleAssigns WHERE id=?";
    $up = "UPDATE ezanaLMS_Modules SET ass_status =? WHERE code =? ";
    $stmt = $mysqli->prepare($adn);
    $upst = $mysqli->prepare($up);
    $stmt->bind_param('s', $delete);
    $upst->bind_param('is', $status, $code);
    $stmt->execute();
    $upst->execute();
    $upst->close();
    $stmt->close();
    if ($stmt && $upst) {
        $success = "Deleted" && header("refresh:1; url=module_allocations.php?view=$view");
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
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$view'";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
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
                                <a href="courses.php" class="active nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class=" nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $course->name; ?> Modules Allocation</h1>
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
                            <div class="">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="module_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Module Allocation</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#guest-lec-modal">Add Guest Lecturer </button>
                                    </div>
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-lg">
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
                                                                    <label for="">Lecturer Number</label>
                                                                    <select class='form-control basic' id="LecNumber" onchange="getLecturerDetails(this.value);" name="">
                                                                        <option selected>Select Lecturer Number</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$course->faculty_id'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($lec = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $lec->number; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Lecturer Name</label>
                                                                    <input type="hidden" id="lecID" readonly required name="lec_id" class="form-control">
                                                                    <input type="text" id="lecName" readonly required name="lec_name" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                                                </div>
                                                                <hr>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Module Name</label>
                                                                    <select class='form-control basic' id="ModuleCode" onchange="OptimizedModuleDetails(this.value);" name="module_code">
                                                                        <option selected>Select Module Code </option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Modules`  WHERE ass_status = '0' AND course_id = '$course->id'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($mod = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $mod->code; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="">Module Name</label>
                                                                    <input type="text" id="ModuleName" required name="module_name" class="form-control">
                                                                </div>
                                                                <?php
                                                                /* Persisit Academic Settings */
                                                                $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($academic_settings = $res->fetch_object()) {
                                                                ?>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Academic Year </label>
                                                                        <input type="text" value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Semester Enrolled</label>
                                                                        <input type="text" value="<?php echo $academic_settings->current_semester; ?>" required name="semester" class="form-control">
                                                                    </div>

                                                                <?php
                                                                } ?>

                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="assign_module" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="guest-lec-modal">
                                        <div class="modal-dialog  modal-lg">
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
                                                                    <label for="">Lecturer Number</label>
                                                                    <select class='form-control basic' id="lecNumber" onchange="getGuestLec(this.value);" name="">
                                                                        <option selected>Select Lecturer Number</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$course->faculty_id'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($lec = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $lec->number; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Lecturer Name</label>
                                                                    <input type="hidden" id="LecID" readonly required name="lec_id" class="form-control">
                                                                    <input type="text" id="LecName" readonly required name="lec_name" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                                                </div>
                                                                <hr>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Module Name</label>
                                                                    <select class='form-control basic' id="moduleCode" onchange="guestLecModule(this.value);" name="module_code">
                                                                        <option selected>Select Module Code </option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Modules`  WHERE  course_id = '$course->id'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($mod = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $mod->code; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="">Module Name</label>
                                                                    <input type="text" id="moduleName" required name="module_name" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="assign_guest_lec" class="btn btn-primary">Submit</button>
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
                                                        <th>Module Details</th>
                                                        <th>Academic Year</th>
                                                        <th>Semester</th>
                                                        <th>Lecture Allocated</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns`  WHERE course_id = '$course->id'   ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($assigns = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $assigns->module_code . "" . $assigns->module_name; ?></td>
                                                            <td><?php echo $assigns->academic_year; ?></td>
                                                            <td><?php echo $assigns->semester; ?></td>
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
                                                            <td>
                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $assigns->id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete
                                                                </a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $assigns->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $assigns->lec_name; ?> Module Allocation ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="module_allocations.php?delete=<?php echo $assigns->id; ?>&code=<?php echo $assigns->module_code; ?>&view=<?php echo $course->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End Delete Confirmation Modal -->
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