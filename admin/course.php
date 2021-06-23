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
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
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
                                                <li class="nav-item"><a class="nav-link" href="#modules" data-toggle="tab">Modules</a></li>
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
                <?php
                require_once('partials/footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('partials/scripts.php'); ?>
</body>

</html>