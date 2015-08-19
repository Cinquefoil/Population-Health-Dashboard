<?php
if($_SESSION['access'] != "admin" && $_SESSION['access'] != "senior"){
	header("location: patientjourney.php");
}
?>