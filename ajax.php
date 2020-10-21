<?php
include('configs/pdoconfig.php');

//Department ID
if (!empty($_POST["DepartmentName"])) {
    $id = $_POST['DepartmentName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}

/* Course Details */
if (!empty($_POST["CourseName"])) {
    $id = $_POST['CourseName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Courses WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['code']); ?>
<?php
    }
}
