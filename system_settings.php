<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
/* System Settings */
if (isset($_POST['systemSettings'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['sysname']) && !empty($_POST['sysname'])) {
        $sysname = mysqli_real_escape_string($mysqli, trim($_POST['sysname']));
    } else {
        $error = 1;
        $err = "System Name Cannot Be Empty";
    }
    if (!$error) {
        $id = $_POST['id'];
        $sysname = $_POST['sysname'];
        $logo = $_FILES['logo']['name'];
        move_uploaded_file($_FILES["logo"]["tmp_name"], "public/dist/img/" . $_FILES["logo"]["name"]);

        $query = "UPDATE ezanaLMS_Settings SET sysname =?, logo =? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sss',  $sysname,  $logo, $id);
        $stmt->execute();
        if ($stmt) {
            $success = "Settings Updated" && header("refresh:1; url=system_settings.php");
        } else {
            //inject alert that profile update task failed
            $info = "Please Try Again Or Try Later";
        }
    }
}

/* Back Up Database */
if (isset($_POST['DumpDatabase'])) {
    define("DB_HOST", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "");
    define("DB_NAME", "ezana_lms");
    $con = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("Error : ");
    if (mysqli_connect_errno($con)) {
        echo "Failed to connect MySQL: " . mysqli_connect_error();
    } else {
        //If you want to export or backup the whole database then leave the $table variable as it is
        //If you want to export or backup few table then mention the names of the tables within the $table array like below
        //eg, $tables = array("wp_commentmeta", "wp_comments", "wp_options");
        $tables = array();
        $backup_file_name = DB_NAME . ".sql";
        backup_database($con, $tables, $backup_file_name);
    }

    function backup_database($con, $tables = "", $backup_file_name)
    {
        if (empty($tables)) {
            $tables_in_database = mysqli_query($con, "SHOW TABLES");
            if (mysqli_num_rows($tables_in_database) > 0) {
                while ($row = mysqli_fetch_row($tables_in_database)) {
                    array_push($tables, $row[0]);
                }
            }
        } else {
            // Checking for any table that doesn't exists in the database
            $existed_tables = array();
            foreach ($tables as $table) {
                if (mysqli_num_rows(mysqli_query($con, "SHOW TABLES LIKE '" . $table . "'")) == 1) {
                    array_push($existed_tables, $table);
                }
            }
            $tables = $existed_tables;
        }
        $contents = "--\n-- Database: `" . DB_NAME . "`\n--\n-- --------------------------------------------------------\n\n\n\n";
        foreach ($tables as $table) {
            $result        = mysqli_query($con, "SELECT * FROM " . $table);
            $no_of_columns = mysqli_num_fields($result);
            $no_of_rows    = mysqli_num_rows($result);
            //Get the query for table creation
            $table_query     = mysqli_query($con, "SHOW CREATE TABLE " . $table);
            $table_query_res = mysqli_fetch_row($table_query);
            $contents .= "--\n-- Table structure for table `" . $table . "`\n--\n\n";
            $contents .= $table_query_res[1] . ";\n\n\n\n";
            /**
             *  $insert_limit -> Limits the number of row insertion in a single INSERT query.
             *           Maximum 100 rowswe will insert in a single INSERT query.
             *  $insert_count -> Counts the number of rows are added to the INSERT query.
             *                   When it will reach the insert limit it will set to 0 again.
             *  $total_count  -> Counts the overall number of rows are added to the INSERT query of a single table.
             */
            $insert_limit = 100;
            $insert_count = 0;
            $total_count  = 0;
            while ($result_row = mysqli_fetch_row($result)) {
                /**
                 * For the first time when $insert_count is 0 and when $insert_count reached the $insert_limit
                 * and again set to 0 this if condition will execute and append the INSERT query in the sql file.
                 */
                if ($insert_count == 0) {
                    $contents .= "--\n-- Dumping data for table `" . $table . "`\n--\n\n";
                    $contents .= "INSERT INTO " . $table . " VALUES ";
                }
                //Values part of an INSERT query will start from here eg. ("1","mitrajit","India"),
                $insert_query = "";
                $contents .= "\n(";
                for ($j = 0; $j < $no_of_columns; $j++) {
                    //Replace any "\n" with "\\n" escape character.
                    //addslashes() function adds escape character to any double quote or single quote eg, \" or \'
                    $insert_query .= "'" . str_replace("\n", "\\n", addslashes($result_row[$j])) . "',";
                }
                //Remove the last unwanted comma (,) from the query.
                $insert_query = substr($insert_query, 0, -1) . "),";
                /*
                 *  If $insert_count reached to the insert limit of a single INSERT query
                 *  or $insert count reached to the number of total rows of a table
                 *  or overall total count reached to the number of total rows of a table
                 *  this if condition will exceute.
                 */
                if ($insert_count == ($insert_limit - 1) || $insert_count == ($no_of_rows - 1) || $total_count == ($no_of_rows - 1)) {
                    //Remove the last unwanted comma (,) from the query and append a semicolon (;) to it
                    $contents .= substr($insert_query, 0, -1);
                    $contents .= ";\n\n\n\n";
                    $insert_count = 0;
                } else {
                    $contents .= $insert_query;
                    $insert_count++;
                }
                $total_count++;
            }
        }
        //Set the HTTP header of the page.
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $backup_file_name . "\"");
        echo $contents;
        exit;
    }
}


/* Restore Database */
if (isset($_POST['RestoreDatabase'])) {
}
require_once('configs/codeGen.php');
require_once('public/partials/_analytics.php');
require_once('public/partials/_head.php');
$ret = "SELECT * FROM `ezanaLMS_Settings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
$cnt = 1;
while ($sys = $res->fetch_object()) {
?>

    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <!-- Navbar -->
            <?php require_once('public/partials/_nav.php'); ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="dashboard.php" class="brand-link">
                    <img src="public/dist/img/logo.png" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3">
                    <span class="brand-text font-weight-light"><?php echo $sys->sysname; ?></span>
                </a>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="dashboard.php" class=" nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="faculties.php" class=" nav-link">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Faculties
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="departments.php" class=" nav-link">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Departments
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="courses.php" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="modules.php" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>
                                        Modules
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="lecturers.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>
                                        Lecturers
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="students.php" class="nav-link">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>
                                        Students
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="active nav-link">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>
                                        System Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="reports.php" class=" nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>Reports</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="system_settings.php" class="active nav-link">
                                            <i class="fas fa-angle-right nav-icon"></i>
                                            <p>System Settings</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">System Settings</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="reports.php">System</a></li>
                                    <li class="breadcrumb-item active">System Settings</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">System Settings</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link " id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home-data-backup" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Backup</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link " id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home-restore" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Restore</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="">System Name</label>
                                                                    <input type="text" required name="sysname" value="<?php echo $sys->sysname; ?>" class="form-control">
                                                                    <input type="hidden" required name="id" value="<?php echo $sys->id ?>" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="">System Logo</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="logo" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="systemSettings" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-home-data-backup" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="text-center">
                                                            <button type="submit" name="DumpDatabase" class="btn btn-primary">Backup System Data</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade show " id="custom-content-below-home-restore" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                                    <br>
                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="">Import .SQL Back Up File</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input required name="restore_sql" type="file" class="custom-file-input">
                                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="submit" name="RestoreDatabase" class="btn btn-primary">Restore System Data</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Main Footer -->
                    <?php require_once('public/partials/_footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
            <?php require_once('public/partials/_scripts.php'); ?>
    </body>

    </html>
<?php
} ?>