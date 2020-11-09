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
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="departments.php?faculty=<?php echo $row->id; ?>">
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
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="courses.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book-reader"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Courses</span>
                                            <span class="info-box-number"><?php echo $courses;?></span>
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
                                            <span class="info-box-number"><?php echo $modules;?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- School Calendar -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="school_calendar.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">School Calendars</span>
                                            <span class="info-box-number"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Time Table-->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="timetables.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-week"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Time Tables</span>
                                            <span class="info-box-number"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Class Recordings -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="class_recordings.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-video"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Class Recordings</span>
                                            <span class="info-box-number"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Past Exam Papers -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="past_exam_papers.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-newspaper"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Past Exam Papers</span>
                                            <span class="info-box-number"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Past Exam Papers Solutions -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="past_exam_paper_solutions.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-paste"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Past Exam Papers Solutions</span>
                                            <span class="info-box-number"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Department Reports -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="faculty_reports.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-filter"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Faculty Reports</span>
                                            <span class="info-box-number"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Lecturers -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="lecturers.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Lecturers</span>
                                            <span class="info-box-number"><?php echo $lecs;?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Students -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="students.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Students</span>
                                            <span class="info-box-number"><?php echo $students;?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Students Groups -->
                            <div class="col-12 col-sm-6 col-md-4">
                                <a href="student_groups.php">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Students Groups</span>
                                            <span class="info-box-number"></span>
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