<?php
$faculty = $_GET['faculty'];

/* Faculty  Departments  */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Departments` WHERE faculty_id ='$faculty' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($departments);
$stmt->fetch();
$stmt->close();


/* Courses */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Courses` WHERE faculty_id = '$faculty' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($courses);
$stmt->fetch();
$stmt->close();

/* Modules */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE faculty_id ='$faculty' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($modules);
$stmt->fetch();
$stmt->close();


/* Lecturers */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$faculty' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($lecs);
$stmt->fetch();
$stmt->close();

/* Students */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Students` WHERE faculty_id = '$faculty' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($students);
$stmt->fetch();
$stmt->close();
