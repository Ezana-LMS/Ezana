<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_Admins WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=total_admins.php");
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
        require_once('partials/_nav.php');
        require_once('partials/_sidebar.php');
        ?>
        <!-- /.navbar -->

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Administrators</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active"> Administrators </li>
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
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="text-right">
                                            <a class="btn btn-outline-success" href="add_administrator.php">
                                                Add Administrator
                                            </a>
                                        </h2>
                                    </div>
                                    <div class="card-body">
                                        <table id="export-dt" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Names</th>
                                                    <th>Email</th>
                                                    <th>Rank</th>
                                                    <th>Phone No. </th>
                                                    <th>Manage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Admins`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($admin = $res->fetch_object()) {
                                                ?>
                                                    <tr class="table-row" data-href="view_admin.php?view=<?php echo $admin->id; ?>">
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $admin->name; ?></td>
                                                        <td><?php echo $admin->email; ?></td>
                                                        <td><?php echo $admin->rank; ?></td>
                                                        <td><?php echo $admin->phone; ?></td>
                                                        <td>
                                                            <a class="badge badge-danger" href="total_admins.php?delete=<?php echo $admin->id; ?>">
                                                                <i class="fas fa-trash"></i>
                                                                Delete Account
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
            </div>
        </div>
        <?php require_once('partials/_footer.php');
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>