/* <![CDATA[ */
	jQuery(document).ready(function(){
		jQuery('#epanel-content,#epanel-content > div').tabs({ fx: { opacity: 'toggle' }, selected: 0 });
		jQuery(".box-description").click(function(){
			var descheading = jQuery(this).prev("h3").html();
			var desctext = jQuery(this).next(".box-descr").html();
			
			jQuery('body').append("<div id='custom-lbox'><div class='shadow'></div><div class='box-desc'><div class='box-desc-top'></div><div class='box-desc-content'><h3>"+descheading+"</h3>"+desctext+"<div class='lightboxclose'></div> </div> <div class='box-desc-bottom'></div>	</div></div>");
			jQuery(".shadow").animate({ opacity: "show" }, "fast").fadeTo("fast", 0.75);
			jQuery('.lightboxclose').click(function(){
				jQuery(".shadow").animate({ opacity: "hide" }, "fast", function(){jQuery("#custom-lbox").remove();});	
			});
		});
		
		jQuery(".defaults-button").click(function() {
		jQuery(".defaults-hover").animate({opacity: "show", top: "-240"}, "fast");
			});
		jQuery(".no").click(function() {
		jQuery(".defaults-hover").animate({opacity: "hide", top: "-300"}, "fast");
			});
		// ":not([safari])" is desirable but not necessary selector
		jQuery('input:checkbox:not([safari])').checkbox();
		jQuery('input[safari]:checkbox').checkbox({cls:'jquery-safari-checkbox'});
		jQuery('input:radio').checkbox();		
	});
/* ]]> */