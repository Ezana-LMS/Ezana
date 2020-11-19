<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
if (isset($_POST['update'])) {

    $faculty = $_GET['faculty'];
    $id = $_GET['id'];

    $paper_name = $_POST['paper_name'];
    $paper_visibility = $_POST['paper_visibility'];
    $solution_visibility = $_POST['solution_visibility'];

    $query = "UPDATE ezanaLMS_PastPapers SET  paper_name = ?, paper_visibility = ?, solution_visibility = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $paper_name, $paper_visibility, $solution_visibility, $id);
    $stmt->execute();
    if ($stmt) {
        $success = "Past Paper Updated" && header("refresh:1; url=past_exam_papers.php?faculty=$faculty");
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
        $id = $_GET['id'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            $ret = "SELECT * FROM `ezanaLMS_PastPapers`  WHERE  id = '$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($pastExas = $res->fetch_object()) {
                require_once('partials/_faculty_sidebar.php');
        ?>
                <!-- /.navbar -->
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Update Past Exam Papers</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="past_exam_papers.php?faculty=<?php echo $row->id; ?>">Past Papers</a></li>
                                        <li class="breadcrumb-item active">Update</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="container">
                            <section class="content">
                                <div class="container-fluid">
                                    <div class="col-md-12">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">Fill All Required Fields</h3>
                                            </div>
                                            <form method="post" enctype="multipart/form-data" role="form">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="">Exam Paper Name</label>
                                                            <input type="text" value="<?php echo $pastExas->paper_name; ?>" name="paper_name" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Exam Paper Visibility / Availability</label>
                                                            <select class='form-control basic' name="paper_visibility">
                                                                <option selected><?php echo $pastExas->paper_visibility; ?></option>
                                                                <option>Available</option>
                                                                <option>Hidden</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="">Exam Paper Solution Visibility / Availability</label>
                                                            <select class='form-control basic' name="solution_visibility">
                                                                <option selected><?php echo $pastExas->solution_visibility; ?></option>
                                                                <option selected>Available</option>
                                                                <option>Hidden</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button type="submit" name="update" class="btn btn-primary">Update Exam Paper</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
        <?php require_once('partials/_footer.php');
            }
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>