$(document).ready(function(){
	$('.content-block').prepend('<div class="content-border-top"/>').append('<div class="clearfix"/>');

    resizeElements();
    $(window).resize(resizeElements);
});