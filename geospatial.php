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

        <!-- JavaScript -->
		<script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/d3.js"></script>
        <script src="js/crossfilter.js"></script>
        <script src="js/dc.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script src="js/classie.js"></script>
		<script src="js/jquery.sortable.js"></script>
        
        <!-- Leaflet JavaScript -->
        <script type="text/javascript" src="js/leaflet.js"></script>
        <script type="text/javascript" src="js/d3-tip.js"></script>

        <script type="text/javascript" src="js/leaflet.markercluster.js"></script>
        <script type="text/javascript" src="js/dc.leaflet.js"></script>
        <!-- Leaflet JavaScript -->
        
		<script>
			$(function() {
				$('.sortable').sortable();
				$('.connected').sortable({
					connectWith: '.connected'
				});
			});
		</script>
		<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('.scroll-pane').jScrollPane();
			});
		</script>
		<script type="text/javascript" src="js/tagsort.js"></script>
		<script>
			$(function(){
				$('div.tags-container').tagSort({selector:'.item', tagWrapper:'span', displaySelector: '.item-tags', displaySeperator: ' / ', inclusive: true, fadeTime:0});
			});
		</script>
		<script type="text/javascript" src="js/moment.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
		<script>
			$(document).ready(function(){
				jQuery('div.tags-container span:contains("Health")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Event Location")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Gender")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Age")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Race")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Education")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Health")').click();
				jQuery('div.tags-container span:contains("Health")').click();
			});
		</script>

        <!-- CSS -->
        <!-- Leaflet CSS -->
        <link type="text/css" href="css/leaflet.css" rel="stylesheet"/>
        <link type="text/css" href="css/MarkerCluster.Default.css" rel="stylesheet"/>
        <link type="text/css" href="css/MarkerCluster.css" rel="stylesheet"/>
        <!-- End Leaflet CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/dc.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet">
		<link href="css/slideMenus.css" rel="stylesheet" />
		<link href="css/jquery.jscrollpane.css" rel="stylesheet" />
		<link href="css/tagsort.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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
			
			#footer {
				position: fixed;
				bottom: 0;
				width: 100%;
				background-color: #f8f7f7;
				padding: 0px 10px 0px 10px;
				border-top-style:solid;
				border-top-color:#cccccc;
				border-top-width:2px;
			}
			
			.gradientBoxesWithOuterShadows { 
				border-style: outset;
				border-radius: 20px;
				-moz-border-radius: 10px;
				box-shadow: 2px 2px 2px #cccccc;
				-moz-box-shadow: 2px 2px 2px #cccccc;
			}

        </style>
    </head>
    
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
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
                        <li>
                            <a href="home.php"><span class="glyphicon glyphicon-th-large" style="padding:0px" aria-hidden="true"></span> Screening Result</a>
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
            </div>
        </nav>
		
		<!-- Top Slide Menu -->
		<nav class="cbp-spmenu cbp-spmenu-horizontal cbp-spmenu-top" id="cbp-spmenu-s3" style="background-color:#f8f7f7;padding: 0px 10px 0px 20px;height:372px;border-bottom-style:solid;border-bottom-color:#cccccc;border-bottom-width:2px">
			<div align="center">
				<table>
					<tr>
						<td>
							<div style="text-align:center">
							<div class="chart-wrapper" id="chart-date-bar" style="background:#f8f7f7">
								<strong>Health Screening Dates</strong>
								<div class="clearfix"></div>
							</div>	
							
							<br />
							<div class="chart-wrapper" id="chart-dateMove-bar" style="background:#f8f7f7">
								<strong>Zoom-in View of Selected Date Range</strong>
								<div class="clearfix"></div>
							</div>
						</div>
						</td>
						<td>
							<div>
								<b><u>Date Inputs</u></b>
								</br>
								
								Start & End Date
								<br />
								<input type="text" id="daterange" onchange="byDateRange(this.value);"/>
								
								</br><br />
								
								<input id="byYearRadio" type="radio" name="period" onclick="toggleInput();"> By Year <input id ="byYear" type="text" size="2" onchange="byYear(this.value);" disabled="true"/>
								<br />
								<input type="radio" name="period" onclick="lastWeek();"/> Last Week
								<br />
								<input type="radio" name="period" onclick="lastMonth();"/> Last Month
								<br />
								<input type="radio" name="period" onclick="lastYear();"/> Last Year
								<br />
								<input type="radio" name="period" onclick="allDates();" checked="checked"/> All Dates
								<br />
								(<span id="DisplayStartDate"></span> - <span id="DisplayEndDate"></span>)
							</div>
						</td>
					</tr>
				</table>
			</div>
				
			<table>
				<tr>
					<td>
						<div class="slideMain">
							<section>
								<button id="showTop" style="height:40px;width:60px">&#9650; Date Selector</button>
							</section>
						</div>
					</td>
					<td style="vertical-align:top;padding:8px 0px 0px 5px">
						<b>Charts:</b>
					</td>
					<td>
						<div class="container" style="width:800px">
							<div class="tags-container row"></div>
							
							<div data-item-tags="All"></div>
						</div>
					</td>
					<td>
						<div class="slideMain">
							<section>
								<button id="showRight" style="height:40px;width:60px">Resident List &#9654;</button>
							</section>
						</div>
					</td>
				</tr>
			</table>
		</nav>
		
		<!-- Right Slide Menu -->
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2" style="width:310px">
			<h3 style="text-align:center;font-size:20px;padding:10px">Resident Information</h3>
			
			<div id="dc-data-count">
				<span class="filter-count" style="font-weight:bold"></span> residents selected out of <span class="total-count" style="font-weight:bold"></span> records
			</div>
			
			<div class="chart-wrapper" style="width:100%;height:78%;overflow:auto;font-size:12px">
				<table class="table table-hover" id="dc-table-graph" style="background:#f8f7f7">
					<thead>
						<tr class="header">
							<th>NRIC</th>
							<th>Zone</th>
							<th>Health Status</th>
							<th>Current Habits</th>
						</tr>
					</thead>
				</table>
			</div>
		</nav>
		
		<section id="connected">				
			<div align="center">
				</br>
			
				<ul class="list-unstyled connected list no2 sortable grid" style="padding:105px 0px 10px 30px">
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="test" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Health and Habits">
							<strong>Health and Habits</strong>
							<a class="reset" href="javascript:chart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-event-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Event Location">
							<strong>Event Location</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-gender-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Gender">
							<strong>Gender</strong>
							<a class="reset" href="javascript:genderRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-age-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Age">
							<strong>Age</strong>
							<a class="reset" href="javascript:ageRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-race-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Race">
							<strong>Race</strong>
							<a class="reset" href="javascript:raceRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-education-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Education">
							<strong>Education</strong>
							<a class="reset" href="javascript:educationRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-bmi-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, BMI">
							<strong>BMI</strong>
							<a class="reset" href="javascript:bmiRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-sugar-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Sugar">
							<strong>Sugar</strong>
							<a class="reset" href="javascript:sugarRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-bp-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, BP">
							<strong>BP</strong>
							<a class="reset" href="javascript:bpRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-occupation-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Occupation">
							<strong>Occupation</strong>
							<a class="reset" href="javascript:occupationRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-exercise-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Exercise">
							<strong>Exercise (hrs/week)</strong>
							<a class="reset" href="javascript:exerciseChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<!--<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-foot-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Diabetes Foot">
							<strong>Diabetes Foot</strong>
							<a class="reset" href="javascript:footChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-living-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Living With">
							<strong>Living With</strong>
							<a class="reset" href="javascript:livingRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>-->
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-smoking-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Smoking">
							<strong>Smoking</strong>
							<a class="reset" href="javascript:smokingRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<!--<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-backache-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Backache">
							<strong>Backache (Taxi)</strong>
							<a class="reset" href="javascript:backacheChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-lengthache-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Length of Backache">
							<strong>Length of Backache (Taxi)</strong>
							<a class="reset" href="javascript:lengthacheRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>  
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-sleep-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Length of Sleep">
							<strong>Length of Sleep (Taxi)</strong>
							<a class="reset" href="javascript:sleepRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>-->
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="demo1" style="background:#f8f7f7;margin:-15px 5px 25px 5px;width:630px" data-item-id="1" data-item-tags="All, Public Health Screening Penetration Rate">
							<strong>Public Health Screening Penetration Rate</strong>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="demo2" style="background:#f8f7f7;margin:-15px 5px 25px 5px;width:630px" data-item-id="1" data-item-tags="All, Public Health Status Ratio">
							<strong>Public Health Status Ratio</strong>
						</div>
					</li>
       
				</ul>
			</div>			
		</section>
        <!--
          <div id="demo1">
            <strong>GeoMap</strong>

          </div>-->
        
		<div id="footer" style="background-color:#f8f7f7">			
			<b>
			Applied Filters
				<span id="reset-all">
					<span onclick="javascript:dc.filterAll();dc.redrawAll();" style="cursor:pointer;font-size:12px;font-weight:bold;color:#1a7bbf">[Reset All]</span>:
				</span> 
			</b>
			<span id="healthFilters"></span>
			<span id="eventFilters"></span>
			<span id="genderFilters"></span>
			<span id="ageFilters"></span>
			<span id="raceFilters"></span>
			<span id="educationFilters"></span>
			<span id="bmiFilters"></span>
			<span id="sugarFilters"></span>
			<span id="bpFilters"></span>
			<span id="occupationFilters"></span>
			<span id="exerciseFilters"></span>
			<span id="diabetesFilters"></span>
			<span id="livingFilters"></span>
			<span id="smokingFilters"></span>
			<span id="backacheFilters"></span>
			<span id="lengthbackacheFilters"></span>
			<span id="lengthsleepFilters"></span>
		</div>
		
		<!-- SlideMenu JavaScript Function -->
		<script type="text/javascript">
			var menuRight = document.getElementById( 'cbp-spmenu-s2' ),
				menuTop = document.getElementById( 'cbp-spmenu-s3' ),
				showRight = document.getElementById( 'showRight' ),
				showTop = document.getElementById( 'showTop' ),
				body = document.body;

			showRight.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuRight, 'cbp-spmenu-open' );
			};
			showTop.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuTop, 'cbp-spmenu-open' );
			};
		</script>

		<script type="text/javascript">
			/********************************************************
			*														*
			* 	Step0: Load and parse data from csv file    		*
			*														*
			********************************************************/

			var dataset;
			var parseDate = d3.time.format("%d/%m/%Y").parse;

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
				 
				});
				dataset = data;
				
				/********************************************************
				*														*
				* 	Step1: Create the dc.js chart objects & link to div	*
				*														*
				********************************************************/
				dateBarChart = dc.barChart("#chart-date-bar"),
				moveChart = dc.barChart("#chart-dateMove-bar"),
				chart = dc.heatMap("#test");
				eventRowChart  = dc.rowChart("#chart-event-row");
				genderRowChart = dc.rowChart("#chart-gender-row");
				ageRowChart = dc.rowChart("#chart-age-row"),
				raceRowChart = dc.rowChart("#chart-race-row"),
				educationRowChart = dc.rowChart("#chart-education-row"),
				bmiRowChart = dc.rowChart("#chart-bmi-row");
				sugarRowChart = dc.rowChart("#chart-sugar-row");
				bpRowChart = dc.rowChart("#chart-bp-row");
				occupationRowChart = dc.rowChart("#chart-occupation-row"),
				exerciseRowChart = dc.rowChart("#chart-exercise-row");
				footRowChart = dc.rowChart("#chart-foot-row");
				livingRowChart = dc.rowChart("#chart-living-row");
				smokingRowChart = dc.rowChart("#chart-smoking-row");
				backacheRowChart = dc.rowChart("#chart-backache-row");
				lengthacheRowChart = dc.rowChart("#chart-lengthache-row");
				sleepRowChart = dc.rowChart("#chart-sleep-row");
                
                marker = dc_leaflet.markerChart("#demo1");
                marker2 = dc_leaflet.markerChart("#demo2");

				var dataCount = dc.dataCount("#dc-data-count");
				var dataTable = dc.dataTable("#dc-table-graph");

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
				
				var dateDim1 = ndx.dimension(function(d) {return d.date});
				var dateGroup1 = dateDim1.group();
				var minDate = dateDim1.bottom(1)[0].date;
				var maxDate = dateDim1.top(1)[0].date;
				
				var minDateMonth = parseInt(minDate.getMonth())+1;
				var maxDateMonth = parseInt(maxDate.getMonth())+1;
				
				document.getElementById('DisplayStartDate').innerHTML = minDate.getDate() + "/" + minDateMonth + "/" + minDate.getFullYear();
				document.getElementById('DisplayEndDate').innerHTML = maxDate.getDate() + "/" + maxDateMonth + "/" + maxDate.getFullYear();
				
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
                
                //Leaflet Dim//
                var facilities = ndx.dimension(function(d) { return d.Geo; });
                //var facilitiesGroup = facilities.group().reduceCount();
                var facilitiesGroup = facilities.group().reduce(
					function (p, v) {
						++p.count;
                        p.ratio = v.age40plus;
                        p.postalCode = v['Addr_Postal.Code'];
						return p;
					},
					function (p, v) {
						--p.count;
                        p.ratio = v.age40plus;
                        p.postalCode = v['Addr_Postal.Code'];
						return p;
					},
					function () {
                        return {count: 0, ratio: 0, postalCode: 0};
					}
				);
                
                var facilities2 = ndx.dimension(function(d) { return d.Geo; });
                var facilities2Group = facilities2.group().reduce(
					function (p, v) {
						++p.count;
                        p.ratio = v.age40plus;
                        p.postalCode = v['Addr_Postal.Code'];
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
                        p.ratio = v.age40plus;
                        p.postalCode = v['Addr_Postal.Code'];
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
						return {count: 0, HP: 0, HN: 0, UP: 0, UN: 0, ratio: 0, postalCode: 0};
					}
				);
				//END Leaflet Dim//
                
				/********************************************************
				*														*
				* 	Step4: Create the Visualisations					*
				*														*
				********************************************************/
				dataCount.dimension(ndx).group(all)
				
				dataTable.width(960).height(800)
					.dimension(icDim)
					.group(function(d) { return "";
					 })
					.size(10000)	// number of rows to return
					.columns([
					  function(d) { return d.NRIC; },
					  function(d) { return d.Zone; },
					  function(d) { return d.Healthy; },
					  function(d) { return d.Habits; }
					])
					.sortBy(function(d){ return d.NRIC; })
					.order(d3.ascending);
				
				///////////////////////////////////////
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
					
				chart
				.width(90 * 2 + 70)
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
				
				chart.on("filtered", function(c, f){
						updateGraph()});
				chart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("healthFilters").innerHTML = chart.filters();
					});
				});
				
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
				eventRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("eventFilters").innerText = eventRowChart.filters();
					});
				});
				
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
				genderRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("genderFilters").innerText = genderRowChart.filters();
					});
				});
				
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
				ageRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("ageFilters").innerText = ageRowChart.filters();
					});
				});
				
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
				raceRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("raceFilters").innerText = raceRowChart.filters();
					});
				});
						
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
				educationRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("educationFilters").innerText = educationRowChart.filters();
					});
				});
				
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
				bmiRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("bmiFilters").innerText = bmiRowChart.filters();
					});
				});
						
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
				sugarRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("sugarFilters").innerText = sugarRowChart.filters();
					});
				});
						
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
				bpRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("bpFilters").innerText = bpRowChart.filters();
					});
				});
						
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
				occupationRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("occupationFilters").innerText = occupationRowChart.filters();
					});
				});
						
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
				exerciseRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("exerciseFilters").innerText = exerciseRowChart.filters();
					});
				});
						
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
				footRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("diabetesFilters").innerText = footRowChart.filters();
					});
				});
						
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
				livingRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("livingFilters").innerText = livingRowChart.filters();
					});
				});
						
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
				smokingRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("smokingFilters").innerText = smokingRowChart.filters();
					});
				});
						
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
				backacheRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("backacheFilters").innerText = backacheRowChart.filters();
					});
				});
						
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
				lengthacheRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("lengthbackacheFilters").innerText = lengthacheRowChart.filters();
					});
				});
						
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
				sleepRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("lengthsleepFilters").innerText = sleepRowChart.filters();
					});
				});
                
                //Leaflet chart//
                marker
                  .dimension(facilities)
                  .group(facilitiesGroup)
                  .valueAccessor(function (p){
                      var numberFormat = d3.format('.2f');
                      return d3.format("%")(p.value.count / p.value.ratio);
                  })
                  .width(600)
                  .height(400)
                  .center([1.342860,103.837361]) 
                  .zoom(11)
                  .cluster(true);  

                marker2
                  .dimension(facilities2)
                  .group(facilities2Group)
                  .valueAccessor(function (p){
                      var numberFormat = d3.format('.2f');
                      return d3.format("%")(p.value.UN / p.value.ratio);
                  })
                  .width(600)
                  .height(400)
                  .center([1.342860,103.837361]) 
                  .zoom(11)
                  .cluster(false);
                //END Leaflet chart//

				dc.renderAll();
				
				function updateGraph(){
					d3.selectAll("g.row").attr("style", "fill: " + function(d){
						return "red";
					});
				}
			});
		</script>
		<script type="text/javascript">
			function byDateRange(dateSelected) {
				var twoDates = dateSelected.split("-");
				
				var startDate = twoDates[0].split("/");
				startDate.reverse();
				
				var endDate = twoDates[1].split("/");
				endDate.reverse();
				
				dateBarChart.filter(dc.filters.RangedFilter(new Date(startDate), new Date(endDate)));
				dc.redrawAll();
			}
			function toggleInput(){
				if(document.getElementById("byYearRadio").checked == true){
					document.getElementById('byYear').disabled = false;
				}
				else{
					document.getElementById('byYear').disabled = true;
				}
			}
			function byYear(yearSelected){
				dateBarChart.filter(dc.filters.RangedFilter(new Date(yearSelected,0,1), new Date(yearSelected,11,30)));
				dc.redrawAll();
			}
			function lastWeek() {
				toggleInput();
				var now = moment(new Date());
				var mon = moment().startOf('week').subtract('days', 6)
				var startMoment = mon.format("YYYY-MM-DD");
				var endMoment = mon.add('days', 6).format("YYYY-MM-DD");
				
				var startDate = startMoment.split("-");
				var endDate = endMoment.split("-");
				
				dateBarChart.filter(dc.filters.RangedFilter(new Date(startDate), new Date(endDate)));
				dc.redrawAll();
			}
			function lastMonth() {
				toggleInput();
				var firstOfMonth = moment().startOf("month").subtract('months', 1);
				var endOfMonth = moment().endOf("month").subtract('months', 1);
				var startMoment = firstOfMonth.format("YYYY-MM-DD");
				var endMoment = endOfMonth.format("YYYY-MM-DD");
				
				var startDate = startMoment.split("-");
				var endDate = endMoment.split("-");
				
				dateBarChart.filter(dc.filters.RangedFilter(new Date(startDate), new Date(endDate)));
				dc.redrawAll();
			}
			function lastYear() {
				toggleInput();
				var firstOfYear = moment().startOf("year").subtract('years', 1);
				var endOfYear = moment().endOf("year").subtract('years', 1);
				var startMoment = firstOfYear.format("YYYY-MM-DD");
				var endMoment = endOfYear.format("YYYY-MM-DD");
				
				var startDate = startMoment.split("-");
				var endDate = endMoment.split("-");
				
				dateBarChart.filter(dc.filters.RangedFilter(new Date(startDate), new Date(endDate)));
				dc.redrawAll();
			}
			function allDates() {
				toggleInput();
				dateBarChart.filterAll();
				dc.redrawAll();
			}
		</script>
		<script type="text/javascript">
			$(function() {
				$("#daterange").daterangepicker({
					"autoApply": true,
					"showDropdowns": true,
					"locale": {
						"format": "DD/MM/YYYY",
						"separator": " - ",
						"daysOfWeek": [
							"Su",
							"Mo",
							"Tu",
							"We",
							"Th",
							"Fr",
							"Sa"
						],
						"monthNames": [
							"Jan",
							"Feb",
							"Mar",
							"Apr",
							"May",
							"Jun",
							"Jul",
							"Aug",
							"Sep",
							"Oct",
							"Nov",
							"Dec"
						],
						"firstDay": 1
					},
					"startDate": "1/9/2013",
					"endDate": "15/1/2015",
					"minDate": "1/9/2013",
					"maxDate": "15/1/2015",
					"opens": "left",
				});
			});
		</script>
	</body>
</html>