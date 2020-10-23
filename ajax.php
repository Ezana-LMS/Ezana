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

/* Module Details */
if (!empty($_POST["ModuleName"])) {
    $id = $_POST['ModuleName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Modules WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['code']); ?>
<?php
    }
}

/* Lec Details */
if (!empty($_POST["LecName"])) {
    $id = $_POST['LecName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Lecturers WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}


/* Student Details */
if (!empty($_POST["StudentAdmn"])) {
    $id = $_POST['StudentAdmn'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Students WHERE admno = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}

/* Get course name */
if (!empty($_POST["Coursecode"])) {
    $id = $_POST['Coursecode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Courses WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}