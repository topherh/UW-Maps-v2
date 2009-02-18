var UWMaps = {
    map:null,
    point:new GLatLng(33.475, -111.975),
    zoomlevel:10,
    campuses:{},
    shiftclick:false
};

UWMaps.init = function() {
  UWMaps.map = new GMap2(document.getElementById('map'));
  UWMaps.map.addControl(new GLargeMapControl());
  UWMaps.map.addControl(new GMapTypeControl());
  UWMaps.map.addControl(new GScaleControl());
  UWMaps.map.addControl(new GOverviewMapControl(new GSize(100,100)));
  UWMaps.map.setCenter(UWMaps.point, UWMaps.zoomlevel);

  var c = UWMaps.directLink;

  UWMaps.campuses = {
    'tempe'        : new UWMapCampus('tempe', c),
    'west'         : new UWMapCampus('west', c),
    'polytechnic'  : new UWMapCampus('polytechnic', c),
    'downtown'     : new UWMapCampus('downtown', c),
    'researchpark' : new UWMapCampus('researchpark', c),
    'skysong'      : new UWMapCampus('skysong', c)
  }

  document.onkeydown = function(e) {
      if (!e) var e = window.event;
      if (e.keyCode == 16) UWMaps.shiftclick = true;
  }

  document.onkeyup = function(e) {
      if (!e) var e = window.event;
      if (e.keyCode == 16) UWMaps.shiftclick = false;
  }
}

UWMaps.reset = function() {
  UWMaps.blurAllCampuses();
  UWMaps.map.setCenter(UWMaps.point, UWMaps.zoomlevel);
  UWMaps.removeAllLandmarks();

  var m = document.getElementById('UWMaps-intro-message');
  m.style.display = 'block';
}

UWMaps.blurAllCampuses = function() {
  if (UWMaps.campuses) {
    for (c in UWMaps.campuses) {
      var campus = UWMaps.campuses[c];
      if (campus.isFocused) {
        campus.blur();
      }
    }
  }
}

UWMaps.removeAllLandmarks = function() {
  if (UWMaps.campuses) {
    for (c in UWMaps.campuses) {
      var campus = UWMaps.campuses[c];
      for (set in campus.markersets) {
        var ms = campus.markersets[set];
        for (lm in ms.landmarks) {
          ms.landmarks[lm].remove();
        }
      }
    }
  }
}

var UWMapCampus = function(id, callback) {
  var self = this;

  this.name = null;
  this.shortname = null;
  this.fullname = null;
  this.point = null;
  this.zoomlevel = null;
  this.url = null;
  this.imageurl = null;
  this.marker = null;
  this.overlays = null;
  this.markersets = null;
  this.navDiv = null;
  this.infoDiv = null;
  this.isFocused = false;
  this.clickhandler = null;

  GDownloadUrl('xml/' + id + '.xml', function(data, responseCode) {
    var xml = GXml.parse(data);
    var d = xml.documentElement; 
    if (d) {
      self.initialize(d);
    }
    if (callback) callback(self);
  });
}

UWMapCampus.prototype.initialize = function(x) {
  var self = this;

  this.name = x.getAttribute('name');
  this.shortname = x.getAttribute('shortname');
  this.fullname = x.getAttribute('fullname');
  this.point = new GLatLng(parseFloat(x.getAttribute('lat')), parseFloat(x.getAttribute('lon')));
  this.zoomlevel = parseInt(x.getAttribute('zoomlevel'));
  this.url = x.getAttribute('url');
  this.imageurl = x.getAttribute('imageurl');

  this.marker = new GMarker(this.point, {title:this.name});
  GEvent.addListener(this.marker, 'click', function() {self.getinfo();});
  GEvent.addListener(this.marker, 'dblclick', function() {self.focus();});
  UWMaps.map.addOverlay(this.marker);

  this.parseOverlays(x.getElementsByTagName('overlay'));
  this.parseMarkerSets(x.getElementsByTagName('markerset'));
  this.createNavigationDiv();

  var linkbar = document.getElementById('UWMaps-linkbar');
  linkbar.appendChild(this.navDiv);
}

UWMapCampus.prototype.parseOverlays = function(a) {
  this.overlays = new Object();
  if (a) {
    for (var i = 0; i < a.length; i++) {
      var overlay = new ASUMapOverlay(a[i], this);
      this.overlays[overlay.type] = overlay;
    }
  }
}

