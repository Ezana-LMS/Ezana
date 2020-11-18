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
        require_once('partials/_nav.php');
        require_once('partials/_sidebar.php');
        ?>
        <!-- /.navbar -->

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Enrollments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active"> Enrollments </li>
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
                                        <table id="export-dt" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Admission</th>
                                                    <th>Name</th>
                                                    <th>Course</th>
                                                    <th>Module</th>
                                                    <th>Academic Yr</th>
                                                    <th>Sem Enrolled</th>
                                                    <th>Sem Start</th>
                                                    <th>Sem End </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = "SELECT * FROM `ezanaLMS_Enrollments`  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                $cnt = 1;
                                                while ($en = $res->fetch_object()) {
                                                ?>

                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $en->student_adm; ?></td>
                                                        <td><?php echo $en->student_name; ?></td>
                                                        <td><?php echo $en->course_name; ?></td>
                                                        <td><?php echo $en->module_name; ?></td>
                                                        <td><?php echo $en->academic_year_enrolled; ?></td>
                                                        <td><?php echo $en->semester_enrolled; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($en->semester_start)); ?></td>
                                                        <td><?php echo date('d M Y', strtotime($en->semester_end)); ?></td>
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