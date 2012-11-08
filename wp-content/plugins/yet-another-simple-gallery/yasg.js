(function($) {
	$.fn.yasg = function() {
		return this.each(function() {
			var box_id = this.id;
			var main_img = $('.main_img',this);
			var thumbs = $('.nav li a',this);
			thumbs.click(function(e) {
				thumbs.each(function() {
					$(this).parent().removeClass('current');
					$(this).parent().addClass('reg');
				});
				$(this).parent().removeClass('reg');
				$(this).parent().addClass('current');
				loader = yasg_path + 'images/ajax-loader.gif';
				new_url = $(this).attr('href');
				full_url = $(this).attr('title');
				main_img.fadeOut(500, function() {
					main_img.attr('src',loader);
				}).fadeIn(400,function () {
					new_img = new Image();
					new_img.src = new_url;
					new_img.onload = function(){
						main_img.fadeOut(500, function() {
							main_img.attr('src',new_url);
							main_img.parent().attr('href',full_url);
						}).fadeIn(400);
					};
				});
	            return false;
	        });
			var prev = $('.prev', this);
			var next = $('.next', this);
			var nav = $('.nav', this);
			var nav_holder = nav.parent();
			var container_width = nav_holder.width();
			var nav_width = nav.width();
			var step = nav_width/thumbs.length;
			next.click(function(e) {
				if ($(':animated').length) {
			        return false;
			    }
				prev.css('visibility','visible');
				var delta = nav_width - container_width; 
				if (!nav.css('left')) {
			 		current_position = 0;
			 	}
				else {
					current_position = nav.css('left').replace(/px/i,'')*1;
				}
				new_position = current_position - step;
				if (Math.abs(current_position) < delta) {
					nav.animate({left: new_position},{duration: 500});
				}
				if (Math.abs(new_position) >= delta) {
					next.css('visibility','hidden');
				}
			});
			prev.click(function(e) {
				if ($(':animated').length) {
			        return false;
			    }
				next.css('visibility','visible');
				if (!nav.css('left')) {
			 		current_position = 0;
			 	}
				else {
					current_position = nav.css('left').replace(/px/i,'')*1;
				}
				new_position = current_position + step;
				if (current_position < 0) {
					nav.animate({left: new_position},{duration: 500});
				}
				if (new_position >= 0) {
					prev.css('visibility','hidden');
				}
			});
		});
	};
})(jQuery);

jQuery(document).ready(function(){
	yasg_path = 'http://'+location.hostname+'/wp-content/plugins/yet-another-simple-gallery/';
	jQuery('.galleryHolder').yasg();
});
