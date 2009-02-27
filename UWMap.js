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
***** var locset = new UWLocationSet(map, category);
***** locset.show;
***** locset.hide;
**********************************************************************/
// // function UWLocationSet()
// // {
    // // this.locations = new Array();

    // // this.init = function(map)
    // // {
        // // // Read the data
        // // GDownloadUrl("categories.xml", function(doc)
        // // {
            // // var xmlDoc = GXml.parse(doc);
            // // var markers = xmlDoc.documentElement.getElementsByTagName("marker");

            // // GLog.write('Original Marker Length: ' + markers.length);
            // // for (var i=0; i<markers.length; i++)
            // // {
                // // GLog.write('Original Marker: ' + markers[i].getAttribute("name"));
                // // GLog.write('Map Object: ' + map);
                // // var uloc = new UWLocation(i, map);
                // // Glog.write('Got here');
                // // // obtain the attribues of each marker
                // // uloc.lat = parseFloat(markers[i].getAttribute("lat"));
                // // uloc.lng = parseFloat(markers[i].getAttribute("lng"));

                // // uloc.name = markers[i].getAttribute("name");
                // // uloc.address = markers[i].getAttribute("address");
                // // uloc.category = markers[i].getAttribute("category");
                
                // // //  Calculated Properties
                // // uloc.point = new GLatLng(uloc.lat,uloc.lng); // This could be done smarter
                // // uloc.html = "<b>"+uloc.name+"</b><p>"+uloc.address; // Ditto
                // // // create the marker
                // // // This is the failure point for some strange reason
                // // GLog.write('Location Name: ' + uloc.name);
                // // GLog.write('Loop Number: ' + i);

                // // this.locations.append(uloc);
            // // }
            // // GLog.write('Object Marker Length: ' + uloc.locations.length);
        // // });
    // // }          
    // // // == shows all this.markers of a particular category, and ensures the checkbox is checked ==
    // // this.show = function(category)
    // // {
        // // var loc = this.locations;
        // // GLog.write('Length of Locations Array: ' + loc.length);
        // // for (var i=0; i<loc.length; i++)
        // // {
            // // if (loc[i].category == category)
            // // {
                // // loc[i].activate;
                // // loc[i].marker.show();
            // // }
        // // }
        // // // == check the checkbox ==
        // // document.getElementById(category+"box").checked = true;
    // // }
    // // // == hides all this.locations.markers of a particular category, and ensures the checkbox is cleared ==
    // // this.hide = function(map, category)
    // // {
        // // var loc = this.locations;
        // // for (var i=0; i<loc.length; i++)
        // // {
            // // if (loc[i].category == category)
            // // {
                // // loc[i].marker.hide();
            // // }
        // // }
        // // // == clear the checkbox ==
        // // document.getElementById(category+"box").checked = false;
        // // // == close the info window, in case its open on a marker that we just hid
        // // map.closeInfoWindow();
    // // }
// // };