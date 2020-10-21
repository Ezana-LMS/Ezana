<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_course_directory'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name / ID  Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $course_name = $_POST['course_name'];
        $course_code = $_POST['course_code'];
        $created_at = date('d M Y');
        $course_materials = $_FILES['course_materials']['name'];
        move_uploaded_file($_FILES["course_materials"]["tmp_name"], "dist/Course_Dir/" . $_FILES["course_materials"]["name"]);

        $query = "INSERT INTO ezanaLMS_CourseDirectories (id, course_code, course_name, created_at, course_materials) VALUES(?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss', $id, $course_code, $course_name, $created_at, $course_materials);
        $stmt->execute();
        if ($stmt) {
            $success = "Course Added" && header("refresh:1; url=add_course_directory.php");
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
                            <h1>Add Course Materials</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="course_directory.php">Courses</a></li>
                                <li class="breadcrumb-item active">Add Course Materials</li>
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
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" enctype="multipart/form-data" role="form">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="">Course Name</label>
                                            <select class='form-control basic' id="CourseName" onchange="getCourseDetails(this.value);" name="course_name">
                                                <option selected>Select Course Name</option>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Courses`  ";
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
                                            <label for="">Course Code</label>
                                            <input type="text" id="CourseCode" readonly required name="course_code" class="form-control">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="">Course Materials</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input required name="course_materials" type="file" class="custom-file-input">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_course_directory" class="btn btn-primary">Add Course Material</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>