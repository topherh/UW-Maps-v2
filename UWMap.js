/****************************************************************
*** Program Name: UWMap
*** Author: Chris Heiland
*** Last Revision Date: February 23, 2009
*** Notes: Super Class for Google Map API
****************************************************************/

/*********************************NOTES****************************
***** var ulocset = new UWIcon(category)
**********************************************************************/
function UWIcon(category)
{
    this.icon = new GIcon();
    this.category = category;

    var iconCats = new Array();
    iconCats['parking'] = 'G';
    iconCats['bus'] = 'B';
    iconCats['emergency'] = 'P';
    iconCats['atm'] = 'A';
    iconCats['library'] = 'L';
    iconCats['zip'] = 'Z';
    iconCats['computing'] = 'W';
    iconCats['wifi'] = 'W';
    iconCats['bike'] = 'R';
    iconCats['building'] = 'H';
    iconCats['gatehouse'] = 'G';

    try {
        var iconcat = iconCats[this.category];
        this.icon.image = "img/flags/" + iconcat + ".gif";
        this.icon.shadow = "img/shadow.png";
        this.icon.iconSize = new GSize(32, 32);
        this.icon.shadowSize = new GSize(45, 33);
        this.icon.iconAnchor = new GPoint(5, 34);
        this.icon.infoWindowAnchor = new GPoint(5, 2);
        this.icon.infoShadowAnchor = new GPoint(14, 25);
        this.icon.transparent = "img/flags/.gif";
        this.icon.printImage = "img/flags/.gif";
        this.icon.mozPrintImage = "img/flags/.gif";    
    }
    catch(ex) {
        this.icon = new GIcon(G_DEFAULT_ICON);
        this.iconAnchor = new GPoint(0, 0);
        this.infoWindowAnchor = new GPoint(10, 0 );
    }

}

/*********************************NOTES****************************
***** var uloc = new UWLocation(code, map, lat, lng, name, category)
***** loc.init() - Creates new marker
**********************************************************************/
function UWLocation(code, map, lat, lng, name, category)
{
    // Public Class Properties
    this.url;
    this.marker;
    this.code = code;
    this.name = name;
    this.category = category;
    this.lat = parseFloat(lat);
    this.lng = parseFloat(lng);
    this.point = new GLatLng(this.lat,this.lng);
    this.buildingIcon = new UWIcon(this.category);
    this.event1;
    this.event2;

    this.init = function(map)
    {
        var url = 'info/window.php?cat='+ this.category +'&code='+ this.code;
        // GMarker does not work if you assign it right away
        // Need to store it in a temp variable and the load when complete
        var mark = new GMarker(this.point,this.buildingIcon.icon);
        var event1 = GEvent.addListener(mark, 'click', function()
        { 
          mark.openExtInfoWindow(
            map,
            "custom_info_window_red",
            '<p>Loading...</p>',
            {
                ajaxUrl:url,
                beakOffset:3,
                paddingX:0,
                paddingY:25
            }
          ); 
        });
        var event2 = GEvent.addListener(mark, 'dblclick', function()
        {
            if (map.getExtInfoWindow())
            {
                map.closeExtInfoWindow();
                //mark.closeExtInfoWindow(map);
            }
            map.removeOverlay(mark);
        
        });
        map.setCenter(new GLatLng(this.lat,this.lng), 17);
        this.url = url;
            
        this.marker = mark;
        this.event1 = event1;
        this.event2 = event2;
    }
    this.destroy = function(map)
    {
        if (map.getExtInfoWindow())
        {
            map.closeExtInfoWindow();
        }
        GEvent.removeListener(this.event1);
        GEvent.removeListener(this.event2);
        map.removeOverlay(this.marker);
        //this.marker.hide();
    }
};

