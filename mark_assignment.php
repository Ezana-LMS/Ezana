<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//delete
if (isset($_GET['delete'])) {
    $exam_id = $_GET['exam_id'];
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_StudentAnswers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=mark_assignment.php?exam_id=$exam_id");
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
        <!-- Main Sidebar Container -->
        <?php
        require_once('partials/_sidebar.php');
        $exam_id = $_GET['exam_id'];
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Mark Attempted Assignment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="">Tests</a></li>
                                <li class="breadcrumb-item"><a href="assignments.php">Assignments</a></li>
                                <li class="breadcrumb-item active">Mark Assignments</li>
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
                                    <a class="btn btn-outline-success" href="upload_assignment_attempt.php?exam_id=<?php echo $exam_id; ?>">
                                        <i class="fas fa-file-upload"></i>
                                        Upload Attempted Assignment
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
                                            <th>Module Code</th>
                                            <th>Module Name</th>
                                            <th>Student ADMNo</th>
                                            <th>Student Name</th>
                                            <th>Date Uploaded</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $exam_id = $_GET['exam_id'];
                                        $ret = "SELECT * FROM `ezanaLMS_StudentAnswers`  WHERE  exam_id  ='$exam_id' AND status !='Graded' ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($exam = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $exam->module_code; ?></td>
                                                <td><?php echo $exam->module_name; ?></td>
                                                <td><?php echo $exam->student_regno; ?></td>
                                                <td><?php echo $exam->student_name; ?></td>
                                                <td><?php echo date('d M Y - g:i', strtotime($exam->created_at)); ?></td>
                                                <td>
                                                    <a class="badge badge-success" href="grade_assignment.php?grade=<?php echo $exam->id; ?>&exam_id=<?php echo $exam->exam_id; ?>">
                                                        <i class="fas fa-eye"></i>
                                                        Grade Assignment
                                                    </a>
                                                    <a class="badge badge-danger" href="mark_assignment.php?delete=<?php echo $exam->id; ?>">
                                                        <i class="fas fa-trash"></i>
                                                        Delete Assignment
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