<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_class_recording'])) {
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
        $class_name = $_POST['class_name'];
        $lecturer_name  = $_POST['lecturer_name'];
        $external_link = $_POST['external_link'];
        $details  = $_POST['details'];
        $created_at  = date('d M Y');
        $video = $_FILES['video']['name'];
        move_uploaded_file($_FILES["video"]["tmp_name"], "dist/EzanaLMSData/" . $_FILES["video"]["name"]);
        $faculty = $_GET['faculty'];

        $query = "INSERT INTO ezanaLMS_ClassRecordings (id, faculty_id, class_name, lecturer_name, external_link, details, created_at, video) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $id, $faculty, $class_name, $lecturer_name, $external_link, $details, $created_at, $video);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Recoding Added" && header("refresh:1; url=add_class_recording.php?faculty=$faculty");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');

$faculty = $_GET['faculty'];
$ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($f = $res->fetch_object()) {
?>

    <body class="hold-transition sidebar-collapse layout-top-nav">
        <div class="wrapper">
            <!-- Navbar -->
            <?php require_once('partials/_faculty_nav.php'); ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Upload Recordings</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="class_recordings.php?faculty=<?php echo $f->id; ?>">Class Recordings</a></li>
                                    <li class="breadcrumb-item active"> Upload </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="container">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Fill All Required Fields</h3>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" role="form">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="">Class Name</label>
                                                        <input type="text" required name="class_name" class="form-control" id="exampleInputEmail1">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
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
                                                                <input name="video" type="file" class="custom-file-input" id="exampleInputFile">
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
                                            <div class="card-footer">
                                                <button type="submit" name="add_class_recording" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
    } ?>
        </div>
        <?php require_once('partials/_scripts.php'); ?>
    </body>

    </html>