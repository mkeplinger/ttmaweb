/* wppa-tinymce.js
* Pachkage: wp-photo-album-plus
*
*
* Version 4.7.9
*
*/


// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.mygallery', {
		// creates control instances based on the control's id.
		// our button's id is "mygallery_button"
		createControl : function(id, controlManager) {
			if (id == 'mygallery_button') {
				// creates the button
				var button = controlManager.createButton('mygallery_button', {
					title : 'WPPA+ Gallery Shortcode', 				// title of the button
					image : wppaImageDirectory+'albumnew32.png',  	// path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = jQuery(window).height();
						H = H - 115;
						tb_show( 'WPPA+ Gallery Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=mygallery-form' );

						var isNew = wppa_getCookie('wppanewstyle') == 'on';
						if (isNew) document.getElementById('mygallery-newstyle').checked = 'checked';
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('mygallery', tinymce.plugins.mygallery);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var xmlhttp = wppaGetXmlHttp();				// located in wppa-admin-scripts.js
		// wppa-ajax.php calls wppa_make_tinymce_dialog(); which is located in wppa-tinymce.php

		var url = wppaAjaxUrl+'?action=wppa&wppa-action=tinymcedialog';			
		xmlhttp.open("GET",url,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange=function() {
			if  (xmlhttp.readyState == 4 && xmlhttp.status!=404 ) {
				var formtext = xmlhttp.responseText;

				var form = jQuery(formtext);
		
				var table = form.find('table');
				form.appendTo('body').hide();
		
				// handles the click event of the submit button
				form.find('#mygallery-submit').click(function(){
					
					var type 	= table.find('#mygallery-type').val();
					var temp	= table.find('#mygallery-album').val().split('|');
					var album 	= temp[0];
					var size 	= table.find('#mygallery-size').val();
					var align	= table.find('#mygallery-align').val();
					var temp 	= table.find('#mygallery-photo').val().split('.');
					var photo	= temp[0];						
					var alb 	= table.find('#mygallery-alb').val();
						if ( alb == '' ) alb = '0'; else alb = parseInt(alb);
					var cnt		= table.find('#mygallery-cnt').val();
						if ( cnt == '' ) cnt = '0'; else cnt = parseInt(cnt);
					var newstyle = document.getElementById('mygallery-newstyle').checked;
					
					// Sinitize input
					if (size == 0) {}								// Ok, use default
					else if (size == parseInt(size) && size > 0) {}	// Ok, positive number
					else if (size == 'auto') {}						// Ok, auto
					else {
						alert('Sorry, you made a mistake\n\nSize must be a positive number or auto\nA number less than 100 will be interpreted as a percentage of the current column width\n\nPlease try again');
						return;
					}
					if (size < 100) size=size/100;

					// Check for inconsistencies
					if ( type == 'cover' ) {
						if ( album == '#topten' || album == '#lasten' || album == '#all' ) {
							alert('Sorry, you made a mistake\n\nA --- special --- selection has no album cover\n\nPlease try again');
							return;
						}
					}
					if ( type != 'photo' && type != 'mphoto' && type != 'slphoto' && type != 'generic' )	{
						if ( album == 0 ) {
							alert('Sorry, you made a mistake\n\nPlease select an album\n\nPlease try again');
							return;
						}
					}
					if ( type == 'photo' || type == 'mphoto' || type == 'slphoto' )	{
						if ( photo == 0 ) {
							alert('Sorry, you made a mistake\n\nPlease select a photo\n\nPlease try again');
							return;
						}
					}
					
					// Make the shortcode
					var shortcode = '%%wppa%%';
					
					if ( type == 'generic' ) {
					}
					else if ( type == 'photo' || type == 'mphoto' || type == 'slphoto' )	{			
						shortcode += ' %%'+type+'='+photo+'%%';
					}
					else {
						var temp = album.split('|');
						if ( temp[0] == '#topten'|| temp[0] == '#lasten' ) {
							if ( cnt != '0' ) {
								shortcode += ' %%'+type+'='+temp[0]+','+alb+','+cnt+'%%';
							}
							else if ( alb != '0' ) {
								shortcode += ' %%'+type+'='+temp[0]+','+alb+'%%';
							}
							else {
								shortcode += ' %%'+type+'='+album+'%%';
							}
						}
						else {
							shortcode += ' %%'+type+'='+album+'%%';
						}
					}
						
					if ( size != 0 )
						shortcode += ' %%size='+size+'%%';
					
					if ( align != 'none' )
						shortcode += ' %%align='+align+'%%';			
					
					// Make the new shortcode
					var newShortcode = '[wppa type="'+type+'"';
					
					if ( type == 'generic' ) {
					}
					else if ( type == 'photo' || type == 'mphoto' || type == 'slphoto' )	{			
						newShortcode += ' photo="'+photo+'"';
					}
					else {
						var temp = album.split('|');
						if ( temp[0] == '#topten'|| temp[0] == '#lasten' ) {
							if ( cnt != '0' ) {
								newShortcode += ' album="'+temp[0]+','+alb+','+cnt+'"';
							}
							else if ( alb != '0' ) {
								newShortcode += ' album="'+temp[0]+','+alb+'"';
							}
							else {
								newShortcode += ' album="'+temp[0]+'"';
							}
						}
						else {
							newShortcode += ' album="'+album+'"';
						}
					}
					
					if ( size != 0 )  newShortcode += ' size="'+size+'"';
					if ( align != 'none' ) newShortcode += ' align="'+align+'"';
					newShortcode += '][/wppa]';
					
					// inserts the shortcode into the active editor and save the newstyle checkbox
					if (newstyle) {
						tinyMCE.activeEditor.execCommand('mceInsertContent', 0, newShortcode);
						wppa_setCookie('wppanewstyle', 'on', '365');
					}
					else {
						tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
						wppa_setCookie('wppanewstyle', 'off', '365');
					}
					
					// closes Thickbox
					tb_remove();
				});
			}
		}
	});
})()

function wppaGalleryTypeChange(value) {

	if (value == 'generic' ) {
		jQuery('.mygallery-photo').hide();
		jQuery('.mygallery-album').hide();
		jQuery('.mygallery-help').show();
	}
	else if (value == 'photo' || value == 'mphoto' || value == 'slphoto' ) {
		jQuery('.mygallery-photo').show();
		jQuery('.mygallery-album').hide();
		jQuery('.mygallery-help').hide();
	}
	else {
		jQuery('.mygallery-photo').hide();
		jQuery('.mygallery-album').show();
		jQuery('.mygallery-help').hide();
	}
}

function wppaTinyMcePhotoPreview(id) {
	jQuery('#mygallery-photo-preview').html('<img src="'+wppaThumbDirectory+id+'" style="max-width:600px; max-height:150px; margin-top:3px;" />');
}

function wppaTinyMceAlbumPreview(id) {
	var html = '';
	var temp = id.split('|');
	var count = temp.length - 1;
	
	if (count > 0) for (var i = 1; i <= count; i++) {
		if ( temp[i] != '' ) html += '<img src="'+wppaThumbDirectory+temp[i]+'" title="'+parseInt(temp[i])+'" style="max-width:75px; max-height:75px; margin:2px;" />';
	}
	else {
		html = '<br /><br /><br />'+wppaNoPreview;
	}
	jQuery('#mygallery-album-preview').html(html);
	
	if ( temp[0] == '#topten' || temp[0] == '#lasten' ) { 
		jQuery('.mygallery-extra').show();
	}
	else {
		jQuery('.mygallery-extra').hide();
	}
}
