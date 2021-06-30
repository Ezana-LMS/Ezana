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
require_once('../config/edu_admn_checklogin.php');
edu_admn_checklogin();
require_once('../config/codeGen.php');
$time = time(); /* Timestamp Everything */

/* Add Past papers */
if (isset($_POST['add_paper'])) {
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
        $paper_name = $_POST['paper_name'];
        $paper_visibility = $_POST['paper_visibility'];
        $pastpaper = $time . $_FILES['pastpaper']['name'];
        /* Module ID */
        $module_id = $_POST['module_id'];
        move_uploaded_file($_FILES["pastpaper"]["tmp_name"], "../Data/PastPapers/" . $time . $_FILES["pastpaper"]["name"]);

        $query = "INSERT INTO ezanaLMS_PastPapers (id, paper_name, paper_visibility, faculty_id, course_name, module_name,  pastpaper) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $paper_name, $paper_visibility, $faculty, $course_name, $module_name, $pastpaper);
        $stmt->execute();
        if ($stmt) {
            $success = "$paper_name Uploaded";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}


/* Upload Solution */
if (isset($_POST['upload_solution'])) {
    $id = $_POST['id'];
    $solution_visibility = $_POST['solution_visibility'];
    $solution = $time . $_FILES['solution']['name'];
    $module_id = $_POST['module_id'];
    move_uploaded_file($_FILES["solution"]["tmp_name"], "../Data/PastPapers/" . $time . $_FILES["solution"]["name"]);
    $query = "UPDATE ezanaLMS_PastPapers SET solution_visibility = ?, solution =? WHERE id = ?  ";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $solution_visibility, $solution, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Past Paper Solution Uploaded";
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
        $success = "$paper_name Updated";
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
        $success = "Deleted" && header("refresh:1; url=module_pastpapers?view=$view");
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
                                    <li class="breadcrumb-item"><a href="modules?view=<?php echo $mod->faculty_id;?>">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="col-md-12 text-center">
                                <h1 class="m-0 text-bold"><?php echo $mod->name; ?> Past Papers</h1>
                                <br>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Past Paper</button>
                                <a title="View <?php echo $mod->name; ?> Past Papers In Tabular Formart" href="module_pastpapers_tabular?view=<?php echo $mod->id; ?>" class="btn btn-primary"><i class="fas fa-table"></i></a>
                            </div>

                            <!-- Add Past Paper Modal -->
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
                                                            <input type="text" required name="paper_name" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Exam Paper Visibility / Availability </label>
                                                            <input type="text"  required name="paper_visibility" class="form-control availability">
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
                                                <div class="text-right">
                                                    <button type="submit" name="add_paper" class="btn btn-primary">Upload Paper</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End Add Past Paper Modal -->
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
                                                $ret = "SELECT * FROM `ezanaLMS_PastPapers` WHERE module_name = '$mod->name'   ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($pastExas = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-6">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><?php echo $pastExas->paper_name; ?></h5>
                                                                <br>
                                                                <hr>
                                                                <div class="text-center">
                                                                    <a target="_blank" href="../Data/PastPapers/<?php echo $pastExas->pastpaper; ?>" class="btn btn-outline-success">
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
                                                                        <a target='_blank' href= '../Data/PastPapers/$pastExas->solution' class='btn btn-outline-success'>
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
                                                                                        <!-- Hidden -->
                                                                                        <input type="hidden" required name="id" value="<?php echo $pastExas->id; ?>" class="form-control">
                                                                                        <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Exam Paper Solution Visibility / Availability</label>
                                                                                                <input type="text" required name="solution_visibility" class="form-control availability">
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
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End SOlution Modal -->
                                                            </div>
                                                            <div class="card-footer">
                                                                <small class="text-muted">Uploaded: <?php echo date('d-M-Y g:ia', strtotime($pastExas->created_at)); ?></small>
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
                                                                                        <input type="hidden" required name="id" value="<?php echo $pastExas->id; ?>" class="form-control">
                                                                                        <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Exam Paper Name</label>
                                                                                                <input type="text" required value="<?php echo $pastExas->paper_name; ?>" name="paper_name" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Exam Paper Visibility / Availability</label>
                                                                                                <input type="text" required name="paper_visibility" class="form-control availability" value="<?php echo $pastExas->paper_visibility; ?>" >
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Exam Paper Solution Visibility / Availability</label>
                                                                                                <input type="text" required name="solution_visibility" class="form-control availability" value="<?php echo $pastExas->solution_visibility; ?>">
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class=" text-right">
                                                                                        <button type="submit" name="update_pastpaper" class="btn btn-primary">Update Exam Paper</button>
                                                                                    </div>
                                                                                </form>
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
                                                                                <a href="module_pastpapers?delete=<?php echo $pastExas->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
        }  ?>
</body>

</html>