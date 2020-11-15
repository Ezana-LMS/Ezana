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
        <?php require_once('partials/_nav.php'); ?>
        <!-- /.navbar -->

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Total Departments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active"> Departments </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <section class="content">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <!-- <div class="card-header">
                                            <h2 class="text-right">
                                                <a class="btn btn-outline-success" href="add_module.php?faculty=<?php echo $f->id; ?>">
                                                    Register New Module
                                                </a>

                                                <a class="btn btn-outline-success" href="module_notices.php?faculty=<?php echo $f->id; ?>">
                                                    Module Notices
                                                </a>

                                                <a class="btn btn-outline-success" href="module_reading_materials.php?faculty=<?php echo $f->id; ?>">
                                                    Module Reading Materials
                                                </a>
                                            </h2>
                                        </div> -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Department Code / Number</th>
                                                    <th>Department Name</th>
                                                    <th>Depeartment Head</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Departments`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($dep = $res->fetch_object()) {
                                                ?>
                                                    <tr class="table-row" data-href="view_department.php?department=<?php echo $dep->id; ?>">
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $dep->code; ?></td>
                                                        <td><?php echo $dep->name; ?></td>
                                                        <td><?php echo $dep->hod; ?></td>

                                                    </tr>
                                                <?php $cnt = $cnt + 1;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php');
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>