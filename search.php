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

//get search term
$searchTerm = $_GET['term'];

//get matched data from skills table
$query = "SELECT DISTINCT Name FROM mockdata WHERE Name LIKE '%".$searchTerm."%' ORDER BY Name ASC";
$result = mysqli_query($mysqli, $query);

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row['Name'];
}
//return json data
echo json_encode($data);
?>