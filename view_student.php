<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
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

            $view = $_GET['view'];
            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$view' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($std = $res->fetch_object()) {
                //Get Default Profile Picture
                if ($std->profile_pic == '') {
                    $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/logo.jpeg' alt='User profile picture'>";
                } else {
                    $dpic = "<img class='profile-user-img img-fluid img-circle' src='dist/img/students/$std->profile_pic' alt='User profile picture'>";
                }
        ?>
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-dark"><?php echo $std->name; ?> Profile</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="students.php?faculty=<?php echo $f->id; ?>">Students</a></li>
                                        <li class="breadcrumb-item active"> Profile </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="container">
                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card card-primary card-outline">
                                                <div class="card-body box-profile">
                                                    <div class="text-center">
                                                        <?php echo $dpic; ?>
                                                    </div>

                                                    <h3 class="profile-username text-center"><?php echo $std->name; ?></h3>

                                                    <p class="text-muted text-center"><?php echo $std->admno; ?></p>

                                                    <ul class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <b>Email: </b> <a class="float-right"><?php echo $std->email; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>ID / Passport: </b> <a class="float-right"><?php echo $std->idno; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Phone: </b> <a class="float-right"><?php echo $std->phone; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Address</b> <a class="float-right"><?php echo $std->adr; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>DOB</b> <a class="float-right"><?php echo $std->dob; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Gender</b> <a class="float-right"><?php echo $std->gender; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Added At </b> <a class="float-right"><?php echo $std->created_at; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Updated At </b> <a class="float-right"><?php echo $std->updated_at; ?></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Account Status</b>
                                                            <a class="float-right">
                                                                <?php
                                                                if ($std->acc_status == 'Active') {
                                                                    echo "<span class='badge badge-success'>$std->acc_status</span>";
                                                                } else {
                                                                    echo "<span class='badge badge-danger'>$std->acc_status</span>";
                                                                }
                                                                ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card">
                                                <div class="card-header p-2">
                                                    <ul class="nav nav-pills">
                                                        <li class="nav-item"><a class="nav-link active" href="#modules" data-toggle="tab">Modules Enrolled</a></li>
                                                        <li class="nav-item"><a class="nav-link" href="#courses" data-toggle="tab">Courses Enrolled</a></li>

                                                    </ul>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content">
                                                        <div class="active tab-pane" id="modules">
                                                            <table id="example1" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Module Code</th>
                                                                        <th>Module Name</th>
                                                                        <th>Date Enrolled</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $admission_number = $std->admno;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE student_adm = '$admission_number'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($mod = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $cnt; ?></td>
                                                                            <td><?php echo $mod->module_code; ?></td>
                                                                            <td><?php echo $mod->module_name; ?></td>
                                                                            <td><?php echo $mod->created_at; ?></td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="tab-pane" id="courses">
                                                            <table id="courses_enrolled" class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Course Code</th>
                                                                        <th>Couse Name</th>
                                                                        <th>Academic Yr</th>
                                                                        <th>Semester Enrolled</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $admission_number = $std->admno;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Enrollments` WHERE student_adm = '$admission_number'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    $cnt = 1;
                                                                    while ($mod = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $cnt; ?></td>
                                                                            <td><?php echo $mod->course_code; ?></td>
                                                                            <td><?php echo $mod->course_name; ?></td>
                                                                            <td><?php echo $mod->academic_year_enrolled; ?></td>
                                                                            <td><?php echo $mod->semester_enrolled; ?></td>
                                                                        </tr>
                                                                    <?php $cnt = $cnt + 1;
                                                                    } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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