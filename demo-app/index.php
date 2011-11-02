<html>
<head>
<title>GIS Fusion Table with GMaps Support</title>
<link type="text/css" rel="stylesheet" href="stylesheets/slide.css" />
<link type="text/css" rel="stylesheet" href="stylesheets/main.css" />
<!-- Import the visualization javascript --> 
<script type="text/javascript" src="http://www.google.com/jsapi"></script> 
<!-- Initialize --> 
<script type="text/javascript"> 
  google.load('visualization', '1', {});
</script>
<script type='text/javascript'>
    google.load('visualization', '1', {'packages':['table']});
</script>
<script type='text/javascript'>
    google.load('visualization', '1', {'packages':['corechart']});
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<!-- jQuery - the core -->
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<!-- Sliding effect -->
<script src="js/slide.js" type="text/javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
<!-- DYNAMIC FORM -->
	var counter = 0;
	function moreFields() {
		disableField(false);
		counter++;
		var newFields = document.getElementById('formBox').cloneNode(true);
		newFields.id = 'dynamicField';
		newFields.style.display = 'block';
		var newField = newFields.childNodes;
		for (var i=0;i<newField.length;i++) {
			var theName = newField[i].name		
			if (theName)
				newField[i].name = theName + counter;
		}
		var insertHere = document.getElementById('writeroot');
		insertHere.parentNode.insertBefore(newFields,insertHere);
		document.getElementById('remove_btn').style.display ='inline';
	}
	
</script>

<script language="javascript" type="text/javascript">
	var map, sliderTimer, layer, slider;
	var infoWindow = null;
	var tableID, activeLayer;
	var geocoder;
	var argument, colName, selectCol, agg_Title, for_C;
    var queryStr; 
