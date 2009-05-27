/****************************************************************
*** Program Name: UWMap
*** Author: Chris Heiland
*** Last Revision Date: February 23, 2009
*** Notes: Super Class for Google Map API
****************************************************************/

/*********************************NOTES****************************
***** var loc = new UWLocation(id, map, gicots);
***** loc.activate - Behavior does something useful
***** loc.show;
***** loc.hide;
**********************************************************************/
function UWLocation(id, map, lat, lng, name, address, category, desc)
{
    // Public Class Properties
    this.id = id;
    this.lat = parseFloat(lat);
    this.lng = parseFloat(lng);
    this.point = new GLatLng(this.lat,this.lng);
    this.address = address;
    this.name = name;
    this.category = category;
    this.desc = desc;
    this.marker;
    this.buildingIcon = new GIcon();
   
    var iconCats = new Array();
    iconCats['parking'] = 'G';
    iconCats['bus'] = 'B';
    iconCats['emergency'] = 'P';
    iconCats['atm'] = 'A';
    iconCats['libraries'] = 'L';
    iconCats['zip'] = 'Z';
    iconCats['wifi'] = 'W';
    iconCats['bike'] = 'R';
    iconCats['building'] = 'H';
    iconCats['gatehouse'] = 'G';

    // This icon is a different shape, so we need our own settings       
    this.buildingIcon.image = "img/flags/" + iconCats[this.category] + ".gif";
    this.buildingIcon.shadow = "img/shadow.png";
    this.buildingIcon.iconSize = new GSize(32, 32);
    this.buildingIcon.shadowSize = new GSize(45, 33);
    this.buildingIcon.iconAnchor = new GPoint(5, 34);
    this.buildingIcon.infoWindowAnchor = new GPoint(5, 2);
    this.buildingIcon.infoShadowAnchor = new GPoint(14, 25);
    this.buildingIcon.transparent = "img/flags/.gif";
    this.buildingIcon.printImage = "img/flags/.gif";
    this.buildingIcon.mozPrintImage = "img/flags/.gif";    
        
    // GMarker does not work if you assign it right away
    // Need to store it in a temp variable and the load when complete
    var mark = new GMarker(this.point,this.buildingIcon);
    GEvent.addListener(mark, "click", function()
    {
        var idiv = document.createElement('div');
        idiv.setAttribute('class','popupBox');
        idiv.innerHTML = desc;

        var p1 = document.createElement('p');
        var p2 = document.createElement('p');

        var input = document.createElement("INPUT");
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'Permalink');
        fieldValue = window.location + '?location=' + name;
        input.setAttribute('value', fieldValue);
        input.setAttribute('size', '50');

        var t = document.createElement('a');
        t.className = 'info-removemarker';
        t.href = '#';
        t.appendChild(document.createTextNode('Remove marker'));
        t.onclick = function(e) {
            map.removeOverlay(mark);
            map.closeInfoWindow();
        }
        p1.appendChild(t);
        p2.appendChild(input);
        idiv.appendChild(p1);
        idiv.appendChild(p2);
        mark.openInfoWindow(
            idiv
            );
    });

    GEvent.addListener(mark, 'dblclick', function()
    {
        map.closeInfoWindow();
        map.removeOverlay(mark);

    });

    this.marker = mark;
};

/*********************************NOTES****************************
***** var locset = new UWLocationSet(map);
***** locset.add(category,uloc);
***** locset.show(map,category);
***** locset.hide(map,category);
**********************************************************************/
function UWLocationSet(map)
{
    this.cat = new Array();
    
    this.cat['parking'] = new Array();
    this.cat['bus'] = new Array();
    this.cat['wifi'] = new Array();
    this.cat['zip'] = new Array();
    this.cat['bike'] = new Array();
    this.cat['atm'] = new Array();
    this.cat['libraries'] = new Array();
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
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var category = markers[i].getAttribute("category");
            var desc = markers[i].childNodes[0].nodeValue;
            //var desc = markers[i].getAttribute("desc");
		
            // Papulate Dropdown List
            if (category == 'building')
            {
                var option = new Option(name,name);
	        try {
		    buildList.add(option, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
		    buildList.add(option); // IE only
		}
            }

            var uloc = new UWLocation(i, map, lat, lng, name, address, category, desc); // Line is Failing
            try {
                this.cat[category].push(uloc);
            }
            catch(ex) {
                GLog.write('Error: Category Does NOT Exist');
            }
        }
    }
    this.show = function(map,category)
    {
        var arrLoc = this.cat[category];
        for (var i=0; i<arrLoc.length; i++)
        {
            map.addOverlay(arrLoc[i].marker);
            arrLoc[i].marker.show();
        }
        // == check the checkbox ==
        map.setCenter(new GLatLng(47.65565,-122.30817), 17);
        document.getElementById(category+"box").checked = true;
    }
    this.hide = function(map,category)
    {
        var arrLoc = this.cat[category];
        for (var i=0; i<arrLoc.length; i++)
        {
            arrLoc[i].marker.hide();
        }
        // == clear the checkbox ==
        document.getElementById(category+"box").checked = false;
        // == close the info window, in case its open on a marker that we just hid
        map.closeInfoWindow();
    }
    this.search = function(map,category,strQuery)
    {
        var arrLoc = this.cat[category];
        // map.closeInfoWindow();

        for (var i=0; i<arrLoc.length; i++)
        {
            // Clear all markers before we display another
            arrLoc[i].marker.hide();
            map.removeOverlay(arrLoc[i].marker);
            // GLog.write('Map Marker: '+arrLoc[i].name);
            // GLog.write(arrLoc[i].name.toLowerCase() + ' == ' + strQuery.toLowerCase());
            if (arrLoc[i].name.toLowerCase() == strQuery.toLowerCase())
            {
                map.addOverlay(arrLoc[i].marker);
                arrLoc[i].marker.show();
                map.setCenter(new GLatLng(arrLoc[i].lat,arrLoc[i].lng), 17);
            }
        }
    }
};

/*********************************NOTES****************************
***** var UMap = new UWCampusMap();
**********************************************************************/
// var UWCampusMap = function(map)
// {
    // this.opacity = 0.9; // 1.0 is solid, anything less and we can see if the map lines up
    // this.name = "Campus";
    // this.tileLayers = [];

    // // ============================================================
    // // http://code.google.com/p/cumberland/wiki/TilePyramiderAndGoogleMaps
    // function CustomGetTileUrl(point,zoom)
    // {
        // // We only have zoom at 17 - need to adjust as we get more slices
        // if (zoom < 12 || zoom > 17)
        // {
            // return 'blanktile.png';
        // }

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
            // point.x > maxTileCoord.x ||
            // point.y < minTileCoord.y ||
            // point.y > maxTileCoord.y)
        // {
            // return 'blanktile.png';
        // }
        // return 'cutter/' + zoom + '_' + point.x + '_' + point.y + '.png';
    // }
    
    // var tileLayer = new GTileLayer(null,12,19, {
        // isPng:true,
        // opacity:this.opacity
        // });
    
    // this.tileLayers = [G_NORMAL_MAP.getTileLayers()[0],tileLayer];
    // this.tilelayers[1].getTileUrl = CustomGetTileUrl;
// };
