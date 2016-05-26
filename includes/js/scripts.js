function resizeElements(){
	// Find out the width of the page
	var	wrapperWidth = $('body').width() < 1040 ? 640 : 960;

	// Set other elements accordingly
	$('.wrapper').width(wrapperWidth);
	$('.thumb-nav li').width = (wrapperWidth/3) - 20;
	$('.footer').width(wrapperWidth + 20);
	$('canvas#background').width('60%').height('100%');
}