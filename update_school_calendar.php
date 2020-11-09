<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

if (isset($_POST['update_school_calendar'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['academic_yr']) && !empty($_POST['academic_yr'])) {
        $academic_yr = mysqli_real_escape_string($mysqli, trim($_POST['academic_yr']));
    } else {
        $error = 1;
        $err = "Academic Year Cannot Be Empty";
    }
    if (isset($_POST['semester_name']) && !empty($_POST['semester_name'])) {
        $semester_name = mysqli_real_escape_string($mysqli, trim($_POST['semester_name']));
    } else {
        $error = 1;
        $err = "Semester Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Opening Dates Cannot Be Empty";
    }
    if (isset($_POST['semester_end']) && !empty($_POST['semester_end'])) {
        $semester_end = mysqli_real_escape_string($mysqli, trim($_POST['semester_end']));
    } else {
        $error = 1;
        $err = "Semester Closing  Dates Cannot Be Empty";
    }
    if (!$error) {

        $update = $_GET['update'];
        $academic_yr = $_POST['academic_yr'];
        $semester_start = $_POST['semester_start'];
        $semester_name = $_POST['semester_name'];
        $semester_end = $_POST['semester_end'];

        $query = "UPDATE ezanaLMS_Calendar SET academic_yr =?, semester_start =?, semester_name =?, semester_end =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss',  $academic_yr, $semester_start, $semester_name, $semester_end, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Educational Dates Updated" && header("refresh:1; url=school_calendar.php");
        } else {
            $info = "Please Try Again Or Try Later";
        }
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
                                <li class="breadcrumb-item"><a href="school_calendar.php">School Calendar</a></li>
                                <li class="breadcrumb-item active">Update Calendar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">
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
                                    <table id="example1" class="table table-bordered table-striped">
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
                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>