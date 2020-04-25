<?php
require_once "../configs/config.php";

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

$sqlUpdate = "UPDATE ezanaLMS_Events SET title='" . $title . "',start='" . $start . "',end='" . $end . "' WHERE id=" . $id;
mysqli_query($mysqli, $sqlUpdate);
mysqli_close($mysqli);
