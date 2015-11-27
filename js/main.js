$(document).ready(function() {
	$('#left-menu').sidr({
		name: 'sidr-left',
		side: 'left',
		body: '' // By default
	});
	$('#right-menu').sidr({
		name: 'sidr-right',
		side: 'right',
		body: ''
	});
	resizeDiv();
});


window.onresize = function(event) {
  resizeDiv();
}

function resizeDiv() {
  vph = $(window).height() - 90; 
  $('.diag-table #lists').css({'height': vph + 'px'});
  $('.patient-table #lists').css({'height': vph + 'px'});
}

function hideNav() {
	if(d3.select(".datebar").style("display") != "none") {
		$('.datebar').hide();
		d3.select(".filterbar").classed("btm-shadow", true);
		$('body').css({'padding-top': '87px'});
		d3.select(".showHide a.flap").text("Show v");
		$('.showHide').css({'top': '69px'});
	} else {
		$('.datebar').show();
		d3.select(".filterbar").classed("btm-shadow", false);
		$('body').css({'padding-top': '232px'});
		d3.select(".showHide a.flap").text("Hide x");
		$('.showHide').css({'top': '214px'});
	}
}

function shiftLeft() {
	if(d3.select("#sidr-left").style("display") == "none") {
		$('.viewDiag').animate({left:'242px'}, 180);
		$('.viewPatients').animate({right:'-44px'}, 180);
		$('body').css({'overflow-y': 'hidden'});
	} else {
		$('.viewDiag').animate({left:'-50px'}, 180);
		$('body').css({'overflow-y': 'auto'});
	}
}

function shiftRight() {
	if(d3.select("#sidr-right").style("display") == "none") {
		$('.viewPatients').animate({right:'276px'}, 180);
		$('.viewDiag').animate({left:'-50px'}, 180);
		$('body').css({'overflow-y': 'hidden'});
	} else {
		$('.viewPatients').animate({right:'-44px'}, 180);
		$('body').css({'overflow-y': 'auto'});
	}
}


var margin = {top: 5, right: 20, bottom: 30, left: 30},
	barPadding = 0.35,
 	dHeight,
 	rangeMax = function(d) {return d - margin.left - margin.right};

var formatValue = d3.format(".2f"),
	formatAge = d3.format(" "),
	formatDateTime = d3.time.format("%m/%d/%Y %I:%M:%S %p"),
	formatDate = d3.time.format("%m/%d/%Y"),
	formatTime = d3.time.format("%I:%M:%S %p"),
	getTime = d3.time.format("%I:%M %p"),
	getDay = d3.time.format("%w"),
	getDate = d3.time.format("%e %b %Y (%a)"),
	getDateTime = d3.time.format("%d/%m/%Y(%a) %I:%M %p")
	getMonth = d3.time.format("%b");

var dayOrder = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

var deptFilter,
	demoFilter = [],
	patientFilterDiag = {},
	patientFilterDept = {},
	diagFilter = [];

var mapLoader,
	totalPatients,
	totalEpisodes,
	episodeCounts = {},
	patientCounts = {};

function render(method) {
	d3.select(this).call(method);
}

function renderNetwork() {
	parseData(gender.top(Infinity));
	createDefs(svg.append('svg:defs'));
    generateLinks(modData);
    drawNetwork(modLink);
    networkSummary();
    drawLegend();
}

function renderMap() {
	generate_hexgrid();
	draw_choro_grid();
}

function renderAll() {
	d3.select("#patients #total").text(!deptFilter ? totalPatients : patientCounts[deptFilter]);
	d3.select("#patients #active").text(record.groupAll().reduce(reduceAdd,reduceRemove,reduceInitial).value().count);
	d3.selectAll("#episodes #total").each(function() { d3.select(this).text(!deptFilter ? totalEpisodes : episodeCounts[deptFilter]) });
	d3.selectAll("#episodes #active").each(function() { d3.select(this).text(record.groupAll().value()) });
	yearChart.each(render);
	dateChart.each(render);
	admitChart.each(render);
	discChart.each(render);
	demoChart.each(render);
	statChart.each(render);
	dList.each(render);
	pList.each(render);
	
	if(d3.select(".filterbar")[0][0].clientHeight > 40) {
		var shiftBy = d3.select(".filterbar")[0][0].clientHeight - 39
		$('body').css({'padding-top': 232 + shiftBy + 'px'});
	} else if(parseInt(d3.select("body").style("padding-top").substring(0,d3.select("body").style("padding-top").indexOf("p"))) > 232) {
		$('body').css({'padding-top': 232 + 'px'});
	};

	if($(".filterBtn-c:visible").length > 0) {
		d3.select(".filterBtn-all").style("display", null);
	} else {
		d3.select(".filterBtn-all").style("display", "none");
	};
}

