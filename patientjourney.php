<?php
include_once "checkSession.php";
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
    
		<title>Patient Journey</title>
  
		<!-- Javascript -->
		<script src="js/bootstrap.min.js" type="text/javascript"></script>
		<script src="js/json2.js" type="text/javascript"></script>
		<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
		<script src="js/underscore-min.js" type="text/javascript"></script>
		<script src="js/backbone-min.js" type="text/javascript"></script>
		<script src="js/jquery.tmpl.js" type="text/javascript"></script>
		<script src="js/ba-debug.min.js" type="text/javascript"></script>
		<script src="js/ba-tinyPubSub.js" type="text/javascript"></script>
		<script src="js/jquery.mousewheel.js" type="text/javascript"></script>
		<script src="js/jquery.ui.ipad.js" type="text/javascript"></script>
		<script src="js/globalize.js" type="text/javascript"></script>
		<script src="js/modernizr.custom.js" type="text/javascript"></script>
		<script src="js/jquery.jscrollpane.min.js" type="text/javascript"></script>
		<script src="timeglider/TG_Date.js" type="text/javascript"></script>
		<script src="timeglider/TG_Org.js" type="text/javascript"></script>
		<script src="timeglider/TG_Timeline.js" type="text/javascript"></script> 
		<script src="timeglider/TG_TimelineView.js" type="text/javascript"></script>
		<script src="timeglider/TG_Mediator.js" type="text/javascript"></script> 
		<script src="timeglider/timeglider.timeline.widget.js" type="text/javascript"></script>
		<script src="timeglider/timeglider.datepicker.js" type="text/javascript"></script>
		<script>
			$(function() {
				$("#nameA").autocomplete({
					source: 'search.php'
				});
			});
		</script>
		<script>
			$(function() {
				$("#nameB").autocomplete({
					source: 'search.php'
				});
			});
		</script>
		<script src="js/jquery-ui.js"></script>
		<script type='text/javascript'>
			function compareP(){
				var patient = document.getElementById("nameA").value;
				
				$.ajax({
					url: "patientjourneysql.php",
					type: "post",
					data: {"patient": patient}, 
					success: function(data) {
						var json = JSON.parse(data);
						
						var tg1 = window.tg1 = "";
		   
						$(function () { 
							var tg_instance = {};

							tg1 = $("#p1").timeline({			
								"min_zoom":1, 
								"max_zoom":40,
								"icon_folder":"timeglider/icons/",
								//"data_source": "presentation.json",
								//"data_source": "patientjourneysqlmulti.php",
								"data_source": json,
								"show_footer":true,
								"display_zoom_level":false,
								"mousewheel":"zoom", // zoom | pan | none
								"constrain_to_data":true,
								"image_lane_height":100,
								"legend":{type:"checkboxes"}, // default | checkboxes
								"loaded":function () { 
									// loaded callback function
								 }
							});
						});
					}
				});
			}
			
			function compareG(){
				var groupAage = document.getElementById("ageA").value;
				var groupArace = document.getElementById("raceA").value;
				var groupAgender = document.getElementById("genderA").value;
				var groupAsmoking = document.getElementById("smokingA").value;
				var groupBage = document.getElementById("ageB").value;
				var groupBrace = document.getElementById("raceB").value;
				var groupBgender = document.getElementById("genderB").value;
				var groupBsmoking = document.getElementById("smokingB").value;
				
				$.ajax({
					url: "patientjourneysqlmultigroup.php",
					type: "post",
					data: {"groupAage": groupAage, "groupArace": groupArace, "groupAgender": groupAgender, "groupAsmoking": groupAsmoking, "groupBage": groupBage, "groupBrace": groupBrace, "groupBgender": groupBgender, "groupBsmoking": groupBsmoking}, 
					success: function(data) {
						var json = JSON.parse(data);
						
						var tg1 = window.tg1 = "";
		   
						$(function () { 
							var tg_instance = {};

							tg1 = $("#p1").timeline({			
								"min_zoom":1, 
								"max_zoom":40,
								"icon_folder":"timeglider/icons/",
								//"data_source": "presentation.json",
								//"data_source": "patientjourneysqlmulti.php",
								"data_source": json,
								"show_footer":true,
								"display_zoom_level":false,
								"mousewheel":"zoom", // zoom | pan | none
								"constrain_to_data":true,
								"image_lane_height":100,
								"legend":{type:"checkboxes"}, // default | checkboxes
								"loaded":function () { 
									// loaded callback function
								 }
							});
						});
					}
				});
			}
		</script>

		<!-- CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
		<link href="css/tg_legend_checkboxes.css" rel="stylesheet">
		<link href="timeglider/Timeglider.css" rel="stylesheet">
		<link href="timeglider/timeglider.datepicker.css" rel="stylesheet">
		<link rel="stylesheet" href="css/jquery-ui.css">
    
		<!-- Custom CSS -->
		<style>
			body {
				padding-top: 10px;
				padding-left: 30px;
				background: white;
			}
			
			#bs-example-navbar-collapse-1 ul li a:hover {
				border-bottom:2px #FFF solid;
			}
			
			.gradientBoxesWithOuterShadows { 
				border-style: outset;
				border-radius: 20px;
				-moz-border-radius: 10px;
				box-shadow: 2px 2px 2px #cccccc;
				-moz-box-shadow: 2px 2px 2px #cccccc;
			}
			
			.header {
				margin:32px;
			}

			#p1 {
				margin:32px;
				margin-bottom:0;
				height:450px;
			}
					
			.timeglider-legend {
				width:180px;
			}
			
			*.no-select {
			-moz-user-select: -moz-none;
			-khtml-user-select: none;
			-webkit-user-select: none;
			user-select: none;
			}
			
			.timeglider-timeline-event.ongoing .timeglider-event-title {
				color:green;
			}
			
			/* fix bootstrap v3 issue */
			.timeglider-container,
			.timeglider-container:before,
			.timeglider-container:after {
				-webkit-box-sizing: content-box;
				-moz-box-sizing: content-box;
				box-sizing: content-box;
			}

			/* restore default value */
			.timeglider-container *,
			.timeglider-container *:before,
			.timeglider-container *:after{
				-webkit-box-sizing: content-box;
				-moz-box-sizing: content-box;
				box-sizing: content-box;
			}
		</style>
	</head>
  
	<body>
		<!-- Navigation -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav" style="font-size:12px">
						<?php
							if($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Senior"){
						?>
							<li>
								<a href="home.php"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Screening Result</a>
							</li>
							<li>
								<a href="classificationTry1.php"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Health Classification</a>
							</li>
						<?php
							}
						?>
						<li>
							<a href="patientjourney.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Patient Journey</a>
						</li>
						<?php
							if($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Senior"){
						?>
							<li>
								<a href="analysis.php"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Repeat Analysis</a>
							</li>
							<li>
								<a href="geospatial.php"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Geospatial Intelligence</a>
							</li>
						<?php
							}
						?>
						<?php
						if($_SESSION['role'] == "Admin"){
							echo '
							<li>
								<a href="dataprocessing.php"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Data Processing</a>
							</li>
							<li>
								<a href="account.php"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> User Account</a>
							</li>
							';
						}?>
						<li>
							<a href="logout.php"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a>
						</li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>
		
		</br></br></br>
		
		<div class="gradientBoxesWithOuterShadows" align="center" style="float:left;height:520px;width:350px;margin:00px 10px 0px 0px">
			<br />
		
			<span style="text-align:center;font-weight:bold">Individual Patient Journey</span>
			
			<br /><br />
		
			<table style="font-weight:bold">
				<tr>
					<td>
						<div class="ui-widget" align="center">
							<label for="nameA">Patient: </label> <input id="nameA">
							<br /><br />
							<input type="submit" id="comparePatients" name="comparePatients" class="btn btn-primary" value="View Patient Journey" style="color:#cfe5f2" onclick="compareP();"/>
							<br /><br />
							<hr />
						</div>
					</td>
				</tr>
			</table>
			<span style="text-align:center;font-weight:bold">Temporal Event Sequence</span>
			
			<br /><br />
			
			<u style="text-align:center;font-weight:bold">Group A</u>		
			<table style="font-weight:bold">
				<tr>
					<td>
						Age
					</td>
					<td>
						<select id="ageA" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT AgeGrp FROM ktphalldata ORDER BY AgeGrp";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['AgeGrp'] . '">' . $row['AgeGrp'] . '</option>';
								}
							?>
						</select>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						Race
					</td>
					<td>
						<select id="raceA" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT `Race.Full.Text` as race FROM ktphalldata ORDER BY `Race.Full.Text`";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['race'] . '">' . $row['race'] . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Gender
					</td>
					<td>
						<select id="genderA" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT `Gender.Full.Text` as gender FROM ktphalldata ORDER BY `Gender.Full.Text`";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['gender'] . '">' . $row['gender'] . '</option>';
								}
								
							?>
						</select>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						Smoking
					</td>
					<td>
						<select id="smokingA" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT `X8Q_LS_Smoking` as smoking FROM ktphalldata ORDER BY `X8Q_LS_Smoking`";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['smoking'] . '">' . $row['smoking'] . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
			</table>
			
			<br />
			
			<u style="text-align:center;font-weight:bold">Group B</u>		
			<table style="font-weight:bold">
				<tr>
					<td>
						Age
					</td>
					<td>
						<select id="ageB" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT AgeGrp FROM ktphalldata ORDER BY AgeGrp";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['AgeGrp'] . '">' . $row['AgeGrp'] . '</option>';
								}
							?>
						</select>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						Race
					</td>
					<td>
						<select id="raceB" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT `Race.Full.Text` as race FROM ktphalldata ORDER BY `Race.Full.Text`";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['race'] . '">' . $row['race'] . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Gender
					</td>
					<td>
						<select id="genderB" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT `Gender.Full.Text` as gender FROM ktphalldata ORDER BY `Gender.Full.Text`";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['gender'] . '">' . $row['gender'] . '</option>';
								}
								
							?>
						</select>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						Smoking
					</td>
					<td>
						<select id="smokingB" style="width:80px">
							<option value="All">All</option>
							<?php
								$query = "SELECT DISTINCT `X8Q_LS_Smoking` as smoking FROM ktphalldata ORDER BY `X8Q_LS_Smoking`";
								$result = mysqli_query($mysqli, $query);
								
								while($row = mysqli_fetch_assoc($result)){
									echo '<option value="' . $row['smoking'] . '">' . $row['smoking'] . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<div align="center">
							<br />
							<input type="submit" id="compareGroups" name="compareGroups" class="btn btn-primary" value="Compare Demographic Groups" style="color:#cfe5f2" onclick="compareG();"/>
						</div>
					</td>
				</tr>
			</table>
		</div>
		
		<div id='p1'></div>
		
		<div style="clear:both"></div>
	</body>
</html>