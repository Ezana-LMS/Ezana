<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//Delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_Lecturers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=lecturers.php?faculty=$faculty");
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
        while ($row = $res->fetch_object()) {
            require_once('partials/_faculty_sidebar.php')
        ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $row->name; ?> Lecturers</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
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
                                                <a class="btn btn-outline-primary" href="add_lecturer.php?faculty=<?php echo $row->id; ?>">
                                                    Add New Lecturer
                                                </a>
                                                <a class="btn btn-outline-primary" href="import_lecturer.php?faculty=<?php echo $row->id; ?>">
                                                    Import Lecturers Details
                                                </a>
                                                <a class="btn btn-outline-primary" href="assign_lecturer_module.php?faculty=<?php echo $row->id; ?>">
                                                    Lecturers Modules Assigns
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Lec Number</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>ID / Passport No</th>
                                                        <th>Manage </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$row->id'  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($lec = $res->fetch_object()) {
                                                    ?>
                                                        <tr class="table-row" data-href="view_lecturer.php?view=<?php echo $lec->id; ?>&faculty=<?php echo $row->id; ?>">
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $lec->number; ?></td>
                                                            <td><?php echo $lec->name; ?></td>
                                                            <td><?php echo $lec->email; ?></td>
                                                            <td><?php echo $lec->phone; ?></td>
                                                            <td><?php echo $lec->idno; ?></td>
                                                            <td>
                                                                <a class="badge badge-primary" href="update_lecturer.php?update=<?php echo $lec->id; ?>&faculty=<?php echo $row->id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                    Update Lecturer
                                                                </a>

                                                                <a class="badge badge-danger" href="lecturers.php?delete=<?php echo $lec->id; ?>&faculty=<?php echo $row->id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete Lecturer
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