UWMapCampus.prototype.parseMarkerSets = function(a) {
  this.markersets = new Object();
  if (a) {
    for (var i = 0; i < a.length; i++) {
      var set = new ASUMapMarkerSet(this, a[i]);
      this.markersets[set.type] = set;
    }
  }
}

UWMapCampus.prototype.createNavigationDiv = function() {
  var self = this;

  this.navDiv = document.getElementById('UWMaps-campus-' + this.shortname);

  var linkbar = document.getElementById('campus-linkbar-' + this.shortname);
  for (x in this.markersets) {
    linkbar.appendChild(this.markersets[x].nav);
  }

  if (this.overlays['campus']) {
    var li = document.createElement('li');
    var cb = document.createElement('input');
    cb.id = 'UWMaps-overlay-checkbox-' + this.shortname;
    cb.className = 'UWMaps-overlay-checkbox';
    cb.setAttribute('type', 'checkbox');
    cb.defaultChecked = true;
    cb.onclick = function(e) {
        if (this.checked) {
          if (self.overlays['campus']) self.overlays['campus'].show();
        } else {
          if (self.overlays['campus']) self.overlays['campus'].hide();
        }
    }
    li.appendChild(cb);
    li.appendChild(document.createTextNode(' Show Campus Overlay'));
    linkbar.appendChild(li);
  }
}

UWMapCampus.prototype.selectLandmark = function(type, code) {
  var set = this.markersets[type];
  if (set) set.select(code);
}

UWMapCampus.prototype.getinfo = function() {
  var self = this;

  if (!this.infoDiv) {
    this.infoDiv = document.createElement('div');
    this.infoDiv.className = 'UWMaps-campus-info';

    var t = document.createElement('a');
    t.className = 'UWMaps-campus-info-name';
    t.href = '#';
    t.onclick = function(e) {self.focus();}
    t.appendChild(document.createTextNode(this.fullname));
    this.infoDiv.appendChild(t);

    t = document.createElement('a');
    t.className = 'UWMaps-campus-info-permalink';
    t.href = '?campus=' + escape(this.shortname);
    t.appendChild(document.createTextNode('Map Permalink (Copy/Paste Link)'));
    this.infoDiv.appendChild(t);

    if (this.imageurl) {
      t = document.createElement('img');
      t.className = 'UWMaps-campus-info-image';
      t.src = this.imageurl;
      this.infoDiv.appendChild(t);
    }

    t = document.createElement('a');
    t.className = 'UWMaps-campus-info-url';
    t.href = this.url;
    t.appendChild(document.createTextNode('Web site'));
    this.infoDiv.appendChild(t);
  }

  this.marker.openInfoWindow(this.infoDiv);
}

UWMapCampus.prototype.focus = function(callback) {
  var self = this;

  var m = document.getElementById('UWMaps-intro-message');
  m.style.display = 'none';

  UWMaps.map.closeInfoWindow();
  UWMaps.blurAllCampuses();
  this.isFocused = true;
  UWMaps.map.setMapType(G_NORMAL_MAP);
  UWMaps.map.setCenter(this.point, this.zoomlevel);
  UWMaps.map.removeOverlay(this.marker);

  if (this.overlays['campus']) {
    this.overlays['campus'].show();
    //document.getElementById('UWMaps-legend').style.display = 'block';
  }

  this.navDiv.style.display = 'block';

  if (!this.clickhandler) {
    this.clickhandler = GEvent.addListener(UWMaps.map, 'click', function(overlay, point) {
        if (point) { //background clicked
          if (UWMaps.shiftclick) {
            UWMaps.shiftclick = false;
            var lm = new ASUMapUserLandmark(point, self.shortname);
            lm.focus();
          } else {
            self.findClosestLandmark(point);
          }
        }
    });
  }

  if (callback) callback(this);
}

UWMapCampus.prototype.blur = function() {
  if (this.clickhandler) {
    GEvent.removeListener(this.clickhandler);
    this.clickhandler = null;
  }

  this.navDiv.style.display = 'none';

  //document.getElementById('UWMaps-legend').style.display = 'none';
  if (this.overlays['campus']) { this.overlays['campus'].hide(); }

  UWMaps.map.addOverlay(this.marker);

  this.isFocused = false;
}

