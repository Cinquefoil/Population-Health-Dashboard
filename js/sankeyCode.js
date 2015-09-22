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

var svg = d3.select("#chartH").append("svg")
    .attr("width", (width) + margin.left + margin.right)
    .attr("height", (height) + margin.top + margin.bottom)
    //.attr("width", 100)
    //.attr("height", 300)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
var svgBP = d3.select("#chartBP").append("svg")
    //.attr("width", 100)
    //.attr("height", 300)
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
var svgSugar = d3.select("#chartSugar").append("svg")
    //.attr("width", 100)
    //.attr("height", 300)
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var svgBMI = d3.select("#chartBMI").append("svg")
    //.attr("width", 100)
    //.attr("height", 300)
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
var sankey = d3.sankey()
    .nodeWidth(15)
    .nodePadding(10)
    .size([width, height]);

var path = sankey.link();

////////////////////////End of Sankey Code