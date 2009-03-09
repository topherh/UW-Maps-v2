<?php
/**
 * AutoComplete Field - PHP Remote Script
 *
 * This is a sample source code provided by fromvega.
 * Search for the complete article at http://www.fromvega.com
 *
 * Enjoy!
 *
 * @author fromvega
 *
 */

// check the parameter
if (isset($_GET['part']) and $_GET['part'] != '')
{
    // Grab our categories XML document and prepare for parsing
    $doc = new DOMDocument();
    $doc->load( 'categories.xml' );

    $markers = $doc->getElementsByTagName( "marker" );

	// initialize the results array
	$results = array();

    // We are only searching for the name by looking through all the results
    for ($x=0; $x<$markers->length; $x++)
    {
        $name = $markers->item($x)->getAttribute('name');
        //$category = $markers->item($x)->getAttribute('category');

        if ( strpos(strtolower($name), strtolower($_GET['part'])) === 0 )
        {
            $results[] = $name;
        }
    }

	// return the array as json with PHP 5.2
	echo json_encode($results);
}