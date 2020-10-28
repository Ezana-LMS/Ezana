<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $manage = $_GET['manage'];
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_GroupsAnnouncements WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=manage_group_announcements.php?manage=$manage");
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
        $manage = $_GET['manage'];
        $ret = "SELECT * FROM `ezanaLMS_Groups`  WHERE code ='$manage' ";
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
                                <h1><?php echo $g->name; ?> Announcements </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="group_announcements.php">Groups</a></li>
                                    <li class="breadcrumb-item active">Manage Announcements</li>
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
                                        <a class="btn btn-outline-success" href="add_group_announcements.php?group_code=<?php echo $g->code; ?>&group_name=<?php echo $g->name; ?>">
                                            <i class="fas fa-plus"></i>
                                            Create New Group  Announcement
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
                                                <th>Posted By</th>
                                                <th>Posted On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_GroupsAnnouncements` WHERE group_code ='$manage'  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($ga= $res->fetch_object()) {
                                            ?>

                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $ga->group_code; ?></td>
                                                    <td><?php echo $ga->group_name; ?></td>
                                                    <td><?php echo $ga->created_by; ?></td>
                                                    <td><?php echo date('d M Y', strtotime($ga->created_at)); ?></td>
                                                    <td>
                                                        <a class="badge badge-success" href="view_group_announcement.php?view=<?php echo $ga->id; ?>">
                                                            <i class="fas fa-eye"></i>
                                                            View
                                                        </a>
                                                        <a class="badge badge-primary" href="update_group_announcement.php?update=<?php echo $ga->id; ?>">
                                                            <i class="fas fa-edit"></i>
                                                            Update
                                                        </a>
                                                        <a class="badge badge-danger" href="manage_group_announcements.php?delete=<?php echo $ga->id; ?>&manage=<?php echo $ga->group_code; ?>">
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