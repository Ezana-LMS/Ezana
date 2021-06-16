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
$time = date("d-M-Y") . "-" . time();

/* Add Module Notice */
if (isset($_POST['add_notice'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['announcements']) && !empty($_POST['announcements'])) {
        $announcements = mysqli_real_escape_string($mysqli, trim($_POST['announcements']));
    } else {
        $error = 1;
        $err = "Noctices Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $faculty_id = $_POST['faculty_id'];
        $module_id = $_POST['module_id'];

        $attachments = $time.$_FILES['attachments']['name'];
        move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $time. $_FILES["attachments"]["name"]);
        $query = "INSERT INTO ezanaLMS_ModulesAnnouncements (id, module_name, module_code, announcements, created_by,attachments, faculty_id) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $module_name, $module_code, $announcements, $created_by, $attachments, $faculty_id);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated" && header("refresh:1; url=lec_module_notices.php?view=$module_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Update Module Notice */
if (isset($_POST['update_notice'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['announcements']) && !empty($_POST['announcements'])) {
        $announcements = mysqli_real_escape_string($mysqli, trim($_POST['announcements']));
    } else {
        $error = 1;
        $err = "Notices Cannot Be Empty";
    }
    if (!$error) {
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $module_id = $_POST['module_id'];
        $attachments = $time. $_FILES['attachments']['name'];
        move_uploaded_file($_FILES["attachments"]["tmp_name"], "public/uploads/EzanaLMSData/memos/" . $time. $_FILES["attachments"]["name"]);
        $id = $_POST['id'];
        $module_id = $_POST['module_id'];
        $query = "UPDATE  ezanaLMS_ModulesAnnouncements SET announcements =?, created_by =?, attachments =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss', $announcements, $created_by, $attachments, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Updated" && header("refresh:1; url=lec_module_notices.php?view=$module_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Module Notice */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_ModulesAnnouncements WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=lec_module_notices.php?view=$view");
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Notices & Memos</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="lec_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="lec_allocated_modules.php">Allocated Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?> Notices</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" method="GET">
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Module Notice</button>
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
                                                    <!-- Add Module Notices Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Announcement Posted By</label>
                                                                    <?php
                                                                    $id = $_SESSION['id'];
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($user = $res->fetch_object()) {
                                                                    ?>
                                                                        <input type="text" required name="created_by" value="<?php echo $user->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                    <?php
                                                                    } ?>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="">Upload Module Memo (PDF Or Docx)</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h2 class="text-center">Or</h2>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Type Module Announcements</label>
                                                                    <textarea required name="announcements" rows="20" class="form-control Summernote"></textarea>
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                    <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_notice" class="btn btn-primary">Post</button>
                                                        </div>
                                                    </form>
                                                    <!-- End Module Notice Form -->
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
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card ">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Module Notices, Announcements And Memos</h3>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements` WHERE module_code  = '$mod->code'  ORDER BY `ezanaLMS_ModulesAnnouncements`.`created_at` DESC  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($not = $res->fetch_object()) {
                                                                ?>
                                                                    <div class="d-flex w-100 justify-content-between">
                                                                        <h5 class="mb-1"></h5>
                                                                        <small class="text-bold"><?php echo date('d M Y g:ia', strtotime($not->created_at)); ?></small>
                                                                    </div>
                                                                    <?php
                                                                    echo
                                                                    $not->announcements . "  ~ By " .
                                                                        "<b> " . $not->created_by . " </b> ";
                                                                    /* Show A Button To Download Attachment */
                                                                    if ($not->attachments != '') {
                                                                        echo
                                                                        "   <hr>
                                                                                <div class='text-center'>
                                                                                    <a href='public/uploads/EzanaLMSData/memos/$not->attachments' target='_blank' class='btn btn-outline-success'>Download Memo Attachment</a>
                                                                                </div>
                                                                            ";
                                                                    } else {
                                                                        /* Nothing Just Be Dumb */
                                                                    } ?>
                                                                    <br>
                                                                    <div class="card-footer">
                                                                        <div class="row ">
                                                                            <a class="badge badge-primary text-right" data-toggle="modal" href="#update-<?php echo $mod->id; ?>">
                                                                                <i class="fas fa-edit"></i>
                                                                                Update
                                                                            </a>
                                                                            <!-- Udpate Notice Modal -->
                                                                            <div class="modal fade" id="update-<?php echo $mod->id; ?>">
                                                                                <div class="modal-dialog  modal-xl">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h4 class="modal-title">Fill All Required Values </h4>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <!-- Add Module Notices Form -->
                                                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                                                <div class="card-body">
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Module Name</label>
                                                                                                            <input readonly type="text" value="<?php echo $not->module_name; ?>" id="ModuleCode" required name="module_code" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Module Code</label>
                                                                                                            <input readonly type="text" id="ModuleCode" value="<?php echo $not->module_code; ?>" required name="module_code" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Announcement Posted By</label>
                                                                                                            <input type="text" required name="created_by" value="<?php echo $not->created_by; ?>" class="form-control" id="exampleInputEmail1">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Upload Module Memo (PDF Or Docx)</label>
                                                                                                            <div class="input-group">
                                                                                                                <div class="custom-file">
                                                                                                                    <input name="attachments" accept=".pdf, .docx, .doc" type="file" class="custom-file-input">
                                                                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-12">
                                                                                                            <label for="exampleInputPassword1">Module Announcements</label>
                                                                                                            <textarea required name="announcements" rows="20" class="form-control Summernote"><?php echo $not->announcements; ?></textarea>
                                                                                                            <input type="hidden" required name="id" value="<?php echo $not->id; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>


                                                                                                <div class="card-footer text-right">
                                                                                                    <button type="submit" name="update_notice" class="btn btn-primary">Update</button>
                                                                                                </div>
                                                                                            </form>
                                                                                            <!-- End Module Notice Form -->
                                                                                        </div>
                                                                                        <div class="modal-footer justify-content-between">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <a class="badge badge-danger" href="#delete-<?php echo $not->id; ?>" data-toggle="modal">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </a>
                                                                            <!-- Delete Confirmation Modal -->
                                                                            <div class="modal fade" id="delete-<?php echo $not->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                                            <a href="lec_module_notices.php?delete=<?php echo $not->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- End Delete Confirmation Modal -->
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                <?php
                                                                } ?>
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
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>