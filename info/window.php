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

$pageURL = 'http://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

// We are only searching for the name by looking through all the results
for ($x=0; $x<$markers->length; $x++)
{
    $name = $markers->item($x)->getAttribute('name');
    $code = $markers->item($x)->getAttribute('code');
    $address = $markers->item($x)->getAttribute('address');
    $category = $markers->item($x)->getAttribute('category');
    $desc = $markers->item($x)->nodeValue;
    if ($strCode and $strCategory)
    {
        if (($code == $strCode) and ($category == $strCategory))
        {
            echo'<h2>' . $name . ' (' . $code . ')</h2>' .
            '<div id="popLeft">' .
            '<div id="scrollText">' . 
            $desc . 
            '</div>' . '</div>' . 
            '<div id="popRight">' .
            '<img class="photoBorder" src="img/bldg/' . strtolower($code) . '.jpg" alt="' . 
            $name . '" title="' . $name . '" width="240" height="180" />' . 
            '<p>Address: ' . $address . '</p>' .
            '<p style="padding-left:15px">Share: <input name="embed" value="' .
            $pageURL . '?location="' . $name . '" size="30" /></p>' . 
            '</div>';
        }
    }
}
?>
