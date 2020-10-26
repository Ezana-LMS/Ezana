<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_score'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['group_name']) && !empty($_POST['group_name'])) {
        $group_name = mysqli_real_escape_string($mysqli, trim($_POST['group_name']));
    } else {
        $error = 1;
        $err = "Group Name Cannot Be Empty";
    }
    if (isset($_POST['group_score']) && !empty($_POST['group_score'])) {
        $group_score = mysqli_real_escape_string($mysqli, trim($_POST['group_score']));
    } else {
        $error = 1;
        $err = "Group Score Cannot Be Empty";
    }
    if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
        $project_id = mysqli_real_escape_string($mysqli, trim($_GET['project_id']));
    } else {
        $error = 1;
        $err = "Project ID Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_GroupsAssignmentsGrades WHERE   project_id='$project_id' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($project_id == $row['project_id']) {
                $err =  "Group Work Already Graded";
            }
        } else {
            $id = $_POST['id'];
            $group_name = $_GET['group_name'];
            $group_code = $_GET['group_code'];
            $project_id = $_GET['project_id'];
            $group_score = $_POST['group_score'];
            $created_at = date('d M Y');

            $query = "INSERT INTO ezanaLMS_GroupsAssignmentsGrades (id, group_name, group_code, project_id, group_score, created_at) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssss', $id, $group_name, $group_code, $project_id, $group_score, $created_at);
            $stmt->execute();
            if ($stmt) {
                $success = "Group Score Added" && header("refresh:1; url=manage_group_projects.php?group_code=$group_code");
            } else {
                $info = "Please Try Again Or Try Later";
            }
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
        $project_id = $_GET['project_id'];
        $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE id ='$project_id'  ";
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
                                <h1>Grade Group <?php echo $gcode->group_name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                    <li class="breadcrumb-item"><a href="manage_group_projects.php?group_code=<?php echo $gcode->group_code; ?>">Projects</a></li>
                                    <li class="breadcrumb-item active">Grade Project</li>
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
                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Group Code</label>
                                                <input type="text" readonly required name="group_code" value="<?php echo $gcode->group_code; ?><?php echo $b; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="">Assignment Submission Date</label>
                                                <input type="text" readonly value="<?php echo $gcode->submitted_on; ?>" required name="group_name" class="form-control" id="exampleInputEmail1">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Group Project / Assignment Score</label>
                                                <input type="text" required name="group_score" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="add_score" class="btn btn-primary">Add Score</button>
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