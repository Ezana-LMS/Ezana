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
    define("DB_USER", 'root');
    define("DB_PASSWORD", '');
    define("DB_NAME", 'ezana_lms');
    define("DB_HOST", 'localhost');
    define("BACKUP_DIR", 'public/backup/'); // Comment this line to use same script's directory ('.')
    define("TABLES", '*'); // Full backup
    define('IGNORE_TABLES', array(
        'tbl_token_auth',
        'token_auth'
    )); 
    define("CHARSET", 'utf8');
    define("GZIP_BACKUP_FILE", true); // Set to false if you want plain SQL backup files (not gzipped)
    define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
    define("BATCH_SIZE", 1000); // Batch size when selecting rows from database in order to not exhaust system memory

    /**
     * The Backup_Database class
     */
    class Backup_Database
    {
        /**
         * Host where the database is located
         */
        var $host;

        /**
         * Username used to connect to database
         */
        var $username;

        /**
         * Password used to connect to database
         */
        var $passwd;

        /**
         * Database to backup
         */
        var $dbName;

        /**
         * Database charset
         */
        var $charset;

        /**
         * Database connection
         */
        var $conn;

        /**
         * Backup directory where backup files are stored 
         */
        var $backupDir;

        /**
         * Output backup file
         */
        var $backupFile;

        /**
         * Use gzip compression on backup file
         */
        var $gzipBackupFile;

        /**
         * Content of standard output
         */
        var $output;

        /**
         * Disable foreign key checks
         */
        var $disableForeignKeyChecks;

        /**
         * Batch size, number of rows to process per iteration
         */
        var $batchSize;

        /**
         * Constructor initializes database
         */
        public function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
        {
            $this->host                    = $host;
            $this->username                = $username;
            $this->passwd                  = $passwd;
            $this->dbName                  = $dbName;
            $this->charset                 = $charset;
            $this->conn                    = $this->initializeDatabase();
            $this->backupDir               = BACKUP_DIR ? BACKUP_DIR : '.';
            $this->backupFile              = 'myphp-backup-' . $this->dbName . '-' . date("Ymd_His", time()) . '.sql';
            $this->gzipBackupFile          = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
            $this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
            $this->batchSize               = defined('BATCH_SIZE') ? BATCH_SIZE : 1000; // default 1000 rows
            $this->output                  = '';
        }

        protected function initializeDatabase()
        {
            try {
                $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
                if (mysqli_connect_errno()) {
                    throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
                    die();
                }
                if (!mysqli_set_charset($conn, $this->charset)) {
                    mysqli_query($conn, 'SET NAMES ' . $this->charset);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
                die();
            }

            return $conn;
        }

        /**
         * Backup the whole database or just some tables
         * Use '*' for whole database or 'table1 table2 table3...'
         * @param string $tables
         */
        public function backupTables($tables = '*')
        {
            try {
                /**
                 * Tables to export
                 */
                if ($tables == '*') {
                    $tables = array();
                    $result = mysqli_query($this->conn, 'SHOW TABLES');
                    while ($row = mysqli_fetch_row($result)) {
                        $tables[] = $row[0];
                    }
                } else {
                    $tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
                }

                $sql = 'CREATE DATABASE IF NOT EXISTS `' . $this->dbName . '`' . ";\n\n";
                $sql .= 'USE `' . $this->dbName . "`;\n\n";

                /**
                 * Disable foreign key checks 
                 */
                if ($this->disableForeignKeyChecks === true) {
                    $sql .= "SET foreign_key_checks = 0;\n\n";
                }

                /**
                 * Iterate tables
                 */
                foreach ($tables as $table) {
                    if (in_array($table, IGNORE_TABLES))
                        continue;
                    $this->obfPrint("Backing up `" . $table . "` table..." . str_repeat('.', 50 - strlen($table)), 0, 0);

                    /**
                     * CREATE TABLE
                     */
                    $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';
                    $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `' . $table . '`'));
                    $sql .= "\n\n" . $row[1] . ";\n\n";

                    /**
                     * INSERT INTO
                     */

                    $row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `' . $table . '`'));
                    $numRows = $row[0];

                    // Split table in batches in order to not exhaust system memory 
                    $numBatches = intval($numRows / $this->batchSize) + 1; // Number of while-loop calls to perform

                    for ($b = 1; $b <= $numBatches; $b++) {

                        $query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $this->batchSize - $this->batchSize) . ',' . $this->batchSize;
                        $result = mysqli_query($this->conn, $query);
                        $realBatchSize = mysqli_num_rows($result); // Last batch size can be different from $this->batchSize
                        $numFields = mysqli_num_fields($result);

                        if ($realBatchSize !== 0) {
                            $sql .= 'INSERT INTO `' . $table . '` VALUES ';

                            for ($i = 0; $i < $numFields; $i++) {
                                $rowCount = 1;
                                while ($row = mysqli_fetch_row($result)) {
                                    $sql .= '(';
                                    for ($j = 0; $j < $numFields; $j++) {
                                        if (isset($row[$j])) {
                                            $row[$j] = addslashes($row[$j]);
                                            $row[$j] = str_replace("\n", "\\n", $row[$j]);
                                            $row[$j] = str_replace("\r", "\\r", $row[$j]);
                                            $row[$j] = str_replace("\f", "\\f", $row[$j]);
                                            $row[$j] = str_replace("\t", "\\t", $row[$j]);
                                            $row[$j] = str_replace("\v", "\\v", $row[$j]);
                                            $row[$j] = str_replace("\a", "\\a", $row[$j]);
                                            $row[$j] = str_replace("\b", "\\b", $row[$j]);
                                            if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[0-9]+$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
                                                $sql .= $row[$j];
                                            } else {
                                                $sql .= '"' . $row[$j] . '"';
                                            }
                                        } else {
                                            $sql .= 'NULL';
                                        }

                                        if ($j < ($numFields - 1)) {
                                            $sql .= ',';
                                        }
                                    }

                                    if ($rowCount == $realBatchSize) {
                                        $rowCount = 0;
                                        $sql .= ");\n"; //close the insert statement
                                    } else {
                                        $sql .= "),\n"; //close the row
                                    }

                                    $rowCount++;
                                }
                            }

                            $this->saveFile($sql);
                            $sql = '';
                        }
                    }

                  

                    $sql .= "\n\n";

                    $this->obfPrint('OK');
                }

                /**
                 * Re-enable foreign key checks 
                 */
                if ($this->disableForeignKeyChecks === true) {
                    $sql .= "SET foreign_key_checks = 1;\n";
                }

                $this->saveFile($sql);

                if ($this->gzipBackupFile) {
                    $this->gzipBackupFile();
                } else {
                    $this->obfPrint('Backup file succesfully saved to ' . $this->backupDir . '/' . $this->backupFile, 1, 1);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
                return false;
            }

            return true;
        }

        /**
         * Save SQL to file
         * @param string $sql
         */
        protected function saveFile(&$sql)
        {
            if (!$sql) return false;

            try {

                if (!file_exists($this->backupDir)) {
                    mkdir($this->backupDir, 0777, true);
                }

                file_put_contents($this->backupDir . '/' . $this->backupFile, $sql, FILE_APPEND | LOCK_EX);
            } catch (Exception $e) {
                print_r($e->getMessage());
                return false;
            }

            return true;
        }

        /*
         * Gzip backup file
         *
         * @param integer $level GZIP compression level (default: 9)
         * @return string New filename (with .gz appended) if success, or false if operation fails
         */
        protected function gzipBackupFile($level = 9)
        {
            if (!$this->gzipBackupFile) {
                return true;
            }

            $source = $this->backupDir . '/' . $this->backupFile;
            $dest =  $source . '.gz';

            $this->obfPrint('Gzipping backup file to ' . $dest . '... ', 1, 0);

            $mode = 'wb' . $level;
            if ($fpOut = gzopen($dest, $mode)) {
                if ($fpIn = fopen($source, 'rb')) {
                    while (!feof($fpIn)) {
                        gzwrite($fpOut, fread($fpIn, 1024 * 256));
                    }
                    fclose($fpIn);
                } else {
                    return false;
                }
                gzclose($fpOut);
                if (!unlink($source)) {
                    return false;
                }
            } else {
                return false;
            }

            $this->obfPrint('OK');
            return $dest;
        }

        /**
         * Prints message forcing output buffer flush
         *
         */
        public function obfPrint($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1)
        {
            if (!$msg) {
                return false;
            }

            if ($msg != 'OK' and $msg != 'KO') {
                $msg = date("Y-m-d H:i:s") . ' - ' . $msg;
            }
            $output = '';

            if (php_sapi_name() != "cli") {
                $lineBreak = "<br />";
            } else {
                $lineBreak = "\n";
            }

            if ($lineBreaksBefore > 0) {
                for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                    $output .= $lineBreak;
                }
            }

            $output .= $msg;

            if ($lineBreaksAfter > 0) {
                for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                    $output .= $lineBreak;
                }
            }


            // Save output for later use
            $this->output .= str_replace('<br />', '\n', $output);

            echo $output;


            if (php_sapi_name() != "cli") {
                if (ob_get_level() > 0) {
                    ob_flush();
                }
            }

            $this->output .= " ";

            flush();
        }

        /**
         * Returns full execution output
         *
         */
        public function getOutput()
        {
            return $this->output;
        }
        /**
         * Returns name of backup file
         *
         */
        public function getBackupFile()
        {
            if ($this->gzipBackupFile) {
                return $this->backupDir . '/' . $this->backupFile . '.gz';
            } else
                return $this->backupDir . '/' . $this->backupFile;
        }

        /**
         * Returns backup directory path
         *
         */
        public function getBackupDir()
        {
            return $this->backupDir;
        }

        /**
         * Returns array of changed tables since duration
         *
         */
        public function getChangedTables($since = '1 day')
        {
            $query = "SELECT TABLE_NAME,update_time FROM information_schema.tables WHERE table_schema='" . $this->dbName . "'";

            $result = mysqli_query($this->conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $resultset[] = $row;
            }
            if (empty($resultset))
                return false;
            $tables = [];
            for ($i = 0; $i < count($resultset); $i++) {
                if (in_array($resultset[$i]['TABLE_NAME'], IGNORE_TABLES)) // ignore this table
                    continue;
                if (strtotime('-' . $since) < strtotime($resultset[$i]['update_time']))
                    $tables[] = $resultset[$i]['TABLE_NAME'];
            }
            return ($tables) ? $tables : false;
        }
    }


    /**
     * Instantiate Backup_Database and perform backup
     */

    // Report all errors
    error_reporting(E_ALL);
    // Set script max execution time
    set_time_limit(900); // 15 minutes

    if (php_sapi_name() != "cli") {
        echo '<div style="font-family: monospace;">';
    }

    $backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, CHARSET);

    // Option-1: Backup tables already defined above
    $result = $backupDatabase->backupTables(TABLES) ? 'OK' : 'KO';


    $backupDatabase->obfPrint('Backup result: ' . $result, 1);

    // Use $output variable for further processing, for example to send it by email
    $output = $backupDatabase->getOutput();

    if (php_sapi_name() != "cli") {
        echo '</div>';
    }
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
                                                            <button type="submit" name="restore_Data" class="btn btn-primary">Restore System Data</button>
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