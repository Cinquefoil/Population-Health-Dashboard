<?php
include_once "mysqli.connect.php";
session_start();

$name = $_POST['Name'];
$email = $_POST['Email'];
$role = $_POST['Role'];

$query = 'INSERT INTO account (name, email, role, password) VALUES ("' . $name . '", "' . $email . '", "' . $role . '", "ktph")';
if(mysqli_query($mysqli, $query)){
	header('Location: account.php');
}
?>