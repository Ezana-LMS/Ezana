<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $faculty = $_GET['faculty'];
    $adn = "DELETE FROM ezanaLMS_PastPapers WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=past_exam_papers.php?faculty=$faculty");
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
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($f = $res->fetch_object()) {
            require_once('partials/_faculty_nav.php');
        ?>
            <!-- /.navbar -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $f->name; ?> Past Exam Papers</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                    <li class="breadcrumb-item active"> Past Exam Papers </li>
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
                                                <a class="btn btn-outline-success" href="past_exams.php">
                                                    <i class="fas fa-plus"></i>
                                                    <i class="fas fa-file-upload"></i>
                                                    Upload Past Paper
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Course</th>
                                                        <th>Module</th>
                                                        <th>Date Uploaded</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_PastPapers`  WHERE  pastpaper_type  != 'Solution' AND faculty_id = '$f->id' ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($pastExas = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $pastExas->course_name; ?></td>
                                                            <td><?php echo $pastExas->module_name; ?></td>
                                                            <td><?php echo date('d M Y - g:i', strtotime($pastExas->created_at)); ?></td>
                                                            <td>
                                                                <a class="badge badge-success" target="_blank" href="EzanaLMSData/PastPapers/<?php echo $pastExas->pastpaper; ?>">
                                                                    <i class="fas fa-download"></i>
                                                                    Download Papers
                                                                </a>
                                                                <a class="badge badge-danger" href="past_exam_papers.php?delete=<?php echo $pastExas->id; ?>&faculty=<?php echo $f->id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                    Delete Paper
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