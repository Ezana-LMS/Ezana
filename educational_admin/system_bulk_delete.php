<?php
/*
 * Created on Tue Jun 29 2021
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
require_once('../config/edu_admn_checklogin.php');
edu_admn_checklogin();

/* Delete Departments */
if (isset($_GET['department'])) {
    $delete = $_GET['department'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Departments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_bulk_delete?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Courses */
if (isset($_GET['course'])) {
    $delete = $_GET['course'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Courses WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_bulk_delete?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Modules */
if (isset($_GET['module'])) {
    $delete = $_GET['module'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Modules WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_bulk_delete?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Student */
if (isset($_GET['student'])) {
    $delete = $_GET['student'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Students WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_bulk_delete?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Lecturers */
if (isset($_GET['lecturer'])) {
    $delete = $_GET['lecturer'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Lecturers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=system_bulk_delete?view=$view");
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
        <?php require_once('partials/aside.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) { ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-danger text-bold">Delete Operations</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="">System Settings</a></li>
                                    <li class="breadcrumb-item active">Delete Operations</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-danger card-outline">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5 col-sm-3">
                                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                                        <a class="nav-link active" data-toggle="pill" href="#departments" role="tab">Departments</a>
                                                        <a class="nav-link" data-toggle="pill" href="#courses" role="tab">Courses</a>
                                                        <a class="nav-link" data-toggle="pill" href="#modules" role="tab">Modules</a>
                                                        <a class="nav-link" data-toggle="pill" href="#non-teaching-staff" role="tab">Non Teaching Staff</a>
                                                        <a class="nav-link" data-toggle="pill" href="#lecturers" role="tab">Lecturers</a>
                                                        <a class="nav-link" data-toggle="pill" href="#students" role="tab">Students</a>


                                                    </div>
                                                </div>
                                                <div class="col-7 col-sm-9">
                                                    <div class="tab-content" id="vert-tabs-tabContent">


                                                        <div class="tab-pane fade active" id="departments" role="tabpanel">
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>HOD</th>
                                                                        <th>Email</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$view' ORDER BY `name` ASC   ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($dep = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $dep->code; ?></td>
                                                                            <td><?php echo $dep->name; ?></td>
                                                                            <td><?php echo $dep->hod; ?></td>
                                                                            <td><?php echo $dep->email; ?></td>
                                                                            <td>

                                                                                <a class="badge badge-danger" href="#delete-<?php echo $dep->id; ?>" data-toggle="modal">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $dep->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $dep->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <p>Heads Up, You Are About To Delete <?php echo $dep->name; ?>. This Operation Is Irreversible.</p>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_bulk_delete?department=<?php echo $dep->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-danger"> Delete </a>
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

                                                        <div class="tab-pane fade" id="courses" role="tabpanel">
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>Course Head</th>
                                                                        <th>Course Head Email</th>
                                                                        <th>Department</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view'";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($courses = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $courses->code; ?></td>
                                                                            <td><?php echo $courses->name; ?></td>
                                                                            <td><?php echo $courses->hod; ?></td>
                                                                            <td><?php echo $courses->email; ?></td>
                                                                            <td><?php echo $courses->department_name; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" href="#delete-<?php echo $courses->id; ?>" data-toggle="modal">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $courses->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $courses->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <p>Heads Up, You Are About To Delete <?php echo $courses->name; ?>. This Operation Is Irreversible.</p>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_bulk_delete?course=<?php echo $courses->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-danger"> Delete </a>
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

                                                        <div class="tab-pane fade" id="modules" role="tabpanel">
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Code</th>
                                                                        <th>Course</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE faculty_id = '$faculty'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($mod = $res->fetch_object()) {
                                                                    ?>

                                                                        <tr>
                                                                            <td><?php echo $mod->name; ?></td>
                                                                            <td><?php echo $mod->code; ?></td>
                                                                            <td><?php echo $mod->course_name; ?></td>
                                                                            <td>
                                                                                <!-- End Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $mod->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $mod->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $mod->name; ?> ?</h4>
                                                                                                <br>
                                                                                                <p>Heads Up, You Are About To Delete <?php echo $mod->name; ?>. This Operation Is Irrevssible.</p>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_bulk_delete?module=<?php echo $mod->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-danger"> Delete </a>
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


                                                        <div class="tab-pane fade" id="lecturers" role="tabpanel">
                                                            <table id="lecturers" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Number</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>ID/Passport</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$view' ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($lec = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $lec->number; ?></td>
                                                                            <td><?php echo $lec->name; ?></td>
                                                                            <td><?php echo $lec->email; ?></td>
                                                                            <td><?php echo $lec->phone; ?></td>
                                                                            <td><?php echo $lec->idno; ?></td>
                                                                            <td>

                                                                                <!-- End Lec Modal -->
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $lec->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $lec->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $lec->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <p>Heads Up, You Are About To Delete <?php echo $lec->name; ?>. This Operation Is Irreversible.</p>
                                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_bulk_delete?lecturer=<?php echo $lec->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                                        <div class="tab-pane fade" id="students" role="tabpanel">
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name & Admission</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>ID/Passport</th>
                                                                        <th>Course Taking</th>
                                                                        <th>Manage</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$view' ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($std = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $std->admno . "<br>" . $std->name; ?></td>
                                                                            <td><?php echo $std->email; ?></td>
                                                                            <td><?php echo $std->phone; ?></td>
                                                                            <td><?php echo $std->idno; ?></td>
                                                                            <td><?php echo $std->course; ?></td>
                                                                            <td>
                                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $std->id; ?>">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    Delete
                                                                                </a>
                                                                                <!-- Delete Confirmation Modal -->
                                                                                <div class="modal fade" id="delete-<?php echo $std->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body text-center text-danger">
                                                                                                <h4>Delete <?php echo $std->name; ?> Details ?</h4>
                                                                                                <br>
                                                                                                <p>Heads Up, You Are About To Delete <?php echo $std->admno . " " . $std->name; ?>. This Operation Is Irrevisible.</p> <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                <a href="system_bulk_delete?student=<?php echo $std->id; ?>&view=<?php echo $view; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                        <!-- /.card -->
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
        <?php }
        require_once('partials/scripts.php'); ?>
</body>

</html>