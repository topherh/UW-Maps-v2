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
    this.icon;

    var iconCats = new Array();
    iconCats['parking'] = 'G';
    iconCats['emergency'] = 'P';
    iconCats['library'] = 'L';
    iconCats['bike'] = 'R';
    iconCats['building'] = 'H';
    iconCats['gatehouse'] = 'G';

    if (iconCats[category])
    {
        this.icon = new GIcon();
        this.icon.image = "img/flags/" +iconCats[category]+ ".gif";
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
    else
    {
        this.icon = new GIcon(G_DEFAULT_ICON);
        this.iconAnchor = new GPoint(5, 34);
        this.infoWindowAnchor = new GPoint(5, 2);
    }
};

/*********************************NOTES****************************
***** var uloc = new UWLocation(code, map, lat, lng, name, category)
***** loc.init() - Creates new marker
**********************************************************************/
function UWLocation(code, map, lat, lng, name, cat)
{
    // Public Class Properties
    this.code = code;
    this.name = name;
    this.category = cat;
    this.lat = parseFloat(lat);
    this.lng = parseFloat(lng);
    this.point = new GLatLng(this.lat,this.lng);
    this.url = 'info/window.php?cat='+ this.category +'&code='+ this.code;
    this.marker = null;
    this.buildingIcon = null;
    this.html = '<p>Loading..</p>';
    this.cssid = 'custom_info_window_red';
    this.event1 = null;
    this.event2 = null;

    this.init = function() 
    {
        self = this;
        
        this.buildingIcon = new UWIcon(this.category);
        // GMarker does not work if you assign it right away
        // Need to store it in a temp variable and the load when complete
        this.marker = new GMarker(this.point,this.buildingIcon.icon);
        this.event1 = GEvent.addListener(this.marker, 'click', function(){
            // this.openInfoWindow('hi');
            this.openExtInfoWindow(map,self.cssid,self.html,
                {
                    ajaxUrl:self.url,
                    beakOffset:3,
                    paddingX:25,
                    paddingY:25
                }
            );
        });
        this.event2 = GEvent.addListener(this.marker, 'dblclick', function(){
            try {
                this.closeExtInfoWindow(map);
                map.closeExtInfoWindow();
            }
            catch (ex)
            {
                //Hope it works
            }
        });
        map.addOverlay(this.marker);
    }
    this.openw = function()
    {
        //this.marker.openInfoWindow('hi');
        this.marker.openExtInfoWindow(map,this.cssid,this.html,
           {
               ajaxUrl:this.url,
               beakOffset:3,
               paddingX:25,
               paddingY:25
           }
        );
    }
    this.center = function(z)
    {
       map.setCenter(this.point,z);
    }
    this.destroy = function()
    {
        GEvent.removeListener(this.event1);
        GEvent.removeListener(this.event2);
        map.removeOverlay(this.marker);
    }
};

/*********************************NOTES****************************
***** var ulocset = new UWLocationSet(map);
***** ulocset.load(xmlDoc);
***** ulocset.show(category);
***** ulocset.hide(category);
***** ulocset.search(category,strQuery)
***** ulocset.locate(point);
**********************************************************************/
function UWLocationSet(map)
{
    this.result = null;
    this.cat = new Array();

    // All Known Categories - statically
    // Generated for purformance
    this.cat['food'] = new Array();
    this.cat['landmarks'] = new Array();
    this.cat['computing'] = new Array();
    this.cat['library'] = new Array();
    this.cat['building'] = new Array();
    this.cat['gatehouse'] = new Array();

    this.load = function(xmlDoc)
    {
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
        for (var i=0; i<markers.length; i++)
        {
            // obtain the attribues of each marker
            var lat = markers[i].getAttribute("lat");
            var lng = markers[i].getAttribute("lng");

            // Rest of the information related to the marker
            var code = markers[i].getAttribute("code");
            var name = markers[i].getAttribute("name");
            var cat = markers[i].getAttribute("category");

            var uloc = new UWLocation(code, map, lat, lng, name, cat);
            // Load everything from the sheet, prepare other
            // Categories to load from sidebar box click
            if (cat != 'building')
            {
                uloc.init();
                uloc.marker.hide();
            } 

            try {
                this.cat[cat].push(uloc);
            }
            catch(ex) {
                GLog.write('Error: Category '+ cat +' Does NOT Exist');
            }
        }
    }
    this.show = function(c)
    {
        for (var i=0; i<this.cat[c].length; i++)
        {
            if (this.cat[c][i].marker)
            {
                this.cat[c][i].marker.show();
            }
            else
            {
                this.cat[c][i].init();
            }
        }
        cmap.center(16);
    }
    this.hide = function(c)
    {
        for (var i=0; i<this.cat[c].length; i++)
        {
            this.cat[c][i].marker.hide();
        }
    }
    this.clear = function()
    {
        // Only care about clearing buildings
        // Other categories are handled seperate
        var c = 'building';
        for (var i=0; i<this.cat[c].length; i++)
        {
            if (this.cat[c][i].marker)
                this.cat[c][i].destroy();
        }
    }
    this.search = function(c,strQuery)
    {
        // If we have a result, it's because
        // Someone already clicked
        if (this.result)
            this.cat[c][this.result].destroy();
        // Verify there's something exists
        for (var i=0; i<this.cat[c].length; i++)
        {
            if (this.cat[c][i].name.toLowerCase() == strQuery.toLowerCase())
            {
                this.cat[c][i].init();
                this.cat[c][i].center(17);
                this.cat[c][i].openw();
                this.result = i;
            }
        }
    }
    this.locate = function(point)
    {
        if (point)
        {
            var maxXrange = 0.0015; //degrees lon.
            var maxYrange = 0.001; //degrees lat.
            var minimumdist = 1000; //1 kilometer
            var bL = null;
    
            // If we have a result, it's because
            // Someone already clicked
            var c = 'building';
            if (this.result)
                this.cat[c][this.result].destroy();
            for (var i=0; i<this.cat[c].length; i++)
            {
                var candidate = this.cat[c][i].point;
        
                if ((Math.abs(point.x - candidate.x) < maxXrange) &&
                (Math.abs(point.y - candidate.y) < maxYrange))
                {
                    var candidatedist = candidate.distanceFrom(point);
                    if (candidatedist < minimumdist)
                    {
                        minimumdist = candidatedist;
                        bL = i;
                    }
                }
            }
            if (bL)
            {
                this.cat[c][bL].init();
                this.cat[c][bL].center(17);
                this.cat[c][bL].openw();
                this.result = bL;
            }
        }
    }
};

