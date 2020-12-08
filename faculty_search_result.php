<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');
require_once('public/partials/_analytics.php');
if (isset($_POST['add_faculty'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Faculty Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Faculty Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Faculties WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Faculty With This Code Already Exists";
            } else {
                $err = "Faculty Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];

            $query = "INSERT INTO ezanaLMS_Faculties (id, code, name, details) VALUES(?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssss', $id, $code, $name, $details);
            $stmt->execute();
            if ($stmt) {
                $success = "Faculty Added";
            } else {
                //inject alert that profile update task failed
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
        <?php require_once('public/partials/_nav.php'); ?>
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
                            <a href="faculties.php" class="active nav-link">
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
                            <a href="courses.php" class="nav-link">
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
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Faculties</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Faculties</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline" action="faculty_search_result.php" method="GET">
                                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Faculty Name Or Code">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Faculty</button>
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
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Faculty Description</label>
                                                                <textarea id="textarea" name="details" rows="10" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="add_faculty" class=" btn btn-primary">Add Faculty</button>
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
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                $query = $_GET['query'];
                                $min_length = 5;
                                if (strlen($query) >= $min_length) {
                                    $query = htmlspecialchars($query);
                                    $query = mysqli_real_escape_string($mysqli, $query);
                                    $raw_results = mysqli_query($mysqli, "SELECT * FROM ezanaLMS_Faculties WHERE (`name` LIKE '%" . $query . "%') OR (`code` LIKE '%" . $query . "%') ");
                                    if (mysqli_num_rows($raw_results) > 0) {
                                        while ($results = mysqli_fetch_array($raw_results)) {
                                ?>
                                            <div class="col-md-12">
                                                <div class="card card-primary collapsed-card">
                                                    <div class="card-header">
                                                        <a href="faculty_dashboard.php?view=<?php echo $results['id']; ?>">
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
                                                                <a href="">
                                                                    Departments
                                                                </a>
                                                            </li>

                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="">
                                                                    Courses
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="">
                                                                    Modules
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="">
                                                                    Calendar
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="">
                                                                    Lecturers
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="">
                                                                    Students
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
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="jumbotron">
                                            <div class="row">

                                                <div class="col-lg-4 col-6">
                                                    <a href="departments.php">
                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Departments</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-building"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                                <?php echo $departments; ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-6">
                                                    <a href="courses">
                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Courses</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-chalkboard-teacher"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                                <?php echo $courses; ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-6">
                                                    <a href="modules.php">
                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Modules</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-chalkboard"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                                <?php echo $modules; ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-6">
                                                    <a href="school_calendar.php">

                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Calendar</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-calendar"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-6">
                                                    <a href="lecturers.php">

                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Lecturers</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-user-tie"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                                <?php echo $lecs; ?>
                                                            </div>

                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-6">
                                                    <a href="students.php">

                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Students</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-user-graduate"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                                <?php echo $students; ?>
                                                            </div>
                                                        </div>
                                                    </a>
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
                <?php require_once('public/partials/_footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>