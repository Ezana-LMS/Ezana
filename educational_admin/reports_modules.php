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
edu_admn_checklogin();
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
                                <h1 class="m-0 text-bold">Modules</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="reports?view=<?php echo $view; ?>">Reports</a></li>
                                    <li class="breadcrumb-item active">Modules</li>
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
                                            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view' ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($course = $res->fetch_object()) {
                                            ?>
                                                <option><?php echo $course->name; ?></option>
                                            <?php
                                            } ?>
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
                                <table id="export-data-table" class="table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Module Name</th>
                                            <th>Module Code</th>
                                            <th>Teaching Duration</th>
                                            <th>Exam Weight Percentage</th>
                                            <th>Cat Weight Percentage</th>
                                            <th>Lectures Per Week</th>
                                            <th>Course Name</th>
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
                                                    <td><?php echo $modules['course_duration']; ?></td>
                                                    <td><?php echo $modules['exam_weight_percentage']; ?></td>
                                                    <td><?php echo $modules['cat_weight_percentage']; ?></td>
                                                    <td><?php echo $modules['lectures_number']; ?></td>
                                                    <td><?php echo $modules['course_name']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } ?>
                                </table>
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