UWMapCampus.prototype.findClosestLandmark = function(point) {
  var maxXrange = 0.0015; //degrees lon.
  var maxYrange = 0.001; //degrees lat.
  var minimumdist = 1000; //1 kilometer
  var bestLandmark = null;

  for (i in this.markersets) {
    var set = this.markersets[i];
    for (j in set.landmarks) {
      var lm = set.landmarks[j];
      var candidate = lm.point;
      if ((Math.abs(point.x - candidate.x) < maxXrange) &&
          (Math.abs(point.y - candidate.y) < maxYrange)) {
        var candidatedist = candidate.distanceFrom(point);
        if (candidatedist < minimumdist) {
          minimumdist = candidatedist;
          bestLandmark = lm;
        }
      }
    }
  }
  if (bestLandmark) bestLandmark.focus();
}

var ASUMapMarkerSet = function(campus, xmlMarkerSet) {
  var self = this;
  this.parent = campus;
  this.name = xmlMarkerSet.getAttribute('name');
  this.type = xmlMarkerSet.getAttribute('type');
  this.src = xmlMarkerSet.getAttribute('src');
  this.popup = new UWMapsPopup(this.type);
  this.selectbox = createSelectBox();
  this.nav = createNavLI();
  this.landmarks = parseLandmarks(xmlMarkerSet.getElementsByTagName('marker'));

  function createSelectBox() {
    var sb = document.createElement('select');
    sb.setAttribute('size', '1');
    sb.onchange = function(e) {
        var val = this.options[this.options.selectedIndex].value;
        if (val && val != '---Find---') {
          var opts = self.landmarks;
          if (opts && opts[val]) {opts[val].focus();}
        }
    }
    var opt = document.createElement('option');
    opt.appendChild(document.createTextNode('---Find---'));
    sb.appendChild(opt);

    return sb;
  }

  function createNavLI() {
    var li = document.createElement('li');
    li.id = self.parent.shortname + '-' + self.type + '-map';

    var span = document.createElement('span');
    span.className = 'UWMaps-linkbar-markerset-text';
    span.appendChild(document.createTextNode(self.name + ': '));

    li.appendChild(span);
    li.appendChild(self.selectbox);

    var pick = document.createElement('a');
    pick.href = '#';
    pick.onclick = function(e) {if (self.popup) self.popup.focus();}

    span = document.createElement('span');
    span.className = 'UWMaps-popup-link';
    span.appendChild(document.createTextNode('Search by name'));
    pick.appendChild(span);
    li.appendChild(pick);

    return li;
  }

  function parseLandmarks(a) {
    var hash = new Object();
    if (a) {
      for (var i = 0; i < a.length; i++) {
        var m = new ASUMapLandmark(a[i], self.type, self.parent);
        hash[m.code] = m;

        var opt = document.createElement('option');
        opt.value = m.code;
        opt.appendChild(document.createTextNode(m.code));
        self.selectbox.appendChild(opt);

        self.popup.addoption(m);
      }
    }
    return hash;
  }

  this.select = function(code) {
    for (var i = 0; i < self.selectbox.length; i++) {
      var opt = self.selectbox.options[i].value;
      if (opt == code) {
        self.selectbox.selectedIndex = i;
        self.selectbox.onchange();
        break;
      }
    }
  }

}

