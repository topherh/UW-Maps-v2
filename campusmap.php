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
        google.load("jquery", "1.3.2");
    </script>
    <!-- Google Includes -->
    
    <!-- Shared JS code -->
    <script type="text/javascript" src="scripts/extinfowindow.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="UWMap.js"></script>

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
                cmap.center(17);
            }

            var campusmap = this.campusmap;
            GEvent.addListener(cmap.map, 'click', function(campusmap, point)
            {
                cmap.ulocset.locate(point);
            });

            //GEvent.addListener(cmap.map, 'extinfowindowopen', function(){GLog.write("extinfowindowopen found");});
            //GEvent.addListener(cmap.map, 'extinfowindowclose', function(){GLog.write("extinfowindowclose found");});
            // The point of this is instead of using the KML data, we just 
            // Choose the closest pointer and go with that.
            // ------------------------------------------------------ 
            // var campusmap = ulocset.cmap.campusmap;
            // GEvent.addListener(ulocset.cmap.map, 'click', function(campusmap, point)
            // {
            //     ulocset.locate(point);
            // });
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
    </script>

</head>
  <body onunload="GUnload();"> 
  
  <div id="thinSearchbar">
	<a href="http://www.washington.edu/"><img id="lgo" src="Slim_Header/w.gif" alt="University of Washington"/></a>  
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
        <li><a href="#search" id="searchTab"></a></li>
        <li><a href="#browse" id="browseTab"></a></li>
    </ul>
    <br style="clear:both" />
    <div id="search">
            <input name="searchField" type="text" id="searchField" />
            <input value="Go" type="submit" onclick="cmap.ulocset.search('building',document.getElementById('searchField').value)" />
    </div>
    <div id="browse">
        <form id="browseform">
            <select name="buildingList" size="1" onclick="cmap.ulocset.search('building',this.value,'code')" class="results-label" id="buildingList">
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
        echo "<option value=\"$code\">$name ($code)</option>";
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
                   
                   
                   
       <h3>Noteworthy Locations</h3>            
                   
    <ul>
    
    <li><a id="fComputing" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="computingbox" onclick="boxclick(this,'computing')" />Computer Labs</label></a></li> 
    
    <li><a id="fFood" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="foodbox" onclick="boxclick(this,'food')" />Food</label></a></li>
    
    <li><a id="fGatehouse" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="gatehousebox" onclick="boxclick(this,'gatehouse')" />Gatehouses</label></a></li>
    
    <li><a id="fLandmarks" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="landmarksbox" onclick="boxclick(this,'landmarks')" />Landmarks</label></a></li>  
    
    <li><a id="fLibrary" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="librarybox" onclick="boxclick(this,'library')" />Libraries</label></a></li>  
    
    <li><a id="fVisitors" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="visitorsbox"  onclick="boxclick(this,'visitors')" />Visitors Center</label></a></li> 
    
    </ul>

    <br />
      
  <br />


        </div>
    <p><a onclick="cmap.reset();" href="#">Reset Map</a></p>	  
      </div>
       
    <div id="map"></div>

<div id="foot">

    
    
  
</div>
    <span style="clear:both"><span>
</div>


<div class="bottom"><span></span></div>
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
