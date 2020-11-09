<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//Delete Calendar Details
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_Calendar WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=school_calendar.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/_head.php');

?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">School Calendar / Important Dates</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">School Calendar</li>
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
                                            <a class="btn btn-outline-success" href="add_school_calendar.php">
                                                <i class="fas fa-plus"></i>
                                                Add Important Dates
                                            </a>
                                        </h2>
                                    </div>
                                    <div class="card-body">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Semester Name</th>
                                                    <th>Opening Dates</th>
                                                    <th>Closing Dates</th>
                                                    <th>Academic Year</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Calendar`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($cal = $res->fetch_object()) {
                                                ?>

                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $cal->semester_name; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($cal->semester_start)); ?></td>
                                                        <td><?php echo  date('d M Y', strtotime($cal->semester_end)); ?></td>
                                                        <td><?php echo $cal->academic_yr; ?></td>
                                                        <td>
                                                            <a class="badge badge-primary" href="update_school_calendar.php?update=<?php echo $cal->id; ?>">
                                                                <i class="fas fa-edit"></i>
                                                                Update
                                                            </a>

                                                            <a class="badge badge-danger" href="school_calendar.php?delete=<?php echo $cal->id; ?>">
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
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>