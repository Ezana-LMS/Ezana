<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_Departments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=departments.php?faculty=$faculty");
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
while ($row = $res->fetch_object()) {
?>

    <body class="hold-transition sidebar-collapse layout-top-nav">
        <div class="wrapper">

            <!-- Navbar -->
            <?php
                 require_once('partials/_faculty_nav.php');
                 require_once('partials/_faculty_sidebar.php');
            ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $row->name; ?> Departments</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item active"> Departments </li>
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
                                                <a class="btn btn-outline-success" href="add_department.php?faculty=<?php echo $row->id; ?>">
                                                    Register New Department
                                                </a>

                                                <a class="btn btn-outline-primary" href="departmental_notememos.php?faculty=<?php echo $row->id; ?>">
                                                    Departmental Memos / Notices
                                                </a>

                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Department Code / Number</th>
                                                        <th>Department Name</th>
                                                        <th>Depeartment Head</th>
                                                        <th>Manage Departments</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$row->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($dep = $res->fetch_object()) {
                                                    ?>
                                                        <tr class="table-row" data-href="view_department.php?department=<?php echo $dep->id; ?>">
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $dep->code; ?></td>
                                                            <td><?php echo $dep->name; ?></td>
                                                            <td><?php echo $dep->hod; ?></td>
                                                            <td>
                                                                <a class="badge badge-success" href="view_department.php?department=<?php echo $dep->id; ?>">
                                                                    <i class="fas fa-eye"></i>
                                                                    View Department
                                                                </a>

                                                                <a class="badge badge-danger" href="departments.php?delete=<?php echo $dep->id; ?>&faculty=<?php echo $dep->faculty_id; ?>">
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
                </div>
            </div>
        <?php require_once('partials/_footer.php');
    } ?>
        </div>
        <?php require_once('partials/_scripts.php'); ?>
    </body>

    </html>