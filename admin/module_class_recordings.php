<?php
/*
 * Created on Fri Jun 25 2021
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

/* Add Class Recordings  */
if (isset($_POST['upload_class_recording'])) {
    $maxsize = 1152428800; //Minimum Of 200Mbs
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
    if (!$error) {
        $id = $_POST['id'];
        $view = $_POST['view'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');
        $faculty = $_POST['faculty'];
        /* Clip Handling Logic */
        $video = $time . $_FILES['video']['name'];
        $target_dir = "../Data/Class_Recordings/";
        $target_file = $target_dir . $time . $_FILES["video"]["name"];
        $clip_type = 'Clip'; /* Diffrentiate If Class Recording Is A Clip Or Link */

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
                    $query = "INSERT INTO ezanaLMS_ClassRecordings (id, clip_type, faculty_id, module_id, class_name, lecturer_name, details, created_at, video) VALUES(?,?,?,?,?,?,?,?,?)";
                    $stmt = $mysqli->prepare($query);
                    $rc = $stmt->bind_param('sssssssss', $id, $clip_type, $faculty, $view, $class_name, $lecturer_name, $details, $created_at, $video);
                    $stmt->execute();
                    mysqli_query($mysqli, $query);
                    if ($stmt) {
                        $success = "$class_name Clip Uploaded";
                    }
                }
            }
        } else {
            $err = "Invalid file extension.";
        }
    }
}

/* Add Class Recording Via Link */
if (isset($_POST['add_class_recording'])) {
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
    if (!$error) {
        $id = $_POST['id'];
        $view = $_POST['view'];
        $external_link = $_POST['external_link'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');
        $faculty = $_POST['faculty'];
        $clip_type = 'Link';

        // Insert record
        $query = "INSERT INTO ezanaLMS_ClassRecordings (id, clip_type, faculty_id, module_id, class_name, lecturer_name, external_link, details, created_at) VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssss', $id, $clip_type, $faculty, $view, $class_name, $lecturer_name, $external_link, $details, $created_at);
        $stmt->execute();
        mysqli_query($mysqli, $query);
        if ($stmt) {
            $success = "$class_name Class Recording Link Shared";
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

    if (!$error) {
        $id = $_POST['id'];
        $view = $_POST['view'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');
        /* Clip Handling Logic */
        $video =  $time . $_FILES['video']['name'];
        $target_dir = "../Data/Class_Recordings/";
        $target_file = $target_dir . $time . $_FILES["video"]["name"];

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
                    $query = "UPDATE ezanaLMS_ClassRecordings SET class_name =?, details =?, created_at =?, video =? WHERE id = ?";
                    $stmt = $mysqli->prepare($query);
                    $rc = $stmt->bind_param('sssss', $class_name, $details, $created_at, $video, $id);
                    $stmt->execute();
                    mysqli_query($mysqli, $query);
                    if ($stmt) {
                        $success = "$class_name Clip Updated";
                    }
                }
            }
        } else {
            $err = "Invalid file extension.";
        }
    }
}

/* Update Class Recording Via Link */
if (isset($_POST['update_class_recording_link'])) {
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
    if (!$error) {
        $id = $_POST['id'];
        $external_link = $_POST['external_link'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');

        // Insert record
        $query = "UPDATE ezanaLMS_ClassRecordings SET class_name =?, external_link =?, details =?, created_at =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss', $class_name, $external_link, $details, $created_at, $id);
        $stmt->execute();
        mysqli_query($mysqli, $query);
        if ($stmt) {
            $success = "$class_name Class Recording Link Updated";
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
        $success = "Deleted" && header("refresh:1; url=module_class_recordings?view=$view");
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
                                <h1 class="m-0 text-bold"><?php echo $mod->name; ?> Course Recordings</h1>
                                <br>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Upload Class Recording</button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-class-link">Share Class Recording Link</button>

                            </div>
                            <!-- Add Clip Modal -->
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
                                                            <label for="">Allocated Module Lecturer Name</label>
                                                            <select type="text" required name="lecturer_name" class="form-control basic">
                                                                <!-- Load Lecs -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($lec = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $lec->lec_name; ?></option>
                                                                <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputFile">Upload Video</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input name="video" required type="file" accept=".mp4, .WebM" class="custom-file-input" id="exampleInputFile">
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
                                                <div class="text-right">
                                                    <button type="submit" name="upload_class_recording" class="btn btn-primary">Upload Class Recording</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->

                            <!-- Add Clip Modal -->
                            <div class="modal fade" id="modal-class-link">
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
                                                            <label for="">Allocated Module Lecturer Name</label>
                                                            <select type="text" required name="lecturer_name" class="form-control basic">
                                                                <!-- Load Lecs -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($lec = $res->fetch_object()) {
                                                                ?>
                                                                    <option><?php echo $lec->lec_name; ?></option>
                                                                <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="">Class External Link <small class="text-danger">If In YouTube, Vimeo, Google Drive, etc</small></label>
                                                            <input type="text" required name="external_link" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Description | Clip Transcription</label>
                                                            <textarea type="text" rows="10" name="details" class="form-control Summernote"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="add_class_recording" class="btn btn-primary">Share Class Recording Link</button>
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
                                            <div class="row">
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE module_id = '$mod->id'  ORDER BY `ezanaLMS_ClassRecordings`.`created_at` ASC";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($cr = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-4">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body">
                                                                <a href="module_class_recording?clip=<?php echo $cr->id; ?>&view=<?php echo $mod->id; ?>">
                                                                    <img class="img-thumbnail" src="../assets/img/play_clip.jpeg" class="card-img-top">
                                                                    <br>
                                                                    <div class="text-center">
                                                                        <h5 class="card-title"><?php echo substr($cr->class_name, 0, 25); ?>...</h5><br>
                                                                        <small class="text-muted">Uploaded: <?php echo $cr->created_at; ?></small><br>
                                                                        <?php
                                                                        /* If Shared Via Link Just Show */
                                                                        if ($cr->clip_type == 'Link') {
                                                                            echo "<span class='badge badge-success'>Link Available</span>";
                                                                        } else {
                                                                            echo "<span class='badge badge-success'>Clip Available</span>";
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="card-footer text-center">
                                                                <?php
                                                                /* If Shared Via Link Just Show */
                                                                if ($cr->clip_type == 'Link') {
                                                                    echo "<a class='badge badge-warning' data-toggle='modal' href='#update-clip-link-$cr->id'>Update</a>";
                                                                } else {
                                                                    echo "<a class='badge badge-warning' data-toggle='modal' href='#update-clip-recording-$cr->id'>Update</a>";
                                                                }
                                                                ?>
                                                                <a class="badge badge-danger" href="#delete-<?php echo $cr->id; ?>" data-toggle="modal">Delete</a>
                                                            </div>
                                                            <!-- Load Update And Delete Modals -->
                                                            <?php require_once('partials/class_recording_modals.php'); ?>
                                                            <!-- End Load Modals -->
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
            } ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('partials/scripts.php'); ?>
</body>

</html>