/*********************************NOTES****************************
***** var ulocset = new UWLocationSet(map);
***** ulocset.load(xmlDoc);
***** ulocset.show(map,category);
***** ulocset.hide(map,category);
**********************************************************************/
function UWLocationSet(map)
{
    this.cat = new Array();
    
    this.cat['parking'] = new Array();
    this.cat['bus'] = new Array();
    this.cat['wifi'] = new Array();
    this.cat['zip'] = new Array();
    this.cat['bike'] = new Array();
    this.cat['food'] = new Array();
    this.cat['computing'] = new Array();
    this.cat['atm'] = new Array();
    this.cat['library'] = new Array();
    this.cat['emergency'] = new Array();
    this.cat['building'] = new Array();
    this.cat['gatehouse'] = new Array();

    this.load = function(xmlDoc)
    {
        var buildList = document.getElementById("buildingList");
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
        for (var i=0; i<markers.length; i++)
        {
            // obtain the attribues of each marker
            var lat = markers[i].getAttribute("lat");
            var lng = markers[i].getAttribute("lng");

            // Rest of the information related to the marker
            var code = markers[i].getAttribute("code");
            var name = markers[i].getAttribute("name");
            var category = markers[i].getAttribute("category");

            var uloc = new UWLocation(code, map, lat, lng, name, category);
            try {
                this.cat[category].push(uloc);
            }
            catch(ex) {
                GLog.write('Error: Category '+ category +' Does NOT Exist');
            }
        }
    }
    this.show = function(map,category)
    {
        map.closeInfoWindow();
        map.clearOverlays();
        var arrLoc = this.cat[category];
        for (var i=0; i<arrLoc.length; i++)
        {
            arrLoc[i].init(map);
            map.addOverlay(arrLoc[i].marker);
            //map.addOverlay(arrLoc[i].marker);
            //arrLoc[i].marker.show();
        }
        // == check the checkbox ==
        map.setCenter(new GLatLng(47.65565,-122.30817), 16);
        //document.getElementById(category+"box").checked = true;
    }
    this.hide = function(map,category)
    {
        var arrLoc = this.cat[category];
        map.clearOverlays();
        map.closeInfoWindow();
        for (var i=0; i<arrLoc.length; i++)
        {
            arrLoc[i].destroy(map);
            //arrLoc[i].marker.hide();
        }
        // == clear the checkbox ==
        //document.getElementById(category+"box").checked = false;
        // == close the info window, in case its open on a marker that we just hid
    }
    this.search = function(map,category,strQuery)
    {
        var arrLoc = this.cat[category];
        // map.closeInfoWindow();

        for (var i=0; i<arrLoc.length; i++)
        {
            // Clear all markers before we display another
            if (arrLoc[i].marker)
            {
                //arrLoc[i].marker.hide();
                //map.removeOverlay(arrLoc[i].marker);
                arrLoc[i].destroy(map);
            }
            // GLog.write('Map Marker: '+arrLoc[i].name);
            // GLog.write(arrLoc[i].name.toLowerCase() + ' == ' + strQuery.toLowerCase());
            if (arrLoc[i].name.toLowerCase() == strQuery.toLowerCase())
            {
                arrLoc[i].init(map);
                map.addOverlay(arrLoc[i].marker);
                GEvent.trigger(arrLoc[i].marker,'click');
               //arrLoc[i].marker.show();
            }
        }
    }
    this.locate = function(map,point)
    {
        if (point)
        {
            var maxXrange = 0.0015; //degrees lon.
            var maxYrange = 0.001; //degrees lat.
            var minimumdist = 1000; //1 kilometer
            var bestLocation = null;
    
            var category = 'building';
            var arrLoc = this.cat[category];
            for (var i=0; i<arrLoc.length; i++)
            {
                // Clear all markers before we display another
                if (arrLoc[i].marker)
                {
                    arrLoc[i].destroy(map);
                }
                var candidate = arrLoc[i].point;
        
                if ((Math.abs(point.x - candidate.x) < maxXrange) &&
                (Math.abs(point.y - candidate.y) < maxYrange))
                {
                    var candidatedist = candidate.distanceFrom(point);
                    if (candidatedist < minimumdist)
                    {
                        minimumdist = candidatedist;
                        bestLocation = i;
                    }
                }
            }
            if (bestLocation)
            {
                var i = bestLocation;
                // Maybe instead of center location, drop down a bit
                // for new window height
                //map.setCenter(new GLatLng(arrLoc[i].lat,arrLoc[i].lng), 17);
                arrLoc[i].init(map);
                // Add the InfoWindow Show Here
                map.addOverlay(arrLoc[i].marker);
                GEvent.trigger(arrLoc[i].marker,'click');
            }
        }
    }
};

/*********************************NOTES****************************
***** var UMap = new UWCampusMap();
**********************************************************************/
//  var UWCampusMap = function(map)
//  {
//      this.opacity = 1.0; // 1.0 is solid, anything less and we can see if the map lines up
//      this.name = "Campus";
//      this.tileLayers = [];
// 
//      // create the map
//      var mapTypes = new Array(G_SATELLITE_MAP,G_HYBRID_MAP);
//      map = new GMap2(document.getElementById("map"), {mapTypes: mapTypes});
//      //map = new GMap2(document.getElementById("map"));
//      ulocset = new UWLocationSet(map);
//    
//      map.addControl(new GLargeMapControl());
//      map.addControl(new GOverviewMapControl());
//      map.addControl(new GMapTypeControl());
// 
//      //============================================================
//      //http:code.google.com/p/cumberland/wiki/TilePyramiderAndGoogleMaps
//      function CustomGetTileUrl(point,zoom)
//      {
//          // We only have limited zoom - need to adjust as we get more slices
//          if (zoom < 12 || zoom > 17)
//          {
//              return 'blanktile.png';
//          }
// 
//          // Define our tile boundaries
//          // Note: origin in google maps is top-left
//          var minLL = new GLatLng(47.6641,-122.32565); 
//          var maxLL = new GLatLng(47.6465,-122.2881);
//          
//          // convert our lat/long values to world pixel coordinates
//          var currentProjection = G_NORMAL_MAP.getProjection();
//          var minPixelPt = currentProjection.fromLatLngToPixel(minLL, zoom);
//          var maxPixelPt = currentProjection.fromLatLngToPixel(maxLL, zoom);
// 
//          // convert our world pixel coordinates to tile coordinates 
//          var minTileCoord = new GPoint();
//          minTileCoord.x = Math.floor(minPixelPt.x / 256);
//          minTileCoord.y = Math.floor(minPixelPt.y / 256);
// 
//          var maxTileCoord = new GPoint();
//          maxTileCoord.x = Math.floor(maxPixelPt.x / 256);
//          maxTileCoord.y = Math.floor(maxPixelPt.y / 256);
// 
//          // filter out any tile requests outside of our bounds
//          if (point.x < minTileCoord.x || 
//              point.x > maxTileCoord.x ||
//              point.y < minTileCoord.y ||
//              point.y > maxTileCoord.y)
//          {
//              return 'blanktile.png';
//          }
//          return 'cutter/' + zoom + '_' + point.x + '_' + point.y + '.png';
//      }
// 
//      var tileLayer = new GTileLayer(null,12,19, {
//          isPng:true,
//          opacity:this.opacity
//          });
//     
//      this.tileLayers = [G_NORMAL_MAP.getTileLayers()[0],tileLayer];
//      this.tilelayers[1].getTileUrl = CustomGetTileUrl;
//  };
