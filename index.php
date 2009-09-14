<?php
$loc = $_GET['loc'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
    <title>University of Washington Campus Map</title>
    <link href="css/header.css" rel="stylesheet" type="text/css" />
    <link href="css/UWInfoWindow.css" type="text/css" rel="Stylesheet" media="screen" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
    </style>

    <!-- Google Includes -->
<?php
    if ($_SERVER['HTTP_HOST']=='www.washington.edu')
        echo '    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAELDzYTvQ326NllQs81d5BxS1qEFRkHRRSgcRbuO1-H7lKO0hixRwj-08ry7DfRTDKTuGnYtuKETchg"></script>';
    else
        echo '    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAELDzYTvQ326NllQs81d5BxQQSftKFH6IMJjAAUN3JhTwf9BKSRTXJUD0__rg8Fw1JjQZ02n_Y6pqaQ"></script>';
?>
    <script type="text/javascript">
        google.load("maps", "2");
        google.load("jquery", "1.3.2");
    </script>
    <!-- Google Includes -->
    
    <!-- Shared JS code -->
    <script type="text/javascript" language="Javascript" src="scripts/jquery.json-min.js"></script>
    <script type="text/javascript" language="Javascript" src="scripts/extinfowindow.js"></script>
    <script type="text/javascript" language="Javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" language="Javascript" src="scripts/jquery.dimensions.pack.js"></script>
    <script type="text/javascript" language="Javascript" src="UWMap.js"></script>

    <script type="text/javascript">
    //<![CDATA[
    var cmap;
    
<?php
    if ($loc)
        echo "    var loc = '$loc';";
    else
        echo '    var loc = null;';
?>

    function OnLoad()
    {
        if (GBrowserIsCompatible())
        {
            cmap = new UWCampusMap();

            GDownloadUrl("markers.xml", function(doc)
            {
                var xmlDoc = GXml.parse(doc);
                cmap.ulocset.load(xmlDoc);
            });

            if (loc)
            {
                cmap.overlay();
                setTimeout('cmap.ulocset.search(\'building\',loc,\'code\')', 2000);
            }
            else
            {
                cmap.center(16);
            }

            var campusmap = this.campusmap;
            GEvent.addListener(cmap.map, 'click', function(campusmap, point)
            {
                cmap.ulocset.locate(point);
            });
        }
        else
        {
            alert("Sorry, the Google Maps API is not compatible with this browser");
        }
    }    
    
    // onclick event used for the categories displayed on page
    // == a checkbox has been clicked ==
    function boxclick(box,c)
    {
        if (box.checked)
            cmap.ulocset.show(c);
        else
            cmap.ulocset.hide(c);
    }
    // Start the loading process
    window.onload = function()
    {
         OnLoad();
    }
    //]]>
    // Start the loading process
    window.unload = function()
    {
        map2=null;
        GUnload();
    }
    //]]>
    </script>

</head>
<body>
<!-- Google Analytics -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9574772-1");
pageTracker._trackPageview();
} catch(err) {}
</script> 
<!-- Google Analytics -->

<div id="thinSearchbar">
    <a href="http://www.washington.edu/"><img id="lgo" src="img/w.gif" alt="University of Washington"/></a>  
    <div id="rhtlnks">
      <form class=formation name=form1 id="searchbox_001967960132951597331:04hcho0_drk" 
    		action="http://www.google.com/cse">
          <input type="hidden" name="cx" value="001967960132951597331:04hcho0_drk" />
          <input type="hidden" name="cof" value="FORID:0" />
          <input name="q" type="text" size="20" value="Enter Search" onclick="make_blank();"/>
          <input type="submit" name="sa" value="Go" />
      </form>
      <div id="searcha">
        <ul>
          <li><a href="http://www.washington.edu/">UW Home</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
          <li><a href="http://www.uwnews.org/">News</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
          <li><a href="http://gohuskies.ocsn.com/">Sports</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
          <li><a href="http://www.washington.edu/alumni/">Alumni</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
          <li><a href="http://myuw.washington.edu/">MyUW</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
          <li><a href="http://www.washington.edu/home/directories.html">Directories</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
          <li><a href="http://www.washington.edu/visit/events.html">Calendar</a></li>
        </ul>
      </div>
    </div>
</div>

<div id="rounded">

    <div class="top"><span></span></div>

    <div id="entire">
        <div id="nav">
            <br />
            <img src="img/headline.gif" alt="UW Campus Buildings" /> 
        
          	<div id="dotted">
                <div class="subTabs">
                    <ul class="tabNavigation">
                        <li><a href="#search" id="searchTab" onclick="pageTracker._trackPageview('/maps/index-searchtab');"></a></li>
                        <li><a href="#browse" id="browseTab" onclick="pageTracker._trackPageview('/maps/index-browsetab');"></a></li>
                    </ul>
                    <br style="clear:both" />
                    <div id="search">
                        <input name="searchField" type="text" id="searchField" />
                        <input id="searchgo" value="Go" type="submit" onclick="cmap.ulocset.search('building',document.getElementById('searchField').value);pageTracker._trackPageview('/maps/index-search');" />
                    </div>
                    <div id="browse">
                        <form id="browseform">
                            <select name="buildingList" size="1" onclick="cmap.ulocset.search('building',this.value,'code');pageTracker._trackPageview('/maps/index-browse');" class="results-label" id="buildingList">
                                <option value="" selected="selected">Select a building...</option>
