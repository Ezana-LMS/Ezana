<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_memo'])) {

    $update = $_GET['update'];
    $departmental_memo = $_POST['departmental_memo'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/memos/" . $_FILES["attachments"]["name"]);
    $updated_at = date('d M Y g:i');

    $query = "UPDATE ezanaLMS_DepartmentalMemos SET departmental_memo =?, attachments =?, updated_at =? WHERE id =?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $departmental_memo, $attachments, $updated_at, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Departmental Memo Updated" && header("refresh:1; url=departmental_memos.php");
    } else {
        $info = "Please Try Again Or Try Later";
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
        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE id ='$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($memo = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update Departmental Memo</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="departmental_memos.php">Departments</a></li>
                                    <li class="breadcrumb-item"><a href="departmental_memos.php">Departmental Memos</a></li>
                                    <li class="breadcrumb-item active">Update Memo</li>
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
                                    <h3 class="card-title">Fill Required Fields</h3>
                                </div>
                                <!-- form start -->
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-12">
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
                                    <div class="card-footer">
                                        <button type="submit" name="update_memo" class="btn btn-primary">Update Departmental Memo</button>
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
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>