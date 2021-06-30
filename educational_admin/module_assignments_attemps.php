<?php
/*
 * Created on Wed Jun 30 2021
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
require_once('../config/codeGen.php');

/* Add Student Grades */
if (isset($_POST['add_assignment'])) {
    $id = $_POST['id'];
    $module_code = $_POST['module_code'];
    $module_name = $_POST['module_name'];
    $regno = $_POST['regno'];
    $name = $_POST['name'];
    $marks = $_POST['marks'];
    /* Module ID */
    $module_id = $_POST['module_id'];

    $query = "INSERT INTO ezanaLMS_StudentModuleGrades (id, module_code, module_name, regno, name, marks) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss', $id, $module_code, $module_name, $regno, $name, $marks);
    $stmt->execute();
    if ($stmt) {
        $success = "$name Grades Added Form $module_code - $module_name";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Student Attempt */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_AssignmentsAttempts WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=module_assignments_attemps?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <?php require_once('partials/aside.php'); ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right small">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    <li class="breadcrumb-item"><a href="modules?view=<?php echo $mod->faculty_id;?>">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12 text-center">
                                <h1 class="m-0 text-bold"><?php echo $mod->name; ?> Assignments Attempts</h1>
                                <br>
                                <span class="btn btn-primary"><i class="fas fa-arrow-left"></i><a href="module_assignments?view=<?php echo $mod->id; ?>" class="text-white">Back</a></span>

                            </div>

                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('partials/module_menu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card-box">
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th data-toggle="true">Student Admission</th>
                                                            <th data-toggle="true">Student Name</th>
                                                            <th>Module Name</th>
                                                            <th>Module Code</th>
                                                            <th data-hide="all">Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $assignment_id = $_GET['assignment'];
                                                        $ret = "SELECT * FROM `ezanaLMS_AssignmentsAttempts` WHERE assignment_id = '$assignment_id'   ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($attempts = $res->fetch_object()) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $attempts->std_regno; ?></td>
                                                                <td><?php echo $attempts->std_name; ?></td>
                                                                <td><?php echo $attempts->module_name; ?></td>
                                                                <td><?php echo $attempts->module_code; ?></td>
                                                                <td>
                                                                    <a target="_blank" href="../Data/Module_Assignments_Attempts/<?php echo $attempts->attachments; ?>" class="badge badge-secondary">
                                                                        <i class="fas fa-eye"></i>
                                                                        View Attachment
                                                                    </a>
                                                                    <!-- End Update Modal -->
                                                                    <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $attempts->id; ?>">
                                                                        <i class="fas fa-trash"></i>
                                                                        Delete Attempt
                                                                    </a>
                                                                    <!-- Delete Confirmation Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $attempts->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <h4>Delete?</h4>
                                                                                    <br>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="module_assignments_attemps?delete=<?php echo $attempts->id; ?>&view=<?php echo $mod->id;?>" class="text-center btn btn-danger"> Delete </a>
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
                    </section>
                    <!-- Main Footer -->
                <?php require_once('partials/footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('partials/scripts.php'); ?>
</body>

</html>