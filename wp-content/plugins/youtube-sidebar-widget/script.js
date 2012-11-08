jQuery(function($){
	$("#youtube-sidebar-widget li div.play_arrow, #youtube-sidebar-widget li a").click(function(){
		var hash = $(this).parent().attr('id');
		$('body').prepend("<div id='ysw-overlay'></div><div id='ysw-viewer'><a href='#'>close</a><iframe title='YouTube video player' width='640' height='390' src='http://www.youtube.com/embed/" + hash + "' frameborder='0' allowfullscreen></iframe></div>");
		var win = $(window);
		var overlay = $("#ysw-overlay");
		var viewer = $('#ysw-viewer');
		var top = ((win.height() / 2) - (viewer.height() / 2)) + "px";
		var left = ((win.width() / 2) - (viewer.width() / 2)) + "px";
		viewer.css({
			top: top,
			left: left,
			display: 'block'
		}).children('a').click(function(){
			viewer.prev().hide().remove();
			viewer.hide().remove();
			return false;
		});
		overlay.css({
			left: "0px",
			top: "0px"
		});
		return false;
	});

	$(window).resize(function() {
		var win = $(window);
		var viewer = $('#ysw-viewer');
		var top = ((win.height() / 2) - (viewer.height() / 2)) + "px";
		var left = ((win.width() / 2) - (viewer.width() / 2)) + "px";
		viewer.css({
			top: top,
			left: left,
			display: 'block'
		});
	});
	
});
