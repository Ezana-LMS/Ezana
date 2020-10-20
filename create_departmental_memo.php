<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_memo'])) {

    $id = $_POST['id'];
    $department_id = $_GET['department_id'];
    $department_name  = $_GET['department_name'];
    $departmental_memo = $_POST['departmental_memo'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/memos/" . $_FILES["attachments"]["name"]);
    $created_at = date('d M Y g:i');

    $query = "INSERT INTO ezanaLMS_DepartmentalMemos (id, department_id, department_name, departmental_memo, attachments, created_at) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssss', $id, $department_id, $department_name, $departmental_memo, $attachments, $created_at);
    $stmt->execute();
    if ($stmt) {
        $success = "Departmental Memo Added" && header("refresh:1; url=create_departmental_memo.php?department_name=$department_name&department_id=$department_id");
    } else {
        //inject alert that profile update task failed
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
        <?php require_once('partials/_sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add New Departmental Memo</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="departmental_memos.php">Departments</a></li>
                                <li class="breadcrumb-item"><a href="departmental_memos.php">Departmental Memos</a></li>
                                <li class="breadcrumb-item active">Add Memo</li>
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
                            <!-- /.card-header -->
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
                                                    <input required name="attachments" type="file" class="custom-file-input">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="text-center">Or </h2>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputPassword1">Type Departmental Memo</label>
                                            <textarea name="details" id="textarea" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_memo" class="btn btn-primary">Add Departmental Memo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>