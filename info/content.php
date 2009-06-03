<?php
/** 
 * Custom information grab - find info on 
 * Building or Location based on category
 * or building code
 *
 * @author cheiland
**/
$strCategory = $_GET['cat'];
$strCode = $_GET['code'];

// Fixed the if statement later with error checking
    $doc = new DOMDocument();
    // SHould think about this - so much from one file
    $doc->load( 'markers.xml' );
 
    $markers = $doc->getElementsByTagName( "marker" );
 
    // initialize the results array
    $results = array();

    // We are only searching for the name by looking through all the results
    for ($x=0; $x<$markers->length; $x++)
    {
        $name = $markers->item($x)->getAttribute('name');
        $code = $markers->item($x)->getAttribute('code');
        $address = $markers->item($x)->getAttribute('address');
        $category = $markers->item($x)->getAttribute('category');
        if ($strCode and $strCategory)
        {
            if ($code == $strCode)
                $results[$code] = $name;

        }
        elseif ($strCategory)
        {
            if ($category == $strCategory)
                $results[$code] = $name;
        }

    }
    echo json_encode($results);
?>
