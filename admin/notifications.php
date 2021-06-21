<?php
/*
 * Created on Mon Jun 21 2021
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
                            <h1 class="m-0 text-dark">Notifications</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Notifcations</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <br>
                        <form name="notificationsForm" method="post" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- Operations -->
                                            <div class="text-right">
                                                <input type="button" class="btn btn-danger" value="Clear Notifications" name="ClearNotifications" onClick="setDeleteAction();">
                                            </div>
                                            <br>
                                            <!-- End Operations -->
                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Type</th>
                                                        <th>Nofitication Details</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    /* Load All Notifications Based On Time They Were Generated */
                                                    $result = mysqli_query($mysqli, "SELECT * FROM `ezanaLMS_Notifications`   ORDER BY `created_at` DESC ");
                                                    $i = 0;
                                                    while ($notification = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="notifications[]" value="<?php echo $notification["id"]; ?>"></td>
                                                            <td><?php echo $notification["type"]; ?></td>
                                                            <td><?php echo $notification["notification_detail"]; ?></td>
                                                            <td><?php echo date('d M Y g:ia', strtotime($notification["created_at"])); ?></td>
                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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