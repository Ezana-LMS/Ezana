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
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $readingMaterials = $_FILES['readingMaterials']['name'];
        move_uploaded_file($_FILES["readingMaterials"]["tmp_name"], "public/uploads/EzanaLMSData/Reading_Materials/" . $_FILES["readingMaterials"]["name"]);
        $external_link = $_POST['external_link'];
        $faculty = $_POST['faculty'];
        /* Module ID  */
        $view = $_POST['view'];

        $query = "INSERT INTO ezanaLMS_ModuleRecommended (id, visibility, faculty_id, module_name, module_code, readingMaterials, external_link) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $visibility,  $faculty, $module_name, $module_code, $readingMaterials, $external_link);
        $stmt->execute();
        if ($stmt) {
            $success = "Reading Materials Shared" && header("refresh:1; url=course_materials.php?view=$view");
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
    /* Module ID  */
    $view = $_POST['view'];
    $query = "UPDATE ezanaLMS_ModuleRecommended SET visibility =?, external_link =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $visibility, $external_link, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Reading Materials Updated" && header("refresh:1; url=course_materials.php?view=$view");
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
        $success = "Deleted" && header("refresh:1; url=course_materials.php?view=$view");
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
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        System Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports.php" class="nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Course Materials</h1>
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
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Reading Materials</button>
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
                                                                <div class="form-group col-md-4">
                                                                    <label for="">Reading Materials Visibility</label>
                                                                    <select class='form-control basic' name="visibility">
                                                                        <option selected>Available</option>
                                                                        <option>Hidden</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputPassword1">Hyperlink | Extenal Link</label>
                                                                    <input type="text" name="external_link" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputFile">Reading Materials (PDF, DOCX, PPTX)</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="readingMaterials" type="file" class="custom-file-input" id="exampleInputFile">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_reading_materials" class="btn btn-primary">Add Reading Materials</button>
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
                                <?php require_once('public/partials/_modulemenu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="row">
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE module_code ='$mod->code' ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($rm = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-4">
                                                        <div class="card">
                                                            <a href="reading_materials_viewer.php?id=<?php echo $rm->id; ?>&view=<?php echo $view; ?>" target="_blank">
                                                                <div class="card-body">
                                                                    <p class="card-title">
                                                                        <?php
                                                                        /* Trim Topic */
                                                                        if (strlen($rm->readingMaterials) > 30) {
                                                                            $trimstring = substr($rm->readingMaterials, 0, 30) . '...';
                                                                        } else {
                                                                            $trimstring = $rm->readingMaterials;;
                                                                        }
                                                                        echo $trimstring
                                                                        ?>
                                                                    </p>
                                                                    <br>
                                                                    <hr>
                                                                    <div class="text-center">
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
                                                            </a>
                                                            <div class="card-footer">
                                                                <small class="text-muted">Uploaded: <?php echo $rm->created_at; ?></small>
                                                                <br>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-visibility-<?php echo $rm->id; ?>">Edit Visiblity</a>
                                                                <!-- Upload Solution Modal -->
                                                                <div class="modal fade" id="edit-visibility-<?php echo $rm->id; ?>">
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
                                                                                            <input type="hidden" required name="id" value="<?php echo $rm->id; ?>" class="form-control">
                                                                                            <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            <div class="form-group col-md-4">
                                                                                                <label for="">Reading Materials Visibility</label>
                                                                                                <select class='form-control basic' name="visibility">
                                                                                                    <option selected><?php echo $rm->visibility; ?></option>
                                                                                                    <option>Available</option>
                                                                                                    <option>Hidden</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-md-8">
                                                                                                <label for="exampleInputPassword1">Hyperlink | Extenal Link</label>
                                                                                                <input type="text" name="external_link" value="<?php echo $rm->external_link; ?>" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <!-- <div class="form-group col-md-6">
                                                                                                <label for="exampleInputFile">Reading Materials (PDF, DOCX, PPTX)</label>
                                                                                                <div class="input-group">
                                                                                                    <div class="custom-file">
                                                                                                        <input required name="readingMaterials" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div> -->
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_reading_materials" class="btn btn-primary">Update Reading Materials</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                                                                                <h4>Delete <?php echo $rm->readingMaterials; ?> ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="course_materials.php?delete=<?php echo $rm->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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