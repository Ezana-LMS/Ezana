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

        $faculty = $_GET['faculty'];
        $ret = "SELECT * FROM `ezanaLMS_Faculties` WHERE id = '$faculty' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            $watch = $_GET['watch'];
            $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE id ='$watch'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($cr = $res->fetch_object()) {
                require_once('partials/_faculty_nav.php');
                require_once('partials/_faculty_sidebar.php');
        ?>
                <!-- /.navbar -->
                <div class="content-wrapper">
                    <div class="content">
                        <div class="container">
                            <section class="content-header">
                                <div class="container-fluid">
                                    <div class="row mb-2">
                                        <div class="col-sm-6">
                                            <h1>
                                                <?php echo $cr->class_name; ?> Recording
                                        </div>
                                        <div class="col-sm-6">
                                            <ol class="breadcrumb float-sm-right">
                                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                                <li class="breadcrumb-item"><a href="faculty_dashboard.php?faculty=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></li>
                                                <li class="breadcrumb-item"><a href="class_recordings.php?faculty=<?php echo $row->id; ?>">Class Recordings</a></li>
                                                <li class="breadcrumb-item active"> View </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="content">
                                <div class="container-fluid">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Class Recording Uploaded By <span class="text-success"> <?php echo  $cr->lecturer_name; ?></span> On <span class="text-warning"><?php echo $cr->created_at; ?></span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Clip Description</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-hyperlink" role="tab" aria-controls="custom-content-below-home" aria-selected="true">External Link</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-watch" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Watch Recording</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php echo $cr->details; ?>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-hyperlink" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php
                                                    if ($cr->external_link != '') {
                                                        echo
                                                            "
                                                <a target='_blank' href='$cr->external_link' class='btn btn-outline-success'>
                                                    <i class='fas fa-play'></i>
                                                        Open Link
                                                </a>
                                            ";
                                                    } else {
                                                        echo
                                                            "
                                                <a  class='btn btn-outline-danger'>
                                                    <i class='fas fa-times'></i>
                                                        Link Not Available
                                                </a>
                                                ";
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tab-pane fade show" id="custom-content-below-watch" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <?php
                                                    if ($cr->video != '') {
                                                        echo
                                                            "
                                            <div class='embed-responsive embed-responsive-16by9'>
                                                <iframe class='embed-responsive-item' src='dist/ClassVideos/$cr->video' allowfullscreen></iframe>
                                            </div>
                                            ";
                                                    } else {
                                                        echo
                                                            "
                                            <a  class='btn btn-outline-danger'>
                                                <i class='fas fa-times'></i>
                                                    Class Recording Video Attachment Not Available
                                            </a>
                                            ";
                                                    }
                                                    ?>
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