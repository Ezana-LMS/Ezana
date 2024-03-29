<?php
/*
 * Created on Sat Jun 26 2021
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
                            <h1 class="m-0 text-bold">Student Module Enrollments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports">Reports</a></li>
                                <li class="breadcrumb-item active">Enrollments</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-center">
                            <form method="post" enctype="multipart/form-data" role="form">
                                <div class="text-center form-group col-md-12">
                                    <label for="">Academic Year</label>
                                    <select name="AcademicYear" class='form-control basic'>
                                        <option selected>Select Academic Year</option>
                                        <?php
                                        /* Persisit Academic Settings */
                                        $ret = "SELECT DISTINCT current_academic_year FROM `ezanaLMS_AcademicSettings` ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($academic_settings = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $academic_settings->current_academic_year; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="SearchByAcademicYear" class="btn btn-primary">Search Academic Years</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <table id="export-data-table" class="table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Course</th>
                                        <th>Module</th>
                                        <th>Year</th>
                                        <th>Academic Yr</th>
                                        <th>Sem Enrolled</th>
                                        <th>Sem Start</th>
                                        <th>Sem End </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['SearchByAcademicYear'])) {
                                        $AcademicYear = $_POST['AcademicYear'];
                                        $querry = $mysqli->query("SELECT  * FROM `ezanaLMS_Enrollments` WHERE academic_year_enrolled = '$AcademicYear'");
                                        while ($enrollment = $querry->fetch_array()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $enrollment['student_adm'] . "<br>" . $enrollment['student_name']; ?></td>
                                                <td><?php echo $enrollment['course_code'] . "<br>" . $enrollment['course_name'] ?></td>
                                                <td><?php echo $enrollment['module_code'] . "<br>" . $enrollment['module_name'] ?></td>
                                                <td><?php echo $enrollment['stage']; ?></td>
                                                <td><?php echo $enrollment['academic_year_enrolled']; ?></td>
                                                <td><?php echo $enrollment['semester_enrolled']; ?></td>
                                                <td><?php echo date('d M Y', strtotime($enrollment['semester_start'])); ?></td>
                                                <td><?php echo date('d M Y', strtotime($enrollment['semester_end'])); ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
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