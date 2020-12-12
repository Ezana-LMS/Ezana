<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Course */
if (isset($_POST['add_course'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Couse  Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (isset($_POST['department_id']) && !empty($_POST['department_id'])) {
        $department_id = mysqli_real_escape_string($mysqli, trim($_POST['department_id']));
    } else {
        $error = 1;
        $err = "Department Name / ID  Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Courses WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Course With This Code Already Exists";
            } else {
                $err = "Course Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];
            $department_id = $_POST['department_id'];
            $department_name = $_POST['department_name'];
            $faculty_id = $_POST['faculty_id'];
            $query = "INSERT INTO ezanaLMS_Courses (id, code, name, details, department_id, faculty_id, department_name) VALUES(?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssssss', $id, $code, $name, $details, $department_id, $faculty_id,  $department_name);
            $stmt->execute();
            if ($stmt) {
                $success = "Course Added";
            } else {
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}

/*  Update Course*/
if (isset($_POST['update_course'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Couse  Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Course Name Cannot Be Empty";
    }
    if (!$error) {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $code = $_POST['code'];
        $details = $_POST['details'];

        $query = "UPDATE ezanaLMS_Courses SET  code =?, name =?, details =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss', $code, $name, $details, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Course Updated";
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Course */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_Courses WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=faculty_courses.php?view=$view");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('public/partials/_analytics.php');
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id= '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($faculty = $res->fetch_object()) {
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
                                <a href="departments.php" class=" nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $faculty->name; ?> Courses</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?view=<?php echo $view; ?>"><?php echo $faculty->name; ?></a></li>
                                    <li class="breadcrumb-item active">Courses</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-left">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline" action="course_search_result.php" method="GET">
                                        <input class="form-control mr-sm-2" type="search" name="query" placeholder="Course Name Or Code">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Course</button>
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog  modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Fill All Required Values </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Add Course Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Course Name</label>
                                                                    <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">Course Number / Code</label>
                                                                    <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Department Name</label>
                                                                    <select class='form-control basic' id="DepartmentName" onchange="getDepartmentDetails(this.value);" name="department_name">
                                                                        <option selected>Select Department Name</option>
                                                                        <?php
                                                                        $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE faculty_id = '$view' ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($dep = $res->fetch_object()) {
                                                                        ?>
                                                                            <option><?php echo $dep->name; ?></option>
                                                                        <?php
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6" style="display:none">
                                                                    <label for="">Department Name</label>
                                                                    <input type="text" id="DepartmentID" readonly required name="department_id" class="form-control">
                                                                    <input type="text" value="<?php echo $view; ?>" readonly required name="faculty_id" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Course Description</label>
                                                                    <textarea required name="details" id="textarea" rows="10" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <?php
                                    $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view' ORDER BY RAND()  LIMIT 8";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); //ok
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($course = $res->fetch_object()) {
                                    ?>
                                        <div class="col-md-12">
                                            <div class="card card-primary collapsed-card">
                                                <div class="card-header">
                                                    <a href="course.php?view=<?php echo $course->id; ?>">
                                                        <h3 class="card-title"><?php echo $cnt; ?>. <?php echo $course->name; ?></h3>
                                                        <div class="card-tools text-right">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="card-body">
                                                    <ul class="list-group">

                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="modules.php?view=<?php echo $course->id; ?>">
                                                                Modules
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="timetables.php?view=<?php echo $course->id; ?>">
                                                                Time Table
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="enrollments.php?view=<?php echo $course->id; ?>">
                                                                Enrolled Students
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        $cnt = $cnt + 1;
                                    } ?>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Code</th>
                                                                <th>Name</th>
                                                                <th>Department</th>
                                                                <th>Manage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$view'";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($courses = $res->fetch_object()) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $cnt; ?></td>
                                                                    <td><?php echo $courses->code; ?></td>
                                                                    <td><?php echo $courses->name; ?></td>
                                                                    <td><?php echo $courses->department_name; ?></td>
                                                                    <td>
                                                                        <a class="badge badge-primary" data-toggle="modal" href="#edit-course-<?php echo $courses->id; ?>">
                                                                            <i class="fas fa-edit"></i>
                                                                            Update
                                                                        </a>
                                                                        <!-- Update Course Modal -->
                                                                        <div class="modal fade" id="edit-course-<?php echo $courses->id; ?>">
                                                                            <div class="modal-dialog  modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Fill All Required Values </h4>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <!-- Update Course Form -->
                                                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                                                            <div class="card-body">
                                                                                                <div class="row">
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Course Name</label>
                                                                                                        <input type="text" required name="name" value="<?php echo $courses->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                                                        <input type="hidden" required name="id" value="<?php echo $courses->id; ?>" class="form-control" id="exampleInputEmail1">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label for="">Course Number / Code</label>
                                                                                                        <input type="text" required name="code" value="<?php echo $courses->code; ?>"" class=" form-control">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="form-group col-md-12">
                                                                                                        <label for="exampleInputPassword1">Course Description</label>
                                                                                                        <textarea required name="details" id="editor-<?php echo $courses->id; ?>" rows="10" class="form-control"><?php echo $courses->details; ?></textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="card-footer text-right">
                                                                                                <button type="submit" name="update_course" class="btn btn-primary">Update</button>
                                                                                            </div>
                                                                                        </form>
                                                                                        <!-- Inline CK Editor Script -->
                                                                                        <script>
                                                                                            CKEDITOR.replace('editor-<?php echo $courses->id; ?>');
                                                                                        </script>
                                                                                        <!-- End Inline Ck Editor Script -->
                                                                                        <!-- End Update Course Form -->
                                                                                    </div>
                                                                                    <div class="modal-footer justify-content-between">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Update Modal -->

                                                                        <a class="badge badge-danger" href="faculty_courses.php?delete=<?php echo $courses->id; ?>&view=<?php echo $faculty->id; ?>">
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
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>