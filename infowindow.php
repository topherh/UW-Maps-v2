<?php /***
* http://bradleysepos.com/projects/jquery/clipboard/
* <script type="text/javascript" src="scripts/jquery-1.3.1.min.js"></script>
* <script type="text/javascript" src="scripts/jquery.clipboard.min.js"></script>
* <script type="text/javascript">
* $(document).ready(function(){
*     $.clipboardReady(function(){
*         $("#copy-text").click(function(){
*             $.clipboard($("#share-url").val());
*             return false;
*         });
*     },{debug:true});
* });
* </script>
**/?>
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
    $image = $markers->item($x)->getAttribute('img') ? $markers->item($x)->getAttribute('img') : 'http://www.washington.edu/maps/img/bldg/'.strtolower($code).'.jpg';
    if ($category == 'landmarks')
        $image = 'http://www.washington.edu/maps/'.$markers->item($x)->getAttribute('img');
    $desc = $markers->item($x)->nodeValue;
    if ($strCode and $strCategory)
    {
        if (($code == $strCode) and ($category == $strCategory))
        {
            $title = '<h2>' . $name .( $category == 'landmarks' ? '' : ' ('.$code.')' ). '</h2>' ;
            $addrs = $address ? '<h3>Address: ' . $address . '</h3>' : '';

            echo $title .
            '<div id="popLeft">' .
            '<div id="scrollText">' . 
			//$addrs .
            $desc . 
            '</div>' . '</div>' . 
            '<div id="popRight">' .
            '<img class="photoBorder" src="'.$image.'" alt="'.
            $name . '" title="' . $name . '" width="240" height="180" />' . 
            '<br />' . '<p style="padding-left:2px; padding-top:6px">' .
            '<div class="popHeader">Link to this location* <input id="share-url" name="share-url" ' .
            'onclick="this.focus();this.select();" value="' .
            $pageURL . "?l=" . $code . ($category == 'building' ? '' : "&c=" . $category) 
            . "\" size=\"30\" /></div></p>" . 
			'<div class="copyURL">* Step 1 - Click / highlight the above URL<br />
			                      * Step 2 - PC: Control-C to copy; Mac: Command-C</div>' .
            '<input id="copy-text" type="button" onclick="copy_to_clipboard(document.getElementById(\'share-url\').value)" value="Copy" />' .
            '</div>';
        }
    }
}
?>
