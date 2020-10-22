<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//Remove Member
if (isset($_GET['remove'])) {
    $view = $_GET['view'];
    $remove = $_GET['remove'];
    $adn = "DELETE FROM ezanaLMS_StudentsGroups WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $remove);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=view_group.php?view=$view");
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
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Groups` WHERE id ='$view'  ";
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
                                <h1>
                                    <?php echo $g->name; ?> Details
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                    <li class="breadcrumb-item active">View</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="content">
                    <div class="container-fluid">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <?php echo $g->code; ?> - <?php echo $g->name; ?> Created On <span class="text-success"> <?php echo $g->created_at; ?></span> And Updated On <span class="text-warning"><?php echo $g->updated_at; ?></span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Group Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-members" role="tab" aria-controls="custom-content-below-members" aria-selected="false">Group Members</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-notices" role="tab" aria-controls="custom-content-below-notices" aria-selected="false">Group Notices</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-projects" role="tab" aria-controls="custom-content-below-projects" aria-selected="false">Group Projects</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-assignments" role="tab" aria-controls="custom-content-below-assignments" aria-selected="false">Group Assignments</a>
                                    </li> -->
                                </ul>
                                <div class="tab-content" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <br>
                                        <?php echo $g->details; ?>
                                    </div>
                                    <div class="tab-pane fade" id="custom-content-below-members" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                        <br>
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student Admission No</th>
                                                    <th>Student Name</th>
                                                    <th>Date Added</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $GroupCode = $g->code;
                                                $ret = "SELECT * FROM `ezanaLMS_StudentsGroups` WHERE code = '$GroupCode'  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($stdGroup = $res->fetch_object()) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $stdGroup->student_admn; ?></td>
                                                        <td><?php echo $stdGroup->student_name; ?></td>
                                                        <td><?php echo date('d M Y g:i', strtotime($stdGroup->created_at)); ?></td>
                                                        <td>
                                                            <a class="badge badge-danger" href="view_group.php?remove=<?php echo $stdGroup->id; ?>&view=<?php echo $g->id; ?>">
                                                                <i class="fas fa-user-times"></i>
                                                                Remove Member
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
                    </div>
                </section>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>