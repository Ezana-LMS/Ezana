<?php
/*
 * Created on Tue Jun 29 2021
 *
 * The MIT License (MIT)
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */



$id  = $_SESSION['id'];
$ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($admin = $res->fetch_object()) {

    $faculty_id = $admin->school_id;

    /* Faculty */
    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Faculties`  WHERE id = '$faculty_id' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($faculties);
    $stmt->fetch();
    $stmt->close();

    /* Modules */
    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Modules` WHERE faculty_id = '$faculty_id' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($modules);
    $stmt->fetch();
    $stmt->close();

    /* Lecs  */
    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Lecturers` WHERE faculty_id = '$faculty_id' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($lecs);
    $stmt->fetch();
    $stmt->close();

    /*  Students */
    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Students` WHERE faculty_id = '$faculty_id' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($students);
    $stmt->fetch();
    $stmt->close();

    /* Departments  */
    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Departments` WHERE faculty_id = '$faculty_id' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($departments);
    $stmt->fetch();
    $stmt->close();

    /* Courses */
    $query = "SELECT COUNT(*)  FROM `ezanaLMS_Courses` WHERE faculty_id = '$faculty_id' ";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($courses);
    $stmt->fetch();
    $stmt->close();
}
