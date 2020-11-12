<?php
session_start();
require_once('configs/checklogin.php');
require_once('configs/config.php');
require_once('configs/codeGen.php');
check_login();

use EzanaLmsAPI\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once('configs/DataSource.php');
$db = new DataSource();
$conn = $db->getConnection();
require_once('vendor/autoload.php');


if (isset($_POST["upload"])) {

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'EzanaLMSData/XLSFiles/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        for ($i = 0; $i <= $sheetCount; $i++) {

            $id = "";
            if (isset($spreadSheetAry[$i][0])) {
                $id = mysqli_real_escape_string($conn, $spreadSheetAry[$i][0]);
            }

            $admno = "";
            if (isset($spreadSheetAry[$i][1])) {
                $admno = mysqli_real_escape_string($conn, $spreadSheetAry[$i][1]);
            }
            $name = "";
            if (isset($spreadSheetAry[$i][2])) {
                $name = mysqli_real_escape_string($conn, $spreadSheetAry[$i][2]);
            }

            $email = "";
            if (isset($spreadSheetAry[$i][3])) {
                $email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][3]);
            }

            $password = "";
            if (isset($spreadSheetAry[$i][4])) {
                $password = mysqli_real_escape_string($conn, $spreadSheetAry[$i][4]);
            }

            $phone = "";
            if (isset($spreadSheetAry[$i][5])) {
                $phone = mysqli_real_escape_string($conn, $spreadSheetAry[$i][5]);
            }

            $adr = "";
            if (isset($spreadSheetAry[$i][6])) {
                $adr = mysqli_real_escape_string($conn, $spreadSheetAry[$i][6]);
            }

            $dob = "";
            if (isset($spreadSheetAry[$i][7])) {
                $dob = mysqli_real_escape_string($conn, $spreadSheetAry[$i][7]);
            }

            $idno = "";
            if (isset($spreadSheetAry[$i][8])) {
                $idno = mysqli_real_escape_string($conn, $spreadSheetAry[$i][8]);
            }

            $gender = "";
            if (isset($spreadSheetAry[$i][9])) {
                $gender = mysqli_real_escape_string($conn, $spreadSheetAry[$i][9]);
            }

            $acc_status = "";
            if (isset($spreadSheetAry[$i][10])) {
                $acc_status = mysqli_real_escape_string($conn, $spreadSheetAry[$i][10]);
            }

            $created_at = "";
            if (isset($spreadSheetAry[$i][11])) {
                $created_at = mysqli_real_escape_string($conn, $spreadSheetAry[$i][11]);
            }
            $faculty = "";
            if (isset($spreadSheetAry[$i][11])) {
                $faculty = mysqli_real_escape_string($conn, $spreadSheetAry[$i][11]);
            }

            if (!empty($name) || !empty($admno) || !empty($idno) || !empty($gender) || !empty($email)) {
                $query = "INSERT INTO ezanaLMS_Students (id, faculty_id,  admno, name, email, password, phone, adr, dob, idno, gender, acc_status, created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $paramType = "ssssssssssss";
                $paramArray = array(
                    $id,
                    $faculty,
                    $admno,
                    $name,
                    $email,
                    $password,
                    $phone,
                    $adr,
                    $dob,
                    $idno,
                    $gender,
                    $acc_status,
                    $created_at
                );
                $insertId = $db->insert($query, $paramType, $paramArray);
                // $query = "insert into tbl_info(name,description) values('" . $name . "','" . $description . "')";
                // $result = mysqli_query($conn, $query);
                if (!empty($insertId)) {
                    $success = "Excel Data Imported into the Database";
                } else {
                    $success = "Excel Data Imported into the Database";
                }
            }
        }
    } else {
        $info = "Invalid File Type. Upload Excel File.";
    }
}

require_once('partials/_head.php');
