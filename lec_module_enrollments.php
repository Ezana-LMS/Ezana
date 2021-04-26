<?php
/*
 * Created on Mon Apr 26 2021
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
lec_check_login();
require_once('configs/codeGen.php');
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_lec_nav.php');
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
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <?php require_once('public/partials/_brand.php'); ?>
                    <!-- Sidebar -->
                    <?php require_once('public/partials/_lec_sidebar.php'); ?>
                </aside>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Enrolled Students</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="lec_dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="lec_allocated_modules.php">Allocated Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?> Module Enrollments</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <!-- <div class="text-left">
                                    <nav class="navbar navbar-light bg-light col-md-12">
                                        <form class="form-inline"  method="GET">
                                        </form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Enrollment</button>
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
                                                                        <input type="text" required name="code" value="<?php echo $a . "" . $b; ?>" class="form-control">
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

                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Course Code</label>
                                                                        <input type="text" required name="course_code" value="<?php echo $course->code; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Course Name</label>
                                                                        <input type="text" readonly required value="<?php echo $mod->course_name; ?>" name="course_name" class="form-control">
                                                                    </div>
                                                                    <hr>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Module Name</label>
                                                                        <input type="text" value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Module Code</label>
                                                                        <input type="text" value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
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
                                                                            <input type="text" value="<?php echo $academic_settings->current_academic_year; ?>" required name="academic_year_enrolled" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Semester Enrolled</label>
                                                                            <input type="text" value="<?php echo $academic_settings->current_semester; ?>" required name="semester_enrolled" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Semester Start Date</label>
                                                                            <input type="text" value="<?php echo $academic_settings->start_date; ?>" required name="semester_start" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">Semester End Date</label>
                                                                            <input type="text" value="<?php echo $academic_settings->end_date; ?>" required name="semester_end" class="form-control">
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
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div> -->
                                <hr>
                                <div class="row">
                                    <!-- Module Side Menu -->
                                    <?php require_once('public/partials/_lec_modulemenu.php'); ?>
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
                                                        $cnt = 1;
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
                                                                    <a class="badge badge-success" href="lec_student_profile.php?view=<?php echo $en->student_adm; ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                        View Student Profile
                                                                    </a>
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
                        <?php require_once('public/partials/_footer.php'); ?>
                    </div>
                </div>
                <!-- ./wrapper -->
        <?php }
        }
        require_once('public/partials/_scripts.php'); ?>
</body>

</html>