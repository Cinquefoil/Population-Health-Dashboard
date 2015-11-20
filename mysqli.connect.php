<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "ktph";

$mysqli = mysqli_connect($host, $user, $password, $database);

if ($mysqli->errno) {
	echo "Unable to connect to the database: <br />".$mysqli->error;
	exit();
}
?>