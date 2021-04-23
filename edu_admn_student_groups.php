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

/* Add Students Groups  */
if (isset($_POST['add_group'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Group Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Group Name Cannot Be Empty";
    }
    /* 
    if (isset($_POST['student_admn']) && !empty($_POST['student_admn'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_admn']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Number Cannot Be Empty";
    } */

    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Groups WHERE  code='$code'   ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Group With This Code Already Exists";
            } /* elseif (($code  && $student_admn) == ($row['code'] && $row['student_admn'])) {
                $err = "Student Already Added To Group";
            } */
        } else {
            $id = $_POST['id'];
            $module_id = $_POST['module_id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $faculty = $_POST['faculty_id'];

            $query = "INSERT INTO ezanaLMS_Groups (id, module_id, faculty_id, name, code, details) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $module_id, $faculty, $name, $code, $details);
            $stmt->execute();
            if ($stmt) {
                $success = "Student Group  Added" && header("refresh:1; url=edu_admn_student_groups.php?view=$module_id");
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/* Update Students Groups  */
if (isset($_POST['update_group'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Group Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Group Name Cannot Be Empty";
    }
    /* 
    if (isset($_POST['student_admn']) && !empty($_POST['student_admn'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_admn']));
    } else {
        $error = 1;
        $err = "Student Admission Number Cannot Be Empty";
    }
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_admn = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name Number Cannot Be Empty";
    } */

    if (!$error) {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $updated_at = date('d M Y');
        $details = $_POST['details'];
        $view = $_GET['view'];

        $query = "UPDATE ezanaLMS_Groups SET name =?, code =?, updated_at=?, details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss', $name, $code, $updated_at, $details, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Student Group  Updated" && header("refresh:1; url=edu_admn_student_groups.php?view=$view");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Students Groups  */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Groups WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=edu_admn_student_groups.php?view=$view");
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
                            <div class="">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="edu_admn_module_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <div class="text-right">
                                        <a href="edu_admn_student_groups_card.php?view=<?php echo $mod->id; ?>" title="View <?php echo $mod->name; ?> Student Groups In List Formart" class="btn btn-primary"><i class="fas fa-list-alt"></i></a>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Create Group</button>
                                    </div>
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
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Group Name</label>
                                                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Group Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Group Description</label>
                                                                    <textarea required name="details" rows="10" class="form-control Summernote"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_group" class="btn btn-primary">Add Group</button>
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
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Group Code Number</th>
                                                        <th>Group Name</th>
                                                        <th>Created At</th>
                                                        <th>Manage Groups</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE module_id = '$mod->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($g = $res->fetch_object()) {
                                                    ?>

                                                        <tr>
                                                            <td><?php echo $g->code; ?></td>
                                                            <td><?php echo $g->name; ?></td>
                                                            <td><?php echo date('d M Y, g:ia', strtotime($g->created_at)); ?></td>
                                                            <td>
                                                                <a class="badge badge-success" href="edu_admn_group_details.php?group=<?php echo $g->id; ?>&view=<?php echo $mod->id; ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                    View
                                                                </a>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-group-<?php echo $g->id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Edit
                                                                </a>
                                                                <div class="modal fade" id="edit-group-<?php echo $g->id; ?>">
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
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Group Name</label>
                                                                                                <input type="text" value="<?php echo $g->name; ?>" required name="name" class="form-control" id="exampleInputEmail1">
                                                                                                <input type="hidden" required name="id" value="<?php echo $g->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">

                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Group Number / Code</label>
                                                                                                <input type="text" required name="code" value="<?php echo $g->code; ?>" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Group Description</label>
                                                                                                <textarea required name="details" rows="10" class="form-control Summernote"><?php echo $g->details; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_group" class="btn btn-primary">Update Group</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a class="badge badge-danger" href="#delete-<?php echo $g->id; ?>" data-toggle="modal">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete
                                                                </a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $g->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $g->name; ?> ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="edu_admn_student_groups.php?delete=<?php echo $g->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('public/partials/_footer.php');
                    ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>