function renderESA() {
	if(!deptFilter && !diagFilter.length) {
		prepareData(gender.top(Infinity))
		drawESA(flowICMap);
	} else if(!deptFilter && diagFilter.length > 0) {
		var tempPList = {}
		gender.top(Infinity).forEach(function(d) {
			if(!tempPList[d.PatientIC]) {
				tempPList[d.PatientIC] = 1;
			}
		})
		diagDim.filter(null);
		patientDim.filter(function(d) { return d3.keys(tempPList).indexOf(d.toString()) >= 0 });
		prepareData(gender.top(Infinity))
		drawESA(flowICMap);
		patientDim.filter(null);
		diagDim.filter(function(d) { return diagFilter.indexOf(d.toString()) >= 0; });
	} else if(deptFilter && !diagFilter.length) {
		var tempPList = {}
		gender.top(Infinity).forEach(function(d) {
			if(!tempPList[d.PatientIC]) {
				tempPList[d.PatientIC] = 1;
			}
		})
		dept.filter(null);
		patientDim.filter(function(d) { return d3.keys(tempPList).indexOf(d.toString()) >= 0 });
		prepareData(gender.top(Infinity))
		drawESA(flowICMap);
		patientDim.filter(null);
		dept.filter(deptFilter)
	} else {
		/*
		var tempList = [];
		console.log(patientFilterDept)
		console.log(patientFilterDiag)
		if(patientFilterDiag.length > patientFilterDept.length) {
			d3.keys(patientFilterDept).forEach(function(d) {
				if(patientFilterDiag[d] == 1) {
					tempList.push(d);
					console.log(tempList)
				}
			})
		} else {
			d3.keys(patientFilterDiag).forEach(function(d) {
				if(patientFilterDept[d] == 1) {
					tempList.push(d);
					console.log(tempList)
				}
			})
		}*/

		var tempPList = {}
		gender.top(Infinity).forEach(function(d) {
			if(!tempPList[d.PatientIC]) {
				tempPList[d.PatientIC] = 1;
			}
		})
		dept.filter(null);
		diagDim.filter(null);
		patientDim.filter(function(d) { return d3.keys(tempPList).indexOf(d.toString()) >= 0 })
		prepareData(gender.top(Infinity))
		drawESA(flowICMap);
		patientDim.filter(null);
		diagDim.filter(function(d) { return diagFilter.indexOf(d.toString()) >= 0; });
		dept.filter(deptFilter)
	}
}

function resetAll() {
	loader()
	window.setTimeout(function() {
		$(".filterBtn-c a:visible").each(function(d) {
			var result = d3.select(this)[0][0].attributes[1].value
			var start = d3.select(this)[0][0].attributes[1].value.indexOf("(");
			var end = d3.select(this)[0][0].attributes[1].value.indexOf(")");
			var value = result.substring(start + 1, end);
			resetOnly(value);
		})
		$(".filterBtn-diag a:visible").each(function(d) {
			var result = d3.select(this)[0][0].attributes[1].value
			var start = d3.select(this)[0][0].attributes[1].value.indexOf("'");
			var end = d3.select(this)[0][0].attributes[1].value.indexOf("')");
			var value = result.substring(start + 1, end);
			resetOnly(value);
		})
		$(".filterBtn-dept a:visible").each(function(d) {
			var result = d3.select(this)[0][0].attributes[1].value
			var start = d3.select(this)[0][0].attributes[1].value.indexOf("'");
			var end = d3.select(this)[0][0].attributes[1].value.indexOf("')");
			var value = result.substring(start + 1, end);
			resetOnly(value);
			profilerInitiator();
		})
		$(".filterBtn-events a:visible").each(function(d) {
			var result = d3.select(this)[0][0].attributes[1].value
			var start = d3.select(this)[0][0].attributes[1].value.indexOf("'");
			var end = d3.select(this)[0][0].attributes[1].value.indexOf("')");
			var value = result.substring(start + 1, end);
			resetOnly(value);
		})
		if(d3.select("#profiler").classed("active")) {
			renderMap();
		}
		if(d3.select("#networkContent").classed("active")) {
			if(!deptFilter) {
				redrawNetwork();
				networkSummary();
			} else {
				dept.filter(null);
				redrawNetwork();
				dept.filter(deptFilter)
			}
		}

		if(d3.select("#events").classed("active")) {
			renderESA();
		}
		cLoader();
		redrawDemo();
		renderAll();
	}, 1)
}

