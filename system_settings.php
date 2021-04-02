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
/* System Settings */
if (isset($_POST['systemSettings'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['sysname']) && !empty($_POST['sysname'])) {
        $sysname = mysqli_real_escape_string($mysqli, trim($_POST['sysname']));
    } else {
        $error = 1;
        $err = "System Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $version = $_POST['version'];
        $sysname = $_POST['sysname'];
        $logo = $_FILES['logo']['name'];
        move_uploaded_file($_FILES["logo"]["tmp_name"], "public/dist/img/" . $_FILES["logo"]["name"]);

        $query = "UPDATE ezanaLMS_Settings SET sysname =?, logo =?, version=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss',  $sysname,  $logo, $version, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Current Academic Year And Academic Semester */
if (isset($_POST['CurrentAcademicTerm'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['current_academic_year']) && !empty($_POST['current_academic_year'])) {
        $current_academic_year = mysqli_real_escape_string($mysqli, trim($_POST['current_academic_year']));
    } else {
        $error = 1;
        $err = "Current Academic Year Cannot Be Empty";
    }
    if (isset($_POST['current_semester']) && !empty($_POST['current_semester'])) {
        $current_semester = mysqli_real_escape_string($mysqli, trim($_POST['current_semester']));
    } else {
        $error = 1;
        $err = "Current Academic Year Cannot Be Empty";
    }
    if (isset($_POST['end_date']) && !empty($_POST['end_date'])) {
        $end_date = mysqli_real_escape_string($mysqli, trim($_POST['end_date']));
    } else {
        $error = 1;
        $err = "Semester Closing Date   Cannot Be Empty";
    }
    if (isset($_POST['start_date']) && !empty($_POST['start_date'])) {
        $start_date = mysqli_real_escape_string($mysqli, trim($_POST['start_date']));
    } else {
        $error = 1;
        $err = "Semester Start Date  Cannot Be Empty";
    }

    if (!$error) {
        $id = $_POST['id'];
        $current_academic_year = $_POST['current_academic_year'];
        $current_semester = $_POST['current_semester'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $query = "UPDATE ezanaLMS_AcademicSettings SET current_academic_year =?, current_semester =?, start_date =?, end_date = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss',  $current_academic_year,  $current_semester, $start_date, $end_date, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Faculty */
if (isset($_GET['delete_faculty'])) {
    $delete = $_GET['delete_faculty'];
    $adn = "DELETE FROM ezanaLMS_Faculties WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Departments */
if (isset($_GET['delete_department'])) {
    $delete = $_GET['delete_department'];
    $adn = "DELETE FROM ezanaLMS_Departments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Department Details Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Courses */
if (isset($_GET['delete_course'])) {
    $delete = $_GET['delete_course'];
    $adn = "DELETE FROM ezanaLMS_Courses WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Modules */
if (isset($_GET['delete_module'])) {
    $delete = $_GET['delete_module'];
    $adn = "DELETE FROM ezanaLMS_Modules WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Student */
if (isset($_GET['delete_student'])) {
    $delete = $_GET['delete_student'];
    $adn = "DELETE FROM ezanaLMS_Students WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Lecturers */
if (isset($_GET['delete_lecturer'])) {
    $delete = $_GET['delete_lecturer'];
    $adn = "DELETE FROM ezanaLMS_Lecturers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete On Non Teaching Staff */
if (isset($_GET['delete_staff'])) {
    $delete = $_GET['delete_staff'];
    $adn = "DELETE FROM ezanaLMS_Admins WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_settings.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('configs/codeGen.php');
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
                            <a href="#" class="active nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="reports.php" class=" nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="system_settings.php" class="active nav-link">
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
                            <h1 class="m-0">System Settings</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports.php">System</a></li>
                                <li class="breadcrumb-item active">System Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="pill" href="#customization" role="tab">Customization</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#academic_settings" role="tab">Academic Settings</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="pill" href="#back_up_utillity" role="tab">Data Backup Utility</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link text-danger" data-toggle="pill" href="#delete_functionalities" role="tab">Delete Functionalities</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade show active" id="customization" role="tabpanel">
                                                <br>
                                                <?php
                                                /* Persisit System Settings On Brand */
                                                $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($sys = $res->fetch_object()) {
                                                ?>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="">System Name</label>
                                                                    <input type="text" required name="sysname" value="<?php echo $sys->sysname; ?>" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">System Version</label>
                                                                    <input type="text" required name="version" value="<?php echo $sys->version; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">System Logo</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="logo" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="systemSettings" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                <?php
                                                } ?>
                                            </div>

                                            <div class="tab-pane fade show " id="academic_settings" role="tabpanel">
                                                <br>
                                                <?php
                                                /* Persisit Academic Settings */
                                                $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($academic_settings = $res->fetch_object()) {
                                                ?>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Academic Year</label>
                                                                    <input type="text" required value="<?php echo $academic_settings->current_academic_year; ?> " name="current_academic_year" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $academic_settings->id ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Semester</label>
                                                                    <input type="text" required value="<?php echo $academic_settings->current_semester; ?>" name="current_semester" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Semester Opening Date</label>
                                                                    <input type="date" required value="<?php echo $academic_settings->start_date; ?>" name="start_date" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Current Semester Closing Dates</label>
                                                                    <input type="date" required value="<?php echo $academic_settings->end_date; ?>" name="end_date" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="CurrentAcademicTerm" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                <?php
                                                } ?>
                                            </div>

                                            <div class="tab-pane fade show " id="delete_functionalities" role="tabpanel">
                                                <br>
                                                <p class="text-danger">Select Any Module To Access Delete Functionalities</p>
                                                <div class="col-md-12">
                                                    <div class="card collapsed-card">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Faculties</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="faculties" class=" table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code Number</th>
                                                                        <th>Name</th>
                                                                        <th>Head</th>
                                                                        <th>Email</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($faculty = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $faculty->code; ?></td>
                                                                            <td><?php echo $faculty->name; ?></td>
                                                                            <td><?php echo $faculty->head; ?></td>
                                                                            <td><?php echo $faculty->email; ?></td>
                                                                            <td>
                                                                                <!-- End Update Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $faculty->id; ?>"> <i class="fas fa-trash"></i> Delete</a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $faculty->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $faculty->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_faculty=<?php echo $faculty->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Departments -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Departments</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="departments" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>HOD</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` ORDER BY `name` ASC   ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($dep = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $dep->code; ?></td>
                                                                            <td><?php echo $dep->name; ?></td>
                                                                            <td><?php echo $dep->hod; ?></td>
                                                                            <td>

                                                                                <a class="badge badge-danger" href="#delete-<?php echo $dep->id; ?>" data-toggle="modal">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $dep->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $dep->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_department=<?php echo $dep->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Courses -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Courses</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="courses" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>Department</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses`";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($courses = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $courses->code; ?></td>
                                                                            <td><?php echo $courses->name; ?></td>
                                                                            <td><?php echo $courses->department_name; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" href="#delete-<?php echo $courses->id; ?>" data-toggle="modal">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $courses->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $courses->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_course=<?php echo $courses->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Modules -->
                                                    <div class="card collapsed-card">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Modules</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="modules" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Code</th>
                                                                        <th>Course</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Modules`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($mod = $res->fetch_object()) {
                                                                    ?>

                                                                        <tr>
                                                                            <td><?php echo $mod->name; ?></td>
                                                                            <td><?php echo $mod->code; ?></td>
                                                                            <td><?php echo $mod->course_name; ?></td>
                                                                            <td>
                                                                                <!-- End Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $mod->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $mod->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $mod->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_module=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Non Teaching Staff -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Non Teaching Staffs</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="example1" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
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
                                                                            <td><?php echo $admin->phone; ?></td>
                                                                            <td><?php echo $admin->rank; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $admin->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->

                                                                                <div class="modal fade" id="delete-<?php echo $admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $admin->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_staff=<?php echo $admin->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Lecturers -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Lecturers</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="lecturers" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Number</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>ID/Passport </th>
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
                                                                            <td><?php echo $lec->email; ?></td>
                                                                            <td><?php echo $lec->phone; ?></td>
                                                                            <td><?php echo $lec->idno; ?></td>
                                                                            <td>

                                                                                <!-- End Lec Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $lec->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $lec->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_lecturer=<?php echo $lec->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>

                                                    <!-- Students -->
                                                    <div class="card collapsed-card ">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Students</h3>
                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <table id="students" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Adm No</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>ID/Passport</th>
                                                                        <th>Gender</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Students` ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($std = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $std->admno; ?></td>
                                                                            <td><?php echo $std->name; ?></td>
                                                                            <td><?php echo $std->email; ?></td>
                                                                            <td><?php echo $std->phone; ?></td>
                                                                            <td><?php echo $std->idno; ?></td>
                                                                            <td><?php echo $std->gender; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $std->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $std->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $std->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_settings.php?delete_student=<?php echo $std->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- End Delete Confirmation Modal -->
                                                                            </td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade show " id="back_up_utillity" role="tabpanel">
                                                <br>
                                                <div class="text-center">
                                                    <a href="system_database_dump.php" target="_blank" class="btn btn-primary">Backup </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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