//Table Visualization Variables
    var tableQuery;
    var pageNum = 0;
    var tableRecords = 29;

	function initializeMap(){			
		//alert("initialize");
                activateFarmLayer(); //default map layer
		activateMap();
		disableField(true);
//		echoSelectedLayer();			
    }

    /* echoes selected layer*/
      function echoSelectedLayer() {
		activateMap();
		//disableField(true);	
		activateFarmLayer(); //defaults to farm if others fail
	}

  function selectLayer(tableID){
                
		geocoder = new google.maps.Geocoder();
		map = new google.maps.Map(document.getElementById('map'), {
		center: new google.maps.LatLng(18.251,-77.278),
		zoom: 9,
		navigationControl: true,
		mapTypeControl: true,
		scaleControl: false,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.SMALL
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		if(tableID=="three"){
			updateHeatmap();
		}
		layer = new google.maps.FusionTablesLayer(tableID, {
			suppressInfoWindows: false, 
		 });
		
		layer.setMap(map);
		google.maps.event.addListener(layer, 'click', displayWin);
		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
		});
		doLoading(1000);
	}

  // When a user clicks on a feature on the map, intercept the
  // click event and display the data in a modal dialog box.

	function displayWin(mouseEvent) {
		if (infoWindow != null) {
			infoWindow.close();
    }
	
	infoWindow = new google.maps.InfoWindow({
			 content: "",
			 position: mouseEvent.position			 
		});	
	//map.setZoom(10);
    /*map.setCenter(event.latLng);*/
	infoWindow.open(map);
	}
	  
  // assign markers
	 function placeMarker(location) {
		var clickedLocation = new google.maps.LatLng(location);
		document.getElementById("lat").value = location.lat();
		document.getElementById("lng").value = location.lng();
	}
	
	function doTimeout(timeVar) {
		setTimeout('refreshMap(9)', timeVar);
	}
	
	//refreshes map data
	function refreshMap(zoomVar)
	{
		var myLatlng = new google.maps.LatLng(18.251,-77.279);
		var myOptions = {
			zoom: zoomVar,
			center: myLatlng,
		};
		map.setOptions(myOptions);
	}
		
	function resetMap() {
		layer.setQuery("select Xcoord from 337700 where * = '*'");
		//map.setCenter(centered);
		refreshMap(9);
	}
	
	/* activateFarmLayer sets map layer to farm data*/
        
	function activateFarmLayer(){
            if (document.getElementById("table").style.visibility == "hidden"){
                loading(true);
                document.getElementById("map").style.visibility = "visible";
                document.getElementById("table").style.visibility = "hidden";
                document.getElementById("chart_con").style.visibility = "hidden";
                document.getElementById("chart").style.visibility = "hidden";
                clearQuery();
		
                selectLayer(337700);
                activeLayer="one";

                document.getElementById('farmProp_lnk').style.color ='#fff';
                document.getElementById('cropLayr_lnk').style.color ='#15ADFF';
                document.getElementById('cropPrice_lnk').style.color ='#15ADFF';
                document.getElementById('curLayer').innerHTML = " FARM LAYER";
                addOption_propLayr();
            }
            else{
                clearQuery();
                selectLayer(337700);
                activeLayer="one";
                activateTable();
                document.getElementById('farmProp_lnk').style.color ='#fff';
                document.getElementById('cropLayr_lnk').style.color ='#15ADFF';
                document.getElementById('cropPrice_lnk').style.color ='#15ADFF';
                document.getElementById('curLayer').innerHTML = " FARM LAYER";
                addOption_propLayr();
            }

	}
	
	function activateCropLayer(){
	if (document.getElementById("table").style.visibility == "hidden"){
            loading(true);
            document.getElementById("map").style.visibility = "visible";
		document.getElementById("table").style.visibility = "hidden";
		document.getElementById("chart_con").style.visibility = "hidden";
		document.getElementById("chart").style.visibility = "hidden";
        clearQuery();
		selectLayer(323365);
		activeLayer="two";

		document.getElementById('cropLayr_lnk').style.color ='#fff';
		document.getElementById('farmProp_lnk').style.color ='#15ADFF';
		document.getElementById('cropPrice_lnk').style.color ='#15ADFF';
		document.getElementById('curLayer').innerHTML = " CROP LAYER";
		addOption_cropLayr();
                
            }
            else{
                clearQuery();
		selectLayer(323365);
		activeLayer="two";
                activateTable();
                document.getElementById('cropLayr_lnk').style.color ='#fff';
		document.getElementById('farmProp_lnk').style.color ='#15ADFF';
		document.getElementById('cropPrice_lnk').style.color ='#15ADFF';
		document.getElementById('curLayer').innerHTML = " CROP LAYER";
		addOption_cropLayr();
            }
        		
	}
	
	function activateCropPrices(){
            loading(true);
            if (document.getElementById("table").style.visibility == "hidden"){
                document.getElementById("map").style.visibility = "visible";
		document.getElementById("table").style.visibility = "hidden";
		document.getElementById("chart_con").style.visibility = "hidden";
		document.getElementById("chart").style.visibility = "hidden";
                clearQuery();
		selectLayer(351221);		
		activeLayer="three";

		document.getElementById('cropLayr_lnk').style.color ='#15ADFF';
		document.getElementById('farmProp_lnk').style.color ='#15ADFF';
		document.getElementById('cropPrice_lnk').style.color ='#fff';
		document.getElementById('curLayer').innerHTML = " CROP PRICES";
		addOption_cropPrices();
            }
            else{
                clearQuery();
		selectLayer(351221);
		activeLayer="three";
                activateTable();
                document.getElementById('cropLayr_lnk').style.color ='#15ADFF';
		document.getElementById('farmProp_lnk').style.color ='#15ADFF';
		document.getElementById('cropPrice_lnk').style.color ='#fff';
		document.getElementById('curLayer').innerHTML = " CROP PRICES";
		addOption_cropPrices();
            }
        
	}
	
        function clearQuery(){
            tableQuery = "";
            queryStr =   "";
        }

	function runQuery() {
		document.getElementById("chart_con").style.visibility = "hidden";
	var elemStr ="", critStr ="", resStr ="", element ="", criteria ="", result ="";
        loading(true);
        if (counter > 0) {
            
			if(activeLayer=="one"){
				tableNum="337700";	
				tableQuery =("\""+"select 'Parish','Extension','District','Farmersize','PropertySize','FIrstnameX','LastnameX' "+
                                             "from"+" "+tableNum+" "+"where ");
			}
			if(activeLayer=="two"){
				tableNum="323365";	
				tableQuery =("\""+"select 'Parish','Extension','District','CropGroup','CropType','PropertySize','CropArea','Farmsize','FarmerAge' "+
                                                "from"+" "+tableNum+" "+"where ");
			}
			if(activeLayer=="three"){
				tableNum="351221";
                tableQuery =("\""+"select 'Parish','CropType','FreqPrice','SupplyStatus','Quality','Price_Month'  from"+" "+tableNum+" "+"where ");
			}

            queryStr = ("\""+"select Xcoord from"+" "+tableNum+" "+"where ");
                
            for (var n=1;n <= counter ; n++ ) {
                //window.alert("here");
                elemStr = ("document.filterForm.element"+n+".value");
                critStr = ("document.filterForm.criteria"+n+".value");
                resStr  = ("document.filterForm.result"+n+".value");

                element  = eval(elemStr);
                criteria = eval(critStr);
                result   = eval(resStr);
                queryStr = (queryStr+""+element+" "+criteria+" "+"\'"+result+"\'");
                tableQuery = (tableQuery+""+element+" "+criteria+" "+"\'"+result+"\'");
                if (n != counter) {
                    queryStr = (queryStr+" AND ");
                    tableQuery = (tableQuery+" AND ");
                }
            }
        }
        else {
		
            if(activeLayer=="one"){
				tableNum="337700";
				tableQuery =("\""+"select 'Parish','Extension','District','Farmersize','PropertySize','FIrstnameX','LastnameX' "+
                                             "from"+" "+tableNum+" ");
			}
			if(activeLayer=="two"){
				tableNum="323365";
				tableQuery =("\""+"select 'Parish','Extension','District','CropGroup','CropType','PropertySize','CropArea','Farmsize','FarmerAge' "+
                                                "from"+" "+tableNum+" ");
			}
			if(activeLayer=="three"){
				tableNum="351221";
                tableQuery =("\""+"select 'Parish','CropType','FreqPrice','SupplyStatus','Quality','Price_Month'  from"+" "+tableNum+" ");
			}
            queryStr = ("\" "+"select Xcoord from"+" " +tableNum+" "+ "where * = '*' ");
        }
        
        queryStr=(queryStr + "\"");
        tableQuery = (tableQuery+"\"");
        //alert("Table Query: "+tableQuery);
        //alert("Map Query: "+queryStr);
        //var element = document.filterForm.element1.value;
        //window.alert(""+ queryStr);
        if (result == ""){
            
            doLoading(1000);
            resetMap();
            
        }
        else{
            //doTimeout(1000);	
            
            doLoading(1000);
            var isTableVisible = document.getElementById("table").style.visibility;

            if(isTableVisible=="visible"){
                document.getElementById("map").style.visibility = "hidden";
                activateTable();
            }
            else{
                document.getElementById("table").style.visibility = "hidden";
                document.getElementById("chart_con").style.visibility = "hidden";
                document.getElementById("chart").style.visibility = "hidden";
                document.getElementById("map").style.visibility = "visible";
		layer.setQuery(queryStr);
            //doTimeout(3000);		
            }
        }
    }

    function loading(hide) {
        if (hide==true) {        //Hide map and show loading image
            //document.getElementById("map").style.visibility = "hidden";
            document.getElementById("load").style.display = "block";
        }
        else {     //Show map and hide loading image
            document.getElementById("load").style.display = "none";
            //document.getElementById("map").style.visibility = "";
        }
		
    }
	
	function doLoading(timeVar) {
		setTimeout("loading(false)", timeVar);
	}
