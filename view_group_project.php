<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        require_once('partials/_sidebar.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_GroupsAssignments` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($gcode = $res->fetch_object()) {
        ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    <?php echo $gcode->group_name; ?> Project
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_groups.php">Groups</a></li>
                                    <li class="breadcrumb-item"><a href="group_projects.php">Groups Projects</a></li>
                                    <li class="breadcrumb-item active">View Project</li>
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
                                    Project / Assignment Created On <span class="text-success"> <?php echo $gcode->created_at; ?></span> And Updated On <span class="text-warning"><?php echo $gcode->updated_at; ?></span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Project Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-enrollment-tab" data-toggle="pill" href="#custom-content-below-members" role="tab" aria-controls="custom-content-below-members" aria-selected="false">Project Attachements</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <br>
                                        <?php echo $gcode->details; ?>
                                        <br>
                                        <p class="text-danger">Submission Deadline : <?php echo date('d M Y', strtotime($gcode->submitted_on));  ?></p>
                                    </div>
                                    <div class="tab-pane fade" id="custom-content-below-members" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                        <br>
                                        <?php
                                        if ($gcode->attachments != '') {
                                            echo
                                                "
                                                <a target = '_blank' href='dist/Group_Projects/$gcode->attachments' class='btn btn-outline-success'>
                                                    <i class='fas fa-download'></i>
                                                        Download Attachment
                                                </a>
                                                ";
                                        } else {
                                            echo
                                                "
                                                <a  class='btn btn-outline-danger'>
                                                    <i class='fas fa-times'></i>
                                                        Attachment Not Available
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
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>