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
    $doc->load( 'buildings.xml' );

    $markers = $doc->getElementsByTagName( "marker" );

	// initialize the results array
	$results = array();

    // We are only searching for the name by looking through all the results
    for ($x=0; $x<$markers->length; $x++)
    {
        // Template: `Building Name (CODE)`
        $name = $markers->item($x)->getAttribute('name');
        $code = $markers->item($x)->getAttribute('code');
        $sName = $name . ' (' . $code . ')';

        if ( strpos(strtolower($sName), strtolower($_GET['part'])) !== false )
        {
            $results[] = $name;
        }
    }

    // return the array as json with PHP 5.2
    echo json_encode($results);
}