//******************************************************************************************************************
        function drawPieChart(dataTable,Agg, Agg_Col, Agg_by,for_lable) {
			 if (activeLayer == "three"){
				   if (selectCol == "CropType"){
						   var textSize = 10;
				   }
				   else {
						   var textSize = 16;
				}
			}
        var options = {cht: 'p3', title: 'Property Size By Parish', chp: 0.628, chs: '600x400',
                    colors:['#3399CC','#00FF00','#0000FF','#FF0000','#F0F000','#FFFF00','#00FFFF','#FF00FF','#FF3366','#99FF00','#66FF00','#000033','#FF9900']};
        if (activeLayer == "three"){
			var chart = new google.visualization.BarChart(document.getElementById('chart'));
			document.getElementById("chart").style.visibility = "visible";
			
            chart.draw(dataTable,{width: 600, height: 418,chartArea:{left:150,top:30,width:600,height:350}, legend:'none',
			colors:['#00FF00'],isStacked:true,hAxis:{showEvery:1},vAxis:{textStyle:{color:'#000000',fontSize:textSize}},
			hAxis:{title: ""+Agg+" of "+Agg_Col,  titleTextStyle: {color: '#FF00FF'}},
                        title: "Dislaying "+Agg+" of "+Agg_Col+" Aggregated By "+Agg_by+ "\n" +for_lable});
                   loading(false);

		}
		else{
		
			var chart = new google.visualization.PieChart(document.getElementById('chart'));
			//document.getElementById("chart_con").style.visibility = "visible";
			document.getElementById("chart").style.visibility = "visible";
			chart.draw(dataTable,{width: 800, height: 418,pieSliceText:'percentage',chartArea:{left:20,top:50,width:700,height:350},
							is3D: true, title: "Dislaying "+Agg+" of "+Agg_Col+" Aggregated By "+Agg_by+ "\n" +for_lable});
			//chart.draw(dataTable, options);
                        loading(false);
		}
    }

