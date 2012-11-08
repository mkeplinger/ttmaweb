var ventana_ancho = jQuery(window).width();
var ventana_alto = jQuery(window).height();

jQuery(document).ready(function(){

    jQuery("#closedesignbox").click(function(){

            jQuery("#designbox").css({'display':'none'});

    });
});

function showPosted(id){
    jQuery("#designbox").css({
		'display':'block',
		'height':ventana_alto
	});
}


