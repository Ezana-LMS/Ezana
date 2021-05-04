<?php
/*
 * Created on Tue May 04 2021
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
std_check_login();
require_once('configs/codeGen.php');

/* Add Student Grades */
if (isset($_POST['add_attempt'])) {
    $id = $_POST['id'];
    $assignment_id = $_POST['assignment_id'];
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $std_name = $_POST['std_name'];
    $std_regno = $_POST['std_regno'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Reading_Materials/" . $_FILES["readingMaterials"]["name"]);

    /* Module ID */
    $module_id = $_POST['module_id'];

    $query = "INSERT INTO ezanaLMS_AssignmentsAttempts (id, assignment_id, module_name, module_code, std_name, std_regno, attachments) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $assignment_id, $module_name, $module_code, $std_name, $std_regno, $attachments);
    $stmt->execute();
    if ($stmt) {
        $success = "Assignment Submitted" &&  header("Refresh:0");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

if (isset($_POST['update_attempt'])) {
    $id = $_POST['id'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/Reading_Materials/" . $_FILES["attachments"]["name"]);

    /* Module ID */
    $module_id = $_POST['module_id'];

    $query = "UPDATE ezanaLMS_AssignmentsAttempts SET  attachments WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $attachments, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Assignment Submitted Updated" &&  header("Refresh:0");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


/* Delete Student Attempt */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_AssignmentsAttempts WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("Refresh:0");
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
        require_once('public/partials/_std_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
            /* Student Admission Number */
            $id  = $_SESSION['id'];
            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($std = $res->fetch_object()) {
        ?>
                <!-- /.navbar -->
                <!-- Main Sidebar Container -->
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <?php require_once('public/partials/_brand.php'); ?>
                    <!-- Sidebar -->
                    <?php require_once('public/partials/_std_sidebar.php'); ?>
                </aside>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <hr>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Assignments Attempts</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="std_dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="std_enrolled_modules.php">Enrolled Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?> Assignment Attempts</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <hr>
                                <div class="row">
                                    <!-- Module Side Menu -->
                                    <?php require_once('public/partials/_std_modulemenu.php'); ?>
                                    <!-- Module Side Menu -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="card-box">
                                                    <div class="text-left">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Assignment Attempt</button>
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
                                                                                        <label for="exampleInputFile">Reading Materials (PDF, DOCX, PPTX)</label>
                                                                                        <div class="input-group">
                                                                                            <div class="custom-file">
                                                                                                <input required name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input" id="exampleInputFile">\
                                                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                                <input type="hidden" required name="assignment_id" value="<?php echo $_GET['assignment']; ?>" class="form-control">
                                                                                                <input type="hidden" required name="std_name" value="<?php echo $std->name; ?>" class="form-control">
                                                                                                <input type="hidden" required name="std_regno" value="<?php echo $std->admno; ?>" class="form-control">
                                                                                                <input type="text" readonly value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">

                                                                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-footer text-right">
                                                                                <button type="submit" name="add_attempt" class="btn btn-primary">Add Reading Materials</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="table-responsive">
                                                        <table id="example1" class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th data-toggle="true">Student Details</th>
                                                                    <th>Module Details</th>
                                                                    <th>Attachment File</th>
                                                                    <th data-hide="all">Manage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $assignment_id = $_GET['assignment'];
                                                                $ret = "SELECT * FROM `ezanaLMS_AssignmentsAttempts` WHERE assignment_id = '$assignment_id' AND std_regno = '$std->admno'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($attempts = $res->fetch_object()) {
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $attempts->std_regno . " " . $attempts->std_name; ?></td>
                                                                        <td><?php echo $attempts->module_code . " " . $attempts->module_name; ?></td>
                                                                        <td>
                                                                            <a target="_blank" href="public/uploads/EzanaLMSData/Module_Assignments_Attempts/<?php echo $attempts->attachments; ?>" class="badge badge-secondary">
                                                                                <i class="fas fa-download"></i>
                                                                                Download Attachment
                                                                            </a>
                                                                            <a class="badge badge-primary" data-toggle="modal" href="#edit-<?php echo $attempts->id; ?>">
                                                                                <i class="fas fa-edit"></i>
                                                                                Update Attempt
                                                                            </a>
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
                                                                                                            <label for="exampleInputFile">Attempted Assignment</label>
                                                                                                            <div class="input-group">
                                                                                                                <div class="custom-file">
                                                                                                                    <input required name="attachment" accept=".pdf, .docx, .doc" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                                                    <input type="hidden" required name="id" value="<?php echo $attempts->id; ?>" class="form-control">
                                                                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="card-footer text-right">
                                                                                                    <button type="submit" name="update_attempt" class="btn btn-primary">Update Assignment Attempt</button>
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
                                                                                            <a href="std_module_assignments_attemps.php?delete=<?php echo $attempts->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
            }
        } ?>
                    </div>
                </div>
                <!-- ./wrapper -->
                <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>