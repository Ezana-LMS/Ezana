<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_DepartmentalMemos WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=departmental_memos.php");
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
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Departmental Memos</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="departmental_memos.php">Departments</a></li>
                                <li class="breadcrumb-item active">Departmental Memos</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-right">
                                    <a class="btn btn-outline-success" href="add_departmental_memo.php">
                                        <i class="fas fa-plus"></i>
                                        Add New Departmental Memo
                                    </a>
                                </h2>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Department Name</th>
                                            <th>Department Code Number</th>
                                            <th>Date Posted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_DepartmentalMemos` WHERE type = ''  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($memo = $res->fetch_object()) {
                                        ?>

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $memo->department_name; ?></td>
                                                <td><?php echo $memo->department_id; ?></td>
                                                <td><?php echo $memo->created_at; ?></td>
                                                <td>
                                                    <a class="badge badge-success" href="view_departmental_memo.php?view=<?php echo $memo->id; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                    <a class="badge badge-primary" href="update_departmental_memo.php?update=<?php echo $memo->id; ?>">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>
                                                    <a class="badge badge-danger" href="departmental_memos.php?delete=<?php echo $memo->id; ?>">
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
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>