<?php
    // Grab our categories XML document and prepare for parsing
    $doc = new DOMDocument();
    $doc->load( 'buildings.xml' );

    $markers = $doc->getElementsByTagName( "marker" );

    // We are only searching for the name by looking through all the results
    for ($x=0; $x<$markers->length; $x++)
    {
        $code = $markers->item($x)->getAttribute('code');
        $name = $markers->item($x)->getAttribute('name');
        echo "    <option value=\"$code\">$name ($code)</option>";
    }
?>
                            </select>
                        </form>
                    </div>
                </div>	
            <p><span id="search-error" class="error"></span></p>
    
                <h3>Noteworthy Locations</h3>            
                               
                <ul>
                    <li><a id="fComputing" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="computingbox" onclick="boxclick(this,'computing');pageTracker._trackPageview('/maps/index-computingbox');" />Computer Labs</label></a></li> 
                    <li><a id="fFood" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="foodbox" onclick="boxclick(this,'food');pageTracker._trackPageview('/maps/index-foodbox');" />Food</label></a></li>
                    <li><a id="fGatehouse" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="gatehousebox" onclick="boxclick(this,'gatehouse');pageTracker._trackPageview('/maps/index-gatehousebox');" />Gatehouses</label></a></li>
                    <li><a id="fLandmarks" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="landmarksbox" onclick="boxclick(this,'landmarks');pageTracker._trackPageview('/maps/index-landmarksbox');" />Landmarks</label></a></li>  
                    <li><a id="fLibrary" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="librarybox" onclick="boxclick(this,'library');pageTracker._trackPageview('/maps/index-librarybox');" />Libraries</label></a></li>  
                    <li><a id="fVisitors" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="visitorsbox"  onclick="boxclick(this,'visitors');pageTracker._trackPageview('/maps/index-visitorsbox');" />Visitors Center</label></a></li> 
                </ul>
                  
                <h3 class="reset"><a onclick="cmap.reset();pageTracker._trackPageview('/maps/index-reset');" href="#">RESET MAP</a></h3>
            </div>
            <br />
    
            <input id="leave-feedback" name="leave-feedback" value="leave-feedback" type="submit" />
            <div id="feedback">
                <form id="feedbackForm" action="/maps/" method="post"> 
                    <label for="email"><span class="feedback">Email: </span></label><input class="feedback-in" type="text" id="email" name="email" /> 
                    <label for="comment"><span class="feedback">Comment: </span></label><textarea class="feedback-in" id="comment" name="comment"></textarea> 
                    <input id="feedbackSubmit" type="submit" value="Comment &raquo;" /> 
                </form>
            </div>
        </div>
           
        <div id="map"></div>
    
        <div id="foot"></div>
    
    </div>
    
    <div class="bottom"></div>
</div>

<div id="footer">
    <span class="footLinks">
            <ul>
            <li>Prospective Students</li>
             <li><a href="http://admit.washington.edu/Visit/GuidedTour">&#187; Schedule a Guided Campus Tour</a> </li>
            </ul>
    </span>
    <span class="footLinks">
        <ul>
            <li>Commuter Services</li>
            <li><a href="http://www.washington.edu/commuterservices/get_to_uw/maps_directions/index.php">&#187; Getting to the UW</a> </li>
            <li><a href="http://www.washington.edu/commuterservices/parking/index.php">&#187;  Parking at the UW</a></li> 
            <li><a href="http://www.washington.edu/commuterservices/parking/gatehouse_map.php">&#187;  Gatehouses</a> </li>
            <li><a href="http://www.washington.edu/facilities/transportation/uwshuttles/">&#187; UW Shuttle Service</a></li>
        </ul>
    </span>
    <span class="footLinks">
        <ul>
        	<li>Other Maps</li>
            <li><a href="http://flatline.cs.washington.edu/CAMPS/">&#187; Campus Walking Directions</a></li>
            <li><a href="/home/maps/campusmappg.pdf">&#187; Printable Campus Map (PDF)</a></li>
            <li><a href="/admin/ada/">&#187; Disabilities Access Guide</a></li>
            <li><a href="/home/maps/mobilitymap.pdf">&#187; Wheelchair Access Routes  (PDF)</a></li>
            <li><a href="http://uwmedicine.washington.edu/Global/Maps/">&#187; UW Health Sciences Center</a></li>
        </ul>
    </span>
    <span class="footLinks">
        <ul>
        	<li>Campuses</li>
            <li><a href="http://www.uwb.edu/admin/services/transportation/map.xhtml">&#187; UW Bothell</a></li>
            <li><a href="http://www.tacoma.washington.edu/campus_map/">&#187; UW Tacoma</a></li>
        </ul>
    </span>
</div>

</body>
</html>
