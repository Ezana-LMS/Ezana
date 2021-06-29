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
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php'); ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-bold">Advanced Modules Search</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="modules">Modules</a></li>
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
                                    <label for="">Course Name</label>
                                    <select name="CourseName" class='form-control basic'>
                                        <option selected>Select Course Name</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Courses` ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($course = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $course->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="SearchByCourseName" class="btn btn-primary">Search Modules</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <table id="example1" class="table table-bordered table-striped">
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
                                    if (isset($_POST['SearchByCourseName'])) {
                                        $CourseName = $_POST['CourseName'];
                                        $querry = $mysqli->query("SELECT  * FROM `ezanaLMS_Modules` WHERE course_name = '$CourseName'");
                                        while ($modules = $querry->fetch_array()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $modules['name']; ?></td>
                                                <td><?php echo $modules['code']; ?></td>
                                                <td><?php echo $modules['course_name']; ?></td>
                                                <td>
                                                    <a class="badge badge-success" href="module?view=<?php echo $modules['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    <a class="badge badge-primary" data-toggle="modal" href="#edit-modal-<?php echo $modules['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>
                                                    <!-- Update Module Modal -->
                                                    <div class="modal fade" id="edit-modal-<?php echo $modules['id']; ?>">
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
                                                                                    <input type="text" value="<?php echo $modules['name']; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                                                    <input type="hidden" required name="id" value="<?php echo $modules['id']; ?>" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Module Number / Code</label>
                                                                                    <input type="text" required name="code" value="<?php echo $modules['code']; ?>" class="form-control">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Teaching Duration (Hours & Minutes)</label>
                                                                                    <input type="text" value="<?php echo $modules['course_duration']; ?>" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">Number Of Lectures Per Week</label>
                                                                                    <input type="number" value="<?php echo $modules['lectures_number']; ?>" required name="lectures_number" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">CAT Exam Weight Percentage (%)</label>
                                                                                    <input type="number" min="1" max="100" value="<?php echo $modules['cat_weight_percentage']; ?>" required name="cat_weight_percentage" class="form-control">
                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label for="">End Exam Weight Percentage (%)</label>
                                                                                    <input type="number" min="1" max="100" value="<?php echo $modules['exam_weight_percentage']; ?>" required name="exam_weight_percentage" class="form-control">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label for="exampleInputPassword1">Module Details</label>
                                                                                    <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $modules['details']; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <button type="submit" name="update_module" class="btn btn-primary">Update Module</button>
                                                                        </div>
                                                                    </form>

                                                                    <!-- End Module Form -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                </section>
                <!-- Main Footer -->
                <?php require_once('partials/footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>