<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');

/* Add Past papers */

/* Update past paper */

/* Delete Past paper */

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
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Past Paper</button>
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
                                                   <!-- Form -->
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
                                                        <a href="module_enrollments.php?view=<?php echo $mod->id; ?>">
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
                                                                <h3 class="card-title">Past Papers And Solutions</h3>
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
                                                                            ?> ~ By <?php echo $not->created_by; ?>
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