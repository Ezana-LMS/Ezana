<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Time Table */
if (isset($_POST['add_class'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Code Cannot Be Blank";
    }
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Blank";
    }
    if (isset($_POST['lecturer']) && !empty($_POST['lecturer'])) {
        $lecturer = mysqli_real_escape_string($mysqli, trim($_POST['lecturer']));
    } else {
        $error = 1;
        $err = "Lecturer Assigned Cannot Be Blank";
    }
    if (isset($_POST['day']) && !empty($_POST['day'])) {
        $day = mysqli_real_escape_string($mysqli, trim($_POST['day']));
    } else {
        $error = 1;
        $err = "Day Cannot Name Be Blank";
    }
    if (isset($_POST['time']) && !empty($_POST['time'])) {
        $time = mysqli_real_escape_string($mysqli, trim($_POST['time']));
    } else {
        $error = 1;
        $err = "Time Cannot Name Be Blank";
    }

    if (!$error) {
        $id = $_POST['id'];
        $faculty = $_POST['faculty'];
        $course_code = $_POST['course_code'];
        $course_name = $_POST['course_name'];
        $module_code = $_POST['module_code'];
        $module_name = $_POST['module_name'];
        $lecturer  = $_POST['lecturer'];
        $day = $_POST['day'];
        $time = $_POST['time'];
        $room = $_POST['room'];
        $link = $_POST['link'];
        /* Course Id  */
        $course_id = $_POST['course_id'];
        $query = "INSERT INTO ezanaLMS_TimeTable (id, faculty_id, course_code, course_name, module_code, module_name, lecturer, day, time, room, link) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssssssss', $id, $faculty, $course_code, $course_name, $module_code, $module_name, $lecturer, $day, $time, $room, $link);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Added" && header("refresh:1; url=timetables.php?view=$course_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/*  Update Time Table*/
if (isset($_POST['update_class'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['day']) && !empty($_POST['day'])) {
        $day = mysqli_real_escape_string($mysqli, trim($_POST['day']));
    } else {
        $error = 1;
        $err = "Day Cannot Name Be Blank";
    }
    if (isset($_POST['time']) && !empty($_POST['time'])) {
        $time = mysqli_real_escape_string($mysqli, trim($_POST['time']));
    } else {
        $error = 1;
        $err = "Time Cannot Name Be Blank";
    }

    if (!$error) {
        $id = $_POST['id'];
        $day = $_POST['day'];
        $time = $_POST['time'];
        $room = $_POST['room'];
        $link = $_POST['link'];
        /* Course Id  */
        $course_id = $_POST['course_id'];
        $query = "UPDATE  ezanaLMS_TimeTable SET  day =?, time =?,  room =?, link =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss',  $day, $time, $room, $link, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Class Updated" && header("refresh:1; url=timetables.php?view=$course_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Class */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_TimeTable WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=timetables.php?view=$view");
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
        $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($course = $res->fetch_object()) {
            $CourseCode = $course->code;
            /* Time Tables Under This Course */
            $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE code = '$CourseCode'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($course = $res->fetch_object()) {
        ?>
                <!-- /.navbar -->
                <!-- Main Sidebar Container -->
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <?php require_once('public/partials/_brand.php'); ?>
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
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-cogs"></i>
                                        <p>
                                            System Settings
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="reports.php" class="nav-link">
                                                <i class="fas fa-angle-right nav-icon"></i>
                                                <p>Reports</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="system_settings.php" class="nav-link">
                                                <i class="fas fa-angle-right nav-icon"></i>
                                                <p>System Settings</p>
                                            </a>
                                        </li>
                                    </ul>
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
                                    <h1 class="m-0 text-dark"><?php echo $course->name; ?> Time Table</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="courses.php">Courses</a></li>
                                        <li class="breadcrumb-item active"><?php echo $course->name; ?> TT</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="text-left">
                                    <nav class="navbar navbar-light bg-light col-md-12">
                                        <form class="form-inline" action="module_search_result.php" method="GET">
                                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Class</button>
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
                                                        <!-- Add Time Table Form -->
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <!-- Hidden values -->
                                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                        <input type="hidden" required name="faculty" value="<?php echo $course->faculty_id; ?>" class="form-control">
                                                                        <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                                                        <input type="hidden" required name="course_code" value="<?php echo $course->code; ?>" class="form-control">
                                                                        <input type="hidden" required name="course_name" value="<?php echo $course->name; ?>" class="form-control">
                                                                        <!-- Fetch Module Code, Module Name And Lecturer Using Ajax -->
                                                                        <label for="">Module Code</label>
                                                                        <select class='form-control basic' id="ModuleCode" onchange="getAllocatedModuleDetails(this.value);" name="module_code">
                                                                            <option selected>Select Module Code</option>
                                                                            <?php
                                                                            $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE course_id = '$course->id'  ";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($module_allocations = $res->fetch_object()) {
                                                                            ?>
                                                                                <option><?php echo $module_allocations->module_code; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Module Name</label>
                                                                        <input type="text" id="ModuleAllocatedLecName" readonly required name="lecturer" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Lecturer Name</label>
                                                                        <input type="text" id="AllocatedModuleName" readonly required name="module_name" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Class Day</label>
                                                                        <select class='form-control basic' name="day">
                                                                            <option selected>Select Day</option>
                                                                            <option>Sunday</option>
                                                                            <option>Monday</option>
                                                                            <option>Tuesday</option>
                                                                            <option>Wednesday</option>
                                                                            <option>Thursday</option>
                                                                            <option>Friday</option>
                                                                            <option>Saturday</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Time</label>
                                                                        <input type="text" required name="time" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Room</label>
                                                                        <input type="text" required name="room" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="exampleInputPassword1">Class Link <small class="text-danger">If Its Virtual Class </small></label>
                                                                        <input type="text" name="link" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer text-right">
                                                                <button type="submit" name="add_class" class="btn btn-primary">Create Class</button>
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
                                        <div class="col-md-12">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <a href="course.php?view=<?php echo $course->id; ?>">
                                                        <h3 class="card-title"><?php echo $course->name; ?></h3>
                                                        <div class="card-tools text-right">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="card-body">
                                                    <ul class="list-group">

                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="course_modules.php?view=<?php echo $course->id; ?>">
                                                                Modules
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="timetables.php?view=<?php echo $course->id; ?>">
                                                                TimeTable
                                                            </a>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="enrollments.php?view=<?php echo $course->id; ?>">
                                                                Enrollments
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- <div class="text-right">
                                                    <a href="courses.php" class="float-left btn btn-outline-success">
                                                        <i class="fas fa-arrow-left"></i>
                                                        Back
                                                    </a>
                                                    <span class="btn btn-outline-warning text-success">
                                                        <a class="float-right" data-toggle="modal" href="#update-course-<?php echo $course->id; ?>">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                    </span>
                                                </div> -->
                                                <!-- Update Course Modal -->
                                                <div class="modal fade" id="update-course-<?php echo $course->id; ?>">
                                                    <div class="modal-dialog  modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Fill All Values</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--End Update Course Modal -->
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table id="export-dt" class="table table-bordered table-striped responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>Module Code</th>
                                                                    <th>Module Name </th>
                                                                    <th>Lecturer</th>
                                                                    <th>Day</th>
                                                                    <th>Time</th>
                                                                    <th>Room</th>
                                                                    <th>Link</th>
                                                                    <th>Manage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_TimeTable`  WHERE course_code = '$CourseCode'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                $cnt = 1;
                                                                while ($tt = $res->fetch_object()) {
                                                                ?>

                                                                    <tr>
                                                                        <td><?php echo $tt->module_code; ?></td>
                                                                        <td><?php echo $tt->module_name; ?></td>
                                                                        <td><?php echo $tt->lecturer; ?></td>
                                                                        <td><?php echo $tt->day; ?></td>
                                                                        <td><?php echo $tt->time; ?></td>
                                                                        <td><?php echo $tt->room; ?></td>

                                                                        <td>
                                                                            <?php if ($tt->classlink != '') {
                                                                                echo "<a href='$tt->classlink' target='_blank'>Open Link</a>";
                                                                            } ?>
                                                                        </td>
                                                                        <td>
                                                                            <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $tt->id; ?>">
                                                                                <i class="fas fa-edit"></i>
                                                                                Update
                                                                            </a>
                                                                            <!-- Update Departmental Memo Modal -->
                                                                            <div class="modal fade" id="update-<?php echo $tt->id; ?>">
                                                                                <div class="modal-dialog  modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h4 class="modal-title">Update <?php echo $tt->module_name; ?> Time Table;</h4>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                                                <div class="card-body">
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-3">
                                                                                                            <!-- Hidden values -->
                                                                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="course_id" value="<?php echo $course->id; ?>" class="form-control">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-4">
                                                                                                            <label for="">Class Day</label>
                                                                                                            <select class='form-control basic' name="day">
                                                                                                                <option selected><?php echo $tt->day; ?></option>
                                                                                                                <option>Sunday</option>
                                                                                                                <option>Monday</option>
                                                                                                                <option>Tuesday</option>
                                                                                                                <option>Wednesday</option>
                                                                                                                <option>Thursday</option>
                                                                                                                <option>Friday</option>
                                                                                                                <option>Saturday</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-4">
                                                                                                            <label for="">Time</label>
                                                                                                            <input type="text" value="<?php echo $tt->time; ?>" required name="time" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-4">
                                                                                                            <label for="">Room</label>
                                                                                                            <input type="text" value="<?php echo $tt->room; ?>" required name="room" class="form-control">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-12">
                                                                                                            <label for="exampleInputPassword1">Class Link <small class="text-danger">If Its Virtual Class </small></label>
                                                                                                            <input type="text" value="<?php echo $tt->link; ?>" name="link" class="form-control">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="card-footer text-right">
                                                                                                    <button type="submit" name="update_class" class="btn btn-primary">Create Class</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                        <div class="modal-footer justify-content-between">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <a class="badge badge-danger" href="#delete-<?php echo $tt->id; ?>" data-toggle="modal">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </a>
                                                                            <!-- Delete Confirmation Modal -->
                                                                            <div class="modal fade" id="delete-<?php echo $tt->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">CONFIRM</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body text-center text-danger">
                                                                                            <h4>Delete <?php echo $tt->module_name; ?> ?</h4>
                                                                                            <br>
                                                                                            <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                            <a href="timetables.php?delete=<?php echo $tt->id; ?>&view=<?php echo $course->id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- End Delete Confirmation Modal -->
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
                <?php
                require_once('public/partials/_footer.php');
            }
        } ?>
                    </div>
                </div>
                <!-- ./wrapper -->
                <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>