<?php
include('configs/pdoconfig.php');

/* Get Allocated Module Name */
if (!empty($_POST["AllocatedModuleCode"])) {
    $id = $_POST['AllocatedModuleCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_ModuleAssigns WHERE module_code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['module_name']); ?>
<?php
    }
}

/* Get Allocated Module Lec Name */
if (!empty($_POST["AllocatedModuleName"])) {
    $id = $_POST['AllocatedModuleName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_ModuleAssigns WHERE module_code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['lec_name']); ?>
<?php
    }
}


/* Department ID */
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

/* Course Faculty ID */
if (!empty($_POST["CourseName"])) {
    $id = $_POST['CourseName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Courses WHERE name = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['faculty_id']); ?>
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
/* Module Details  2*/
if (!empty($_POST["ModuleCode"])) {
    $id = $_POST['ModuleCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Modules WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}

/* Guest Lecturer Module */
if (!empty($_POST["moduleCode"])) {
    $id = $_POST['moduleCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Modules WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}

/* Lec Details Part 1 */
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

/* Lec Details Part 2 */
if (!empty($_POST["LecNumber"])) {
    $id = $_POST['LecNumber'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Lecturers WHERE number = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}

if (!empty($_POST["lecID"])) {
    $id = $_POST['lecID'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Lecturers WHERE number = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}
/* Guest Lec Details */
if (!empty($_POST["lecNumber"])) {
    $id = $_POST['lecNumber'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Lecturers WHERE number = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}

if (!empty($_POST["LecID"])) {
    $id = $_POST['LecID'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Lecturers WHERE number = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
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

/* Optimized Department Function */
if (!empty($_POST["DepartmentCode"])) {
    $id = $_POST['DepartmentCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}

/* Department Name */
if (!empty($_POST["DepartmentID"])) {
    $id = $_POST['DepartmentID'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}

/* Department Faculty ID */
if (!empty($_POST["DepartmentName"])) {
    $id = $_POST['DepartmentName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['faculty_id']); ?>
<?php
    }
}



/* Optimized Department Function */
if (!empty($_POST["DepCode"])) {
    $id = $_POST['DepCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}

/* Department Name */
if (!empty($_POST["DepID"])) {
    $id = $_POST['DepID'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}

/* Department Faculty ID */
if (!empty($_POST["DepName"])) {
    $id = $_POST['DepName'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Departments WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['faculty_id']); ?>
<?php
    }
}


/* Optimized Faculty Function */
if (!empty($_POST["FacultyCode"])) {
    $id = $_POST['FacultyCode'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Faculties WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['id']); ?>
<?php
    }
}

/* Faculty Name */
if (!empty($_POST["FacultyID"])) {
    $id = $_POST['FacultyID'];
    $stmt = $DB_con->prepare("SELECT * FROM ezanaLMS_Faculties WHERE code = :id");
    $stmt->execute(array(':id' => $id));
?>
<?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php echo htmlentities($row['name']); ?>
<?php
    }
}