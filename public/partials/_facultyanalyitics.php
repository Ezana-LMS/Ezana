<?php

/* Departments  */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Departments` WHERE faculty_id = '$view' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($faculty_departments);
$stmt->fetch();
$stmt->close();

/* Courses */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Courses` WHERE faculty_id = '$view' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($faculty_courses);
$stmt->fetch();
$stmt->close();


/* Modules */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE faculty_id = '$view' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($faculty_modules);
$stmt->fetch();
$stmt->close();

/* Lecs  */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$view' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($faculty_lecs);
$stmt->fetch();
$stmt->close();

/*  Students */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Students` WHERE faculty_id = '$view' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($faculty_students);
$stmt->fetch();
$stmt->close();

