<?php
/*
 * Created on Wed Jun 30 2021
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
require_once('../config/lec_checklogin.php');
lec_check_login();
require_once('../config/codeGen.php');
$time =  time();


/*  Update Module*/
if (isset($_POST['update_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $details = $_POST['details'];
        $course_duration = $_POST['course_duration'];
        $exam_weight_percentage = $_POST['exam_weight_percentage'];
        $cat_weight_percentage = $_POST['cat_weight_percentage'];
        $lectures_number = $_POST['lectures_number'];
        $updated_at = date('d M Y');
        $query = "UPDATE ezanaLMS_Modules SET  name =?, code =?, details =?,  course_duration =?, exam_weight_percentage =?, cat_weight_percentage=?,  lectures_number =?, updated_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssss', $name, $code, $details,  $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $updated_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "$name Updated";
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
                                <h1 class="m-0 text-bold"><?php echo $mod->name; ?></h1>
                                <br>
                                <span class="btn btn-primary"><i class="fas fa-arrow-left"></i><a href="modules" class="text-white">Back</a></span>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-module-<?php echo $mod->id; ?>">Edit Module</button>
                            </div>

                            <!-- Add Module Notice Modal-->
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
                                            <!-- Add Module Notices Form -->
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Announcement Posted By</label>
                                                            <?php
                                                            $id  = $_SESSION['id'];
                                                            $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id ='$id' ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($lec = $res->fetch_object()) {
                                                            ?>
                                                                <input type="text" readonly required name="created_by" value="<?php echo $lec->name; ?>" class="form-control" id="exampleInputEmail1">
                                                            <?php
                                                            } ?>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="">Upload Module Memo (PDF Or Docx)</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input name="attachments" required accept=".pdf, .docx, .doc" type="file" class="custom-file-input">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Type Module Announcement</label>
                                                            <textarea name="announcements" placeholder="Type Module Announcement" rows="20" class="form-control Summernote"></textarea>
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            <input type="hidden" value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                            <input type="hidden" value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
                                                            <input type="hidden" required name="faculty_id" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                            <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class=" text-right">
                                                    <button type="submit" name="add_notice" class="btn btn-primary">Post</button>
                                                </div>
                                            </form>
                                            <!-- End Module Notice Form -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Module Notice Modal -->

                            <!-- Update Module Modal -->
                            <div class="modal fade" id="update-module-<?php echo $mod->id; ?>">
                                <div class="modal-dialog  modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Fill All Values</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="">Module Name</label>
                                                            <input type="text" readonly value="<?php echo $mod->name; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $mod->id; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Module Number / Code</label>
                                                            <input type="text" readonly required name="code" value="<?php echo $mod->code; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Course Name</label>
                                                            <input type="text" name="c_name" readonly required value="<?php echo $mod->course_name; ?>" class="form-control">
                                                            <input type="hidden" value="<?php echo $mod->course_id; ?>" required name="course_id" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Teaching Duration(Hours And Minutes)</label>
                                                            <input type="text" value="<?php echo $mod->course_duration; ?>" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Number Of Lectures Per Week</label>
                                                            <input type="number" value="<?php echo $mod->lectures_number; ?>" required name="lectures_number" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">CAT Exam Weight Percentage (%)</label>
                                                            <input type="number" min="1" max="100" value="<?php echo $mod->cat_weight_percentage; ?>" required name="cat_weight_percentage" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">End Exam Weight Percentage(%)</label>
                                                            <input type="number" min="1" max="100" value="<?php echo $mod->exam_weight_percentage; ?>" required name="exam_weight_percentage" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Module Details</label>
                                                            <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $mod->details; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="update_module" class="btn btn-primary">Update Module</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Update Module Modal -->
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('partials/module_menu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Module Name : <span class="float-right text-dark "><?php echo $mod->name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Module Code : <span class="float-right text-dark"><?php echo $mod->code; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Course Name : <span class="float-right text-dark"><?php echo $mod->course_name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Teaching Duration : <span class="float-right text-dark"><?php echo $mod->course_duration; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        No Of Lecturers Per Week : <span class="float-right text-dark"><?php echo $mod->lectures_number; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Exam Weight Percentage : <span class="float-right text-dark"><?php echo $mod->exam_weight_percentage; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Cat Weight Percentage : <span class="float-right text-dark"><?php echo $mod->cat_weight_percentage; ?></span>
                                                                    </span>
                                                                </li>
                                                                <!-- Course And Department  Module Registered To -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$mod->course_id'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($course = $res->fetch_object()) {
                                                                    $department_id = $course->department_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id = '$department_id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($department = $res->fetch_object()) {
                                                                        /* Number Of Students Registered To This Module */
                                                                        $query = "SELECT COUNT(*)  FROM `ezanaLMS_Enrollments` WHERE module_code = '$mod->code' ";
                                                                        $stmt = $mysqli->prepare($query);
                                                                        $stmt->execute();
                                                                        $stmt->bind_result($enrolled_students);
                                                                        $stmt->fetch();
                                                                        $stmt->close();
                                                                ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Department Code : <span class="float-right text-dark"><?php echo $department->code; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Department Name : <span class="float-right text-dark"><?php echo $department->name; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Code : <span class="float-right text-dark"><?php echo $course->code; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Name : <span class="float-right text-dark"><?php echo $course->name; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Enrolled Students : <span class="float-right text-dark"><?php echo $enrolled_students ?></span>
                                                                            </span>
                                                                        </li>
                                                                <?php

                                                                    }
                                                                } ?>
                                                            </ul>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <!-- Assigned Lec Details -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($ass = $res->fetch_object()) {
                                                                    /* Lec Details */
                                                                    $lec = $ass->lec_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$lec'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($lecturer = $res->fetch_object()) {

                                                                ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Name : <span class="float-right text-dark"><?php echo $lecturer->name; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Email : <span class="float-right text-dark"><?php echo $lecturer->email; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer ID / Passport No : <span class="float-right text-dark"><?php echo $lecturer->idno; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Address : <span class="float-right text-dark"><?php echo $lecturer->adr; ?></span>
                                                                            </span>
                                                                        </li>
                                                                    <?php }
                                                                }
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code' AND status= 'Guest Lecturer'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($GuestLec = $res->fetch_object()) {
                                                                    $Guestlecture = $GuestLec->lec_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$Guestlecture'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($Glecturer = $res->fetch_object()) {
                                                                    ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Name : <span class="float-right text-dark"><?php echo $Glecturer->name; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Email : <span class="float-right text-dark"><?php echo $Glecturer->email; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer ID / Passport No : <span class="float-right text-dark"><?php echo $Glecturer->idno; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Phone : <span class="float-right text-dark"><?php echo $Glecturer->phone; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Guest Lecturer Address : <span class="float-right text-dark"><?php echo $Glecturer->adr; ?></span>
                                                                            </span>
                                                                        </li>
                                                                <?php
                                                                    }
                                                                } ?>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <span class="nav-link text-center text-primary">
                                                                    Module Details
                                                                </span>
                                                            </li>
                                                            <li class="nav-item">
                                                                <?php echo $mod->details; ?>
                                                            </li>
                                                        </ul>
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
                <?php require_once('partials/footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('partials/scripts.php'); ?>
</body>

</html>