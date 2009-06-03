<?php
$loc = $_GET['location'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>University of Washington Campus Map</title>
    <link href="Slim_Header/header.css" rel="stylesheet" type="text/css" />
    <link href="cpopup/css/redInfoWindow.css" type="text/css" rel="Stylesheet" media="screen" />
    <link href="main.css" rel="stylesheet" type="text/css" />
    <link href="autocomplete.css" rel="stylesheet" type="text/css" media="screen" />
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
    </style>

    <!-- Google Includes -->
    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAcU4W0SxvtACcZE2LNL5tMhQOjAQj1TDVieadEub6KQQllOqtmRQlZxJIcWkImOAv2IHj2_p0dx4emQ"></script>
    <script type="text/javascript">
        google.load("maps", "2");
    </script>
    <!-- Google Includes -->
    
    <!-- Shared JS code -->
    <script type="text/javascript" src="scripts/functions.js"></script>
    <!-- script type="text/javascript" src="scripts/plusminus.js"></script -->
    <script type="text/javascript" src="scripts/extinfowindow.js"></script>    
    
    <!-- JQuery / Autocomplete Start-->
    <script type="text/javascript" src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/dimensions.js"></script>
    <script type="text/javascript" src="scripts/autocomplete.js"></script>
    <!-- JQuery / Autocomplete End -->
    
    <script type="text/javascript" src="UWMap.js"></script>

    <script type="text/javascript">
    //<![CDATA[
        $(function() {
            setAutoComplete("searchField", "results", "autocomplete.php?part=");
        });
    //]]>
    </script>
    <script type="text/javascript">
    //<![CDATA[
        $(function() {
            var tabContainers = $('div.subTabs > div');
            tabContainers.hide().filter(':first').show();
        
            $('div.subTabs ul.tabNavigation a').click(function () {
            	tabContainers.hide();
            	tabContainers.filter(this.hash).show();
            	$('div.subTabs ul.tabNavigation a').removeClass('selected');
            	$(this).addClass('selected');
            	return false;
            }).filter(':first').click();
        });
    //]]>
    </script>

    <script type="text/javascript">
    //<![CDATA[
    
    var map;
    var ulocset;
    
    function OnLoad()
    {
        if (GBrowserIsCompatible())
        {
            // create the map
            var mapTypes = new Array(G_SATELLITE_MAP,G_HYBRID_MAP);
            map = new GMap2(document.getElementById("map"), {mapTypes: mapTypes});
            ulocset = new UWLocationSet(map);
        
            map.addControl(new GLargeMapControl());
            map.addControl(new GOverviewMapControl());
            map.addControl(new GMapTypeControl());

            // ============================================================
            // http://code.google.com/p/cumberland/wiki/TilePyramiderAndGoogleMaps
            function CustomGetTileUrl(point,zoom)
            {
                // We only have limited zoom - need to adjust as we get more slices
                if (zoom < 12 || zoom > 17)
                {
                    return 'blanktile.png';
                }

                // Define our tile boundaries
                // Note: origin in google maps is top-left
                var minLL = new GLatLng(47.6641,-122.32565); 
                var maxLL = new GLatLng(47.6465,-122.2881);
                
                // convert our lat/long values to world pixel coordinates
                var currentProjection = G_NORMAL_MAP.getProjection();
                var minPixelPt = currentProjection.fromLatLngToPixel(minLL, zoom);
                var maxPixelPt = currentProjection.fromLatLngToPixel(maxLL, zoom);

                // convert our world pixel coordinates to tile coordinates 
                var minTileCoord = new GPoint();
                minTileCoord.x = Math.floor(minPixelPt.x / 256);
                minTileCoord.y = Math.floor(minPixelPt.y / 256);

                var maxTileCoord = new GPoint();
                maxTileCoord.x = Math.floor(maxPixelPt.x / 256);
                maxTileCoord.y = Math.floor(maxPixelPt.y / 256);

                // filter out any tile requests outside of our bounds
                if (point.x < minTileCoord.x || 
                    point.x > maxTileCoord.x ||
                    point.y < minTileCoord.y ||
                    point.y > maxTileCoord.y)
                {
                    return 'blanktile.png';
                }
                return 'cutter/' + zoom + '_' + point.x + '_' + point.y + '.png';
            }

            // Setting the Normal Map as the initial will show it in the background if 
            // user goes out of range
            var tileLayer = new GTileLayer(null,12,19, {
                    isPng:true,
                    opacity:1.0 // 1.0 is solid, anything less and we can see if the map lines up
                    });
            
            var tilelayers = [G_NORMAL_MAP.getTileLayers()[0],tileLayer];
            tilelayers[1].getTileUrl = CustomGetTileUrl;

            var campusmap = new GMapType(tilelayers, G_NORMAL_MAP.getProjection(), "Campus");
            campusmap.getMaximumResolution = function(latlng){ return 17;};
            campusmap.getMinimumResolution = function(latlng){ return 12;};
            map.addMapType(campusmap);

            // Sets the center and the default map
<?php
    if ($loc)
        echo '    map.setMapType(campusmap);';
    else
        echo '    map.setCenter(new GLatLng(47.65565,-122.30817), 17, campusmap);';
?>
            // ============================================================
            // ============================================================

            // Following is called 3 times - need to put it somewhere or at least have defaults
            // map.setCenter(new GLatLng(47.65565,-122.30817), 17);
            
            GDownloadUrl("markers.xml", function(doc)
            {
                var xmlDoc = GXml.parse(doc);
                ulocset.load(xmlDoc);
            });

<?php
    if ($loc)
        echo "    ulocset.search(map,'building',$loc);";
?>
            // The point of this is instead of using the KML data, we just 
            // Choose the closest pointer and go with that - downsides??
            // ------------------------------------------------------ 
            GEvent.addListener(map, 'click', function(campusmap, point)
            {
                ulocset.locate(map,point);
            });
        }
        else
        {
            alert("Sorry, the Google Maps API is not compatible with this browser");
        }
    }    

    // doSearch needs to work with OnLocalSearch to do the actual searching
    // then display the correct results on screen
    function doSearch(strQuery)
    {
        var input = document.getElementById(strQuery).value;
        // Here is where the custom search goes
	map.closeInfoWindow();
        ulocset.search(map,'building',input);
    } 
    
    // OnClick event used for the categories displayed on page
    // == a checkbox has been clicked ==
    function boxclick(box,category)
    {
        // Bring back Location Set with Category
        //var loc = locations;
        if (box.checked)
        {
            ulocset.show(map,category);
        }
        else
        {
            ulocset.hide(map,category);
        }
    }

    //]]>
    </script>

    <script>
    window.onload = function()
    {
         OnLoad();
         //menuinit();
    }
    </script>

</head>
  <body onunload="GUnload()"> 
  
  <div id="thinSearchbar">
	<a href="http://www.washington.edu/"><img id="lgo" src="Slim_Header/w.gif" alt="University of Washington"/></a>  
<div id="rhtlnks">
      <form class=formation name=form1 id="searchbox_001967960132951597331:04hcho0_drk" 
			action="http://www.google.com/cse">
        <input type="hidden" name="cx" value="001967960132951597331:04hcho0_drk" />
        <input type="hidden" name="cof" value="FORID:0" />
        <input name="q" type="text" size="20" value="Enter Search" onClick="make_blank();"/>
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
  
 <p>&nbsp;</p> 
        <div id="entire">

      <div id="nav">

<br />
<div class="purpleText"><strong>UW Campus Buildings</strong></div> 

  	  <div id="dotted">
<div class="subTabs">
    <ul class="tabNavigation">
        <li><a href="#search" id="searchTab"></a></li>
        <li><a href="#browse" id="browseTab"></a></li>
    </ul>
    <div id="search">
            <input name="searchField" type="text" id="searchField" />
            <input value="Go" type="submit" onclick="doSearch('searchField')" />
    </div>
    <div id="browse">
        <form id="browseform">
            <select name="buildingList" size="1" class="gmls-no-results-label" id="buildingList" >
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
        echo "<option value=\"$name\" onclick=\"doSearch('buildingList')\">$name ($code)</option>";
    }
