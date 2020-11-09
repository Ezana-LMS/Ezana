<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('partials/_analytics.php');
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <?php
        require_once('partials/_faculty_nav.php');
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {

            require_once('partials/_faculty_sidebar.php');
        ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"> <?php echo $row->name; ?> Dashboard</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                    <li class="breadcrumb-item active"> <?php echo $row->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <div class="row">

                            <!-- Departments -->
                            <div class="col-12 col-sm-6 col-md-6">
                                <a href="departments.php?faculty=<?php echo $row->id;?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-building" aria-hidden="true"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Departments</span>
                                            <span class="info-box-number">
                                                <?php echo $departments; ?>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Courses  -->
                            <div class="col-12 col-sm-6 col-md-6">
                                <a href="courses.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book-reader"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Courses</span>
                                            <span class="info-box-number"><?php echo $courses; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Modules -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="modules.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Modules</span>
                                            <span class="info-box-number"><?php echo $modules; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Students Enrolled -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="modules.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-graduate"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Students</span>
                                            <span class="info-box-number"><?php echo $modules; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Lecturers -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="modules.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Lecturers</span>
                                            <span class="info-box-number"><?php echo $modules; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>