<?php
$username = $_POST["username"];
$pswd = $_POST["password"];
$validate = false;

do {
	if ($username == "ahmad" && $pswd == "1234") {
		$validate = true;
		header("Location: /pharmagains");
		exit();
		break;
	} elseif ($username == "thayyib" && $pswd == "4321") {
		$validate = true;
		header("Location: /pharmagains");
		exit();
		break;
	}

	$validate = false;
	header("Location: index.html");
	exit();
} while ($validate == false)
?>