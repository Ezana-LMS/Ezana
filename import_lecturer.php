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

        $targetPath = 'dist/XLSFiles/' . $_FILES['file']['name'];
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

            $number = "";
            if (isset($spreadSheetAry[$i][1])) {
                $number = mysqli_real_escape_string($conn, $spreadSheetAry[$i][1]);
            }
            $name = "";
            if (isset($spreadSheetAry[$i][2])) {
                $name = mysqli_real_escape_string($conn, $spreadSheetAry[$i][2]);
            }

            $idno = "";
            if (isset($spreadSheetAry[$i][3])) {
                $idno = mysqli_real_escape_string($conn, $spreadSheetAry[$i][3]);
            }

            $phone = "";
            if (isset($spreadSheetAry[$i][4])) {
                $phone = mysqli_real_escape_string($conn, $spreadSheetAry[$i][4]);
            }

            $email = "";
            if (isset($spreadSheetAry[$i][5])) {
                $email = mysqli_real_escape_string($conn, $spreadSheetAry[$i][5]);
            }

            $adr = "";
            if (isset($spreadSheetAry[$i][6])) {
                $adr = mysqli_real_escape_string($conn, $spreadSheetAry[$i][6]);
            }

            $password = "";
            if (isset($spreadSheetAry[$i][7])) {
                $password = mysqli_real_escape_string($conn, $spreadSheetAry[$i][7]);
            }

            $created_at = "";
            if (isset($spreadSheetAry[$i][8])) {
                $created_at = mysqli_real_escape_string($conn, $spreadSheetAry[$i][8]);
            }

            $facuty_id = "";
            if (isset($spreadSheetAry[$i][9])) {
                $created_at = mysqli_real_escape_string($conn, $spreadSheetAry[$i][9]);
            }

            if (!empty($name) || !empty($admno) || !empty($idno) || !empty($gender) || !empty($email)) {
                $query = "INSERT INTO ezanaLMS_Lecturers (id, faculty_id number, name, idno, phone, email, adr, password, created_at) VALUES(?,?,?,?,?,?,?,?,?,?)";
                $paramType = "sssssssss";
                $paramArray = array(
                    $id,
                    $number,
                    $name,
                    $idno,
                    $phone,
                    $email,
                    $adr,
                    $password,
                    $created_at,
                    $facuty_id
                );
                $insertId = $db->insert($query, $paramType, $paramArray);
                if (!empty($insertId)) {
                    $success = "Excel Data Imported into the Database";
                } else {
                    $success = "Excel Data Imported into the Database" ;
                }
            }
        }
    } else {
        $info = "Invalid File Type. Upload Excel File.";
    }
}

require_once('partials/_head.php');
?>