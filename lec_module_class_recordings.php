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

/* Add Class Recordings  */
if (isset($_POST['add_class_recording'])) {
    $maxsize = 1152428800; //Minimum Of 200Mbs
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['class_name']) && !empty($_POST['class_name'])) {
        $class_name = mysqli_real_escape_string($mysqli, trim($_POST['class_name']));
    } else {
        $error = 1;
        $err = "Class Name Cannot Be Empty";
    }
    if (isset($_POST['lecturer_name']) && !empty($_POST['lecturer_name'])) {
        $lecturer_name = mysqli_real_escape_string($mysqli, trim($_POST['lecturer_name']));
    } else {
        $error = 1;
        $err = "Lectuer Name Cannot Be Empty";
    }
    if (isset($_POST['details']) && !empty($_POST['details'])) {
        $details = mysqli_real_escape_string($mysqli, trim($_POST['details']));
    } else {
        $error = 1;
        $err = "Video Transcription Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $view = $_POST['view'];
        $class_name = $_POST['class_name'];
        $lecturer_name  = $_POST['lecturer_name'];
        $external_link = $_POST['external_link'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');
        $faculty = $_POST['faculty'];
        /* Clip Handling Logic */
        $video = $_FILES['video']['name'];
        $target_dir = "public/uploads/EzanaLMSData/ClassVideos/";
        $target_file = $target_dir . $_FILES["video"]["name"];

        // Select file type
        $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("mp4", "avi", "3gp", "mov", "mpeg");
        // Check extension
        if (in_array($videoFileType, $extensions_arr)) {

            // Check file size
            if (($_FILES['video']['size'] >= $maxsize) || ($_FILES["video"]["size"] == 0)) {
                $err = "File too large. File must be less than 50MB.";
            } else {
                // Upload
                if (move_uploaded_file($_FILES['video']['tmp_name'], $target_file)) {
                    // Insert record
                    $query = "INSERT INTO ezanaLMS_ClassRecordings (id, faculty_id, module_id, class_name, lecturer_name, external_link, details, created_at, video) VALUES(?,?,?,?,?,?,?,?,?)";
                    $stmt = $mysqli->prepare($query);
                    $rc = $stmt->bind_param('sssssssss', $id, $faculty, $view, $class_name, $lecturer_name, $external_link, $details, $created_at, $video);
                    $stmt->execute();
                    mysqli_query($mysqli, $query);
                    if ($stmt) {
                        //inject alert that post is shared  
                        $success = "Uploaded" && header("refresh:1; url=lec_module_class_recordings.php?view=$view");
                    }
                }
            }
        } else {
            $err = "Invalid file extension.";
        }
    }
}


/* Update Class Recordings   */
if (isset($_POST['update_class_recording'])) {
    $maxsize = 1152428800; //Minimum Of 200Mbs
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['class_name']) && !empty($_POST['class_name'])) {
        $class_name = mysqli_real_escape_string($mysqli, trim($_POST['class_name']));
    } else {
        $error = 1;
        $err = "Class Name Cannot Be Empty";
    }
    if (isset($_POST['lecturer_name']) && !empty($_POST['lecturer_name'])) {
        $lecturer_name = mysqli_real_escape_string($mysqli, trim($_POST['lecturer_name']));
    } else {
        $error = 1;
        $err = "Lectuer Name Cannot Be Empty";
    }
    if (isset($_POST['details']) && !empty($_POST['details'])) {
        $details = mysqli_real_escape_string($mysqli, trim($_POST['details']));
    } else {
        $error = 1;
        $err = "Video Transcription Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $view = $_POST['view'];
        $class_name = $_POST['class_name'];
        $lecturer_name  = $_POST['lecturer_name'];
        $external_link = $_POST['external_link'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');
        /* Clip Handling Logic */
        $video = $_FILES['video']['name'];
        $target_dir = "public/uploads/EzanaLMSData/ClassVideos/";
        $target_file = $target_dir . $_FILES["video"]["name"];

        // Select file type
        $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("mp4", "avi", "3gp", "mov", "mpeg");
        // Check extension
        if (in_array($videoFileType, $extensions_arr)) {

            // Check file size
            if (($_FILES['video']['size'] >= $maxsize) || ($_FILES["video"]["size"] == 0)) {
                $err = "File too large. File must be less than 50MB.";
            } else {
                // Upload
                if (move_uploaded_file($_FILES['video']['tmp_name'], $target_file)) {
                    // Insert record
                    $query = "UPDATE ezanaLMS_ClassRecordings SET class_name =?, lecturer_name =?, external_link =?, details =?, created_at =?, video =? WHERE id = ?";
                    $stmt = $mysqli->prepare($query);
                    $rc = $stmt->bind_param('sssssss', $class_name, $lecturer_name, $external_link, $details, $created_at, $video, $id);
                    $stmt->execute();
                    mysqli_query($mysqli, $query);
                    if ($stmt) {
                        //inject alert that post is shared  
                        $success = "Updated" && header("refresh:1; url=lec_module_class_recordings.php?view=$view");
                    }
                }
            }
        } else {
            $err = "Invalid file extension.";
        }
    }
}

