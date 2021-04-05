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

/* Add Past papers */
if (isset($_POST['add_paper'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (!$error) {
        $faculty = $_POST['faculty'];
        $module_name = $_POST['module_name'];
        $id = $_POST['id'];
        $course_name = $_POST['course_name'];
        $paper_name = $_POST['paper_name'];
        $paper_visibility = $_POST['paper_visibility'];
        $pastpaper = $_FILES['pastpaper']['name'];
        /* Module ID */
        $module_id = $_POST['module_id'];
        move_uploaded_file($_FILES["pastpaper"]["tmp_name"], "public/uploads/EzanaLMSData/PastPapers/" . $_FILES["pastpaper"]["name"]);

        $query = "INSERT INTO ezanaLMS_PastPapers (id, paper_name, paper_visibility, faculty_id, course_name, module_name,  pastpaper) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $paper_name, $paper_visibility, $faculty, $course_name, $module_name, $pastpaper);
        $stmt->execute();
        if ($stmt) {
            $success = "Past Paper Uploaded" && header("refresh:1; url=pastpapers.php?view=$module_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Upload Solution */
if (isset($_POST['upload_solution'])) {
    $id = $_POST['id'];
    $solution_visibility = $_POST['solution_visibility'];
    $solution = $_FILES['solution']['name'];
    /* Module ID */
    $module_id = $_POST['module_id'];
    move_uploaded_file($_FILES["solution"]["tmp_name"], "public/uploads/EzanaLMSData/PastPapers/" . $_FILES["solution"]["name"]);
    $query = "UPDATE ezanaLMS_PastPapers SET solution_visibility = ?, solution =? WHERE id = ?  ";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $solution_visibility, $solution, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Past Paper Solution Uploaded" && header("refresh:1; url=pastpapers.php?view=$module_id");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Update past paper */
if (isset($_POST['update_pastpaper'])) {
    $view = $_POST['view'];
    $id = $_POST['id'];
    $paper_name = $_POST['paper_name'];
    $paper_visibility = $_POST['paper_visibility'];
    $solution_visibility = $_POST['solution_visibility'];

    $query = "UPDATE ezanaLMS_PastPapers SET  paper_name = ?, paper_visibility = ?, solution_visibility = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $paper_name, $paper_visibility, $solution_visibility, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Past Paper Updated" && header("refresh:1; url=pastpapers.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

/* Delete Past paper */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_PastPapers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=pastpapers.php?view=$view");
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Past Papers</h1>
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
                            <div class="">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="module_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <div class="text-right">
                                        <a title="View <?php echo $mod->name; ?> Past Papers In Tabular Formart" href="tabular_pastpapers.php?view=<?php echo $mod->id; ?>" class="btn btn-primary"><i class="fas fa-table"></i></a>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Past Paper</button>
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
                                                            <div class="row">
                                                                <div class="form-group col-md-6" style="display: none;">
                                                                    <label for="">Course Name</label>
                                                                    <select class='form-control basic' name="course_name">
                                                                        <option selected>Select Course Name</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$mod->course_id'  ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($course = $res->fetch_object()) {
                                                                        ?>
                                                                            <option selected><?php echo $course->name; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="">Exam Paper Name</label>
                                                                    <input type="text" value="<?php echo $mod->name; ?>" name="paper_name" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Exam Paper Visibility / Availability</label>
                                                                    <select class='form-control basic' name="paper_visibility">
                                                                        <option selected>Available</option>
                                                                        <option>Hidden</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputFile">Upload Past Exam Paper ( PDF / Docx )</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="pastpaper" type="file" class="custom-file-input" id="exampleInputFile">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_paper" class="btn btn-primary">Upload Paper</button>
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
                                                $ret = "SELECT * FROM `ezanaLMS_PastPapers` WHERE module_name = '$mod->name'   ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($pastExas = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><?php echo $pastExas->paper_name; ?></h5>
                                                                <br>
                                                                <hr>
                                                                <div class="text-center">
                                                                    <a target="_blank" href="pdf_viewer.php?id=<?php echo $pastExas->id; ?>&view=<?php echo $view; ?>" class="btn btn-outline-success">
                                                                        View Paper
                                                                    </a>
                                                                    <?php
                                                                    /* If It Lacks upload_solutionSolution Give Option to upload else Download solution */
                                                                    if ($pastExas->solution == '') {
                                                                        echo
                                                                        "
                                                                        <a  data-toggle='modal' href= '#solution-$pastExas->id' class='btn btn-outline-primary'>
                                                                            Upload Solution
                                                                        </a>
                                                                        ";
                                                                    } else {
                                                                        echo
                                                                        "
                                                                        <a target='_blank' href= 'pdf_solution_viewer.php?id=$pastExas->id&view=$view' class='btn btn-outline-success'>
                                                                            View Solution
                                                                        </a>
                                                                        ";
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <!-- Upload Solution Modal -->
                                                                <div class="modal fade" id="solution-<?php echo $pastExas->id; ?>">
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
                                                                                                <input type="hidden" required name="id" value="<?php echo $pastExas->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Exam Paper Solution Visibility / Availability</label>
                                                                                                <select class='form-control basic' name="solution_visibility">
                                                                                                    <option selected>Available</option>
                                                                                                    <option>Hidden</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputFile">Upload Past Exam Paper Solution ( PDF / Docx )</label>
                                                                                                <div class="input-group">
                                                                                                    <div class="custom-file">
                                                                                                        <input required name="solution" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="upload_solution" class="btn btn-primary">Upload</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End SOlution Modal -->
                                                            </div>
                                                            <div class="card-footer">
                                                                <small class="text-muted">Uploaded: <?php echo $pastExas->created_at; ?></small>
                                                                <br>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#edit-visibility-<?php echo $pastExas->id; ?>">Edit Visiblity</a>

                                                                <!-- Edit Visibility Solution Modal -->
                                                                <div class="modal fade" id="edit-visibility-<?php echo $pastExas->id; ?>">
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
                                                                                            <div class="form-group col-md-6">
                                                                                                <input type="hidden" required name="id" value="<?php echo $pastExas->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Exam Paper Name</label>
                                                                                                <input type="text" value="<?php echo $pastExas->paper_name; ?>" name="paper_name" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Exam Paper Visibility / Availability</label>
                                                                                                <select class='form-control basic' name="paper_visibility">
                                                                                                    <option selected><?php echo $pastExas->paper_visibility; ?></option>
                                                                                                    <option>Available</option>
                                                                                                    <option>Hidden</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Exam Paper Solution Visibility / Availability</label>
                                                                                                <select class='form-control basic' name="solution_visibility">
                                                                                                    <option selected><?php echo $pastExas->solution_visibility; ?></option>
                                                                                                    <option selected>Available</option>
                                                                                                    <option>Hidden</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_pastpaper" class="btn btn-primary">Update Exam Paper</button>
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
                                                                <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $pastExas->id; ?>">Delete Paper</a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $pastExas->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $pastExas->paper_name; ?> ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="pastpapers.php?delete=<?php echo $pastExas->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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