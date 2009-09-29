<?php
$loc = $_GET['loc'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
    <title>University of Washington Campus Map</title>
    <link href="css/UWInfoWindow.css" type="text/css" rel="Stylesheet" media="screen" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />

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
    <script type="text/javascript" src="scripts/jquery.dimensions.pack.js"></script>
    <script type="text/javascript" src="scripts/jquery.json-min.js"></script>
    <script type="text/javascript" src="scripts/jquery.copy.min.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="UWMap.js"></script>

    <script type="text/javascript">
    //<![CDATA[
    var cmap;
    
<?php
    if ($loc)
    {
        echo "    var loc = '$loc';";
        if (strpos($loc,'GH') == 0)
            echo "    var loccat = 'gatehouse';";
        elseif (strpos($loc,'LNDMK') == 0)
            echo "    var loccat = 'landmarks';";
        else
            echo "    var loccat = 'building';";
    }
    else
    {
        echo '    var loccat = null;';
        echo '    var loc = null;';
    }
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
                setTimeout('cmap.ulocset.search(loccat,loc,\'code\')', 2000);
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

<div class="wheader patchYes colorGold">	
  <span id="autoMargin">
  
    <div class="wlogoSmall">
            <div class="logoAbsolute"><a id="wlogoLink" href="http://www.washington.edu/">W</a></div>
            <div><a href="http://www.washington.edu/">University of Washington</a></div>
    </div>
    
	<div id="wsearch">        
          <form name=form1 id="searchbox_001967960132951597331:04hcho0_drk" action="http://www.google.com/cse">
			 <div class="wfield">
                <input type="hidden" name="cx" value="001967960132951597331:04hcho0_drk" />
				<input type="hidden" name="cof" value="FORID:0" />
			    <input name="q" type="text" value="Search the UW" class="wTextInput" onclick="make_blank();"/>			   
             </div>   
	  			<input type="submit" class="formbutton" name="sa" value="Go" />
          </form>
    </div>
    
	<div id="wtext">
    	<ul>
      		<li><a href="http://www.washington.edu/">UW Home</a></li>
        	<li><span class="border"><a href="http://www.washington.edu/home/directories.html">Directories</a></span></li>
       	  	<li><span class="border"><a href="http://www.washington.edu/visit/events.html">Calendar</a></span></li>
       	  	<li><span class="border"><a href="http://www.washington.edu/maps/">Maps</a></span></li>
       	  	<li><span class="border"><a href="http://myuw.washington.edu/">My UW</a></span></li>
       </ul>
    </div>
    
  </span>
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
                        <li><a href="#search" id="searchTab" onclick="pageTracker._trackPageview('/maps/index-searchtab');">Search</a></li>
                        <li><a href="#browse" id="browseTab" onclick="pageTracker._trackPageview('/maps/index-browsetab');">Brownse</a></li>
                    </ul>
                    <br style="clear:both" />
                    <div id="search">
                        <input name="searchField" type="text" id="searchField" />
                        <input id="searchgo" value="Go" type="submit" onclick="cmap.ulocset.search('building',document.getElementById('searchField').value);pageTracker._trackPageview('/maps/index-search');" />
                    </div>
                    <div id="browse">
                        <form id="browseform">
                            <select name="buildingList" size="1" onchange="cmap.ulocset.search('building',this.value,'code');pageTracker._trackPageview('/maps/index-browse');" class="results-label" id="buildingList">
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
    
            <div class="center"><input id="leave-feedback" name="leave-feedback" value="Leave Feedback" type="submit" /></div>
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
             <li><a href="http://admit.washington.edu/Visit/GuidedTour">&bull; Schedule a Guided Campus Tour &#187;</a> </li>
            </ul>
    </span>
    <span class="footLinks">
        <ul>
            <li>Commuter Services</li>
            <li><a href="http://www.washington.edu/commuterservices/get_to_uw/maps_directions/index.php">&bull; Getting to the UW &#187;</a> </li>
            <li><a href="http://www.washington.edu/commuterservices/parking/index.php">&bull; Parking at the UW &#187;</a></li> 
            <li><a href="http://www.washington.edu/facilities/transportation/uwshuttles/">&bull; UW Shuttle Service &#187;</a></li>
        </ul>
    </span>
    <span class="footLinks">
        <ul>
        	<li>Other Maps</li>
            
            
            
            <li><a href="http://www.washington.edu/home/maps/">&bull; Static Campus Map &#187;</a></li>
            
            <li><a href="http://flatline.cs.washington.edu/CAMPS/">&bull; Campus Walking Directions &#187;</a></li>
            <li><a href="/home/maps/campusmappg.pdf">&bull; Printable Campus Map &#187;</a></li>
            <li><a href="/admin/ada/">&bull; Disabilities Access Guide &#187;</a></li>
            <li><a href="http://uwmedicine.washington.edu/Global/Maps/">&bull; UW Health Sciences Center &#187;</a></li>
        </ul>
    </span>
    <span class="footLinks">
        <ul>
        	<li>Campuses</li>
            <li><a href="http://www.uwb.edu/admin/services/transportation/map.xhtml">&bull; UW Bothell &#187;</a></li>
            <li><a href="http://www.tacoma.washington.edu/campus_map/">&bull; UW Tacoma &#187;</a></li>
        </ul>
    </span>
</div>

</body>
</html>
