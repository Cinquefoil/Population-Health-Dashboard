<?php
    $username = "root"; 
    $password = "";   
    $host = "localhost";
    $database="ktph";
    
    $conn = mysqli_connect($host, $username, $password, $database);
	
	$header = array("id" => "js_history", "title" => "KTPH Population Health Touchpoints", "focus_date" => "2013-09-00 12:00:00", "initial_zoom" => "30");
	$legend = array();
	$legend[] = array("title" => "Health Screening (Healthy)", "icon" => "square_green.png");
	$legend[] = array("title" => "Health Screening (Unhealthy)", "icon" => "square_red.png");
	$legend[] = array("title" => "Visit Polyclinic", "icon" => "plus_blue.png");
	$legend[] = array("title" => "Teleconsult", "icon" => "star_purple.png");
	$legend[] = array("title" => "House Visit", "icon" => "star_orange.png");
	$nodes = array();

	/*Screening Result (Healthy)*/
    $goodscreeningquery = "SELECT NRIC as id, Zone as title, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' AND healthy = 'Healthy' LIMIT 100";
    $query = mysqli_query($conn,$goodscreeningquery);
    if (!$goodscreeningquery){
        echo "mySQL Error";
        die("Connection failed: ");
    }
    while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "square_green.png";
		$node['importance'] = "50";
		$node['startdate'] = date('Y-m-d H:i:s',strtotime($node['startdate']));
        $nodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT NRIC as id, Zone as title, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' AND healthy = 'Unhealthy' LIMIT 10";
    $query = mysqli_query($conn,$badscreeningquery);
    if (!$badscreeningquery){
        echo "mySQL Error";
        die("Connection failed: ");
    }
    while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "square_red.png";
		$node['importance'] = "50";
		$node['startdate'] = date('Y-m-d H:i:s',strtotime($node['startdate']));
        $nodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT NRIC as id, Zone as title, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' ORDER BY NRIC DESC LIMIT 30";
    $query = mysqli_query($conn,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "plus_blue.png";
		$node['importance'] = "50";
		$node['startdate'] = date('Y-m-d H:i:s',strtotime($node['startdate']));
        $nodes[] = $node;
    }
	
	/*Teleconsult*/
	$polyquery = "SELECT NRIC as id, Zone as title, DateOfPolyVisit as startdate, NurseAction FROM `ktphalldata` WHERE NurseAction = 'Teleconsult' LIMIT 30";
    $query = mysqli_query($conn,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "star_purple.png";
		$node['importance'] = "50";
		$node['startdate'] = date('Y-m-d H:i:s',strtotime($node['startdate']));
        $nodes[] = $node;
    }
	
	/*House Visit*/
	$polyquery = "SELECT NRIC as id, Zone as title, DateOfPolyVisit as startdate, NurseAction FROM `ktphalldata` WHERE NurseAction = 'Y' LIMIT 30";
    $query = mysqli_query($conn,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "star_orange.png";
		$node['importance'] = "50";
		$node['startdate'] = date('Y-m-d H:i:s',strtotime($node['startdate']));
        $nodes[] = $node;
    }
	
	$finalJson = (object)$header;
	$finalJson->events = $nodes;
	$finalJson->legend = $legend;
	
	echo json_encode(array($finalJson));
    mysqli_close($conn);
?>