//*****************************************************************************************************************
//*****************************************************************************************************************
    function runAggregate() {
        for_C ="";
//        window.alert("Run Aggregate"); 
		document.getElementById("chart").style.visibility = "visible";
		document.getElementById("tableInfo").style.visibility = "hidden";
        var len = document.aggregateF.show_agg.length;
        for ( i = 0; i < len; i++) {
            if (document.aggregateF.show_agg[i].checked) {
                argument  = document.aggregateF.show_agg[i].value;
                colName   = document.aggregateF.show_agg[i].title;
                break;
            }
        }
        if (argument == "") {
            alert("No Aggregate Function chosen");
        }
        /* else {
            alert("Value/Argument: "+argument+"\nTitle/Column: "+colName);
        } */

        if(activeLayer=="one"){
            tableNum="337700";
        }
        else if(activeLayer=="two"){
            tableNum="323365";
        }
        else if(activeLayer=="three"){
            tableNum="351221";
        }

        document.getElementById("map").style.visibility = "hidden";
		document.getElementById("table").style.visibility = "hidden";
		document.getElementById("chart_con").style.visibility = "hidden";
        document.getElementById("chart").style.visibility = "hidden";

        if (tableNum != "337700"){      //If not table one then we know that it will have multiple aggregate by options
            if ((document.aggregateF.Parish.checked)&(document.aggregateF.CropType.checked))    //Should be implemented with a for to support more than two fields
            {
                loading(true);
                document.getElementById("chart_con").style.visibility = "visible";
				if(tableNum =="351221"){
					var input = "2";
				}
				else{
					var input = prompt("Do you want to aggregate " + colName + " by:\n1:'Parish' \n OR \n2:'Crop Type'\n Enter appropriate number.");
				}
                if (input == "1") {
                    selectCol = "Parish";
                    createDropDown("Parish");
                    addAltOption(document.dropDown.parish_croptype, "Clarendon","CLARENDON","parish");
                    addAltOption(document.dropDown.parish_croptype, "Hanover","HANOVER","parish");
                    addAltOption(document.dropDown.parish_croptype, "Manchester","MANCHESTER","parish");
                    addAltOption(document.dropDown.parish_croptype, "Portland","PORTLAND","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. Andrew","ST.ANDREW","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. Ann","ST.ANN","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. Catherine","ST.CATHERINE","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. Elizabeth","ST.ELIZABETH","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. James","ST.JAMES","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. Mary","ST.MARY","parish");
                    addAltOption(document.dropDown.parish_croptype, "St. Thomas","ST.THOMAS","parish");
                    addAltOption(document.dropDown.parish_croptype, "Trelawny","TRELAWNY","parish");
                    addAltOption(document.dropDown.parish_croptype, "Westmorland","WESTMORLAND","parish");
                    loading(false);
                    
                }
                else if (input == "2") {
                    selectCol = "CropType";
                    createDropDown("CropType");
                    addOption_Crops();
                    
                }
                else{
                    alert("Input does not match Selections Default Values Selected");
                    selectCol = "Parish";
                    getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" GROUP BY '"+selectCol+"'");
                }
            }
            else if(document.aggregateF.Parish.checked){
                selectCol = "Parish";
                getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" GROUP BY '"+selectCol+"'");
            }
            else if(document.aggregateF.CropType.checked){
                selectCol = "CropType";
                getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" GROUP BY '"+selectCol+"'");

            }
            else{
                alert("NO \"Aggregate By\" option selected\nDefault option = Parish");
                selectCol = "Parish";
                getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" GROUP BY '"+selectCol+"'");
            }
        }
        else if(document.aggregateF.Parish.checked) {       //Aggregate by can only be parish.
            selectCol = "Parish";
            getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" GROUP BY '"+selectCol+"'");
        }
        else{
            alert("NO \"Aggregate By\" option selected\nDefault option = Parish");
            selectCol = "Parish";
            getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" GROUP BY '"+selectCol+"'");
        }

        
        
    }
	
    function runAltAggregate(){
        loading(true);
        var condition = document.dropDown.parish_croptype.value;
        var selection = document.dropDown.parish_croptype.id;
        
        if (selection == "Parish"){
            selectCol = "CropType";
            
            for_C = ("\nfor "+ condition) ;
            

        }
        else{
            selectCol = "Parish";
            for_C = ("\nfor "+ condition) ;
        }
        
        getAggregate("SELECT '"+selectCol+"',"+argument+"("+colName+") FROM "+tableNum+" WHERE '"+selection+"'= '"+condition+"' GROUP BY '"+selectCol+"'");
        
    }
    function getAggregate(sqlString){
        loading(true);
                var queryText = encodeURIComponent(sqlString);
		var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
		//window.alert(query);
		query.send(getAggregateData);
	}

	function getAggregateData(response) {

	  numRows = response.getDataTable().getNumberOfRows();
	  numCols = response.getDataTable().getNumberOfColumns();   
	  var data = response.getDataTable();
          if (colName == "CropType"){
              agg_Title = "Crop Type";
          }
          else if (colName == "PropertySize"){
              agg_Title = "Property Size";
          }
          else if (colName == "CropArea"){
               agg_Title = "Crop Area";
          }
          else if (colName == "FreqPrice"){
               agg_Title = "Frequent Prices";
          }
          else {
              alert("No Aggregate Value Selected Using default Values");
          }
	  drawPieChart(data,argument, agg_Title, selectCol,for_C);
          //google.setOnLoadCallback(drawPieChart);
	}

    function clearAggregate(){
         getAggregate("SELECT  FROM 337700 WHERE");
     }
//*****************************************************************************************************************************
    function disableField(result)
	{
		document.filterForm.filterApplyButton.disabled=result;
		document.filterForm.filterClearButton.disabled=result;
	}
	
		
	function removeAllOptions(selectbox){
		var i;
		for(i=selectbox.options.length-1;i>=0;i--){
			selectbox.remove(i);
		}
	}
	
	function removeAllFields(formbox){
		var i;
		for(i=formbox.options.length-1;i>=0;i--){
			selectbox.remove(i);
		}
	}
	
	function addOption(selectbox,text,value )
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
		selectbox.options.add(optn);
	}
        function addAltOption(selectbox,text,value,iden )
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
                optn.id = iden;
		selectbox.options.add(optn);
	}