?>
            </select>
        </form>
    </div>
</div>	
	
   
  <br />
	    <!-- <ul>
          
          
	         <li>
	          <a id="fParking" class="forms" href="#" onclick="menuexpand('m1'); return false;"><label><input class="checky" type="checkbox" id="parkingbox" onclick="boxclick(this,'parking')" /></label>Parking<img id="pm1" src="" alt=""></a>
	          <ul id="m1">  
           
	            <li><form method="post" action="" class="subforms"><p>
	              <label><input type="checkbox" id="bus" /></label>
	              Motorcycle parking lots</p></form>
                </li>
                                       
                   <li><form method="post" action="" class="subforms"><p>
                       <label><input type="checkbox" id="bus" /></label>
                        Unrestricted parking lots</p></form>
				  </li>
                              
                  <li><form method="post" action="" class="subforms"><p>
                       <label><input type="checkbox" id="bus" /></label>Campus gatehouses</p>
     							   </form>
				  </li><br />
              </ul>
            </li> 
                    
                  
            <li>
              <a id="fBus" class="forms" href="#" onclick="menuexpand('m2'); return false;"><label><input class="checky" type="checkbox" id="busbox" onclick="busclick(this,'bus')" /></label>Bus Stops<img id="pm2" src="" alt=""></a>
                    <ul id="m2">
                        <li><form method="post" action="" class="subforms"><p>
                          <label><input type="checkbox" id="bus" /></label>
                          Sound Transit</p></form>
                        </li>
                                       
                        <li><form method="post" action="" class="subforms"><p>
                             <label><input type="checkbox" id="bus" /></label>
                              Metro</p></form>
   					    </li><br />                        
                   </ul> -->
    <ul>
    
        <li><a id="fComputing" class="forms" href="#"><label><input class="checky" type="checkbox" id="computingbox" onclick="boxclick(this,'computing')" /></label>Computer Labs</a></li> 
    
    <li><a id="fFood" class="forms" href="#"><label><input class="checky" type="checkbox" id="foodbox" onclick="boxclick(this,'food')" /></label>Food</a></li>
    
    
    <li><a id="fGatehouse" class="forms" href="#"><label><input class="checky" type="checkbox" id="gatehousebox" onclick="boxclick(this,'gatehouse')" /></label>Gatehouses</a></li>   
    
    <li><a id="fLandmarks" class="forms" href="#"><label><input class="checky" type="checkbox" id="landmarksbox" onclick="boxclick(this,'landmarks')" /></label>Landmarks</a></li>  
    
    <li><a id="fLibrary" class="forms" href="#"><label><input class="checky" type="checkbox" id="librarybox" onclick="boxclick(this,'library')" /></label>Libraries</a></li>  
    
    
          <li><a id="fVisitors" class="forms" href="#"><label><input class="checky" type="checkbox" id="visitors" onclick="boxclick(this,'visitors')" /></label>Visitors Center</a></li> 
    
        
        
           
    </ul>
	  
      
  <br />

    
    <div class="purpleText"><strong>Prospective Students</strong></div> 
    
    <ul>
        <li><a href="http://admit.washington.edu/Visit/GuidedTour">&#187; Schedule a Guided Campus Tour</a> </li>
    </ul>
    
    
