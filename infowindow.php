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
$doc->load( 'iphone-app.xml' );
// $doc->load( 'markers.xml' );

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
$strPageContent = '';

// We are only searching for the name by looking through all the results
for( $x=0; $x<$aLoc->length; $x++ )
{
    if ($strCode and $strCategory)
    {
        //if ((trim($aLoc->item($x)->childNodes->item(1)->nodeName) == 'code') and (trim($aLoc->item($x)->childNodes->item(1)->nodeValue) == $strCode))
        if ((trim($aLoc->item($x)->childNodes->item(5)->nodeName) == 'code') and (trim($aLoc->item($x)->childNodes->item(5)->nodeValue) == $strCode))
        {
            // Location: echo "<h4>".$aLoc->item($x)->nodeName.'</h4>';
            // $aLoc->childNodes->item($x)->nodeValue;
            // $aOrganizations = array();
            $aLocations = array();
            $aOrgs = array();
            for ($j=0;$j<$aLoc->item($x)->childNodes->length;$j++)
            {
                if ($aLoc->item($x)->childNodes->item($j)->hasChildNodes() == 1)
                {
                    if (trim($aLoc->item($x)->childNodes->item($j)->nodeName) != 'organizations')
                    {
                        $strPageContent[trim($aLoc->item($x)->childNodes->item($j)->nodeName)] = trim($aLoc->item($x)->childNodes->item($j)->nodeValue);
                        // echo "<p>".trim($aLoc->item($x)->childNodes->item($j)->nodeName).' == '.trim($aLoc->item($x)->childNodes->item($j)->nodeValue)."</p>";
                    }

                    // TODO: How much replication should we really have here?
                    // Location Key Value Pairs
                    $aLocations[trim($aLoc->item($x)->childNodes->item($j)->nodeName)] = trim($aLoc->item($x)->childNodes->item($j)->nodeValue);
                    for ($i=0;$i<$aLoc->item($x)->childNodes->item($j)->childNodes->length;$i++)
                    {
                        if (trim($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->nodeName) == 'organization')
                        {
                            // echo "<h5>".$aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->nodeName.'</h5>';
                            for ($k=0;$k<$aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->length;$k++)
                            {
                                if (trim($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeName) != '#text')
                                {
                                    $aOrg = '';
                                    // TODO: Org Names
                                    $aOrg[trim($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeName)] = 
                                        trim($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeValue);
                                    ## echo '<p>'.trim($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeName).' == '.
                                    ## trim($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeValue).'</p>';
                                    ## $orgname = '';
                                    ## $orgurl = '';
                                    ## if ($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeName == 'url')
                                    ## {
                                    ##     $orgurl = $aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeValue;
                                    ## }
                                    ## if ($aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeName == 'name')
                                    ## {
                                    ##     $orgname = $aLoc->item($x)->childNodes->item($j)->childNodes->item($i)->childNodes->item($k)->nodeValue;
                                    ## }
                                    ## echo '<p>'.$orgname.'-----'.$orgurl.'</p>';
                                    array_push($aOrgs, $aOrg);
                                }
                            }
                        }
                    }
                }
            }
            $strPageContent['orgs'] = $aOrgs;
        }

        ## foreach ($aLocations as $key=>$value)
        ## {
        ##     ## print "<p>Key: $key => Value: $value";
        ## }
        // if ($x == 10)
        //     exit;
    
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
    }
}

// Let's go with some variables here, make things easier
$strName = $strPageContent['name'];
$strCode = $strPageContent['code'];
$strAddress = $strPageContent['address'];
$strImg = $strPageContent['img'];
$strLng = $strPageContent['lng'];
$strLat = $strPageContent['lat'];
$strInfo = $strPageContent['info'];
$strCat = $strPageContent['category'];
$aOrgs = $strPageContent['orgs'];

// We are doing the check up top, should be ok for now
// if (($code == $strCode) and ($category == $strCategory))
// {
    // If we have a landmark, then what should we do here?
    // The url is taken care of now - don't worry about it
    $image = 'src="' . ($strImg ? $strImg : 'http://www.washington.edu/maps/img/bldg/'.strtolower($strCode).'.jpg'). '"';
    // $image = 'src="'.$strImg.'"';
    // $title = '<h2>' . $strName .( $strImg ? '' : ' ('.$strCode.')' ). '</h2>' ;
    $title = '<h2>' . $strName .( $strCat=='landmarks' ? '' : ' ('.$strCode.')' ). '</h2>' ;
    // $title = '<h2>' . $strName .' ('.$strCode.')' . '</h2>' ;
    $addrs = $strImg ? '' : '<p>Address: ' . $strAddress . '</p>';
    $name = $strName;

    // var_dump($aOrgs);

    // Now go forth and prosper
    echo $title.
    '<div id="popLeft">' .
    '<div id="scrollText">';
    // TODO: What should be the or else behavior?
    if ($aOrgs)
    {
        echo '<p>What you can find here:</p>';
        echo '<ul>';

        foreach ($aOrgs as $sOrg)
        {
            // Total Cheat
            if ($sOrg['name'] != '')
                echo '<li><a href="'.$sOrg['url'].'" title="'.$sOrg['name'].'">'.$sOrg['name'].'</a></li>';
        }
        echo '</ul>';
    }
    else if ($strInfo)
    {
        echo '<p>'.$strInfo.'</p>';
    }
    echo '</div>' . '</div>' . 
    '<div id="popRight">' .
    '<img class="photoBorder" '.$image.' alt="'.
    $name . '" title="' . $name . '" width="240" height="180" />' . 
    $addrs .
    '<p style="padding-left:15px">Share: <input name="embed" value="' .
    $pageURL . "?location=" . $strCode . "\" onclick=\"this.focus();this.select();\" size=\"30\" /></p>" . 
    '</div>';
// }
?>
