 function addOption_propLayr(){
		
		removeAllOptions(document.filterForm.element);
		addOption(document.filterForm.element, "","");
		addOption(document.filterForm.element, "Parish","Parish");
		addOption(document.filterForm.element, "Extension","Extension");
		addOption(document.filterForm.element, "District","District");
		addOption(document.filterForm.element, "PropertySize","PropertySize");
                
	}
	function addOption_cropLayr(){		
	
		removeAllOptions(document.filterForm.element);
		addOption(document.filterForm.element, "","");
		addOption(document.filterForm.element, "Parish","Parish");
		addOption(document.filterForm.element, "Extension","Extension");		
		addOption(document.filterForm.element, "District","District");
		addOption(document.filterForm.element, "CropGroup","CropGroup");
		addOption(document.filterForm.element, "CropType","CropType");
		addOption(document.filterForm.element, "CropArea","CropArea");
		addOption(document.filterForm.element, "CropDate","CropDate");
		addOption(document.filterForm.element, "PropertySize","PropertySize");

	}
	
	 function addOption_cropPrices(){
		
		removeAllOptions(document.filterForm.element);
		addOption(document.filterForm.element, "","");
		addOption(document.filterForm.element, "Parish","Parish");
		addOption(document.filterForm.element, "CropType","CropType");
		addOption(document.filterForm.element, "PriceMonth","PriceMonth");
		addOption(document.filterForm.element, "SupplyStatus","SupplyStatus");
		addOption(document.filterForm.element, "FreqPrice","FreqPrice"); 

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
	
		function addOption(selectbox,text,value ){
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
		selectbox.options.add(optn);
	}
	
/*****************************************************************************************************************************/