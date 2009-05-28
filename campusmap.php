<?php
$loc = $_GET['location'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<link href="Slim_Header/header.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    body {
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
    }
</style>

<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<link href="funkyBox.css" rel="stylesheet" type="text/css" />
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>University of Washington Campus Map</title>

    <!-- Shared JS code -->
    <script type="text/javascript" src="functions.js"></script>
    
    <!-- Google Includes -->
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAcU4W0SxvtACcZE2LNL5tMhQOjAQj1TDVieadEub6KQQllOqtmRQlZxJIcWkImOAv2IHj2_p0dx4emQ" type="text/javascript"></script>
    <script src="http://www.google.com/uds/api?file=uds.js&v=1.0&key=ABQIAAAAcU4W0SxvtACcZE2LNL5tMhQOjAQj1TDVieadEub6KQQllOqtmRQlZxJIcWkImOAv2IHj2_p0dx4emQ" type="text/javascript"></script>      
    <script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script>
    <!-- Google Includes -->
    
    <script type="text/javascript" src="scripts/plusminus.js"></script>
    <script type="text/javascript" src="scripts/extinfowindow.js"></script>    
    <script type="text/javascript" src="UWMap.js"></script>
    
    <!-- JQuery / Autocomplete Start-->
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="dimensions.js"></script>
    <script type="text/javascript" src="autocomplete.js"></script>
    <!-- JQuery / Autocomplete End -->
    
    <script type="text/javascript">
	$(function() {
	    setAutoComplete("searchField", "results", "autocomplete.php?part=");
	});
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
    </script>
    
    <link href="main.css" rel="stylesheet" type="text/css" />
    <link href="autocomplete.css" rel="stylesheet" type="text/css" media="screen" />
    
    <style type="text/css">
      @import url("http://www.google.com/uds/css/gsearch.css");
      @import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
    </style>

    <script type="text/javascript">
    //<![CDATA[
    
    var map;
    var ulocset;
    
    function OnLoad()
    {
        if (GBrowserIsCompatible())
        {
            // create the map
            map = new GMap2(document.getElementById("map"));
            ulocset = new UWLocationSet(map);
        
            map.addControl(new GLargeMapControl());
            map.addControl(new GMapTypeControl());

            // ============================================================
            // http://code.google.com/p/cumberland/wiki/TilePyramiderAndGoogleMaps
            function CustomGetTileUrl(point,zoom)
            {
                // We only have zoom at 17 - need to adjust as we get more slices
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

            var campusmap = new GMapType(tilelayers, G_SATELLITE_MAP.getProjection(), "Campus");
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
            // Not sure what the campusmap is doing at this point
            GEvent.addListener(map, 'click', function(campusmap, point)
            {
                if (point)
                {
                    var maxXrange = 0.0015; //degrees lon.
                    var maxYrange = 0.001; //degrees lat.
                    var minimumdist = 1000; //1 kilometer
                    var bestLocation = null;
        
                    var category = 'building'; // TODO:Remove
                    var arrLoc = ulocset.cat[category];
                    for (var i=0; i<arrLoc.length; i++)
                    {
                        // Clear all markers before we display another
                        map.removeOverlay(arrLoc[i].marker);
                        var candidate = arrLoc[i].point;
                
                        if ((Math.abs(point.x - candidate.x) < maxXrange) &&
                        (Math.abs(point.y - candidate.y) < maxYrange))
                        {
                            var candidatedist = candidate.distanceFrom(point);
                            if (candidatedist < minimumdist)
                            {
                                minimumdist = candidatedist;
                                bestLocation = arrLoc[i];
                            }
                        }
                    }
                    if (bestLocation)
                    {
                        map.addOverlay(bestLocation.marker);
                        bestLocation.marker.show();
                        map.setCenter(new GLatLng(bestLocation.lat,bestLocation.lng), 17);
                    }
                }
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

    google.setOnLoadCallback(OnLoad);
    //]]>
    </script>

    <script>
    window.onload = function() {
         menuinit();
    }
    window.onunload = function()
    {
        GUnload();
    }
    </script>


</head>
  <body>
  
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
          <li><a href="http://www.washington.edu/discovery/about.html">About Us</a>&nbsp;&nbsp;<span class="barLeft">|</span></li>
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
  
  <div id="tabs">
  	<ul>
    	<li><a href="campusmap.html"><img src="img/buttons/map_1_active.gif" alt="Campus maps tab" width="154" height="40" /></a></li>
        <li><a href="busroute.html"><img src="img/buttons/map_2.gif" alt="Bus routes tab" width="135" height="40" /></a></li>
       <li><a href="dining.html"><img src="img/buttons/map_3.gif" alt="Food tab" width="109" height="40" /></a></li>
    </ul>  
  </div>
  
        <div id="entire">

      <div id="nav">
      
      <!-- <div align="center"><img src="compass_map.png" alt="" width="128px" height="128px" style="padding-top:10px" /></div>
      
      <br /> -->

<br />
<div class="purpleText"><strong>UW Campus Buildings</strong></div> 

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
            <select name="buildingList" size="1" class="gmls-no-results-label" id="buildingList" onclick="doSearch('buildingList')">
                <option value="" selected="selected">Select a building...</option>
            </select>
        </form>
    </div>
</div>	
	
   
  <br />
  <br />



  	  <div id="dotted">
	    <!--   <ul>
          
          
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
                   </ul>
            </li>      
            
            <li>
					<a id="fEmergency" class="forms" href="#"><label><input class="checky" type="checkbox" id="emergencybox" onclick="boxclick(this,'emergency')" /></label>Emergency Phone</a>
            </li>   
            
            <li>
              <a id="fATM" class="forms" href="#"><label><input class="checky" type="checkbox" id="atmbox" onclick="boxclick(this,'atm')" /></label>ATM</a>
            </li>  

              
            <li>
              <a id="fBike" class="forms" href="#"><label><input class="checky" type="checkbox" id="bikebox" onclick="boxclick(this,'bike')" /></label>Bike Racks</a>
            </li>   -->
	  
      




<div class="purpleText"><strong>Visitors Center</strong></div> 


    <ul>
<li><a href="http://www.washington.edu/visit">&#187; Information and Visitors Center</a></li>

</ul>



<div class="purpleText"><strong>Prospective Students</strong></div> 

<ul>
<li><a href="http://admit.washington.edu/Visit/GuidedTour">&#187; Schedule a Guided Campus Tour</a> </li>
</ul>




<div class="purpleText"><strong>Commuter Services</strong></div>        

<ul>
<li><a href="http://www.washington.edu/commuterservices/get_to_uw/maps_directions/index.php">&#187; Getting to the UW</a> </li>
<li><a href="http://www.washington.edu/commuterservices/parking/index.php">&#187;  Parking at the UW</a></li> 
<li><a href="http://www.washington.edu/commuterservices/parking/gatehouse_map.php">&#187;  Gatehouses</a> </li>

<li><a href="http://www.washington.edu/facilities/transportation/uwshuttles/">&#187; UW Shuttle Service</a></li>
</ul>


<div class="purpleText"><strong>Other Maps</strong></div> 

<ul>
<li><a href="http://flatline.cs.washington.edu/CAMPS/">&#187; Campus Walking Directions</a></li>
<li><a href="/home/maps/campusmappg.pdf">&#187; Printable Campus Map - UW Campus and vicinity (PDF)</a></li>
<li><a href="/admin/ada/">&#187; Access Guide for People With Disabilities</a></li>
<li><a href="/home/maps/mobilitymap.pdf">&#187; Campus Mobility - Wheelchair Entrances and Routes  (PDF)</a></li>
<li><a href="/computing/compmap.html">&#187; Computing Labs</a></li>
<li><a href="http://www.lib.washington.edu/about/bookdrops.html">&#187; UW Libraries</a></li>
<li><a href="http://uwmedicine.washington.edu/Global/Maps/">&#187; UW Health Sciences Center</a></li>
<li><a href="http://www.uwb.edu/admin/services/transportation/map.xhtml">&#187; UW Bothell</a> <a href="http://www.tacoma.washington.edu/campus_map/">&#187; UW Tacoma</a></li>
</ul>

        </div>
      </div>
       
      <div id="map"></div>
      </div>
      
</body>
</html>
