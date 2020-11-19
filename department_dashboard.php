<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//require_once('partials/_faculties_analytics.php');
require_once('partials/_head.php');
?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">
        <?php
        require_once('partials/_dep_nav.php');
        $department = $_GET['department'];
        $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id = '$department' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
            require_once('partials/_faculty_sidebar.php');
        ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"> <?php echo $row->name; ?> Dashboard</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="faculties.php">Faculties</a></li>
                                    <li class="breadcrumb-item"><a href=""><?php echo $row->name; ?></a></li>
                                    <li class="breadcrumb-item active">View</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        <?php require_once('partials/_footer.php');
        } ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>