<?php
/*
 * Created on Sun Jun 27 2021
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
                            <h1 class="m-0 text-bold">Students</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports">Reports</a></li>
                                <li class="breadcrumb-item active">Students</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-center">
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <select name="School" class='col-md-12 form-control basic mr-sm-2'>
                                        <option selected>Select Faculty / School Name</option>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Faculties` ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($faculty = $res->fetch_object()) {
                                        ?>
                                            <option><?php echo $faculty->name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-center">
                                        <button name="SearchLecturers" class="btn btn-outline-success my-2 my-sm-0" type="submit">Search By School / Faculty</button>
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
                                            <th>Student Details</th>
                                            <th>School</th>
                                            <th>Department</th>
                                            <th>Course</th>
                                            <th>Year</th>
                                            <th>Date Enrolled</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_POST['SearchLecturers'])) {
                                            $School = $_POST['School'];
                                            $ret = "SELECT * FROM `ezanaLMS_Students`  WHERE school = '$School' ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($std = $res->fetch_object()) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        Reg No: <?php echo $std->admno . "<br> Name: " . $std->name . "<br>Gender: " . $std->gender . "<br>"; ?>
                                                        D.O.B: <?php echo date('d-M-Y', strtotime($std->dob)) . "<br> Email: " . $std->email . "<br>Address: " . $std->adr; ?>
                                                    </td>
                                                    <td><?php echo $std->school; ?></td>
                                                    <td><?php echo $std->department; ?></td>
                                                    <td><?php echo $std->course; ?></td>
                                                    <td><?php echo $std->current_year; ?></td>
                                                    <td><?php echo date('d-M-Y', strtotime($std->day_enrolled)); ?></td>
                                                </tr>
                                        <?php
                                            }
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