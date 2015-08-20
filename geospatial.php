<?php
include_once "checkSession.php";
include_once "checkAccess.php";
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title>Geospatial Intelligence</title>
	  
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
					<ul class="nav navbar-nav" style="font-size:12px">
						<li>
							<a href="home.php"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Screening Result</a>
						</li>
						<li>
							<a href="classificationTry1.php"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Health Classification</a>
						</li>
						<li>
							<a href="patientjourney.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Patient Journey</a>
						</li>
						<li>
							<a href="analysis.php"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Repeat Analysis</a>
						</li>
						<li>
							<a href="geospatial.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Geospatial Intelligence</a>
						</li>
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
			<table align="center" width="900">
				<tr bgcolor="#FF0000">
					<td colspan="3">
						<h3>Inputs for Date and Location will be selected from here </h3>
					</td>
				</tr>
				<tr bgcolor="#f8f7f7">
					<td>
						<h3>Distribution of Health Screening Population</h3>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<iframe width="100%" height="520" frameborder="0" src="https://catnpete.cartodb.com/viz/3549af44-419c-11e5-946a-0e853d047bba/embed_map" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe></tr>
					</td>
				</tr>
				<tr bgcolor="#f8f7f7">
					<td>
						<h3>Distribution of Health Screening Population Over Time</h3>
					</td>
				</tr>
				<tr>
					<td>
						<iframe width="100%" height="520" frameborder="0" src="https://catnpete.cartodb.com/viz/4193f59c-4242-11e5-bd9f-0e9d821ea90d/embed_map" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
					<td>
				</tr>
				<tr bgcolor="#f8f7f7">
					<td>
						<h3>Cumulative Distribution of Health Screening Population Over Time</h3>
					</td>
				</tr>
				<tr>
					<td>
						<iframe width="100%" height="520" frameborder="0" src="https://catnpete.cartodb.com/viz/e71beeb0-4243-11e5-9d76-0e018d66dc29/embed_map" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
					<td>
				</tr>
			</table>
		</div>

		<script type="text/javascript">
			function print_filter(filter){
				var f=eval(filter);
				if (typeof(f.length) != "undefined") {}else{}
				if (typeof(f.top) != "undefined") {f=f.top(Infinity);}else{}
				if (typeof(f.dimension) != "undefined") {f=f.dimension(function(d) { return "";}).top(Infinity);}else{}
				console.log(filter+"("+f.length+") = "+JSON.stringify(f).replace("[","[\n\t").replace(/}\,/g,"},\n\t").replace("]","\n]"));
				return f.length;
			}
			});
		</script>
	</body>
</html>