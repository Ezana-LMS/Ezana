<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
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
                        $success = "Uploaded" && header("refresh:1; url=class_recordings.php?view=$view");
                    }
                }
            }
        } else {
            $err = "Invalid file extension.";
        }
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
            /* Class Recoding */
            $clip = $_GET['clip'];
            $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE id= '$clip'";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($cr = $res->fetch_object()) {
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
                                    <h1 class="m-0 text-dark"><?php echo $cr->class_name; ?> Clip </h1>
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
                                        <form class="form-inline" action="clips_search.php" method="GET">
                                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Clip Or Class Name">
                                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Upload Class Recording</button>
                                        <div class="modal fade" id="modal-default">
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
                                                                        <label for="">Class Name</label>
                                                                        <input type="text" required name="class_name" class="form-control" value="<?php echo $mod->name; ?>" id="exampleInputEmail1">
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                        <input type="hidden" required name="view" value="<?php echo $mod->id; ?>" class="form-control">
                                                                        <input type="hidden" required name="faculty" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Lecturer Name</label>
                                                                        <input type="text" required name="lecturer_name" class="form-control">
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
                                                                                <input name="video" type="file" required accept=".mp4, .WebM" class="custom-file-input" id="exampleInputFile">
                                                                                <label class="custom-file-label" for="exampleInputFile">Choose Video File</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="exampleInputPassword1">Description</label>
                                                                        <textarea id="textarea" type="text" rows="10" name="details" class="form-control"></textarea>
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
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <a href="module.php?view=<?php echo $mod->id; ?>">
                                                        <h3 class="card-title"><?php echo $mod->name; ?></h3>
                                                        <div class="card-tools text-right">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="card-body">
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="module_notices.php?view=<?php echo $mod->id; ?>">
                                                                Notices & Memos
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="pastpapers.php?view=<?php echo $mod->id; ?>">
                                                                Past Papers
                                                            </a>
                                                        </li>

                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="course_materials.php?view=<?php echo $mod->id; ?>">
                                                                Reading Materials
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="class_recordings.php?view=<?php echo $mod->id; ?>">
                                                                Class Recordings
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="module_assignments.php?view=<?php echo $mod->id; ?>">
                                                                Assignments
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="student_groups.php?view=<?php echo $mod->id; ?>">
                                                                Student Groups
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="module_enrollments.php?view=<?php echo $mod->id; ?>">
                                                                Module Enrollments
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class='embed-responsive embed-responsive-16by9'>
                                                                    <video controls width='320px' height='200px' class='embed-responsive-item' id='uploaded_video' src='public/uploads/EzanaLMSData/ClassVideos/<?php echo $cr->video; ?>' allowfullscreen>
                                                                </div>
                                                                <hr>
                                                                <p>
                                                                    <?php echo $cr->details; ?>
                                                                </p>
                                                                <br>
                                                                <h3 class="text-center">Course Reading Materials</h3>
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
                                                                            <a href="public/uploads/EzanaLMSData/Reading_Materials/<?php echo $rm->readingMaterials; ?>" target="_blank">
                                                                                <div class="card-body">
                                                                                    <p class="card-title"><?php echo $rm->readingMaterials; ?></p>
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
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="text-center">
                                                                    <?php
                                                                    /* If Class Has External Link */
                                                                    if ($cr->external_link == '') {
                                                                    } else {
                                                                        echo
                                                                        "
                                                                        <a target='_blank' href= '$cr->external_link' class='btn btn-outline-success'>
                                                                            Clip External Link
                                                                        </a>
                                                                        ";
                                                                    }
                                                                    ?>
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
                <?php require_once('public/partials/_footer.php');
            }
        } ?>
                    </div>
                </div>
                <!-- ./wrapper -->
                <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>