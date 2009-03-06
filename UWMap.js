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
function UWLocation(id, map, lat, lng, name, address, category)
{
    // Public Class Properties
    this.id = id;
    this.lat = parseFloat(lat);
    this.lng = parseFloat(lng);
    this.point = new GLatLng(this.lat,this.lng);
    this.address = address;
    this.name = name;
    this.category = category;
    this.marker;
    this.buildingIcon = new GIcon();
    
    var html = "<b>" + this.name + "</b>" 
        + "<p>" + this.address + "</p>";

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
        mark.openExtInfoWindow(
          map,
          "extInfoWindow_funkyBox", html,
          {beakOffset: 2}
        ); 
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

    this.load = function(xmlDoc)
    {
        var buildList = document.getElementById("buildingList");
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
        for (var i=0; i<markers.length; i++)
        {
            // obtain the attribues of each marker
            var lat = markers[i].getAttribute("lat");
            var lng = markers[i].getAttribute("lng");

            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var category = markers[i].getAttribute("category");
            
            // Papulate Controls
            if (category == 'building')
            {
                var option = document.createElement("option");
                option.text = name;
                option.value = name;
                buildList.appendChild(option);            
            }
            
            var uloc = new UWLocation(i, map, lat, lng, name, address, category); // Line is Failing
            this.cat[category].push(uloc);
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
    this.search = function(map,category,data)
    {
        var arrLoc = this.cat[category];
        for (var i=0; i<arrLoc.length; i++)
        {
            // Clear all markers before we display another
            map.removeOverlay(arrLoc[i].marker);
            if (arrLoc[i].name.toLowerCase() == data.toLowerCase())
            {
                map.addOverlay(arrLoc[i].marker);
                arrLoc[i].marker.show();
                map.setCenter(new GLatLng(arrLoc[i].lat,arrLoc[i].lng), 17);
            }
        }
    }
};