function resetOnly(i) {
	if(parseInt(i) || i == 0) {
		delete demoFilter[i];

		var chart = d3.selectAll(".chart");
		d3.select(chart[0][i]).data()[0].filter(null);
		
		var dateChart = d3.selectAll(".dateChart")
		var yearChart = d3.selectAll(".yearChart")
		var ids = []
		for(var z = 0; z < dateChart[0].length; z++) {
			ids.push(d3.select(dateChart[0][z]).data()[0].id());
		}
		if(ids.indexOf(i) != -1 || d3.select(yearChart[0][0]).data()[0].id() == i) {
			redrawDate();
		}

		d3.select(chart[0][i]).selectAll(".foreground").classed("selected", false).classed("background", false);
	} else if(i == "dept") {
		dept.filter(null);
		deptFilter = null;
		d3.select(".filterBtn-dept text").text("Showing all touchpoints");
    	d3.select(".filterBtn-dept .close").style("display","none");
	} else if(i == "events") {
		patientDim.filter(null);
		d3.select(".filterBtn-events text").text("Showing all event flows");
      	d3.select(".filterBtn-events .close").style("display","none");
	} else if(i == "diag") {
		diagDim.filter(null);
		diagFilter = [];
	}
}

function reset(i) {
	loader();
	if(parseInt(i) || i == 0) {
		delete demoFilter[i];

		var chart = d3.selectAll(".chart");
		d3.select(chart[0][i]).data()[0].filter(null);
		
		var dateChart = d3.selectAll(".dateChart")
		var yearChart = d3.selectAll(".yearChart")
		var ids = []
		for(var z = 0; z < dateChart[0].length; z++) {
			ids.push(d3.select(dateChart[0][z]).data()[0].id());
		}
		if(ids.indexOf(i) != -1 || d3.select(yearChart[0][0]).data()[0].id() == i) {
			redrawDate(i);
		}

		d3.select(chart[0][i]).selectAll(".foreground").classed("selected", false).classed("background", false);
	} else if(i == "dept") {
		dept.filter(null);
		deptFilter = null;
		d3.select(".filterBtn-dept text").text("Showing all touchpoints");
    	d3.select(".filterBtn-dept .close").style("display","none");
		profilerInitiator();
	} else if(i == "events") {
		patientDim.filter(null);
		d3.select(".filterBtn-events text").text("Showing all event flows");
      	d3.select(".filterBtn-events .close").style("display","none");
	} else if(i == "diag") {
		diagDim.filter(null);
		diagFilter = [];
	}

	if(d3.select("#profiler").classed("active")) {
		renderMap();
	}

	if(d3.select("#networkContent").classed("active")) {
		if(!deptFilter) {
			redrawNetwork();
			networkSummary();
		} else {
			dept.filter(null);
			redrawNetwork();
			dept.filter(deptFilter)
		}
	}

	if(d3.select("#events").classed("active")) {
		if(i!="events") {
			renderESA();
		}
	}

	cLoader();
	redrawDemo();
	renderAll();
}

function redrawDemo(id) {
	nestData();
	var demoChart = d3.selectAll(".demoChart")
	
	for(var i = 0; i < demoChart[0].length; i++) {
		if(d3.select(demoChart[0][i]).data()[0].id() != id){
			d3.select(demoChart[0][i]).data()[0].group(nestedData[i]);
		}
	}
}

