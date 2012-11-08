var fv = swfobject.getFlashPlayerVersion();

function FlAGClass(ExtendVar, skin_id, pic_id, slideshow) {
	jQuery(document).ready(function() {
		if(pic_id !== false){
			var skin_function = flagFind(skin_id);
			if(pic_id !== 0 ) {
				jQuery.fancybox(
				{
					'showNavArrows'	: false,
					'overlayShow'	: true,
					'overlayOpacity': '0.9',
					'overlayColor'	: '#000',
					'transitionIn'	: 'elastic',
					'transitionOut'	: 'elastic',
					'titlePosition'	: 'over',
					'titleFormat'	: function(title, currentArray, currentIndex, currentOpts) {
						var descr = jQuery('<div />').html(jQuery("#flag_pic_"+pic_id, flag_alt[skin_id]).find('.flag_pic_desc > span').html()).text();
						title = jQuery('<div />').html(jQuery("#flag_pic_"+pic_id, flag_alt[skin_id]).find('.flag_pic_desc > strong').html()).text();
						if(title.length || descr.length)
							return '<div class="grand_controls" rel="'+skin_id+'"><span rel="prev" class="g_prev">prev</span><span rel="show" class="g_slideshow '+slideshow+'">play/pause</span><span rel="next" class="g_next">next</span></div><div id="fancybox-title-over">'+(title.length? '<strong class="title">'+title+'</strong>' : '')+(descr.length? '<div class="descr">'+descr+'</div>' : '')+'</div>';
						else
							return '<div class="grand_controls" rel="'+skin_id+'"><span rel="prev" class="g_prev">prev</span><span rel="show" class="g_slideshow '+slideshow+'">play/pause</span><span rel="next" class="g_next">next</span></div>';
					},
					'href'			: jQuery("#flag_pic_"+pic_id, flag_alt[skin_id]).attr('href'),
					'onStart' 		: function(){
						//if(skin_function && jQuery.isFunction(skin_function[skin_id+'_fb'])) {
							skin_function[skin_id+'_fb']('active');
						//}
						jQuery('#fancybox-wrap').addClass('grand');
					},
					'onClosed' 		: function(currentArray, currentIndex){
						//if(skin_function && jQuery.isFunction(skin_function[skin_id+'_fb'])) {
							skin_function[skin_id+'_fb']('close');
						//}
						jQuery('#fancybox-wrap').removeClass('grand');
					},
					'onComplete'	: function(currentArray, currentIndex) {
					}
				});
			}
			jQuery('#fancybox-wrap').undelegate('.grand_controls span','click').delegate('.grand_controls span','click', function(){
				//if(skin_function && jQuery.isFunction(skin_function[skin_id+'_fb'])) {
					skin_function[skin_id+'_fb'](jQuery(this).attr('rel'));
					if(jQuery(this).hasClass('g_slideshow')){
						jQuery(this).toggleClass('play stop');
					}
				//}
			});
		} else {
			jQuery('.flag_alternate').each(function(i){
				jQuery(this).show();
				var catMeta = jQuery('.flagCatMeta',this).hide().get();
				for(j=0; j<catMeta.length; j++) {
					var catName = jQuery(catMeta[j]).find('h4').text();
					var catDescr = jQuery(catMeta[j]).find('p').text();
					var catId = jQuery(catMeta[j]).next('.flagcategory').attr('id');
					var act = '';
					if(j==0) act = ' active';
					jQuery('.flagcatlinks',this).append('<a class="flagcat'+act+'" href="#'+catId+'" title="'+catDescr+'">'+catName+'</a>');
				}
			});
			jQuery('.flag_alternate .flagcat').click(function(){
				if(!jQuery(this).hasClass('active')) {
					var catId = jQuery(this).attr('href');
					jQuery(this).addClass('active').siblings().removeClass('active');
					jQuery('.flag_alternate '+catId).show().siblings('.flagcategory').hide();
					alternate_flag_e(catId);
				}
				return false;
			});
			alternate_flag_e('.flagcategory:first', ExtendVar);
		}
	});
}

