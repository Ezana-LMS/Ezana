<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();

//delete
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_PasswordResets WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=student_password_resets.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
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
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Students Accounts Password Resets</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="student_password_resets.php">Students</a></li>
                                <li class="breadcrumb-item active">Password Resets</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Email</th>
                                            <th>New Password</th>
                                            <th>Token</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_PasswordResets` WHERE acc_type = 'Student'  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row->email; ?></td>
                                                <td><?php echo $row->new_pass; ?></td>
                                                <td><?php echo $row->token; ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->status == 'Pending') {
                                                        echo "
                                                                <a href='reset_student_password.php?email=$row->email&id=$row->id' class='badge badge-success'>
                                                                    <i class='fas fa-cogs'></i>
                                                                    <i class='fas fa-user-lock'></i>
                                                                    Reset Password
                                                                </a>";
                                                    } else {
                                                        echo "
                                                                <a class='badge badge-primary' href='mailto:$row->email&password=$row->new_pass'>
                                                                    <i class='fas fa-envelope'></i>
                                                                        <i class='fas fa-user-lock'></i>
                                                                            Email Password
                                                                </a>
                                                            ";
                                                    }

                                                    ?>
                                                    <a class='badge badge-danger' href='student_password_resets.php?delete=<?php echo $row->id; ?>'>
                                                        <i class='fas fa-trash'></i>
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>

                                        <?php
                                            $cnt = $cnt + 1;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>