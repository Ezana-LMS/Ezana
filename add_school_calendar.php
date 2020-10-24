<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['add_school_calendar'])) {
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
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Calendar WHERE  semester_name='$semester_name'  ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($semester_name == $row['semester_name']) {
                $err =  "Semester Name Already Exists";
            } 
        } else {
            $id = $_POST['id'];
            $academic_yr = $_POST['academic_yr'];
            $semester_start = $_POST['semester_start'];
            $semester_name = $_POST['semester_name'];
            $semester_end = $_POST['semester_end'];
            
            $query = "INSERT INTO ezanaLMS_Calendar (id, academic_yr, semester_start, semester_name, semester_end) VALUES(?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssss', $id, $academic_yr, $semester_start, $semester_name, $semester_end);
            $stmt->execute();
            if ($stmt) {
                $success = "Educational Dates Added" && header("refresh:1; url=add_school_calendar.php");
            } else {
                $info = "Please Try Again Or Try Later";
            }
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
                            <h1>Add New Module</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="add_module.php">Modules</a></li>
                                <li class="breadcrumb-item active">Add Module</li>
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
                                            <label for="">Module Name</label>
                                            <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Module Number / Code</label>
                                            <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                        </div>
                                    </div>
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
                                            <input type="text" id="CourseCode" readonly required name="" class="form-control">
                                            <input type="hidden" id="CourseID" required name="course_id" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="">Teaching Duration</label>
                                            <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Number Of Lectures Per Week</label>
                                            <input type="text" required name="lectures_number" class="form-control">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="">Module CAT / End Exam Weight Percentage</label>
                                            <input type="text" required name="weight_percentage" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputPassword1">Module Details</label>
                                            <textarea required id="textarea" name="details" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
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