<br />    
    <div class="purpleText"><strong>Commuter Services</strong></div>        
    
    <ul>
        <li><a href="http://www.washington.edu/commuterservices/get_to_uw/maps_directions/index.php">&#187; Getting to the UW</a> </li>
        <li><a href="http://www.washington.edu/commuterservices/parking/index.php">&#187;  Parking at the UW</a></li> 
        <li><a href="http://www.washington.edu/commuterservices/parking/gatehouse_map.php">&#187;  Gatehouses</a> </li>
        <li><a href="http://www.washington.edu/facilities/transportation/uwshuttles/">&#187; UW Shuttle Service</a></li>
    </ul>
    
    
    
    <br />
    <div class="purpleText"><strong>Other Maps</strong></div> 
    
    <ul>
        <li><a href="http://flatline.cs.washington.edu/CAMPS/">&#187; Campus Walking Directions</a></li>
        <li><a href="/home/maps/campusmappg.pdf">&#187; Printable Campus Map (PDF)</a></li>
        <li><a href="/admin/ada/">&#187; Disabilities Access Guide</a></li>
        <li><a href="/home/maps/mobilitymap.pdf">&#187; Wheelchair Access Routes  (PDF)</a></li>
        <li><a href="http://uwmedicine.washington.edu/Global/Maps/">&#187; UW Health Sciences Center</a></li>
        
        <br /><br />
        
        <li><a href="http://www.uwb.edu/admin/services/transportation/map.xhtml">&#187; UW Bothell</a> <a href="http://www.tacoma.washington.edu/campus_map/">&#187; UW Tacoma</a></li>
    </ul>

        </div>
      </div>
       
    <div id="map"></div>


</div>
      
</body>
</html>
