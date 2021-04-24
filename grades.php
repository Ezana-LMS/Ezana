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

/* Add Student Grades */
if (isset($_POST['add_grade'])) {
    $id = $_POST['id'];
    $module_code = $_POST['module_code'];
    $module_name = $_POST['module_name'];
    $regno = $_POST['regno'];
    $name = $_POST['name'];
    $marks = $_POST['marks'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];

    $query = "INSERT INTO ezanaLMS_StudentModuleGrades (semester, academic_year, id, module_code, module_name, regno, name, marks) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $semester, $academic_year, $id, $module_code, $module_name, $regno, $name, $marks);
    $stmt->execute();
    if ($stmt) {
        $success = "Grades Submitted" && header("refresh:1; url=grades.php?view=$module_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


/* Update Student Grades */
if (isset($_POST['update_grade'])) {
    $id = $_POST['id'];
    $module_code = $_POST['module_code'];
    $module_name = $_POST['module_name'];
    $regno = $_POST['regno'];
    $name = $_POST['name'];
    $marks = $_POST['marks'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];


    $query = "UPDATE  ezanaLMS_StudentModuleGrades SET academic_year = ?, semester = ?, module_code =?, module_name =?, regno =?, name =?, marks =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $academic_year, $semester, $module_code, $module_name, $regno, $name, $marks, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Grades Updated" && header("refresh:1; url=grades.php?view=$module_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Student Grade */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_StudentModuleGrades WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=grades.php?view=$view");
    } else {
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
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <div class="sidebar ">
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
                                <a href="courses.php" class=" nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class="active nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Student Grades</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="modules.php">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
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
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-export">Export Marks Entries</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Enter Marks | Grade</button>
                                    </div>
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Fill All Required Values </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                            <input type="hidden" name="module_name" value="<?php echo $mod->name; ?>" class="form-control">
                                                            <input type="hidden" name="module_code" value="<?php echo $mod->code; ?>" class="form-control">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Student Admission Number</label>
                                                                    <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="regno">
                                                                        <option selected>Select Student Admission Number</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE module_code  = '$mod->code'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($std = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $std->student_adm; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Student Name</label>
                                                                    <input type="text" id="StudentName" required name="name" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Attained Marks | Grade</label>
                                                                    <input type="text" required name="marks" class="form-control">
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
                                                                        <label for="">Semester</label>
                                                                        <input type="text" value="<?php echo $academic_settings->current_semester; ?>" required name="semester" class="form-control">
                                                                    </div>

                                                                <?php
                                                                } ?>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_grade" class="btn btn-primary">Add Marks</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-export">
                                        <div class="modal-dialog  modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                    <!--Export DT -->
                                                    <table id="export-dt" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Student Details</th>
                                                                <th>Module Details</th>
                                                                <th>Academic Year</th>
                                                                <th>Semester</th>
                                                                <th>Marks Attained</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_StudentModuleGrades` WHERE module_code = '$mod->code' ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($grade = $res->fetch_object()) {
                                                            ?>

                                                                <tr>
                                                                    <td><?php echo $grade->regno . " " . $grade->name; ?></td>
                                                                    <td><?php echo $grade->module_code . " " . $grade->module_name; ?></td>
                                                                    <td><?php echo $grade->academic_year; ?></td>
                                                                    <td><?php echo $grade->semester; ?></td>
                                                                    <td><?php echo $grade->marks; ?></td>
                                                                </tr>
                                                            <?php $cnt = $cnt + 1;
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('public/partials/_modulemenu.php'); ?>
                                <!-- Module Side Menu -->

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Student Details</th>
                                                        <th>Marks | Grade Attained</th>
                                                        <th>Academic Year</th>
                                                        <th>Semester</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_StudentModuleGrades` WHERE module_code = '$mod->code' ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($grade = $res->fetch_object()) {
                                                    ?>

                                                        <tr>
                                                            <td><?php echo $grade->regno . " " . $grade->name; ?></td>
                                                            <td><?php echo $grade->marks; ?></td>
                                                            <td><?php echo $grade->academic_year; ?></td>
                                                            <td><?php echo $grade->semester; ?></td>
                                                            <td>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-<?php echo $grade->id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update Entry
                                                                </a>
                                                                <!-- Update Modal -->
                                                                <div class="modal fade" id="edit-<?php echo $grade->id; ?>">
                                                                    <div class="modal-dialog  modal-xl">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Fill All Required Values </h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <!-- Form -->
                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                    <div class="card-body">
                                                                                        <input type="hidden" required name="id" value="<?php echo $grade->id; ?>" class="form-control">
                                                                                        <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                        <input type="hidden" name="module_name" value="<?php echo $mod->name; ?>" class="form-control">
                                                                                        <input type="hidden" name="module_code" value="<?php echo $mod->code; ?>" class="form-control">
                                                                                        <div class="row">

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Student Admission Number</label>
                                                                                                <input type="text" value="<?php echo $grade->regno; ?>" required name="regno" class="form-control">
                                                                                            </div>

                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Student Name</label>
                                                                                                <input type="text" value="<?php echo $grade->name; ?>" required name="name" class="form-control">
                                                                                            </div>

                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Attained Marks | Grade</label>
                                                                                                <input type="text" required value="<?php echo $grade->marks; ?>" name="marks" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Academic Year</label>
                                                                                                <input type="text" required value="<?php echo $grade->academic_year; ?>" name="academic_year" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Semester</label>
                                                                                                <input type="text" required value="<?php echo $grade->semester; ?>" name="semester" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_grade" class="btn btn-primary">Update</button>
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
                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $grade->id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete Entry
                                                                </a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $grade->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $grade->admno . " " . $grade->name; ?> Marks Entry ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="grades.php?delete=<?php echo $grade->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                <?php  }
            require_once('public/partials/_footer.php');
                ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>