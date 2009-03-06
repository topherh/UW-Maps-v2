var myvar;
function menuinit() {
        document.getElementById('m1').style.display = 'none';
        document.getElementById('m2').style.display = 'none';

	document.getElementById('pm1').src = 'img/expanders/plus.gif';
	document.getElementById('pm2').src = 'img/expanders/plus.gif';

}
function menuexpand (i) {
        menuinit();
        if (myvar == i) {
		document.getElementById('p' + i).src = 'img/expanders/plus.gif';
		document.getElementById(i).style.display = 'none';
		myvar = '';
	}
        else {
		document.getElementById('p' + i).src = 'img/expanders/minus.gif';
		document.getElementById(i).style.display = 'block';
		myvar = i;
	}
}