var ASUMapLandmark = function(xmlMarker, type, campus) {
  var self = this;
  this.campus = campus;
  this.name = xmlMarker.getAttribute('name');
  this.code = xmlMarker.getAttribute('code');
  this.type = type;
  this.imageurl = xmlMarker.getAttribute('imageurl');
  this.toururl = xmlMarker.getAttribute('toururl');
  this.point = new GLatLng(parseFloat(xmlMarker.getAttribute('lat')), parseFloat(xmlMarker.getAttribute('lon')));
  this.marker = createMarker();
  this.infoDiv = null;

  function createMarker() {
    var gmarker = new GMarker(self.point);
    GEvent.addListener(gmarker, 'click', function() {self.getinfo();});
    GEvent.addListener(gmarker, 'dblclick', function() {self.remove();});
    return gmarker;
  }

  this.getinfo = function() {
    if (!self.infoDiv) {
      self.infoDiv = document.createElement('div');
      self.infoDiv.className = 'UWMaps-landmark-info';

      if (self.imageurl) {
        var i = document.createElement('img');
        i.className = 'UWMaps-landmark-info-image';
        i.src = self.imageurl;
        self.infoDiv.appendChild(i);
      }

      var t = document.createElement('span');
      t.className = 'UWMaps-landmark-info-code';
      t.appendChild(document.createTextNode(self.code));
      self.infoDiv.appendChild(t);

      if (self.name != self.code) {
        t = document.createElement('span');
        t.className = 'UWMaps-landmark-info-name';
        t.appendChild(document.createTextNode(self.name));
        self.infoDiv.appendChild(t);
      }

      t = document.createElement('a');
      t.className = 'UWMaps-landmark-info-permalink';
      t.href = '?campus=' + escape(campus.shortname) + '&' + escape(self.type) + '=' + escape(self.code);
      t.appendChild(document.createTextNode('Permalink (Copy/Paste Link)'));
      self.infoDiv.appendChild(t);

      if (self.toururl) {
        t = document.createElement('a');
        t.className = 'UWMaps-landmark-info-url';
        t.href = self.toururl;
        t.appendChild(document.createTextNode('Campus Tour site'));
        self.infoDiv.appendChild(t);
      }

      t = document.createElement('a');
      t.className = 'UWMaps-landmark-info-removemarker';
      t.href = '#';
      t.appendChild(document.createTextNode('Remove marker'));
      t.onclick = function(e) {
          UWMaps.map.removeOverlay(self.marker);
          UWMaps.map.closeInfoWindow();
      }
      self.infoDiv.appendChild(t);
    }

    self.marker.openInfoWindow(self.infoDiv);
  }

  this.focus = function(callback) {
    UWMaps.map.removeOverlay(self.marker);
    UWMaps.map.addOverlay(self.marker);
    self.getinfo();

    if (callback) {callback(self.campus);}
  }

  this.remove = function() {
    UWMaps.map.closeInfoWindow();
    UWMaps.map.removeOverlay(self.marker);
  }
}

var ASUMapUserLandmark = function(point, campusshortname, text) {
  var self = this;
  this.id = '[' + point.x + ',' + point.y + ']';
  this.campusshortname = campusshortname;
  this.text = text;
  this.marker = createMarker(point);
  this.infoDiv = null;

  function createMarker(p) {
    var gmarker = new GMarker(p,
        {icon:new GIcon(G_DEFAULT_ICON, 'images/sparkymarker.png'), draggable:true, bouncy:true}
    );
    GEvent.addListener(gmarker, 'click', function() {self.getinfo();});
    GEvent.addListener(gmarker, 'dragend', function() {self.sethref();});
    return gmarker;
  }

  this.settext = function() {
    var elm = document.getElementById('UWMaps-userlandmark-info-text-' + self.id);
    while (elm.childNodes.length) elm.removeChild(elm.childNodes[0]);
    elm.appendChild(self.gettext());
  }

  this.gettext = function() {
    var s = document.createElement('span');
    s.appendChild(document.createTextNode(self.text));
    return s;
  }

  this.sethref = function() {
    var elm = document.getElementById('UWMaps-userlandmark-info-permalink-' + self.id);
    if (elm) elm.href = self.gethref();
  }

  this.gethref = function() {
    var spot = self.marker.getPoint();
    var href = '?';
    if (self.campusshortname && UWMaps.campuses[self.campusshortname]) {
      href += 'campus=' + escape(self.campusshortname) + '&';
    }
    href += 'lon=' + spot.x + '&lat=' + spot.y
    if (self.text) {
      href += '&info=' + escape(self.text);
    }
    return href;
  }

  this.getinfo = function() {
    if (!self.infoDiv) {
      self.infoDiv = document.createElement('div');
      self.infoDiv.className = 'UWMaps-userlandmark-info';

      var d = document.createElement('div');
      d.id = 'UWMaps-userlandmark-info-text-' + self.id;
      d.className = 'UWMaps-userlandmark-info-text';

      if (self.text) {
        d.appendChild(self.gettext());
      } else {
        var s = document.createElement('input');
        s.setAttribute('type', 'text');
        s.setAttribute('size', '15');
        s.onkeyup = function(e) { 
            if (!e) var e = window.event;
            if (e.keyCode == 13) { //enter
              self.text = this.value;
              self.settext();
              self.sethref();
            }
        }
        d.appendChild(s);
      }
      self.infoDiv.appendChild(d);

      var t = document.createElement('a');
      t.id = 'UWMaps-userlandmark-info-permalink-' + self.id;
      t.className = 'UWMaps-userlandmark-info-permalink';
      t.href = self.gethref();
      t.appendChild(document.createTextNode('Map Permalink (Copy/Paste Link)'));
      self.infoDiv.appendChild(t);

      t = document.createElement('a');
      t.className = 'UWMaps-userlandmark-info-removemarker';
      t.href = '#';
      t.appendChild(document.createTextNode('Remove marker'));
      t.onclick = function(e) {self.remove();}
      self.infoDiv.appendChild(t);

      self.marker.openInfoWindow(self.infoDiv);

    } else {
      self.marker.openInfoWindow(self.infoDiv);
      self.sethref();
    }
  }

  this.focus = function(callback) {
    UWMaps.map.removeOverlay(self.marker);
    UWMaps.map.addOverlay(self.marker);
    self.getinfo();

    if (callback) {callback(self.campus);}
  }

  this.remove = function() {
    UWMaps.map.closeInfoWindow();
    UWMaps.map.removeOverlay(self.marker);
  }
}

