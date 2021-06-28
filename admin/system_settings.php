<?php
/*
 * Created on Mon Jun 28 2021
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
require_once('../config/codeGen.php');
admin_checklogin();
$time = time();

/* System Settings */
if (isset($_POST['systemSettings'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['sysname']) && !empty($_POST['sysname'])) {
        $sysname = mysqli_real_escape_string($mysqli, trim($_POST['sysname']));
    } else {
        $error = 1;
        $err = "System Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $version = $_POST['version'];
        $sysname = $_POST['sysname'];
        $logo = $time . $_FILES['logo']['name'];
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../Data/SystemLogo/" . $time . $_FILES["logo"]["name"]);

        $query = "UPDATE ezanaLMS_Settings SET sysname =?, logo =?, version=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss',  $sysname,  $logo, $version, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "System Settings" && header("refresh:1; url=system_settings");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* System Calendar */
if (isset($_POST['Calendar_Iframe'])) {
    $id = $_POST['id'];
    $calendar_iframe = $_POST['calendar_iframe'];
    $query = "UPDATE ezanaLMS_Settings SET calendar_iframe =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss',  $calendar_iframe, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Calendar Settings Updated" && header("refresh:1; url=system_settings");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* System Mail Settings */
if (isset($_POST['mailSettings'])) {
    $id = $_POST['id'];
    $stmp_host = $_POST['stmp_host'];
    $stmp_port = $_POST['stmp_port'];
    $stmp_sent_from = $_POST['stmp_sent_from'];
    $stmp_username = $_POST['stmp_username'];
    $stmp_password = $_POST['stmp_password'];

    $query = "UPDATE ezanaLMS_Settings SET  stmp_host =?, stmp_port =?, stmp_sent_from =?, stmp_username =?, stmp_password =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss',  $stmp_host, $stmp_port, $stmp_sent_from, $stmp_username, $stmp_password, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "STMP Mailer Settings Updated" && header("refresh:1; url=system_settings");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}



/* Mark As Current */
if (isset($_GET['Current'])) {
    $delete = $_GET['Current'];
    $status = 'Current';
    /* Update Initial Current First */
    $clear_initial_current = "UPDATE  ezanaLMS_AcademicSettings SET status = '' ";
    $current = "UPDATE  ezanaLMS_AcademicSettings SET status =? WHERE id = ?";

    $clear_stmt = $mysqli->prepare($clear_initial_current);
    $current_stmt = $mysqli->prepare($current);

    $current_stmt->bind_param('ss', $status, $delete);

    $clear_stmt->execute();
    $current_stmt->execute();

    $clear_stmt->close();
    $current_stmt->close();

    if ($clear_stmt && $current_stmt) {
        $success = "Set Current" && header("refresh:1; url=system_settings");
    } else {
        $info = "Please Try Again Or Try Later";
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
                            <h1 class="m-0 text-bold">System Settings</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="">Settings</a></li>
                                <li class="breadcrumb-item active">System Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5 col-sm-3">
                                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                                    <a class="nav-link active" data-toggle="pill" href="#customization" role="tab">System Customization</a>
                                                    <a class="nav-link" data-toggle="pill" href="#academic_settings" role="tab">Academic Settings</a>
                                                    <a class="nav-link" data-toggle="pill" href="#calendar" role="tab">Calendar Plugin Url</a>
                                                    <a class="nav-link" data-toggle="pill" href="#mailer" role="tab">Mailing Settings</a>
                                                    <a class="nav-link" data-toggle="pill" href="#utilities" role="tab"> System Utilities</a>

                                                </div>
                                            </div>
                                            <div class="col-7 col-sm-9">
                                                <div class="tab-content" id="vert-tabs-tabContent">
                                                    <div class="tab-pane text-left fade show active" id="customization" role="tabpanel">
                                                        <br>
                                                        <?php
                                                        /* Persisit System Settings On Brand */
                                                        $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($sys = $res->fetch_object()) {
                                                        ?>
                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">System Name</label>
                                                                            <input type="text" required name="sysname" value="<?php echo $sys->sysname; ?>" class="form-control">
                                                                            <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">System Version</label>
                                                                            <input type="text" required name="version" value="<?php echo $sys->version; ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">System Logo</label>
                                                                            <div class="input-group">
                                                                                <div class="custom-file">
                                                                                    <input required name="logo" type="file" class="custom-file-input">
                                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button type="submit" name="systemSettings" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        <?php
                                                        } ?>
                                                    </div>

                                                    <div class="tab-pane fade" id="academic_settings" role="tabpanel">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Academic Year</th>
                                                                    <th>Semester</th>
                                                                    <th>Start Date </th>
                                                                    <th>End Date </th>
                                                                    <th>Manage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_AcademicSettings`  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($cal = $res->fetch_object()) {
                                                                ?>

                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                            echo $cal->current_academic_year;
                                                                            /* Show If Its Current */
                                                                            if ($cal->status == 'Current') {
                                                                                echo "<br> <span class='badge badge-success'>Current</span> ";
                                                                            } else {
                                                                                /* Nothing */
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo $cal->current_semester; ?></td>
                                                                        <td><?php echo date('d M Y', strtotime($cal->start_date)); ?></td>
                                                                        <td><?php echo  date('d M Y', strtotime($cal->end_date)); ?></td>
                                                                        <td>
                                                                            <?php
                                                                            if ($cal->status == 'Current') {
                                                                                /* Nothing */
                                                                            } else {
                                                                                echo "
                                                                                    <a class='badge badge-success' data-toggle='modal' href='#mark-as-current-$cal->id'>
                                                                                        <i class='fas fa-check'></i>
                                                                                        Mark As Current
                                                                                    </a>
                                                                                ";
                                                                            }
                                                                            ?>

                                                                            <!-- Mark As Current Confirmation Modal -->
                                                                            <div class="modal fade" id="mark-as-current-<?php echo $cal->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body text-center text-danger">
                                                                                            <h4>Mark Academic Year:<?php echo $cal->current_academic_year . " And Semester : " . $cal->current_semester; ?> As Current</h4>
                                                                                            <br>
                                                                                            <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                            <a href="system_settings?Current=<?php echo $cal->id; ?>" class="text-center btn btn-danger"> Yes Set As Current </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- End Mark As Current Confirmation Modal -->
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="tab-pane fade" id="calendar" role="tabpanel">
                                                        <?php
                                                        /* Persisit System Settings On Brand */
                                                        $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($sys = $res->fetch_object()) {
                                                        ?>
                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="">Google Calendar Iframe Embed Code</label>
                                                                            <textarea type="text" rows='5' required name="calendar_iframe" class="form-control"><?php echo $sys->calendar_iframe; ?></textarea>
                                                                            <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button type="submit" name="Calendar_Iframe" class="btn btn-primary">Update Calendar</button>
                                                                </div>
                                                            </form>
                                                        <?php
                                                        } ?>

                                                    </div>

                                                    <div class="tab-pane fade" id="mailer" role="tabpanel">
                                                        <?php
                                                        /* Persisit System Settings On Brand */
                                                        $ret = "SELECT * FROM `ezanaLMS_Settings` ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($sys = $res->fetch_object()) {
                                                        ?>
                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">STMP Host</label>
                                                                            <input type="text" required name="stmp_host" value="<?php echo $sys->stmp_host; ?>" class="form-control">
                                                                            <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">STMP Port</label>
                                                                            <input type="text" required name="stmp_port" value="<?php echo $sys->stmp_port; ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-4">
                                                                            <label for="">Send Mails From</label>
                                                                            <input type="text" required name="stmp_sent_from" value="<?php echo $sys->stmp_sent_from; ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">STMP Username</label>
                                                                            <input type="text" required name="stmp_username" value="<?php echo $sys->stmp_username; ?>" class="form-control">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="">STMP Password</label>
                                                                            <input type="password" required name="stmp_password" value="<?php echo $sys->stmp_password; ?>" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button type="submit" name="mailSettings" class="btn btn-primary">Save</button>
                                                                </div>
                                                            </form>
                                                        <?php
                                                        } ?>
                                                    </div>
                                                    <div class="tab-pane fade" id="utilities" role="tabpanel">
                                                        <br><br><br>
                                                        <div class="text-center">
                                                            <a href="system_settings_database_backup" target="_blank" class="btn btn-primary">Backup System Database</a>
                                                            <a href="../FileManager/" target="_blank" class="btn btn-primary">Ezana LMS Files Explorer</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
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