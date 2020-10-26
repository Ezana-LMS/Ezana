<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update_score'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['group_score']) && !empty($_POST['group_score'])) {
        $group_score = mysqli_real_escape_string($mysqli, trim($_POST['group_score']));
    } else {
        $error = 1;
        $err = "Group Score Cannot Be Empty";
    }
    if (!$error) {
        $view = $_GET['view'];
        $update = $_GET['update'];
        $group_score = $_POST['group_score'];
        $created_at = date('d M Y');

        $query = "UPDATE ezanaLMS_GroupsAssignmentsGrades SET group_score =?, created_at =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sss', $group_score, $created_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Group Score Updated" && header("refresh:1; url=view_group_project.php?view=$view");
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
        $ret = "SELECT * FROM `ezanaLMS_GroupsAssignmentsGrades` WHERE id ='$update'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($gcode = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Grade Group <?php echo $gcode->group_name; ?> Score</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                    <li class="breadcrumb-item"><a href="manage_group_projects.php?group_code=<?php echo $g->code; ?>">Projects</a></li>
                                    <li class="breadcrumb-item active">Update Grade</li>
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
                                                <label for="">Group Name</label>
                                                <input type="text" readonly value="<?php echo $gcode->group_name; ?>" required name="group_name" class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Group Code</label>
                                                <input type="text" readonly required name="group_code" value="<?php echo $gcode->group_code; ?><?php echo $b; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Group Project / Assignment Score</label>
                                                <input type="text" value="<?php echo $gcode->group_score; ?>" required name="group_score" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="update_score" class="btn btn-primary">Udpate Score</button>
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