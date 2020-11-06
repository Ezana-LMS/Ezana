<?php
/* 1.  Departments  */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Departments` WHERE  faculty_name = '($faculty->name)' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($departments);
$stmt->fetch();
$stmt->close();

/* 2.  Courses */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Courses` WHERE ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($courses);
$stmt->fetch();
$stmt->close();


/* 3.  Modules */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE  ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($modules);
$stmt->fetch();
$stmt->close();
