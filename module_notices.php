<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Module Notice */

if (isset($_POST['add_notice'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['module_name']) && !empty($_POST['module_name'])) {
        $module_name = mysqli_real_escape_string($mysqli, trim($_POST['module_name']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['module_code']) && !empty($_POST['module_code'])) {
        $module_code = mysqli_real_escape_string($mysqli, trim($_POST['module_code']));
    } else {
        $error = 1;
        $err = "Module Name Cannot Be Empty";
    }
    if (isset($_POST['announcements']) && !empty($_POST['announcements'])) {
        $announcements = mysqli_real_escape_string($mysqli, trim($_POST['announcements']));
    } else {
        $error = 1;
        $err = "Noctices Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $module_name  = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $created_at = date('d M Y');
        $faculty_id = $_POST['faculty_id'];
        $module_id = $_POST['module_id'];
        $query = "INSERT INTO ezanaLMS_ModulesAnnouncements (id, module_name, module_code, announcements, created_by, created_at, faculty_id) VALUES(?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssssss', $id, $module_name, $module_code, $announcements, $created_by, $created_at, $faculty_id);
        $stmt->execute();
        if ($stmt) {
            $success = "Posted" && header("refresh:1; url=module_notices.php?view=$module_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}
/* Update Module Notice */
if (isset($_POST['update_notice'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['announcements']) && !empty($_POST['announcements'])) {
        $announcements = mysqli_real_escape_string($mysqli, trim($_POST['announcements']));
    } else {
        $error = 1;
        $err = "Notices Cannot Be Empty";
    }
    if (!$error) {
        $announcements = $_POST['announcements'];
        $created_by = $_POST['created_by'];
        $created_at = date('d M Y g:i');
        $module_id = $_POST['module_id'];
        $id = $_POST['id'];
        $query = "UPDATE  ezanaLMS_ModulesAnnouncements SET announcements =?, created_by =?, created_at =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssss', $announcements, $created_by, $created_at, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Posted" && header("refresh:1; url=module_notices.php?view=$module_id");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Delete Module Notice */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $view = $_GET['view'];
    $adn = "DELETE FROM ezanaLMS_ModulesAnnouncements WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=module_notices.php?view=$view");
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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
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
                                <a href="courses.php" class=" nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class="active nav-link">
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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="modules.php">Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
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
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Module Notice</button>
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
                                                    <!-- Add Module Notices Form -->
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Announcement Posted By</label>
                                                                    <?php
                                                                    $id = $_SESSION['id'];
                                                                    $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id = '$id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($user = $res->fetch_object()) {
                                                                    ?>
                                                                        <input type="text" required name="created_by" value="<?php echo $user->name; ?>" class="form-control" id="exampleInputEmail1">
                                                                    <?php
                                                                    } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputPassword1">Module Announcements</label>
                                                                    <textarea required id="textarea" name="announcements" rows="20" class="form-control"></textarea>
                                                                    <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->name; ?>" required name="module_name" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->code; ?>" required name="module_code" class="form-control">
                                                                    <input type="hidden" value="<?php echo $mod->id; ?>" required name="module_id" class="form-control">
                                                                    <input type="hidden" required name="faculty_id" value="<?php echo $mod->faculty_id; ?>" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="add_notice" class="btn btn-primary">Add Notice</button>
                                                        </div>
                                                    </form>
                                                    <!-- End Module Notice Form -->
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
                                                <a href="module.php?view=<?php echo $mod->id; ?>">
                                                    <h3 class="card-title"><?php echo $mod->name; ?></h3>
                                                    <div class="card-tools text-right">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="card-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="module_notices.php?view=<?php echo $mod->id; ?>">
                                                            Notices
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="pastpapers.php?view=<?php echo $mod->id; ?>">
                                                            Past Papers
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="course_materials.php?view=<?php echo $mod->id; ?>">
                                                            Course Materials
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="class_recordings.php?view=<?php echo $mod->id; ?>">
                                                            Class Recordings
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="student_groups.php?view=<?php echo $mod->id; ?>">
                                                            Student Groups
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="module_enrollments.php?view=<?php echo $mod->code; ?>">
                                                            Module Enrollments
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
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card ">
                                                            <div class="card-header">
                                                                <h3 class="card-title">Module Notices And Announcements</h3>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements` WHERE module_code  = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                $cnt = 1;
                                                                while ($not = $res->fetch_object()) {
                                                                ?>
                                                                    <div class="d-flex w-100 justify-content-between">
                                                                        <h5 class="mb-1"></h5>
                                                                        <small><?php echo $not->created_at; ?></small>
                                                                    </div>
                                                                    <small>
                                                                        <?php
                                                                        echo $not->announcements;
                                                                        ?>
                                                                        <div class="row">
                                                                            <a class="badge badge-primary" data-toggle="modal" href="#update-<?php echo $mod->id; ?>">
                                                                                <i class="fas fa-edit"></i>
                                                                                Update
                                                                            </a>
                                                                            <!-- Udpate Notice Modal -->
                                                                            <div class="modal fade" id="update-<?php echo $mod->id; ?>">
                                                                                <div class="modal-dialog  modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h4 class="modal-title">Fill All Required Values </h4>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <!-- Add Module Notices Form -->
                                                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                                                <div class="card-body">
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-4">
                                                                                                            <label for="">Module Name</label>
                                                                                                            <input readonly type="text" value="<?php echo $not->module_name; ?>" id="ModuleCode" required name="module_code" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-4">
                                                                                                            <label for="">Module Code</label>
                                                                                                            <input readonly type="text" id="ModuleCode" value="<?php echo $not->module_code; ?>" required name="module_code" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-4">
                                                                                                            <label for="">Announcement Posted By</label>
                                                                                                            <input type="text" required name="created_by" value="<?php echo $not->created_by; ?>" class="form-control" id="exampleInputEmail1">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-12">
                                                                                                            <label for="exampleInputPassword1">Module Announcements</label>
                                                                                                            <textarea required id="<?php echo $not->id; ?>" name="announcements" rows="20" class="form-control"><?php echo $not->announcements; ?></textarea>
                                                                                                            <input type="hidden" required name="id" value="<?php echo $not->id; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="module_id" value="<?php echo $mod->id; ?>" class="form-control">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- Inline ck editor -->
                                                                                                <script>
                                                                                                    CKEDITOR.replace('<?php echo $not->id; ?>');
                                                                                                </script>

                                                                                                <div class="card-footer text-right">
                                                                                                    <button type="submit" name="update_notice" class="btn btn-primary">Update Notice</button>
                                                                                                </div>
                                                                                            </form>
                                                                                            <!-- End Module Notice Form -->
                                                                                        </div>
                                                                                        <div class="modal-footer justify-content-between">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <a class="badge badge-danger" href="module_notices.php?delete=<?php echo $not->id; ?>&view=<?php echo $mod->id; ?>">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </a>
                                                                        </div>
                                                                        <hr>
                                                                    </small>
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
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php');
            } ?>
            </div>
        </div>
    <!-- ./wrapper -->
    <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>