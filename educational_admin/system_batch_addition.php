<?php
/*
 * Created on Tue Jun 29 2021
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
require_once('../config/edu_admn_checklogin.php');
require_once('../config/codeGen.php');
edu_admn_checklogin();


/* Add Departments */
if (isset($_POST['add_dept'])) {
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Department Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Department Name Cannot Be Empty";
    }
    if (!$error) {
        $sql = "SELECT * FROM  ezanaLMS_Departments WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Department With This Code Already Exists";
            } else {
                $err = "Department Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $faculty = $_POST['faculty'];
            $details = $_POST['details'];
            $hod = $_POST['hod'];
            $faculty_name = $_POST['faculty_name'];
            $email = $_POST['email'];

            $query = "INSERT INTO ezanaLMS_Departments (id, code, name, faculty_id, faculty_name, details, hod, email) VALUES(?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssss', $id, $code, $name, $faculty, $faculty_name, $details, $hod, $email);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Added" && header("refresh:1; url=system_batch_addition?view=$faculty_id");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Add Course */
if (isset($_POST['add_course'])) {
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
    if (isset($_POST['department_id']) && !empty($_POST['department_id'])) {
        $department_id = mysqli_real_escape_string($mysqli, trim($_POST['department_id']));
    } else {
        $error = 1;
        $err = "Department Name / ID  Cannot Be Empty";
    }
    if (!$error) {
        $sql = "SELECT * FROM  ezanaLMS_Courses WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Course With This Code Already Exists";
            } else {
                $err = "Course Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $details = $_POST['details'];
            $department_name = $_POST['department_name'];
            $faculty_id = $_POST['faculty_id'];
            $faculty_name = $_POST['faculty_name'];
            $hod = $_POST['hod'];
            $email = $_POST['email'];
            $query = "INSERT INTO ezanaLMS_Courses (id, hod, email, code, name, details, department_id, faculty_id, faculty_name, department_name) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssss', $id, $hod, $email, $code, $name, $details, $department_id, $faculty_id, $faculty_name,  $department_name);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Added" && header("refresh:1; url=system_batch_addition?view=$faculty_id");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Add Module */
if (isset($_POST['add_module'])) {
    //Error Handling and prevention of posting double entries
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
                $success = "$name Module Added" && header("refresh:1; url=system_batch_addition?view=$faculty_id");
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
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
        ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-bold">Batch Addition Functionality</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="">System Settings</a></li>
                                    <li class="breadcrumb-item active">Batch Addition</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <h5>Add Department</h5>
                                            </div>
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row border-primary">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Department Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            <input type="hidden" required name="faculty" value="<?php echo $view; ?>" class="form-control">
                                                            <input type="hidden" required name="faculty_name" readonly class="form-control" value="<?php echo $faculty->name; ?>">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Department Number / Code</label>
                                                            <input type="text" required readonly name="code" value="<?php echo $departmentalpha . $departmentbeta; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">HOD</label>
                                                            <select class='form-control basic' style="width: 100%;" id="DepartmentHead" name="hod" onchange="getDepartmentHeadDetails(this.value);">
                                                                <option selected>Select HOD</option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id ='$view' ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($hods = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $hods->name; ?></option>
                                                                <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Email</label>
                                                            <input type="email" required readonly name="email" id="DepartmentHeadEmail" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Department Description</label>
                                                            <textarea name="details" rows="10" class="form-control Summernote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
                                                </div>
                                            </form>
                                            <hr>
                                            <div class="text-center">
                                                <h5>Add Course</h5>
                                            </div>
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Department Code</label>
                                                            <select class='form-control basic' style="width: 100%;" id="DepCode" onchange="getDepartmentDetailsOnDocuments(this.value);">
                                                                <option selected>Select Department Code</option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$view'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($dep = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $dep->code; ?></option>
                                                                <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Department Name</label>
                                                            <input type="text" id="DepName" readonly required name="department_name" class="form-control">
                                                            <input type="hidden" id="DepID" readonly required name="department_id" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="">Faculty Name</label>
                                                            <input type="text" id="DepFacName" readonly required name="faculty_name" class="form-control">
                                                            <input type="hidden" id="DepFacID" readonly required name="faculty_id" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Number / Code</label>
                                                            <input type="text" readonly required name="code" value="<?php echo $coursealpha . $coursebeta; ?>" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Head </label>
                                                            <select class='form-control basic' style="width: 100%;" id="CourseHead" name="hod" onchange="getCourseHeadDetails(this.value);">
                                                                <option selected>Select Course Head</option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$view' ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($course_hod = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $course_hod->name; ?></option>
                                                                <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Head Email</label>
                                                            <input type="text" required name="email" readonly id="CourseHeadEmail" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Course Description</label>
                                                            <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                                                </div>
                                            </form>
                                            <hr>
                                            <div class="text-center">
                                                <h5>Add Module</h5>
                                            </div>
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Module Name</label>
                                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Module Number / Code</label>
                                                            <input type="text" required readonly name="code" value="<?php echo $modulealpha . $modulebeta; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Code</label>
                                                            <select class='form-control basic' style="width: 100%;" id="ModuleCourseCode" onchange="optimizedCourseDetails(this.value);">
                                                                <option selected>Select Course Code</option>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id ='$view' ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($course = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $course->code; ?></option>
                                                                <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Course Name</label>
                                                            <input type="text" id="ModuleCourseName" readonly required name="course_name" class="form-control">
                                                            <input type="hidden" readonly id="ModuleCourseID" required name="course_id" class="form-control">
                                                            <input type="hidden" readonly id="ModuleCourseFacultyID" required name="faculty_id" class="form-control">

                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Teaching Duration(Hours & Minutes)</label>
                                                            <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Number Of Lectures Per Week</label>
                                                            <input type="number" required name="lectures_number" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Module CAT Weight Percentage</label>
                                                            <input type="number" min="1" max="100" required name="cat_weight_percentage" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Module End Exam Weight Percentage</label>
                                                            <input type="number" min="1" max="100" required name="exam_weight_percentage" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Module Description</label>
                                                            <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('partials/footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php
        }
        require_once('partials/scripts.php'); ?>
</body>

</html>