//*******************************************************************************************************
//*******************************************************************************************************
        function addRadioB(box,clas,type, name,title, value,lable,lableClass)
	{
		var button = document.createElement("input");
                button.setAttribute("type",type);
                button.setAttribute("name",name);
                button.setAttribute("value",value);
                button.setAttribute("class", clas);
                button.setAttribute("title", title);

                var lableSpan = document.createElement("span");
                lableSpan.className = lableClass;
                lableSpan.innerHTML = ""+lable;
                //label.setAttribute("class", clas);
                //label.setAttribute("text", value);

		var foo = document.getElementById(box);
                //Append the element in page (in span).
                foo.appendChild(button);
                foo.appendChild(lableSpan);
                
	}
        function addAggOption(box,clas, type, name, value, lable, lableClass)
        {
            var check = document.createElement("input");
            check.setAttribute("name", name);
            check.setAttribute("type", type);
            check.setAttribute("class", clas);
            check.setAttribute("value", value);

            var lableSpan = document.createElement("span");
            lableSpan.className = lableClass;
            lableSpan.innerHTML = ""+lable+":\t";

            var foo = document.getElementById(box);
            foo.appendChild(check);
            foo.appendChild(lableSpan);
        }
        function addAggLable(box,lable,lableClass)
        {
            var lableSpan = document.createElement("span");
            lableSpan.className = lableClass;
            lableSpan.innerHTML = ""+lable+":\t";
            var foo = document.getElementById(box);
            foo.appendChild(lableSpan);
        }
        function removeAgg()
        {
            var foo  = document.getElementById("radioB");
            var fuu  = document.getElementById("options");
            var foo1 = document.getElementById("radioB1");
            var fuu1 = document.getElementById("options1");
            var faa  = document.getElementById("agg_options");

            //var child = document.getElementsByName("show_agg");

            var foo_children = foo.childNodes;
            //alert(""+foo_children.length);
            for(i=foo_children.length-1;i>=0;i--)
                {
                    foo.removeChild(foo_children[i]);
                    //alert("ping");
                }
            var fuu_children = fuu.childNodes;
            //alert(""+fuu_children.length);
            for(i=fuu_children.length-1;i>=0;i--)
                {
                    fuu.removeChild(fuu_children[i]);
                    //alert("ping");
                }
            var foo1_children = foo1.childNodes;
            //alert(""+foo_children.length);
            for(i=foo1_children.length-1;i>=0;i--)
                {
                    foo1.removeChild(foo1_children[i]);
                    //alert("ping");
                }
            var fuu1_children = fuu1.childNodes;
            //alert(""+fuu_children.length);
            for(i=fuu1_children.length-1;i>=0;i--)
                {
                    fuu1.removeChild(fuu1_children[i]);
                    //alert("ping");
                }
            var faa_children = faa.childNodes;
            //alert(""+faa_children.length);
            for(i=faa_children.length-1;i>=0;i--)
                {
                    faa.removeChild(faa_children[i]);
                    //alert("ping");
                }
        }
//*******************************************************************************************************************************
//*******************************************************************************************************************************

    function createDropDown(id){
        var dropdown = document.createElement("select");
        dropdown.setAttribute("name", "parish_croptype");
        dropdown.setAttribute("id", id);
        dropdown.setAttribute("size", "10");
        //dropdown.setAttribute("Multiple", "multiple")

        var button = document.createElement("input");
        button.setAttribute("name","newChart");
        button.setAttribute("type", "submit");
        button.setAttribute("value", "Show Aggregate");
        button.setAttribute("onclick", "runAltAggregate()");

        var form = document.getElementById("chartDropDown");

        var form_c = form.childNodes;
        for(i=form_c.length-1;i>=0;i--){
            form.removeChild(form_c[i]);
            //alert("ping");
        }

        form.appendChild(dropdown);
        form.appendChild(button);

    }
    function addCropQuery(sqlString){
                var queryText = encodeURIComponent(sqlString);
		var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
		//window.alert(query);
		query.send(getCropData);
	}

	function getCropData(response) {

	  numRows = response.getDataTable().getNumberOfRows();
	  numCols = response.getDataTable().getNumberOfColumns();
         
          for(i = 0; i < numRows; i++) {
              var cropData = response.getDataTable().getValue(i, 0) + "";
              addAltOption(document.dropDown.parish_croptype, cropData,cropData,"crop_type");
          }
          loading(false);
        }
    function addOption_Crops(){
        addCropQuery("\"select 'CropType', count(CropType) from "+tableNum+" group by 'CropType'\"");



    }
