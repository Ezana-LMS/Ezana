<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_paper'])) {
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
    if (isset($_POST['exam_time']) && !empty($_POST['exam_time'])) {
        $exam_time = mysqli_real_escape_string($mysqli, trim($_POST['exam_time']));
    } else {
        $error = 1;
        $err = "Exam Time Duration Cannot Be Empty";
    }
    if (isset($_POST['instructions']) && !empty($_POST['instructions'])) {
        $instructions = mysqli_real_escape_string($mysqli, trim($_POST['instructions']));
    } else {
        $error = 1;
        $err = "Exam Instructions  Cannot Be Empty";
    }

    if (!$error) {
        $id = $_POST['id'];
        $module_code = $_POST['module_code'];
        $module_name = $_POST['module_name'];
        $exam_time = $_POST['exam_time'];
        $instructions = $_POST['instructions'];
        $type = 'Exam';
        $attachment = $_FILES['attachment']['name'];
        move_uploaded_file($_FILES["attachment"]["tmp_name"], "dist/Exams/" . $_FILES["attachment"]["name"]);

        $query = "INSERT INTO ezanaLMS_ExamQuestions (id, module_code, module_name, exam_time, instructions, type, attachment) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $module_code, $module_name, $exam_time, $instructions, $type, $attachment);
        $stmt->execute();
        if ($stmt) {
            $success = "Exam Paper Uploaded" && header("refresh:1; url=upload_questions_bank.php");
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
        <?php require_once('partials/_sidebar.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Upload Exam Papers</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="questions_bank.php">Exams</a></li>
                                <li class="breadcrumb-item active">Upload Question Paper </li>
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
                                        <div class="form-group col-md-4">
                                            <label for="">Module Name</label>
                                            <select class='form-control basic' id="ModuleName" onchange="getModuleDetails(this.value);" name="module_name">
                                                <option selected>Select Module Name </option>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($mod = $res->fetch_object()) {
                                                ?>
                                                    <option><?php echo $mod->module_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Module Code</label>
                                            <input type="text" id="ModuleCode" readonly required name="module_code" class="form-control">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Exam Paper Time</label>
                                            <input type="text" required name="exam_time" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputFile">Upload Past Exam Paper ( PDF / Docx )</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input required name="attachment" accept=".docx,.pdf,.doc" type="file" class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Select File</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="">Instructions</label>
                                            <textarea type="text" required id="textarea" name="instructions" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_paper" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
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