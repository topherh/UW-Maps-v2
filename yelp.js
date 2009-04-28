/**
 * store the Yelp API key
 */
var YWSID = "VblhHix8eCBPyqXxnIbXOw";

/**
 * store and return the Yelp food categories
 */
function returnYelpCats() {
    	
	var foodCatsArray = { "afghani":"Afghan","african":"African","newamerican":"American","argentine":"Argentine","asianfusion":"Asian Fusion","bbq":"Barbeque","basque":"Basque","belgian":"Belgian","brasseries":"Brasseries","brazilian":"Brazilian","breakfast_brunch":"Breakfast & Brunch","british":"British","buffets":"Buffets","burgers":"Burgers","burmese":"Burmese","cajun":"Cajun/Creole","cambodian":"Cambodian","caribbean":"Caribbean","chicken_wings":"Chicken Wings","chinese":"Chinese","creperies":"Creperies","cuban":"Cuban","delis":"Delis","dimsum":"Dim Sum","diners":"Diners","ethiopian":"Ethiopian","hotdogs":"Fast Food","filipino":"Filipino","fishnchips":"Fish & Chips","fondue":"Fondue","foodstands":"Food Stands","french":"French","gastropubs":"Gastropubs","german":"German","gluten_free":"Gluten-Free","greek":"Greek","halal":"Halal","hawaiian":"Hawaiian","himalayan":"Himalayan/Nepalese","hotdog":"Hot Dogs","hungarian":"Hungarian","indpak":"Indian","indonesian":"Indonesian","irish":"Irish","italian":"Italian","japanese":"Japanese","korean":"Korean","kosher":"Kosher","latin":"Latin American","raw_food":"Live/Raw Food","malaysian":"Malaysian","mediterranean":"Mediterranean","mexican":"Mexican","mideastern":"Middle Eastern","modern_european":"Modern European","mongolian":"Mongolian","moroccan":"Moroccan","pakistani":"Pakistani","persian":"Persian/Iranian","pizza":"Pizza","polish":"Polish","portuguese":"Portuguese","russian":"Russian","sandwiches":"Sandwiches","scandinavian":"Scandinavian","seafood":"Seafood","singaporean":"Singaporean","soulfood":"Soul Food","southern":"Southern","spanish":"Spanish","steak":"Steakhouses","sushi":"Sushi Bars","taiwanese":"Taiwanese","tapas":"Tapas Bars","tex-mex":"Tex-Mex","thai":"Thai","turkish":"Turkish","ukrainian":"Ukrainian","vegan":"Vegan","vegetarian":"Vegetarian","vietnamese":"Vietnamese" };
	
	return foodCatsArray;
}

/**
 * reset the cursor focus into the "term" field each time the map is called
 */
function resetYelpCursor() {

	document.yelpForm.term.focus();				
}

/**
 * set the Yelp error to hidden
 */
function clearYelpError() {
	
	document.getElementById("yelpError").style.visibility = 'hidden';		
}

/*
 * Generate HTML for the Yelp food categories dropdown
 */
function generateRestaurantCategoriesDropdownHTML() {
	
	var html = '';
	
	html += '<form name="yelpCatsForm"><select name="catValue" size="1" class="" id="catValue" onchange="updateMap();"><option value="" selected="selected">Select a category...</option>';
	
	// store the food cats array
	var foodCats = returnYelpCats();
	
	for(key in foodCats) {
		
		html += '<option value="'+key+'">'+foodCats[key]+'</option>';
						
	}
	
	html += '</select></form>';
	
	return html;
	
}