//*******************************************************************************************************************************
	function addOption_propLayr(){
		for(k=0;k<3;k++){
			removeElement('elementForm','dynamicField');
		}
		removeAllOptions(element);
		removeAgg();
		addOption(document.filterForm.element, "Parish","Parish");
		addOption(document.filterForm.element, "Extension","Extension");
		addOption(document.filterForm.element, "District","District");
		addOption(document.filterForm.element, "PropertySize","PropertySize");
		addOption(document.filterForm.element, "FarmerID","FarmerID");
		addOption(document.filterForm.element, "Property_ID","Property_ID");
                addAggLable("options","Property Size","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","SUM","sum","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","AVERAGE","avg","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","MINIMUM","min","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","MAXIMUM","max","aggb_labl");
                addAggOption("agg_options","agg_box", "checkbox", "Parish", "Parish", "Parish", "agg_labl");

	}
	function addOption_cropLayr(){			
		for(k=0;k<3;k++){
			removeElement('elementForm','dynamicField'); 
		}
		removeAllOptions(element);
		removeAgg();
		addOption(document.filterForm.element, "Parish","Parish");
		addOption(document.filterForm.element, "Extension","Extension");		
		addOption(document.filterForm.element, "District","District");
		addOption(document.filterForm.element, "CropGroup","CropGroup");
		addOption(document.filterForm.element, "CropType","CropType");
		addOption(document.filterForm.element, "CropArea","CropArea");
		addOption(document.filterForm.element, "CropDate","CropDate");
		addOption(document.filterForm.element, "FarmerID","FarmerID");
		addOption(document.filterForm.element, "Property_ID","Property_ID");
		addOption(document.filterForm.element, "PropertySize","PropertySize");
                addAggLable("options","Property Size","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","SUM","sum","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","AVERAGE","avg","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","MINIMUM","min","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","PropertySize","MAXIMUM","max","aggb_labl");

                addAggLable("options1","Crop Area","aggb_labl");
                addRadioB("radioB1","agg_box","radio","show_agg","CropArea","SUM","sum","aggb_labl");
                addRadioB("radioB1","agg_box","radio","show_agg","CropArea","AVERAGE","avg","aggb_labl");
                addRadioB("radioB1","agg_box","radio","show_agg","CropArea","MINIMUM","min","aggb_labl");
                addRadioB("radioB1","agg_box","radio","show_agg","CropArea","MAXIMUM","max","aggb_labl");

                addAggOption("agg_options","agg_box", "checkbox", "Parish", "Parish", "Parish", "agg_labl");
                addAggOption("agg_options","agg_box", "checkbox", "CropType", "CropType", "Crop Type", "agg_labl");

	}
	function addOption_cropPrices(){
		for(k=0;k<3;k++){
			removeElement('elementForm','dynamicField'); 
		}
		removeAllOptions(element);
		removeAgg();

		addOption(document.filterForm.element, "Parish","Parish");
		addOption(document.filterForm.element, "CropType","CropType");
		addOption(document.filterForm.element, "PriceMonth","PriceMonth");
		addOption(document.filterForm.element, "SupplyStatus","SupplyStatus");
		addOption(document.filterForm.element, "FreqPrice","FreqPrice");

                addAggLable("options","Frequent Prices","aggb_labl");

                addRadioB("radioB","agg_box","radio","show_agg","FreqPrice","AVERAGE","avg","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","FreqPrice","MINIMUM","min","aggb_labl");
                addRadioB("radioB","agg_box","radio","show_agg","FreqPrice","MAXIMUM","max","aggb_labl");
                
                addAggOption("agg_options","agg_box", "checkbox", "Parish", "Parish", "Parish", "agg_labl");
                addAggOption("agg_options","agg_box", "checkbox", "CropType", "CropType", "Crop Type", "agg_labl");
	}
	
	function removeElement(parentDiv, childDiv){
		 if (childDiv == parentDiv) {
			  //alert("The parent div cannot be removed.");
		 }
		 else if (document.getElementById(childDiv)) {     
			  var child = document.getElementById(childDiv);
			  var parent = document.getElementById(parentDiv);
			  parent.removeChild(child);
			  counter--;
		 }
		 else {
			  //alert("Child div has already been removed or does not exist.");
			  return false;
		 }
	}
	
	function getLayerData(sqlString){			
		var queryText = encodeURIComponent(sqlString);				
		var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
		query.send(getData);	
	}
	
	function getData(response) {
	 
	  numRows = response.getDataTable().getNumberOfRows();
	  numCols = response.getDataTable().getNumberOfColumns();

         var data = response.getDataTable();

         var table = new google.visualization.Table(document.getElementById('table'));
         table.draw(data, {showRowNumber: false});
         loading(false);
	}	        
	
	function activateTable(){
            loading(true);
		/*document.map_togglr.src='images/map_toggle_trans.png';
		document.table_togglr.src='images/table_toggle_trans.png';*/
        pageNum = 0;
        var tableQueryEnd = " OFFSET "+pageNum*tableRecords+" LIMIT "+tableRecords;
		
		document.getElementById("map").style.visibility = "hidden";
		document.getElementById("table").style.visibility = "visible";
		document.getElementById("chart_con").style.visibility = "hidden";
		document.getElementById("chart").style.visibility = "hidden";
		document.getElementById("tableInfo").style.visibility = "visible";
		document.getElementById("saveButton").style.visibility = "visible";
		//getLayerData("SELECT 'Parish', 'Extension', 'PropertySize', 'District', 'FarmerID' FROM 337700 WHERE PropertySize > 0");

        if ( tableQuery== "") {
            if(activeLayer=="one"){
            tableNum="337700";
            tableQuery =("\""+"select 'Parish','Extension','District','FarmerID','Property_ID','Farmersize','PropertySize','FIrstnameX','LastnameX' "+
                             "from"+" "+tableNum+tableQueryEnd+"\"");
            }
            else if(activeLayer=="two"){
                tableNum="323365";
                tableQuery =("\""+"select 'Parish','Extension','District','FarmerID','Property_ID','CropGroup','CropType','PropertySize','CropArea','Farmsize','FarmerAge' "+
                                "from"+" "+tableNum+tableQueryEnd+"\"");
            }
            else if(activeLayer=="three"){
                tableNum="351221";
                tableQuery =("\""+"select 'Parish','CropType','FreqPrice','SupplyStatus','Quality','Price_Month' from"+" "+tableNum+tableQueryEnd+"\"");
            }
            getLayerData(tableQuery);
        }

        else {
            getLayerData(tableQuery+tableQueryEnd);
        }
    }

    function tablePrev(){
//        pageNum ? 0pageNum--;
        alert("Next Page: "+pageNum);
		getLayerData("SELECT 'Parish', 'Extension', 'PropertySize', 'District', 'FarmerID' FROM 337700 WHERE PropertySize > 0 OFFSET "
                        +(pageNum*tableRecords)+"LIMIT "+tableRecords);
    }
