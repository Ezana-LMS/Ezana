<?php
/*
 * Created on Mon Apr 26 2021
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
lec_check_login();
require_once('configs/codeGen.php');

/* Add Assignment */
if (isset($_POST['add_assignment'])) {

    $faculty = $_POST['faculty'];
    $module_name = $_POST['module_name'];
    $id = $_POST['id'];
    $module_code = $_POST['module_code'];
    $submission_deadline = $_POST['submission_deadline'];
    $attachments = $_FILES['attachments']['name'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Module_Assignments/" . $_FILES["attachments"]["name"]);

    $query = "INSERT INTO ezanaLMS_ModuleAssignments (id, faculty, module_code, module_name, submission_deadline, attachments) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss', $id, $faculty, $module_code, $module_name, $submission_deadline, $attachments);
    $stmt->execute();
    if ($stmt) {
        $success = "Assignment Uploaded" && header("refresh:1; url=lec_module_assignments.php?view=$module_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}



/* Update Assignment */
if (isset($_POST['update_assignment'])) {

    $id = $_POST['id'];
    $submission_deadline = $_POST['submission_deadline'];
    $attachments = $_FILES['attachments']['name'];
    /* Module ID */
    $module_id = $_POST['module_id'];

    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Module_Assignments/" . $_FILES["attachments"]["name"]);
    $query = "UPDATE ezanaLMS_ModuleAssignments SET submission_deadline = ?, attachments =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $submission_deadline, $attachments, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Assignment Updated" && header("refresh:1; url=lec_module_assignments.php?view=$module_id");
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
        $success = "Deleted" && header("refresh:1; url=lec_module_assignments.php?view=$view");
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
        require_once('public/partials/_lec_nav.php');
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
                <?php require_once('public/partials/_lec_sidebar.php'); ?>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Assignments</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="lec_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="lec_allocated_modules.php">Allocated Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" method="GET">
                                    </form>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Upload Assignment</button>
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
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                            <input type="hidden" name="module_name" value="<?php echo $mod->name; ?>" class="form-control">
                                                            <input type="hidden" name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                            <input type="hidden" name="module_code" value="<?php echo $mod->code; ?>" class="form-control">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Submission Deadline</label>
                                                                    <input type="date" name="submission_deadline" required class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputFile">Upload Assignment ( PDF / Docx )</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input" id="exampleInputFile">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_assignment" class="btn btn-primary">Upload</button>
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
                                <?php require_once('public/partials/_lec_modulemenu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card-box">
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
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
                                                                    <td><?php echo date('d M Y - g:i', strtotime($assignments->created_at)); ?></td>
                                                                    <td><?php echo date('d M Y', strtotime($assignments->submission_deadline)); ?></td>
                                                                    <td>
                                                                        <a target="_blank" href="public/uploads/EzanaLMSData/Module_Assignments/<?php echo $assignments->attachments; ?>" class="badge badge-secondary">
                                                                            <i class="fas fa-download"></i>
                                                                            Download
                                                                        </a>
                                                                        <a href="lec_module_assignments_attemps.php?view=<?php echo $mod->id; ?>&assignment=<?php echo $assignments->id; ?>" class="badge badge-primary">
                                                                            <i class="fas fa-check"></i>
                                                                            Student Attemps
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
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Submission Deadline</label>
                                                                                                        <input type="date" name="submission_deadline" value="<?php echo $assignments->submission_deadline; ?>" required class="form-control">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="exampleInputFile">Upload Assignment ( PDF / Docx )</label>
                                                                                                        <div class="input-group">
                                                                                                            <div class="custom-file">
                                                                                                                <input required name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="card-footer text-right">
                                                                                                <button type="submit" name="update_assignment" class="btn btn-primary">Upload</button>
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
                                                                                        <a href="lec_module_assignments.php?delete=<?php echo $assignments->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                <?php require_once('public/partials/_footer.php');
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>