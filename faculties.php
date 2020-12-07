<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('dashboard/partials/_head.php');

?>

<body>

    <div id="wrapper">

        <?php
        require_once('dashboard/partials/_topbar.php');
        ?>

        <div id="sidebar-wrapper" class="collapse sidebar-collapse">

            <div id="search">
                <form action="search_results.php" method="GET">
                    <input class="form-control input-sm" type="text" name="search" placeholder="Enter Faculty Name" />
                    <button type="search" name="query" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <nav id="sidebar">

                <ul id="main-nav" class="open-active">

                    <li>
                        <a href="dashboard.php">
                            <i class="fa fa-dashboard"></i>
                            Main Dashboard
                        </a>
                    </li>
                    <li class="active">
                        <a href="faculties.php">
                            <i class="fa fa-university"></i>
                            Faculties
                        </a>
                    </li>
                    <li>
                        <a href="departments.php">
                            <i class="fa fa-building"></i>
                            Departments
                        </a>
                    </li>
                    <li>
                        <a href="courses.php">
                            <i class="fa fa-chalkboard-teacher"></i>
                            Courses
                        </a>
                    </li>
                    <li>
                        <a href="modules.php">
                            <i class="fas fa-chalkboard"></i>
                            Modules
                        </a>
                    </li>
                    <li>
                        <a href="lecturers.php">
                            <i class="fas  fa-user-tie"></i>
                            Lecturers
                        </a>
                    </li>
                    <li>
                        <a href="students.php">
                            <i class="fas fa-user-graduate"></i>
                            Students
                        </a>
                    </li>

                </ul>
            </nav> <!-- #sidebar -->
        </div>

        <div id="content">

            <div id="content-header">
                <h1>Faculties</h1>
            </div> <!-- #content-header -->

            <div id="content-container">

                <div class="row">
                    <?php
                    /* Get A List Of The Faculties */
                    $ret = "SELECT * FROM `ezanaLMS_Faculties` ORDER BY `ezanaLMS_Faculties`.`name` ASC   ";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    while ($single_faculty = $res->fetch_object()) {
                    ?>
                        <div class="col-md-4">
                            <div class="portlet">
                                <div class="portlet-header">
                                    <h3>
                                        <?php echo $single_faculty->name; ?> - <?php echo $single_faculty->code; ?>
                                    </h3>
                                    <br>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#FacultyDetails">
                                        Faculty Details
                                    </button>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="FacultyDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once("dashboard/partials/_footer.php");
    require_once('dashboard/partials/_scripts.php');
    ?>
</body>

</html>