<?php
/*
 * Created on Wed Apr 21 2021
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
require_once('configs/codeGen.php');
check_login();
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_edu_admn_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <?php require_once('public/partials/_sidebar.php'); ?>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?> Students Reports</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="edu_admn_reports.php">Reports</a></li>
                                    <li class="breadcrumb-item active">Students</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="POST">
                                        <div class="d-flex justify-content-left">
                                            <select name="Department" class='form-control basic mr-sm-2'>
                                                <option selected>Select Department</option>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$view' ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($dep = $res->fetch_object()) {
                                                ?>
                                                    <option><?php echo $dep->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="text-right">
                                                <button name="SearchStudents" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By Department</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form method="POST">
                                        <div class="d-flex justify-content-left">
                                            <select name="Course" class='form-control basic mr-sm-2'>
                                                <option selected>Select Courses</option>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view' ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($courses = $res->fetch_object()) {
                                                ?>
                                                    <option><?php echo $courses->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="text-right">
                                                <button name="SearchStudents" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By Course</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form method="POST">
                                        <div class="d-flex justify-content-left">
                                            <select name="CurrentYear" class='form-control basic mr-sm-2'>
                                                <option selected>Select Year</option>
                                                <option>1st Year </option>
                                                <option>2nd Year </option>
                                                <option>3rd Year </option>
                                                <option>4th Year </option>
                                            </select>
                                            <div class="text-right">
                                                <button name="SearchStudents" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By Year</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="export-dt" class="table table-bordered  table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Adm No</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Gender</th>
                                                        <th>DOB</th>
                                                        <th>Email</th>
                                                        <th>Department</th>
                                                        <th>Course</th>
                                                        <th>Date Enrolled</th>
                                                        <th>Year</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (isset($_POST['SearchStudents'])) {
                                                        $School = $_POST['School'];
                                                        $Department = $_POST['Department'];
                                                        $Course = $_POST['Course'];
                                                        $CurrentYear = $_POST['CurrentYear'];

                                                        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$view' AND(department = '$Department' || course = '$Course' || current_year = '$CurrentYear') ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($std = $res->fetch_object()) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $std->admno; ?></td>
                                                                <td><?php echo $std->name; ?></td>
                                                                <td><?php echo $std->phone; ?></td>
                                                                <td><?php echo $std->gender; ?></td>
                                                                <td><?php echo $std->dob; ?></td>
                                                                <td><?php echo $std->email; ?></td>
                                                                <td><?php echo $std->department; ?></td>
                                                                <td><?php echo $std->course; ?></td>
                                                                <td><?php echo $std->day_enrolled; ?></td>
                                                                <td><?php echo $std->current_year; ?></td>

                                                            </tr>
                                                    <?php
                                                        }
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
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>