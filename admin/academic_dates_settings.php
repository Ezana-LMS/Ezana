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

/* Add Academic Years */
if (isset($_POST['add_academic_years'])) {
    $error = 0;
    if (isset($_POST['current_academic_year']) && !empty($_POST['current_academic_year'])) {
        $current_academic_year = mysqli_real_escape_string($mysqli, trim($_POST['current_academic_year']));
    } else {
        $error = 1;
        $err = "Academic Year Cannot Be Empty";
    }
    if (isset($_POST['current_semester']) && !empty($_POST['current_semester'])) {
        $current_semester = mysqli_real_escape_string($mysqli, trim($_POST['current_semester']));
    } else {
        $error = 1;
        $err = "Semester Name Cannot Be Empty";
    }
    if (isset($_POST['start_date']) && !empty($_POST['start_date'])) {
        $start_date = mysqli_real_escape_string($mysqli, trim($_POST['start_date']));
    } else {
        $error = 1;
        $err = "Semester Opening Dates Cannot Be Empty";
    }
    if (isset($_POST['end_date']) && !empty($_POST['end_date'])) {
        $end_date = mysqli_real_escape_string($mysqli, trim($_POST['end_date']));
    } else {
        $error = 1;
        $err = "Semester Closing  Dates Cannot Be Empty";
    }
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = mysqli_real_escape_string($mysqli, trim($_POST['id']));
    } else {
        $error = 1;
        $err = "ID Cannot Be Empty";
    }

    if (!$error) {
        $sql = "SELECT * FROM  ezanaLMS_AcademicSettings";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($current_semester == $row['current_semester']) && ($current_academic_year == $row['current_academic_year'])) {
                $err =  "$current_academic_year Already Has $current_semester ";
            }
        } else {
            $query = "INSERT INTO ezanaLMS_AcademicSettings (current_academic_year, current_semester, start_date, end_date) VALUES(?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssss', $current_academic_year, $current_semester, $start_date, $end_date);
            $stmt->execute();
            if ($stmt) {
                $success = "$current_academic_year Added With $current_semester";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Academic Settings  */
if (isset($_POST['update_academic_years'])) {
    $error = 0;
    if (isset($_POST['current_academic_year']) && !empty($_POST['current_academic_year'])) {
        $current_academic_year = mysqli_real_escape_string($mysqli, trim($_POST['current_academic_year']));
    } else {
        $error = 1;
        $err = "Academic Year Cannot Be Empty";
    }
    if (isset($_POST['current_semester']) && !empty($_POST['current_semester'])) {
        $current_semester = mysqli_real_escape_string($mysqli, trim($_POST['current_semester']));
    } else {
        $error = 1;
        $err = "Semester Name Cannot Be Empty";
    }
    if (isset($_POST['start_date']) && !empty($_POST['start_date'])) {
        $start_date = mysqli_real_escape_string($mysqli, trim($_POST['start_date']));
    } else {
        $error = 1;
        $err = "Semester Opening Dates Cannot Be Empty";
    }
    if (isset($_POST['end_date']) && !empty($_POST['end_date'])) {
        $end_date = mysqli_real_escape_string($mysqli, trim($_POST['end_date']));
    } else {
        $error = 1;
        $err = "Semester Closing  Dates Cannot Be Empty";
    }

    if (!$error) {

        $query = "UPDATE  ezanaLMS_AcademicSettings SET current_academic_year =?, current_semester =?, start_date =?, end_date =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss', $current_academic_year, $current_semester, $start_date, $end_date, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "$current_academic_year Added With $current_semester Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Delete Important Dates */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    /* Dont Accidentally Delete Current Semester */
    $adn = "DELETE FROM ezanaLMS_AcademicSettings WHERE id=? && status !='Current'";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=academic_dates_settings");
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
                            <h1 class="m-0 text-dark">Academic Calendar Settings</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Academic Dates</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <nav class="navbar col-md-12">
                                <form class="form-inline" action="" method="GET">
                                </form>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Important Dates</button>
                                <!-- Add Important Dates Modal -->
                                <div class="modal fade" id="modal-default">
                                    <div class="modal-dialog  modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Add Academic Dates </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Academic Year</label>
                                                                <input type="text" required name="current_academic_year" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Semester</label>
                                                                <input type="text" required name="current_semester" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Semester Opening Date</label>
                                                                <input type="date" required name="start_date" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for=""> Semester Closing Dates</label>
                                                                <input type="date" required name="end_date" class=" form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="add_academic_years" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
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
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="example1" class="table table-bordered table-striped">
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
                                                                <td><?php echo $cal->current_academic_year; ?></td>
                                                                <td><?php echo $cal->current_semester; ?></td>
                                                                <td><?php echo date('d M Y', strtotime($cal->start_date)); ?></td>
                                                                <td><?php echo  date('d M Y', strtotime($cal->end_date)); ?></td>
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
                                                                                    <h4 class="modal-title">Update <?php echo $cal->current_academic_year; ?> <?php echo $cal->current_semester; ?> Dates </h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Academic Year</label>
                                                                                                    <input type="text" required name="current_academic_year" value="<?php echo $cal->current_academic_year; ?>" class="form-control">
                                                                                                    <input type="hiddden" required name="id" value="<?php echo $cal->id; ?>" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Semester</label>
                                                                                                    <input type="text" required name="current_semester" value="<?php echo $cal->current_semester; ?>" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Semester Opening Date</label>
                                                                                                    <input type="date" required value="<?php echo $cal->start_date; ?>" name="start_date" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for=""> Semester Closing Dates</label>
                                                                                                    <input type="date" value="<?php echo $cal->end_date; ?>" required name="end_date" class=" form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="add_academic_years" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>
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
                                                                                    <p>Heads Up, You Are About To Delete This Academic Dates, This Operation Is Irreversible</p>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="academic_dates_settings?delete=<?php echo $cal->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Delete Confirmation Modal -->
                                                                </td>
                                                            </tr>
                                                        <?php
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
                <?php require_once('partials/footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>