function redrawDate(id) {
	var dateChart = d3.selectAll(".dateChart")

	for(var i = 0; i < dateChart[0].length; i++) {
		if(d3.select(dateChart[0][i]).data()[0].id() > id || !id){
			switch (i)
			{
			case 0:
				d3.select(dateChart[0][i]).data()[0]
				.x(d3.time.scale()
				  .domain([d3.time.month(d3.min(month.top(Infinity), function(d){return d.admissionTimeStamp;})), d3.time.month.offset(new Date(d3.time.month(d3.max(month.top(Infinity), function(d){return d.admissionTimeStamp;}))),1)])
			      .rangeRound([0, rangeMax(940)]))
				break;
			case 1:
				d3.select(dateChart[0][i]).data()[0]
				.x(d3.time.scale()
			      .domain([d3.time.week(d3.min(week.top(Infinity), function(d){return d.admissionTimeStamp;})), d3.time.week.offset(new Date(d3.time.week(d3.max(week.top(Infinity), function(d){return d.admissionTimeStamp;}))),1)])
			      .rangeRound([0, rangeMax(940)]))
				break;
			case 2:
				d3.select(dateChart[0][i]).data()[0]
				.x(d3.time.scale()
			  	  .domain([d3.time.day(d3.min(date.top(Infinity), function(d){return d.admissionTimeStamp;})), d3.time.day.offset(new Date(d3.time.day(d3.max(date.top(Infinity), function(d){return d.admissionTimeStamp;}))),7)])
			      .rangeRound([0, rangeMax(940)]))
				break;
			}
		}
	}
}

function yearCharts() {
	var yearCharts = [
		barChart()
		.id(0)
		.linear(true)
		.tick(true)
		.dimension(year)
		.group(years)
		.round(d3.time.year.round)
		.x(d3.time.scale()
		.domain([d3.min(years.all(), function(d){return d.key;}), d3.time.year.offset(new Date(d3.max(years.all(), function(d){return d.key;})),1)]) 
		.rangeRound([0, rangeMax(940)]))
		.xAxis(d3.svg.axis()
		.orient("bottom")
		.ticks(d3.time.years))
	];

	yearChart = d3.selectAll(".yearChart")
	.data(yearCharts)
	.each(function(yearChart) { yearChart.on("brushend", renderAll); });

	return yearChart;
} 

function dateCharts() {
	var dateCharts = [
		barChart()
		.id(1)
		.axisUpdate(true)
		.linear(true)
		.tick(true)
		.dimension(month)
		.group(months)
		.round(d3.time.month.round)
		.x(d3.time.scale()
		  .domain([d3.time.month(d3.min(month.top(Infinity), function(d){return d.admissionTimeStamp;})), d3.time.month.offset(new Date(d3.time.month(d3.max(month.top(Infinity), function(d){return d.admissionTimeStamp;}))),1)])
		  .rangeRound([0, rangeMax(940)]))
		.xAxis(d3.svg.axis()
		  .orient("bottom")
		  .ticks(d3.time.months)
		  .tickFormat(d3.time.format("%b %y"))),

		barChart()
		.id(2)
		.axisUpdate(true)
		.linear(true)
		.dimension(week)
		.group(weeks)
		.round(d3.time.week.round)
		.x(d3.time.scale()
		  .domain([d3.time.week(d3.min(week.top(Infinity), function(d){return d.admissionTimeStamp;})), d3.time.week.offset(new Date(d3.time.week(d3.max(week.top(Infinity), function(d){return d.admissionTimeStamp;}))),1)])
		  .rangeRound([0, rangeMax(940)])),

		barChart()
		.id(3)
		.axisUpdate(true)
		.linear(true)
		.dimension(date)
		.group(dates)
		.round(d3.time.day.round)
		.x(d3.time.scale()
		  .domain([d3.time.day(d3.min(date.top(Infinity), function(d){return d.admissionTimeStamp;})), d3.time.day.offset(new Date(d3.time.day(d3.max(date.top(Infinity), function(d){return d.admissionTimeStamp;}))),7)])
		  .rangeRound([0, rangeMax(940)]))
	];

	dateChart = d3.selectAll(".dateChart")
	.data(dateCharts)
	.each(function(dateChart) { dateChart.on("brushend", renderAll); });

	return dateChart;
}

