<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();

/* Add Module */

if (isset($_POST['add_module'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Empty";
    }
    if (isset($_POST['course_name']) && !empty($_POST['course_name'])) {
        $course_name = mysqli_real_escape_string($mysqli, trim($_POST['course_name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        $course_id = mysqli_real_escape_string($mysqli, trim($_POST['course_id']));
    } else {
        $error = 1;
        $err = "Course ID Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Modules WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Module With This Code Already Exists";
            } else {
                $err = "Module  Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $course_name = $_POST['course_name'];
            $course_id = $_POST['course_id'];
            $course_duration = $_POST['course_duration'];
            $exam_weight_percentage = $_POST['exam_weight_percentage'];
            $cat_weight_percentage = $_POST['cat_weight_percentage'];
            $lectures_number = $_POST['lectures_number'];
            $created_at = date('d M Y');
            $faculty_id = $_POST['faculty_id'];

            $query = "INSERT INTO ezanaLMS_Modules (id, name, code, details, course_name, course_id, course_duration, exam_weight_percentage, cat_weight_percentage, lectures_number, created_at, faculty_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssssssssssss', $id, $name, $code, $details, $course_name, $course_id, $course_duration, $exam_weight_percentage, $cat_weight_percentage, $lectures_number, $created_at, $faculty_id);
            $stmt->execute();
            if ($stmt) {
                $success = "$name Module Created";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="dashboard.php" class="brand-link">
                <img src="public/dist/img/logo.png" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">Ezana LMS</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="dashboard.php" class=" nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="faculties.php" class=" nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Faculties
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="departments.php" class="nav-link">
                                <i class="nav-icon fas fa-building"></i>
                                <p>
                                    Departments
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="courses.php" class="active nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Courses
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="modules.php" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                <p>
                                    Modules
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="lecturers.php" class="nav-link">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>
                                    Lecturers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="students.php" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Students
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings.php" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Course Search Results</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="courses.php">Courses</a></li>
                                <li class="breadcrumb-item active">Search Results</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline" action="course_search_result.php" method="GET">
                                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Dep Name Or Code">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>

                                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Course</button>
                                <div class="modal fade" id="modal-default">
                                    <div class="modal-dialog  modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Fill All Values </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-4">
                                                                <label for="">Module Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Module Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="">Course Name</label>
                                                                <input type="text" value="<?php echo $course->name; ?>" required name="course_name" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">

                                                            <div class="form-group col-md-4" style="display:none">
                                                                <label for="">Course ID</label>
                                                                <input type="text" readonly value="<?php echo $course->id; ?>" required name="course_id" class="form-control">
                                                                <input type="text" readonly value="<?php echo $course->faculty_id; ?>" required name="faculty_id" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Teaching Duration</label>
                                                                <input type="text" required name="course_duration" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Number Of Lectures Per Week</label>
                                                                <input type="text" required name="lectures_number" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module CAT Weight Percentage</label>
                                                                <input type="text" required name="cat_weight_percentage" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Module End Exam Weight Percentage</label>
                                                                <input type="text" required name="exam_weight_percentage" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Module Details</label>
                                                                <textarea required id="dep_details" name="details" rows="10" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </nav>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $query = $_GET['query'];
                                $min_length = 5;
                                if (strlen($query) >= $min_length) {
                                    $query = htmlspecialchars($query);
                                    $query = mysqli_real_escape_string($mysqli, $query);
                                    $raw_results = mysqli_query($mysqli, "SELECT * FROM ezanaLMS_Modules WHERE (`name` LIKE '%" . $query . "%') OR (`code` LIKE '%" . $query . "%') ");
                                    if (mysqli_num_rows($raw_results) > 0) {
                                        while ($results = mysqli_fetch_array($raw_results)) {
                                ?>
                                            <div class="col-md-12">
                                                <div class="card card-primary collapsed-card">
                                                    <div class="card-header">
                                                        <a href="module.php?view=<?php echo $results['id']; ?>">
                                                            <h3 class="card-title"><?php echo $results['name']; ?></h3>
                                                            <div class="card-tools text-right">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="module_notices.php?view=<?php echo $results['id']; ?>">
                                                                    Notices
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="pastpapers.php?view=<?php echo $results['id']; ?>">
                                                                    Past Papers
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="course_materials.php?view=<?php echo $results['id']; ?>">
                                                                    Course Materials
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="class_recordings.php?view=<?php echo $results['id']; ?>">
                                                                    Class Recordings
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="student_groups.php?view=<?php echo $results['id']; ?>">
                                                                    Student Groups
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="enrollments.php?view=<?php echo $results['id']; ?>">
                                                                    Module Enrollments
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    } else {
                                        echo "<span class ='text-danger'>No Search Results</span>";
                                    }
                                } else {
                                    echo "<span class ='text-danger'> Minimum Search Querry  Length Is " . $min_length . " Characters </span> ";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        ?>
</body>

</html>