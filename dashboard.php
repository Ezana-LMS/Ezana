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

        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"> <?php echo $_SESSION['name']; ?> Dashboard </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <div class="row">

                        <!-- Faculties -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="faculties.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-university"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Faculties</span>
                                        <span class="info-box-number"><?php echo $faculties; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>


                        <!-- Departments -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="total_departments.php">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fa fa-building" aria-hidden="true"></i></span>
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
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="total_courses.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Courses</span>
                                        <span class="info-box-number"><?php echo $courses; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Modules -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="total_modules.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chalkboard"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Modules</span>
                                        <span class="info-box-number"><?php echo $modules; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Lecturers -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="total_lecs.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-tie"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Lecturers</span>
                                        <span class="info-box-number"><?php echo $lecs; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Students -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="total_students.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Students</span>
                                        <span class="info-box-number"><?php echo $students; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="text-right">
                                        <a class="btn btn-outline-success" href="add_faculties.php">
                                            Register New Faculty
                                        </a>
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <table id="example1" class=" table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Faculty Code Number</th>
                                                <th>Faculty Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM `ezanaLMS_Faculties`  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($faculty = $res->fetch_object()) {
                                            ?>
                                                <tr class="table-row" data-href="faculty_dashboard.php?faculty=<?php echo $faculty->id; ?>">
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $faculty->code; ?></td>
                                                    <td><?php echo $faculty->name; ?></td>
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
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>