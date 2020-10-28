<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $code = $_GET['code'];
    $status = 0;
    $adn = "DELETE FROM ezanaLMS_ModuleAssigns WHERE id=?";
    $up = "UPDATE ezanaLMS_Modules SET ass_status =? WHERE code =? ";
    $stmt = $mysqli->prepare($adn);
    $upst = $mysqli->prepare($up);
    $stmt->bind_param('s', $delete);
    $upst->bind_param('is', $status, $code);
    $stmt->execute();
    $upst->execute();
    $upst->close();
    $stmt->close();
    if ($stmt && $upst) {
        $success = "Deleted" && header("refresh:1; url=assign_lecturer_module.php");
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
                            <h1>Module Assigns</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="manage_modules.php">Modules</a></li>
                                <li class="breadcrumb-item active">Assigns</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-right">
                                    <a class="btn btn-outline-success" href="new_module_assign.php">
                                        <i class="fas fa-plus"></i>
                                        Register New Module Assign
                                    </a>

                                    <!-- <a class="btn btn-outline-primary" href="">
                                        <i class="fas fa-file-excel"></i>
                                        Import Students From .XLS File
                                    </a> -->
                                </h2>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Module Code</th>
                                            <th>Module Name</th>
                                            <th>Lecturer Name</th>
                                            <th>Date Assigned</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns`  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($ass = $res->fetch_object()) {
                                        ?>

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $ass->module_code; ?></td>
                                                <td><?php echo $ass->module_name; ?></td>
                                                <td><?php echo $ass->lec_name; ?></td>
                                                <td><?php echo $ass->created_at; ?></td>
                                                <td>
                                                    <!-- <a class="badge badge-success" href="view_module_assign.php?view=<?php echo $ass->id; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a> -->

                                                    <a class="badge badge-primary" href="update_module_assign.php?update=<?php echo $ass->id; ?>">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>

                                                    <a class="badge badge-danger" href="assign_lecturer_module.php?delete=<?php echo $ass->id; ?>&code=<?php echo $ass->module_code; ?>">
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
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>