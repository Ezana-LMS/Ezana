<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('partials/_faculties_analytics.php');
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
                                <h1 class="m-0 text-dark"> <?php echo $row->name; ?> Reports</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->name; ?>"><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item active"> Reports </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <!-- Departments -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="departments.php?faculty=<?php echo $row->id; ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-building" aria-hidden="true"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Departments Reports</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Courses  -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="courses.php?faculty=<?php echo $row->id; ?>">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book-reader"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Courses Reports</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Modules -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="modules.php?faculty=<?php echo $row->id; ?>">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Modules Reports</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- School Calendar -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="school_calendar.php?faculty=<?php echo $row->id; ?>">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">School Calendars Reports</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Lecturers -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="lecturers.php?faculty=<?php echo $row->id; ?>">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Lecturers Reports</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Students -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="students.php?faculty=<?php echo $row->id; ?>">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Students Reports</span>
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