//    function tableNext(){
    function tablePageChange(change) {
        loading(true);
        if (change == "next")  
            pageNum++;
        else if (change == "prev")  
            pageNum = pageNum > 0 ? --pageNum : 0;

        var cTable, ctableQuery;  
        var tableBegin = pageNum*tableRecords;
        var tableQueryEnd = " OFFSET "+pageNum*tableRecords+" LIMIT "+tableRecords+"\"";
        if (activeLayer == "one") {
            //cTable = "337700";
            ctableQuery =(tableQuery+tableQueryEnd);
        }
        else if (activeLayer == "two") {
            //cTable = "323365"
            ctableQuery =(tableQuery+tableQueryEnd);
        }
        else if (activeLayer == "three") {
            //cTable = "351221";
            ctableQuery =(tableQuery+tableQueryEnd);
        }

//        alert("Next Page: "+pageNum);
//        alert(ctableQuery);
        getLayerData(ctableQuery);
		
    }
	function activateMap(){
		document.getElementById("map").style.visibility = "visible";
		document.getElementById("table").style.visibility = "hidden";
		document.getElementById("chart").style.visibility = "hidden";
		document.getElementById("tableInfo").style.visibility = "hidden";
		document.getElementById("chart_con").style.visibility = "hidden";
		document.getElementById("saveButton").style.visibility = "hidden";
		//alert("here");
		runQuery();
	}
		
	 function updateHeatmap() {
    /*var heatmap = document.getElementById('heatmap');*/
    layer.set('heatmap', heatmap.checked);
  }
  
</script>

