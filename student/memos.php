<?php
/*
 * Created on Thu Jul 01 2021
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
require_once('../config/std_checklogin.php');
std_checklogin();
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php');
        $id = $_SESSION['id'];
        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($std = $res->fetch_object()) {
        ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-bold">Memos</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item active">Memos</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Department Details</th>
                                                <th>Memo Title</th>
                                                <th>Date Posted & By</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos`  WHERE faculty_id  = '$std->faculty_id' AND target_audience = 'Students'   ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($memo = $res->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $memo->department_name; ?></td>
                                                    <td>
                                                        <?php echo $memo->memo_title;
                                                        if ($memo->update_status == '') {
                                                            /* Nothing */
                                                        } else {
                                                            echo " <span class='badge badge-success'> $memo->update_status </span>";
                                                        } ?>
                                                    </td>
                                                    <td><?php echo date('d-m-Y', strtotime($memo->created_at)) . "<br>" . $memo->created_by; ?></td>
                                                    <td>
                                                        <a class="badge badge-success" data-toggle="modal" href="#view-<?php echo $memo->id; ?>">
                                                            <i class="fas fa-eye"></i>
                                                            View
                                                        </a>
                                                        <!-- View Deptmental Memo Modal -->
                                                        <div class="modal fade" id="view-<?php echo $memo->id; ?>">
                                                            <div class="modal-dialog  modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title"><?php echo $memo->memo_title; ?> </h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="text-right">
                                                                            <span class='text-success'>
                                                                                Date: <?php echo date('d M Y', strtotime($memo->created_at)); ?><br>
                                                                                <?php echo $memo->type; ?>
                                                                            </span>
                                                                        </div>
                                                                        <?php echo $memo->departmental_memo; ?>

                                                                        <hr>
                                                                        <div class="text-center">
                                                                            <?php

                                                                            if ($memo->attachments != '') {
                                                                                echo
                                                                                "<a href='../Data/Memos/$memo->attachments' target='_blank' class='btn btn-outline-success'> View  $memo->type </a>";
                                                                            } else {
                                                                                echo
                                                                                "<a  class='btn btn-outline-danger'><i class='fas fa-times'></i> $memo->type Attachment Not Available </a>";
                                                                            } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
        <?php
        }
        require_once('partials/scripts.php'); ?>
</body>

</html>