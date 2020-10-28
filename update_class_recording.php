<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_class_recording'])) {
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
        $update = $_GET['update'];
        $class_name = $_POST['class_name'];
        $lecturer_name  = $_POST['lecturer_name'];
        $external_link = $_POST['external_link'];
        $details  = $_POST['details'];
        $updated_at  = date('d M Y');
        $video = $_FILES['video']['name'];
        move_uploaded_file($_FILES["video"]["tmp_name"], "dist/ClassVideos/" . $_FILES["video"]["name"]);

        $query = "UPDATE ezanaLMS_ClassRecordings SET class_name =?, lecturer_name =?, external_link =?, details =?, updated_at =?, video =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $class_name, $lecturer_name, $external_link, $details, $updated_at, $video, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Recoding Added" && header("refresh:1; url=class_recordings.php");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        require_once('partials/_sidebar.php');
        $update = $_GET['update'];
        $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE id ='$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($cr = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $cr->class_name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="class_recordings.php">Class Recordings</a></li>
                                    <li class="breadcrumb-item active">Update</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Fill All Required Fields</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Class Name</label>
                                                <input type="text" value="<?php echo $cr->class_name; ?>" required name="class_name" class="form-control" id="exampleInputEmail1">
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
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
                                                <textarea id="textarea" type="text" rows="10" name="details" class="form-control"><?php echo $cr->details; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_class_recording" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php');
        }
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>