</head>
<body id="body_wrapper" onLoad="initializeMap()" oncontextmenu="return false;">
		<div id="menu">
		
		<!-- Panel -->
		<div id="toppanel">
			<div id="panel">
				<div class="content clearfix">
					<div class="left">
						<h1>Query Manager</h1>
						<div id="left">
						<form id="elementForm" name="filterForm" action="javascript:void(0)">
						<div id="formBox" style="display:none;">
							<!-- CREATE SELECTBOX-->
							<select id="element" name="element">								
								<!--<option name="Parish" value="Parish">Parish</option>
								<option value="Extension">Extension</option>
								<option value="District">District</option>
								<option value="PropertySize">PropertySize</option>
								<option value="CropGroup">CropGroup</option>
								<option value="CropType">CropType</option>
								<option value="CropArea">CropArea</option>
								<option value="CropDate">CropDate</option>
								<option value="PriceMonth">PriceMonth</option>
								<option value="SupplyStatus">SupplyStatus</option>-->
							</select>
							<!-- CREATE SELECTBOX-->
							<select name="criteria">
								<option value="=">=</option>
								<option value="<"><</option>
								<option value="<="><=</option>
								<option value=">">></option>
								<option value=">=">>=</option>
								<option value="starts with">starts with</option>
								<option value="ends with">ends with</option>
								<option value="contains">contains</option>
								<option value="contains ignoring case">contains ignoring case</option>
								<option value="does not contain">does not contain</option>
								<option value="not equal to">not equal to</option>
								<option value="matches">matches</option>
							</select>
							<!-- CREATE TEXTBOX-->
							<input type="text" name="result" SIZE="15%" />	
							<a href="javascript:void(0)" onclick="this.parentNode.parentNode.removeChild(this.parentNode); counter--;"><img id="remove_btn" style="display:none;" src="images/remove.gif"/></a><br /><br />
						</div>
							<span id="writeroot"></span>
							<a href="javascript:void(0)" id="moreFields" onclick=" moreFields()"><span class="trigger_txt">Add Condition</span></a><br /><br \>		
							
							<input name="filterApplyButton" type="submit" value="Apply" onclick="runQuery()">
							<input name="filterClearButton" type="reset" value="Clear filter" onclick="resetMap()"> 
						</form>
							
						</div>	
					</div>
					<div class="left right" style="display:inline-block;">			
						<!-- Register Form -->
						<form action="javascript:void(0)" name="aggregateF" id="aggrgateForm">
							<h1>Aggregate Manager</h1>	
							
							<label class="agg_manager_labl" for="agg_managr">Show aggregate:</label>
                                                        <br /><br /><br />
                                                        <span id="options" ></span>
							<span id="radioB" >&nbsp;</span>
							<br />
                                                        <span id="options1" ></span>
							<span id="radioB1" >&nbsp;</span>
							<br /><br />
							<label class="agg_manager_labl" for="agg_by">Aggregated by:  </label>
                                                        <br />
                                                        <span id="agg_options"></span>
                                                        <!--<input class="agg_box" type="checkbox" name="Parish" value="Parish" />
                                                        <span class="agg_labl">Parish</span>-->
                                                        <br /><br />
							<span style="margin-left:10px;"><input type="submit" value="Apply" name="apbut"  onclick="runAggregate()">
							<input type="reset" value="Clear aggregation" onClick="activateMap()"></span>
						</form>
					</div>
				</div>
			</div> <!-- /login -->	
			<!-- The tab on top -->	
			<div class="tab">
				<ul class="login">
					<li class="left">&nbsp;</li>
					<li id="toggle">
						<a id="open" class="open" href="#">Query | Aggregate</a>
						<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
					</li>
					<li class="right">&nbsp;</li>
				</ul> 
			</div> <!-- / top -->
			
			</div> <!--panel -->
		</div> 
			<span class="mapLayer" style="display:inline;margin-left:50px;position:absolute;top:2%;z-index:2;">
				<img src="images/logo.png"/>
			</span>
			<span class="mapLayer" style="display:inline;margin-left:50px;position:absolute;top:65px;z-index:1;">
				<a style="color:#ffffff;" href="javascript:void(0)" id="farmProp_lnk" onclick="activateFarmLayer();">Farm Properties</a> | 
				<a style="color:#15ADFF;" href="javascript:void(0)" id="cropLayr_lnk" onclick="activateCropLayer();">Crop Layer</a> | 
				<a style="color:#15ADFF;" href="javascript:void(0)" id="cropPrice_lnk" onclick="activateCropPrices();">Crop Prices</a>		
			</span>
			
		<img id="load" style="display: none; position: absolute; bottom:55%; right:47%;z-index:4;" src="images/load.gif" align="middle"/>
		<div id="map" style="visibility:hidden"></div>
		<div id="table" style="visibility:hidden; position:absolute; top:15%; z-index:1;"></div>
                <div id="chart_con" align="middle" style="height:300px; width: 200px; border-left:2px solid #CCCCCC;padding: 5px 15px;position: absolute;right:8%;top: 40%;visibility: visible;z-index: 3;">
                    <form method="javascript:void(0)" style="padding-top:10px; " name="dropDown" id ="chartDropDown" action="javascript:void(0)">
                    </form>
                    <br />
                    <br />
                    <hr/>
                    <hr/><hr/><br /><br /><br /><br /><br />
                    <br /><br /><br /><br /><br /><br /><br />	
                    
                </div><div id="chart" style=" position:absolute; visibility:visible; top:50%;margin-top:11px; z-index:2;margin-right:-20px;"></div>
		<span class="switchers">
			<!-- <a id="switcherMap" onClick="activateMap()" onMouseOver="document.map_togglr.src='images/map_toggle_hov.png'" onMouseOut="document.map_togglr.src='images/map_toggle.png'" href="javascript:void(0)"><img name="map_togglr" id="map_togglr" style="border:0px; position:absolute; bottom:10%; left:0;z-index:3;" src="images/map_toggle.png"/></a> -->
			<!-- <a id="switcherTable" onClick="activateTable()" onMouseOver="document.table_togglr.src='images/table_toggle_hov.png'" onMouseOut="document.table_togglr.src='images/table_toggle.png'" href="javascript:void(0)"><img name="table_togglr" id="table_togglr" style="border:0px; position:absolute; bottom:10%;; left:45px;z-index:3;" src="images/table_toggle.png"/></a> -->
			<a id="switcherMap" onClick="activateMap()" onMouseOver="document.map_togglr.src='images/new_map-hover.png'" onMouseOut="document.map_togglr.src='images/new_map.png'" href="javascript:void(0)"><img name="map_togglr" id="map_togglr" style="border:0px; position:absolute; bottom:0px; left:-28px;z-index:3;" src="images/new_map.png"/></a>
			<a id="switcherTable" onClick="activateTable()" onMouseOver="document.table_togglr.src='images/new_table-hover.png'" onMouseOut="document.table_togglr.src='images/new_table.png'" href="javascript:void(0)"><img name="table_togglr" id="table_togglr" style="border:0px; position:relative;top:120px; left:-28px;z-index:3;" src="images/new_table.png"/></a>
			<a id="saveButton" onMouseOver="document.save_togglr.src='images/save.png'" onMouseOut="document.save_togglr.src='images/save-hover.png'" href="saveFile.php"><img name="save_togglr" id="save_togglr" style="border:0px; position:absolute; top:0px; left:-28px;z-index:3;" src="images/save-hover.png"/></a>
		</span>
		<div id="footer">
			<!--<span id="saveButton"><a href="javascript:void(0)" onclick="saveFileAs();"><img style="Margin-top:-26px; margin-right:26px;position:absolute; bottom:15%; right:28px;z-index:3;" src="images/save.png" /></a></span> -->
			<div id="tableInfo" align="middle" style="visibility:visible;padding-top:2px; color:#fff;">
                <a style="font-size:85%;color:#ffffff;" href="javascript:void(0)" id="tablePrev_lnk" onclick="tablePageChange('prev');">Prev Page</a>
                <a style="font-size:85%;color:#ffffff;" href="javascript:void(0)" id="tableNext_lnk" onclick="tablePageChange('next');">Next Page</a> 
            </div>
			<div id="curLayer" align="middle" style="font-size:90%;padding-top:2px; color:#fff;">now viewing:</div>
		</div>
		
</body>
</html>
<?php
function writeCSVfile($csvdata){
	$fname.= 'data.csv';
	$fp = fopen($fname,'w');
	fwrite($fp,$csvdata);
	fclose($fp);

	header('Content-type: application/csv');
	header("Content-Disposition: inline; filename=".$fname);
	//readfile($fname);
	
}
?>