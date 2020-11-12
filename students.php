<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['students'];
    $adn = "DELETE FROM ezanaLMS_Students WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=students.php?faculty=$faculty");
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
                                <h1 class="m-0 text-dark"><?php echo $f->name; ?> Lecturers</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item active"> Lecturers </li>
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
                                                <a class="btn btn-outline-success" href="add_student.php?faculty=<?php echo $f->id; ?>">
                                                    <i class="fas fa-user-plus"></i>
                                                    Register New Student
                                                </a>

                                                <a class="btn btn-outline-primary" href="import_students.php?faculty=<?php echo $f->id; ?>">
                                                    <i class="fas fa-file-excel"></i>
                                                    Import Students From .XLS File
                                                </a>

                                                <a class="btn btn-outline-primary" href="enrolled_students.php?faculty=<?php echo $f->id; ?>">
                                                    <i class="fas fa-file-excel"></i>
                                                    Enroll Students
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Adm No</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>ID / Passport No</th>
                                                        <th>Gender</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Students` WHERE faculty_id = '$f->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($std = $res->fetch_object()) {
                                                    ?>

                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $std->admno; ?></td>
                                                            <td><?php echo $std->name; ?></td>
                                                            <td><?php echo $std->email; ?></td>
                                                            <td><?php echo $std->phone; ?></td>
                                                            <td><?php echo $std->idno; ?></td>
                                                            <td><?php echo $std->gender; ?></td>
                                                        <tr class="table-row" data-href="view_student.php?view=<?php echo $std->id; ?>&faculty=<?php echo $f->id; ?>">

                                                            <a class="badge badge-primary" href="update_student.php?update=<?php echo $std->id; ?>">
                                                                <i class="fas fa-edit"></i>
                                                                Update
                                                            </a>

                                                            <a class="badge badge-danger" href="manage_students.php?delete=<?php echo $std->id; ?>">
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