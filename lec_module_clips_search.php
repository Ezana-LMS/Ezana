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
require_once('configs/codeGen.php');
lec_check_login();
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
                        $success = "Uploaded" && header("refresh:1; url=lec_module_class_recording.php?view=$view");
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
                        $success = "Updated" && header("refresh:1; url=lec_module_class_recording.php?view=$view");
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
        $success = "Deleted" && header("refresh:1; url=lec_module_class_recording.php?view=$view");
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
        ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <?php require_once('public/partials/_brand.php'); ?>
            <!-- Sidebar -->
            <?php
            require_once('public/partials/_lec_sidebar.php');
            /* Limit Search Querry To Logged In User Faculty Scope */
            $id  = $_SESSION['id'];
            $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id ='$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($lec = $res->fetch_object()) {
            ?>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Module Class Recordings Search Results</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="">Modules</a></li>
                                <li class="breadcrumb-item active">Class Recordings</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline" action="lec_module_clips_search.php" method="GET">
                                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Clip Or Class Name">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                            </nav>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $query = $_GET['query'];
                                $min_length = 3;
                                if (strlen($query) >= $min_length) {
                                    $query = htmlspecialchars($query);
                                    $query = mysqli_real_escape_string($mysqli, $query);
                                    $raw_results = mysqli_query($mysqli, "SELECT * FROM ezanaLMS_ClassRecordings WHERE (`class_name` LIKE '%" . $query . "%') OR (`details` LIKE '%" . $query . "%') AND faculty_id = '$lec->faculty_id' ");
                                    if (mysqli_num_rows($raw_results) > 0) {
                                        while ($results = mysqli_fetch_array($raw_results)) {
                                ?>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <a href="lec_play_class_recording.php?clip=<?php echo $results['id']; ?>&view=<?php echo $results['module_id']; ?>">
                                                            <h5 class="card-title"><?php echo $results['class_name']; ?></h5>
                                                            <br>

                                                        </a>
                                                    </div>
                                                    <div class="card-footer">
                                                        <small class="text-muted">Uploaded: <?php echo $results['created_at']; ?><br></small>

                                                    </div>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    } else {
                                        echo "<span class ='text-danger'>No Search Results</span>";
                                    }
                                } else {
                                    echo "<span class ='text-danger'> Minimum Search Querry  Length Is " . $min_length . " Characters </span> ";
                                } ?>
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
            }
    ?>
</body>

</html>