function demoCharts() {
	nestData();

	var demoCharts = [
		barChart()
		.id(4)
		.unique(true)
		.dimension(gender)
		.group(nestGender)
		.round("ordinal")
		.x(d3.scale.ordinal()
		  .domain(genders.all().map(function(d){return d.key;}))
		  .rangeBands([0, rangeMax(140)], barPadding)),

		barChart()
		.id(5)
		.unique(true)
		.dimension(race)
		.group(nestRace)
		.round("ordinal")
		.x(d3.scale.ordinal()
		  .domain(races.all().map(function(d){return d.key;}))
		  .rangeBands([0, rangeMax(540)], barPadding)),

		barChart()
		.id(6)
		.unique(true)
		.linear(true)
		.dimension(age)
		.group(nestAge)
		.round("linear")
		.x(d3.scale.linear()
		  .domain([d3.min(ages.all(), function(d){return d.key;}), d3.max(ages.all(), function(d){return d.key;}) + 1])
		  .rangeRound([0, rangeMax(700)]))
		.xAxis(d3.svg.axis()
		  .orient("bottom")
		  .tickPadding(1.5)
		  .tickFormat(function(d){ return d*5;})
		  .ticks(ages.all().length))
	];

	demoChart = d3.selectAll(".demoChart")
	.data(demoCharts)
	.each(function(demoChart) { demoChart.on("brushend", renderAll); });

	return demoChart;
}

function admitCharts() {
	var admitCharts = [
		barChart()
		.id(7)
		.linear(true)
		.tick(true)
		.dimension(admitDay)
		.group(admitDays)
		.round("linear")
		.x(d3.scale.linear()
		  .domain([0, admitDays.size()])
		  .rangeRound([0, rangeMax(540)]))
		.xAxis(d3.svg.axis()
		  .orient("bottom")
		  .tickPadding(1.5)
		  .tickFormat(function(d){ return dayOrder[d];})),
		
		barChart()
		.id(8)
		.linear(true)
		.dimension(admitHour)
		.group(admitHours)
		.round(d3.time.hour.round)
		.x(d3.time.scale()
		  .domain([d3.min(admitHours.all(), function(d){return d.key;}), d3.time.hour.offset(new Date(d3.max(admitHours.all(), function(d){return d.key;})),1)])
		  .rangeRound([0, rangeMax(700)]))
		.xAxis(d3.svg.axis()
		  .orient("bottom")
		  .ticks(d3.time.hours)
		  .tickFormat(d3.time.format("%H")))
	];

	admitChart = d3.selectAll(".admitChart")
	.data(admitCharts)
	.each(function(admitChart) { admitChart.on("brushend", renderAll); });

	return admitChart;
}

function discCharts() {
	var discCharts = [
		barChart()
		.id(9)
		.linear(true)
		.tick(true)
		.dimension(discDay)
		.group(discDays)
		.round("linear")
		.x(d3.scale.linear()
		  .domain([0,discDays.size()])
		  .rangeRound([0, rangeMax(540)]))
		.xAxis(d3.svg.axis()
		  .orient("bottom")
		  .tickPadding(1.5)
		  .tickFormat(function(d){ return dayOrder[d];})),
		
		barChart()
		.id(10)
		.linear(true)
		.dimension(discHour)
		.group(discHours)
		.round(d3.time.hour.round)
		.x(d3.time.scale()
		  .domain([d3.min(discHours.all(), function(d){return d.key;}), d3.time.hour.offset(new Date(d3.max(discHours.all(), function(d){return d.key;})),1)])
		  .rangeRound([0, rangeMax(700)]))
		.xAxis(d3.svg.axis()
		  .orient("bottom")
		  .ticks(d3.time.hours)
		  .tickFormat(d3.time.format("%H")))
	];

	discChart = d3.selectAll(".discChart")
	.data(discCharts)
	.each(function(discChart) { discChart.on("brushend", renderAll); });

	return discChart;
}

function statCharts() {
	var statCharts = [
		barChart()
		.id(11)
		.dimension(classType)
		.group(classTypes)
		.round("ordinal")
		.x(d3.scale.ordinal()
		  .domain(classTypes.all().filter(function(d){ return d.key !== ""; }).map(function(d) { return d.key;}))
		  .rangeBands([0, rangeMax(300)], barPadding)),
		
		barChart()
		.id(12)
		.dimension(visitType)
		.group(visitTypes)
		.round("ordinal")
		.x(d3.scale.ordinal()
		  .domain(visitTypes.all().filter(function(d){ return d.key !== ""; }).map(function(d) { return d.key;}))
		  .rangeBands([0, rangeMax(300)], barPadding))
	];

	statChart = d3.selectAll(".statChart")
	.data(statCharts)
	.each(function(statChart) { statChart.on("brushend", renderAll); });

	return statChart;
}

