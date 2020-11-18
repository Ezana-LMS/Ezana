<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

if (isset($_POST['update_school_calendar'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['academic_yr']) && !empty($_POST['academic_yr'])) {
        $academic_yr = mysqli_real_escape_string($mysqli, trim($_POST['academic_yr']));
    } else {
        $error = 1;
        $err = "Academic Year Cannot Be Empty";
    }
    if (isset($_POST['semester_name']) && !empty($_POST['semester_name'])) {
        $semester_name = mysqli_real_escape_string($mysqli, trim($_POST['semester_name']));
    } else {
        $error = 1;
        $err = "Semester Name Cannot Be Empty";
    }
    if (isset($_POST['semester_start']) && !empty($_POST['semester_start'])) {
        $semester_start = mysqli_real_escape_string($mysqli, trim($_POST['semester_start']));
    } else {
        $error = 1;
        $err = "Semester Opening Dates Cannot Be Empty";
    }
    if (isset($_POST['semester_end']) && !empty($_POST['semester_end'])) {
        $semester_end = mysqli_real_escape_string($mysqli, trim($_POST['semester_end']));
    } else {
        $error = 1;
        $err = "Semester Closing  Dates Cannot Be Empty";
    }
    if (isset($_GET['faculty']) && !empty($_GET['faculty'])) {
        $faculty = mysqli_real_escape_string($mysqli, trim($_GET['faculty']));
    } else {
        $error = 1;
        $err = "Faculty ID  Dates Cannot Be Empty";
    }
    if (!$error) {

        $update = $_GET['update'];
        $academic_yr = $_POST['academic_yr'];
        $semester_start = $_POST['semester_start'];
        $semester_name = $_POST['semester_name'];
        $semester_end = $_POST['semester_end'];
        $faculty = $_GET['faculty'];


        $query = "UPDATE ezanaLMS_Calendar SET academic_yr =?, semester_start =?, semester_name =?, semester_end =? WHERE id =?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssss',  $academic_yr, $semester_start, $semester_name, $semester_end, $update);
        $stmt->execute();
        if ($stmt) {
            $success = "Educational Dates Updated" && header("refresh:1; url=school_calendar.php?faculty=$faculty");
        } else {
            $info = "Please Try Again Or Try Later";
        }
    }
}
require_once('partials/_head.php');

?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/_nav.php');
        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            $update = $_GET['update'];
            $ret = "SELECT * FROM `ezanaLMS_Calendar` WHERE id = '$update'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($cal = $res->fetch_object()) {
        ?>
                <!-- /.navbar -->
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark">Update <?php echo $cal->semester_name; ?> Dates</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"> <?php echo $row->name; ?></a></li>
                                        <li class="breadcrumb-item active "> Update School Calendar</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
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
                                                                <label for="">Semester Name</label>
                                                                <input type="text" value="<?php echo $cal->semester_name; ?>" required name="semester_name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Academic Year Name</label>
                                                                <input type="text" value="<?php echo $cal->academic_yr; ?>" required name="academic_yr" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Semester Opening Dates</label>
                                                                <input type="date" value="<?php echo $cal->semester_start; ?>" required name="semester_start" class="form-control" id="exampleInputEmail1">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Semester Closing Dates</label>
                                                                <input type="date" value="<?php echo $cal->semester_end; ?>" required name="semester_end" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <button type="submit" name="update_school_calendar" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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