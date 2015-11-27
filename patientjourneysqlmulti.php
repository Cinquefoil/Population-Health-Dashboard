<?php
include_once "mysqli.connect.php";
	$patient1name = $_POST['patient1name'];
	$patient2name = $_POST['patient2name'];
	
	$presentation = array("presentation" => "Timeglider", "title" => "KTPH Population Health Touchpoints", "focus_date" => "2014-04-00 00:00:00", "initial_zoom" => "30", "initial_timelines" => ["timeline1", "timeline2"]);
	$header = array("id" => "timeline1", "title" => "Patient 1", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30", "inverted" => "true", "bottom" => "170");
	$header2 = array("id" => "timeline2", "title" => "Patient 2", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30", "bottom" => "225");
	
	$legend = array();
	$legend[] = array("title" => "Health Screening (Healthy)", "icon" => "healthy.png");
	$legend[] = array("title" => "Health Screening (Unhealthy)", "icon" => "unhealthy.png");
	$legend[] = array("title" => "Visited Polyclinic", "icon" => "polyclinic.png");
	$legend[] = array("title" => "Teleconsult", "icon" => "teleconsult.png");
	$legend[] = array("title" => "House Visit", "icon" => "house.png");
	$legend[] = array("title" => "Intervention Programmes", "icon" => "intervention.png");
	$patient1nodes = array();
	$patient2nodes = array();

	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT id,ScreeningDate as id, ScreeningDate as startdate FROM mockdata WHERE HealthStatus = 'Healthy' AND Name = '" . $patient1name . "'";
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
        $patient1nodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT id,ScreeningDate as id, ScreeningDate as startdate FROM mockdata WHERE HealthStatus = 'Unhealthy' AND Name = '" . $patient1name . "'";	
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
        $patient1nodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT id,PolyVisitDate as id, PolyVisitDate as startdate FROM mockdata WHERE PolyVisitDate <> 'NA' AND Name = '" . $patient1name . "'";
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
        $patient1nodes[] = $node;
    }
	
	/*Teleconsult*/
	$teleconsultquery = "SELECT id,TeleconsultDate as id, TeleconsultDate as startdate FROM mockdata WHERE TeleconsultDate <> 'NA' AND Name = '" . $patient1name . "'";
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
        $patient1nodes[] = $node;
    }
	
	/*House Visit*/
	$housevisitquery = "SELECT id,HouseVisitDate as id, HouseVisitDate as startdate FROM mockdata WHERE HouseVisitDate <> 'NA' AND Name = '" . $patient1name . "'";
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
        $patient1nodes[] = $node;
    }
	
	/********************************************************************************/
	
	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT id,ScreeningDate as id, ScreeningDate as startdate FROM mockdata WHERE HealthStatus = 'Healthy' AND Name = '" . $patient2name . "'";
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
        $patient2nodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT id,ScreeningDate as id, ScreeningDate as startdate FROM mockdata WHERE HealthStatus = 'Unhealthy' AND Name = '" . $patient2name . "'";
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
        $patient2nodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT id,PolyVisitDate as id, PolyVisitDate as startdate FROM mockdata WHERE PolyVisitDate <> 'NA' AND Name = '" . $patient2name . "'";
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
        $patient2nodes[] = $node;
    }
	
	/*Teleconsult*/
	$teleconsultquery = "SELECT id,TeleconsultDate as id, TeleconsultDate as startdate FROM mockdata WHERE TeleconsultDate <> 'NA' AND Name = '" . $patient2name . "'";
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
        $patient2nodes[] = $node;
    }
	
	/*House Visit*/
	$housevisitquery = "SELECT id,HouseVisitDate as id, HouseVisitDate as startdate FROM mockdata WHERE HouseVisitDate <> 'NA' AND Name = '" . $patient2name . "'";
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
        $patient2nodes[] = $node;
    }

	
	$timelineA = (object)$header;
	$timelineA->events = $patient1nodes;
	$timelineB = (object)$header2;
	$timelineB->events = $patient2nodes;
	
	$bothTimeline = array();
	$bothTimeline[] = $timelineA;
	$bothTimeline[] = $timelineB;
	
	$presentation['timelines'] = $bothTimeline;
	$presentation['legend'] = $legend;
	
	echo json_encode($presentation);
    mysqli_close($mysqli);
?>