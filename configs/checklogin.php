<?php
function check_login()
{
	if ((strlen($_SESSION['id']) == 0) || (strlen($_SESSION['email']) == 0) || (strlen($_SESSION['name']) == 0)) {
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = "index.php";
		$_SESSION["id"] = "";
		$_SESSION["email"] = "";
		$_SESSION["name"] = "";
		header("Location: http://$host$uri/$extra");
	}
}
