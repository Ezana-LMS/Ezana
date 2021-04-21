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

/* Add Department */
if (isset($_POST['add_dept'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Department Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Department Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Departments WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Department With This Code Already Exists";
            } else {
                $err = "Department Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $view = $_GET['view'];
            $details = $_POST['details'];
            $hod = $_POST['hod'];
            $faculty_name = $_POST['faculty_name'];
            $created_at = date('d M Y');


            $query = "INSERT INTO ezanaLMS_Departments (id, code, name, faculty_id, faculty_name, details, hod, created_at) VALUES(?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssss', $id, $code, $name, $view, $faculty_name, $details, $hod, $created_at);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Department Added" && header("Refresh: 0");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Faculty */
if (isset($_POST['update_faculty'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Faculty Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Faculty Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];
        $head = $_POST['head'];
        $email = $_POST['email'];

        $query = "UPDATE ezanaLMS_Faculties SET code =?, name =?, details =?, head=?, email =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss', $code, $name, $details, $head, $email, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Added" && header("Refresh: 0");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
            /* Faculty Analytics */
            require_once('public/partials/_facultyanalyitics.php');
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
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="edu_admn_faculties.php?view=<?php echo $faculty->id; ?>">Faculties</a></li>
                                    <li class="breadcrumb-item active"><?php echo $faculty->name; ?> Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="edu_admn_department_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Dep Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_faculty">Edit Faculty</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Department</button>
                                    </div>
                                    <div class="modal fade" id="modal-default">
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
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Department Name</label>
                                                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty_name" value="<?php echo $faculty->name; ?>" class="form-control">

                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Department Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="">HOD</label>
                                                                    <input type="text" required name="hod" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Department Details</label>
                                                                    <textarea name="details" rows="10" class="form-control Summernote"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_dept" class="btn btn-primary">Add Department</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Faculty Head</label>
                                                                    <input type="text" required name="head" value="<?php echo $faculty->head; ?>" class="form-control" id="exampleInputEmail1">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Faculty Email</label>
                                                                    <input type="text" required name="email" value="<?php echo $faculty->email; ?>" class="form-control">
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
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Faculty Sub Menu -->
                                <?php require_once('public/partials/_facultysubmenu.php'); ?>
                                <!-- End Sub Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header text-center bg-primary">
                                                    <h3 class="widget-user-username"><?php echo $faculty->name; ?></h3>
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
                                                                    <a href="edu_admn_faculty_departments.php?view=<?php echo $view; ?>" class="nav-link">
                                                                        Departments: <span class="float-right badge bg-success"><?php echo $faculty_departments; ?></span>
                                                                    </a>
                                                                </li>

                                                                <li class="nav-item">
                                                                    <a href="edu_admn_faculty_lects.php?view=<?php echo $view; ?>" class="nav-link">
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
                                                                    <a href="edu_admn_faculty_courses.php?view=<?php echo $view; ?>" class="nav-link">
                                                                        Courses Offered: <span class="float-right badge bg-primary"><?php echo $faculty_courses; ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="edu_admn_faculty_modules.php?view=<?php echo $view; ?>" class="nav-link">
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
                                                    <h3 class="widget-user-username">Lecturers / Students Numbers</h3>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <canvas id="facultyPersonnels" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
            /* Lecs  */
            $query = "SELECT COUNT(*)  FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$view' ";
            $stmt = $mysqli->prepare($query);
            $stmt->execute();
            $stmt->bind_result($faculty_lecs);
            $stmt->fetch();
            $stmt->close();

            /*  Students */
            $query = "SELECT COUNT(*)  FROM `ezanaLMS_Students` WHERE faculty_id = '$view' ";
            $stmt = $mysqli->prepare($query);
            $stmt->execute();
            $stmt->bind_result($faculty_students);
            $stmt->fetch();
            $stmt->close();
        } ?>

        <!-- Page Level Scripts -->

        <script>
            /* Faculty Students Aganist Lecturers Donught Chart */
            $(function() {
                var donutChartCanvas = $('#facultyPersonnels').get(0).getContext('2d')
                var donutData = {
                    labels: [
                        'Lecturers',
                        'Students',
                    ],
                    datasets: [{
                        data: [<?php echo $faculty_lecs; ?>, <?php echo $faculty_students; ?>],
                        backgroundColor: ['#f56954', '#00a65a'],
                    }]
                }
                var donutOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                }
                /* You Can Switch From Donught To Pie Chart */

                var donutChart = new Chart(donutChartCanvas, {
                    type: 'pie',
                    data: donutData,
                    options: donutOptions
                })
            })
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