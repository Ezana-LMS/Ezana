<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
//Delete Calendar Details
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_Calendar WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=school_calendar.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/_head.php');

?>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"> <?php echo $_SESSION['name']; ?> Dashboard </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">School Calendar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">

                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>