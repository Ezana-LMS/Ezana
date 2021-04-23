<?php
/*
 * Created on Fri Apr 23 2021
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

/* Add Group Announcements */
if (isset($_POST['add_notice'])) {
    $id = $_POST['id'];
    $group_code  = $_POST['group_code'];
    $group_name = $_POST['group_name'];
    $announcement = $_POST['announcement'];
    $created_by = $_POST['created_by'];
    $faculty = $_POST['faculty'];
    /* Module ID */
    $view = $_POST['view'];
    /* Group id */
    $group = $_POST['group'];

    $query = "INSERT INTO ezanaLMS_GroupsAnnouncements (id, faculty_id, group_name, group_code, announcement, created_by) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss', $id, $faculty, $group_name, $group_code, $announcement, $created_by);
    $stmt->execute();
    if ($stmt) {
        $success = "Posted" && header("refresh:1; url=edu_admn_group_details.php?view=$view&group=$group");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Update Announcement */
if (isset($_POST['update_notice'])) {
    $id = $_POST['id'];
    $announcement = $_POST['announcement'];
    $created_by = $_POST['created_by'];
    $updated_at = date('d M Y');
    $view = $_POST['view'];
    $group = $_POST['group'];

    $query = "UPDATE  ezanaLMS_GroupsAnnouncements SET announcement =?, created_by =?, updated_at=? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $announcement, $created_by, $updated_at, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=edu_admn_group_details.php?view=$view&group=$group");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Group Member */
if (isset($_POST['add_member'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['student_admn']) && !empty($_POST['student_admn'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_admn']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_name = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Cannot Be Empty";
    }
    if (isset($_POST['group_code']) && !empty($_POST['group_code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['group_code']));
    } else {
        $error = 1;
        $err = "Group Code Cannot Be Empty";
    }

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_StudentsGroups  WHERE  (code = '$code' AND student_admn ='$student_admn')   ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (($code  && $student_admn) == ($row['code'] && $row['student_admn'])) {
                $err = "Student Already Added To Group";
            }
        } else {
            $id = $_POST['id'];
            $group_name = $_POST['group_name'];
            $group_code = $_POST['group_code'];
            $student_admn = $_POST['student_admn'];
            $student_name = $_POST['student_name'];
            $view = $_POST['view'];/* Module ID */
            $group = $_POST['group'];/* gROUP iD */
            $faculty = $_POST['faculty'];

            $query = "INSERT INTO ezanaLMS_StudentsGroups (id, faculty_id, name, code, student_admn, student_name) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $faculty, $group_name, $group_code, $student_admn, $student_name);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Added To group"  && header("refresh:1; url=edu_admn_group_details.php?view=$view&group=$group");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

//Remove Member
if (isset($_GET['remove'])) {
    $view = $_GET['view'];
    $group = $_GET['group'];
    $remove = $_GET['remove'];
    $adn = "DELETE FROM ezanaLMS_StudentsGroups WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $remove);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=edu_admn_group_details.php?view=$view&group=$group");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

//Delete Announcements
if (isset($_GET['delete_Announcement'])) {
    $delete = $_GET['delete_Announcement'];
    $view = $_GET['view'];
    $group = $_GET['group'];
    $adn = "DELETE FROM ezanaLMS_GroupsAnnouncements WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=edu_admn_group_details.php?view=$view&group=$group");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Add Group Assignments */
if (isset($_POST['add_group_project'])) {
    $id = $_POST['id'];
    $details = $_POST['details'];
    $faculty = $_POST['faculty'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Group_Projects/" . $_FILES["attachments"]["name"]);
    $submitted_on = $_POST['submitted_on'];
    $group_code = $_POST['group_code'];
    $group_name  = $_POST['group_name'];
    /* Module ID */
    $view = $_POST['view'];
    /* Group ID */
    $group_id = $_POST['group'];

    $query = "INSERT INTO ezanaLMS_GroupsAssignments (id, faculty_id, module_id, group_code, group_name,  attachments, details, submitted_on) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssss', $id, $faculty, $view, $group_code, $group_name,  $attachments, $details, $submitted_on);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment Added" && header("refresh:1; url=edu_admn_group_details.php?view=$view&group=$group_id");
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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
            /* Group Details Based On Given Group ID */
            $GroupID = $_GET['group'];
            $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE id = '$GroupID'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($g = $res->fetch_object()) {
        ?>
                <!-- Main Sidebar Container -->
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <?php require_once('public/partials/_brand.php'); ?>
                    <!-- Sidebar -->
                    <?php require_once('public/partials/_sidebar.php');?>
                </aside>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Student Groups</h1>
                                </div>
                                <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="edu_admn_modules.php?view=<?php echo $mod->faculty_id; ?>">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?> Student Groups</li>
                                </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="text-left">
                                    <nav class="navbar navbar-light bg-light col-md-12">
                                        <form class="form-inline" action="edu_admin_module_search_result.php" method="GET">
                                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Group Announcement</button>
                                        <div class="modal fade" id="modal-default">
                                            <div class="modal-dialog  modal-xl">
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
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">Notice Posted By</label>
                                                                        <?php
                                                                        $id = $_SESSION['id'];
                                                                        $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id = '$id'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($user = $res->fetch_object()) {
                                                                        ?>
                                                                            <input type="text" required name="created_by" value="<?php echo $user->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                        <?php
                                                                        } ?>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="exampleInputPassword1">Group Notice</label>
                                                                        <textarea required name="announcement" rows="20" class="form-control Summernote"></textarea>
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                        <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                        <input type="hidden" required name="faculty" value="<?php echo $mod->faculty; ?>" class="form-control">
                                                                        <input type="hidden" required name="group" value="<?php echo $g->id; ?>" class="form-control">
                                                                        <input type="hidden" required name="group_code" value="<?php echo $g->code; ?>" class="form-control">
                                                                        <input type="hidden" required name="group_name" value="<?php echo $g->name; ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer text-right">
                                                                <button type="submit" name="add_notice" class="btn btn-primary">Add Notice</button>
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
                                    <!-- Module Side Menu -->
                                    <?php require_once('public/partials/_edu_admn_modulemenu.php'); ?>
                                    <!-- Module Side Menu -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-header">
                                                                <h3 class="card-title">
                                                                    <?php echo $g->code; ?> - <?php echo $g->name; ?> Created On <span class="text-success"> <?php echo date('d M Y g:ia', strtotime($g->created_at)); ?></span> And Updated On <span class="text-warning"><?php echo $g->updated_at; ?></span>
                                                                </h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                                    <li class="nav-item">
                                                                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Group Details</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-add_member" role="tab" aria-controls="custom-content-below-notices" aria-selected="false">Add Members</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-members" role="tab" aria-controls="custom-content-below-members" aria-selected="false">Group Members</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-notices" role="tab" aria-controls="custom-content-below-notices" aria-selected="false">Group Notices</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-notices-assignments" role="tab" aria-controls="custom-content-below-notices-assignments" aria-selected="false">Group Assignments</a>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content" id="custom-content-below-tabContent">
                                                                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                                        <br>
                                                                        <?php echo $g->details; ?>
                                                                    </div>

                                                                    <div class="tab-pane fade" id="custom-content-below-members" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                                        <br>
                                                                        <table id="example1" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Admission No</th>
                                                                                    <th>Name</th>
                                                                                    <th>Date Added</th>
                                                                                    <th>Manage</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $ret = "SELECT * FROM `ezanaLMS_StudentsGroups` WHERE code = '$g->code' ";
                                                                                $stmt = $mysqli->prepare($ret);
                                                                                $stmt->execute(); //ok
                                                                                $res = $stmt->get_result();
                                                                                $cnt = 1;
                                                                                while ($stdGroup = $res->fetch_object()) {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $stdGroup->student_admn; ?></td>
                                                                                        <td><?php echo $stdGroup->student_name; ?></td>
                                                                                        <td><?php echo date('d M Y', strtotime($stdGroup->created_at)); ?></td>
                                                                                        <td>
                                                                                            <a class="badge badge-danger" href="edu_admn_group_details.php?remove=<?php echo $stdGroup->id; ?>&group=<?php echo $g->id; ?>&view=<?php echo $mod->id; ?>">
                                                                                                <i class="fas fa-user-times"></i>
                                                                                                Remove Member
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php $cnt = $cnt + 1;
                                                                                } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div class="tab-pane fade" id="custom-content-below-add_member" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                                                        <br>
                                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="">Student Admission Number</label>
                                                                                        <!-- Hidden Values -->
                                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                        <input type="hidden" required name="group_name" value="<?php echo $g->name; ?>" class="form-control">
                                                                                        <input type="hidden" required name="group_code" value="<?php echo $g->code; ?>" class="form-control">
                                                                                        <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                        <input type="hidden" required name="group" value="<?php echo $g->id; ?>" class="form-control">
                                                                                        <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">

                                                                                        <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="student_admn">
                                                                                            <option selected>Select Admission Number</option>
                                                                                            <?php
                                                                                            /* For A Student To Join Group, He / She Mush Be Enrolled To The Module */
                                                                                            $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE module_code = '$mod->code'   ";
                                                                                            $stmt = $mysqli->prepare($ret);
                                                                                            $stmt->execute(); //ok
                                                                                            $res = $stmt->get_result();
                                                                                            while ($std = $res->fetch_object()) {
                                                                                            ?>
                                                                                                <option><?php echo $std->student_adm; ?></option>
                                                                                            <?php
                                                                                            } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="">Student Name</label>
                                                                                        <input type="text" id="StudentName" readonly required name="student_name" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-footer text-right">
                                                                                <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
                                                                            </div>
                                                                        </form>
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
                                                                                $cnt = 1;
                                                                                while ($ga = $res->fetch_object()) {
                                                                                ?>
                                                                                    <div class="d-flex w-100 justify-content-between">
                                                                                        <h5 class="mb-1"></h5>
                                                                                        <small><b><?php echo date('d M Y', strtotime($ga->created_at)); ?></b></small>
                                                                                    </div>
                                                                                    <?php
                                                                                    echo $ga->announcement;
                                                                                    ?> ~ <b> <small><?php echo $ga->created_by; ?></small></b>
                                                                                    <div class="card-footer row">
                                                                                        <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $ga->id; ?>">
                                                                                            <i class="fas fa-edit"></i>
                                                                                            Update
                                                                                        </a>
                                                                                        <!-- Udpate Notice Modal -->
                                                                                        <div class="modal fade" id="update-<?php echo $ga->id; ?>">
                                                                                            <div class="modal-dialog  modal-xl">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header">
                                                                                                        <h4 class="modal-title">Fill All Required Values </h4>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                            <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                                                                            <div class="card-body">
                                                                                                                <div class="row">
                                                                                                                    <div class="form-group col-md-12">
                                                                                                                        <label for="">Notice Posted By</label>
                                                                                                                        <input type="text" required name="created_by" value="<?php echo $ga->created_by; ?>" class="form-control" id="exampleInputEmail1">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="row">
                                                                                                                    <div class="form-group col-md-12">
                                                                                                                        <label for="exampleInputPassword1">Group Notice</label>
                                                                                                                        <textarea required name="announcement" rows="20" class="form-control Summernote"><?php echo $ga->announcement; ?></textarea>
                                                                                                                        <!-- Hide This -->
                                                                                                                        <input type="hidden" required name="id" value="<?php echo $ga->id; ?>" class="form-control">
                                                                                                                        <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                                                        <input type="hidden" required name="group" value="<?php echo $g->id; ?>" class="form-control">

                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="card-footer text-right">
                                                                                                                <button type="submit" name="update_notice" class="btn btn-primary">Update Notice</button>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                    <div class="modal-footer justify-content-between">
                                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $ga->id; ?>">
                                                                                            <i class="fas fa-trash"></i>
                                                                                            Delete
                                                                                        </a>
                                                                                        <!-- Delete Confirmation Modal -->
                                                                                        <div class="modal fade" id="delete-<?php echo $ga->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                            <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body text-center text-danger">
                                                                                                        <h4>Delete Announcement ?</h4>
                                                                                                        <br>
                                                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                                        <a href="edu_admn_group_details.php?delete_Announcement=<?php echo $ga->id; ?>&view=<?php echo $mod->id; ?>&group=<?php echo $g->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- End Delete Confirmation Modal -->
                                                                                    </div>
                                                                                    <hr>
                                                                                <?php $cnt = $cnt + 1;
                                                                                } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade show " id="custom-content-below-notices-assignments" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                                        <br>
                                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <!-- Hide This Please -->
                                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                    <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                    <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                                    <input type="hidden" required name="group_name" value="<?php echo $g->name; ?>" class="form-control">
                                                                                    <input type="hidden" required name="group_code" value="<?php echo $g->code; ?>" class="form-control">
                                                                                    <input type="hidden" required name="group" value="<?php echo $g->id; ?>" class="form-control">
                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="exampleInputPassword1">Submission Date </label>
                                                                                        <input type="date" required name="submitted_on" class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <label for="">Upload Group Assignment (PDF Or Docx)</label>
                                                                                        <div class="input-group">
                                                                                            <div class="custom-file">
                                                                                                <input name="attachments" accept=".pdf, .doc, .docx" type="file" class="custom-file-input">
                                                                                                <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-12">
                                                                                        <label for="exampleInputPassword1">Instructions</label>
                                                                                        <textarea name="details" required rows="5" class="form-control Summernote"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-footer text-right">
                                                                                <button type="submit" name="add_group_project" class="btn btn-primary">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
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
                    <?php require_once('public/partials/_footer.php');
                } ?>
                    </div>
                </div>
                <!-- ./wrapper -->
                <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>