/* Delete Class Recordings  */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_ClassRecordings WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=lec_module_class_recordings.php?view=$view");
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
                                <h1 class="m-0 text-dark">Course Recordings</h1>
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
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" method="GET">
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Upload Class Recording</button>
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
                                                                    <label for="">Class Name</label>
                                                                    <input type="text" required name="class_name" class="form-control" value="<?php echo $mod->name; ?>" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                    <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Lecturer Name</label>
                                                                    <?php
                                                                    $id  = $_SESSION['id'];
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id ='$id' ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($lec = $res->fetch_object()) {
                                                                    ?>
                                                                        <input type="text" required name="lecturer_name" value="<?php echo $lec->name; ?>" class="form-control">
                                                                    <?php
                                                                    } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Class External Link *Recomended <small class="text-danger">If In YouTube, Vimeo, Google Drive, etc</small></label>
                                                                    <input type="text" name="external_link" class="form-control">
                                                                </div>
                                                                <h5 class="text-center"> Or </h5>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputFile">Upload Video</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input name="video" type="file" accept=".mp4, .WebM" class="custom-file-input" id="exampleInputFile">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose Video File</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Description</label>
                                                                    <textarea type="text" rows="10" name="details" class="form-control Summernote"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_class_recording" class="btn btn-primary">Upload Class Recording</button>
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
                                            <div class="row">
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE module_id = '$mod->id'  ORDER BY `ezanaLMS_ClassRecordings`.`created_at` ASC";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($cr = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-4">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <a href="lec_play_class_recording.php?clip=<?php echo $cr->id; ?>&view=<?php echo $mod->id; ?>">
                                                                    <h5 class="card-title"><?php echo $cr->class_name; ?></h5>
                                                                </a>
                                                                <br>
                                                            </div>
                                                            <div class="card-footer">
                                                                <small class="text-muted">Uploaded: <?php echo $cr->created_at; ?><br></small>
                                                                <a class="badge badge-warning" data-toggle="modal" href="#update-clip-<?php echo $cr->id; ?>">Update</a>

                                                                <!-- Upload Solution Modal -->
                                                                <div class="modal fade" id="update-clip-<?php echo $cr->id; ?>">
                                                                    <div class="modal-dialog  modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Fill All Required Values </h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <!-- Update Form -->
                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Class Name</label>
                                                                                                <input type="text" value="<?php echo $cr->class_name; ?>" required name="class_name" class="form-control" id="exampleInputEmail1">
                                                                                                <input type="hidden" required name="id" value="<?php echo $cr->id; ?>" class="form-control">
                                                                                                <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-6">
                                                                                                <label for="">Lecturer Name</label>
                                                                                                <input type="text" value="<?php echo $cr->lecturer_name; ?>" required name="lecturer_name" class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Class External Link <small class="text-danger">If In YouTube, Vimeo, Google Drive, etc</small> *Recomended</label>
                                                                                                <input type="text" name="external_link" value="<?php echo $cr->external_link; ?>" class="form-control">
                                                                                            </div>
                                                                                            <h5 class="text-center"> Or </h5>
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputFile">Upload Video</label>
                                                                                                <div class="input-group">
                                                                                                    <div class="custom-file">
                                                                                                        <input name="video" type="file" class="custom-file-input" id="exampleInputFile">
                                                                                                        <label class="custom-file-label" for="exampleInputFile">Choose Video File</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="exampleInputPassword1">Description</label>
                                                                                                <textarea id="textarea" type="text" rows="10" name="details" class="form-control Summernote"><?php echo $cr->details; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-footer text-right">
                                                                                        <button type="submit" name="update_class_recording" class="btn btn-primary">Submit</button>
                                                                                    </div>
                                                                                </form>
                                                                                <!-- End Form -->
                                                                            </div>
                                                                            <div class="modal-footer justify-content-between">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End  Modal -->
                                                                <a class="badge badge-danger" href="#delete-<?php echo $cr->id; ?>" data-toggle="modal">Delete</a>
                                                                <!-- Delete Confirmation Modal -->
                                                                <div class="modal fade" id="delete-<?php echo $cr->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $cr->class_name; ?> Class Recording ?</h4>
                                                                                <br>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="lec_module_class_recordings.php?delete=<?php echo $cr->id; ?>&view=<?php echo $mod->id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                    ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>