function alternate_flag_e(t, ExtendVar){
	jQuery('.flag_alternate').find(t).not('.loaded').each(function(){
		var d = jQuery(this).html();
		if(d) {
			d = d.replace(/\[/g, '<');
			d = d.replace(/\]/g, ' />');
			jQuery(this).addClass('loaded').html(d);
		}
		jQuery(this).show();
		var
			showDescr, longDescription, imgdescr, psImgCaption, curel,
			options = {
				allowUserZoom:false,
				captionAndToolbarAutoHideDelay:0,
				captionAndToolbarHide:false,
				captionAndToolbarShowEmptyCaptions:false,
				zIndex:10000,
				getToolbar: function(){
					flagToolbar = window.Code.PhotoSwipe.Toolbar.getToolbar();
					flagToolbar = flagToolbar + '<div class="ps-toolbar-descr"><div class="ps-toolbar-content"></div></div>';
					return flagToolbar;
					// NB. Calling PhotoSwipe.Toolbar.getToolbar() wil return the default toolbar HTML
				},
				getImageCaption: function(el){
					psImgCaption = jQuery('<strong></strong>').addClass('ps-title').append(jQuery(el).attr('title'));
					return psImgCaption;
				},
				getImageMetaData: function(el){
					imgdescr = jQuery(el).find('span.flag_pic_desc > span:first').html();
					if(imgdescr.length){
						imgdescr = jQuery('<div></div>').append(imgdescr);
					}
					return {
						longDescription: imgdescr
					}

				}
			},
			instance = jQuery('a.flag_pic_alt',this).photoSwipe(options);

		// onShow - store a reference to our "say hi" button
	  	instance.addEventHandler(window.Code.PhotoSwipe.EventTypes.onShow, function(e){
			showDescr = window.document.querySelectorAll('.ps-toolbar-descr')[0];
		});
		// onBeforeHide - clean up
		instance.addEventHandler(window.Code.PhotoSwipe.EventTypes.onBeforeHide, function(e){
			showDescr = null;
		});
		// onDisplayImage
		instance.addEventHandler(window.Code.PhotoSwipe.EventTypes.onDisplayImage, function(e){
			curel = instance.getCurrentImage();
			if(curel.metaData.longDescription){
				jQuery('.ps-caption-content').append(jQuery('<div></div>').addClass('ps-long-description').html(jQuery(curel.metaData.longDescription).text()).hide());
				jQuery('.ps-toolbar-descr').removeClass('disabled active').addClass('enabled');
			} else {
				jQuery('.ps-toolbar-descr').removeClass('enabled active').addClass('disabled');
			}
		});
		// onSlideshowStart
		instance.addEventHandler(window.Code.PhotoSwipe.EventTypes.onCaptionAndToolbarShow, function(e){
			curel = instance.getCurrentImage();
			if(curel.metaData.longDescription){
				jQuery('.ps-caption-content').append(jQuery('<div></div>').addClass('ps-long-description').html(jQuery(curel.metaData.longDescription).text()).hide());
				jQuery('.ps-toolbar-descr').removeClass('disabled active').addClass('enabled');
			} else {
				jQuery('.ps-toolbar-descr').removeClass('enabled active').addClass('disabled');
			}
		});
		// onToolbarTap - listen out for when the toolbar is tapped
		instance.addEventHandler(window.Code.PhotoSwipe.EventTypes.onToolbarTap, function(e){
			if (e.toolbarAction === window.Code.PhotoSwipe.Toolbar.ToolbarAction.none){
				if (e.tapTarget === showDescr || window.Code.Util.DOM.isChildOf(e.tapTarget, showDescr)){
					if(jQuery(showDescr).hasClass('enabled')){
						jQuery('.ps-toolbar-descr').toggleClass('active');
						jQuery('.ps-long-description').slideToggle(400);
					}
				}
			}
		});

	});
}
if(fv.major<10 || (navigator.userAgent.toLowerCase().indexOf("android") > -1)) {
	new FlAGClass(ExtendVar, false, false, false);
}
function thumb_cl(skin_id, pic_id, slideshow){
  	pic_id = parseInt(pic_id);
	new FlAGClass(ExtendVar, skin_id, pic_id, slideshow);
}
