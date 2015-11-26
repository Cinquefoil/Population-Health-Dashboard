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

		<!-- CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
		<!-- UNCOMMENT FOR CHECKBOX-STYLE LEGEND ITEMS
		<link href="css/tg_legend_checkboxes.css" rel="stylesheet">
		 -->
		<link href="timeglider/Timeglider.css" rel="stylesheet">
		<link href="timeglider/timeglider.datepicker.css" rel="stylesheet">
    
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
		
		</br></br>

		<div id='p1'></div>

		<script type='text/javascript'>
			var ico = window.ico;
			
			window.pizzaShack = {
				clicker: function(tg_event) {
					alert("you clicked on " + tg_event.title);
				}
			};
		  
			var tg1 = window.tg1 = "";
		   
			$(function () { 
				var tg_instance = {};

				tg1 = $("#p1").timeline({
							
							/*
							// custom hover & click callbacks
							// returning false prevents default
			
							eventHover: function($ev, ev) {
								debug.log("ev hover, no follow:", ev);
								return false;
							},
							
							eventClick: function($ev, ev) {
								debug.log("eventClick, no follow:", ev);
								return false;
							},
							*/
			
							"min_zoom":1, 
							"max_zoom":50, 
							"timezone":"-06:00",
							"icon_folder":"timeglider/icons/",
							//"data_source": "json/new_history.json",
							"data_source": "patientjourneysql.php",
							"show_footer":true,
							"display_zoom_level":true,
							"mousewheel":"zoom", // zoom | pan | none
							"constrain_to_data":true,
							"image_lane_height":100,
							"legend":{type:"default"}, // default | checkboxes
							"loaded":function () { 
								// loaded callback function
							 }
			
					}).resizable({
						stop:function(){ 
							// $(this).data("timeline").resize();
						}
					});
					
				tg_instance = tg1.data("timeline");
			
				$(".goto").click(function() {
					var d = $(this).attr("date");
					var z = $(this).attr("zoom");
					tg_instance.goTo(d,z);
				});
				
				$(".zoom").click(function() {
					var z = Number($(this).attr("z"));
					tg_instance.zoom(z);
				});
				
				tg_instance.panButton($(".pan-left"), "left");
				tg_instance.panButton($(".pan-right"), "right");
			
				$("#getScope").click(function() {
					var so = tg_instance.getScope();	
					var ml = "RETURNS: <br><br>container (jquery dom object): " + so.container.toString()
					+ "<br>focusDateSec (tg sec):" + so.focusDateSec
					+ "<br>focusMS (js timestamp): " + so.focusMS
					+ "<br>leftMS (js timestamp): " + so.leftMS
					+ "<br>left_sec (tg sec): " + so.left_sec
					+ "<br>rightMS (js timestamp): " + so.rightMS
					+ "<br>right_sec (tg sec): " + so.right_sec
					+ "<br>spp (seconds per pixel): " + so.spp
					+ "<br>timelineBounds (object, left- & right-most in tg sec): " + JSON.stringify(so.timelineBounds)
					+ "<br>timelines (array of ids): " + JSON.stringify(so.timelines);
					
					var d = new Date(so.focusMS)
					
					ml += "<br><br>Date using focusMS:" + d.toString('yyyy-MM-dd');
					
					$(".scope-view").html(ml);
				});
			
				$("#loadData").click(function() {
					var src = $("#loadDataSrc").val();
					var cb_fn = function(args, timeline) {
						// called after parsing data, after load
						debug.log("args", args, "timeline", timeline[0].id);
					};
					
					var cb_args = {}; // {display:true};
					tg_instance.getMediator().emptyData();
					tg_instance.loadTimeline(src, function(){debug.log("cb!");}, true);
					
					$("#reloadDataDiv").hide();
				});
				
				$("#reloadTimeline").click(function() {
					tg_instance.reloadTimeline("js_history", "json/new_history.json");
				});
				
				$("#refresh").click(function() {
					debug.log("timeline refreshed!");
					tg_instance.refresh();
				});

				$("#scrolldown").bind("click", function() {
					$(".timeglider-timeline-event").animate({top:"+=100"})
				})
				
				$("#scrollup").bind("click", function() {
					$(".timeglider-timeline-event").animate({top:"-=100"})
				})

				timeglider.eventActions = {
					nagavigateTo:function(obj) {
						// event object must have a "navigateTo"
						// element with zoom, then ISO date delimited
						// with a pipe | 
						// one can use
						var nav = obj.navigateTo;
						tg_instance.goTo(nav.focus_date,nav.zoom_level);
						
						setTimeout(function () {
							$el = $(".timeglider-timeline-event#" + obj.id);
							$el.find(".timeglider-event-spanner").css({"border":"1px solid green"}); // 
						}, 50);
					}
				}

				$("#adjustNow").click(function() {
					tg_instance.adjustNowEvents();
				});	
				
				$("#addEvent").click(function() {
				
					var rando = Math.floor((Math.random()*1000)+1); 
					var impo = Math.floor((Math.random()*50)+20); 
					
					var obj = {
						id:"new_" + rando,
						title:"New Event!",
						startdate:"today",
						importance:impo,
						icon:"star_red.png",
						timelines:["js_history"]
					}
					
					tg_instance.addEvent(obj, true);
				});	

				
				$("#updateEvent").click(function() {
					
					var updatedEventModel = {
						id:"deathofflash",
						title: "Flash struggles to survive in the age of HTML5."
					}
					tg_instance.updateEvent(updatedEventModel);
				});
			}); // end document-ready
		</script>
	</body>
</html>