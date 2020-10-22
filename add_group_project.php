<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_project'])) {

    $id = $_POST['id'];
    $group_code = $_GET['group_code'];
    $group_name  = $_GET['group_name'];
    $type = $_GET['type'];
    $details = $_POST['details'];

    /* $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/memos/" . $_FILES["attachments"]["name"]); */

    $created_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];

    $query = "INSERT INTO ezanaLMS_GroupsAssignments (id, group_code, group_name, type, details, created_at, submitted_on) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssss', $id, $group_code, $group_name, $type, $details, $created_at, $submitted_on);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Submitted" && header("refresh:1; url=add_group_project.php?group_code=$group_code&group_name=$group_name");
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
        <?php require_once('partials/_sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add New Departmental Notice</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="departmental_notices.php">Departments</a></li>
                                <li class="breadcrumb-item"><a href="departmental_notices.php">Departmental Notices</a></li>
                                <li class="breadcrumb-item active">Add Notice</li>
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
                                            <label for="">Upload Departmental Notice (PDF Or Docx)</label>
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
                                            <label for="exampleInputPassword1">Type Departmental Notice</label>
                                            <textarea name="departmental_memo" id="textarea" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_notice" class="btn btn-primary">Add Departmental Notice</button>
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