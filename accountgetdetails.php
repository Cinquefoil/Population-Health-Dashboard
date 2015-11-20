<?php
include_once "mysqli.connect.php";
session_start();

$name = $_POST['name'];

$query = 'SELECT name, email, role FROM account WHERE name = "' . $name . '"';
$result = mysqli_query($mysqli, $query);

$row = mysqli_fetch_assoc($result);
echo json_encode($row);
?>