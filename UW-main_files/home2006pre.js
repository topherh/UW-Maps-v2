document.cssonly = 1;
document.flyout_disable = window.location.href.indexOf ('?nojavascript') > 0 ||
							! document.childNodes ||
							(navigator.userAgent.indexOf ('MSIE') > 0 &&
							navigator.userAgent.indexOf ('Mac') > 0 &&
							navigator.userAgent.indexOf ('Opera') < 0);
