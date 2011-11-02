$(document).ready(function() {
	
	// Expand Panel
	$("#open").click(function(){
		$("div#panel").slideDown("slow");
		document.getElementById("table").style.top = "230px";
		document.getElementById("chart").style.top = "35%";
		document.getElementById("chart_con").style.top = "50%";
		document.getElementById("load").style.bottom = "20%";
		document.getElementById("body_wrapper").style.height = "750px";
		//document.getElementById("map").style.top = "14%";
		//document.getElementById("table").style.height = "60%";
	});	
	
	// Collapse Panel
	$("#close").click(function(){
		$("div#panel").slideUp("slow");
		document.getElementById("table").style.top = "15%";		
		document.getElementById("chart").style.top = "20%";
		document.getElementById("chart_con").style.top = "20%";
		document.getElementById("load").style.bottom = "40%";
		document.getElementById("body_wrapper").style.height = "600px";
		//document.getElementById("map").style.top = "0%";
		//document.getElementById("table").style.height = "75%";
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});		
		
});