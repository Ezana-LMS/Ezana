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

/* Add Important Dates */
if (isset($_POST['add_dates'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['academic_yr']) && !empty($_POST['academic_yr'])) {
        $academic_yr = mysqli_real_escape_string($mysqli, trim($_POST['academic_yr']));
    } else {
        $error = 1;
        $err = "Academic Year Cannot Be Empty";
    }
    if (isset($_POST['semester_name']) && !empty($_POST['semester_name'])) {
        $semester_name = mysqli_real_escape_string($mysqli, trim($_POST['semester_name']));
    } else {
        $error = 1;
        $err = "Semester Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Opening Dates Cannot Be Empty";
    }
    if (isset($_POST['semester_end']) && !empty($_POST['semester_end'])) {
        $semester_end = mysqli_real_escape_string($mysqli, trim($_POST['semester_end']));
    } else {
        $error = 1;
        $err = "Semester Closing  Dates Cannot Be Empty";
    }
    if (isset($_GET['view']) && !empty($_GET['view'])) {
        $view = mysqli_real_escape_string($mysqli, trim($_GET['view']));
    } else {
        $error = 1;
        $err = "Faculty ID  Dates Cannot Be Empty";
    }
    if (isset($_POST['description']) && !empty($_POST['description'])) {
        $description = $_POST['description'];
    } else {
        $error = 1;
        $err = "Event Description Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Calendar WHERE  (semester_name='$semester_name' AND academic_yr = '$academic_yr' AND faculty_id = '$view' AND details = '$description' )   ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($semester_name == $row['semester_name']) && ($academic_yr == $row['academic_yr']) && ($view == $row['faculty_id']) && ($description  == $row['details'])) {
                $err =  "Academic Dates Already Added";
            }
        } else {
            $id = $_POST['id'];
            $query = "INSERT INTO ezanaLMS_Calendar (id, faculty_id,  academic_yr, semester_start, semester_name, semester_end, details) VALUES(?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssss', $id, $view,  $academic_yr, $semester_start, $semester_name, $semester_end, $description);
            $stmt->execute();
            if ($stmt) {
                $success = "Educational Dates Added" && header("Refresh: 0");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Important Dates */
if (isset($_POST['update_school_calendar'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['academic_yr']) && !empty($_POST['academic_yr'])) {
        $academic_yr = mysqli_real_escape_string($mysqli, trim($_POST['academic_yr']));
    } else {
        $error = 1;
        $err = "Academic Year Cannot Be Empty";
    }
    if (isset($_POST['semester_name']) && !empty($_POST['semester_name'])) {
        $semester_name = mysqli_real_escape_string($mysqli, trim($_POST['semester_name']));
    } else {
        $error = 1;
        $err = "Semester Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Opening Dates Cannot Be Empty";
    }
    if (isset($_POST['semester_end']) && !empty($_POST['semester_end'])) {
        $semester_end = mysqli_real_escape_string($mysqli, trim($_POST['semester_end']));
    } else {
        $error = 1;
        $err = "Semester Closing  Dates Cannot Be Empty";
    }
    if (!$error) {

        $id = $_POST['id'];
        $academic_yr = $_POST['academic_yr'];
        $semester_start = $_POST['semester_start'];
        $semester_name = $_POST['semester_name'];
        $semester_end = $_POST['semester_end'];
        $description = $_POST['description'];
        /* fACULTY id */
        $view = $_POST['view'];


        $query = "UPDATE ezanaLMS_Calendar SET academic_yr =?, semester_start =?, semester_name =?, semester_end =?, details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssss',  $academic_yr, $semester_start, $semester_name, $semester_end, $description, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Educational Dates Updated" && header("Refresh: 0");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Important Dates */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Calendar WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=edu_admn_school_calendar.php?view=$view");
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
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?> Important Dates</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active"><?php echo $faculty->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline">
                                        <!-- Leave This Like This For Now -->
                                    </form>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Faculty Important Dates</button>
                                    </div>
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Faculty Important Dates </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <?php
                                                            /* Persisit Academic Settings */
                                                            $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($academic_settings = $res->fetch_object()) {
                                                            ?>
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Academic Year </label>
                                                                        <input type="text" required value="<?php echo $academic_settings->current_academic_year; ?>" name="academic_yr" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Semester </label>
                                                                        <input type="text" required value="<?php echo $academic_settings->current_semester; ?>" name="semester_name" class="form-control" id="exampleInputEmail1">
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                        <input type="hidden" required name="view" value="<?php echo $faculty->id; ?>" class="form-control">

                                                                    </div>
                                                                </div>
                                                            <?php
                                                            } ?>
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Start Date</label>
                                                                    <input type="date" required name="semester_start" class="form-control" id="exampleInputEmail1">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">End Date</label>
                                                                    <input type="date" required name="semester_end" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Description</label>
                                                                    <textarea rows="3" type="text" required name="description" class="form-control Summernote"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit" name="add_dates" class="btn btn-primary">Submit</button>
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- <div class="text-center">
                                                <h1 class="display-4">Faculty Important Dates</h1>
                                            </div> -->
                                            <div class="row">
                                                <div class="col-12">

                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Academic Year</th>
                                                                <th>Semester</th>
                                                                <th>Start Date </th>
                                                                <th>End Date </th>
                                                                <th>Description</th>
                                                                <th>Manage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE faculty_id = '$faculty->id'  ORDER BY `ezanaLMS_Calendar`.`semester_start` ASC   ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($cal = $res->fetch_object()) {
                                                            ?>

                                                                <tr>

                                                                    <td><?php echo $cal->academic_yr; ?></td>
                                                                    <td><?php echo $cal->semester_name; ?></td>
                                                                    <td><?php echo date('d M Y', strtotime($cal->semester_start)); ?></td>
                                                                    <td><?php echo  date('d M Y', strtotime($cal->semester_end)); ?></td>
                                                                    <td><?php echo $cal->details; ?></td>
                                                                    <td>
                                                                        <a class="badge badge-primary" data-toggle="modal" href="#update-calendar-<?php echo $cal->id; ?>">
                                                                            <i class="fas fa-edit"></i>
                                                                            Update
                                                                        </a>
                                                                        <!-- Update Modal -->
                                                                        <div class="modal fade" id="update-calendar-<?php echo $cal->id; ?>">
                                                                            <div class="modal-dialog  modal-xl">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Update <?php echo $cal->academic_yr; ?> <?php echo $cal->semester_name; ?> Important Dates </h4>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                                                            <div class="card-body">
                                                                                                <div class="row">
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Academic Year Name</label>
                                                                                                        <input type="text" value="<?php echo $cal->academic_yr; ?>" required name="academic_yr" class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Semester Name</label>
                                                                                                        <input type="text" value="<?php echo $cal->semester_name; ?>" required name="semester_name" class="form-control" id="exampleInputEmail1">
                                                                                                        <input type="hidden" required name="id" value="<?php echo $cal->id; ?>" class="form-control">
                                                                                                        <input type="hidden" required name="view" value="<?php echo $faculty->id; ?>" class="form-control">
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Start Date</label>
                                                                                                        <input type="date" value="<?php echo $cal->semester_start; ?>" required name="semester_start" class="form-control" id="exampleInputEmail1">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">End Date</label>
                                                                                                        <input type="date" value="<?php echo $cal->semester_end; ?>" required name="semester_end" class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-12">
                                                                                                        <label for="">Description</label>
                                                                                                        <textarea rows="3" type="text" required name="description" class="form-control Summernote"><?php echo $cal->details; ?></textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="text-right">
                                                                                                <button type="submit" name="update_school_calendar" class="btn btn-primary">Submit</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                    <div class="modal-footer justify-content-between">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Update Modal -->

                                                                        <a class="badge badge-danger" href="#delete-<?php echo $cal->id; ?>" data-toggle="modal">
                                                                            <i class="fas fa-trash"></i>
                                                                            Delete
                                                                        </a>
                                                                        <!-- Delete Confirmation Modal -->
                                                                        <div class="modal fade" id="delete-<?php echo $cal->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body text-center text-danger">
                                                                                        <h4>Delete Dates?</h4>
                                                                                        <br>
                                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                        <a href="edu_admn_school_calendar.php?view=<?php echo $view; ?>&delete=<?php echo $cal->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Delete Confirmation Modal -->
                                                                    </td>
                                                                </tr>
                                                            <?php $cnt = $cnt + 1;
                                                            } ?>
                                                        </tbody>
                                                    </table>
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
        } ?>
</body>

</html>