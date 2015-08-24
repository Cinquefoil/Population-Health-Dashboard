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
    
		<title>Health Classification</title>
  
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
		<style type="text/css">
			.chart {
			  display: block;
			  margin: auto;
			  margin-top: 60px;
			  font-size: 15px;
			}

			rect {
			  stroke: #eee;
			  fill: #aaa;
			  fill-opacity: .8;
			}

			rect.parent1 {
			  cursor: pointer;
			  fill: steelblue;
			}

			text {
			  pointer-events: none;
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
						<li>
							<a href="home.php"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Screening Result</a>
						</li>
						<li>
							<a href="classificationTry1.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Health Classification</a>
						</li>
						<li>
							<a href="patientjourney.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Patient Journey</a>
						</li>
						<li>
							<a href="analysis.php"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Repeat Analysis</a>
						</li>
						<li>
							<a href="geospatial.php"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Geospatial Intelligence</a>
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
			<table align="center">
				<tr bgcolor="#FF0000">
					<td colspan="3">
						<h3>Inputs for Date and Location will be selected from here </h3>
					</td>
				</tr>
				<tr>
					<td>
						<div id="nurse-intervention-bar" style="background:#f8f7f7">
						Metric on Nurse Intervention<br>
						(Tele Consult, Home Visit)<br>
						and Uncontactable cases</br>
						- To show no. of follow-up cases,<br>
						cases contacted and cases uncontactable
					</td>
					<td>
						<div id="seen-doc-bar" style="background:#f8f7f7">
						Display subset from Cases contacted,<br>
						how many have seen doctor
					</td>
					<td>
						Health Summary (Sample Image)<br>
						<img src="images/vennSample.jpg" height="200" width="200">
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="healthTree">
						<h3>Health Classification focusing on New Cases of BS and BP</h3>
						</div>
					</td>
				</tr>
			</table>
		</div>

		<script type="text/javascript">
			var w = 1024,
				h = 550,
				x = d3.scale.linear().range([0, w]),
				y = d3.scale.linear().range([0, h]);

			var vis = d3.select("#healthTree").append("div")
				.attr("class", "chart")
				.style("width", w + "px")
				.style("height", h + "px")
				.append("svg:svg")
				.attr("width", w)
				.attr("height", h);
				
			var counting=0; //get total number of resident for percentage calculation

			var partition = d3.layout.partition()
				.value(function(d) { 
				counting += d.size;
				return d.size; });

			d3.json("flare1.json", function(root) {

				var g = vis.selectAll("g")
					.data(partition.nodes(root))
					.enter().append("svg:g")
					.attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; })
					.on("click", click);

				var kx = w / root.dx,
					ky = h / 1;

				g.append("svg:rect")
					.attr("width", root.dy * kx)
					.attr("height", function(d) { return d.dx * ky; })
					.attr("class", function(d) { return d.children ? "parent" : "child"; })
					.style("fill", function(d){
						if (d.name === "New BS/BP" || d.name === "New Cases BS/BP"){
							return "#d62728";
						}else if (d.name === "Poor Control BS/BP"){
							return "#ff7f0e";
						}else if (d.name === "Good Control Cholesterol" || d.name === "Good Control BS/BP"){
							return "#2ca02c";
						} 
					});

				g.append("svg:text")
					.attr("transform", transform)
					.attr("dy", ".35em")
					.style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; })
					.text(function(d) { 
						return d.name  + " " + d.value + " (" + d3.round((d.value/counting*100),1) + "%)";
					})

				d3.select(window)
					.on("click", function() { click(root); })

				function click(d) {
					if (!d.children) return;

					kx = (d.y ? w - 40 : w) / (1 - d.y);
					ky = h / d.dx;
					x.domain([d.y, 1]).range([d.y ? 40 : 0, w]);
					y.domain([d.x, d.x + d.dx]);

					var t = g.transition()
						.duration(d3.event.altKey ? 7500 : 750)
						.attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; });

					t.select("rect")
						.attr("width", d.dy * kx)
						.attr("height", function(d) { return d.dx * ky; });

					t.select("text")
						.attr("transform", transform)
						.style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; });

					d3.event.stopPropagation();
				}

				function transform(d) {
					return "translate(8," + d.dx * ky / 2 + ")";
				}
			  
				function print_filter(filter){
					var f=eval(filter);
					if (typeof(f.length) != "undefined") {}else{}
					if (typeof(f.top) != "undefined") {f=f.top(Infinity);}else{}
					if (typeof(f.dimension) != "undefined") {f=f.dimension(function(d) { return "";}).top(Infinity);}else{}
					console.log(filter+"("+f.length+") = "+JSON.stringify(f).replace("[","[\n\t").replace(/}\,/g,"},\n\t").replace("]","\n]"));
					return f.length;
				}
			}); <!-- HERE -->
		</script>
	</body>
</html>