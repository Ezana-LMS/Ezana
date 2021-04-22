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
        $created_at = date('d M Y h:m:s');
        $pastpaper = $_FILES['pastpaper']['name'];
        /* Module ID */
        $module_id = $_POST['module_id'];
        move_uploaded_file($_FILES["pastpaper"]["tmp_name"], "public/uploads/EzanaLMSData/PastPapers/" . $_FILES["pastpaper"]["name"]);

        $query = "INSERT INTO ezanaLMS_PastPapers (id, paper_name, paper_visibility, faculty_id, course_name, module_name,  created_at, pastpaper) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $id, $paper_name, $paper_visibility, $faculty, $course_name, $module_name, $created_at, $pastpaper);
        $stmt->execute();
        if ($stmt) {
            $success = "Past Paper Uploaded" && header("refresh:1; url=edu_admn_tabular_pastpapers.php?view=$module_id");
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
        $success = "Past Paper Solution Uploaded" && header("refresh:1; url=edu_admn_tabular_pastpapers.php?view=$module_id");
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
        $success = "Past Paper Updated" && header("refresh:1; url=edu_admn_tabular_pastpapers.php?view=$view");
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
        $success = "Deleted" && header("refresh:1; url=edu_admn_tabular_pastpapers.php?view=$view");
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Past Papers</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="edu_admn_modules.php?view=<?php echo $faculty_id; ?>">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
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
                                        <a href="edu_admn_pastpapers.php?view=<?php echo $mod->id; ?>" title="View <?php echo $mod->name; ?> Past Papers In List Formart" class="btn btn-primary"><i class="fas fa-list-alt"></i></a>
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
                                                                        <?php
                                                                        } ?>
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
                                                                            <input required name="pastpaper" accept=".pdf, .docx, .doc" type="file" class="custom-file-input" id="exampleInputFile">
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
                                <?php require_once('public/partials/_edu_admn_modulemenu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card-box">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-12 text-right form-inline">
                                                            <div class="form-group mr-2" style="display: none;">
                                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                                    <option value="">Show all</option>
                                                                    <option value="active">Active</option>
                                                                    <option value="disabled">Disabled</option>
                                                                    <option value="suspended">Suspended</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="demo-foo-filtering" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th data-toggle="true">Paper</th>
                                                                <th data-toggle="true">Date Uploaded</th>
                                                                <th data-hide="all">Manage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_PastPapers` WHERE module_name = '$mod->name'   ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($pastExas = $res->fetch_object()) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $pastExas->paper_name; ?> </td>
                                                                    <td><?php echo date('d M Y - g:i', strtotime($pastExas->created_at)); ?></td>
                                                                    <td>
                                                                        <?php
                                                                        /* If It Lacks upload_solutionSolution Give Option to upload else Download solution */
                                                                        if ($pastExas->solution == '') {
                                                                            echo
                                                                            "
                                                                        <a  data-toggle='modal' href= '#solution-$pastExas->id' class='badge badge-primary'>
                                                                            <i class='fas fa-upload'></i>
                                                                            Upload Solution
                                                                        </a>
                                                                        ";
                                                                        } else {
                                                                            echo
                                                                            "
                                                                        <a target='_blank' href= 'public/uploads/EzanaLMSData/PastPapers/$pastExas->solution' class='badge badge-success'>
                                                                        <i class='fas fa-eye'></i>
                                                                            View Solution
                                                                        </a>
                                                                        ";
                                                                        } ?>
                                                                        <!-- Upload Solution Modal -->
                                                                        <div class="modal fade" id="solution-<?php echo $pastExas->id; ?>">
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
                                                                                                                <input required name="solution" accept=".pdf, .docx, .doc" type="file" class="custom-file-input" id="exampleInputFile">
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
                                                                        <a target="_blank" href="edu_admn_pdf_viewer.php?id=<?php echo $pastExas->id; ?>&view=<?php echo $view; ?>" class="badge badge-secondary">
                                                                            <i class="fas fa-eye"></i>
                                                                            View Paper
                                                                        </a>
                                                                        <a class="badge badge-warning" data-toggle="modal" href="#edit-visibility-<?php echo $pastExas->id; ?>">
                                                                            <i class="fas fa-edit"></i>
                                                                            Edit Visibility
                                                                        </a>
                                                                        <!--  <div class="custom-control custom-switch">
                                                                            <input type="checkbox" name="solution_visibility" value="<?php echo $pastExas->solution_visibility; ?>" class="custom-control-input" id="customSwitch1">
                                                                            <label class="custom-control-label" for="customSwitch1">Visibility</label>
                                                                        </div>  -->

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
                                                                                            <div class="text-right">
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
                                                                        <!-- End Edit Visibilty Modal -->
                                                                        <a class="badge badge-danger" data-toggle="modal" href="#delete-<?php echo $pastExas->id; ?>">
                                                                            <i class="fas fa-trash"></i>
                                                                            Delete Paper
                                                                        </a>
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
                                                                                        <a href="edu_admn_tabular_pastpapers.php?delete=<?php echo $pastExas->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>