var ASUMapOverlay = function(xmlOverlay, campus) {
  var self = this;
  this.type = xmlOverlay.getAttribute('type');
  this.tilelayers = new Object();
  this.visible = false;

  var a = xmlOverlay.getElementsByTagName('tilelayer');
  if (a) {
    for (var i = 0; i < a.length; i++) {
      var layer = new Object();
      layer['zoomlevel'] = a[i].getAttribute('zoomlevel');
      layer['xmin'] = a[i].getAttribute('xmin');
      layer['xmax'] = a[i].getAttribute('xmax');
      layer['ymin'] = a[i].getAttribute('ymin');
      layer['ymax'] = a[i].getAttribute('ymax');

      this.tilelayers[layer['zoomlevel']] = layer;
    }
  }

  var tl = new GTileLayer(new GCopyrightCollection(), 0, 17);
  tl.getTileUrl = function(tile, zoom) {
    var layer = self.tilelayers[zoom];
    if (layer && tile.x >= layer['xmin'] && tile.x <= layer['xmax'] &&
                 tile.y >= layer['ymin'] && tile.y <= layer['ymax']) {
      return 'tiles/' + campus.shortname + '/' + zoom + '/' + tile.x + '_' + tile.y + '_' + zoom + '.gif';
    } else {
      return 'images/transparent.gif';
    }
  }
  GEvent.addListener(UWMaps.map, 'maptypechanged', function() {
    if (campus.isFocused) {
      var t = UWMaps.map.getCurrentMapType();
      if (t == G_SATELLITE_MAP || t == G_HYBRID_MAP) {
        self.hide();
      } else {
        self.show();
      }
    }
  });

  this.overlay = new GTileLayerOverlay(tl);

  this.show = function() {
    if (!this.visible) UWMaps.map.addOverlay(self.overlay);
    this.visible = true;

    var cb = document.getElementById('UWMaps-overlay-checkbox-' + campus.shortname);
    if (cb) cb.checked = true;
  }

  this.hide = function() {
    if (this.visible) UWMaps.map.removeOverlay(self.overlay);
    this.visible = false;

    var cb = document.getElementById('UWMaps-overlay-checkbox-' + campus.shortname);
    if (cb) cb.checked = false;
  }
}

