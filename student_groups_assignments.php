<?php
/*
 * Created on Thu Apr 01 2021
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
check_login();
require_once('configs/codeGen.php');
/* Add Groups Assignments */
if (isset($_POST['add_group_project'])) {
    $id = $_POST['id'];
    $details = $_POST['details'];
    $faculty = $_POST['faculty'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Group_Projects/" . $_FILES["attachments"]["name"]);
    $submitted_on = $_POST['submitted_on'];
    /* Module ID */
    $view = $_POST['view'];

    $query = "INSERT INTO ezanaLMS_GroupsAssignments (id, faculty_id, module_id,  attachments, details, submitted_on) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss', $id, $faculty, $view,  $attachments, $details, $submitted_on);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment Added" && header("refresh:1; url=student_groups_assignments.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
/* Update Group Assignments */

if (isset($_POST['update_group_project'])) {

    $id = $_POST['id'];
    $details = $_POST['details'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Group_Projects/" . $_FILES["attachments"]["name"]);
    $updated_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];
    /* Module ID */
    $view  = $_POST['view'];

    $query = "UPDATE ezanaLMS_GroupsAssignments SET attachments =?, details =?, updated_at =?, submitted_on =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssss', $attachments, $details, $updated_at, $submitted_on, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Updated" && header("refresh:1; url=student_groups_assignments.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
/* Delete Group Assignments */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_GroupsAssignments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=student_groups_assignments.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('public/partials/_analytics.php');
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="dashboard.php" class=" nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="faculties.php" class=" nav-link">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Faculties
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="departments.php" class=" nav-link">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Departments
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="courses.php" class=" nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class="active nav-link">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>
                                        Modules
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="non_teaching_staff.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-secret"></i>
                                    <p>
                                        Non Teaching Staff
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="lecturers.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>
                                        Lecturers
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="students.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>
                                        Students
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class=" nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        System Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports.php" class=" nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>Reports</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="system_settings.php" class="nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>System Settings</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Group Assignments</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="modules.php">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="module_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Assignment</button>
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Fill All Required Values </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <!-- Hide This Please -->
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">

                                                                <!-- <div class="form-group col-md-6">
                                                                    <label for="exampleInputPassword1">Group Name</label>
                                                                    <select class='form-control basic' id="GroupName" onchange="getGroupDetails(this.value);" name="group_name">
                                                                        <option selected>Select Group Name</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE faculty_id = '$row->id'";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($group = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $group->name; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputPassword1"> Group Code</label>
                                                                    <input type="text" required name="group_code" id="groupCode" class="form-control">
                                                                </div> -->
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputPassword1">Submission Date </label>
                                                                    <input type="date" required name="submitted_on" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Upload Group Assignment (PDF Or Docx)</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input name="attachments" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Instructions</label>
                                                                    <textarea name="details" id="textarea" required rows="5" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_group_project" class="btn btn-primary">Submit</button>
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
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <a href="module.php?view=<?php echo $mod->id; ?>">
                                                    <h3 class="card-title"><?php echo $mod->name; ?></h3>
                                                    <div class="card-tools text-right">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="card-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="module_notices.php?view=<?php echo $mod->id; ?>">
                                                            Notices & Memos
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="pastpapers.php?view=<?php echo $mod->id; ?>">
                                                            Past Papers
                                                        </a>
                                                    </li>

                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="course_materials.php?view=<?php echo $mod->id; ?>">
                                                            Reading Materials
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="class_recordings.php?view=<?php echo $mod->id; ?>">
                                                            Class Recordings
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="module_assignments.php?view=<?php echo $mod->id; ?>">
                                                            Assignments
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="student_groups.php?view=<?php echo $mod->id; ?>">
                                                            Student Groups
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="grades.php?view=<?php echo $mod->id; ?>">
                                                            Grades
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="module_enrollments.php?view=<?php echo $mod->id; ?>">
                                                            Module Enrollments
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="row">
                                                <?php
                                                /* Sort By Date Created At */
                                                $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE module_id ='$mod->id' ORDER BY `ezanaLMS_GroupsAssignments`.`created_at` ASC  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($gcode = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <a data-toggle="modal" href="#Instructions-<?php echo $gcode->id; ?>">
                                                                <div class="card-body">
                                                                    <p class="card-title"><?php echo $gcode->attachments; ?></p>
                                                                </div>
                                                            </a>
                                                            <div class="modal fade" id="Instructions-<?php echo $gcode->id; ?>">
                                                                <div class="modal-dialog  modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Group Assignments Details And Attemps</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                                                <li class="nav-item">
                                                                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Assignment Instructions</a>
                                                                                </li>
                                                                            </ul>
                                                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                                                    <br>
                                                                                    <?php echo $gcode->details; ?>
                                                                                    <br>
                                                                                    <small class="text-danger">Submission Deadline: <?php echo $gcode->submitted_on; ?></small>
                                                                                    <br>
                                                                                    <a target='_blank' href='public/uploads/EzanaLMSData/Group_Projects/<?php echo $gcode->attachments; ?>' class='btn btn-outline-success'>
                                                                                        Open Assignment
                                                                                    </a>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="card-footer">
                                                                <small class="text-muted">Submission Deadline: <?php echo date('d M Y', strtotime($gcode->submitted_on)); ?></small>
                                                                <a class="badge badge-primary" href="group_assignments_attemps.php?assignment=<?php echo $gcode->id; ?>&view=<?php echo $mod->id; ?>"> Attempts</a>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-<?php echo $gcode->id; ?>"> Edit</a>
                                                                <div class="modal fade" id="edit-<?php echo $gcode->id; ?>">
                                                                    <div class="modal-dialog  modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Fill All Required Values </h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <!-- Form -->
                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <!-- hIDE tHIS -->
                                                                                            <input type="hidden" required name="id" value="<?php echo $gcode->id; ?>" class="form-control">
                                                                                            <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="exampleInputPassword1">Submission Date </label>
                                                                                                <input type="date" value="<?php echo $gcode->submitted_on; ?>" required name="submitted_on" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Upload Group Assignment (PDF Or Docx)</label>
                                                                                                <div class="input-group">
                                                                                                    <div class="custom-file">
                                                                                                        <input data-default-file="dist/Group_Projects/<?php echo $gcode->attachments; ?>" required data-max-file-size="5M" name="attachments" type="file" class="custom-file-input">
                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Type Group Project / Assignment Or Project Description </label>
                                                                                                <textarea name="details" id="textarea" rows="10" required class="form-control"><?php echo $gcode->details; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_group_project" class="btn btn-primary">Submit</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $gcode->id; ?>"> Delete</a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $gcode->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $gcode->attachments; ?> Group Assignment ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="student_groups_assignments.php?delete=<?php echo $gcode->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End Delete Confirmation Modal -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>