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
                            <h1 class="m-0 text-dark">Courses </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports">Reports</a></li>
                                <li class="breadcrumb-item active">Courses</li>
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
                                                    <th>Course Head</th>
                                                    <th>Course Head Email</th>
                                                    <th>Number Of Modules</th>
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
                                                            <td><?php echo $courses->head; ?></td>
                                                            <td><?php echo $courses->email; ?></td>
                                                            <td>
                                                                <?php
                                                                $query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE course_id = '$course->id' ";
                                                                $stmt = $mysqli->prepare($query);
                                                                $stmt->execute();
                                                                $stmt->bind_result($module);
                                                                $stmt->fetch();
                                                                $stmt->close();
                                                                echo $module;
                                                                ?>
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