var UWMapsPopup = function(type) {
  var self = this;
  this.type = type;
  this.dirty = true;
  this.options = new Array();

  this.node = null;
  this.list = null;

  this.node = document.createElement('div');
  this.node.className = 'UWMaps-popup UWMaps-popup-inactive';
  this.node.style.opacity = 0.97;
  this.node.style.MozOpacity = 0.97;
  this.node.style.KHTMLOpacity = 0.97;
  this.node.style.filter = 'alpha(opacity:97)';
  this.node.appendChild(createSearchBar());

  this.list = document.createElement('div');
  this.list.className = 'UWMaps-popup-list';
  this.node.appendChild(this.list);

  var d = document.body ? document.body : document.documentElement;
  d.appendChild(this.node);

  function createSearchBar() {
    var t = document.createElement('div');
    t.className = 'UWMaps-popup-searchbar';

    var a = document.createElement('a');
    a.className = 'UWMaps-popup-closebutton';
    a.href = '#';
    a.onclick = function(e) { self.blur(); }
    a.appendChild(document.createTextNode('Close'));
    t.appendChild(a);

    t.appendChild(document.createTextNode('Search: '));

    var s = document.createElement('input');
    s.setAttribute('type', 'text');
    s.setAttribute('size', '60');
    s.onkeyup = function(e) { self.search(this.value); }
    t.appendChild(s);

    return t;
  }

  function createBaseBar() {
    var t = document.createElement('div');
    t.className = 'UWMaps-popup-basebar';
    var a = document.createElement('a');
    a.className = 'UWMaps-popup-bottomclosebutton';
    a.href = '#';
    a.onclick = function(e) { self.blur(); }
    a.appendChild(document.createTextNode('Close'));
    t.appendChild(a);

    return t;
  }

  this.addoption = function(landmark) {
    self.options.push(new UWMapsPopupOption(self, landmark));
    self.dirty = true;
  }

  this.focus = function() {
    if (self.dirty) {
      while (self.list.childNodes.length) self.list.removeChild(self.list.childNodes[0]);
      if (self.options.length > 0) {
        self.options.sort(self.sort);
        for (var i = 0; i < self.options.length; i++) {
          self.list.appendChild(self.options[i].node);
        }
      } else {
        self.list.appendChild(document.createTextNode('There are no landmarks defined for this type.'));
      }
      self.dirty = false;
    }
    self.node.className = 'UWMaps-popup UWMaps-popup-active';
  }

  this.blur = function() {
    self.node.className = 'UWMaps-popup UWMaps-popup-inactive';
  }

  this.sort = function(a, b) {
    return (a.name > b.name ? 1 : -1);
  }

  this.search = function(value) {
    if (value) {
      var text = value.toLowerCase();
      for (var i = 0; i < self.options.length; i++) {
        if (self.options[i].matches(text)) {
          self.options[i].show();
        } else {
          self.options[i].hide();
        }
      }
    } else {
      for (var i = 0; i < self.options.length; i++) {
        self.options[i].show();
      }
    }
  }

}

var UWMapsPopupOption = function(parent, landmark) {
  var self = this;
  this.name = landmark.name.toLowerCase();
  this.node = createOption(landmark);

  function createOption(lm) {
    var a = document.createElement('a');
    a.className = 'UWMaps-popup-option';
    a.href = '#';
    a.onclick = function(e) {
        lm.campus.selectLandmark(lm.type, lm.code);
        parent.blur();
    }
    a.appendChild(document.createTextNode(lm.name + (lm.name != lm.code ? ' (' + lm.code + ')' : '')));

    return a;
  }

  this.matches = function(text) {
    return (self.name.match(text));
  }

  this.show = function() {
    self.node.className = 'UWMaps-popup-option';
  }

  this.hide = function() {
    self.node.className = 'UWMaps-popup-option UWMaps-popup-option-hidden';
  }
}

var ASUMapQuery = new Object();
ASUMapQuery.parse = function() {
  var query = document.location.search.substring(1);
  var parms = query.split('&');
  for (var i = 0; i < parms.length; i++) {
    var pos = parms[i].indexOf('=');
    if (pos > 0) ASUMapQuery[parms[i].substring(0, pos)] = unescape(parms[i].substring(pos+1));
  }
}
ASUMapQuery.parse();

UWMaps.directLink = function(campus) {
  var c = ASUMapQuery['campus'];
  if (c == campus.shortname) {
    campus.focus(UWMaps.directLandmarkLink);

    var lat = ASUMapQuery['lat'];
    var lon = ASUMapQuery['lon'];

    if (lat && lon) {
      var p = new GLatLng(parseFloat(lat), parseFloat(lon));
      var lm = new ASUMapUserLandmark(p, c, (ASUMapQuery['info'] ? ASUMapQuery['info'] : null));
      lm.focus();
    }
  }
}

UWMaps.directLandmarkLink = function(campus) {
  var b = ASUMapQuery['building'];
  var m = campus.markersets['building'];
  if (b && m && m.landmarks && m.landmarks[b]) {
    m.landmarks[b].focus();
  }

  var d = ASUMapQuery['decalparking'];
  var m = campus.markersets['decalparking'];
  if (d && m && m.landmarks && m.landmarks[d]) {
    m.landmarks[d].focus();
  }

  var v = ASUMapQuery['visitorparking'];
  var m = campus.markersets['visitorparking'];
  if (v && m && m.landmarks && m.landmarks[v]) {
    m.landmarks[v].focus();
  }
}

