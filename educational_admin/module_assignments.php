<?php
/*
 * Created on Thu Jun 24 2021
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
admin_checklogin();
require_once('../config/codeGen.php');
$time = time();

/* Add Assignment */
if (isset($_POST['add_assignment'])) {
    $faculty = $_POST['faculty'];
    $module_name = $_POST['module_name'];
    $id = $_POST['id'];
    $module_code = $_POST['module_code'];
    $submission_deadline = $_POST['submission_deadline'];
    $attachments = $time . $_FILES['attachments']['name'];
    $title = $_POST['title'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "../Data/Module_Assignments/" . $time . $_FILES["attachments"]["name"]);

    $query = "INSERT INTO ezanaLMS_ModuleAssignments (id, title, faculty, module_code, module_name, submission_deadline, attachments) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $title, $faculty, $module_code, $module_name, $submission_deadline, $attachments);
    $stmt->execute();
    if ($stmt) {
        $success = "$title Uploaded";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


/* Update Assignment */
if (isset($_POST['update_assignment'])) {

    $id = $_POST['id'];
    $submission_deadline = $_POST['submission_deadline'];
    $attachments = $time . $_FILES['attachments']['name'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    $title = $_POST['title'];

    move_uploaded_file($_FILES["attachments"]["tmp_name"], "../Data/Module_Assignments/" . $time . $_FILES["attachments"]["name"]);
    $query = "UPDATE ezanaLMS_ModuleAssignments SET  title = ?, submission_deadline = ?, attachments =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $title, $submission_deadline, $attachments, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "$title  Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Assignment */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_ModuleAssignments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=module_assignments?view=$view");
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
                                    <li class="breadcrumb-item"><a href="modules">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12 text-center">
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Assignments</h1>
                                <br>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Upload Assignment</button>
                            </div>
                            <!-- Add Asssignment Modal -->
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
                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                    <input type="hidden" name="module_name" value="<?php echo $mod->name; ?>" class="form-control">
                                                    <input type="hidden" name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                    <input type="hidden" name="module_code" value="<?php echo $mod->code; ?>" class="form-control">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="">Assignment Title</label>
                                                            <input type="text" name="title" required class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Submission Deadline</label>
                                                            <input type="text" name="submission_deadline" required class="availability form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputFile">Upload Assignment ( PDF / Docx )</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input required name="attachments" type="file" class="custom-file-input" id="exampleInputFile">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_assignment" class="btn btn-primary">Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->

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
                                                            <th data-toggle="true">Title</th>
                                                            <th data-toggle="true">Date Uploaded</th>
                                                            <th data-toggle="true">Submission Deadline</th>
                                                            <th data-hide="all">Manage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_ModuleAssignments` WHERE module_code = '$mod->code'   ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($assignments = $res->fetch_object()) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $assignments->title; ?></td>
                                                                <td><?php echo date('d M Y - g:ia', strtotime($assignments->created_at)); ?></td>
                                                                <td><?php echo date('d M Y - g:ia', strtotime($assignments->submission_deadline)); ?></td>
                                                                <td>
                                                                    <a target="_blank" href="../Data/Module_Assignments/<?php echo $assignments->attachments; ?>" class="badge badge-secondary">
                                                                        <i class="fas fa-eye"></i>
                                                                        View
                                                                    </a>
                                                                    <a href="module_assignments_attemps?view=<?php echo $mod->id; ?>&assignment=<?php echo $assignments->id; ?>" class="badge badge-primary">
                                                                        <i class="fas fa-check"></i>
                                                                        Student Attempts
                                                                    </a>
                                                                    <a class="badge badge-warning" data-toggle="modal" href="#edit-<?php echo $assignments->id; ?>">
                                                                        <i class="fas fa-edit"></i>
                                                                        Update
                                                                    </a>
                                                                    <!-- Update Modal -->
                                                                    <div class="modal fade" id="edit-<?php echo $assignments->id; ?>">
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
                                                                                            <input type="hidden" required name="id" value="<?php echo $assignments->id; ?>" class="form-control">
                                                                                            <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            <input type="hidden" name="module_name" value="<?php echo $mod->name; ?>" class="form-control">
                                                                                            <input type="hidden" name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                                            <input type="hidden" name="module_code" value="<?php echo $mod->code; ?>" class="form-control">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">Assignment Title</label>
                                                                                                    <input type="text" name="title" value="<?php echo $assignments->title; ?>" required class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Submission Deadline</label>
                                                                                                    <input type="text" name="submission_deadline" value="<?php echo $assignments->submission_deadline; ?>" required class="availability form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="exampleInputFile">Upload Assignment ( PDF / Docx )</label>
                                                                                                    <div class="input-group">
                                                                                                        <div class="custom-file">
                                                                                                            <input required name="attachments" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_assignment" class="btn btn-primary">Upload</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Update Modal -->
                                                                    <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $assignments->id; ?>">
                                                                        <i class="fas fa-trash"></i>
                                                                        Delete
                                                                    </a>
                                                                    <!-- Delete Confirmation Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $assignments->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                    <a href="module_assignments?delete=<?php echo $assignments->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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