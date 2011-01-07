<?php global $shortname; ?>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.cycle.all.min.js"></script> 
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.easing.1.3.js"></script>	
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/superfish.js"></script>	
	
	<script type="text/javascript">
	//<![CDATA[
	
		jQuery.noConflict();
	
		jQuery('ul.superfish').superfish({ 
			delay:       200,                            // one second delay on mouseout 
			animation:   {'marginLeft':'0px',opacity:'show',height:'show'},  // fade-in and slide-down animation 
			speed:       'fast',                          // faster animation speed 
			autoArrows:  true,                           // disable generation of arrow mark-up 
			onBeforeShow: function(){ this.css('marginLeft','20px'); },
			dropShadows: false                            // disable drop shadows 
		});
		
		jQuery('ul.nav > li > a.sf-with-ul').parent('li').addClass('sf-ul');
		
		<?php if (get_option($shortname.'_disable_toptier') == 'on') echo('jQuery("ul.nav > li > ul").prev("a").attr("href","#");'); ?>
		
		/* search form */
		
		var $searchform = jQuery('#header div#search-form');
		var $searchinput = $searchform.find("input#searchinput");
		var $searchvalue = $searchinput.val();
		
		$searchform.css("right","25px");
		
		jQuery("#header a#search-icon").click(function(){
			if ($searchform.filter(':hidden').length == 1)	
				$searchform.animate({"right": "-1", "opacity": "toggle"}, "slow")
			else
				$searchform.animate({"right": "25", "opacity": "toggle"}, "slow");
			return false;
		});
			
		$searchinput.focus(function(){
			if (jQuery(this).val() == $searchvalue) jQuery(this).val("");
		}).blur(function(){
			if (jQuery(this).val() == "") jQuery(this).val($searchvalue);
		});
		
		
		/* footer widgets improvements */
		
		var $footer_widget = jQuery("#footer .widget");
		
		if (!($footer_widget.length == 0)) {
			$footer_widget.each(function (index, domEle) {
				// domEle == this
				if ((index+1)%3 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
			});
		};
	
		
		<?php if (is_front_page() && get_option($shortname.'_featured')=='on') { ?>
		
			/* featured slider */
		
			var $featured_area = jQuery('#featured-slider'),
				$feature_thumb = jQuery('#featured-thumbs img'),
				$active_arrow = jQuery('div#active_item');
				ordernum = 1,
				pause_scroll = false,
				$slider_control = jQuery('#featured-thumbs'), //div#featured-thumbs
				$slider_control_tab = $feature_thumb.parent('a');
			
			if (!($featured_area.length == 0)) {
				$featured_area.cycle({
					timeout: 0,
					speed: 300,
					cleartypeNoBg: true,
					fx: '<?php echo(get_option($shortname.'_slider_effect')); ?>'
				});
			};
			
			<?php if (get_option($shortname.'_pause_hover') == 'on') { ?>			
				$featured_area.mouseover(function(){
					pause_scroll = true;
				}).mouseout(function(){
					pause_scroll = false;
				});
			<?php }; ?>

			function gonext(this_element){
				$slider_control.find("img.active").removeClass('active');
				this_element.find("img").addClass('active');
							
				$active_arrow.animate({"left": this_element.find("img").position().left+55}, "slow");
				
				ordernum = this_element.prevAll('a').length+1;
				$featured_area.cycle(ordernum - 1);
			};
			
			
			$slider_control_tab.click(function() {
				clearInterval(interval);
				gonext(jQuery(this));
				return false;
			});
			
			jQuery('a#prevlink, a#nextlink').click(function() {
				clearInterval(interval);
				
				if (jQuery(this).attr("id") === 'nextlink') {
								
					auto_number = $slider_control.find("img.active").parent().prevAll('a').length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
					
				} else {
					auto_number = $slider_control.find("img.active").parent().prevAll('a').length-1;
					if (auto_number === -1) auto_number = $slider_control_tab.length-1;
				};
				
				gonext($slider_control_tab.eq(auto_number));
				return false;
			});
			
			$feature_thumb.hover(function(){			
				$next_div = jQuery(this).parent('a').next('div');
				
				$next_div.css('bottom','106px')
				$next_div.css({'left':jQuery(this).position().left-10});
				
				jQuery(this).addClass('hover').fadeTo('fast',0.5);
				$next_div.animate({"bottom": "96px", "opacity": "toggle"}, "fast");
				
			},function(){
				jQuery(this).removeClass('hover').fadeTo('fast',1);
				$next_div.animate({"bottom": "106px", "opacity": "toggle"}, "fast");
			});
			
			
			var auto_number;
			var interval;
			
			$slider_control_tab.bind('autonext', function autonext(){
				if (!pause_scroll) gonext(jQuery(this)); 
				return false;
			});
			
			<?php if (get_option($shortname.'_slider_auto') == 'on') { ?>
				interval = setInterval(function () {
					auto_number = $slider_control.find("img.active").parent().prevAll('a').length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
					$slider_control_tab.eq(auto_number).trigger('autonext');
				}, <?php echo(get_option($shortname.'_slider_autospeed')); ?>);
			<?php }; ?>
			
		<?php }; ?>	
	//]]>	
	</script>