<?php
$faculty = $_GET['faculty'];

/* Faculty  Departments  */
$query = "SELECT COUNT(*)  FROM `ezanaLMS_Departments` WHERE faculty_id ='$faculty' ";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($departments);
$stmt->fetch();
$stmt->close();
