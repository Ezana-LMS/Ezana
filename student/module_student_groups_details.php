<?php
/*
 * Created on Thu Jul 01 2021
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
require_once('../config/std_checklogin.php');
std_checklogin();
require_once('../config/codeGen.php');
$time = time();

/* Upload Group Assignment */
if (isset($_POST['submit_project'])) {
    $id = $_POST['id'];
    $project_id = $_POST['project_id'];
    $faculty_id = $_POST['faculty_id'];
    $Submitted_Files = $time . $_FILES['Submitted_Files']['name'];
    move_uploaded_file($_FILES["Submitted_Files"]["tmp_name"], "../Data/Group_Projects_Attemps/" . $time . $_FILES["Submitted_Files"]["name"]);
    $group_code = $_POST['group_code'];
    $group_name  = $_POST['group_name'];
    /* Module ID */
    $view = $_POST['view'];
    /* Group ID */
    $group = $_POST['group'];

    $query = "INSERT INTO ezanaLMS_GroupsAssignmentsGrades (id, faculty_id, group_name, group_code, project_id, Submitted_Files) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss', $id, $faculty_id, $group_name, $group_code, $project_id, $Submitted_Files);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment Attempt Submitted";
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
        require_once('partials/aside.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
            /* Group Details Based On Given Group ID */
            $GroupID = $_GET['group'];
            $ret = "SELECT * FROM `ezanaLMS_StudentsGroups` WHERE id = '$GroupID'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($g = $res->fetch_object()) {

        ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="modules">Enrolled Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?> Groups</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12 text-center">
                                    <h1 class="m-0 text-bold"><?php echo $g->code; ?> - <?php echo $g->name; ?> Details</h1>
                                    <br>
                                </div>
                                <hr>
                                <div class="row">
                                    <!-- Module Side Menu -->
                                    <?php require_once('partials/module_menu.php'); ?>
                                    <!-- Module Side Menu -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body">
                                                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                                    <li class="nav-item active">
                                                                        <a class="nav-link active " id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-members" role="tab" aria-controls="custom-content-below-members" aria-selected="false">Group Members</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-notices" role="tab" aria-controls="custom-content-below-notices" aria-selected="false">Group Notices</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-notices-assignments" role="tab" aria-controls="custom-content-below-notices-assignments" aria-selected="false">Group Assignments</a>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content" id="custom-content-below-tabContent">
                                                                    <div class="tab-pane fade show active" id="custom-content-below-members" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                                        <br>
                                                                        <table id="example1" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Member Details</th>
                                                                                    <th>Date Added</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $ret = "SELECT * FROM `ezanaLMS_StudentsGroups` WHERE code = '$g->code' ";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute(); //ok
                                                                                $res = $stmt->get_result();
                                                                                while ($stdGroup = $res->fetch_object()) {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $stdGroup->student_admn . "<br>" . $stdGroup->student_name; ?></td>
                                                                                        <td><?php echo date('d M Y', strtotime($stdGroup->created_at)); ?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div class="tab-pane fade" id="custom-content-below-notices" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                                        <br>
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <?php
                                                                                $ret = "SELECT * FROM `ezanaLMS_GroupsAnnouncements` WHERE  group_code = '$g->code' ";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute(); //ok
                                                                                $res = $stmt->get_result();
                                                                                while ($ga = $res->fetch_object()) {
                                                                                ?>
                                                                                    <div class="d-flex w-100 justify-content-between">
                                                                                        <h5 class="mb-1"></h5>
                                                                                        <small class="text-success"><b><?php echo date('d-M-Y g:ia', strtotime($ga->created_at)); ?></b></small>
                                                                                    </div>
                                                                                    <?php
                                                                                    echo $ga->announcement;
                                                                                    ?> ~ <small><b><?php echo $ga->created_by; ?></b></small>
                                                                                <?php
                                                                                } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-pane fade" id="custom-content-below-notices-assignments" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                                        <br>
                                                                        <table id="faculties" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Group Details</th>
                                                                                    <th>Submission Deadline</th>
                                                                                    <th>Posted On</th>
                                                                                    <th>Manage</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE group_code = '$g->code' ";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute(); //ok
                                                                                $res = $stmt->get_result();
                                                                                while ($ass = $res->fetch_object()) {
                                                                                    $deadline = date('m/d/Y g:ia', strtotime($ass->submitted_on));
                                                                                    $now = date('m/d/Y g:ia', strtotime("now"));

                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $ass->group_code . "<br>" . $ass->group_name; ?></td>
                                                                                        <td><?php echo $ass->submitted_on; ?></td>
                                                                                        <td><?php echo date('d M Y g:ia', strtotime($ass->created_at)); ?></td>
                                                                                        <td>
                                                                                            <a class="badge badge-success" target="_blank" href="../Data/Group_Projects/<?php echo $ass->attachments; ?>">
                                                                                                <i class="fas fa-file-signature"></i>
                                                                                                Open Assignment
                                                                                            </a>

                                                                                            <?php
                                                                                            if ($deadline > $now) {
                                                                                                echo
                                                                                                "
                                                                                                <a class='badge badge-primary' data-toggle='modal' href='#add_update_$ass->id'>
                                                                                                    <i class='fas fa-file-upload'></i>
                                                                                                    Submit Attempt
                                                                                                </a>
                                                                                                ";
                                                                                            } else {
                                                                                                echo
                                                                                                "
                                                                                                    <span class='badge badge-danger'><i class='fas fa-ban'></i> Submission Deadline Elapsed</span>
                                                                                                ";
                                                                                            }
                                                                                            ?>

                                                                                            <!-- Submit Attempt -->
                                                                                            <div class="modal fade" id="add_update_<?php echo $ass->id; ?>">
                                                                                                <div class="modal-dialog  modal-xl">
                                                                                                    <div class="modal-content">
                                                                                                        <div class="modal-header">
                                                                                                            <h4 class="modal-title">Fill All Required Values </h4>
                                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                            </button>
                                                                                                        </div>
                                                                                                        <div class="modal-body">
                                                                                                            <h4 class="modal-title">Instructions</h4>
                                                                                                            <?php echo $ass->details; ?>
                                                                                                            <hr>
                                                                                                            <!-- Form -->
                                                                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                                                                <div class="card-body">
                                                                                                                    <div class="row">
                                                                                                                        <div class="form-group col-md-12">
                                                                                                                            <label for="">Upload Group Assignment Attempts</label>
                                                                                                                            <div class="input-group">
                                                                                                                                <div class="custom-file">
                                                                                                                                    <input name="Submitted_Files" required accept=".pdf, .doc, .docx" type="file" class="custom-file-input">
                                                                                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                                                                    <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                                                                    <input type="hidden" required name="group" value="<?php echo $g->id; ?>" class="form-control">
                                                                                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                                                                                    <input type="hidden" required name="group_name" value="<?php echo $g->name; ?>" class="form-control">
                                                                                                                                    <input type="hidden" required name="group_code" value="<?php echo $g->code; ?>" class="form-control">
                                                                                                                                    <input type="hidden" required name="project_id" value="<?php echo $ass->id; ?>" class="form-control">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="text-right">
                                                                                                                    <button type="submit" name="submit_project" class="btn btn-primary">Submit</button>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Main Footer -->
                        <?php require_once('partials/footer.php');
                        ?>
                    </div>
                </div>
                <!-- ./wrapper -->
        <?php  }
        }
        require_once('partials/scripts.php'); ?>
</body>

</html>