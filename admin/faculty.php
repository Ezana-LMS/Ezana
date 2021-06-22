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
require_once('../config/codeGen.php');
admin_checklogin();


/* Update Faculty Details */
if (isset($_POST['update_faculty'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $code = $_POST['code'];
    $details = $_POST['details'];

    $query = "UPDATE ezanaLMS_Faculties SET code =?, name =?, details =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $code, $name, $details, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "$name Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


/* Assign Faculty Head Or Update Faculty Head */
if (isset($_POST['update_faculty_head'])) {
    $faculty_id = $_POST['faculty_id'];
    $head = $_POST['head'];
    $email = $_POST['email'];
    /* Password To Confirm  */
    $password = sha1(md5($_POST['password']));
    $user_email = $_POST['user_email'];

    $sql = "SELECT * FROM  ezanaLMS_Admins  WHERE  password = '$password' AND email= '$user_email' ";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($password == $row['password'] && $user_email == $row['email']) {
            /* Allow User TO Update Faculty Head */
            $query = "UPDATE ezanaLMS_Faculties SET email =?, head =? WHERE id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sss', $email, $head, $faculty_id);
            $stmt->execute();
            if ($stmt) {
                $success = "Faculty Head Updated";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    } else {
        $err = "Incorrect Password, Please Try Again";
    }
}

require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
            /* Faculty Analytics */
            require_once('partials/faculty_analytics.php');
        ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <?php require_once('partials/aside.php'); ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties">Faculties</a></li>
                                    <li class="breadcrumb-item active"><?php echo $faculty->name; ?> Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="" method="GET">
                                    </form>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_faculty_head">Edit Faculty Head</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_faculty">Edit Faculty</button>
                                    </div>
                                    <!-- Edit Faculty Modal -->
                                    <div class="modal fade" id="edit_faculty">
                                        <div class="modal-dialog  modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Fill All Values </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Faculty Name</label>
                                                                    <input type="text" required name="name" value="<?php echo $faculty->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $faculty->id; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Faculty Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $faculty->code; ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Faculty Description</label>
                                                                    <textarea id="edit_Faculty" name="details" rows="5" class="form-control Summernote"><?php echo $faculty->details; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="update_faculty" class=" btn btn-primary ">Update Faculty</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Edit Faculty Modal -->

                                    <!-- Update Faculty Head Modal -->
                                    <div class="modal fade" id="edit_faculty_head" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Update Faculty Head </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-danger">
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Your Administrator Password</label>
                                                                    <!-- Hidden Values -->
                                                                    <input type="hidden" required name="id" value="<?php echo  $_SESSION['id']; ?>" class="form-control">
                                                                    <input type="hidden" required name="user_email" value="<?php echo $_SESSION['email']; ?>" class="form-control">
                                                                    <input type="password" required name="password" class="form-control">
                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $view; ?>" class="form-control">

                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Faculty Head</label>
                                                                    <select class='form-control basic' id="FacultyHead" name="head" onchange="getFacultyHeadDetails(this.value);">
                                                                        <option selected>Select Faculty Head</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Admins` ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($admins = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $admins->name; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Faculty Head Email</label>
                                                                    <input type="email" required name="email" id="FacultyHeadEmail" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="update_faculty_head" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  End Update Faculty Modal Head  -->
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Faculty Side Navigation Bar -->
                                <?php require_once('partials/faculty_menu.php'); ?>

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header  bg-primary">
                                                    <span><i class="fas fa-arrow-left"></i><a href="faculties" class="text-white"> Back</a>
                                                        <h3 class="text-center widget-user-username"><?php echo $faculty->name; ?></h3>
                                                    </span>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Faculty Code: <span class="float-right "><?php echo $faculty->code; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Faculty Head: <span class="float-right "><?php echo $faculty->head; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="faculty_departments?view=<?php echo $view; ?>" class="nav-link">
                                                                        Departments: <span class="float-right badge bg-success"><?php echo $faculty_departments; ?></span>
                                                                    </a>
                                                                </li>

                                                                <li class="nav-item">
                                                                    <a href="faculty_lecturers?view=<?php echo $view; ?>" class="nav-link">
                                                                        Lecturers <span class="float-right badge bg-danger"><?php echo $faculty_lecs; ?></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Faculty Email: <a href="mailto:<?php echo $faculty->email; ?>"><span class="float-right "><?php echo $faculty->email; ?></a></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="faculty_courses?view=<?php echo $view; ?>" class="nav-link">
                                                                        Courses Offered: <span class="float-right badge bg-primary"><?php echo $faculty_courses; ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="faculty_modules?view=<?php echo $view; ?>" class="nav-link">
                                                                        Modules: <span class="float-right badge bg-info"><?php echo $faculty_modules; ?></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <span class="nav-link text-center text-primary">
                                                                    Faculty Details
                                                                </span>
                                                            </li>
                                                            <li class="nav-item">
                                                                <?php echo $faculty->details; ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header text-center bg-primary">
                                                    <h3 class="widget-user-username">Lecturers / Students %</h3>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-center">
                                                                <div id="facultyPersonnels" style="height: 300px; width: 100%;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
        <?php require_once('partials/scripts.php');
        } ?>

        <!-- Page Level Scripts -->

        <script>
            /* Faculty Students Aganist Lecturers Donught Chart */
            var chart = new CanvasJS.Chart("facultyPersonnels", {
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                exportEnabled: true,
                animationEnabled: true,
                data: [{
                    type: "pie",
                    startAngle: 25,
                    toolTipContent: "<b>{label}</b>: {y}%",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - {y}%",
                    dataPoints: [{
                            y: <?php echo $faculty_lecs; ?>,
                            label: "Lecturers"
                        },
                        {
                            y: <?php echo $faculty_students; ?>,
                            label: "Students"
                        },
                    ]
                }]
            });
            chart.render();

            /* Edit Faculty */
            $(document).ready(function() {
                $('#edit_Faculty').summernote({
                    height: 300,
                    minHeight: null,
                    maxHeight: null,
                    focus: true
                });

                $('#department_details').summernote({
                    height: 300,
                    minHeight: null,
                    maxHeight: null,
                    focus: true
                });
            });
        </script>

</body>

</html>