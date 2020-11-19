<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_Enrollments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=enrolled_students.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
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
                                <h1 class="m-0 text-dark"> Enrolled Students </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="students.php?faculty=<?php echo $row->id; ?>">Students</a></li>
                                    <li class="breadcrumb-item active"> Enrolled Students </li>
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
                                                <a class="btn btn-outline-success" href="add_student_enrollment.php?faculty=<?php echo $row->id;?>">
                                                    <i class="fas fa-user-plus"></i>
                                                    Add Enrollment
                                                </a>

                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Admission</th>
                                                        <th>Name</th>
                                                        <th>Course</th>
                                                        <th>Module</th>
                                                        <th>Academic Yr</th>
                                                        <th>Sem Enrolled</th>
                                                        <th>Sem Start</th>
                                                        <th>Sem End </th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Enrollments`  WHERE faculty_id = '$row->id' ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($en = $res->fetch_object()) {
                                                    ?>

                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $en->student_adm; ?></td>
                                                            <td><?php echo $en->student_name; ?></td>
                                                            <td><?php echo $en->course_name; ?></td>
                                                            <td><?php echo $en->module_name; ?></td>
                                                            <td><?php echo $en->academic_year_enrolled; ?></td>
                                                            <td><?php echo $en->semester_enrolled; ?></td>
                                                            <td><?php echo date('d M Y', strtotime($en->semester_start)); ?></td>
                                                            <td><?php echo date('d M Y', strtotime($en->semester_end)); ?></td>
                                                            <td>
                                                                <a class="badge badge-primary" href="update_enrolled_student.php?update=<?php echo $en->id; ?>&faculty=<?php echo $row->id;?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update
                                                                </a>

                                                                <a class="badge badge-danger" href="enrolled_students.php?delete=<?php echo $en->id; ?>&faculty=<?php echo $row->id;?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete
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