<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_paper'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (!$error) {
        $faculty = $_GET['faculty'];
        $module_name = $_POST['module_name'];
        $id = $_POST['id'];
        $course_name = $_POST['course_name'];
        $pastpaper_type = 'Solution';
        $created_at = date('d M Y h:m:s');
        $pastpaper = $_FILES['pastpaper']['name'];
        move_uploaded_file($_FILES["pastpaper"]["tmp_name"], "EzanaLMSData/PastPapers/" . $_FILES["pastpaper"]["name"]);

        $query = "INSERT INTO ezanaLMS_PastPapers (id, faculty_id, course_name, module_name,  pastpaper_type, created_at, pastpaper) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $faculty, $course_name, $module_name, $pastpaper_type, $created_at, $pastpaper);
        $stmt->execute();
        if ($stmt) {
            $success = "Past Paper Uploaded" && header("refresh:1; url=add_past_exam_paper_solution.php?faculty=$faculty");
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
        <?php
        require_once('partials/_faculty_nav.php');
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($f = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Upload Exam Paper Solutions</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="past_exam_paper_solutions.php?faculty=<?php echo $f->id; ?>"> Past Papers Solutions</a></li>
                                    <li class="breadcrumb-item active">Upload</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Fill All Required Fields</h3>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" role="form">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="">Course Name</label>
                                                        <select class='form-control basic' name="course_name">
                                                            <option selected>Select Course Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($course = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $course->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="">Module Name</label>
                                                        <select class='form-control basic' name="module_name">
                                                            <option selected>Select Module Name</option>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE faculty_id = '$f->id'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($mod = $res->fetch_object()) {
                                                            ?>
                                                                <option><?php echo $mod->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="exampleInputFile">Upload Past Exam Paper Solution ( PDF / Docx )</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input required name="pastpaper" type="file" class="custom-file-input" id="exampleInputFile">
                                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_paper" class="btn btn-primary">Upload Paper Solution</button>
                                            </div>
                                        </form>
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