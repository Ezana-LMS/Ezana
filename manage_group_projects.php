<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $group_code = $_GET['group_code'];
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_GroupsAssignments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=manage_group_projects.php?group_code=$group_code");
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
        $ret = "SELECT * FROM `ezanaLMS_Groups`  WHERE code ='$group_code' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($g = $res->fetch_object()) {
        ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $g->name; ?> Projects </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                    <li class="breadcrumb-item"><a href="manage_group_projects.php?group_code=<?php echo $g->code; ?>">Projects</a></li>
                                    <li class="breadcrumb-item active">Manage </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="text-right">
                                        <a class="btn btn-outline-success" href="add_group_project.php?group_code=<?php echo $g->code; ?>&group_name=<?php echo $g->name; ?>&type=Project">
                                            <i class="fas fa-plus"></i>
                                            Create New Group Project
                                        </a>
                                        <!-- <a class="btn btn-outline-primary" href="">
                                        <i class="fas fa-file-excel"></i>
                                        Import Faculties From .XLS File
                                    </a> -->
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Group Code Number</th>
                                                <th>Group Name</th>
                                                <th>Project Created At</th>
                                                <th>Submission Deadline</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE group_code ='$group_code'  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($gcode = $res->fetch_object()) {
                                            ?>

                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $gcode->group_code; ?></td>
                                                    <td><?php echo $gcode->group_name; ?></td>
                                                    <td><?php echo $gcode->created_at; ?></td>
                                                    <td><?php echo $gcode->submitted_on; ?></td>
                                                    <td>
                                                        <a class="badge badge-success" href="view_group_project.php?view=<?php echo $gcode->id; ?>">
                                                            <i class="fas fa-eye"></i>
                                                            View
                                                        </a>
                                                        <a class="badge badge-primary" href="update_group_project.php?update=<?php echo $gcode->id; ?>">
                                                            <i class="fas fa-edit"></i>
                                                            Update
                                                        </a>
                                                        <a class="badge badge-warning" href="grade_group_project.php?grade=<?php echo $gcode->id; ?>">
                                                            <i class="fas fa-check"></i>
                                                            Grade Project
                                                        </a>
                                                        <a class="badge badge-danger" href="manage_group_projects.php?delete=<?php echo $gcode->id; ?>&group_code=<?php echo $g->code; ?>">
                                                            <i class="fas fa-trash"></i>
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
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