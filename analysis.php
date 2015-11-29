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

        <title>Repeat Analysis</title>

        <!-- JavaScript -->
		<script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/d3.js"></script>
        <script src="js/crossfilter.js"></script>
        <script src="js/dc.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sankey.js"> </script> 
		<script src="js/modernizr.custom.js"></script>
		<script src="js/classie.js"></script>
		<script src="js/jquery.sortable.js"></script>
        
        <script src="js/d3.parsets.js"></script>
        <script src="js/highlight.min.js"></script>
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
		<script type="text/javascript" src="js/daterangepicker.js"></script>
		<script>
			$(document).ready(function(){
				jQuery('div.tags-container span:contains("Event Location")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("BMI")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Diastolic")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Systolic")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Glucose")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Cholesterol")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("HDL")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("LDL")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Triglycerides")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Event Location")').click();
				jQuery('div.tags-container span:contains("Event Location")').click();
			});
		</script>
		<script type="text/javascript" src="js/html2canvas.js"></script>
		<script type="text/javascript" src="js/jquery.base64.js"></script>
		<script type="text/javascript" src="js/tableExport.js"></script>
		<script type="text/javascript" src="js/jspdf/libs/sprintf.js"></script>
		<script type="text/javascript" src="js/jspdf/jspdf.js"></script>
		<script type="text/javascript" src="js/jspdf/libs/base64.js"></script>

        <!-- CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/dc.css" rel="stylesheet"/>
		<link href="css/styles.css" rel="stylesheet">
		<link href="css/slideMenus.css" rel="stylesheet" />
		<link href="css/jquery.jscrollpane.css" rel="stylesheet" />
		<link href="css/tagsort.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
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
			
			a.reset {
				color: #FFF;
				background-color: #1AACBF;
				font-weight:bold;
				border: 1px solid #259FE0;
				border-radius: 25px;
				padding: 2px 8px;
				text-decoration: none;
			}
			
			#resetAll {
				color: #FFF;
				background-color: #1AACBF;
				font-weight:bold;
				border: 1px solid #259FE0;
				border-radius: 25px;
				padding: 2px 8px;
				text-decoration: none;
			}
			
			#showRight {
				margin-top: 250px;
				right: 51px;
				z-index: 999;
				position: relative;
				
				/* Safari */
				-webkit-transform: rotate(90deg);
				/* Firefox */
				-moz-transform: rotate(90deg);
				/* IE */
				-ms-transform: rotate(90deg);
				/* Opera */
				-o-transform: rotate(90deg);
				/* Internet Explorer */
				filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
				
				border-radius: 0px 0px 8px 8px;
				padding: 5px 10px 2px 10px;
				font-size: 12px;
			}
			
			#showRight:hover {
				background-color: #1AACBF;
			}
			
			#showTop:hover {
				background-color: #1AACBF;
			}
			
			#exportExcel {
				color: #FFF;
				background-color: #1A7BBF;
				font-weight: bold;
				font-size: 16px;
				border: 1px solid #259FE0;
				border-radius: 10px;
				padding: 4px 12px;
				margin: 2px 0px 0px 0px;
			}
            
            .y-axis-label{
                font: 12px sans-serif;
            }
            
            .x-axis-label{
                font: 11px sans-serif;
            }
            
            #chart-bmi-bar1 g.stack._0 > rect.bar,
            #chart-diastolic-bar1 g.stack._0 > rect.bar,
            #chart-systolic-bar1 g.stack._0 > rect.bar,
            #chart-glucose-bar1 g.stack._0 > rect.bar,
            #chart-chol-bar1 g.stack._0 > rect.bar,
            #chart-hdl-bar1 g.stack._0 > rect.bar,
            #chart-ldl-bar1 g.stack._0 > rect.bar,
            #chart-trig-bar1 g.stack._0 > rect.bar{
                stroke: none;
                fill: #d62728;
            }
            
            #chart-bmi-bar1 g.stack._1 > rect.bar,
            #chart-diastolic-bar1 g.stack._1 > rect.bar,
            #chart-systolic-bar1 g.stack._1 > rect.bar,
            #chart-glucose-bar1 g.stack._1 > rect.bar,
            #chart-chol-bar1 g.stack._1 > rect.bar,
            #chart-hdl-bar1 g.stack._1 > rect.bar,
            #chart-ldl-bar1 g.stack._1 > rect.bar,
            #chart-trig-bar1 g.stack._1 > rect.bar{
                stroke: none;
                fill: #1f77b4;
            }
            
            #chart-bmi-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-diastolic-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-systolic-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-glucose-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-chol-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-hdl-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-ldl-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1),
            #chart-trig-bar1 g.dc-legend-item:nth-child(1) > rect:nth-child(1){
                stroke: none;
                fill: #d62728;
            }
            
            #chart-bmi-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-diastolic-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-systolic-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-glucose-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-chol-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-hdl-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-ldl-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1),
            #chart-trig-bar1 g.dc-legend-item:nth-child(2) > rect:nth-child(1){
                stroke: none;
                fill: #1f77b4;
            }
            
        </style>
        <!--///////////////-->Style for Scankey
                <style>
            .node rect {
              cursor: move;
              fill-opacity: .9;
              shape-rendering: crispEdges;
            }
            .node text {
              pointer-events: none;
              text-shadow: 0 1px 0 #fff;
            }
            .link {
              fill: none;
              stroke: #000;
              stroke-opacity: .2;
            }
            .link:hover {
              stroke-opacity: .5;
            }
        </style> 
        <!--///////////////-->END Style
        <!--///////////////-->Style for ParallelSet
         <style>
            .dimension { cursor: ns-resize; }
            .category { cursor: ew-resize; }
            .dimension tspan.name { font-size: 1.5em; fill: #333; font-weight: bold; }
            .dimension tspan.sort { fill: #000; cursor: pointer; opacity: 0; }
            .dimension tspan.sort:hover { fill: #333; }
            .dimension:hover tspan.name { fill: #000; }
            .dimension:hover tspan.sort { opacity: 1; }
            .dimension line { stroke: #000; }
            .dimension rect { stroke: none; fill-opacity: 0; }
            .dimension > rect, .category-background { fill: #fff; }
            .dimension > rect { display: none; }
            .category:hover rect { fill-opacity: .3; }
            .dimension:hover > rect { fill-opacity: .3; }
            .ribbon path { stroke-opacity: 0; fill-opacity: .5; }
            .ribbon path.active { fill-opacity: .9; }
            .ribbon-mouse path { fill-opacity: 0; }

            .category-0 { fill: #1f77b4; stroke: #1f77b4; }
            .category-1 { fill: #ff7f0e; stroke: #ff7f0e; }
            .category-2 { fill: #2ca02c; stroke: #2ca02c; }
            .category-3 { fill: #d62728; stroke: #d62728; }
            .category-4 { fill: #9467bd; stroke: #9467bd; }
            .category-5 { fill: #8c564b; stroke: #8c564b; }
            .category-6 { fill: #e377c2; stroke: #e377c2; }
            .category-7 { fill: #7f7f7f; stroke: #7f7f7f; }
            .category-8 { fill: #bcbd22; stroke: #bcbd22; }
            .category-9 { fill: #17becf; stroke: #17becf; }

            .tooltipPS {
              background-color: rgba(242, 242, 242, .6);
              position: absolute;
              padding: 5px;
            }
            </style> 
        <!--///////////////-->END Style
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
                            <a href="analysis.php" style="background-color:#1AACBF;color:#FFF;border-bottom:2px #1AACBF solid"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Repeat Analysis</a>
                        </li>
                        <li>
                            <a href="geospatial.php"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Geospatial Intelligence</a>
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
							<!--<div class="chart-wrapper" id="chart-dateMove-bar" style="background:#f8f7f7">
								<strong>Zoom-in View of Selected Date Range</strong>
								<div class="clearfix"></div>
							</div>-->
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
			
			<br /><br /><br /><br />
				
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
				</tr>
			</table>
		</nav>
		
		<!-- Right Slide Menu -->
		<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2" style="width:310px;border-left:1px solid #47a3da">
			<div class="slideMain">
				<section>
					<button id="showRight">Patient List</button>
				</section>
			</div>
			<h3 style="text-align:center;font-size:20px;margin:-280px 0px 0px 0px;padding:10px">Patient Information</h3>
			
			<div id="dc-data-count">
				<span class="filter-count" style="font-weight:bold"></span> patients selected out of <span class="total-count" style="font-weight:bold"></span> records
			</div>
			
			<div class="chart-wrapper" style="width:100%;height:75%;overflow:auto;font-size:12px">
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
			<div align="center">
				<button id="exportExcel" onclick="javascript:$('#dc-table-graph').tableExport({type:'excel',escape:'false'});"><img src="images/xls.png" width="24px"> Export to Excel (.xls)</button>
			</div>
		</nav>
		
		<section id="connected">				
			<div align="center">			
				<ul class="list-unstyled connected list no2 sortable grid" style="padding:105px 0px 10px 30px">
					<!--<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="test" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Health and Habits">
							<strong>Health and Habits</strong>
							<a class="reset" href="javascript:chart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>-->
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-event-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Event Location">
							<strong>Event Location</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-bmi-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, BMI">
							<strong>BMI < 23</strong>
							<a class="reset" href="javascript:bmiBarChart1.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-diastolic-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Diastolic">
							<strong>Diastolic < 90 mm Hg</strong>
							<a class="reset" href="javascript:diastolicBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-systolic-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Systolic">
							<strong>Systolic < 140 mm Hg </strong>
							<a class="reset" href="javascript:systolicBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-glucose-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Glucose">
							<strong>Glucose < 6.0 mmol/L</strong>
							<a class="reset" href="javascript:glucoseBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-chol-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Cholesterol">
							<strong>Cholesterol < 5.18 mmol/L</strong>
							<a class="reset" href="javascript:cholBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-hdl-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, HDL">
							<strong>HDL > 1.03 mmol/L</strong>
							<a class="reset" href="javascript:hdlBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-ldl-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, LDL">
							<strong>LDL < 3.37 mmol/L</strong>
							<a class="reset" href="javascript:ldlBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-trig-bar1" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Triglycerides">
							<strong>Triglycerides < 2.26 mmol/L</strong>
							<a class="reset" href="javascript:trigBarChart1.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
                        <div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="vis" style="background:#f8f7f7;margin:-15px 5px 25px 5px;width:1000px" data-item-id="1" data-item-tags="All, Flow Analysis">
							<!--<button onclick="changeSankeyToStrat()">Analysis Stratification</button>
                            <button onclick="changeSankeyToBP()">Analysis BP</button>
                            <button onclick="changeSankeyToBS()">Analysis BS</button>
                            <button onclick="changeSankeyToBMI()">Analysis BMI</button>-->
						</div>
                    </li>
				</ul>
			</div>
		</section>
			
		<div id="footer" style="background-color:#f8f7f7">			
			<b style="font-size:16px">
				Applied Filters
				<span id="reset-all">
					<span id="resetAll" onclick="javascript:dc.filterAll();dc.redrawAll();" style="cursor:pointer;font-weight:bold;font-size:14px;color:#FFF">Reset All</span>:
				</span> 
			</b>
			<span style="font-size:16px">
				<span id="eventFilters"></span>
				<span id="bmiFilters"></span>
				<span id="diastolicFilters"></span>
				<span id="systolicFilters"></span>
				<span id="glucoseFilters"></span>
				<span id="cholFilters"></span>
				<span id="hdlFilters"></span>
				<span id="ldlFilters"></span>
				<span id="trigFilters"></span>
			</span>
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
			var parseDate = d3.time.format("%Y-%m-%d").parse;

			///////Testing code for Sync Sankey Chart
			var startDate;
			var endDate;
			var startDate1 = "FY2013";
			var endDate1 = "FY2014";
			///////END Testing code

			//d3.json(("js/trending.php"),function(error, data){
            d3.json(("js/ReturnRepeatResult.php"),function(error, data){
				if (error){
					console.log("error in php");
						}

				var numIndex = 0;
				data.forEach(function(d){
					d.date = parseDate(d['Measurement.Att.Date']);
					
					d.M_Systolic_1st = +d.M_Systolic_1st;
					d.M_Diastolic_1st = +d.M_Diastolic_1st;
					d.L_Chol_f = +d.L_Chol_f;
					d.L_Trig_f = +d.L_Trig_f;
					d.L_HDL_f = +d.L_HDL_f;
					d.L_LDL_f = +d.L_LDL_f;
					d.L_Glucose_f = +d.L_Glucose_f;
					d.f_BMI = +d.f_BMI;
					
					/////////////////New Trends Analysis
					if (numIndex % 2 == 0){
						d.PastOrCurrent = "FirstVisit";
						numIndex = 1;
					}else{
						d.PastOrCurrent = "LastVisit";
						numIndex = 0;
					}
					
					if (d.NurseAction == "Y" || d.NurseAction == "Teleconsult"){
						d.Intervention = "Yes";     
					}else {
						d.Intervention = "No";
					}
					
					if (d.PastOrCurrent == "FirstVisit" && (d.NurseAction == "Y" || d.NurseAction == "Teleconsult")){
						d.visitInterv = "wFirst";       
					}else if (d.PastOrCurrent == "FirstVisit" && (d.NurseAction !== "Y" && d.NurseAction !== "Teleconsult")){
						d.visitInterv = "woFirst";
					}else if (d.PastOrCurrent == "LastVisit" && (d.NurseAction == "Y" || d.NurseAction == "Teleconsult")){
						d.visitInterv = "wLast";
					}else if (d.PastOrCurrent == "LastVisit" && (d.NurseAction !== "Y" && d.NurseAction !== "Teleconsult")){
						d.visitInterv = "woLast";
					}
					
					if (d.Healthy === "Healthy" && d.Habits === "Positive Habits"){
						d.healthResult = "Healthy/PosHabits";
					} else if (d.Healthy === "Healthy" && d.Habits === "Negative Habits"){
						d.healthResult = "Healthy/NegHabits";
					} else if (d.Healthy === "Unhealthy" && d.Habits === "Positive Habits"){
						d.healthResult = "Unhealthy/PosHabits";
					} else {
						d.healthResult = "Unhealthy/NegHabits";
					}
					/////////////////////END TESTING
				});

				dataset = data;
				
				dateBarChart = dc.barChart("#chart-date-bar");
				eventRowChart  = dc.rowChart("#chart-event-row");
				
				////////////////////Testing for Trends Analysis
				systolicBarChart1 = dc.barChart("#chart-systolic-bar1");
				diastolicBarChart1 = dc.barChart("#chart-diastolic-bar1");
				cholBarChart1 = dc.barChart("#chart-chol-bar1");
				trigBarChart1 = dc.barChart("#chart-trig-bar1");
				hdlBarChart1 = dc.barChart("#chart-hdl-bar1");
				ldlBarChart1 = dc.barChart("#chart-ldl-bar1");
				glucoseBarChart1 = dc.barChart("#chart-glucose-bar1");
				bmiBarChart1 = dc.barChart("#chart-bmi-bar1");
				
				var dataCount = dc.dataCount("#dc-data-count");
				var dataTable = dc.dataTable("#dc-table-graph");
				/////////////////End Testing
				
				var ndx = crossfilter(dataset);
				var all = ndx.groupAll();
				
				var dateDim1 = ndx.dimension(function(d) {return d.date});
				var dateGroup1 = dateDim1.group();
				var minDate = dateDim1.bottom(1)[0].date;
				var maxDate = dateDim1.top(1)[0].date;
				
				var minDateMonth = parseInt(minDate.getMonth())+1;
				var maxDateMonth = parseInt(maxDate.getMonth())+1;
				document.getElementById('DisplayStartDate').innerHTML = minDate.getDate() + "/" + minDateMonth + "/" + minDate.getFullYear();
				document.getElementById('DisplayEndDate').innerHTML = maxDate.getDate() + "/" + maxDateMonth + "/" + maxDate.getFullYear();
				
				var scnZoneDim = ndx.dimension(function(d) {return d.scnZone});
				var scnZoneGroup = scnZoneDim.group();
				
				var icDim = ndx.dimension(function(d){return d.NRIC;});
    
				//////////////////Testing for Trending
				var trendBMIDim1 = ndx.dimension(function(d) {return d.visitInterv});
				var trendBMIGroup1 = trendBMIDim1.group().reduce(
					function (p, v){
						++p.count;
						if (v.M_Systolic_1st < 140){
							p.H_Systolic++;
						}else{
							p.NH_Systolic++;
						}
						
						if (v.M_Diastolic_1st < 90){
							p.H_Diastolic++;
						}else{
							p.NH_Diastolic++;
						}
						
						if (v.L_Chol_f < 5.18){ 
							p.H_Chol++;
						}else{
							p.NH_Chol++;
						}
						
						if (v.L_Trig_f < 2.26){
							p.H_Trig++;
						}else{
							p.NH_Trig++;
						}
						
						if (v.L_HDL_f < 1.03){
							p.H_HDL++;
						}else{
							p.NH_HDL++;
						}
						
						if (v.L_LDL_f < 3.37){
							p.H_LDL++;
						}else{
							p.NH_LDL++;
						}
						
						if (v.L_Glucose_f < 6.0){
							p.H_Glucose++;
						}else{
							p.NH_Glucose++;
						}
						
						if (v.f_BMI < 23 ){
							p.H_BMI++;
						}else{
							p.NH_BMI++;
						}
						return p;
					},
					function (p, v){
						--p.count;
						if (v.M_Systolic_1st < 140){
							p.H_Systolic--;
						}else{
							p.NH_Systolic--;
						}
						
						if (v.M_Diastolic_1st < 90){
							p.H_Diastolic--;
						}else{
							p.NH_Diastolic--;
						}
						
						if (v.L_Chol_f < 5.18){ 
							p.H_Chol--;
						}else{
							p.NH_Chol--;
						}
						
						if (v.L_Trig_f < 2.26){
							p.H_Trig--;
						}else{
							p.NH_Trig--;
						}
						
						if (v.L_HDL_f < 1.03){
							p.H_HDL--;
						}else{
							p.NH_HDL--;
						}
						
						if (v.L_LDL_f < 3.37){
							p.H_LDL--;
						}else{
							p.NH_LDL--;
						}
						
						if (v.L_Glucose_f < 6.0){
							p.H_Glucose--;
						}else{
							p.NH_Glucose--;
						}
						
						if (v.f_BMI < 23 ){
							p.H_BMI--;
						}else{
							p.NH_BMI--;
						}
						return p;
					},
					function () {
						return {count:0, H_Systolic:0, NH_Systolic:0, H_Diastolic:0, NH_Diastolic:0, 
						H_Chol:0, NH_Chol:0, H_Trig:0, NH_Trig:0, H_HDL:0, NH_HDL:0, H_LDL:0, NH_LDL:0,
						H_Glucose:0, NH_Glucose:0, H_BMI:0, NH_BMI:0
								
						};
					}
				);
				///////////////////END TEsting 

				dateBarChart
					.width(800).height(196)
					.margins({top: 0, right: 50, bottom: 20, left: 40})
					.dimension(dateDim1)
					.group(dateGroup1)
					//.gap(8)
					.x(d3.time.scale().domain([minDate,maxDate]))
				dateBarChart.xAxis().tickFormat(d3.time.format("%b %y"));
				dateBarChart.elasticY(true)
					.elasticX(true)
					.yAxis()
					.ticks(2);
		
				///////Testing code for Sync Sankey Chart
				dateBarChart.on("filtered", function(c, f){
					console.log("Entered into filtered");
					var filteredDim = ndx.dimension(function(d) {return d.date});
					var minFilteredDate = filteredDim.bottom(1)[0].date;
					var maxFilteredDate = filteredDim.top(1)[0].date;
					
					var yearNameFormat = d3.time.format("%Y");
					var monthNameFormat = d3.time.format("%m");
					var dayNameFormat = d3.time.format("%d");
					
					var sendMinDate = yearNameFormat(minFilteredDate)+"-"+
						monthNameFormat(minFilteredDate)+"-"+dayNameFormat(minFilteredDate);
					var sendMaxDate = yearNameFormat(maxFilteredDate)+"-"+
						monthNameFormat(maxFilteredDate)+"-"+dayNameFormat(maxFilteredDate);
					console.log(sendMinDate + " " + sendMaxDate);
					
					//window.location.href = "http://localhost/PDashboard/js/sankeyFiltered.php?minDate=" + sendMinDate + "&maxDate=" + sendMaxDate;

					var filteredDim = ndx.dimension(function(d) {return d.NRIC});
					var allRes = filteredDim.top(Infinity);
					updateParallelSet(allRes);
				});
				///////END Testing code
    
				eventRowChart
					.width(200).height(441)
					.dimension(scnZoneDim)
					.group(scnZoneGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				eventRowChart.on("filtered", function(c, f){
					var filteredDim = ndx.dimension(function(d) {return d.NRIC});
					var allRes = filteredDim.top(Infinity);
					updateParallelSet(allRes);
				});
				eventRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("eventFilters").innerText = eventRowChart.filters();
					});
				});
				
				dataCount.dimension(ndx).group(all)
    
				dataTable.width(960).height(800)
					.dimension(icDim)
					.group(function(d) { return "List of all residents based on filters"
					 })
					.size(1000)	// number of rows to return
					.columns([
					  function(d) { return d.NRIC; },
					  function(d) { return d.Zone; },//d['Measurement.Att.Date']
					  //function(d) { return d['Measurement.Att.Date']; },
					  //function(d) { return d['Gender.Full.Text']; },
					  function(d) { return d.Healthy; },
					  function(d) { return d.Habits; }
					])
					.sortBy(function(d){ return d.NRIC; })
					.order(d3.ascending);
                    
				////////////////////Testing for Trend Analysis
				systolicBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				systolicBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))
					.group(trendBMIGroup1, 'Unhealthy (Systolic > 140)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_Systolic;
					})
					.stack(trendBMIGroup1, 'Healthy (Systolic < 140)', function (d) {
						return d.value.H_Systolic;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (Systolic < 140): ' + d.value.H_Systolic + '\n' +
							'Unhealthy (Systolic > 140): ' + d.value.NH_Systolic 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				systolicBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				systolicBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				systolicBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("systolicFilters").innerText = systolicBarChart1.filters();
					});
				});
						
				diastolicBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				diastolicBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))       
					.group(trendBMIGroup1, 'Unhealthy (Diastolic > 90)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_Diastolic;
					})
					.stack(trendBMIGroup1, 'Healthy (Diastolic < 90)', function (d) {
						return d.value.H_Diastolic;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (Diastolic < 90): ' + d.value.H_Diastolic + '\n' +
							'Unhealthy ((Diastolic > 90): ' + d.value.NH_Diastolic 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				diastolicBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				diastolicBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				diastolicBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("diastolicFilters").innerText = diastolicBarChart1.filters();
					});
				});
						
				cholBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				cholBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))
					// Add the base layer of the stack with group. The second parameter specifies a series name for use in the
					// legend
					// The `.valueAccessor` will be used for the base layer
					
					.group(trendBMIGroup1, 'Unhealthy (Chol > 5.18)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_Chol;
					})
					// stack additional layers with `.stack`. The first paramenter is a new group.
					// The second parameter is the series name. The third is a value accessor.
					.stack(trendBMIGroup1, 'Healthy (Chol < 5.18)', function (d) {
						return d.value.H_Chol;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (Chol < 5.18): ' + d.value.H_Chol + '\n' +
							'Unhealthy (Chol > 5.18): ' + d.value.NH_Chol 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				cholBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				cholBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				cholBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("cholFilters").innerText = cholBarChart1.filters();
					});
				});
						
				trigBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				trigBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))        
					.group(trendBMIGroup1, 'Unhealthy (Trig > 2.26)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_Trig;
					})
					.stack(trendBMIGroup1, 'Healthy (Trig < 2.26)', function (d) {
						return d.value.H_Trig;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (Trig < 2.26): ' + d.value.H_Trig + '\n' +
							'Unhealthy (Trig > 2.26): ' + d.value.NH_Trig 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				trigBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				trigBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				trigBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("trigFilters").innerText = trigBarChart1.filters();
					});
				});
						
				hdlBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				hdlBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))
					.group(trendBMIGroup1, 'Unhealthy (HDL < 1.03)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_HDL;
					})
					.stack(trendBMIGroup1, 'Healthy (HDL > 1.03)', function (d) {
						return d.value.H_HDL;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (HDL > 1.03): ' + d.value.H_HDL + '\n' +
							'Unhealthy (HDL < 1.03): ' + d.value.NH_HDL 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				hdlBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				hdlBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				hdlBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("hdlFilters").innerText = hdlBarChart1.filters();
					});
				});
						
				ldlBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				ldlBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))
					.group(trendBMIGroup1, 'Unhealthy (LDL > 3.37)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_LDL;
					})
					.stack(trendBMIGroup1, 'Healthy (LDL < 3.37)', function (d) {
						return d.value.H_LDL;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (LDL < 3.37): ' + d.value.H_LDL + '\n' +
							'Unhealthy (LDL > 3.37): ' + d.value.NH_LDL 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				ldlBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				ldlBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				ldlBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("ldlFilters").innerText = ldlBarChart1.filters();
					});
				});
						
				glucoseBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				glucoseBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))
					.group(trendBMIGroup1, 'Unhealthy (Glucose > 6.0)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_Glucose;
					})
					.stack(trendBMIGroup1, 'Healthy (Glucose < 6.0)', function (d) {
						return d.value.H_Glucose;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (Glucose < 6.0): ' + d.value.H_Glucose + '\n' +
							'Unhealthy (Glucose > 6.0): ' + d.value.NH_Glucose 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				glucoseBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				glucoseBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				glucoseBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("glucoseFilters").innerText = glucoseBarChart1.filters();
					});
				});
				
				bmiBarChart1
					.width(250)
					.height(200)
					.transitionDuration(1000)
					.gap(15)
					.margins({top: 40, right: 50, bottom: 30, left: 40})
					.dimension(trendBMIDim1)
				bmiBarChart1.elasticY(true)
					.renderHorizontalGridLines(true)
					.legend(dc.legend().x(80).y(0).itemHeight(13).gap(5))  
					.group(trendBMIGroup1, 'Unhealthy (BMI > 23)')
					.x(d3.scale.ordinal().domain(["wFirst","wLast","woFirst","woLast"]))
					.xUnits(dc.units.ordinal)
					.valueAccessor(function (d) {
						return d.value.NH_BMI;
					})
					.stack(trendBMIGroup1, 'Healthy (BMI < 23)', function (d) {
						return d.value.H_BMI;
					})
					.title(function (d) {
						return (
							'Total: ' + d.value.count + '\n' +
							'Healthy (BMI < 23): ' + d.value.H_BMI + '\n' +
							'Unhealthy (BMI > 23): ' + d.value.NH_BMI 
						);
					})
					.xAxisLabel("W Intervention   W/O Intervention")
					.yAxisLabel("Number of Resident")
				bmiBarChart1.on('renderlet.a',(function(chart){
						chart.selectAll("g.x text")
						.attr('dx', '0')
						.attr('dy', '3')
						.attr('transform', "rotate(0)")
						.append("text")
				}));
				bmiBarChart1.on('renderlet', function(chart) {
					var extra_data = [{x: chart.x().range()[2], y: chart.y()(0)}, 
									  {x: chart.x().range()[2], y: chart.y()(400)}];
					var line = d3.svg.line()
						.x(function(d) { return d.x; })
						.y(function(d) { return d.y; })
						.interpolate('linear');
					var path = chart.select('g.chart-body').selectAll('path.extra').data([extra_data]);
					path.enter().append('path').attr('class', 'extra').attr('stroke', 'green').style("stroke-dasharray", ("3, 3"));
					path.attr('d', line);
				});
				bmiBarChart1.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("bmiFilters").innerText = bmiBarChart1.filters();
					});
				});

			dc.renderAll();  
		}); //END OF d3.json(("js/trending.php"),function(error, data

		function print_filter(filter){
			var f=eval(filter);
			if (typeof(f.length) != "undefined") {}else{}
			if (typeof(f.top) != "undefined") {f=f.top(Infinity);}else{}
			if (typeof(f.dimension) != "undefined") {f=f.dimension(function(d) { return "";}).top(Infinity);}else{}
			console.log(filter+"("+f.length+") = "+JSON.stringify(f).replace("[","[\n\t").replace(/}\,/g,"},\n\t").replace("]","\n]"));
			return f.length;
		}

		var chart = d3.parsets()
			//.dimensions(["scnZone", "Intervention", "Gender.Full.Text", "Race.Full.Text", "NurseAction", "healthResult"]);
			.dimensions(["scnZone", "Intervention", "NurseAction", "healthResult"]);

		var vis = d3.select("#vis").append("svg")
			.attr("width", chart.width())
			.attr("height", chart.height());

		var partition = d3.layout.partition()
			.sort(null)
			.size([chart.width(), chart.height() * 5 / 4])
			.children(function(d) { return d.children ? d3.values(d.children) : null; })
			.value(function(d) { return d.count; });

		var ice = false;

		function curves() {
		  var t = vis.transition().duration(500);
		  if (ice) {
			t.delay(1000);
			icicle();
		  }
		  t.call(chart.tension(this.checked ? .5 : 1));
		}

		//updateParallelSet(dataset);

		//d3.csv("allDataWGeo1.csv", function(error, csv) {
		//d3.json(("js/trending.php"),function(error, csv){
        d3.json(("js/ReturnRepeatResult.php"),function(error, csv){
		  vis.datum(csv).call(chart);

		  window.icicle = function() {
			var newIce = this.checked,
				tension = chart.tension();
			if (newIce === ice) return;
			if (ice = newIce) {
			  var dimensions = [];
			  vis.selectAll("g.dimension")
				 .each(function(d) { dimensions.push(d); });
			  dimensions.sort(function(a, b) { return a.y - b.y; });
			  var root = d3.parsets.tree({children: {}}, csv, dimensions.map(function(d) { return d.name; }), function() { return 1; }),
				  nodes = partition(root),
				  nodesByPath = {};
			  nodes.forEach(function(d) {
				var path = d.data.name,
					p = d;
				while ((p = p.parent) && p.data.name) {
				  path = p.data.name + "\0" + path;
				}
				if (path) nodesByPath[path] = d;
			  });
			  var data = [];
			  vis.on("mousedown.icicle", stopClick, true)
				.select(".ribbon").selectAll("path")
				  .each(function(d) {
					var node = nodesByPath[d.path],
						s = d.source,
						t = d.target;
					s.node.x0 = t.node.x0 = 0;
					s.x0 = t.x0 = node.x;
					s.dx0 = s.dx;
					t.dx0 = t.dx;
					s.dx = t.dx = node.dx;
					data.push(d);
				  });
			  iceTransition(vis.selectAll("path"))
				  .attr("d", function(d) {
					var s = d.source,
						t = d.target;
					return ribbonPath(s, t, tension);
				  })
				  .style("stroke-opacity", 1);
			  iceTransition(vis.selectAll("text.icicle")
				  .data(data)
				.enter().append("text")
				  .attr("class", "icicle")
				  .attr("text-anchor", "middle")
				  .attr("dy", ".3em")
				  .attr("transform", function(d) {
					return "translate(" + [d.source.x0 + d.source.dx / 2, d.source.dimension.y0 + d.target.dimension.y0 >> 1] + ")rotate(90)";
				  })
				  .text(function(d) { return d.source.dx > 15 ? d.node.name : null; })
				  .style("opacity", 1e-6))
				  .style("opacity", 1);
			  iceTransition(vis.selectAll("g.dimension rect, g.category")
				  .style("opacity", 1))
				  .style("opacity", 1e-6)
				  .each("end", function() { d3.select(this).attr("visibility", "hidden"); });
			  iceTransition(vis.selectAll("text.dimension"))
				  .attr("transform", "translate(0,-5)");
			  vis.selectAll("tspan.sort").style("visibility", "hidden");
			} else {
			  vis.on("mousedown.icicle", null)
				.select(".ribbon").selectAll("path")
				  .each(function(d) {
					var s = d.source,
						t = d.target;
					s.node.x0 = s.node.x;
					s.x0 = s.x;
					s.dx = s.dx0;
					t.node.x0 = t.node.x;
					t.x0 = t.x;
					t.dx = t.dx0;
				  });
			  iceTransition(vis.selectAll("path"))
				  .attr("d", function(d) {
					var s = d.source,
						t = d.target;
					return ribbonPath(s, t, tension);
				  })
				  .style("stroke-opacity", null);
			  iceTransition(vis.selectAll("text.icicle"))
				  .style("opacity", 1e-6).remove();
			  iceTransition(vis.selectAll("g.dimension rect, g.category")
				  .attr("visibility", null)
				  .style("opacity", 1e-6))
				  .style("opacity", 1);
			  iceTransition(vis.selectAll("text.dimension"))
				  .attr("transform", "translate(0,-25)");
			  vis.selectAll("tspan.sort").style("visibility", null);
			}
		  };
		  d3.select("#icicle")
			  .on("change", icicle)
			  .each(icicle);
		});

		////////////////// FUNCTION for updateParallelSet
		function updateParallelSet(data){
		  vis.datum(data).call(chart);

		  window.icicle = function() {
			var newIce = this.checked,
				tension = chart.tension();
			if (newIce === ice) return;
			if (ice = newIce) {
			  var dimensions = [];
			  vis.selectAll("g.dimension")
				 .each(function(d) { dimensions.push(d); });
			  dimensions.sort(function(a, b) { return a.y - b.y; });
			  var root = d3.parsets.tree({children: {}}, data, dimensions.map(function(d) { return d.name; }), function() { return 1; }),
				  nodes = partition(root),
				  nodesByPath = {};
			  nodes.forEach(function(d) {
				var path = d.data.name,
					p = d;
				while ((p = p.parent) && p.data.name) {
				  path = p.data.name + "\0" + path;
				}
				if (path) nodesByPath[path] = d;
			  });
			  var data = [];
			  vis.on("mousedown.icicle", stopClick, true)
				.select(".ribbon").selectAll("path")
				  .each(function(d) {
					var node = nodesByPath[d.path],
						s = d.source,
						t = d.target;
					s.node.x0 = t.node.x0 = 0;
					s.x0 = t.x0 = node.x;
					s.dx0 = s.dx;
					t.dx0 = t.dx;
					s.dx = t.dx = node.dx;
					data.push(d);
				  });
			  iceTransition(vis.selectAll("path"))
				  .attr("d", function(d) {
					var s = d.source,
						t = d.target;
					return ribbonPath(s, t, tension);
				  })
				  .style("stroke-opacity", 1);
			  iceTransition(vis.selectAll("text.icicle")
				  .data(data)
				.enter().append("text")
				  .attr("class", "icicle")
				  .attr("text-anchor", "middle")
				  .attr("dy", ".3em")
				  .attr("transform", function(d) {
					return "translate(" + [d.source.x0 + d.source.dx / 2, d.source.dimension.y0 + d.target.dimension.y0 >> 1] + ")rotate(90)";
				  })
				  .text(function(d) { return d.source.dx > 15 ? d.node.name : null; })
				  .style("opacity", 1e-6))
				  .style("opacity", 1);
			  iceTransition(vis.selectAll("g.dimension rect, g.category")
				  .style("opacity", 1))
				  .style("opacity", 1e-6)
				  .each("end", function() { d3.select(this).attr("visibility", "hidden"); });
			  iceTransition(vis.selectAll("text.dimension"))
				  .attr("transform", "translate(0,-5)");
			  vis.selectAll("tspan.sort").style("visibility", "hidden");
			} else {
			  vis.on("mousedown.icicle", null)
				.select(".ribbon").selectAll("path")
				  .each(function(d) {
					var s = d.source,
						t = d.target;
					s.node.x0 = s.node.x;
					s.x0 = s.x;
					s.dx = s.dx0;
					t.node.x0 = t.node.x;
					t.x0 = t.x;
					t.dx = t.dx0;
				  });
			  iceTransition(vis.selectAll("path"))
				  .attr("d", function(d) {
					var s = d.source,
						t = d.target;
					return ribbonPath(s, t, tension);
				  })
				  .style("stroke-opacity", null);
			  iceTransition(vis.selectAll("text.icicle"))
				  .style("opacity", 1e-6).remove();
			  iceTransition(vis.selectAll("g.dimension rect, g.category")
				  .attr("visibility", null)
				  .style("opacity", 1e-6))
				  .style("opacity", 1);
			  iceTransition(vis.selectAll("text.dimension"))
				  .attr("transform", "translate(0,-25)");
			  vis.selectAll("tspan.sort").style("visibility", null);
			}
		  };
		  d3.select("#icicle")
			  .on("change", icicle)
			  .each(icicle);
		}//////////////////////END OF updateParallelSet(data)


		function iceTransition(g) {
		  return g.transition().duration(1000);
		}

		function ribbonPath(s, t, tension) {
		  var sx = s.node.x0 + s.x0,
			  tx = t.node.x0 + t.x0,
			  sy = s.dimension.y0,
			  ty = t.dimension.y0;
		  return (tension === 1 ? [
			  "M", [sx, sy],
			  "L", [tx, ty],
			  "h", t.dx,
			  "L", [sx + s.dx, sy],
			  "Z"]
		   : ["M", [sx, sy],
			  "C", [sx, m0 = tension * sy + (1 - tension) * ty], " ",
				   [tx, m1 = tension * ty + (1 - tension) * sy], " ", [tx, ty],
			  "h", t.dx,
			  "C", [tx + t.dx, m1], " ", [sx + s.dx, m0], " ", [sx + s.dx, sy],
			  "Z"]).join("");
		}

		function stopClick() { d3.event.stopPropagation(); }

		// Given a text function and width function, truncates the text if necessary to
		// fit within the given width.
		function truncateText(text, width) {
		  return function(d, i) {
			var t = this.textContent = text(d, i),
				w = width(d, i);
			if (this.getComputedTextLength() < w) return t;
			this.textContent = "…" + t;
			var lo = 0,
				hi = t.length + 1,
				x;
			while (lo < hi) {
			  var mid = lo + hi >> 1;
			  if ((x = this.getSubStringLength(0, mid)) < w) lo = mid + 1;
			  else hi = mid;
			}
			return lo > 1 ? t.substr(0, lo - 2) + "…" : "";
		  };
		}

		d3.select("#file").on("change", function() {
		  var file = this.files[0],
			  reader = new FileReader;
		  reader.onloadend = function() {
			var csv = d3.csv.parse(reader.result);
			vis.datum(csv).call(chart
				.value(csv[0].hasOwnProperty("Number") ? function(d) { return +d.Number; } : 1)
				.dimensions(function(d) { return d3.keys(d[0]).filter(function(d) { return d !== "Number"; }).sort(); }));
		  };
		  reader.readAsText(file);
		});
		</script>
       <!-- <script src="js/sankeyCode.js"></script>
        <script src="js/sankeyMain.js"></script>-->
		<script>
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