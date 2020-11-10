<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_ModulesAnnouncements WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=module_notices.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/_head.php');

$faculty = $_GET['faculty'];
$ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($f = $res->fetch_object()) {
?>

    <body class="hold-transition sidebar-collapse layout-top-nav">
        <div class="wrapper">

            <!-- Navbar -->
            <?php require_once('partials/_faculty_nav.php'); ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $f->name; ?> Modules Announcements </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="modules.php?faculty=<?php echo $f->id; ?>">Modules</a></li>
                                    <li class="breadcrumb-item active">Announcements</li>
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
                                                <a class="btn btn-outline-primary" href="add_module_notice.php?faculty=<?php echo $f->id; ?>">
                                                    Add New Module Announcement
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Module Name</th>
                                                        <th>Module Code</th>
                                                        <th>Created By</th>
                                                        <th>Date Posted</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($not = $res->fetch_object()) {
                                                    ?>

                                                        <tr class="table-row" data-href="view_module_notices.php?view=<?php echo $not->id; ?>&faculty=<?php echo $not->faculty_id; ?>">
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $not->module_name; ?></td>
                                                            <td><?php echo $not->module_code; ?></td>
                                                            <td><?php echo $not->created_by; ?></td>
                                                            <td><?php echo $not->created_at; ?></td>
                                                            <td>

                                                                <a class="badge badge-primary" href="update_module_notice.php?update=<?php echo $not->id; ?>&faculty=<?php echo $not->faculty_id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update Announcement
                                                                </a>

                                                                <a class="badge badge-danger" href="module_notices.php?delete=<?php echo $not->id; ?>&faculty=<?php echo $not->faculty_id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete Announcement
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
    } ?>
        </div>
        <?php require_once('partials/_scripts.php'); ?>
    </body>

    </html>