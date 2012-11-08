/************************************************ 
*  jQuery iphoneSwitch plugin                   *
*                                               *
*  Author: Daniel LaBare                        *
*  Date:   2/4/2008                             *
************************************************/

jQuery.fn.iphoneSwitch = function(start_state, switched_on_callback, switched_off_callback, options) {

	var state = start_state == 'on' ? start_state : 'off';
	
	// define default settings
	var settings = {
		mouse_over: 'pointer',
		mouse_out:  'default',
		switch_on_container_path: 'iphone_switch_container_on.png',
		switch_off_container_path: 'iphone_switch_container_off.png',
		switch_path: 'iphone_switch.png',
		switch_height: 27,
		switch_width: 94
	};

	if(options) {
		jQuery.extend(settings, options);
	}

	// create the switch
	return this.each(function() {

		var container;
		var image;
		
		// make the container
		container = jQuery('<div class="iphone_switch_container" style="background-image:url('+settings.switch_path+'); background-position:'+(state == 'on' ? 0 : -53)+'px" src="'+(state == 'on' ? settings.switch_on_container_path : settings.switch_off_container_path)+'"  ></div>');
		
		// make the switch image based on starting state
		image = jQuery('<img class="iphone_switch" /></div>');

		// insert into placeholder
		jQuery(this).html(jQuery(container).html(jQuery(image)));



		// click handling
		jQuery(this).click(function() {

			if(state == 'on') {
				jQuery(this).find('.iphone_switch_container').animate({backgroundPosition: -53}, "slow", function() {
					jQuery(this).attr('src', settings.switch_off_container_path);
					switched_off_callback();
				});
				state = 'off';
			}
			else {
				jQuery(this).find('.iphone_switch_container').animate({backgroundPosition: 0}, "slow", function() {
					switched_on_callback();
				});
				jQuery(this).find('.iphone_switch_container').attr('src', settings.switch_on_container_path);
				state = 'on';
			}
		});		

	});
	
};


jQuery.fn.socialSwitch = function(start_state, imgSocial, switched_on_callback, switched_off_callback, options) {

	var state = start_state == 'on' ? start_state : 'off';

	// define default settings
	var settings = {
		mouse_over: 'pointer',
		mouse_out:  'default',
		switch_on_container_path: imgSocial,
		switch_off_container_path:imgSocial,
		switch_path: 'iphone_switch.png',
                switch_image: imgSocial,
		switch_height: 48,
		switch_width: 48
	};

	if(options) {
		jQuery.extend(settings, options);
	}

	// create the switch
	return this.each(function() {

		var container;
		var image;
                
		// make the container
		container = jQuery('<div class="social_switch_container" style="height:'+settings.switch_height+'px; width:'+settings.switch_width+'px; background-image:url('+settings.switch_path+settings.switch_image+'); background-repeat:none; background-position:'+(state == 'on' ? 0 : -48)+'px" ></div>');

		// make the switch image based on starting state
		image = jQuery('<img class="social_switch" /></div>');

                // insert into placeholder
		jQuery(this).html(jQuery(container).html(jQuery(image)));

		// click handling
		jQuery(this).click(function() {
			if(state == 'on') {
				jQuery(this).find('.social_switch_container').animate({backgroundPosition: -48}, "slow", function() {
					
					switched_off_callback();
				});
				state = 'off';
			}
			else {
				jQuery(this).find('.social_switch_container').animate({backgroundPosition: 0}, "slow", function() {
					switched_on_callback();
				});
				
				state = 'on';
			}
		});

	});

};