/*********************************NOTES****************************
***** var UMap = new UWCampusMap();
***** UWMap.init();
***** UWMap.overlay();
***** UWMap.center()
**********************************************************************/
function UWCampusMap()
{
    this.opacity = 1.0; // 1.0 is solid, anything less and we can see if the map lines up
    this.name = "Campus";
    this.point = new GLatLng(47.65565,-122.30817);
    this.tileLayers = [];
    this.campusmap = null;
    this.map = null;
    this.clicker = null;
    this.loc = null;

    // Setting the Normal Map as the initial will show it in the background if 
    // user goes out of range
    var tileLayer = new GTileLayer(null,12,17, {
            isPng:true,
            opacity:this.opacity
            });
    
    this.tilelayers = [G_NORMAL_MAP.getTileLayers()[0],tileLayer];
    //============================================================
    //http:code.google.com/p/cumberland/wiki/TilePyramiderAndGoogleMaps
    this.tilelayers[1].getTileUrl = function(point,zoom)
    {
        // // Define our tile boundaries
        // // Note: origin in google maps is top-left
        // var minLL = new GLatLng(47.6641,-122.32565); 
        // var maxLL = new GLatLng(47.6465,-122.2881);
  
        // // convert our lat/long values to world pixel coordinates
        // var currentProjection = G_NORMAL_MAP.getProjection();
        // var minPixelPt = currentProjection.fromLatLngToPixel(minLL, zoom);
        // var maxPixelPt = currentProjection.fromLatLngToPixel(maxLL, zoom);
  
        // // convert our world pixel coordinates to tile coordinates 
        // var minTileCoord = new GPoint();
        // minTileCoord.x = Math.floor(minPixelPt.x / 256);
        // minTileCoord.y = Math.floor(minPixelPt.y / 256);
  
        // var maxTileCoord = new GPoint();
        // maxTileCoord.x = Math.floor(maxPixelPt.x / 256);
        // maxTileCoord.y = Math.floor(maxPixelPt.y / 256);
  
        // // filter out any tile requests outside of our bounds
        // if (point.x < minTileCoord.x || 
        //     point.x > maxTileCoord.x ||
        //     point.y < minTileCoord.y ||
        //     point.y > maxTileCoord.y)
        // {
        //     return 'blanktile.png';
        // }

        return 'cutter/' + zoom + '_' + point.x + '_' + point.y + '.png';
    }

    this.campusmap = new GMapType(this.tilelayers, G_NORMAL_MAP.getProjection(), "Campus");
    this.campusmap.getMaximumResolution = function(latlng){ return 17;};
    this.campusmap.getMinimumResolution = function(latlng){ return 12;};

    // create the map
    var mapTypes = new Array(G_SATELLITE_MAP,G_HYBRID_MAP,this.campusmap);
    this.map = new GMap2(document.getElementById("map"), {mapTypes: mapTypes});
      
    this.map.enableScrollWheelZoom();
    this.map.addControl(new GLargeMapControl());
    this.map.addControl(new GMapTypeControl());

    this.ulocset = new UWLocationSet(this.map);

    this.overlay = function()
    {
        this.map.setMapType(this.campusmap);
    }
    this.center = function(z)
    {
        this.map.setCenter(this.point, z, this.campusmap);
    }
};
