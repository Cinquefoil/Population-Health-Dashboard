<?php
    $username = "root"; 
    $password = "";   
    $host = "localhost";
    $database="ktph";
    
    $conn = mysqli_connect($host, $username, $password, $database);
	
	$header = array("id" => "js_history", "title" => "KTPH Population Health Touchpoints", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30");
	$legend = array();
	$legend[] = array("title" => "Health Screening (Healthy)", "icon" => "square_green.png");
	$legend[] = array("title" => "Health Screening (Unhealthy)", "icon" => "square_red.png");
	$legend[] = array("title" => "Visited Polyclinic", "icon" => "plus_blue.png");
	$legend[] = array("title" => "Teleconsult", "icon" => "star_purple.png");
	$legend[] = array("title" => "House Visit", "icon" => "star_orange.png");
	$legend[] = array("title" => "Intervention Programmes", "icon" => "star_yellow.png");
	$nodes = array();

	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT NRIC as id, `Measurement.Att.Date` as startdate FROM `ktphalldata` WHERE `Measurement.Att.Date` <> 'NA' AND healthy = 'Healthy' LIMIT 30";
    $query = mysqli_query($conn,$goodscreeningquery);
    if (!$goodscreeningquery){
        echo "mySQL Error";
        die("Connection failed: ");
    }
    while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "square_green.png";
		$node['title'] = "";
		$node['importance'] = "50";
        $nodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT NRIC as id, `Measurement.Att.Date` as startdate FROM `ktphalldata` WHERE `Measurement.Att.Date` <> 'NA' AND healthy = 'Unhealthy' LIMIT 30";	
    $query = mysqli_query($conn,$badscreeningquery);
    if (!$badscreeningquery){
        echo "mySQL Error";
        die("Connection failed: ");
    }
    while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "square_red.png";
		$node['title'] = "";
		$node['importance'] = "50";
        $nodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' ORDER BY DateOfPolyVisit DESC LIMIT 30";
    $query = mysqli_query($conn,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "plus_blue.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $nodes[] = $node;
    }
	
	/*Teleconsult*/
	$teleconsultquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Teleconsult' LIMIT 30";
    $query = mysqli_query($conn,$teleconsultquery);
    if (!$teleconsultquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "star_purple.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $nodes[] = $node;
    }
	
	/*House Visit*/
	$housevisitquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Y' LIMIT 30";
    $query = mysqli_query($conn,$housevisitquery);
    if (!$housevisitquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "star_orange.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $nodes[] = $node;
    }
	
	/*Intervention Programmes - no data yet*/
	/*
	$interventionquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` LIMIT 30";
    $query = mysqli_query($conn,$interventionquery);
    if (!$interventionquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "star_yellow.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $nodes[] = $node;
    }*/
	
	$finalJson = (object)$header;
	$finalJson->events = $nodes;
	$finalJson->legend = $legend;
	
	echo json_encode(array($finalJson));
    mysqli_close($conn);
?>