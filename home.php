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

        <title>Screening Result</title>

        <!-- Javascript -->
        <script src="js/d3.js"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script src="js/crossfilter.js"></script>
        <script src="js/dc.js"></script>
		<!-- <script src="js/leaflet.js"></script>
        <script src="js/leaflet.markercluster.js"></script>
        <script src="js/dc.leaflet.js"></script>-->
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.gridster.js" type="text/javascript" charset="utf-8"></script>

        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <!--<link href="css/leaflet.css" rel="stylesheet"/>
        <link href="css/leaflet.markercluster.css" rel="stylesheet"/>-->
        <link href="css/dc.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet">
        <link href="css/jquery.gridster.css" rel="stylesheet" />

        <!-- Custom CSS -->
        <style>
            body {
                padding-top: 20px;
                padding-left: 10px;
                background: white;
            }
            
            #bs-example-navbar-collapse-1 ul li a:hover {
                border-bottom:2px #FFF solid;
            }
        </style>
        <!-- style for Heat-Box -->
        <style>
          .heat-box {
            stroke: #E6E6E6;
            stroke-width: 2px;
          }
        </style>
        <!-- END style for Heat-Box -->
        <!-- style for Map -->
        <style type="text/css">
        svg text {
            fill: black;
        }

        #map{
            width: 550px;
            height: 400px;
            margin: 0;
            padding: 0;
        }
        </style>
        <!-- END style for Map -->
        <!-- style for Stratification Number Display -->        
        <style>
        #tableNumDisplay1 td{
            border: 5px solid #98bf21;
        }
         #numboxHP{
            background: #1f77b4;
            width: 80px;
            font-size: 18px;
            text-align: center;
            padding-bottom: 20px;
            height: 25px;
         }
          #numboxUP{
            background: #2ca02c;
            width: 80px;
            font-size: 18px;
            text-align: center;
            padding-bottom: 20px;
            height: 30px;
         }
          #numboxHN{
            background: #ff7f0e;
            width: 80px;
            font-size: 18px;
            text-align: center;
            padding-bottom: 20px;
            height: 40px;
         }
          #numboxUN{
            background: #d62728;
            width: 80px;
            font-size: 18px;
            text-align: center;
            padding-bottom: 20px;
            height: 86px;
         }
         <!-- END style for Stratification Number Display -->
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
                            <a href="home.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-th-large" style="padding:0px" aria-hidden="true"></span> Screening Result</a>
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
        
		<div class="gridster" ></br></br>
			<span id="reset-all">
				<strong>To Reset All Filters, click <a href="javascript:dc.filterAll();dc.redrawAll();">HERE</a></strong>
			</span> 
			<div class="row">
				<div class="chart-wrapper" id="chart-dateMove-bar" style="background:#f8f7f7">
					<strong>Health Screening Dates (Zoom In View)</strong>
					<a class="reset" href="javascript:dateBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
					<div class="clearfix"></div></br>
				</div>
			</div>
			<div class="row">
				<div class="chart-wrapper" id="chart-date-bar" style="background:#f8f7f7">
					<strong>Health Screening Dates (Select a time range to zoom in)</strong>
					<a class="reset" href="javascript:dateBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
					<div class="clearfix"></div></br>
				</div>
			</div>
			
			<ul class="list-unstyled">
				<li data-row="1" data-col="2" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="test" style="background:#f8f7f7">
						<strong>Health and Habits</strong>
						<a class="reset" href="javascript:chart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="1" data-col="1" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="chart-event-row" style="background:#f8f7f7">
						<strong>Event Location</strong>
						<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="1" data-col="3" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="divMap" style="background:#f8f7f7">
						<div id="stractNum">
							<strong>Health and Habits</strong>
							<table id="tableNumDisplay">
								<tr>
									<td>
										PosHabits
									</td>
									<td valign="bottom">
										<div id="numboxHP"></div>
									</td>
									<td valign="bottom">
										<div id="numboxUP"></div>
									</td>
								</tr>
								<tr>
									<td>
										NegHabits
									</td>
									<td valign="top">
										<div id="numboxHN"></div>
									</td>
									<td valign="top">
										<div id="numboxUN"></div>
									</td>
								</tr>
								<tr>
									<td>
										
									</td>
									<td align="center">
										Healthy
									</td>
									<td align="center">
										Unhealthy
									</td>
								</tr>
							</table>
						</div>
					</div>
				</li>
			<!--<li data-row="2" data-col="1" data-sizex="5" data-sizey="5" style="padding-top:5px;">
					<div class="chart-wrapper" id="divMap" style="background:#f8f7f7">
						<div class="testing">
							<strong>Health and Habits</strong>
						</div>
					</div>
				</li>-->
				<li data-row="4" data-col="3" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="chart-gender-row" style="background:#f8f7f7">
						<strong>Gender</strong>
						<a class="reset" href="javascript:genderRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="6" data-col="2" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="chart-occupation-row" style="background:#f8f7f7">
						<strong>Occupation</strong>
						<a class="reset" href="javascript:occupationRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="6" data-col="3" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="chart-education-row" style="background:#f8f7f7">
						<strong>Education</strong>
						<a class="reset" href="javascript:educationRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="5" data-col="3" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="chart-age-row" style="background:#f8f7f7">
						<strong>Age</strong>
						<a class="reset" href="javascript:ageRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="3" data-col="3" data-sizex="5" data-sizey="4" style="padding-top:5px;">
					<div class="chart-wrapper" id="chart-race-row" style="background:#f8f7f7">
						<strong>Race</strong>
						<a class="reset" href="javascript:raceRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="3" data-col="1" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-bmi-row" style="background:#f8f7f7">
						<strong>BMI</strong>
						<a class="reset" href="javascript:bmiRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="5" data-col="1" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-exercise-row" style="background:#f8f7f7">
						<strong>Exercise (hrs/week)</strong>
						<a class="reset" href="javascript:exerciseChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="4" data-col="1" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-sugar-row" style="background:#f8f7f7">
						<strong>Sugar</strong>
						<a class="reset" href="javascript:sugarRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="3" data-col="2" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-bp-row" style="background:#f8f7f7">
						<strong>BP</strong>
						<a class="reset" href="javascript:bpRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="6" data-col="1" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-foot-row" style="background:#f8f7f7">
						<strong>Diabetes Foot</strong>
						<a class="reset" href="javascript:footChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="5" data-col="2" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-living-row" style="background:#f8f7f7">
						<strong>Living With</strong>
						<a class="reset" href="javascript:livingRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="4" data-col="2" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-smoking-row" style="background:#f8f7f7">
						<strong>Smoking</strong>
						<a class="reset" href="javascript:smokingRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="7" data-col="1" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-backache-row" style="background:#f8f7f7">
						<strong>Backache (Taxi)</strong>
						<a class="reset" href="javascript:backacheChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
						<div class="clearfix"></div></br>
					</div>
				</li>
				<li data-row="7" data-col="2" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
					<div class="chart-wrapper" id="chart-lengthache-row" style="background:#f8f7f7">
					<strong>Length of Backache (Taxi)</strong>
					<a class="reset" href="javascript:lengthacheRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
					<div class="clearfix"></div></br>
				  </div>
				</li>
				<li data-row="7" data-col="3" data-sizex="5" data-sizey="4" style="padding-top:5px;">  
				  <div class="chart-wrapper" id="chart-sleep-row" style="background:#f8f7f7">
					<strong>Length of Sleep (Taxi)</strong>
					<a class="reset" href="javascript:sleepRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
					<div class="clearfix"></div></br>
				  </div>
				</li>
				<li data-row="8" data-col="1" data-sizex="10" data-sizey="6" style="padding-top:5px;">
				  <div class="chart-wrapper" id="map" style="background:#f8f7f7">
					<strong>Resident Distribution Map</strong>
				  </div>
				</li>
				<li data-row="9" data-col="1" data-sizex="15" data-sizey="5" style="padding-top:5px;">
					<div class="chart-wrapper">
						<h4>Resident Information (display for testing purpose)</h4>
					  <table class='table table-hover' id='dc-table-graph' style="background:#f8f7f7">
						<thead>
						  <tr class='header'>
							<th>NRIC</th>
							<th>Zone</th>
							<th>Health Status</th>
							<th>Current Habits</th>
						  </tr>
						</thead>
					  </table>
					</div>
				</li>
			</ul>
		</div>

		<script type="text/javascript">
			/********************************************************
			*														*
			* 	Step0: Testing Google Map                          	*
			*														*
			********************************************************/
			var styledMap = new google.maps.StyledMapType(
				[
				{
					"featureType": "water",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 17
						}
					]
				},
				{
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 20
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 17
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"weight": 0.2
						},
						{
							"lightness": 29
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 18
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 16
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 21
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.text.stroke",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"color": "#000000"
						},
						{
							"lightness": 16
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 40
						}
					]
				},
				{
					"featureType": "all",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "transit",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 19
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"lightness": 20
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"weight": 1.2
						},
						{
							"lightness": 17
						}
					]
				}
				],
				{name: "Styled Map"});


			var map = new google.maps.Map(d3.select("#map").node(), {
				zoom: 11,
				center: new google.maps.LatLng(1.370677, 103.811994),
				mapTypeControlOptions:{
					mapTypeId: [google.maps.MapTypeId.ROADMAP, 'map_style']
				}
				// map_style: styledMap
			});

			/********************************************************
			*														*
			* 	Step0: Load and parse data from csv file    		*
			*														*
			********************************************************/

			var dataset;
			var parseDate = d3.time.format("%d/%m/%Y").parse;

			////////////////////////////////////////////////////////////Google Map
			var lngDim;
			var latDim;
			var projection;
			var padding;

			google.maps.event.addListener(map, "bounds_changed", function(){
				var bounds = this.getBounds();
				var northEast = bounds.getNorthEast();
				var southWest = bounds.getSouthWest();

				// console.log(southWest.lng() + " " + northEast.lng());

				lngDim.filterRange([southWest.lng(), northEast.lng()]);
				latDim.filterRange([southWest.lat(), northEast.lat()]);

				dc.redrawAll();
			})

			map.mapTypes.set('map_style', styledMap);
			map.setMapTypeId('map_style');

			var colorDead = "#de2d26";
			// var colorAcci = "rgb(255, 184, 0)";
			var colorAcci = "rgb(255, 204, 0)";

			///////////////////////////////////////////////////////////////

			//////////////////
			var numDisplayHNHeight = 0;
			/////////////////

			d3.csv("allDataWGeo1.csv", function(data){
				data.forEach(function(d){
					if (d.Healthy === "Healthy" && d.Habits === "Positive Habits"){
						d.healthResult = "Healthy/PosHabits";
						d.HealthyNum = 1;
						d.HabitsNum = 1;
					} else if (d.Healthy === "Healthy" && d.Habits === "Negative Habits"){
						d.healthResult = "Healthy/NegHabits";
						d.HealthyNum = 1;
						d.HabitsNum = 2;
					} else if (d.Healthy === "Unhealthy" && d.Habits === "Positive Habits"){
						d.healthResult = "Unhealthy/PosHabits";
						d.HealthyNum = 2;
						d.HabitsNum = 1;
					} else {
						d.healthResult = "Unhealthy/NegHabits";
						d.HealthyNum = 2;
						d.HabitsNum = 2;
					}
					
					d.date = parseDate(d['Measurement.Att.Date']);
					monthString = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
					d.yearMonth = d.year + " " + monthString[d.month];
					
					if (d.OccupationType === null || d.OccupationType === ""){
						d.OccupationType = "Nil";
					}
					
					 ////////////////////////////////////////////////     Google Map  
					d.GoogleLng = +d.GoogleLng;
					d.GoogleLat = +d.GoogleLat;
			 //////////////////////////////////////////// 
				 
				});
				dataset = data;
				
				/********************************************************
				*														*
				* 	Step1: Create the dc.js chart objects & link to div	*
				*														*
				********************************************************/
				 //create map display GOOGLE MAP
				var overlay = new google.maps.OverlayView();

					overlay.onAdd = function() {
					var layer = d3.select(this.getPanes().overlayLayer).append("div")
							.attr("class", "stations");

					// Draw each marker as a separate SVG element.
					// We could use a single SVG, but what size would it have?
					overlay.draw = function() {

						// console.log(hourDim.top(Infinity));

						projection = this.getProjection();
						padding = 5;


						var bindData = layer.selectAll("svg")
							.data(dataset)
							// .data(hourDim.top(Infinity))

						var marker = bindData.each(transform)
							.enter()
							.append("svg")
							.each(transform)
							.attr({
								"class": "marker",					
							})
							.style({
								"width": "60px",
								"height": "20px",
								"position": "absolute"
							});

						

						// // Add a circle.
						marker.append("svg:circle")
							.attr({
								"r": 2,
								"cx": padding,
								"cy": padding,
								"class": "datadot"
							})
							.style({
								"fill": "none",
								"stroke": function(d){
									/*if(((+d["2-30"]) + (+d["死"]))){
										return colorDead;
									}*/
									return colorAcci;
								},
								"stroke-width": function(d){
									/*if(((+d["2-30"]) + (+d["死"]))){
										return "5px";
									}*/
									// return "1.5px";
									return "3px";
								},
								"position": "absolute",
								"opacity": function(d){
									 return 1;
									/*if(((+d["2-30"]) + (+d["死"]))){
										return 1;
									}
									return 0.3;*/
								},
							})

						// console.log(map.getCenter());

					}

				};// overlay

				// Bind our overlay to the map
				overlay.setMap(map);
				////////////////////////////////////////////////////////////////////

				eventRowChart  = dc.rowChart("#chart-event-row"),
				dateBarChart = dc.barChart("#chart-date-bar"),
				moveChart = dc.barChart("#chart-dateMove-bar"),
				genderRowChart = dc.rowChart("#chart-gender-row"),
				ageRowChart = dc.rowChart("#chart-age-row"),
				raceRowChart = dc.rowChart("#chart-race-row"),
				occupationRowChart = dc.rowChart("#chart-occupation-row"),
				educationRowChart = dc.rowChart("#chart-education-row"),
				bmiRowChart = dc.rowChart("#chart-bmi-row");
				exerciseRowChart = dc.rowChart("#chart-exercise-row");
				sugarRowChart = dc.rowChart("#chart-sugar-row");
				bpRowChart = dc.rowChart("#chart-bp-row");
				footRowChart = dc.rowChart("#chart-foot-row");
				livingRowChart = dc.rowChart("#chart-living-row");
				smokingRowChart = dc.rowChart("#chart-smoking-row");
				backacheRowChart = dc.rowChart("#chart-backache-row");
				lengthacheRowChart = dc.rowChart("#chart-lengthache-row");
				sleepRowChart = dc.rowChart("#chart-sleep-row");
				chart = dc.heatMap("#test");
				var dataTable = dc.dataTable("#dc-table-graph");
				
				numboxHN1    = dc.numberDisplay("#numboxHN");
				numboxUN1    = dc.numberDisplay("#numboxUN");
				numboxHP1    = dc.numberDisplay("#numboxHP");
				numboxUP1    = dc.numberDisplay("#numboxUP");


				/********************************************************
				*														*
				* 	Step2:	Set crossfilter and create Dimension    	*
				*														*
				********************************************************/
				
				var ndx = crossfilter(dataset);
				var all = ndx.groupAll();

				var healthResultDim = ndx.dimension(function(d) {return d.healthResult});
				var healthResultGroup = healthResultDim.group();
				
				var healthResultTESTDim = ndx.dimension(function(d) {return d.healthResult});
				
				var oneHealth = healthResultGroup.top(9)[0].value; //for testing
				print_filter(healthResultGroup); //for testing
				
				var gridster;
				
				var dateDim1 = ndx.dimension(function(d) {return d.date});
				var dateGroup1 = dateDim1.group();
				var minDate = dateDim1.bottom(1)[0].date;
				var maxDate = dateDim1.top(1)[0].date;
				/////////////////////////
				var healthResultTESTGroup = healthResultTESTDim.group().reduce(
					function (p, v) {
						++p.count;
						if (v.healthResult === "Healthy/PosHabits"){
							p.HP++;
						}else if (v.healthResult === "Healthy/NegHabits"){
							p.HN++;
						}else if (v.healthResult === "Unhealthy/PosHabits"){
							p.UP++;
						}else {
							p.UN++;
						}
						return p;
					},
					function (p, v) {
						--p.count;
						if (v.healthResult === "Healthy/PosHabits"){
							p.HP--;
						}else if (v.healthResult === "Healthy/NegHabits"){
							p.HN--;
						}else if (v.healthResult === "Unhealthy/PosHabits"){
							p.UP--;
						}else {
							p.UN--;
						}
						return p;
					},
					function () {
						return {count: 0, HP: 0, HN: 0, UP: 0, UN: 0};
					}
				);
				/////////////////////////
				var healthStatusGroup = dateDim1.group().reduce(
					function (p, v) {
						++p.count;
						if (v.healthResult === "Healthy/PosHabits"){
							p.HP++;
						}else if (v.healthResult === "Healthy/NegHabits"){
							p.HN++;
						}else if (v.healthResult === "Unhealthy/PosHabits"){
							p.UP++;
						}else {
							p.UN++;
						}
						return p;
					},
					function (p, v) {
						--p.count;
						if (v.healthResult === "Healthy/PosHabits"){
							p.HP--;
						}else if (v.healthResult === "Healthy/NegHabits"){
							p.HN--;
						}else if (v.healthResult === "Unhealthy/PosHabits"){
							p.UP--;
						}else {
							p.UN--;
						}
						return p;
					},
					function () {
						return {count: 0, HP: 0, HN: 0, UP: 0, UN: 0};
					}
				);
				////////////////////////
				
				var scnZoneDim = ndx.dimension(function(d) {return d.scnZone});
				var scnZoneGroup = scnZoneDim.group();
				
				var genderDim = ndx.dimension(function(d) {return d['Gender.Full.Text']});
				var genderGroup = genderDim.group();
					
				var ageDim = ndx.dimension(function(d) {return d.AgeGrp});
				var ageGroup = ageDim.group();
				
				var raceDim = ndx.dimension(function(d) {return d['Race.Full.Text']});
				var raceGroup = raceDim.group();
				
				var occupationDim = ndx.dimension(function(d) {return d.OccupationType});
				var occupationGroup = occupationDim.group();
				
				var educationDim = ndx.dimension(function(d) {return d.StaffEducation});
				var educationGroup = educationDim.group();
				
				var bmiDim = ndx.dimension(function(d) {return d.BMIGrp});
				var bmiGroup = bmiDim.group();
				
				var exerciseDim = ndx.dimension(function(d) {return d.X8Q_LS_Exercise});
				var exerciseGroup = exerciseDim.group();
				
				var sugarDim = ndx.dimension(function(d) {return d.SugarHigh});
				var sugarGroup = sugarDim.group();
				
				var bpDim = ndx.dimension(function(d) {return d.BPHigh});
				var bpGroup = bpDim.group();
				
				var footDim = ndx.dimension(function(d) {return d.X8Q_MH_DiabetesFoot});
				var footGroup = footDim.group();
				
				var livingDim = ndx.dimension(function(d) {return d.X8Q_Living});
				var livingGroup = livingDim.group();
				
				var smokingDim = ndx.dimension(function(d) {return d.X8Q_LS_Smoking});
				var smokingGroup = smokingDim.group();
				
				var backacheDim = ndx.dimension(function(d) {return d.Q_TAXI_Ache});
				var backacheGroup = backacheDim.group();
				
				var lengthacheDim = ndx.dimension(function(d) {return d.Q_TAXI_LengthAche});
				var lengthacheGroup = lengthacheDim.group();
				
				var sleepDim = ndx.dimension(function(d) {return d.Q_TAXI_AveSleep});
				var sleepGroup = sleepDim.group();
				
				var runDim = ndx.dimension(function(d) { return [+d.HealthyNum, +d.HabitsNum]; });
				var runGroup = runDim.group();
				
				var icDim = ndx.dimension(function(d){return d.NRIC;});
				
				//////////////////////////////////////////// GOOGLE MAp
					lngDim = ndx.dimension(function(d){
					return d.GoogleLng;
				});
				latDim = ndx.dimension(function(d){
					return d.GoogleLat;
				});
				/////////////////////////////////////////////
				
				/********************************************************
				*														*
				* 	Step3a: Create Leaflet Visualisations				*
				*														*
				********************************************************/
			/*
				var groupname = "marker-select";
				var facilities = ndx.dimension(function(d) { return d.Geo; });
				var facilitiesGroup = facilities.group().reduceCount();
				
				dc.leafletMarkerChart("#mapLeaflet .map",groupname)
				  .dimension(facilities)
				  .group(facilitiesGroup)
				  .width(600)
					.height(400)
				  .center([1.370677, 103.811994])
				  .zoom(7)
				  .cluster(true);

				dc.renderAll(groupname);  */
				/********************************************************
				*														*
				* 	Step4: Create the Visualisations					*
				*														*
				********************************************************/
				dataTable.width(960).height(800)
					.dimension(icDim)
					.group(function(d) { return "List of all residents based on filters"
					 })
					.size(1000)	// number of rows to return
					.columns([
					  function(d) { return d.NRIC; },
					  function(d) { return d.Zone; },
					  function(d) { return d.Healthy; },
					  function(d) { return d.Habits; }
					])
					.sortBy(function(d){ return d.NRIC; })
					.order(d3.ascending);
				
			///////////////////////////////////////
				moveChart
					//.renderArea(true)
					.width(900)
					.height(200)
					.transitionDuration(1000)
					.margins({top: 0, right: 50, bottom: 20, left: 40})
					.dimension(dateDim1)
					.mouseZoomable(true)
					// Specify a range chart to link the brush extent of the range with the zoom focue of the current chart.
					.rangeChart(dateBarChart)
					.x(d3.time.scale().domain([minDate,maxDate]))
					.xAxis().tickFormat(d3.time.format("%d%b%y"));
				moveChart.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(750).y(0).itemHeight(13).gap(5))
					.brushOn(false)
					.xUnits(function(){return 100;})
					// Add the base layer of the stack with group. The second parameter specifies a series name for use in the
					// legend
					// The `.valueAccessor` will be used for the base layer
					.group(healthStatusGroup, 'HealthyPosHabits')
					.valueAccessor(function (d) {
						return d.value.HP;
					})
					// stack additional layers with `.stack`. The first paramenter is a new group.
					// The second parameter is the series name. The third is a value accessor.
					.stack(healthStatusGroup, 'HealthyNegHabits', function (d) {
						return d.value.HN;
					})
					.stack(healthStatusGroup, 'UnhealthyPosHabits', function (d) {
						return d.value.UP;
					})
					.stack(healthStatusGroup, 'UnhealthyNegHabits', function (d) {
						return d.value.UN;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Unhealthy-NegHabits: ' + d.value.UN + '\n' + 
							'Unhealthy-PosHabits: ' + d.value.UP + '\n' + 
							'Healthy-NegHabits: ' + d.value.HN + '\n' + 
							'Healthy-PosHabits: ' + d.value.HP 
						);
					});
				
				numboxHN1
				  .formatNumber(d3.format(",.0d"))
				  .valueAccessor(function(d)
				  {
					var result=0;
					var total=0;
					for(i=0 ; i<4 ; i++){
						if (healthResultGroup.top(4)[i].key === "Healthy/NegHabits"){
							result += healthResultGroup.top(4)[i].value;
							total += healthResultGroup.top(4)[i].value;
						}else{
							total += healthResultGroup.top(4)[i].value;
						}
					}
					//numDisplayHNHeight = result/total*100;
					//numDisplayHNHeight = 50;
					return result;
				   })
				  .group(healthResultGroup);
				  //.height(numDisplayHNHeight);
				  
				numboxHP1
				  .formatNumber(d3.format(",.0d"))
				  .valueAccessor(function(d)
				  {
					var result=0;
					var total=0;
					for(i=0 ; i<4 ; i++){
						if (healthResultGroup.top(4)[i].key === "Healthy/PosHabits"){
							result += healthResultGroup.top(4)[i].value;
							total += healthResultGroup.top(4)[i].value;
						}else{
							total += healthResultGroup.top(4)[i].value;
						}
					}
					//numDisplayHNHeight = result/total*100;
					//numDisplayHNHeight = 50;
					return result;
				   })
				  .group(healthResultGroup);
				  //.height(numDisplayHNHeight);
				  
				numboxUN1
				  .formatNumber(d3.format(",.0d"))
				  .valueAccessor(function(d)
				  {
					var result=0;
					var total=0;
					for(i=0 ; i<4 ; i++){
						if (healthResultGroup.top(4)[i].key === "Unhealthy/NegHabits"){
							result += healthResultGroup.top(4)[i].value;
							total += healthResultGroup.top(4)[i].value;
						}else{
							total += healthResultGroup.top(4)[i].value;
						}
					}
					//numDisplayHNHeight = result/total*100;
					//numDisplayHNHeight = 50;
					return result;
				   })
				  .group(healthResultGroup);
				  //.height(numDisplayHNHeight);
				  
				numboxUP1
				  .formatNumber(d3.format(",.0d"))
				  .valueAccessor(function(d)
				  {
					var result=0;
					var total=0;
					for(i=0 ; i<4 ; i++){
						if (healthResultGroup.top(4)[i].key === "Unhealthy/PosHabits"){
							result += healthResultGroup.top(4)[i].value;
							total += healthResultGroup.top(4)[i].value;
						}else{
							total += healthResultGroup.top(4)[i].value;
						}
					}
					//numDisplayHNHeight = result/total*100;
					//numDisplayHNHeight = 50;
					return result;
				   })
				  .group(healthResultGroup);
				  //.height(numDisplayHNHeight);     
			/////////////////////////////
				
				dateBarChart
					.width(900).height(50)
					.margins({top: 0, right: 50, bottom: 20, left: 40})
					.dimension(dateDim1)
					.group(dateGroup1)
					.gap(8)
					.x(d3.time.scale().domain([minDate,maxDate]))
				dateBarChart.xAxis().tickFormat(d3.time.format("%b %y"));
				dateBarChart.elasticY(true)
					.elasticX(true)
					.yAxis()
					.ticks(2);
				/*dateBarChart.on("filtered", function(c, f){
						updateGraph();
					});*/
				
				eventRowChart
					.width(250).height(200)
					.dimension(scnZoneDim)
					.group(scnZoneGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				eventRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				genderRowChart
					.width(250).height(200)
					.dimension(genderDim)
					.group(genderGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				genderRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				ageRowChart
					.width(250).height(200)
					.dimension(ageDim)
					.group(ageGroup)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.elasticX(true)
					.xAxis().ticks(4);
				ageRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				raceRowChart
					.width(250).height(200)
					.dimension(raceDim)
					.group(raceGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				raceRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				occupationRowChart
					.width(250).height(200)
					.dimension(occupationDim)
					.group(occupationGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				occupationRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				educationRowChart
					.width(250).height(200)
					.dimension(educationDim)
					.group(educationGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				educationRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				bmiRowChart
					.width(250).height(200)
					.dimension(bmiDim)
					.group(bmiGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				bmiRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				exerciseRowChart
					.width(250).height(200)
					.dimension(exerciseDim)
					.group(exerciseGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				exerciseRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				sugarRowChart
					.width(250).height(200)
					.dimension(sugarDim)
					.group(sugarGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				sugarRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				bpRowChart
					.width(250).height(200)
					.dimension(bpDim)
					.group(bpGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				bpRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				footRowChart
					.width(250).height(200)
					.dimension(footDim)
					.group(footGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				footRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				livingRowChart
					.width(250).height(200)
					.dimension(livingDim)
					.group(livingGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				livingRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				smokingRowChart
					.width(250).height(200)
					.dimension(smokingDim)
					.group(smokingGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				smokingRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				backacheRowChart
					.width(250).height(200)
					.dimension(backacheDim)
					.group(backacheGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				backacheRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				lengthacheRowChart
					.width(250).height(200)
					.dimension(lengthacheDim)
					.group(lengthacheGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				lengthacheRowChart.on("filtered", function(c, f){
						updateGraph()});
						
				sleepRowChart
					.width(250).height(200)
					.dimension(sleepDim)
					.group(sleepGroup)
					.renderLabel(true)
					.elasticX(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				sleepRowChart.on("filtered", function(c, f){
						updateGraph()});
				
				chart
				.width(90 * 2 + 80)
				.height(90 * 2 + 20)
				.margins({top: 0, right: 50, bottom: 20, left: 50})
				.dimension(runDim)
				.group(runGroup)
				.keyAccessor(function(d) 
					{ 
						if (d.key[0] == 1){
							return "Healthy";
						}else{
							return "Unhealthy";
						}
					})
				.valueAccessor(function(d) 
					{ 
						if (d.key[1] == 1){
							return "PosHabits";
						}else{
							return "NegHabits";
						}
					}
				)
				.colorAccessor(function(d) 
					{ 
						return d.key[0]*2 + d.key[1];  
					}
				)
				.colors(d3.scale.ordinal()
					.domain([3,4,5,6])
					.range(["#1f77b4","#d62728","#ff7f0e","#2ca02c","#ffbb78","#252525","#252525","#252525","#252525","#252525","#252525"]))
				.title(function(d) {
					return +d.value + " Resident";})        
				.calculateColorDomain();
				chart.on('renderlet.a',(function(chart){
					chart.selectAll("rect.heat-box")
					.append("text")
					.attr("text",function(d){return "haha";})
				}));
				chart.on("filtered", function(c, f){
						updateGraph()});

				dc.renderAll();
				
				function print_filter(filter){
							var f=eval(filter);
							if (typeof(f.length) != "undefined") {}else{}
							if (typeof(f.top) != "undefined") {f=f.top(Infinity);}else{}
							if (typeof(f.dimension) != "undefined") {f=f.dimension(function(d) { return "";}).top(Infinity);}else{}
							console.log(filter+"("+f.length+") = "+JSON.stringify(f).replace("[","[\n\t").replace(/}\,/g,"},\n\t").replace("]","\n]"));
							return f.length;
						}

				function transform(d){

					// console.log(d.GoogleLat + " " + GoogleLng);

					d = new google.maps.LatLng(d.GoogleLat, d.GoogleLng);
					d = projection.fromLatLngToDivPixel(d);

					return d3.select(this)
						.style("left", (d.x - padding) + "px")
						.style("top", (d.y - padding) + "px");
				}
				
				function updateGraph(){

					d3.selectAll(".marker")
						.style("display", "none");
						//.style("display", "inline");

					d3.selectAll(".marker")
						.data(dateDim1.top(Infinity))
						.style("display", "inline");    
						
					/*eventRowChart.on('renderlet',(function(chart){
						var colors =d3.scale.ordinal().domain(["California", "Colorado", "Delaware", "Mississippi", "Oklahoma", "Ontario"])
							.range(["steelblue", "brown", "red", "green", "yellow", "grey"]);
						chart.selectAll('rect.bar').each(function(d){
							 d3.select(this).attr("style", "fill: " + colors(d.key)); // use key accessor if you are using a custom accessor
						}); 
					}));*/
					
					d3.selectAll("g.row").attr("style", "fill: " + function(d){
						return "red";
					});
				}
				
				$(function(){
					var log = document.getElementById('log');
					gridster = $(".gridster ul").gridster({
					widget_base_dimensions: [55, 55],
					widget_margins: [5, 5],
					resize: {
					enabled: false,
					}
					}).data('gridster');
				});
			});
		</script>
	</body>
</html>