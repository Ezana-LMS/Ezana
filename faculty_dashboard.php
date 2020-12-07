<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
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
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="text-right">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline">
                                        <input class="form-control mr-sm-2" type="search" placeholder="Type Department Name">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>
                                </nav>
                            </div>
                            <br>
                            <?php
                            $ret = "SELECT * FROM `ezanaLMS_Faculties` ORDER BY `name` ASC ";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute(); //ok
                            $res = $stmt->get_result();
                            $cnt = 1;
                            while ($faculty = $res->fetch_object()) {
                            ?>
                                <div class="col-md-12">
                                    <div class="card card-primary collapsed-card">
                                        <div class="card-header">
                                            <a href="faculty_dashboard.php?faculty=<?php echo $faculty->id; ?>">
                                                <h3 class="card-title"><?php echo $faculty->name; ?></h3>
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
                                                        <span class="badge badge-primary badge-pill">14</span>
                                                    </a>
                                                </li>

                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="">
                                                        Courses
                                                        <span class="badge badge-primary badge-pill"> 14</span>
                                                    </a>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="">
                                                        Modules

                                                        <span class="badge badge-primary badge-pill"> 14</span>
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

                                                        <span class="badge badge-primary badge-pill"> 14</span>
                                                    </a>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="">
                                                        Students

                                                        <span class="badge badge-primary badge-pill"> 14</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } ?>
                            <!-- 
                            <div class="col-md-9">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="jumbotron">
                                            <h1 class="display-4">Hello, world!</h1>
                                            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                                            <hr class="my-4">
                                            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div> -->

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