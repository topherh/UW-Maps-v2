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

$pageURL = 'http://' . $_SERVER["SERVER_NAME"].'/maps/';

// We are only searching for the name by looking through all the results
for ($x=0; $x<$markers->length; $x++)
{
    $name = $markers->item($x)->getAttribute('name');
    $code = $markers->item($x)->getAttribute('code');
    $address = $markers->item($x)->getAttribute('address');
    $category = $markers->item($x)->getAttribute('category');
    $img = $markers->item($x)->getAttribute('img') ? $markers->item($x)->getAttribute('img') : 'http://www.washington.edu/maps/img/bldg/'.strtolower($code).'.jpg';
    $desc = $markers->item($x)->nodeValue;
    if ($strCode and $strCategory)
    {
        if (($code == $strCode) and ($category == $strCategory))
        {
            #$image = 'src="img/' . ($img ? 'landmarks/'.$img : 'bldg/'.strtolower($code).'.jpg'). '"';
            $image = $img;
            $title = '<h2>' . $name .( $img ? '' : ' ('.$code.')' ). '</h2>' ;
            $addrs = $address ? '<p>Address: ' . $address . '</p>' : '';

            echo $title .
            '<div id="popLeft">' .
            '<div id="scrollText">' . 
            $desc . 
            '</div>' . '</div>' . 
            '<div id="popRight">' .
            '<img class="photoBorder" src="'.$image.'" alt="'.
            $name . '" title="' . $name . '" width="240" height="180" />' . 
            $addrs .
            '<p style="padding-left:15px">' .
            'Share: <input id="copy-text-embed" name="embed" value="' .
            $pageURL . "?loc=" . $code. "\" size=\"30\" /></p>" . 
            '</div>';
        }
    }
}
?>
