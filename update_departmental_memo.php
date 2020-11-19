<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update'])) {

    $update = $_GET['update'];
    $departmental_memo = $_POST['departmental_memo'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "EzanaLMSData/memos/" . $_FILES["attachments"]["name"]);
    $created_at = date('d M Y g:i');
    $type = $_POST['type'];
    $faculty = $_GET['faculty'];

    $query = "UPDATE ezanaLMS_DepartmentalMemos SET  departmental_memo =?, attachments =?, created_at =?, type =?, faculty_id =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss',  $departmental_memo, $attachments, $created_at, $type, $faculty, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Departmental NoteMemo Updated" && header("refresh:1; url=departmental_notememos.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/_faculty_nav.php');
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            $update = $_GET['update'];
            $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE id ='$update'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($memo = $res->fetch_object()) {
                require_once('partials/_faculty_sidebar.php');
        ?>
                <!-- /.navbar -->

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Update Departmental Notices & Memo</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="dashboard.php">Faculties</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $faculty; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="departmental_notememos.php?faculty=<?php echo $faculty; ?>">Memos & Notices</a></li>
                                        <li class="breadcrumb-item active">Update</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="container">
                            <section class="content">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">Fill All Required Fields</h3>
                                            </div>
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Type</label>
                                                            <select class='form-control basic' name="type">
                                                                <option selected><?php echo $memo->type; ?></option>
                                                                <option>Notice</option>
                                                                <option>Memo</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Upload Departmental Memo (PDF Or Docx)</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input name="attachments" type="file" class="custom-file-input">
                                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h2 class="text-center">Or </h2>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputPassword1">Type Departmental Memo</label>
                                                            <textarea name="departmental_memo" id="textarea" rows="10" class="form-control"><?php echo $memo->departmental_memo; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
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
            }
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>