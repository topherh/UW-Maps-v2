<?php

$string = 'Afghan|afghani,African|african,American|newamerican,Argentine|argentine,Asian Fusion|asianfusion,Barbeque|bbq,Basque|basque,Belgian|belgian,Brasseries|brasseries,Brazilian|brazilian,Breakfast & Brunch|breakfast_brunch,British|british,Buffets|buffets,Burgers|burgers,Burmese|burmese,Cajun/Creole|cajun,Cambodian|cambodian,Caribbean|caribbean,Chicken Wings|chicken_wings,Chinese|chinese,Creperies|creperies,Cuban|cuban,Delis|delis,Dim Sum|dimsum,Diners|diners,Ethiopian|ethiopian,Fast Food|hotdogs,Filipino|filipino,Fish & Chips|fishnchips,Fondue|fondue,Food Stands|foodstands,French|french,Gastropubs|gastropubs,German|german,Gluten-Free|gluten_free,Greek|greek,Halal|halal,Hawaiian|hawaiian,Himalayan/Nepalese|himalayan,Hot Dogs|hotdog,Hungarian|hungarian,Indian|indpak,Indonesian|indonesian,Irish|irish,Italian|italian,Japanese|japanese,Korean|korean,Kosher|kosher,Latin American|latin,Live/Raw Food|raw_food,Malaysian|malaysian,Mediterranean|mediterranean,Mexican|mexican,Middle Eastern|mideastern,Modern European|modern_european,Mongolian|mongolian,Moroccan|moroccan,Pakistani|pakistani,Persian/Iranian|persian,Pizza|pizza,Polish|polish,Portuguese|portuguese,Russian|russian,Sandwiches|sandwiches,Scandinavian|scandinavian,Seafood|seafood,Singaporean|singaporean,Soul Food|soulfood,Southern|southern,Spanish|spanish,Steakhouses|steak,Sushi Bars|sushi,Taiwanese|taiwanese,Tapas Bars|tapas,Tex-Mex|tex-mex,Thai|thai,Turkish|turkish,Ukrainian|ukrainian,Vegan|vegan,Vegetarian|vegetarian,Vietnamese|vietnamese';

$pieces = explode(",",$string);

echo '<pre>';
print_r($pieces);
echo '</pre>';

$catsArray = array();

foreach ($pieces as $key => $value) {

	$catsArray[] = explode("|",$value);
	
}

echo '<pre>';
print_r($catsArray);
echo '</pre>';

$nameArray = array();
$idArray = array();

foreach ($catsArray as $key1 => $value1) {
		
	$nameArray[] = $value1[0];
	$idArray[] = $value1[1];
}

echo '<pre>';
print_r($idArray);
echo '</pre>';

echo '<pre>';
print_r($nameArray);
echo '</pre>';

$finalArray = array_combine($idArray,$nameArray);

echo '<pre>';
print_r($finalArray);
echo '</pre>';

$string = '';

foreach ($finalArray as $id => $name) {

	$string .= 'catsArray["'.$id.'"]="'.$name.'";<br />';
	
	//$string .= '"'.$id.'":'.$name.',';
	
}

echo $string;

?>