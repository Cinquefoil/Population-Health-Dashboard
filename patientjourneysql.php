<?php
include_once "mysqli.connect.php";
	$patient = $_POST['patient'];
	
	$header = array("id" => "timeline", "title" => "KTPH Population Health Touchpoints", "focus_date" => "2014-03-00 12:00:00", "initial_zoom" => "29");
	
	$legend = array();
	$legend[] = array("title" => "Health Screening (Healthy)", "icon" => "healthy.png");
	$legend[] = array("title" => "Health Screening (Unhealthy)", "icon" => "unhealthy.png");
	$legend[] = array("title" => "Visited Polyclinic", "icon" => "polyclinic.png");
	$legend[] = array("title" => "Teleconsult", "icon" => "teleconsult.png");
	$legend[] = array("title" => "House Visit", "icon" => "house.png");
	$legend[] = array("title" => "Intervention Programmes", "icon" => "intervention.png");
	$patientnodes = array();

	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT id,ScreeningDate as id, ScreeningDate as startdate FROM mockdata WHERE HealthStatus = 'Healthy' AND Name = '" . $patient . "'";
    $query = mysqli_query($mysqli,$goodscreeningquery);
    if (!$goodscreeningquery){
        echo "mySQL Error";
        die("Connection failed: ");
    }
    while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "healthy.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('Y-m-d', $node['startdate'])->format('Y-m-d H:i:s');
        $patientnodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT id,ScreeningDate as id, ScreeningDate as startdate FROM mockdata WHERE HealthStatus = 'Unhealthy' AND Name = '" . $patient . "'";	
    $query = mysqli_query($mysqli,$badscreeningquery);
    if (!$badscreeningquery){
        echo "mySQL Error";
        die("Connection failed: ");
    }
    while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "unhealthy.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('Y-m-d', $node['startdate'])->format('Y-m-d H:i:s');
        $patientnodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT id,PolyVisitDate as id, PolyVisitDate as startdate FROM mockdata WHERE PolyVisitDate <> 'NA' AND Name = '" . $patient . "'";
    $query = mysqli_query($mysqli,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "polyclinic.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('Y-m-d', $node['startdate'])->format('Y-m-d H:i:s');
        $patientnodes[] = $node;
    }
	
	/*Teleconsult*/
	$teleconsultquery = "SELECT id,TeleconsultDate as id, TeleconsultDate as startdate FROM mockdata WHERE TeleconsultDate <> 'NA' AND Name = '" . $patient . "'";
    $query = mysqli_query($mysqli,$teleconsultquery);
    if (!$teleconsultquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "teleconsult.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('Y-m-d', $node['startdate'])->format('Y-m-d H:i:s');
        $patientnodes[] = $node;
    }
	
	/*House Visit*/
	$housevisitquery = "SELECT id,HouseVisitDate as id, HouseVisitDate as startdate FROM mockdata WHERE HouseVisitDate <> 'NA' AND Name = '" . $patient . "'";
    $query = mysqli_query($mysqli,$housevisitquery);
    if (!$housevisitquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "house.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('Y-m-d', $node['startdate'])->format('Y-m-d H:i:s');
        $patientnodes[] = $node;
    }
	
	$finalJson = (object)$header;
	$finalJson->events = $patientnodes;
	$finalJson->legend = $legend;

	echo json_encode(array($finalJson));
    mysqli_close($mysqli);
?>