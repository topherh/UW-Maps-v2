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
    this.html = "<b>"+name+"</b><p>"+address;
    this.category = category;
    this.marker;

    // Private Class Properties
    // This icon is a different shape, so we need our own settings       
    var buildingIcon = new GIcon();
    buildingIcon.image = "img/flags/H.gif";
    buildingIcon.shadow = "img/shadow.png";
    buildingIcon.iconSize = new GSize(32, 32);
    buildingIcon.shadowSize = new GSize(45, 33);
    buildingIcon.iconAnchor = new GPoint(5, 34);
    buildingIcon.infoWindowAnchor = new GPoint(5, 2);
    buildingIcon.infoShadowAnchor = new GPoint(14, 25);
    buildingIcon.transparent = "img/flags/.gif";
    buildingIcon.printImage = "img/flags/.gif";
    buildingIcon.mozPrintImage = "img/flags/.gif";

    // An array of GIcons, to make the selection easier
    var gicons = [];
    gicons["building"] = buildingIcon;

    this.marker = new GMarker(this.point,gicons[this.category]);
    // === Store the category and name info as a this.marker properties ===
    // // // this.marker.mycategory = this.category;
    // // // this.marker.myname = this.name;
    GEvent.addListener(this.marker, "click", function()
    {
        this.marker.openExtInfoWindow(
          map,
          "extInfoWindow_funkyBox", this.html,
          {beakOffset: 2}
        ); 
    });
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
            var option = document.createElement("option");
            option.text = name;
            option.value = name;
            buildList.appendChild(option);            
            
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
