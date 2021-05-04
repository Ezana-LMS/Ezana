<?php
/*
 * Created on Tue May 04 2021
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
std_check_login();

/* Report Bug */
if (isset($_POST['submitBug'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number  = $_POST['number'];
    $bug_title = $_POST['bug_title'];
    $bug_details = $_POST['bug_details'];
    $severity = $_POST['severity'];
    $status = $_POST['status'];
    $query = "INSERT INTO ezanaLMS_BugReports (id, name, email, number, bug_title, bug_details, severity, status) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $id, $name, $email, $number, $bug_title, $bug_details, $severity, $status);
    $stmt->execute();
    /* Mail User  */
    require_once('configs/mail.php');

    if ($stmt && $mail->send()) {
        $success = "Bug Reported" &&  header("refresh:1; url=std_report_bug.php");;
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_std_nav.php');
        ?>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <?php require_once('public/partials/_brand.php'); ?>
            <!-- Sidebar -->
            <?php require_once('public/partials/_std_sidebar.php');
            $id  = $_SESSION['id'];
            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($student = $res->fetch_object()) {
            ?>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Bug Report</h1>
                            <small>Help Our Development Team Upgrade This Platform</small>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="std_dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Bug Report</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                <input type="hidden" required name="name" value="<?php echo $student->name; ?>" class="form-control">
                                                <input type="hidden" required name="email" value="<?php echo $student->email; ?>" class="form-control">
                                                <input type="hidden" required name="number" value="<?php echo $student->admno; ?>" class="form-control">
                                                <input type="hidden" required name="severity" value="High" class="form-control">
                                                <input type="hidden" required name="status" value="Pending Fix" class="form-control">
                                                <!-- Mail Details -->
                                                <input type="hidden" name="email" required class="form-control" value="<?php echo $student->email; ?>">
                                                <input type="hidden" required name="subject" value="Bug Report" class="form-control">
                                                <input type="hidden" required name="message" value="Howdy, <?php echo $student->name; ?>😊. <br> Thanks for submitting a bug report to us. Our team will fix it as soon as possible." class="form-control">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="">Bug Title</label>
                                                <input type="text" required name="bug_title" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputPassword1">Bug Details / Description</label>
                                                <textarea required name="bug_details" class="form-control Summernote"></textarea>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" name="submitBug" class="btn btn-primary">Submit Bug Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    <?php
            }
            require_once("public/partials/_footer.php");
            require_once("public/partials/_scripts.php");
    ?>
</body>

</html>