function profilerInitiator() {
	if(!deptFilter) {
		$('#chartNav a[href="#admission"]').html("Incoming <br>Episodes");
		d3.select("#discharge").style("display","none");
		d3.select("#kpi").style("display","none");
		d3.select("#status").style("display","none");
		$('#chartNav a[href="#discharge"]').css("display","none");
		$('#chartNav a[href="#kpi"]').css("display","none");
		$('#chartNav a[href="#status"]').css("display","none");	
	}
	else if(deptFilter == "SOC") {
		$('#chartNav a[href="#admission"]').html("Actualized <br>Visits");
		d3.select("#discharge").style("display","none");
		d3.select("#kpi").style("display",null);
		d3.selectAll(".iKpi").style("display","none");
		d3.selectAll(".sKpi").style("display",null);
		d3.selectAll(".sKpi").style("z-index","99");
		d3.select("#status").style("display",null);
		$('#chartNav a[href="#discharge"]').css("display","none");
		$('#chartNav a[href="#kpi"]').css("display","");
	} else if(deptFilter == "Inpatient") {
		$('#chartNav a[href="#admission"]').html("Admissions");
		d3.select("#discharge").style("display",null);
		d3.select("#kpi").style("display",null);
		d3.selectAll(".iKpi").style("display",null);
		d3.selectAll(".sKpi").style("display","none");
		d3.select("#status").style("display","none");
		$('#chartNav a[href="#discharge"]').css("display","");
		$('#chartNav a[href="#kpi"]').css("display","");
		$('#chartNav a[href="#status"]').css("display","none");	
	} else {
		$('#chartNav a[href="#admission"]').html("Attendances");
		d3.select("#kpi").style("display","none");
		d3.select("#discharge").style("display",null);
		d3.select("#status").style("display","none");
		d3.select("#status").style("display","none");
		$('#chartNav a[href="#discharge"]').css("display","");
		$('#chartNav a[href="#kpi"]').css("display","none");
		$('#chartNav a[href="#status"]').css("display","none");	
	}

	$('#chartNav a[href="#demographics"]').tab('show');
}

function mapInitiator(){
	if(!d3.select("#overviewMap .map")[0][0]) {
		mapLoader = setInterval(function() {
			if(d3.select("#overviewMap")[0][0].clientHeight) {
				console.log("Got height!")
				loadMap();
				renderMap();
				stopMapLoader();
			}
		},0);
		return false;
	} else {
		return true;
	}
}

function stopMapLoader()
{	
	clearInterval(mapLoader);
}

loader();
d3.json("data/getPatientData.json", function(error, records){
	console.log(error);
	crossfilterize(records.d);
	generateOptions();
	var yearChart = yearCharts();
	var dateChart = dateCharts();
	var admitChart = admitCharts();
	var discChart = discCharts();
	var demoChart = demoCharts();
	var statChart = statCharts();

	totalPatients = record.groupAll().reduce(reduceAdd,reduceRemove,reduceInitial).value().count;
	totalEpisodes = record.groupAll().value(); 
	depts.all().forEach(function(d) {
		episodeCounts[d.key] = d.value;
	});

	dept.group().reduce(reduceAdd,reduceRemove,reduceInitial).all().forEach(function(d) {
		patientCounts[d.key] = d.value.count
	})

	renderNetwork();
	renderAll();
	$('#dateNav a[href="#month"]').tab('show');

	// Javascript to enable link to tab
	var hash = document.location.hash;
	var prefix = "tab_";
	if (hash) {
	    $('#topNav a[href='+hash.replace(prefix,"")+']').tab('show');
	    if(hash.indexOf("profiler") != -1) {
	    	stopMapLoader();
			if(mapInitiator()){
				renderMap();
			};
		} else if(hash.indexOf("events") != -1) {
			renderESA();
		}
	} 

	// Change hash for page-reload
	$('#topNav a').on('shown', function (e) {
	    window.location.hash = e.target.hash.replace("#", "#" + prefix);
	});
	
	$('#topNav a[href="#networkContent"]').click(function (e) {
		redrawNetwork();
	})

	$('#topNav a[href="#profiler"]').click(function (e) {
		profilerInitiator();

		stopMapLoader();
		if(mapInitiator()){
			renderMap();
		};
	})

	$('#topNav a[href="#events"]').click(function (e) {
		renderESA();
		cLoader();
	})
	
	$('#chartNav a[href="#kpi"]').click(function (e) {
		switch(deptFilter) {
			case "Inpatient":
				renderIKPI("iMonth");
				break;
			case "SOC":
				renderIKPI("sMonth");
				break;
		}
	})
	cLoader();
});

