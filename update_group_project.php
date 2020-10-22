<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_group_project'])) {

    $update = $_GET['update'];
    $details = $_POST['details'];
    $attachments = $_FILES['attachments']['name'];
    move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/Group_Projects/" . $_FILES["attachments"]["name"]);
    $updated_at = date('d M Y g:i');
    $submitted_on = $_POST['submitted_on'];

    $query = "UPDATE ezanaLMS_GroupsAssignments SET attachments =?, details =?, updated_at =?, submitted_on =? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssss', $attachments, $details, $updated_at, $submitted_on, $update);
    $stmt->execute();
    if ($stmt) {
        $success = "Group Assignment / Project Updated" && header("refresh:1; url=manage_group_projects.php?group_code=$group_code");
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
        $group_code = $_GET['group_code'];
        $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE code = '$group_code'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($g = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Add <?php echo $g->name; ?> Project</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                    <li class="breadcrumb-item"><a href="group_projects.php">Group Projects</a></li>
                                    <li class="breadcrumb-item active">Add Project</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

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
                                                <label for="exampleInputPassword1">Submission Date </label>
                                                <input type="date" required name="submitted_on" class="form-control">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="">Upload Group Project / Assignment (PDF Or Docx)</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input name="attachments" type="file" class="custom-file-input">
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="text-center">Or</h2>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputPassword1">Type Group Project / Assignment Or Project Description </label>
                                                <textarea name="details" id="textarea" rows="10" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="add_group_project" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>