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
require_once('../config/lec_checklogin.php');
lec_check_login();
require_once('../config/codeGen.php');
$time = time();

/* Add Course Materials */
if (isset($_POST['add_reading_materials'])) {
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
    if (!$error) {
        $id = $_POST['id'];
        $visibility = $_POST['visibility'];
        $readingMaterials = $time . $_FILES['readingMaterials']['name'];
        move_uploaded_file($_FILES["readingMaterials"]["tmp_name"], "../Data/Reading_Materials/" . $time . $_FILES["readingMaterials"]["name"]);
        $external_link = $_POST['external_link'];
        $faculty = $_POST['faculty'];
        $topic = $_POST['topic'];
        /* Module ID  */
        $view = $_POST['view'];

        $query = "INSERT INTO ezanaLMS_ModuleRecommended (id, visibility, faculty_id, module_name, module_code, readingMaterials, external_link, topic) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $id, $visibility,  $faculty, $module_name, $module_code, $readingMaterials, $external_link, $topic);
        $stmt->execute();
        if ($stmt) {
            $success = "$topic Reading Materials Shared";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Update Course Materials  */
if (isset($_POST['update_reading_materials'])) {
    $id = $_POST['id'];
    $visibility = $_POST['visibility'];
    /* $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $readingMaterials = $_FILES['readingMaterials']['name'];
        move_uploaded_file($_FILES["readingMaterials"]["tmp_name"], "public/uploads/EzanaLMSData/Reading_Materials/" . $_FILES["readingMaterials"]["name"]); */
    $external_link = $_POST['external_link'];
    $topic = $_POST['topic'];
    /* Module ID  */
    $view = $_POST['view'];
    $query = "UPDATE ezanaLMS_ModuleRecommended SET visibility =?, external_link =?, topic = ? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $visibility, $external_link, $topic, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "$topic Reading Materials Updated";
    } else {
        $info = "Please Try Again Or Try Later";
    }
}


/* Delete Course Materials */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_ModuleRecommended WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=module_lecture_materials?view=$view");
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
                                <h1 class="m-0 text-bold"><?php echo $mod->name; ?> Reading Materials</h1>
                                <br>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Reading Materials</button>
                            </div>

                            <!-- Add Lecture Materials Modal-->
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
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row" style="display: none;">
                                                        <div class="form-group col-md-4">
                                                            <label for="">Module Name</label>
                                                            <input type="text" readonly value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                            <!-- hIDDEN -->
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                            <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="">Module Code</label>
                                                            <input type="text" readonly value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Reading Materials Visibility</label>
                                                            <select class='form-control basic' name="visibility">
                                                                <option selected>Available</option>
                                                                <option>Hidden</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputFile">Reading Materials (PDF, DOCX, PPTX)</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input required name="readingMaterials" accept=".pdf, .docx, .doc, pptx" type="file" class="custom-file-input" id="exampleInputFile">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputPassword1">Lecture Topic</label>
                                                            <input type="text" required name="topic" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputPassword1">Hyperlink | Extenal Link</label>
                                                            <input type="text" name="external_link" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_reading_materials" class="btn btn-primary">Add Reading Materials</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Lecturer Materials Modal -->
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('partials/module_menu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="row">
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE module_code ='$mod->code'  ORDER BY `created_at` ASC  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($rm = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-4">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body">
                                                                <p class="card-title">
                                                                    <?php
                                                                    /* Trim Topic */
                                                                    if (strlen($rm->topic) > 30) {
                                                                        $trimstring = substr($rm->topic, 0, 30) . '...';
                                                                    } else {
                                                                        $trimstring = $rm->topic;;
                                                                    }
                                                                    echo $trimstring
                                                                    ?>
                                                                </p>
                                                                <br>
                                                                <hr>
                                                                <div class="text-center">
                                                                    <a target='_blank' href='../Data/Reading_Materials/<?php echo $rm->readingMaterials; ?>' class='btn btn-outline-success'>
                                                                        View
                                                                    </a>
                                                                    <?php
                                                                    /* Show External Link */
                                                                    if ($rm->external_link == '') {
                                                                        /* Yall Know Silence Is Best Answer */
                                                                    } else {
                                                                        echo
                                                                        "
                                                                        <a target='_blank' href= '$rm->external_link' class='btn btn-outline-success'>
                                                                            Open Link
                                                                        </a>
                                                                        ";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <small class="text-muted">
                                                                    Uploaded On : <?php echo date('d-M-Y g:ia', strtotime($rm->created_at));
                                                                                    if ($rm->visibility == 'Hidden') {
                                                                                        echo "<span class='text-right badge badge-primary'>$rm->visibility</span>";
                                                                                    } else {
                                                                                        /* Nothing */
                                                                                    }; ?>
                                                                </small>
                                                                <br>
                                                                <a class="badge badge-success" target="_blank" href="../Data/Reading_Materials/<?php echo $rm->readingMaterials; ?>">Download</a>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-visibility-<?php echo $rm->id; ?>">Edit Visiblity</a>
                                                                <!-- Upload Solution Modal -->
                                                                <div class="modal fade" id="edit-visibility-<?php echo $rm->id; ?>">
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
                                                                                            <input type="hidden" required name="id" value="<?php echo $rm->id; ?>" class="form-control">
                                                                                            <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Lecture Topic</label>
                                                                                                <input type="text" required name="topic" value="<?php echo $rm->topic; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Reading Materials Visibility</label>
                                                                                                <select class='form-control basic' name="visibility">
                                                                                                    <option>Available</option>
                                                                                                    <option>Hidden</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-md-8">
                                                                                                <label for="exampleInputPassword1">Hyperlink | Extenal Link</label>
                                                                                                <input type="text" name="external_link" value="<?php echo $rm->external_link; ?>" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <button type="submit" name="update_reading_materials" class="btn btn-primary">Update Reading Materials</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End  Modal -->
                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $rm->id; ?>">Delete</a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $rm->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h5>Delete <b><?php echo $rm->topic; ?></b> Lecture Materials ?</h5>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="module_lecture_materials?delete=<?php echo $rm->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                    <?php require_once('partials/footer.php');
                    ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('partials/scripts.php');
        } ?>
</body>

</html>