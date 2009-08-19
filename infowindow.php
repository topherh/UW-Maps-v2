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
$doc->load( 'locations.xml' );

$aLoc = $doc->getElementsByTagName("location");
// $aLoc->childNodes->item(0)->nodeValue
// $aName = $doc->getElementsByTagName("name");
// $aAddress = $doc->getElementsByTagName("address");
// $aCode = $doc->getElementsByTagName("code");
// $aImg = $doc->getElementsByTagName("img");
// $aLng = $doc->getElementsByTagName("lng");
// $aLat = $doc->getElementsByTagName("lat");
// $aCat = $doc->getElementsByTagName("category");
// $aOrg = $doc->getElementsByTagName("organizations");

$pageURL = 'http://' . $_SERVER["SERVER_NAME"].'/maps/';

// We are only searching for the name by looking through all the results
for( $x=0; $x<$aLoc->length; $x++ )
{
    if ($strCode and $strCategory)
    {
        // $aLoc->childNodes->item($x)->nodeValue;
        $aOrganizations = array();
        $aLocations = array();
        for ($j=0;$j<$aLoc->item($x)->childNodes->length;$j++)
        {
            if ($aLoc->item($x)->childNodes->item($j)->hasChildNodes() == 1)
            {
                if ($aLoc->item($x)->childNodes->item($j)->nodeName != 'organizations')
                    echo "<p>".$aLoc->item($x)->childNodes->item($j)->nodeName.' == '.$aLoc->item($x)->childNodes->item($j)->nodeValue."</p>";

                // TODO: How much replication should we really have here?
                // Location Key Value Pairs
                $aLocations[$aLoc->item($x)->childNodes->item($j)->nodeName] = $aLoc->item($x)->childNodes->item($j)->nodeValue;
                for ($i=0;$i<$aLoc->item($x)->childNodes->item($j)->childNodes->length;$i++)
                {
                    if ($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->nodeName == 'organization')
                        echo "<p>".$aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->nodeName.' == '.$aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->nodeValue."</p>";
                        ($name,$value) = split(' ',$aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->nodeValue);
                        $aOrganizations[$name] = $value;
                }
            }
        }


        foreach ($aLocations as $key=>$value)
        {
            ## print "<p>Key: $key => Value: $value";
        }
        if ($x == 10)
            exit;
    
        // $aName =
        // $aAddress =
        // $aCode =
        // $aImg =
        // $aLng =
        // $aLat =
        // $aCat =
        // $aOrg =

        ## $name = $aName->item($x)->nodeValue;
        ## $code = $aCode->item($x)->nodeValue;
        ## $address = $aAddress->item($x)->nodeValue;
        ## $category = $aCat->item($x)->nodeValue;
        ## $img = $aImg->item($x)->nodeValue;
        ## $aOrgs = $aOrg->item($x);
        // TODO: finish this section //
    
        if (($code == $strCode) and ($category == $strCategory))
        {
            // If we have a landmark, then what should we do here?
            // The url is taken care of now - don't worry about it
            //$image = 'src="img/' . ($img ? 'landmarks/'.$img : 'bldg/'.strtolower($code).'.jpg'). '"';

            $title = '<h2>' . $name .( $img ? '' : ' ('.$code.')' ). '</h2>' ;
            $addrs = $img ? '' : '<p>Address: ' . $address . '</p>';
 
            echo $title .
            '<div id="popLeft">' .
            '<div id="scrollText">';
            // TODO: What should be the or else behavior?
            if ($aOrgs)
            {
                echo '<p>What you can find here:</p>';
                echo '<ul>';

                ## $child = $aOrgs->first_child();
                ## 
                ## while ($child) {
                ##    echo '<li><a href="'.($child->first_child()).'" title="'.($child->next_sibling()).'">'.($child->next_sibling()).'</a></li>';
                ##    $child = $child->next_sibling();
                ## }

                // for ($x=0; $x<$aOrgs->length; $x++)
                // {
                //     $aOrgs->childNodes->item($x)->nodeValue

                // for ($aOrgs as $sOrg)
                // {
                //     echo '<li><a href="'.().'" title="'.().'">'.().'</a></li>';
                // }
                echo '</ul>';
            }
            echo '</div>' . '</div>' . 
            '<div id="popRight">' .
            '<img class="photoBorder" '.$image.' alt="'.
            $name . '" title="' . $name . '" width="240" height="180" />' . 
            $addrs .
            '<p style="padding-left:15px">Share: <input name="embed" value="' .
            $pageURL . "?location=" . $code . "\" onclick=\"this.focus();this.select();\" size=\"30\" /></p>" . 
            '</div>';
        }
    }
}
?>
