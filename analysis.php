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

        <!-- CSS -->
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
                <!-- Collect the nav links, forms, and other content for toggling -->
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
								<input type="radio" name="period" onclick="lastWeek();" disabled="true"> Last Week
								<br />
								<input type="radio" name="period" onclick="lastMonth();" disabled="true"/> Last Month
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
								<button id="showTop" style="height:50px;width:90px">&#9650; Show & Hide Date Selector</button>
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
								<button id="showRight" style="height:50px;width:90px">Show & Hide Resident List &#9654;</button>
							</section>
						</div>
					</td>
				</tr>
			</table>
		</nav>
		
		<section id="connected">				
			<div align="center">
				</br></br>
			
				<ul class="list-unstyled connected list no2 sortable grid" style="padding:41px 0px 10px 30px">
					<!--<li>
						<div class="chart-wrapper item col-md-1" id="test" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Health and Habits">
							<strong>Health and Habits</strong>
							<a class="reset" href="javascript:chart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>-->
					<li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-event-row" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Event Location">
							<strong>Event Location</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-bmi-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, BMI">
							<strong>BMI < 23</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-diastolic-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Diastolic">
							<strong>Diastolic < 90 mm Hg</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-systolic-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Systolic">
							<strong>Systolic < 140 mm Hg </strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-glucose-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Glucose">
							<strong>Glucose < 6.0 mmol/L</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-chol-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Cholesterol">
							<strong>Cholesterol < 5.18 mmol/L</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-hdl-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, HDL">
							<strong>HDL > 1.03 mmol/L</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-ldl-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, LDL">
							<strong>LDL < 3.37 mmol/L</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-trig-bar1" style="background:#f8f7f7;margin:5px" data-item-id="1" data-item-tags="All, Triglycerides">
							<strong>Triglycerides < 2.26 mmol/L</strong>
							<a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
					<li>
                        <div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chartH" style="background:#f8f7f7;margin:5px;width:1000px" data-item-id="1" data-item-tags="All, Flow Analysis">
							
							<strong>Flow Analysis of Intervention Programme</strong>
							<br />
							<button onclick="changeSankeyToStrat()">Analysis Stratification</button>
                            <button onclick="changeSankeyToBP()">Analysis BP</button>
                            <button onclick="changeSankeyToBS()">Analysis BS</button>
                            <button onclick="changeSankeyToBMI()">Analysis BMI</button>
						</div>
                    </li>
				</ul>
			</div>
			
			<div id="footer">
				<span id="reset-all">
					<span onclick="javascript:dc.filterAll();dc.redrawAll();" style="cursor:pointer;font-size:14px;font-weight:bold;color:#1a7bbf">Reset All Filters</span>
				</span>
			</div>
		</section>
		
		<br /><br />
		
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
				disableOther( 'showRight' );
			};
			showTop.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuTop, 'cbp-spmenu-open' );
				disableOther( 'showTop' );
			};
			
			window.onload = function() {
				//$('#showTop').click();
			};

			function disableOther( button ) {
				if( button !== 'showRight' ) {
					classie.toggle( showRight, 'disabled' );
				}
				if( button !== 'showTop' ) {
					classie.toggle( showTop, 'disabled' );
				}
			}
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

