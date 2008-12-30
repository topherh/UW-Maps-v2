// $Id: home2006.js,v 1.8 2006/07/10 18:46:32 fmf Exp $
if (typeof FlyLyr != 'undefined' && FlyLyr.on) {
	flyDefs ( {
		position: "CSS",
		showImage: function (img, lyr) {
			img.className = 'show';
		},
		hideImage: function (img, lyr) {
			img.className = '';
		}
	} );
}

function bodystart () {
	if ('undefined' != typeof document.getElementById &&
			! document.flyout_disable)
		document.body.className = 'script';
}

function ie_newsbar_li_width () {
	var o = document.getElementById ('iesizer');
	return document.body.clientWidth >
				(o.offsetHeight || o.height) *30 * 3 ? '30em' : '33%';
}

var searchplaceholder = ' Search the University of Washington';

function searchfocus () {
	if (document.body.className == 'noscript')
		return;
	var inp = document.uw_searchbox;
	if (inp.className == 'empty') {
		inp.value = '';
		inp.className = '';
	}
}

function searchblur () {
	if (document.body.className == 'noscript')
		return;
	var inp = document.uw_searchbox;
	if (inp.value == '' || inp.value == searchplaceholder) {
		inp.className = 'empty';
		inp.value = searchplaceholder;
	}
}

function searchcheck () {
	if (document.body.className == 'noscript')
		return true;
	var inp = document.uw_searchbox;
	return inp.className != 'empty' && inp.value != '';
}

function webkitsearch () {
	if (document.body.className == 'noscript')
		return;
	// we can't set .placeholder, etc., so emit necessary html
	var awkpos = navigator.userAgent.indexOf ('AppleWebKit');
	if (awkpos < 0 ||
			parseInt (navigator.userAgent.substr (awkpos + 12)) < 400)
		return
	var inp = document.getElementById ('stext');
	if (! inp)
		return;
	var div = inp.parentNode;
	div.removeChild (inp);
	document.write ('<input id="stext" name="q" type="search" ' +
					'size="33" placeholder="' + searchplaceholder +
					'" autosave="UWash Home Search" results="5"/' + '>');
}

function searchsetup () {
	if (document.body.className == 'noscript')
		return;
	document.getElementById ('submit').value ='Go';
	var inp = document.uw_searchbox = document.getElementById ('stext');
	if (inp.type == 'text')
		searchblur ();
}

function visitimage () {
	if ('undefined' != document.getElementById) {
		var img = document.getElementById ('visitimg');
		var imgwrap = img.parentNode;
		imgwrap.removeChild (img);
		var imgobj = document.uw_visitimgarr[Math.floor (Math.random () *
												document.uw_visitimgarr.length)];
		var imgbound = imgobj.indexOf (' ');
		var imgalt = imgobj.substr (imgbound + 1);
		document.write ('<img src="home/graphics/home2006/' +
							imgobj.substr (0, imgbound) + '" id="visitimg" ' +
							'style="width:11.25em;height:6.5625em" ' +
							'alt="' + imgalt + '" title="' + imgalt + '"/' + '>');
	}
}

function quickselimg () {
	if (! document.uw_quickimgarr || ! document.uw_quickimgarr.length)
		return;
	var n = Math.floor (Math.random () *
		(document.uw_quickimgarr.length + 1)) - 1;
	if (n < 0)
		return;
	var imgobj = document.uw_quickimgarr[n];
	var img = document.getElementById ('quickimg');
	if (! img)
		return;
	var imglink = img.parentNode;
	imglink.parentNode.removeChild (imglink);
	document.write ('<a href="' + imgobj.url +
				'"><img id="quickimg" src="' + imgobj.img +
				'" title="" alt="' + imgobj.alt + '" /><' +
				'/a>');
}
