<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
require_once('configs/codeGen.php');
check_login();
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/_faculty_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_ModulesAnnouncements` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($not = $res->fetch_object()) {

            $faculty = $_GET['faculty'];
            $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id ='$faculty' ";
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
                                    <h1 class="m-0 text-dark"><?php echo $not->module_name; ?> Announcement</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $f->id; ?>"><?php echo $f->name; ?></a></li>
                                        <li class="breadcrumb-item"><a href="modules.php?faculty=<?php echo $f->id; ?>">Modules</a></li>
                                        <li class="breadcrumb-item"><a href="module_notices.php?faculty=<?php echo $f->id; ?>">Announcements</a></li>
                                        <li class="breadcrumb-item active">View</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="content">
                        <div class="container-fluid">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php echo $not->module_name; ?>  Announcement Posted By <?php echo $not->created_by; ?> On <?php echo $not->created_at; ?>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true"><?php echo $not->module_name; ?> Announcement</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="custom-content-below-tabContent">
                                        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                            <br>
                                            <?php echo $not->announcements; ?>
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