d3.json(("js/trending.php"),function(error, data){
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
        
        /////////////////Testing for new Trends Analysis
        if (numIndex % 2 == 0){
            d.PastOrCurrent = "FirstVisit";
            numIndex = 1;        
        }else{
            d.PastOrCurrent = "LastVisit";
            numIndex = 0;
        }
        
        if (d.PastOrCurrent == "FirstVisit" && (d.NurseAction == "Y" || d.NurseAction == "Teleconsult")){
            d.visitInterv = "W_InterV_Before";       
        }else if (d.PastOrCurrent == "FirstVisit" && (d.NurseAction !== "Y" && d.NurseAction !== "Teleconsult")){
            d.visitInterv = "WO_InterV_Before";
        }else if (d.PastOrCurrent == "LastVisit" && (d.NurseAction == "Y" || d.NurseAction == "Teleconsult")){
            d.visitInterv = "W_InterV_After";
        }else if (d.PastOrCurrent == "LastVisit" && (d.NurseAction !== "Y" && d.NurseAction !== "Teleconsult")){
            d.visitInterv = "WO_InterV_After";
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
        .width(800).height(190)
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

        if (document.getElementById("chartBP")) {
            //document.getElementById("chartBP").innerHTML = "";
            //document.getElementById("chartBP").setAttribute("id", "chartH");
            reLoadSankey(filteredSankeyJson,"#chartBP");
        }else if (document.getElementById("chartSugar")) {
            //document.getElementById("chartSugar").innerHTML = "";
            //document.getElementById("chartSugar").setAttribute("id", "chartSugar");
            reLoadSankey(filteredSankeyJson,"#chartSugar");
        }else if (document.getElementById("chartBMI")) {
            //document.getElementById("chartBMI").innerHTML = "";
            //document.getElementById("chartBMI").setAttribute("id", "chartBMI");
            reLoadSankey(filteredSankeyJson,"#chartBMI");
        }else if (document.getElementById("chartH")) {
            console.log("I am inside chartH");
            document.getElementById("chartH").innerHTML = "";
            document.getElementById("chartH").setAttribute("id", "chartH");
            //reLoadSankey(filteredSankeyJson,"#chartH");
            //reLoadSankey("js/sankey.php","#chartH");
            //reLoadSankey("js/sankeyFiltered.php?minDate="+minFilteredDate+"&maxDate="+maxFilteredDate,"#chartH");
            reLoadSankey(window.location.href,"#chartH");
        }
        else {
            console.log("why am I here");
        }
       // reLoadSankey("js/sankey.php","#chartH");
                
        //reLoadSankey(phpFile,chart)
        //timeVar = dateBarChart.x(d3.time.scale().domain(d3.extent(data, function(d) { return d.month; })))
        //startDate = f[0];
        //endDate = f[1];
        
        //var tryDim = ndx.dimension(function(d) {return d.date});
        //print_filter(tryDim);
        /*
width = screen.width;
height = screen.height;

if (width > 0 && height >0) {
    window.location.href = "http://localhost/main.php?width=" + width + "&height=" + height;
} else 
    exit();

<?php
echo "<h1>Screen Resolution:</h1>";
echo "Width  : ".$_GET['width']."<br>";
echo "Height : ".$_GET['height']."<br>";
?>

        */
        ////Fri Sep 12 2014 22:48:00 GMT+0800 (Malay Peninsula Standard Time)
        ////2014-03-09
        ////var parseDate = d3.time.format("%Y-%m-%d").parse;
        ////d.date = parseDate(d['Measurement.Att.Date']);
        //console.log("Start Date is " + f[0] + " and end date is " + f[1]);
        //console.log("What is c " + c + " and what is f : " + f);
        //console.log(f[0]);
        //console.log(f[1]);
       // console.log(startDate);
    });
    ///////END Testing code
    
    eventRowChart
        .width(200).height(100)
        .dimension(scnZoneDim)
        .group(scnZoneGroup)
        .elasticX(true)
        .renderLabel(true)
        .ordinalColors(["#aec7e8"])
        .xAxis().ticks(4);
    eventRowChart.on("filtered", function(c, f){
	//		updateGraph()
        //console.log(f);

        console.log(startDate);
    });
    
    dataTable.width(960).height(800)
        .dimension(icDim)
        .group(function(d) { return "List of all residents based on filters"
         })
        .size(1000)	// number of rows to return
        .columns([
          function(d) { return d.NRIC; },
          function(d) { return d.Zone; },//d['Measurement.Att.Date']
          function(d) { return d['Measurement.Att.Date']; },
          function(d) { return d['Gender.Full.Text']; },
          function(d) { return d.Healthy; },
          function(d) { return d.Habits; }
        ])
        .sortBy(function(d){ return d.NRIC; })
        .order(d3.ascending);
                    
    ////////////////////Testing for Trend Analysis
    systolicBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    systolicBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (Systolic > 140)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_Systolic;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
    systolicBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
            
    diastolicBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    diastolicBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (Diastolic > 90)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_Diastolic;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
    diastolicBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
            
    cholBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    cholBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (Chol > 5.18)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
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
    cholBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
            
    trigBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    trigBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (Trig > 2.26)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_Trig;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
    trigBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
            
    hdlBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    hdlBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (HDL < 1.03)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_HDL;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
    hdlBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
            
    ldlBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    ldlBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (LDL > 3.37)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_LDL;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
    ldlBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
            
    glucoseBarChart1
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
    glucoseBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (Glucose > 6.0)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_Glucose;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
    glucoseBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));  
    
    bmiBarChart1
        //.renderArea(true)
        .width(250)
        .height(300)
        .transitionDuration(1000)
        .margins({top: 40, right: 50, bottom: 90, left: 40})
        .dimension(trendBMIDim1)
        ////.mouseZoomable(true)
        // Specify a range chart to link the brush extent of the range with the zoom focue of the current chart.
        ////.rangeChart(dateBarChart)
        ////.x(d3.time.scale().domain([minDate,maxDate]))
        ////.xAxis().tickFormat(d3.time.format("%d%b%y"));
    bmiBarChart1.elasticY(true)
        .renderHorizontalGridLines(true)
        .legend(dc.legend().x(100).y(0).itemHeight(13).gap(5))
        //.brushOn(false)
        //.xUnits(function(){return 100;})
        // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
        // legend
        // The `.valueAccessor` will be used for the base layer
        
        .group(trendBMIGroup1, 'Unhealthy (BMI > 23)')
        .x(d3.scale.ordinal().domain(["W_InterV_Before","W_InterV_After","WO_InterV_Before","WO_InterV_After"]))
        .xUnits(dc.units.ordinal)
        .valueAccessor(function (d) {
            return d.value.NH_BMI;
        })
        // stack additional layers with `.stack`. The first paramenter is a new group.
        // The second parameter is the series name. The third is a value accessor.
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
        //chart.on("renderlet.<renderletKey>", renderletFunction
        bmiBarChart1.on('renderlet.a',(function(chart){
            chart.selectAll("g.x text")
            .attr('dx', '-50')
            .attr('dy', '-7')
            .attr('transform', "rotate(-75)");}));
        //.renderlet(function (chart) {chart.selectAll("g.x text").attr('dx', '-30').attr(
  //'dy', '-7').attr('transform', "rotate(-90)");});
				
    ////////////////////End TESTing
    dc.renderAll();
    
});

    function print_filter(filter){
        var f=eval(filter);
        if (typeof(f.length) != "undefined") {}else{}
        if (typeof(f.top) != "undefined") {f=f.top(Infinity);}else{}
        if (typeof(f.dimension) != "undefined") {f=f.dimension(function(d) { return "";}).top(Infinity);}else{}
        console.log(filter+"("+f.length+") = "+JSON.stringify(f).replace("[","[\n\t").replace(/}\,/g,"},\n\t").replace("]","\n]"));
        return f.length;
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
    
    function changeSankeyToStrat(){
        
        if (document.getElementById("chartBP")) {
            document.getElementById("chartBP").innerHTML = "";
            document.getElementById("chartBP").setAttribute("id", "chartH");
            //reLoadSankey("js/sankey.php","#chartH");
        }else if (document.getElementById("chartSugar")) {
            document.getElementById("chartSugar").innerHTML = "";
            document.getElementById("chartSugar").setAttribute("id", "chartH");
            //reLoadSankey("js/sankey.php","#chartH");
        }else if (document.getElementById("chartBMI")) {
            document.getElementById("chartBMI").innerHTML = "";
            document.getElementById("chartBMI").setAttribute("id", "chartH");
            //reLoadSankey("js/sankey.php","#chartH");
        }
        else {
            console.log("why am I here");
        }
        reLoadSankey("js/sankey.php","#chartH");
    }
    
    function changeSankeyToBP(){
        if (document.getElementById("chartH")) {
            document.getElementById("chartH").innerHTML = "";
            document.getElementById("chartH").setAttribute("id", "chartBP");
        }else if (document.getElementById("chartSugar")) {
            document.getElementById("chartSugar").innerHTML = "";
            document.getElementById("chartSugar").setAttribute("id", "chartBP");
        }else if (document.getElementById("chartBMI")) {
            document.getElementById("chartBMI").innerHTML = "";
            document.getElementById("chartBMI").setAttribute("id", "chartBP");
        }
        else {
            console.log("why am I here");
        }
        reLoadSankey("js/sankeyBP.php","#chartBP");
    }

    function changeSankeyToBS(){
        if (document.getElementById("chartH")) {
            document.getElementById("chartH").innerHTML = "";
            document.getElementById("chartH").setAttribute("id", "chartSugar");
            //reLoadSankey("js/sankeySugar.php","#chartSugar");
        }else if (document.getElementById("chartBP")) {
            document.getElementById("chartBP").innerHTML = "";
            document.getElementById("chartBP").setAttribute("id", "chartSugar");
            //reLoadSankey("js/sankeySugar.php","#chartSugar");
        }else if (document.getElementById("chartBMI")) {
            document.getElementById("chartBMI").innerHTML = "";
            document.getElementById("chartBMI").setAttribute("id", "chartSugar");
            //reLoadSankey("js/sankeySugar.php","#chartSugar");
        }
        else {
            console.log("why am I here");
        }
        reLoadSankey("js/sankeySugar.php","#chartSugar");
    }
    
    function changeSankeyToBMI(){
        if (document.getElementById("chartH")) {
            document.getElementById("chartH").innerHTML = "";
            document.getElementById("chartH").setAttribute("id", "chartBMI");
            //reLoadSankey("js/sankeyBMI.php","#chartBMI");
        }else if (document.getElementById("chartSugar")) {
            document.getElementById("chartSugar").innerHTML = "";
            document.getElementById("chartSugar").setAttribute("id", "chartBMI");
            //reLoadSankey("js/sankeyBMI.php","#chartBMI");
        }else if (document.getElementById("chartBP")) {
            document.getElementById("chartBP").innerHTML = "";
            document.getElementById("chartBP").setAttribute("id", "chartBMI");
            //reLoadSankey("js/sankeyBMI.php","#chartBMI");
        }
        else {
            console.log("why am I here");
        }
        reLoadSankey("js/sankeyBMI.php","#chartBMI");
    }
    
    function convertJSONtoSankeyJSON(plainJSON){
        d3.json(plainJSON, function(error,data){
            //set up graph in same style as original example but empty
            graph = {"nodes" : [], "links" : []};
            
                data.forEach(function (d) {
                  graph.nodes.push({ "name": d.source });
                  graph.nodes.push({ "name": d.target });
                  graph.links.push({ "source": d.source,
                                     "target": d.target,
                                     "value": +d.value });
                });
                
                // return only the distinct / unique nodes
                 graph.nodes = d3.keys(d3.nest()
                   .key(function (d) { return d.name; })
                   .map(graph.nodes));

                 // loop through each link replacing the text with its index from node
                 graph.links.forEach(function (d, i) {
                   graph.links[i].source = graph.nodes.indexOf(graph.links[i].source);
                   graph.links[i].target = graph.nodes.indexOf(graph.links[i].target);
                 });

                 //now loop through each nodes to make nodes an array of objects
                 // rather than an array of strings
                 graph.nodes.forEach(function (d, i) {
                   graph.nodes[i] = { "name": d };
                 });
        });
    }
    
    function reLoadSankey(phpFile,chart){

    ///////////////////////Sankey code
        var margin = {top: 0, right: 50, bottom: 20, left: 40},
            //width = 960 - margin.left - margin.right,
            //height = 500 - margin.top - margin.bottom;
            width = 900,
            height= 500;
            //.width(900).height(50)
              //  .margins({top: 0, right: 50, bottom: 20, left: 40})

        var formatNumber = d3.format(",.0f"),
            format = function(d) { return formatNumber(d) + " resident"; },
            color = d3.scale.category20();
            
        var svg = d3.select(chart).append("svg")
            .attr("width", (width) + margin.left + margin.right)
            .attr("height", (height) + margin.top + margin.bottom)
            //.attr("width", 100)
            //.attr("height", 300)
          .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var sankey = d3.sankey()
            .nodeWidth(15)
            .nodePadding(10)
            .size([width, height]);

        var path = sankey.link();

        ////////////////////////End of Sankey Code
        
        /*
width = screen.width;
height = screen.height;

if (width > 0 && height >0) {
    window.location.href = "http://localhost/main.php?width=" + width + "&height=" + height;
} else 
    exit();
        */
        console.log("I am here");
        console.log(startDate);
        console.log(endDate);
        console.log("I am here2");
        //d3.json("js/sankeyBP.php",function(error, energy){
        d3.json(phpFile,function(error, energy){
        
        var nodeMap = {};
            energy.nodes.forEach(function(x) { nodeMap[x.name] = x; });
            energy.links = energy.links.map(function(x) {
              return {
                source: nodeMap[x.source],
                target: nodeMap[x.target],
                value: x.value
              };
            });
          sankey
              .nodes(energy.nodes)
              .links(energy.links)
              .layout(32);

          var link = svg.append("g").selectAll(".link")
              .data(energy.links)
            .enter().append("path")
              .attr("class", "link")
              .attr("d", path)
              .style("stroke-width", function(d) { return Math.max(1, d.dy); })
              .sort(function(a, b) { return b.dy - a.dy; })
              .on("dblclick", function(d){console.log("Value is " + d.value);});

          link.append("title")
              .text(function(d) { return d.source.name + " → " + d.target.name + "\n" + format(d.value); });

          var node = svg.append("g").selectAll(".node")
              .data(energy.nodes)
            .enter().append("g")
              .attr("class", "node")
              .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
              //d.source.name + " → " + d.target.name
              .on("dblclick", function(d){console.log("Value is " + d.value);})
            .call(d3.behavior.drag()
              .origin(function(d) { return d; })
              .on("dragstart", function() { this.parentNode.appendChild(this); })
              .on("drag", dragmove)
              );

          node.append("rect")
              .attr("height", function(d) { return d.dy; })
              .attr("width", sankey.nodeWidth())
              .style("fill", function(d) { return d.color = color(d.name.replace(/ .*/, "")); })
              .style("stroke", function(d) { return d3.rgb(d.color).darker(2); })
            .append("title")
              .text(function(d) { return d.name + "\n" + format(d.value); });

          node.append("text")
              .attr("x", -6)
              .attr("y", function(d) { return d.dy / 2; })
              .attr("dy", ".35em")
              .attr("text-anchor", "end")
              .attr("transform", null)
              .text(function(d) { return d.name; })
            .filter(function(d) { return d.x < width / 2; })
              .attr("x", 6 + sankey.nodeWidth())
              .attr("text-anchor", "start");

          function dragmove(d) {
            d3.select(this).attr("transform", "translate(" + d.x + "," + (d.y = Math.max(0, Math.min(height - d.dy, d3.event.y))) + ")");
            sankey.relayout();
            link.attr("d", path);
          }
        });
    }
    
</script>
        <script src="js/sankeyCode.js"></script>
        <script src="js/sankeyMain.js"></script>
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