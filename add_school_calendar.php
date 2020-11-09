<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
if (isset($_POST['add_school_calendar'])) {
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
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Calendar WHERE  semester_name='$semester_name'  ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($semester_name == $row['semester_name']) {
                $err =  "Semester Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $academic_yr = $_POST['academic_yr'];
            $semester_start = $_POST['semester_start'];
            $semester_name = $_POST['semester_name'];
            $semester_end = $_POST['semester_end'];

            $query = "INSERT INTO ezanaLMS_Calendar (id, academic_yr, semester_start, semester_name, semester_end) VALUES(?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('sssss', $id, $academic_yr, $semester_start, $semester_name, $semester_end);
            $stmt->execute();
            if ($stmt) {
                $success = "Educational Dates Added" && header("refresh:1; url=add_school_calendar.php");
            } else {
                $info = "Please Try Again Or Try Later";
            }
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
        ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Add Important Dates</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="school_calendar.php">School Calendar</a></li>
                                <li class="breadcrumb-item active">Add Important Dates</li>
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
                                                        <input type="text" required name="semester_name" class="form-control" id="exampleInputEmail1">
                                                        <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Academic Year Name</label>
                                                        <input type="text" required name="academic_yr" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="">Semester Opening Dates</label>
                                                        <input type="date" required name="semester_start" class="form-control" id="exampleInputEmail1">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="">Semester Closing Dates</label>
                                                        <input type="date" required name="semester_end" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" name="add_school_calendar" class="btn btn-primary">Submit</button>
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
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>