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

        <!-- Javascript -->
        <script src="js/d3.js"></script>
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
		
		</br></br>

<div class="row">
<table align="center">
<tr>
    <td colspan="3">
    <div id="chart-date-bar" style="background:#f8f7f7">
        <strong>Health Screening Dates (Select a time range to zoom in)</strong>
        <a class="reset" href="javascript:dateBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div></br>
    </div>
    </td>
    <td>
    <div id="chart-event-row" style="background:#f8f7f7">
        <strong>Event Location</strong>
        <a class="reset" href="javascript:eventRowChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div></br>
    </div>
    </td>
</tr>
<tr>
    <td>
        <div id="chart-bmi-bar" style="background:#f8f7f7">
        BMI < 23
        <a class="reset" href="javascript:bmiBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>
    <td>
        <div id="chart-diastolic-bar" style="background:#f8f7f7">
        Diastolic < 90 mm Hg
        <a class="reset" href="javascript:diastolicBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>
    <td>
        <div id="chart-systolic-bar" style="background:#f8f7f7">
        Systolic < 140 mm Hg
        <a class="reset" href="javascript:systolicBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>   
    </td>
    <td>
        <div id="chart-glucose-bar" style="background:#f8f7f7">
        Glucose < 6.0 mmol/L
        <a class="reset" href="javascript:glucoseBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>

</tr>
<tr>
    <td>
        <div id="chart-chol-bar" style="background:#f8f7f7">
        Cholesterol < 5.18 mmol/L
        <a class="reset" href="javascript:cholBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>
    <td>
        <div id="chart-hdl-bar" style="background:#f8f7f7">
        HDL > 1.03 mmol/L
        <a class="reset" href="javascript:hdlBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>
    <td>
        <div id="chart-ldl-bar" style="background:#f8f7f7">
        LDL < 3.37 mmol/L
        <a class="reset" href="javascript:ldlBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>
    <td>
        <div id="chart-trig-bar" style="background:#f8f7f7">
        Triglycerides < 2.26 mmol/L
        <a class="reset" href="javascript:trigBarChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
        </div>
    </td>
</tr>
<tr>
    <td colspan="4">
        <!--<button type="button" onclick="alert('Hello world!')">Click Me!</button>-->
        <button onclick="changeSankeyToStrat()">Analysis Stratification</button>
        <button onclick="changeSankeyToBP()">Analysis BP</button>
        <button onclick="changeSankeyToBS()">Analysis BS</button>
        <button onclick="changeSankeyToBMI()">Analysis BMI</button>
    </td>
</tr>
<tr>
    <td colspan="4">
        <div id="chartH" style="background:#f8f7f7">
        <!--<div id="chartFirst" style="background:#f8f7f7">-->
        <h3>Flow Analysis of Intervention Programme</h3>
    </td>
</tr>
<tr>
</table>    
</div>    
    
<script type="text/javascript">


			/********************************************************
			*														*
			* 	Step0: Load and parse data from csv file    		*
			*														*
			********************************************************/
var dataset;
var parseDate = d3.time.format("%Y-%m-%d").parse;

