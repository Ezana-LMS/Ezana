<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_GroupsAssignments WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=student_group_assignments.php?faculty=$faculty");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/_head.php');

?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <?php
        require_once('partials/_faculty_nav.php');
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($f = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Group Assignments</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item"><a href="student_groups.php?faculty=<?php echo $f->id; ?>">Student Groups</a></li>
                                    <li class="breadcrumb-item active"> Assignments </li>
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
                                                <a class="btn btn-outline-success" href="add_group_project.php?faculty=<?php echo $f->id; ?>&type=Project">
                                                    Add Group Assignment
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Group Code </th>
                                                        <th>Group Name</th>
                                                        <th>Uploaded At</th>
                                                        <th>Submission Deadline</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE faculty_id ='$f->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($gcode = $res->fetch_object()) {
                                                    ?>

                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $gcode->group_code; ?></td>
                                                            <td><?php echo $gcode->group_name; ?></td>
                                                            <td><?php echo $gcode->created_at; ?></td>
                                                            <td><?php echo $gcode->submitted_on; ?></td>
                                                            <td>
                                                                <a class="badge badge-success" href="view_group_project.php?view=<?php echo $gcode->id; ?>&faculty=<?php echo $f->id;?>">
                                                                    <i class="fas fa-eye"></i>
                                                                    View
                                                                </a>
                                                                <a class="badge badge-primary" href="update_group_project.php?update=<?php echo $gcode->id; ?>&faculty=<?php echo $f->id;?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update
                                                                </a>
                                                                <a class="badge badge-warning" href="grade_group_project.php?project_id=<?php echo $gcode->id; ?>&group_name=<?php echo $gcode->group_name; ?>&group_code=<?php echo $gcode->group_code; ?>&faculty=<?php echo $f->id;?>">
                                                                    <i class="fas fa-check"></i>
                                                                    Grade Project
                                                                </a>
                                                                <a class="badge badge-danger" href="student_group_assignments.php?delete=<?php echo $gcode->id; ?>&faculty=<?php echo $f->id; ?>">
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