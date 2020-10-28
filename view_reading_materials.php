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
        $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($rm = $res->fetch_object()) {
        ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>
                                    Reading Materials
                                </h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage_modules.php">Modules</a></li>
                                    <li class="breadcrumb-item"><a href="reading_materials.php">Reading Materials</a></li>
                                    <li class="breadcrumb-item active">View</li>
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
                                    <?php echo $rm->module_name; ?> Reading Materials Posted On <?php echo $rm->created_at; ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Reading Materials</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-links" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Reading Materials Extenal Link</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <br>
                                        <?php
                                        if ($rm->readingMaterials != '') {
                                            echo
                                                "
                                                <a target='_blank' href='dist/Reading_Materials/$rm->readingMaterials' class='btn btn-outline-primary'>
                                                    <i class='fas fa-download'></i>
                                                        Download Reading Materials
                                                </a>
                                                ";
                                        } else {
                                            echo
                                                "
                                                <a  class='btn btn-outline-danger'>
                                                    <i class='fas fa-times'></i>
                                                        Reading Materials Not Available
                                                </a>
                                                ";
                                        }
                                        ?>
                                    </div>
                                    <div class="tab-pane fade show " id="custom-content-below-links" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <br>
                                        <?php
                                        if ($rm->external_link != '') {
                                            echo
                                                "
                                            <a target='_blank' href='$rm->external_link' class='btn btn-outline-success'>
                                                <i class='fas fa-external-link-alt'></i>
                                                Open Link
                                            </a>
                                            ";
                                        } else {
                                            echo
                                                "
                                                <a  class='btn btn-outline-danger'>
                                                    <i class='fas fa-times'></i>
                                                        External Link Not Available
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