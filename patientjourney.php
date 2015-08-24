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
		<script src="js/d3.js"></script>
			<script type="text/javascript" src="js/d3.layout.js"></script>
		<script src="js/crossfilter.js"></script>
		<script src="js/dc.js"></script>
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/sankey.js"> </script> 
		<script src="js/jquery.gridster.js" type="text/javascript" charset="utf-8"></script>

		<!-- CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/dc.css" rel="stylesheet" type="text/css"/>
		<link href="css/styles.css" rel="stylesheet">
		<link href="css/jquery.gridster.css" rel="stylesheet" />
  
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
    
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
					<!-- <a class='navbar-brand' href='#'><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>-->
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav" style="font-size:11px">
						<?php
							if($_SESSION['access'] == "admin" || $_SESSION['access'] == "senior"){
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
							if($_SESSION['access'] == "admin" || $_SESSION['access'] == "senior"){
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
						if($_SESSION['access'] == "admin"){
							echo '
							<li>
								<a href="dataprocessing.php"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Data Processing</a>
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
		
		</br></br>

		<div class="row">
			<br />
			Individual and Temporal will be here.
		</div>
	</body>
</html>