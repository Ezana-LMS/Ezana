<?php
/*
 * Created on Wed Jun 23 2021
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
$time = time();

/*  Update Course*/
if (isset($_POST['update_course'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Couse  Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (!$error) {

        $id = $_POST['id'];
        $details = $_POST['details'];

        $query = "UPDATE ezanaLMS_Courses SET   code =?, name =?, details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss', $code, $name, $details, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "$name Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Course HOD */
if (isset($_POST['update_course_head'])) {

    $id = $_POST['id'];
    $email = $_POST['email'];
    $hod  = $_POST['hod'];

    $query = "UPDATE ezanaLMS_Courses SET  email =?, hod = ? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $email, $hod, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Course Head Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Course Notice / Memo */
if (isset($_POST['add_memo'])) {
    $id = $_POST['id'];
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "../Data/Memos/" . $time . $_FILES["attachments"]["name"]);
    $course_memo = $_POST['course_memo'];
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    /* Notify Me After Posting Memo / Notice */
    $notif_type = 'Course Memo';
    $status = 'Unread';
    $notification_detail = "Memo For $course_name";

    $query = "INSERT INTO ezanaLMS_CourseMemo (id, created_by, course_id, course_name, course_memo, attachments, faculty_id) VALUES(?,?,?,?,?,?,?)";
    $notif_querry = "INSERT INTO ezanaLMS_Notifications(type, status, notification_detail) VALUES(?,?,?)";

    $stmt = $mysqli->prepare($query);
    $notif_stmt = $mysqli->prepare($notif_querry);

    $rc = $stmt->bind_param('sssssss', $id, $created_by, $course_id, $course_name, $course_memo, $attachments, $faculty);
    $rc = $notif_stmt->bind_param('sss', $notif_type, $status, $notification_detail);

    $stmt->execute();
    $notif_stmt->execute();

    if ($stmt && $notif_stmt) {
        $success = "Course Memo Added";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Update Course Notices And Memo */
if (isset($_POST['update_memo'])) {
    $id = $_POST['id'];
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $attachments = $time . $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "../Data/Memos/" . $time . $_FILES["attachments"]["name"]);
    $course_memo = $_POST['course_memo'];
    $created_at = date('d M Y g:i');
    $faculty = $_POST['faculty'];
    $created_by = $_POST['created_by'];

    $query = "UPDATE ezanaLMS_CourseMemo SET created_by =?, course_id =?, course_name =?, course_memo =?, attachments =?, faculty_id =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $created_by, $course_id, $course_name, $course_memo, $attachments, $faculty, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Course Memo Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Course Memo */
if (isset($_GET['delete_memo'])) {
    $delete = $_GET['delete_memo'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_CourseMemo WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=course?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Module */
if (isset($_POST['add_module'])) {
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        $course_id = mysqli_real_escape_string($mysqli, trim($_POST['course_id']));
    } else {
        $error = 1;
        $err = "Course ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Modules WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Module With This Code Already Exists";
            } else {
                $err = "Module  Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $details = $_POST['details'];
            $course_duration = $_POST['course_duration'];
            $exam_weight_percentage = $_POST['exam_weight_percentage'];
            $cat_weight_percentage = $_POST['cat_weight_percentage'];
            $lectures_number = $_POST['lectures_number'];
            $created_at = date('d M Y');
            $faculty_id = $_POST['faculty_id'];

            $query = "INSERT INTO ezanaLMS_Modules (id, name, code, details, course_name, course_id, course_duration, exam_weight_percentage, cat_weight_percentage, lectures_number, created_at, faculty_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssss', $id, $name, $code, $details, $course_name, $course_id, $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $created_at, $faculty_id);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Added";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

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
        /* Course ID */
        $view = $_POST['view'];
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
                $success = "$lec_name Has Been Assigned To $module_code-$module_code";
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
        
        $id = $_POST['id'];
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
            $success = "$lec_name Has Been Assigned As Guest Lecturer To $module_code - $module_code";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Module Allocation */
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
        $success = "Deleted" && header("refresh:1; url=course?view=$view");
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
        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
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
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="courses">Courses</a></li>
                                    <li class="breadcrumb-item active"><?php echo $course->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <div class="col-md-12 text-center">
                                    <h1 class="m-0 text-dark"><?php echo $course->name; ?> Dashboard</h1>
                                    <br>
                                    <span class="btn btn-primary"><i class="fas fa-arrow-left"></i><a href="courses" class="text-white"> Back</a></span>

                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-course-<?php echo $course->id; ?>">Edit Course</button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-course-head">Edit Course Head</button>
                                </div>
                            </div>
                            <hr>
                            <!-- Update Course Modal -->
                            <div class="modal fade" id="update-course-<?php echo $course->id; ?>">
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
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Name</label>
                                                            <input type="text" required name="name" value="<?php echo $course->name; ?>" class="form-control" id="exampleInputEmail1">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Number / Code</label>
                                                            <input type="text" readonly required name="code" value="<?php echo $course->code; ?>"" class=" form-control">
                                                            <input type="hidden" required name="id" value="<?php echo $course->id; ?>"" class=" form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Course Description</label>
                                                            <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $course->details; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="update_course" class="btn btn-primary">Update Course</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Update Course Modal -->

                            <!-- Update Course HOD -->
                            <div class="modal fade" id="update-course-head">
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
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Head </label>
                                                            <select class='form-control basic' id="CourseHead" name="hod" onchange="getCourseHeadDetails(this.value);">
                                                                <option selected>Select Course Head</option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Lecturers` ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($course_hod = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $course_hod->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course HOD Email</label>
                                                            <input type="text" readonly required name="email" id="CourseHeadEmail" class="form-control">
                                                            <input type="hidden" required name="id" value="<?php echo $course->id; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="update_course_head" class="btn btn-primary">Update Course Head</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Update Course HOD -->

                            <!-- Course Tab Modals -->
                            <?php require_once('partials/course_tab_modals.php'); ?>
                            <!-- End Course Tab Modals -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card card-primary card-outline">
                                        <div class="card-footer p-0">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <span class="nav-link text-primary">
                                                        Code : <span class="float-right "><?php echo $course->code; ?></span>
                                                    </span>
                                                </li>
                                                <li class="nav-item">
                                                    <span class="nav-link text-primary">
                                                        Head : <span class="float-right "><?php echo $course->hod; ?></span>
                                                    </span>
                                                </li>
                                                <li class="nav-item">
                                                    <span class="nav-link text-primary">
                                                        Head Email : <a href="mailto:<?php echo $course->email; ?>"><span class="float-right "><?php echo $course->email; ?></a></span>
                                                    </span>
                                                </li>
                                                <li class="nav-item">
                                                    <span class="nav-link text-primary">
                                                        Enrolled Students :
                                                        <span class="float-right ">
                                                            <?php
                                                            /* Get Enrolled Students To This Course */
                                                            $course_code = $course->code;
                                                            $query = "SELECT COUNT(*)  FROM `ezanaLMS_Enrollments` WHERE course_code = '$course_code' ";
                                                            $stmt = $mysqli->prepare($query);
                                                            $stmt->execute();
                                                            $stmt->bind_result($studentenrollments);
                                                            $stmt->fetch();
                                                            $stmt->close();

                                                            echo $studentenrollments;
                                                            ?>
                                                        </span>
                                                    </span>
                                                </li>
                                                <li class="nav-item">
                                                    <span class="nav-link text-primary">
                                                        Modules :
                                                        <span class="float-right ">
                                                            <?php
                                                            /* Get All Modules Under Respective Course */
                                                            $query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE course_id = '$view' ";
                                                            $stmt = $mysqli->prepare($query);
                                                            $stmt->execute();
                                                            $stmt->bind_result($modulescount);
                                                            $stmt->fetch();
                                                            $stmt->close();

                                                            echo $modulescount;
                                                            ?>
                                                        </span>
                                                    </span>
                                                </li>
                                                <?php
                                                /* Load Departmental Details Of This Course */
                                                $department_id = $course->department_id;
                                                $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id= '$department_id' ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($department = $res->fetch_object()) {
                                                ?>
                                                    <li class="nav-item">
                                                        <span class="nav-link text-primary">
                                                            Department Code : <span class="float-right "><?php echo $department->code; ?></span>
                                                        </span>
                                                    </li>

                                                    <li class="nav-item">
                                                        <span class="nav-link text-primary">
                                                            Department Name : <span class="float-right "><?php echo $department->name; ?></span>
                                                        </span>
                                                    </li>
                                                <?php }
                                                $faculty_id = $course->faculty_id;
                                                $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$faculty_id' ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($faculty = $res->fetch_object()) {
                                                ?>
                                                    <li class="nav-item">
                                                        <span class="nav-link text-primary">
                                                            Faculty / School Code : <span class="float-right "><?php echo $faculty->code; ?></span>
                                                        </span>
                                                    </li>

                                                    <li class="nav-item">
                                                        <span class="nav-link text-primary">
                                                            Faculty / School Name : <span class="float-right "><?php echo $faculty->name; ?></span>
                                                        </span>
                                                    </li>
                                                <?php
                                                } ?>

                                            </ul>
                                            <hr>
                                            <div class="col-md-12">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <span class="nav-link text-center text-primary">
                                                            Course Details
                                                        </span>
                                                    </li>
                                                    <li class="nav-item">
                                                        <?php echo $course->details; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a class="nav-link active" href="#memos" data-toggle="tab">Memos & Notices</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#course_modules" data-toggle="tab">Modules</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#module_allocations" data-toggle="tab">Modules Allocations</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#timetable" data-toggle="tab">Time Table</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#student_enrollments" data-toggle="tab">Student Enrollments</a></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="active tab-pane" id="memos">
                                                    <div class="col-md-12">
                                                        <div class="text-right">
                                                            <a href="#add_memo" data-toggle="modal" class="btn btn-outline-primary">
                                                                Add Memo / Notice
                                                            </a>
                                                        </div>
                                                        <br>
                                                        <table id="example1" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Posted By</th>
                                                                    <th>Date Posted</th>
                                                                    <th>Manage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_CourseMemo` WHERE course_id = '$course->id'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($memo = $res->fetch_object()) {
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $memo->created_by; ?></td>
                                                                        <td><?php echo date('d-M-Y g:ia', strtotime($memo->created_on)); ?></td>
                                                                        <td>
                                                                            <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $memo->id; ?>">
                                                                                <i class="fas fa-eye"></i>
                                                                                View
                                                                            </a>
                                                                            <!-- View Course Memo Modal -->
                                                                            <div class="modal fade" id="view-<?php echo $memo->id; ?>">
                                                                                <div class="modal-dialog  modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header text-justified">
                                                                                            <h4 class="modal-title"><?php echo $course->name; ?> Memo </h4>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <div class="text-right">
                                                                                                <span class='text-success'>
                                                                                                    Date <?php echo date('d-M-Y', strtotime($memo->created_on)); ?>
                                                                                                    <br>
                                                                                                    <?php echo date('g:ia', strtotime($memo->created_on)); ?>
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="text-center">
                                                                                                <?php echo $memo->course_memo; ?>

                                                                                                <hr>
                                                                                                <?php

                                                                                                if ($memo->attachments != '') {
                                                                                                    echo
                                                                                                    "<a href='../Data/Memos/$memo->attachments' target='_blank' class='btn btn-outline-success'> View Memo </a>";
                                                                                                } else {
                                                                                                    echo
                                                                                                    "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $memo->type Attachment Not Available </a>";
                                                                                                } ?>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $memo->id; ?>">
                                                                                <i class="fas fa-edit"></i>
                                                                                Update
                                                                            </a>
                                                                            <!-- Update Course Memo Modal -->
                                                                            <div class="modal fade" id="update-<?php echo $memo->id; ?>">
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

                                                                                                        <div class="form-group col-md-12">
                                                                                                            <input type="hidden" required name="id" value="<?php echo $memo->id; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="course_id" value="<?php echo $memo->course_id; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="course_name" value="<?php echo $memo->course_name; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="faculty" value="<?php echo $memo->faculty_id; ?>" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Created By</label>
                                                                                                            <input type="text" name="created_by" class="form-control" value="<?php echo $memo->created_by; ?>">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Upload Memo | Notice (PDF r Docx)</label>
                                                                                                            <div class="input-group">
                                                                                                                <div class="custom-file">
                                                                                                                    <input name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input">
                                                                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-12">
                                                                                                            <label for="exampleInputPassword1">Type Memo | Notice</label>
                                                                                                            <textarea name="course_memo" rows="10" class="form-control Summernote"><?php echo $memo->course_memo; ?></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="text-right">
                                                                                                    <button type="submit" name="update_memo" class="btn btn-primary">Update</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- End Update Course Memo Modal -->
                                                                            <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $memo->id; ?>">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </a>
                                                                            <!-- Delete Confirmation -->
                                                                            <div class="modal fade" id="delete-<?php echo $memo->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body text-center text-danger">
                                                                                            <h4>Delete This Memo?</h4>
                                                                                            <br>
                                                                                            <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                            <a href="course?delete_memo=<?php echo $memo->id; ?>&view=<?php echo $memo->course_id; ?>" class="text-center btn btn-danger"> Delete </a>
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

                                                <div class="tab-pane" id="course_modules">
                                                    <div class="text-right">
                                                        <a href="#add_module" data-toggle="modal" class="btn btn-outline-primary">
                                                            Add Module
                                                        </a>
                                                    </div>
                                                    <br>
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Module Name</th>
                                                                <th>Module Code</th>
                                                                <th>Course Duration</th>
                                                                <th>CAT & Exam Weight Percentage</th>
                                                                <th>Manage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Modules`  WHERE course_id = '$course->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($mod = $res->fetch_object()) {
                                                            ?>

                                                                <tr>
                                                                    <td><?php echo $mod->name; ?></td>
                                                                    <td><?php echo $mod->code; ?></td>
                                                                    <td><?php echo $mod->course_duration; ?></td>
                                                                    <td><?php echo $mod->cat_weight_percentage . " &  " . $mod->exam_weight_percentage; ?></td>
                                                                    <td>
                                                                        <a class="badge badge-success" href="module?view=<?php echo $mod->id; ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                            View
                                                                        </a>
                                                                        <a class="badge badge-primary" data-toggle="modal" href="#edit-modal-<?php echo $mod->id; ?>">
                                                                            <i class="fas fa-edit"></i>
                                                                            Update
                                                                        </a>
                                                                        <!-- Update Module Modal -->
                                                                        <div class="modal fade" id="edit-modal-<?php echo $mod->id; ?>">
                                                                            <div class="modal-dialog  modal-xl">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Fill All Required Values </h4>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <!-- Update Module Form -->
                                                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                                                            <div class="card-body">
                                                                                                <div class="row">
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Module Name</label>
                                                                                                        <input type="text" value="<?php echo $mod->name; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                                                                        <input type="hidden" required name="id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                                        <input type="hidden" required name="view" value="<?php echo $course->id; ?>" class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Module Number / Code</label>
                                                                                                        <input type="text" required name="code" value="<?php echo $mod->code; ?>" class="form-control">
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="row">
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Teaching Duration(Hours And Minutes)</label>
                                                                                                        <input type="text" value="<?php echo $mod->course_duration; ?>" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Number Of Lectures Per Week</label>
                                                                                                        <input type="text" value="<?php echo $mod->lectures_number; ?>" required name="lectures_number" class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">CAT Exam Weight Percentage</label>
                                                                                                        <input type="text" value="<?php echo $mod->cat_weight_percentage; ?>" required name="cat_weight_percentage" class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">End Exam Weight Percentage</label>
                                                                                                        <input type="text" value="<?php echo $mod->exam_weight_percentage; ?>" required name="exam_weight_percentage" class="form-control">
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
                                                                        <!-- End Modal -->
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="tab-pane" id="module_allocations">
                                                    <div class="text-right">
                                                        <a href="#add_module_allocation" data-toggle="modal" class="btn btn-outline-primary">
                                                            Add Module Allocation
                                                        </a>
                                                        <a href="#add_guest_lec_module_allocation" data-toggle="modal" class="btn btn-outline-primary">
                                                            Add Guest Lecturer Allocation
                                                        </a>
                                                    </div>
                                                    <br>
                                                                                                   
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                <?php
                require_once('partials/footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('partials/scripts.php'); ?>
</body>

</html>