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

        <!-- JavaScript -->
		<script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/d3.js"></script>
        <script src="js/crossfilter.js"></script>
        <script src="js/dc.js"></script>
        <script src="js/bootstrap.min.js"></script>
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
				//jQuery('div.tags-container span:contains("Health")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Event Location")').addClass('tagsort-active');
                jQuery('div.tags-container span:contains("Nurse Action")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Cardio Risk")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("New Cases")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Existing")').addClass('tagsort-active');
				//jQuery('div.tags-container span:contains("Co-Relation")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Health Classification Tree")').addClass('tagsort-active');
				jQuery('div.tags-container span:contains("Health")').click();
				jQuery('div.tags-container span:contains("Health")').click();
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
        <!-- style for Venn -->
        <style>
			.venntooltip {
			  position: absolute;
			  text-align: center;
			  width: 128px;
			  height: 25px;
			  background: #333;
			  color: #ddd;
			  padding: 2px;
			  border: 0px;
			  border-radius: 8px;
			  opacity: 0;
			}
            
            .venntooltip2 {
			  position: absolute;
			  text-align: center;
			  width: 128px;
			  height: 25px;
			  background: #333;
			  color: #ddd;
			  padding: 2px;
			  border: 0px;
			  border-radius: 8px;
			  opacity: 0;
			}
            
            .venntooltip3 {
			  position: absolute;
			  text-align: center;
			  width: 128px;
			  height: 25px;
			  background: #333;
			  color: #ddd;
			  padding: 2px;
			  border: 0px;
			  border-radius: 8px;
			  opacity: 0;
			}
            
        </style>
        <script src="js/venn.js"></script>
        <!-- END style for Venn -->
        <!--Style for HealthTree-->
        <style type="text/css">
			.chart {
			  display: block;
			  margin: auto;
			  margin-top: 20px;
              margin-left: 20px;
              margin-bottom: 20px;
			  font-size: 11px;
			}
			.chart rect {
			  stroke: #eee;
			  fill: #aaa;
			  fill-opacity: .8;
			}

			.chart rect.parent1 {
			  cursor: pointer;
			  fill: steelblue;
			}

			text {
			  pointer-events: none;
			}
		</style>
        <!--END Style for HealthTree-->
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
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-nurse-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Nurse Action">
							<strong>Nurse Action</strong>
							<a class="reset" href="javascript:nurseRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="chart-heart-row" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Cardio Risk">
							<strong>Cardio Risk</strong>
							<a class="reset" href="javascript:heartRowChart.filterAll();dc.redrawAll();" style="display:none;font-size:11px">Reset</a>
							<div class="clearfix"></div>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="venn" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, New Cases">
							<strong>New Cases</strong>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="venn2" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Existing">
							<strong>Existing</strong>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="venn3" style="background:#f8f7f7;margin:-15px 5px 25px 5px" data-item-id="1" data-item-tags="All, Co-Relation">
							<strong>Co-Relation</strong>
						</div>
					</li>
                    <li>
						<div class="chart-wrapper item col-md-1 gradientBoxesWithOuterShadows" id="healthTree" style="background:#f8f7f7;margin:-15px 5px 25px 5px;width:1000px" data-item-id="1" data-item-tags="All, Health Classification Tree">
							<strong>Health Classification Tree</strong>
						</div>
					</li>
				</ul>
			</div>
			
			<div id="footer" style="background-color:#f8f7f7">			
				<b>
				Applied Filters
					<span id="reset-all">
						<span onclick="javascript:dc.filterAll();dc.redrawAll();" style="cursor:pointer;font-size:12px;font-weight:bold;color:#1a7bbf">[Reset All]</span>:
					</span> 
				</b>
				<span id="healthFilters"></span>
				<span id="eventFilters"></span>
				<span id="nurseFilters"></span>
                <span id="heartFilters"></span>
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
            
            /////////////////Venn Variables
            var n_chol = 0;
            var n_bs = 0;
            var n_bp = 0;
            var n_chol_bs = 0;
            var n_chol_bp = 0;
            var n_bs_bp = 0;
            var n_chol_bs_bp = 0;
            
            var e_chol = 0;
            var e_bs = 0;
            var e_bp = 0;
            var e_chol_bs = 0;
            var e_chol_bp = 0;
            var e_bs_bp = 0;
            var e_chol_bs_bp = 0;
            
            var c_chol = 0;
            var c_chol_E_bp = 0;
            var c_chol_E_bs = 0;
            var c_chol_E_bs_bp = 0;
            var c_bs = 0;
            var c_bs_E_bp = 0;
            var c_bs_E_chol = 0;
            var c_bs_E_chol_bp = 0;
            var c_bp = 0;
            var c_bp_E_bs = 0;
            var c_bp_E_chol = 0;
            var c_bp_E_chol_bs = 0;
            var c_chol_bs = 0;
            var c_chol_bp = 0;
            var c_bs_bp = 0;
            var c_chol_bs_bp = 0;
            ////////////////
            
            ///////////////Healthy Tree variables
            var t_healthy = 0;
            var t_unhealthy = 0;
            
            var t_unhealthy_new = 0;
            var t_new_newChol = 0;
            var t_new_newBSBP = 0;
            
            var t_unhealthy_existing = 0;
            var t_existing_goodControl = 0;
            var t_existing_poorControl = 0;
            
            var goodControl_chol = 0;
            var goodControl_newBSBP = 0;
            var goodControl_existingBSBP = 0;
            
            var poorControl_chol = 0;
            var poorControl_newBSBP = 0;
            var poorControl_existingBSBP = 0;
            //////////////

			d3.csv("allDataWGeo1.csv", function(error,data){
            //d3.json(("js/allUniqueData.php"),function(error, data){
                if (error){
                    console.log("error in php");
                }
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
                    
                                      /////////////////////////// Venn
                    if (d.existNewGrp == "Unhealthy, New w/o medicalHist"){
                        if(d.screening == "highBP"){
                            n_bp++;
                        }else if (d.screening == "highSugar"){
                            n_bs++;
                        }else if (d.screening == "highCho"){
                            n_chol++;
                        }else if (d.screening == "highBP&highCho"){
                            n_chol_bp++;
                        }else if (d.screening == "highSugar&highBP"){
                            n_bs_bp++;
                        }else if (d.screening == "highSugar&highCho"){
                            n_chol_bs++;
                        }else if (d.screening == "highSugar&highBP&highCho"){
                            n_chol_bs_bp++;
                        }
                    }
                    
                    if(d.UnhealthyCat == "Unhealthy, Exisiting HighBP"){
                        e_bp++;
                    }else if (d.UnhealthyCat == "Unhealthy, Exisiting HighSugar"){
                        e_bs++;
                    }else if (d.UnhealthyCat == "Unhealthy, Exisiting HighCho"){
                        e_chol++;
                    }else if (d.UnhealthyCat == "Unhealthy, Exisiting HighCho & HighBP"){
                        e_chol_bp++;
                    }else if (d.UnhealthyCat == "Unhealthy, Exisiting HighBP & HighSugar"){
                        e_bs_bp++;
                    }else if (d.UnhealthyCat == "Unhealthy, Exisiting HighCho & HighSugar"){
                        e_chol_bs++;
                    }else if (d.UnhealthyCat == "Unhealthy, Exisiting HighCho, HighBP & HighSugar"){
                        e_chol_bs_bp++;
                    }

                    
                    if (d.existNewGrp == "Unhealthy, New w/o medicalHist"){
                        if(d.screening == "highBP"){
                            c_bp++;
                            if(d.UnhealthyCat == "Unhealthy, Exisiting HighSugar"){
                                c_bp_E_bs++;
                            }else if(d.UnhealthyCat == "Unhealthy, Exisiting HighCho"){
                                c_bp_E_chol++;
                            }else if(d.UnhealthyCat == "Unhealthy, Exisiting HighCho & HighSugar"){
                                c_bp_E_chol_bs++;
                            }
                        }else if (d.screening == "highSugar"){
                            c_bs++;
                            if(d.UnhealthyCat == "Unhealthy, Exisiting HighBP"){
                                c_bs_E_bp++;
                            }else if(d.UnhealthyCat == "Unhealthy, Exisiting HighCho"){
                                c_bs_E_chol++;
                            }else if(d.UnhealthyCat == "Unhealthy, Exisiting HighCho & HighBP"){
                                c_bs_E_chol_bp++;
                            }
                        }else if (d.screening == "highCho"){
                            c_chol++;
                            if(d.UnhealthyCat == "Unhealthy, Exisiting HighBP"){
                                c_chol_E_bp++;
                            }else if(d.UnhealthyCat == "Unhealthy, Exisiting HighSugar"){
                                c_chol_E_bs++;
                            }else if(d.UnhealthyCat == "Unhealthy, Exisiting HighSugar & HighBP"){
                                c_chol_E_bs_bp++;
                            }
                        }else if (d.screening == "highBP&highCho"){
                            c_chol_bp++;
                        }else if (d.screening == "highSugar&highBP"){
                            c_bs_bp++;
                        }else if (d.screening == "highSugar&highCho"){
                            c_chol_bs++;
                        }else if (d.screening == "highSugar&highBP&highCho"){
                            c_chol_bs_bp++;
                        }
                    }
                        //////////////////////Venn End
                        
					////////////////////////////////HealthyTree
					if(d.Healthy == "Healthy"){t_healthy++;}
					if(d.Healthy == "Unhealthy"){t_unhealthy++;}
					
					if(d.New == "Unhealthy, New w/o medicalHist"){t_unhealthy_new++;}
					if(d.UnhealthyCat == "Unhealthy, w/o medicalHist, New HighCho"){t_new_newChol++;}
					if(d.controlTree == "NewNewSugarBP"){t_new_newBSBP++;}
					
					if(d.New == "Unhealthy, Exisiting"){t_unhealthy_existing++;}
					if(d.ControlGrp == "Good Control"){t_existing_goodControl++;}
					if(d.ControlGrp == "Poor Control"){t_existing_poorControl++;}
					
					if(d.controlTree == "goodControlCho"){goodControl_chol++;}
					if(d.controlTree == "goodControlNewSugarBP"){goodControl_newBSBP++;}
					if(d.controlTree == "goodControlExistSugarBP"){goodControl_existingBSBP++;}
					
					if(d.controlTree == "poorControlCho"){poorControl_chol++;}
					if(d.controlTree == "poorControlNewSugarBP"){poorControl_newBSBP++;}
					if(d.controlTree == "poorControlExistSugarBP"){poorControl_existingBSBP++;}
					////////////////////////////////END Healthy Tree
				 
				});
				dataset = data;
                
                ///////////////////////Script for Venn
                var sets = [
                 {"sets": [0], "label": "Cholesterol", "size": n_chol+n_chol_bs+n_chol_bp+n_chol_bs_bp},
                 {"sets": [1], "label": "Blood Sugar", "size": n_bs+n_chol_bs+n_bs_bp+n_chol_bs_bp},
                 {"sets": [2], "label": "Blood Pressure", "size": n_bp+n_chol_bp+n_bs_bp+n_chol_bs_bp},
                 {"sets": [0, 1], "size": n_chol_bs+n_chol_bs_bp},
                 {"sets": [0, 2], "size": n_chol_bp+n_chol_bs_bp},
                 {"sets": [1, 2], "size": n_bs_bp+n_chol_bs_bp},
                 {"sets": [0, 1, 2], "size": n_chol_bs_bp}];
                
                var chart = venn.VennDiagram()
                 .width(220)
                 .height(200);

                var div = d3.select("#venn")
                div.datum(sets).call(chart);
                
                drawVennDiagram("venntooltip");

                //////////////////////////////////////////////////    
                    
                 var sets2 = [
                 {"sets": [0], "label": "Cholesterol", "size": e_chol+e_chol_bs+e_chol_bp+e_chol_bs_bp},
                 {"sets": [1], "label": "Blood Sugar", "size": e_bs+n_chol_bs+e_bs_bp+e_chol_bs_bp},
                 {"sets": [2], "label": "Blood Pressure", "size": e_bp+n_chol_bp+e_bs_bp+n_chol_bs_bp},
                 {"sets": [0, 1], "size": e_chol_bs+e_chol_bs_bp},
                 {"sets": [0, 2], "size": e_chol_bp+e_chol_bs_bp},
                 {"sets": [1, 2], "size": e_bs_bp+e_chol_bs_bp},
                 {"sets": [0, 1, 2], "size": e_chol_bs_bp}];
                
                var chart = venn.VennDiagram()
                 .width(220)
                 .height(200);

                var div = d3.select("#venn2");
                div.datum(sets2).call(chart);
                
                drawVennDiagram("venntooltip2");
                
                 var sets3 = [
                 {"sets": [0], "label": "Cholesterol", "size": c_chol+c_chol_bs+c_chol_bp+c_chol_bs_bp},
                 {"sets": [1], "label": "Blood Sugar", "size": c_bs+c_chol_bs+c_bs_bp+c_chol_bs_bp},
                 {"sets": [2], "label": "Blood Pressure", "size": c_bp+c_chol_bp+c_bs_bp+c_chol_bs_bp},
                 {"sets": [0, 1], "size": c_chol_bs+c_chol_bs_bp},
                 {"sets": [0, 2], "size": c_chol_bp+c_chol_bs_bp},
                 {"sets": [1, 2], "size": c_bs_bp+c_chol_bs_bp},
                 {"sets": [0, 1, 2], "size": c_chol_bs_bp}];
                
                var chart = venn.VennDiagram()
                 .width(220)
                 .height(200);

                var div = d3.select("#venn3");
                div.datum(sets3).call(chart);
                
                drawVennDiagram("venntooltip3");
                /////////////////////////End Script for Venn
                
				/////////////////Health Tree
                var w = 900,
				h = 400,
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

			//d3.json("flare1.json", function(root) {
			/////////////
			var root = 
			{
			 "name": "All Resident",
			 "children": [
			  {
			   "name": "Healthy", "size": t_healthy
			   },
			  {
			   "name": "Unhealthy", 
			   "children": [
				{
				 "name": "New Cases",
				 "children": [
				  {"name": "New Cholesterol", "size": t_new_newChol},
				  {"name": "New BS/BP", "size": t_new_newBSBP}
				  ]
				},
				
				{
				 "name": "Existing",
				 "children": [
				  {
					"name": "Good Control",
						"children": [
						{"name": "Good Control Cholesterol", "size": goodControl_chol},
						{"name": "Good Control BS/BP", "size": goodControl_existingBSBP},
						{"name": "New Cases BS/BP", "size": goodControl_newBSBP}
						]
				  },
				  {
					"name": "Poor Control",
						"children": [
						{"name": "Poor Control Cholesterol", "size": poorControl_chol},
						{"name": "Poor Control BS/BP", "size": poorControl_existingBSBP},
						{"name": "New Cases BS/BP", "size": poorControl_newBSBP }
						]
				  }
				  ]
				}
				]
			  }
			  ]
			};
			////////////
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
					.attr("transform", transform1)
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
						.attr("transform", transform1)
						.style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; });

					d3.event.stopPropagation();
				}

				function transform1(d) {
					return "translate(8," + d.dx * ky / 2 + ")";
				}
                ///////////////////END Tree
				/********************************************************
				*														*
				* 	Step1: Create the dc.js chart objects & link to div	*
				*														*
				********************************************************/
				dateBarChart = dc.barChart("#chart-date-bar"),
				moveChart = dc.barChart("#chart-dateMove-bar"),
				chart = dc.heatMap("#test");
				eventRowChart  = dc.rowChart("#chart-event-row");
                nurseRowChart = dc.rowChart("#chart-nurse-row");
                heartRowChart = dc.rowChart("#chart-heart-row");
				
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
				//console.log(minDate);
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
                
                var nurseDim = ndx.dimension(function(d) {return d.NurseAction});
				var nurseGroup = nurseDim.group();
				
                var heartDim = ndx.dimension(function(d) {return d.fScoreCat});
				var heartGroup = heartDim.group();
                
				var runDim = ndx.dimension(function(d) { return [+d.HealthyNum, +d.HabitsNum]; });
				var runGroup = runDim.group();
				
				var icDim = ndx.dimension(function(d){return d.NRIC;});
				
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
				dateBarChart.on("filtered", function(c, f){
                    console.log("In date BAr");
                    var filteredDim = ndx.dimension(function(d) {return d.NRIC});
                    var allRes = filteredDim.top(Infinity);
                    updateAllGraph(allRes);
                });
                
					
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
                moveChart.on("filtered", function(c, f){
                    console.log("In date BAr");
                    var filteredDim = ndx.dimension(function(d) {return d.NRIC});
                    var allRes = filteredDim.top(Infinity);
                    updateAllGraph(allRes);
                    console.log("out date BAr");
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
					var filteredDim = ndx.dimension(function(d) {return d.NRIC});
                    var allRes = filteredDim.top(Infinity);
                    updateAllGraph(allRes);
                });
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
					var filteredDim = ndx.dimension(function(d) {return d.NRIC});
                    var allRes = filteredDim.top(Infinity);
                    updateAllGraph(allRes);
                });
				eventRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("eventFilters").innerText = eventRowChart.filters();
					});
				});
                
                nurseRowChart
					.width(250).height(200)
					.dimension(nurseDim)
					.group(nurseGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				nurseRowChart.on("filtered", function(c, f){
					var filteredDim = ndx.dimension(function(d) {return d.NRIC});
                    var allRes = filteredDim.top(Infinity);
                    updateAllGraph(allRes);
                });
				nurseRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("nurseFilters").innerText = nurseRowChart.filters();
					});
				});
                
                heartRowChart
					.width(250).height(200)
					.dimension(heartDim)
					.group(heartGroup)
					.elasticX(true)
					.renderLabel(true)
					.ordinalColors(["#aec7e8"])
					.xAxis().ticks(4);
				heartRowChart.on("filtered", function(c, f){
					var filteredDim = ndx.dimension(function(d) {return d.NRIC});
                    var allRes = filteredDim.top(Infinity);
                    updateAllGraph(allRes);
                });
				heartRowChart.renderlet(function(chart) {
					dc.events.trigger(function() {
						document.getElementById("heartFilters").innerText = heartRowChart.filters();
					});
				});

				dc.renderAll();
                
                function updateAllGraph(data){
                    updateHealthClassNewCases(data);
                    updateHealthClassExisting(data);
                    updateHealthClassCombine(data);
                    updateHealthTree(data);
                }
                
                function drawVennDiagram(toolTipChosen){
                    var tooltip = d3.select("body").append("div")
                    .attr("class", toolTipChosen);

                div.selectAll("path")
                    .style("stroke-opacity", 0)
                    .style("stroke", "#fff")
                    .style("stroke-width", 0)

                div.selectAll("g")
                    .on("mouseover", function(d, i) {
                        // sort all the areas relative to the current item
                        venn.sortAreas(div, d);

                        // Display a tooltip with the current size
                        tooltip.transition().duration(400).style("opacity", .9);
                        tooltip.text(d.size + " residents");

                        // highlight the current path
                        var selection = d3.select(this).transition("tooltip").duration(400);
                        selection.select("path")
                            .style("stroke-width", 3)
                            .style("fill-opacity", d.sets.length == 1 ? .4 : .1)
                            .style("stroke-opacity", 1);
                    })

                    .on("mousemove", function() {
                        tooltip.style("left", (d3.event.pageX) + "px")
                               .style("top", (d3.event.pageY - 28) + "px");
                    })

                    .on("mouseout", function(d, i) {
                        tooltip.transition().duration(400).style("opacity", 0);
                        var selection = d3.select(this).transition("tooltip").duration(400);
                        selection.select("path")
                            .style("stroke-width", 0)
                            .style("fill-opacity", d.sets.length == 1 ? .25 : .0)
                            .style("stroke-opacity", 0);
                    });
                }
			     
                function updateHealthClassNewCases(allRes){
                        
                        /////////////////Venn Variables
                        n_chol = 0;
                        n_bs = 0;
                        n_bp = 0;
                        n_chol_bs = 0;
                        n_chol_bp = 0;
                        n_bs_bp = 0;
                        n_chol_bs_bp = 0;
                        ////////////////
                    for(var i = 0 ; i < allRes.length ; i++){    
                        /////////////////////////// Venn
                    if (allRes[i].existNewGrp == "Unhealthy, New w/o medicalHist"){
                        if(allRes[i].screening == "highBP"){
                            n_bp++;
                        }else if (allRes[i].screening == "highSugar"){
                            n_bs++;
                        }else if (allRes[i].screening == "highCho"){
                            n_chol++;
                        }else if (allRes[i].screening == "highBP&highCho"){
                            n_chol_bp++;
                        }else if (allRes[i].screening == "highSugar&highBP"){
                            n_bs_bp++;
                        }else if (allRes[i].screening == "highSugar&highCho"){
                            n_chol_bs++;
                        }else if (allRes[i].screening == "highSugar&highBP&highCho"){
                            n_chol_bs_bp++;
                        }
                    }//end if
                    }//end for
                        //////////////////////Venn End
                        
    
                        ///////////////////////Script for Venn
                    var sets = [
                     {"sets": [0], "label": "Cholesterol", "size": n_chol+n_chol_bs+n_chol_bp+n_chol_bs_bp},
                     {"sets": [1], "label": "Blood Sugar", "size": n_bs+n_chol_bs+n_bs_bp+n_chol_bs_bp},
                     {"sets": [2], "label": "Blood Pressure", "size": n_bp+n_chol_bp+n_bs_bp+n_chol_bs_bp},
                     {"sets": [0, 1], "size": n_chol_bs+n_chol_bs_bp},
                     {"sets": [0, 2], "size": n_chol_bp+n_chol_bs_bp},
                     {"sets": [1, 2], "size": n_bs_bp+n_chol_bs_bp},
                     {"sets": [0, 1, 2], "size": n_chol_bs_bp}];
                    
                    var chart = venn.VennDiagram()
                     .width(220)
                     .height(200);

                    var div = d3.select("#venn")
                    div.datum(sets).call(chart);

                    drawVennDiagram("venntooltip");
                    /////////////////////////End Script for Venn

                }
            
                function updateHealthClassExisting(allRes){

            /////////////////Venn Variables
            var e_chol = 0;
            var e_bs = 0;
            var e_bp = 0;
            var e_chol_bs = 0;
            var e_chol_bp = 0;
            var e_bs_bp = 0;
            var e_chol_bs_bp = 0;
            ////////////////
            
                    for(var i = 0 ; i < allRes.length ; i++){
                    if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighBP"){
                        e_bp++;
                    }else if (allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighSugar"){
                        e_bs++;
                    }else if (allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho"){
                        e_chol++;
                    }else if (allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho & HighBP"){
                        e_chol_bp++;
                    }else if (allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighBP & HighSugar"){
                        e_bs_bp++;
                    }else if (allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho & HighSugar"){
                        e_chol_bs++;
                    }else if (allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho, HighBP & HighSugar"){
                        e_chol_bs_bp++;
                    }
                    }//end for
                    
                 var sets2 = [
                 {"sets": [0], "label": "Cholesterol", "size": e_chol+e_chol_bs+e_chol_bp+e_chol_bs_bp},
                 {"sets": [1], "label": "Blood Sugar", "size": e_bs+n_chol_bs+e_bs_bp+e_chol_bs_bp},
                 {"sets": [2], "label": "Blood Pressure", "size": e_bp+n_chol_bp+e_bs_bp+n_chol_bs_bp},
                 {"sets": [0, 1], "size": e_chol_bs+e_chol_bs_bp},
                 {"sets": [0, 2], "size": e_chol_bp+e_chol_bs_bp},
                 {"sets": [1, 2], "size": e_bs_bp+e_chol_bs_bp},
                 {"sets": [0, 1, 2], "size": e_chol_bs_bp}];
                
                var chart = venn.VennDiagram()
                 .width(220)
                 .height(200);

                var div = d3.select("#venn2")
                div.datum(sets2).call(chart);

                drawVennDiagram("venntooltip2");
                }
                
                function updateHealthClassCombine(allRes){
                    
                    var c_chol = 0;
                    var c_chol_E_bp = 0;
                    var c_chol_E_bs = 0;
                    var c_chol_E_bs_bp = 0;
                    var c_bs = 0;
                    var c_bs_E_bp = 0;
                    var c_bs_E_chol = 0;
                    var c_bs_E_chol_bp = 0;
                    var c_bp = 0;
                    var c_bp_E_bs = 0;
                    var c_bp_E_chol = 0;
                    var c_bp_E_chol_bs = 0;
                    var c_chol_bs = 0;
                    var c_chol_bp = 0;
                    var c_bs_bp = 0;
                    var c_chol_bs_bp = 0;
                    
                    for(var i = 0 ; i < allRes.length ; i++){
                    if (allRes[i].existNewGrp == "Unhealthy, New w/o medicalHist"){
                        if(allRes[i].screening == "highBP"){
                            c_bp++;
                            if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighSugar"){
                                c_bp_E_bs++;
                            }else if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho"){
                                c_bp_E_chol++;
                            }else if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho & HighSugar"){
                                c_bp_E_chol_bs++;
                            }
                        }else if (allRes[i].screening == "highSugar"){
                            c_bs++;
                            if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighBP"){
                                c_bs_E_bp++;
                            }else if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho"){
                                c_bs_E_chol++;
                            }else if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighCho & HighBP"){
                                c_bs_E_chol_bp++;
                            }
                        }else if (allRes[i].screening == "highCho"){
                            c_chol++;
                            if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighBP"){
                                c_chol_E_bp++;
                            }else if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighSugar"){
                                c_chol_E_bs++;
                            }else if(allRes[i].UnhealthyCat == "Unhealthy, Exisiting HighSugar & HighBP"){
                                c_chol_E_bs_bp++;
                            }
                        }else if (allRes[i].screening == "highBP&highCho"){
                            c_chol_bp++;
                        }else if (allRes[i].screening == "highSugar&highBP"){
                            c_bs_bp++;
                        }else if (allRes[i].screening == "highSugar&highCho"){
                            c_chol_bs++;
                        }else if (allRes[i].screening == "highSugar&highBP&highCho"){
                            c_chol_bs_bp++;
                        }
                    }
                    }//end for
                    
                    var sets3 = [
                     {"sets": [0], "label": "Cholesterol", "size": c_chol+c_chol_bs+c_chol_bp+c_chol_bs_bp},
                     {"sets": [1], "label": "Blood Sugar", "size": c_bs+c_chol_bs+c_bs_bp+c_chol_bs_bp},
                     {"sets": [2], "label": "Blood Pressure", "size": c_bp+c_chol_bp+c_bs_bp+c_chol_bs_bp},
                     {"sets": [0, 1], "size": c_chol_bs+c_chol_bs_bp},
                     {"sets": [0, 2], "size": c_chol_bp+c_chol_bs_bp},
                     {"sets": [1, 2], "size": c_bs_bp+c_chol_bs_bp},
                     {"sets": [0, 1, 2], "size": c_chol_bs_bp}];
                    
                    var chart = venn.VennDiagram()
                     .width(220)
                     .height(200);

                    var div = d3.select("#venn3");
                    div.datum(sets3).call(chart);
                                     
                    drawVennDiagram("venntooltip3");
                    
                }
            
                function updateHealthTree(allRes){
                if (document.getElementById("healthTree")) {
                document.getElementById("healthTree").innerHTML = "";}

                ///////////////Healthy Tree variables
                var t_healthy = 0;
                var t_unhealthy = 0;
                
                var t_unhealthy_new = 0;
                var t_new_newChol = 0;
                var t_new_newBSBP = 0;
                
                var t_unhealthy_existing = 0;
                var t_existing_goodControl = 0;
                var t_existing_poorControl = 0;
                
                var goodControl_chol = 0;
                var goodControl_newBSBP = 0;
                var goodControl_existingBSBP = 0;
                
                var poorControl_chol = 0;
                var poorControl_newBSBP = 0;
                var poorControl_existingBSBP = 0;
                //////////////
            
            for(var i = 0 ; i < allRes.length ; i++){
            ////////////////////////////////HealthyTree
            if(allRes[i].Healthy == "Healthy"){t_healthy++;}
            if(allRes[i].Healthy == "Unhealthy"){t_unhealthy++;}
            
            if(allRes[i].New == "Unhealthy, New w/o medicalHist"){t_unhealthy_new++;}
            if(allRes[i].UnhealthyCat == "Unhealthy, w/o medicalHist, New HighCho"){t_new_newChol++;}
            if(allRes[i].controlTree == "NewNewSugarBP"){t_new_newBSBP++;}
            
            if(allRes[i].New == "Unhealthy, Exisiting"){t_unhealthy_existing++;}
            if(allRes[i].ControlGrp == "Good Control"){t_existing_goodControl++;}
            if(allRes[i].ControlGrp == "Poor Control"){t_existing_poorControl++;}
            
            if(allRes[i].controlTree == "goodControlCho"){goodControl_chol++;}
            if(allRes[i].controlTree == "goodControlNewSugarBP"){goodControl_newBSBP++;}
            if(allRes[i].controlTree == "goodControlExistSugarBP"){goodControl_existingBSBP++;}
            
            if(allRes[i].controlTree == "poorControlCho"){poorControl_chol++;}
            if(allRes[i].controlTree == "poorControlNewSugarBP"){poorControl_newBSBP++;}
            if(allRes[i].controlTree == "poorControlExistSugarBP"){poorControl_existingBSBP++;}
            ////////////////////////////////END Healthy Tree
            }//end for
            var w = 900,
				h = 400,
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

			//d3.json("flare1.json", function(root) {
				/////////////
				var root = 
				{
				 "name": "All Resident",
				 "children": [
				  {
				   "name": "Healthy", "size": t_healthy
				   },
				  {
				   "name": "Unhealthy", 
				   "children": [
					{
					 "name": "New Cases",
					 "children": [
					  {"name": "New Cholesterol", "size": t_new_newChol},
					  {"name": "New BS/BP", "size": t_new_newBSBP}
					  ]
					},
					
					{
					 "name": "Existing",
					 "children": [
					  {
						"name": "Good Control",
							"children": [
							{"name": "Good Control Cholesterol", "size": goodControl_chol},
							{"name": "Good Control BS/BP", "size": goodControl_existingBSBP},
							{"name": "New Cases BS/BP", "size": goodControl_newBSBP}
							]
					  },
					  {
						"name": "Poor Control",
							"children": [
							{"name": "Poor Control Cholesterol", "size": poorControl_chol},
							{"name": "Poor Control BS/BP", "size": poorControl_existingBSBP},
							{"name": "New Cases BS/BP", "size": poorControl_newBSBP }
							]
					  }
					  ]
					}
					]
				  }
				  ]
				};
				////////////
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
					.attr("transform", transform1)
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
						.attr("transform", transform1)
						.style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; });

					d3.event.stopPropagation();
				}

				function transform1(d) {
					return "translate(8," + d.dx * ky / 2 + ")";
				}
                
                }
           


           });//last end
		</script>
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