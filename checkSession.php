<?php
include_once "mysqli.connect.php";
session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['role'])){
	header("Location: login.php");
}
else{
	$login_user = $_SESSION['user'];
}
?>