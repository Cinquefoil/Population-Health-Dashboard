<?php
if($_SESSION['role'] != "Admin" && $_SESSION['role'] != "Senior Management"){
	header("location: patientjourney.php");
}
?>