<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

if (isset($_POST['upload_attempted_assignment'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['student_regno']) && !empty($_POST['student_regno'])) {
        $student_regno = mysqli_real_escape_string($mysqli, trim($_POST['student_regno']));
    } else {
        $error = 1;
        $err = "Student Regno Cannot Be Empty";
    }
    if (isset($_POST['student_name']) && !empty($_POST['student_name'])) {
        $student_name = mysqli_real_escape_string($mysqli, trim($_POST['student_name']));
    } else {
        $error = 1;
        $err = "Student Name  Cannot Be Empty";
    }

    if (!$error) {
        $exam_id = $_GET['exam_id'];
        $id = $_POST['id'];
        $module_code = $_POST['module_code'];
        $module_name = $_POST['module_name'];
        $student_regno = $_POST['student_regno'];
        $student_name  = $_POST['student_name'];
        $marks_attained = $_POST['marks_attained'];
        $created_at = date('d M Y g:i');
        $status = 'Marked';
        $query = "INSERT INTO ezanaLMS_StudentGrades (id, module_code,  module_name, student_regno, student_name, marks_attained, created_at, status) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $id, $module_code, $module_name, $student_regno, $student_name, $marks_attained, $created_at, $status);
        $stmt->execute();
        if ($stmt) {
            $success = "Marks Submitted" && header("refresh:1; url=mark_assignment.php?exam_id=$exam_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
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
        $grade = $_GET['grade'];
        $ret = "SELECT * FROM `ezanaLMS_StudentAnswers` WHERE id ='$grade'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($exam = $res->fetch_object()) {
        ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    Grade <?php echo $exam->module_name; ?> Assignment
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="assignments.php">Tests</a></li>
                                    <li class="breadcrumb-item active">Grade Assignment</li>
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
                                    <?php echo $exam->module_name; ?> Answer Sheet Uploaded On <span class='text-success'><?php echo date('d M Y, g:ia', strtotime($exam->created_at)); ?> </span> By <?php echo $exam->student_name;?>  <?php echo $exam->student_regno; ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Mark Answer Sheet</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Grade Assignment</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <br>
                                        <?php
                                        if ($exam->attachments != '') {
                                            echo
                                                "
                                                <a href='dist/Assignments-Attemps/$exam->attachments' class='btn btn-outline-success'>
                                                    <i class='fas fa-download'></i>
                                                        Download Answer Sheet
                                                </a>
                                                ";
                                        } else {
                                            echo
                                                "
                                                <a  class='btn btn-outline-danger'>
                                                    <i class='fas fa-times'></i>
                                                        Answer Sheet Not Available
                                                </a>
                                                ";
                                        }
                                        ?>
                                    </div>
                                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                        <br>
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">Fill All Required Fields</h3>
                                            </div>
                                            <!-- form start -->
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="">Student Name</label>
                                                            <input type="text" id="StudentName" readonly value="<?php echo $exam->student_name; ?>" required name="student_name" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Student Admission Number</label>
                                                            <input type="text" id="StudentName" readonly value="<?php echo $exam->student_regno; ?>" required name="student_name" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Module Code</label>
                                                            <input type="text" readonly value="<?php echo $exam->module_code; ?>" required name="module_code" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Module Name</label>
                                                            <input type="text" readonly value="<?php echo $exam->module_name; ?>" required name="module_name" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="">Assignment Score</label>
                                                            <input type="text" required name="marks_attained" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" name="submit_marks" class="btn btn-primary">Sumbit Marks</button>
                                                </div>
                                            </form>
                                        </div>
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