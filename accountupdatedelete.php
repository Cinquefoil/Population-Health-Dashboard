<?php
include_once "mysqli.connect.php";
session_start();

$name = $_POST['RetrievedName'];
$email = $_POST['RetrievedEmail'];
$role = $_POST['RetrievedRole'];

//Update
if (isset($_POST['Update'])) {
    $query = 'UPDATE account SET name = "' . $name . '", role = "' . $role . '" WHERE email = "' . $email . '"';
	if(mysqli_query($mysqli, $query)){
		header('Location: account.php');
	}
}

//Delete
else {
    $query = 'DELETE FROM account WHERE email = "' . $email . '"';
	if(mysqli_query($mysqli, $query)){
		header('Location: account.php');
	}
}
?>