<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
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
        $id = $_POST['id'];
        $module_code = $_POST['module_code'];
        $module_name = $_POST['module_name'];
        $exam_id  = $_GET['exam_id'];
        $student_regno = $_POST['student_regno'];
        $student_name  = $_POST['student_name'];
        $attachments = $_FILES['attachments']['name'];
        $status = 'Marked';
        move_uploaded_file($_FILES["attachments"]["tmp_name"], "dist/Assignments-Attemps/" . $_FILES["attachments"]["name"]);

        $query = "INSERT INTO ezanaLMS_StudentAnswers (id, module_code, exam_id, module_name, student_regno, student_name, attachments, status) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssss', $id, $module_code, $exam_id, $module_name, $student_regno, $student_name, $attachments, $status);
        $stmt->execute();
        if ($stmt) {
            $success = "Uploaded" && header("refresh:1; url=mark_exams.php?exam_id=$exam_id");
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
        $exam_id = $_GET['exam_id'];
        $ret = "SELECT * FROM `ezanaLMS_ExamQuestions`  WHERE  id  ='$exam_id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($exams = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Upload Assignment Answer Sheet</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="assignments.php">Tests</a></li>
                                    <li class="breadcrumb-item active">Upload Assignments Answers</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <!-- general form elements -->
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
                                                <label for="">Student Admission Number</label>
                                                <select class='form-control basic' id="StudentAdmn" onchange="getStudentDetails(this.value);" name="student_regno">
                                                    <option selected>Select Student Admission Number</option>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Students`  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($std = $res->fetch_object()) {
                                                    ?>
                                                        <option><?php echo $std->admno; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Student Name</label>
                                                <input type="text" id="StudentName" readonly required name="student_name" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Module Code</label>
                                                <input type="text" value="<?php echo $exams->module_code; ?>" required name="module_code" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="">Module Name</label>
                                                <input type="text" value="<?php echo $exams->module_name; ?>" required name="module_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputFile">Upload Assignment Answer Sheet ( PDF / Docx )</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input required name="attachments" accept=".docx,.pdf,.doc" type="file" class="custom-file-input" id="exampleInputFile">
                                                        <label class="custom-file-label" for="exampleInputFile">Select File</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="upload_attempted_assignment" class="btn btn-primary">Upload</button>
                                    </div>
                                </form>
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