function reduceAdd(p, v) {
  	if(p.ic.indexOf(v.PatientIC) == -1) {
  		++p.count;
  		p.ic.push(v.PatientIC);
  	}
  	return p;
}

function reduceRemove(p, v) {
  	if(p.ic.indexOf(v.PatientIC) != -1) {
  		var index = p.ic.indexOf(v.PatientIC)
  		--p.count;
  		p.ic.splice(index, 1);
  	}
  	return p;
}

function reduceInitial() {
  	return {count: 0, ic: []};
}

function orderValue(p) {
  	return p.count;
}

function crossfilterize(data) {
	data.forEach(function(d, i) {
		d.Age = formatAge(d.Age);
		d.admissionTimeStamp = formatDateTime.parse(d.admissionTimeStamp);
		d.admissionTime = formatTime.parse(d.admissionTime);
		d.dischargeTimeStamp = formatDateTime.parse(d.dischargeTimeStamp);
		d.dischargeTime = formatTime.parse(d.dischargeTime);
	});

	record = crossfilter(data);

	date = record.dimension(function(d) { return d3.time.day(d.admissionTimeStamp); });
	dates = date.group();

	year = record.dimension(function(d) { return d3.time.year(d.admissionTimeStamp); });
	years = year.group();

	month = record.dimension(function(d) { return d3.time.month(d.admissionTimeStamp); });
	months = month.group();

	week = record.dimension(function(d) { return d3.time.week(d.admissionTimeStamp); });
	weeks = week.group();

	gender = record.dimension(function(d) { return d.Gender; });
	genders = gender.group();

	age = record.dimension(function(d) { return Math.floor(d.Age/5); });
	ages = age.group();

	race = record.dimension(function(d) { return d.Race; });
	races = race.group();

	admitDay = record.dimension(function(d) { return getDay(d.admissionTimeStamp);});
	admitDays = admitDay.group();

	admitHour = record.dimension(function(d) { return d3.time.hour(d.admissionTime); });
	admitHours = admitHour.group();

	discDay = record.dimension(function(d) { return getDay(d.dischargeTimeStamp);});
	discDays = discDay.group();

	discHour = record.dimension(function(d) { return d3.time.hour(d.dischargeTime); });
	discHours = discHour.group();

	diagDim = record.dimension(function(d) { return d.DiagnosisCode; });
	diags = diagDim.group();

	dept = record.dimension(function(d) { return d.dept; }),
	depts = dept.group();

	classType = record.dimension(function(d) { return d.classType; }),
	classTypes = classType.group();

	visitType = record.dimension(function(d) { return d.visitType; }),
	visitTypes = visitType.group();
	
	patientDim = record.dimension(function(d) { return d.PatientIC; });
}

function nestData() {
	nestGender = d3.nest()
	.key(function(d) { return d.Gender; })
	.sortKeys(d3.ascending)
	.key(function(d) { return d.PatientIC; })
	.rollup(function(leaves) { return leaves.length; })
	.entries(gender.top(Infinity));

	nestAge = d3.nest()
	.key(function(d) { return Math.floor(d.Age/5); })
	.sortKeys(function(a,b) { return a - b; })
	.key(function(d) { return d.PatientIC; })
	.rollup(function(leaves) { return leaves.length; })
	.entries(age.top(Infinity));

	nestRace = d3.nest()
	.key(function(d) { return d.Race; })
	.sortKeys(d3.ascending)
	.key(function(d) { return d.PatientIC; })
	.rollup(function(leaves) { return leaves.length; })
	.entries(race.top(Infinity));

	nestedData = [nestGender, nestRace, nestAge];
}

function generateOptions() {
	genders.all().forEach(function(d) {
		d3.select("#inputGender")
		.append("option")
		.text(d.key)
	})

	races.all().forEach(function(d) {
		d3.select("#inputRace")
		.append("option")
		.text(d.key)
	})

	admitDays.all().forEach(function(d) {
		d3.select("#inputDay")
		.append("option")
		.text(function() {
			switch (parseInt(d.key))
			{
			case 0:
				return "Sunday";
			case 1:
				return "Monday";
			case 2:
				return "Tuesday";
			case 3:
				return "Wednesday";
			case 4:
				return "Thursday";
			case 5:
				return "Friday";
			case 6:
				return "Saturday";
			}
		})
	})
}