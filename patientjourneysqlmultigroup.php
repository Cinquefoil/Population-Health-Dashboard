<?php
include_once "mysqli.connect.php";
	$groupAage = $_POST['groupAage'];
	$groupArace = $_POST['groupArace'];
	$groupAgender = $_POST['groupAgender'];
	$groupAsmoking = $_POST['groupAsmoking'];
	$groupBage = $_POST['groupBage'];
	$groupBrace = $_POST['groupBrace'];
	$groupBgender = $_POST['groupBgender'];
	$groupBsmoking = $_POST['groupBsmoking'];
	
	/*$groupAage = "40-49";
	$groupArace = "Chinese";
	$groupAgender = "Female";
	$groupAsmoking = "No";
	$groupBage = "40-49";
	$groupBrace = "Chinese";
	$groupBgender = "Female";
	$groupBsmoking = "No";*/
	
	$presentation = array("presentation" => "Timeglider", "title" => "KTPH Population Health Touchpoints", "focus_date" => "2014-04-00 00:00:00", "initial_zoom" => "30", "initial_timelines" => ["timeline1", "timeline2"]);
	$header = array("id" => "timeline1", "title" => "Group A", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30", "inverted" => "true", "bottom" => "170");
	$header2 = array("id" => "timeline2", "title" => "Group B", "focus_date" => "2014-04-00 12:00:00", "initial_zoom" => "30", "bottom" => "225");
	
	$legend = array();
	$legend[] = array("title" => "Health Screening (Healthy)", "icon" => "healthy.png");
	$legend[] = array("title" => "Health Screening (Unhealthy)", "icon" => "unhealthy.png");
	$legend[] = array("title" => "Visited Polyclinic", "icon" => "polyclinic.png");
	$legend[] = array("title" => "Teleconsult", "icon" => "teleconsult.png");
	$legend[] = array("title" => "House Visit", "icon" => "house.png");
	$legend[] = array("title" => "Intervention Programmes", "icon" => "intervention.png");
	$groupAnodes = array();
	$groupBnodes = array();

	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT NRIC,`Measurement.Att.Date` as id, `Measurement.Att.Date` as startdate FROM ktphalldata WHERE Healthy = 'Healthy' AND AgeGrp = '" . $groupAage . "' AND `Race.Full.Text` = '" . $groupArace . "' AND `Gender.Full.Text` = '" . $groupAgender . "' AND `X8Q_LS_Smoking` = '" . $groupAsmoking . "'";
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
        $groupAnodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT NRIC,`Measurement.Att.Date` as id, `Measurement.Att.Date` as startdate FROM ktphalldata WHERE Healthy = 'Unhealthy' AND AgeGrp = '" . $groupAage . "' AND `Race.Full.Text` = '" . $groupArace . "' AND `Gender.Full.Text` = '" . $groupAgender . "' AND `X8Q_LS_Smoking` = '" . $groupAsmoking . "'";
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
        $groupAnodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT NRIC,DateOfPolyVisit as id, DateOfPolyVisit as startdate FROM ktphalldata WHERE DateOfPolyVisit <> 'NA' AND AgeGrp = '" . $groupAage . "' AND `Race.Full.Text` = '" . $groupArace . "' AND `Gender.Full.Text` = '" . $groupAgender . "' AND `X8Q_LS_Smoking` = '" . $groupAsmoking . "' ORDER BY DateOfPolyVisit DESC LIMIT 3";
	$query = mysqli_query($mysqli,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "polyclinic.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $groupAnodes[] = $node;
    }
	
	/*Teleconsult*/
	$teleconsultquery = "SELECT NRIC,DateOfPolyVisit as id, DateOfPolyVisit as startdate FROM ktphalldata WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Teleconsult' AND AgeGrp = '" . $groupAage . "' AND `Race.Full.Text` = '" . $groupArace . "' AND `Gender.Full.Text` = '" . $groupAgender . "' AND `X8Q_LS_Smoking` = '" . $groupAsmoking . "' LIMIT 3";
    $query = mysqli_query($mysqli,$teleconsultquery);
    if (!$teleconsultquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "teleconsult.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $groupAnodes[] = $node;
    }
	
	/*House Visit*/
	$housevisitquery = "SELECT NRIC,DateOfPolyVisit as id, DateOfPolyVisit as startdate FROM ktphalldata WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Y' AND AgeGrp = '" . $groupAage . "' AND `Race.Full.Text` = '" . $groupArace . "' AND `Gender.Full.Text` = '" . $groupAgender . "' AND `X8Q_LS_Smoking` = '" . $groupAsmoking . "' LIMIT 3";
    $query = mysqli_query($mysqli,$housevisitquery);
    if (!$housevisitquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "house.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $groupAnodes[] = $node;
    }
	
	/********************************************************************************/
	
	/*Screening Result (Healthy)*/
	$goodscreeningquery = "SELECT NRIC,`Measurement.Att.Date` as id, `Measurement.Att.Date` as startdate FROM ktphalldata WHERE Healthy = 'Healthy' AND AgeGrp = '" . $groupBage . "' AND `Race.Full.Text` = '" . $groupBrace . "' AND `Gender.Full.Text` = '" . $groupBgender . "' AND `X8Q_LS_Smoking` = '" . $groupBsmoking . "'";
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
        $groupBnodes[] = $node;
    }
	
	/*Screening Result (Unhealthy)*/
	$badscreeningquery = "SELECT NRIC,`Measurement.Att.Date` as id, `Measurement.Att.Date` as startdate FROM ktphalldata WHERE Healthy = 'Unhealthy' AND AgeGrp = '" . $groupBage . "' AND `Race.Full.Text` = '" . $groupBrace . "' AND `Gender.Full.Text` = '" . $groupBgender . "' AND `X8Q_LS_Smoking` = '" . $groupBsmoking . "'";
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
        $groupBnodes[] = $node;
    }
	
	/*Visit Polyclinic*/
	$polyquery = "SELECT NRIC,DateOfPolyVisit as id, DateOfPolyVisit as startdate FROM ktphalldata WHERE DateOfPolyVisit <> 'NA' AND AgeGrp = '" . $groupBage . "' AND `Race.Full.Text` = '" . $groupBrace . "' AND `Gender.Full.Text` = '" . $groupBgender . "' AND `X8Q_LS_Smoking` = '" . $groupBsmoking . "' ORDER BY DateOfPolyVisit DESC LIMIT 3";
	$query = mysqli_query($mysqli,$polyquery);
    if (!$polyquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "polyclinic.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $groupBnodes[] = $node;
    }
	
	/*Teleconsult*/
	$teleconsultquery = "SELECT NRIC,DateOfPolyVisit as id, DateOfPolyVisit as startdate FROM ktphalldata WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Teleconsult' AND AgeGrp = '" . $groupBage . "' AND `Race.Full.Text` = '" . $groupBrace . "' AND `Gender.Full.Text` = '" . $groupBgender . "' AND `X8Q_LS_Smoking` = '" . $groupBsmoking . "' LIMIT 3";
    $query = mysqli_query($mysqli,$teleconsultquery);
    if (!$teleconsultquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "teleconsult.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $groupBnodes[] = $node;
    }
	
	/*House Visit*/
	$housevisitquery = "SELECT NRIC,DateOfPolyVisit as id, DateOfPolyVisit as startdate FROM ktphalldata WHERE DateOfPolyVisit <> 'NA' AND NurseAction = 'Y' AND AgeGrp = '" . $groupBage . "' AND `Race.Full.Text` = '" . $groupBrace . "' AND `Gender.Full.Text` = '" . $groupBgender . "' AND `X8Q_LS_Smoking` = '" . $groupBsmoking . "' LIMIT 3";
    $query = mysqli_query($mysqli,$housevisitquery);
    if (!$housevisitquery){
        echo "mySQL Error";
        die("Connection failed: ");
	}
	while($node = mysqli_fetch_assoc($query)){
		$node['icon'] = "house.png";
		$node['title'] = "";
		$node['importance'] = "50";
		$node['startdate'] = DateTime::createFromFormat('d/m/Y', $node['startdate'])->format('Y-m-d H:i:s');
        $groupBnodes[] = $node;
    }
	
	
	$timelineA = (object)$header;
	$timelineA->events = $groupAnodes;
	$timelineB = (object)$header2;
	$timelineB->events = $groupBnodes;
	
	$bothTimeline = array();
	$bothTimeline[] = $timelineA;
	$bothTimeline[] = $timelineB;
	
	$presentation['timelines'] = $bothTimeline;
	$presentation['legend'] = $legend;
	
	echo json_encode($presentation);
    mysqli_close($mysqli);
?>