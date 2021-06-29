<?php
/*
 * Created on Tue Jun 22 2021
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
                $success = "$name Added";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

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

        $query = "UPDATE ezanaLMS_Courses SET  code =?, name =?, details =? WHERE id =?";
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

require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php'); ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Courses</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Courses</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <nav class="navbar col-md-12">
                                <form class="form-inline" action="" method="GET">
                                </form>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Course</button>
                                    <a href="advanced_search_courses" class="btn btn-primary">Advanced Search</a>
                                </div>
                                <div class="modal fade" id="modal-default">
                                    <!-- Add Courses Modal -->
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
                                                                <label for="">Department Code</label>
                                                                <select class='form-control basic' id="DepCode" onchange="getDepartmentDetailsOnDocuments(this.value);">
                                                                    <option selected>Select Department Code</option>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments`  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($dep = $res->fetch_object()) {
                                                                    ?>
                                                                        <option><?php echo $dep->code; ?></option>
                                                                    <?php } ?>
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
                                                                <input type="text" readonly required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>

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
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Courses Modal -->
                                </div>
                            </nav>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Faculty</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Courses`";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($courses = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $courses->code; ?></td>
                                                <td><?php echo $courses->name; ?></td>
                                                <td><?php echo $courses->department_name; ?></td>
                                                <td><?php echo $courses->faculty_name; ?></td>
                                                <td>
                                                    <a class="badge badge-success" href="course?view=<?php echo $courses->id; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    <a class="badge badge-primary" data-toggle="modal" href="#edit-course-<?php echo $courses->id; ?>">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>
                                                    <!-- Update Course Modal -->
                                                    <div class="modal fade" id="edit-course-<?php echo $courses->id; ?>">
                                                        <div class="modal-dialog  modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Fill All Required Values </h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Update Course Form -->
                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Course Name</label>
                                                                                    <input type="text" required name="name" value="<?php echo $courses->name; ?>" class="form-control">
                                                                                    <input type="hidden" required name="id" value="<?php echo $courses->id; ?>" class="form-control">

                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Course Number / Code</label>
                                                                                    <input type="text" required name="code" value="<?php echo $courses->code; ?>"" class=" form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="exampleInputPassword1">Course Description</label>
                                                                                    <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $courses->details; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <button type="submit" name="update_course" class="btn btn-primary">Update</button>
                                                                        </div>
                                                                    </form>
                                                                    <!-- End Update Course Form -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Update Modal -->
                                                </td>
                                            </tr>
                                        <?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Main Footer -->
                <?php require_once('partials/footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>