<!DOCTYPE html>
<html lang='en'>
	<head>
		<script src="js/json2.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery-2.1.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/underscore-min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/backbone-min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery.tmpl.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/ba-debug.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/ba-tinyPubSub.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery.mousewheel.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery.ui.ipad.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/globalize.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/modernizr.custom.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery.jscrollpane.min.js" type="text/javascript"></script>
		<script src="timeglider/TG_Date.js" type="text/javascript" charset="utf-8"></script>
		<script src="timeglider/TG_Org.js" type="text/javascript" charset="utf-8"></script>
		<script src="timeglider/TG_Timeline.js" type="text/javascript" charset="utf-8"></script> 
		<script src="timeglider/TG_TimelineView.js" type="text/javascript" charset="utf-8"></script>
		<script src="timeglider/TG_Mediator.js" type="text/javascript" charset="utf-8"></script> 
		<script src="timeglider/timeglider.timeline.widget.js" type="text/javascript"></script>
		<script src="timeglider/timeglider.datepicker.js" type="text/javascript"></script>

		<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<!-- UNCOMMENT FOR CHECKBOX-STYLE LEGEND ITEMS
		<link rel="stylesheet" href="css/tg_legend_checkboxes.css" type="text/css" media="screen" charset="utf-8">
		 -->
		<link rel="stylesheet" href="timeglider/Timeglider.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="timeglider/timeglider.datepicker.css" type="text/css" media="screen" charset="utf-8">

		<style type='text/css'>
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
		</style>
	</head>
	<body>
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
							"data_source": "journeysql.php",
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