d3.json("js/trending.php",function(error, data){
    if (error){
        console.log("error in php");
            }
            
    var currentIC = "a0000b";
    var PastOrCurrent = "start";
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
        
        if(currentIC != d.NRIC){
            currentIC = d.NRIC;
            PastOrCurrent = "FollowUp";
            d.PastOrCurrent = "FollowUp";
        }else if(PastOrCurrent != "Baseline"){
            PastOrCurrent = "Baseline";
            d.PastOrCurrent = "Baseline";
        }else{
            PastOrCurrent = "NA";
            d.PastOrCurrent = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.M_Systolic_1st < 140){
            d.Systolic = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.M_Systolic_1st < 140){
            d.Systolic = "FollowUp";
        }else{
            d.Systolic = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.M_Diastolic_1st < 90){
            d.Diastolic = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.M_Diastolic_1st < 90){
            d.Diastolic = "FollowUp";
        }else{
            d.Diastolic = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.L_Chol_f < 5.18){
            d.Chol = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.L_Chol_f < 5.18){
            d.Chol = "FollowUp";
        }else{
            d.Chol = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.L_Trig_f < 2.26){
            d.Trig = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.L_Trig_f < 2.26){
            d.Trig = "FollowUp";
        }else{
            d.Trig = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.L_HDL_f > 1.03){
            d.HDL = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.L_HDL_f > 1.03){
            d.HDL = "FollowUp";
        }else{
            d.HDL = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.L_LDL_f < 3.37){
            d.LDL = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.L_LDL_f < 3.37){
            d.LDL = "FollowUp";
        }else{
            d.LDL = "NA";
        }
        
        if(d.PastOrCurrent === "Baseline" && d.L_Glucose_f < 6.0){
            d.Glucose = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.L_Glucose_f < 6.0){
            d.Glucose = "FollowUp";
        }else{
            d.Glucose = "NA";
        }
  
        if(d.PastOrCurrent === "Baseline" && d.f_BMI < 23){
            d.BMI = "Baseline";
        }else if(d.PastOrCurrent === "FollowUp" && d.f_BMI < 23){
            d.BMI = "FollowUp";
        }else{
            d.BMI = "NA";
        }
        
     });
    
    dataset = data;
    
    dateBarChart = dc.barChart("#chart-date-bar");
    eventRowChart  = dc.rowChart("#chart-event-row");
    
    systolicBarChart = dc.barChart("#chart-systolic-bar");
    diastolicBarChart = dc.barChart("#chart-diastolic-bar");
    cholBarChart = dc.barChart("#chart-chol-bar");
    trigBarChart = dc.barChart("#chart-trig-bar");
    hdlBarChart = dc.barChart("#chart-hdl-bar");
    ldlBarChart = dc.barChart("#chart-ldl-bar");
    glucoseBarChart = dc.barChart("#chart-glucose-bar");
    bmiBarChart = dc.barChart("#chart-bmi-bar");
    
    var ndx = crossfilter(dataset);
    var all = ndx.groupAll();
    
    var dateDim1 = ndx.dimension(function(d) {return d.date});
    var dateGroup1 = dateDim1.group();
    var minDate = dateDim1.bottom(1)[0].date;
    var maxDate = dateDim1.top(1)[0].date;
    
    var scnZoneDim = ndx.dimension(function(d) {return d.scnZone});
    var scnZoneGroup = scnZoneDim.group();
    
    var positionDim = ndx.dimension(function(d) {return d.position});
    var positionGroup = positionDim.group();

    var trendSystolicDim = ndx.dimension(function(d) {return d.Systolic});
    var trendSystolicGroup = trendSystolicDim.group();
    
    var trendDiastolicDim = ndx.dimension(function(d) {return d.Diastolic});
    var trendDiastolicGroup = trendDiastolicDim.group();
    
    var trendCholDim = ndx.dimension(function(d) {return d.Chol});
    var trendCholGroup = trendCholDim.group();
    
    var trendTrigDim = ndx.dimension(function(d) {return d.Trig});
    var trendTrigGroup = trendTrigDim.group();
    
    var trendHDLDim = ndx.dimension(function(d) {return d.HDL});
    var trendHDLGroup = trendHDLDim.group();
    
    var trendLDLDim = ndx.dimension(function(d) {return d.LDL});
    var trendLDLGroup = trendLDLDim.group();
    
    var trendGlucoseDim = ndx.dimension(function(d) {return d.Glucose});
    var trendGlucoseGroup = trendGlucoseDim.group();
    
    var trendBMIDim = ndx.dimension(function(d) {return d.BMI});
    var trendBMIGroup = trendBMIDim.group();
     
    dateBarChart
        .width(800).height(100)
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
    
    eventRowChart
        .width(200).height(100)
        .dimension(scnZoneDim)
        .group(scnZoneGroup)
        .elasticX(true)
        .renderLabel(true)
        .ordinalColors(["#aec7e8"])
        .xAxis().ticks(4);
    //eventRowChart.on("filtered", function(c, f){
	//		updateGraph()});

    systolicBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendSystolicDim)
        .group(trendSystolicGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
                           
    diastolicBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendDiastolicDim)
        .group(trendDiastolicGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
    cholBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendCholDim)
        .group(trendCholGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
    trigBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendTrigDim)
        .group(trendTrigGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
    hdlBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendHDLDim)
        .group(trendHDLGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
    ldlBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendLDLDim)
        .group(trendLDLGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
    glucoseBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendGlucoseDim)
        .group(trendGlucoseGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
    bmiBarChart
        .width(220).height(200)
        .margins({top: 40, right: 40, bottom: 40, left: 40})
        .dimension(trendBMIDim)
        .group(trendBMIGroup)
        .x(d3.scale.ordinal().domain(["Baseline","FollowUp","NA"]))
           // .range(["#FFFFFF","#FF0000","#FF3343"]))
        .xUnits(dc.units.ordinal);
        
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
    </body>
</html>