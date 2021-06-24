<?php
/*
 * Created on Thu Jun 24 2021
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

/* Add Enrollment */
if (isset($_POST['add_enroll'])) {
    $error = 0;
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_name = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Cannot Be Empty";
    }
    if (isset($_POST['semester_enrolled']) && !empty($_POST['semester_enrolled'])) {
        $semester_enrolled = mysqli_real_escape_string($mysqli, trim($_POST['semester_enrolled']));
    } else {
        $error = 1;
        $err = "Semester Enrolled Number Cannot Be Empty";
    }
    if (isset($_POST['student_adm']) && !empty($_POST['student_adm'])) {
        $student_adm = mysqli_real_escape_string($mysqli, trim($_POST['student_adm']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Start / End Dates Cannot Be Empty";
    }
    if (isset($_POST['course_code']) && !empty($_POST['course_code'])) {
        $course_code = mysqli_real_escape_string($mysqli, trim($_POST['course_code']));
    } else {
        $error = 1;
        $err = "Course Code Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Enrollments WHERE  student_adm ='$student_adm ' AND module_name ='$module_name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($student_adm && $module_name) == ($row['student_adm'] && $row['module_name'])) {
                $err =  "Student $student_name Already Enrolled On $module_name ";
            }
        } else {
            $faculty = $_POST['faculty'];
            $id = $_POST['id'];
            $code = $_POST['code'];
            $created_at = date('d M Y');
            $semester_end = $_POST['semester_end'];
            $academic_year_enrolled = $_POST['academic_year_enrolled'];
            $module_code = $_POST['module_code'];
            $module_id = $_POST['module_id'];
            $stage = $_POST['stage'];
            $course_id = $_POST['course_id'];
            $query = "INSERT INTO ezanaLMS_Enrollments (course_id, id, stage, faculty_id, code, student_adm, student_name, semester_enrolled, created_at, course_code, course_name, semester_start, semester_end, academic_year_enrolled, module_name, module_code) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssssssss', $course_id, $id, $stage, $faculty, $code, $student_adm, $student_name, $semester_enrolled, $created_at, $course_code, $course_name, $semester_start, $semester_end, $academic_year_enrolled, $module_name, $module_code);
            $stmt->execute();
            if ($stmt) {
                $success = "$student_name Enrolled To $module_code";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/*  Update Enrollments*/
if (isset($_POST['update_enroll'])) {
    $error = 0;
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_name = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Cannot Be Empty";
    }
    if (isset($_POST['semester_enrolled']) && !empty($_POST['semester_enrolled'])) {
        $semester_enrolled = mysqli_real_escape_string($mysqli, trim($_POST['semester_enrolled']));
    } else {
        $error = 1;
        $err = "Semester Enrolled Number Cannot Be Empty";
    }
    if (isset($_POST['student_adm']) && !empty($_POST['student_adm'])) {
        $student_adm = mysqli_real_escape_string($mysqli, trim($_POST['student_adm']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Start / End Dates Cannot Be Empty";
    }
    if (isset($_POST['course_code']) && !empty($_POST['course_code'])) {
        $course_code = mysqli_real_escape_string($mysqli, trim($_POST['course_code']));
    } else {
        $error = 1;
        $err = "Course Code Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $code = $_POST['code'];
        $created_at = date('d M Y');
        $semester_end = $_POST['semester_end'];
        $academic_year_enrolled = $_POST['academic_year_enrolled'];
        $module_code = $_POST['module_code'];
        $module_id = $_POST['module_id'];
        $stage = $_POST['stage'];
        $query = "UPDATE ezanaLMS_Enrollments SET stage=?, code =?, student_adm =?, student_name =?, semester_enrolled =?, updated_at =?, course_code =?, course_name =?, semester_start =?, semester_end =?, academic_year_enrolled =?, module_name =?, module_code =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssssssss',  $stage, $code, $student_adm, $student_name, $semester_enrolled, $updated_at, $course_code, $course_name, $semester_start, $semester_end, $academic_year_enrolled, $module_name, $module_code, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Student Enrollment Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Enrollment */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Enrollments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=module_enrollments?view=$view");
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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id = '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($mod = $res->fetch_object()) {
            $ModuleCourseId  = $mod->course_id;
            /* Course dETAILS */
            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$ModuleCourseId' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($course = $res->fetch_object()) {
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
                                        <li class="breadcrumb-item"><a href="courses">Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12 text-center">
                                    <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Enrolled Students</h1>
                                    <br>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Enrollment</button>
                                </div>
                                <!-- Add Module Enrollment Modal -->
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
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row" style="display:none">
                                                            <div class="form-group col-md-6">
                                                                <label for=""> Name</label>
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                <input type="hidden" required name="course_id" value="<?php echo $mod->course_id; ?>" class="form-control">
                                                                <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                <input type="hidden" required name="course_id" value="<?php echo $mod->course_id; ?>" class="form-control">

                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Enroll Code</label>
                                                                <input type="text" readonly required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">Student Admission Number</label>
                                                                <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="student_adm">
                                                                    <option selected>Select Student Admission Number</option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$mod->faculty_id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($std = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $std->admno; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Student Name</label>
                                                                <input type="text" id="StudentName" readonly required name="student_name" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Student Current Year</label>
                                                                <input type="text" id="StudentYear" readonly required name="stage" class="form-control">
                                                            </div>
                                                            <div style="display:none">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Course Code</label>
                                                                    <input type="text" readonly required name="course_code" value="<?php echo $course->code; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Course Name</label>
                                                                    <input type="text" readonly required value="<?php echo $mod->course_name; ?>" name="course_name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module Name</label>
                                                                <input readonly type="text" value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module Code</label>
                                                                <input readonly type="text" value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
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
                                                                    <label for="">Academic Year Enrolled</label>
                                                                    <input readonly type="text" value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year_enrolled" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Semester Enrolled</label>
                                                                    <input readonly type="text" value="<?php echo $academic_settings->current_semester; ?>" required name="semester_enrolled" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Semester Start Date</label>
                                                                    <input readonly type="text" value="<?php echo $academic_settings->start_date; ?>" required name="semester_start" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Semester End Date</label>
                                                                    <input readonly type="text" value="<?php echo $academic_settings->end_date; ?>" required name="semester_end" class="form-control">
                                                                </div>
                                                            <?php
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="add_enroll" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
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
                                                            <th>Adm</th>
                                                            <th>Name</th>
                                                            <th>Year</th>
                                                            <th>Academic Yr</th>
                                                            <th>Sem Enrolled</th>
                                                            <th>Sem Start</th>
                                                            <th>Sem End </th>
                                                            <th>Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE module_code = '$mod->code' ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($en = $res->fetch_object()) {
                                                        ?>

                                                            <tr>
                                                                <td><?php echo $en->student_adm; ?></td>
                                                                <td><?php echo $en->student_name; ?></td>
                                                                <td><?php echo $en->stage; ?></td>
                                                                <td><?php echo $en->academic_year_enrolled; ?></td>
                                                                <td><?php echo $en->semester_enrolled; ?></td>
                                                                <td><?php echo date('d M Y', strtotime($en->semester_start)); ?></td>
                                                                <td><?php echo date('d M Y', strtotime($en->semester_end)); ?></td>
                                                                <td>
                                                                    <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $en->id; ?>">
                                                                        <i class="fas fa-trash"></i>
                                                                        Delete
                                                                    </a>
                                                                    <!-- Delete Confirmation Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $en->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <h4>Delete <?php echo $en->student_name; ?> Enrollment ?</h4>
                                                                                    <br>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="module_enrollments?delete=<?php echo $en->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                <?php
                require_once('partials/footer.php');
            }
        }
                ?>
                    </div>
                </div>
                <!-- ./wrapper -->
                <?php require_once('partials/scripts.php'); ?>
</body>

</html>