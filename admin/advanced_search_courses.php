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
                            <h1 class="m-0 text-bold">Advanced Courses Search</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="courses">Courses</a></li>
                                <li class="breadcrumb-item active">Advanced Search</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-center">
                            <form method="post" enctype="multipart/form-data" role="form">
                                <div class="text-center form-group col-md-12">
                                    <label for="">Department Name</label>
                                    <select name="DepartmentName" class='form-control basic'>
                                        <option selected>Select Department Name</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Departments` ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($department = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $department->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="SearchCourseByDepartmentName" class="btn btn-primary">Search Courses</button>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
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
                                                if (isset($_POST['SearchCourseByDepartmentName'])) {
                                                    $DepartmentName = $_POST['DepartmentName'];
                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE department_name = '$DepartmentName'";
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
                                                                                                <input type="text" readonly required name="code" value="<?php echo $courses->code; ?>"" class=" form-control">
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
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
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
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>