<?php
/*
 * Created on Fri Jun 25 2021
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
admin_checklogin();
require_once('../config/codeGen.php');

/* Add Student Grades */
if (isset($_POST['add_grade'])) {
    $id = $_POST['id'];
    $module_code = $_POST['module_code'];
    $module_name = $_POST['module_name'];
    $regno = $_POST['regno'];
    $name = $_POST['name'];
    $marks = $_POST['marks'];
    $assignment_name = $_POST['assignment_name'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    $course_id = $_POST['course_id'];

    $query = "INSERT INTO ezanaLMS_StudentModuleGrades (course_id, assignment_name, semester, academic_year, id, module_code, module_name, regno, name, marks) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssssss', $course_id, $assignment_name, $semester, $academic_year, $id, $module_code, $module_name, $regno, $name, $marks);
    $stmt->execute();
    if ($stmt) {
        $success = "$name Grade / Marks Submitted For $assignment_name";
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
    $assignment_name = $_POST['assignment_name'];


    $query = "UPDATE  ezanaLMS_StudentModuleGrades SET assignment_name=?,  academic_year = ?, semester = ?, module_code =?, module_name =?, regno =?, name =?, marks =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $academic_year, $semester, $module_code, $module_name, $regno, $name, $marks, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "$name Grade / Marks  For $assignment_name Updated";
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
        $success = "Deleted" && header("refresh:1; url=module_grades?view=$view");
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
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <?php require_once('partials/aside.php'); ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="modules">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12 text-center">
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Student Grades</h1>
                                <br>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-export">Export Marks Entries</button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Enter Marks | Grade</button>
                            </div>
                            <!-- Add Marks / Module Grades -->
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
                                                    <input type="hidden" name="course_id" value="<?php echo $mod->course_id; ?>" class="form-control">
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
                                                            <input type="text" readonly id="StudentName" required name="name" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Assignment Name</label>
                                                            <select class='form-control basic' name="assignment_name">
                                                                <option selected>Student Attempted Assignment</option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssignments` WHERE module_code  = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($ass = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $ass->title; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
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
                                                                <input type="text" readonly value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Semester</label>
                                                                <input type="text" readonly value="<?php echo $academic_settings->current_semester; ?>" required name="semester" class="form-control">
                                                            </div>

                                                        <?php
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_grade" class="btn btn-primary">Add Marks</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Marks Module Grades -->

                            <!-- Export Module Grades  -->
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
                                                        <th>Assignment </th>
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
                                                            <td><?php echo $grade->assignment_name; ?></td>
                                                            <td><?php echo $grade->marks; ?></td>
                                                        </tr>
                                                    <?php
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Export Grades -->
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('partials/module_menu.php'); ?>
                                <!-- Module Side Menu -->

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Student Details</th>
                                                        <th>Assignment Name</th>
                                                        <th>Marks / Grade </th>
                                                        <th>Academic Yr</th>
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
                                                            <td><?php echo $grade->assignment_name; ?></td>
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
                                                                                <h4 class="modal-title">Provide Administrator Password To Upate Marks/Grade </h4>
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
                                                                                        <input type="hidden" value="<?php echo $grade->regno; ?>" required name="regno" class="form-control">
                                                                                        <input type="hidden" value="<?php echo $grade->name; ?>" required name="name" class="form-control">
                                                                                        <input type="hidden" required value="<?php echo $grade->academic_year; ?>" name="academic_year" class="form-control">
                                                                                        <input type="hidden" required value="<?php echo $grade->semester; ?>" name="semester" class="form-control">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Attained Marks | Grade</label>
                                                                                                <input type="text" required value="<?php echo $grade->marks; ?>" name="marks" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label class="text-danger">Administrator Password</label>
                                                                                                <input type="password" required name="password" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <button type="submit" name="update_grade" class="btn btn-primary">Update</button>
                                                                                    </div>
                                                                                </form>
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
                                                                                <a href="module_grades?delete=<?php echo $grade->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                <?php  }
            require_once('partials/footer.php');
                ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('partials/scripts.php'); ?>
</body>

</html>