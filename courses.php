<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_Courses WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=courses.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}

require_once('partials/_head.php');

$faculty = $_GET['faculty'];
$ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($row = $res->fetch_object()) {
?>

    <body class="hold-transition sidebar-collapse layout-top-nav">
        <div class="wrapper">

            <!-- Navbar -->
            <?php
            require_once('partials/_faculty_nav.php');
            require_once('partials/_faculty_sidebar.php');

            ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $row->name; ?> Courses</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item active"> Courses </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="container">
                        <section class="content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-right">
                                                <a class="btn btn-outline-success" href="add_course.php?faculty=<?php echo $row->id; ?>">
                                                    Register New Course
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Course Code</th>
                                                        <th>Course Name</th>
                                                        <th>Department Name</th>
                                                        <th>Manage Course</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE faculty_id = '$row->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($course = $res->fetch_object()) {
                                                    ?>
                                                        <tr class="table-row" data-href="view_course.php?department=<?php echo $course->department_id; ?>&view=<?php echo $course->id; ?>&faculty=<?php echo $course->faculty_id; ?>">
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $course->code; ?></td>
                                                            <td><?php echo $course->name; ?></td>
                                                            <td><?php echo $course->department_name; ?></td>
                                                            <td>
                                                                <a class="badge badge-danger" href="courses.php?delete=<?php echo $course->id; ?>&faculty=<?php echo $f->id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete Course Details
                                                                </a>
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
                        </section>
                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
    } ?>
        </div>
        <?php require_once('partials/_scripts.php'); ?>
    </body>

    </html>