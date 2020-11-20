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
/* Department Faculty ID */
if (!empty($_POST["DepartmentID"])) {
    $id = $_POST['DepartmentID'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['faculty_id']); ?>
<?php
    }
}


/* Course Details */
if (!empty($_POST["Cname"])) {
    $id = $_POST['Cname'];
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

if (!empty($_POST["CourseCode"])) {
    $id = $_POST['CourseCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Courses WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
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

/* Semester Details */
if (!empty($_POST["SemesterEnrolled"])) {
    $id = $_POST['SemesterEnrolled'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Calendar WHERE semester_name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['semester_start']); ?>
<?php
    }
}

if (!empty($_POST["SemesterStart"])) {
    $id = $_POST['SemesterStart'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Calendar WHERE semester_name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['semester_end']); ?>
<?php
    }
}

if (!empty($_POST["SemesterEnd"])) {
    $id = $_POST['SemesterEnd'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Calendar WHERE semester_name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['academic_yr']); ?>
<?php
    }
}

/* Group Details */
if (!empty($_POST["GroupName"])) {
    $id = $_POST['GroupName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Groups WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['code']); ?>
<?php
    }
}

/* Faculty Details */
if (!empty($_POST["FacultyName"])) {
    $id = $_POST['FacultyName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Faculties WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}