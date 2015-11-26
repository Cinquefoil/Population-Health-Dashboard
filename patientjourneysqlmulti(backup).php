<?php
include_once "mysqli.connect.php";
	
	$presentation = array("presentation" => "Timeglider", "title" => "KTPH Population Health Touchpoints", "focus_date" => "2014-07-00 00:00:00", "initial_zoom" => "32", "initial_timelines" => ["timeline1", "timeline2"]);
	$header = array("id" => "timeline1", "title" => "Timeline 1", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30", "inverted" => "true", "bottom" => "170");
	$header2 = array("id" => "timeline2", "title" => "Timeline 2", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30", "bottom" => "225");
	
	$legend = array();
	$legend[] = array("title" => "Health Screening (Healthy)", "icon" => "square_green.png");
	$legend[] = array("title" => "Health Screening (Unhealthy)", "icon" => "square_red.png");
	$legend[] = array("title" => "Visited Polyclinic", "icon" => "plus_blue.png");
	$legend[] = array("title" => "Teleconsult", "icon" => "star_purple.png");
	$legend[] = array("title" => "House Visit", "icon" => "star_orange.png");
	$legend[] = array("title" => "Intervention Programmes", "icon" => "star_yellow.png");
	$nodes = array();

	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT NRIC as id, `Measurement.Att.Date` as startdate FROM `ktphalldata` WHERE `Measurement.Att.Date` <> 'NA' AND healthy = 'Healthy' LIMIT 3";
    $query = mysqli_query($mysqli,$goodscreeningquery);
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
	$badscreeningquery = "SELECT NRIC as id, `Measurement.Att.Date` as startdate FROM `ktphalldata` WHERE `Measurement.Att.Date` <> 'NA' AND healthy = 'Unhealthy' LIMIT 3";	
    $query = mysqli_query($mysqli,$badscreeningquery);
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
	$polyquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' ORDER BY DateOfPolyVisit DESC LIMIT 3";
    $query = mysqli_query($mysqli,$polyquery);
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
	$teleconsultquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Teleconsult' LIMIT 3";
    $query = mysqli_query($mysqli,$teleconsultquery);
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
	$housevisitquery = "SELECT NRIC as id, DateOfPolyVisit as startdate FROM `ktphalldata` WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Y' LIMIT 3";
    $query = mysqli_query($mysqli,$housevisitquery);
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
	
	$timelineA = (object)$header;
	$timelineA->events = $nodes;
	$timelineB = (object)$header2;
	$timelineB->events = $nodes;
	
	$bothTimeline = array();
	$bothTimeline[] = $timelineB;
	$bothTimeline[] = $timelineA;
	
	$presentation['timelines'] = $bothTimeline;
	$presentation['legend'] = $legend;
	
	echo json_encode($presentation);
    mysqli_close($mysqli);
?>