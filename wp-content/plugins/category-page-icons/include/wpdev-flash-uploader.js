
function wpdev_debug_function (message) {
        var exceptionMessage, exceptionValues = [];

        // Check for an exception object and print it nicely
        if (typeof message === "object" && typeof message.name === "string" && typeof message.message === "string") {
            for (var key in message) {
                if (message.hasOwnProperty(key)) {
                    exceptionValues.push(key + ": " + message[key]);
                }
            }
            exceptionMessage = exceptionValues.join("\n") || "";
          exceptionValues = exceptionMessage.split("\n");
            exceptionMessage = "EXCEPTION: " + exceptionValues.join("\nEXCEPTION: ");
            //jQuery('#media-upload-error').show().text(exceptionMessage);
            message = exceptionMessage;
        } else {
            //jQuery('#media-upload-error').show().text(message);
        }

    message = '<hr/>'+ message;
    jQuery('#media-upload-error').show().html(  jQuery('#media-upload-error').show().html() + message);
}


function wpdev_fileDialogStart() {
	jQuery("#media-upload-error").empty();
}

// progress and success handlers for media multi uploads  EDITED
function wpdev_fileQueued(fileObj) {

	// Get rid of unused form
	jQuery('.media-blank').remove();
	// Collapse a single item
	if ( jQuery('form.type-form #media-items').children().length == 1 && jQuery('.hidden', '#media-items').length > 0 ) {
		jQuery('.describe-toggle-on').show();
		jQuery('.describe-toggle-off').hide();
		jQuery('.slidetoggle').slideUp(200).siblings().removeClass('hidden');
	}
	// Create a progress bar containing the filename
	jQuery('#media-items').append('<div id="media-item-' + fileObj.id + '" class="media-item wpdev-media-item child-of-' + post_id + '"><div class="progress"><div class="bar"></div></div><div class="filename original"><span class="percent"></span> ' + fileObj.name + '</div></div>');
	// Display the progress div
	jQuery('.progress', '#media-item-' + fileObj.id).show();

	// Disable submit and enable cancel
	jQuery('#insert-gallery').attr('disabled', 'disabled');
	jQuery('#cancel-upload').attr('disabled', '');


    /*
	// Get rid of unused form
	jQuery('.media-blank').remove();
	// Collapse a single item
	if ( jQuery('.type-form #media-items>*').length == 1 && jQuery('#media-items .hidden').length > 0 ) {
		jQuery('.describe-toggle-on').show();
		jQuery('.describe-toggle-off').hide();
		jQuery('.slidetoggle').slideUp(200).siblings().removeClass('hidden');
	}
	// Create a progress bar containing the filename
	jQuery('#media-items').append('\
        <div id="media-item-' + fileObj.id + '" class="media-item child-of-' + post_id + '"  >\n\
            <div class="progress">\n\
                <div class="bar"></div>\n\
            </div>\n\
            <div class="filename original">\n\
                <span class="percent"></span> ' + fileObj.name + '\
            </div>\n\
        </div>');
    
	// Display the progress div
	jQuery('#media-item-' + fileObj.id + ' .progress').show();
 

	// Disable submit and enable cancel
	jQuery('#insert-gallery').attr('disabled', 'disabled');
	jQuery('#cancel-upload').attr('disabled', '');/**/
}

function wpdev_uploadStart(fileObj) {
	return true;
}
// EDITED
function wpdev_uploadProgress(fileObj, bytesDone, bytesTotal) {

    	// Lengthen the progress bar
	var w = jQuery('#media-items').width() - 2, item = jQuery('#media-item-' + fileObj.id);
	jQuery('.bar', item).width( w * bytesDone / bytesTotal );
	jQuery('.percent', item).html( Math.ceil(bytesDone / bytesTotal * 100) + '%' );

	if ( bytesDone == bytesTotal )
		jQuery('.bar', item).html('<strong class="crunching">' + swfuploadL10n.crunching + '</strong>');
/*
	// Lengthen the progress bar
	var w = jQuery('#media-items').width() - 2;
	jQuery('#media-item-' + fileObj.id + ' .bar').width( w * bytesDone / bytesTotal );
	jQuery('#media-item-' + fileObj.id + ' .percent').html( Math.ceil(bytesDone / bytesTotal * 100) + '%' );

	if ( bytesDone == bytesTotal )
		jQuery('#media-item-' + fileObj.id + ' .bar').html('<strong class="crunching">' + swfuploadL10n.crunching + '</strong>');/**/
}
// EDITED
function wpdev_prepareMediaItem(fileObj, serverData) {

	var f = ( typeof shortform == 'undefined' ) ? 1 : 2, item = jQuery('#media-item-' + fileObj.id);
	// Move the progress bar to 100%
	jQuery('.bar', item).remove();
	jQuery('.progress', item).hide();

	// Old style: Append the HTML returned by the server -- thumbnail and form inputs
	if ( isNaN(serverData) || !serverData ) {
		item.append(serverData);
		wpdev_prepareMediaItemInit(fileObj);
	}
	// New style: server data is just the attachment ID, fetch the thumbnail and form html from the server
	else {
		item.load('async-upload.php', {attachment_id:serverData, fetch:f}, function(){wpdev_prepareMediaItemInit(fileObj);wpdev_updateMediaForm()});
	}

/*
	// Move the progress bar to 100%
	jQuery('#media-item-' + fileObj.id + ' .bar').remove();
	jQuery('#media-item-' + fileObj.id + ' .progress').hide();

	var f = ( typeof shortform == 'undefined' ) ? 1 : 2;
	// Old style: Append the HTML returned by the server -- thumbnail and form inputs
	if ( isNaN(serverData) || !serverData ) {
		jQuery('#media-item-' + fileObj.id).append(serverData);
		wpdev_prepareMediaItemInit(fileObj);
	}
	// New style: server data is just the attachment ID, fetch the thumbnail and form html from the server
	else {
		jQuery('#media-item-' + fileObj.id).load('async-upload.php', {attachment_id:serverData, fetch:f}, function(){wpdev_prepareMediaItemInit(fileObj);wpdev_updateMediaForm()});
	}/**/
}
//EDITED
function wpdev_prepareMediaItemInit(fileObj) {


	var item = jQuery('#media-item-' + fileObj.id);
	// Clone the thumbnail as a "pinkynail" -- a tiny image to the left of the filename
	jQuery('.thumbnail', item).clone().attr('className', 'pinkynail toggle').prependTo(item);

	// Replace the original filename with the new (unique) one assigned during upload
	jQuery('.filename.original', item).replaceWith( jQuery('.filename.new', item) );

	// Also bind toggle to the links
	jQuery('a.toggle', item).click(function(){
		jQuery(this).siblings('.slidetoggle').slideToggle(350, function(){
			var w = jQuery(window).height(), t = jQuery(this).offset().top, h = jQuery(this).height(), b;

			if ( w && t && h ) {
                b = t + h;

                if ( b > w && (h + 48) < w )
                    window.scrollBy(0, b - w + 13);
                else if ( b > w )
                    window.scrollTo(0, t - 36);
            }
		});
		jQuery(this).siblings('.toggle').andSelf().toggle();
		jQuery(this).siblings('a.toggle').focus();
		return false;
	});

	// Bind AJAX to the new Delete button
	jQuery('a.delete', item).click(function(){
		// Tell the server to delete it. TODO: handle exceptions
		jQuery.ajax({
			url: wpdev_flash_uploader_path,
			type: 'post',
			success: wpdev_deleteSuccess,
			error: wpdev_deleteError,
			id: fileObj.id,
			data: {
                            ajax_action : 'delete-image',
                            file_name : fileObj.name,
                            file_name_org : jQuery('#media-item-' + fileObj.id +' input.filename').val(),
                            file_name_dir : jQuery('#media-item-' + fileObj.id +' input.filedir').val(),
                            fileicon_size : jQuery('#media-item-' + fileObj.id +' input.fileicon_size').val(),
                            _ajax_nonce : this.href.replace(/^.*wpnonce=/,'')
			}
		});
		return false;
	});

	// Bind AJAX to the new Undo button
	jQuery('a.undo', item).click(function(){
		// Tell the server to untrash it. TODO: handle exceptions
		jQuery.ajax({
			url: wpdev_flash_uploader_path,
			type: 'post',
			id: fileObj.id,
			data: {
				id : this.id.replace(/[^0-9]/g,''),
				action: 'untrash-post',
				_ajax_nonce: this.href.replace(/^.*wpnonce=/,'')
			},
			success: function(data, textStatus){
				var item = jQuery('#media-item-' + fileObj.id);

				if ( type = jQuery('#type-of-' + fileObj.id).val() )
					jQuery('#' + type + '-counter').text(jQuery('#' + type + '-counter').text()-0+1);
				if ( item.hasClass('child-of-'+post_id) )
					jQuery('#attachments-count').text(jQuery('#attachments-count').text()-0+1);

				jQuery('.filename .trashnotice', item).remove();
				jQuery('.filename .title', item).css('font-weight','normal');
				jQuery('a.undo', item).addClass('hidden');
				jQuery('a.describe-toggle-on, .menu_order_input', item).show();
				item.css( {backgroundColor:'#ceb'} ).animate( {backgroundColor: '#fff'}, { queue: false, duration: 500, complete: function(){ jQuery(this).css({backgroundColor:''}); } }).removeClass('undo');
			}
		});
		return false;
	});

	// Open this item if it says to start open (e.g. to display an error)
	jQuery('#media-item-' + fileObj.id + '.startopen').removeClass('startopen').slideToggle(500).siblings('.toggle').toggle();

/*


	// Clone the thumbnail as a "pinkynail" -- a tiny image to the left of the filename
	jQuery('#media-item-' + fileObj.id + ' .thumbnail').clone().attr('className', 'pinkynail toggle').prependTo('#media-item-' + fileObj.id);

	// Replace the original filename with the new (unique) one assigned during upload
	jQuery('#media-item-' + fileObj.id + ' .filename.original').replaceWith(jQuery('#media-item-' + fileObj.id + ' .filename.new'));

	// Also bind toggle to the links
	jQuery('#media-item-' + fileObj.id + ' a.toggle').click(function(){
		jQuery(this).siblings('.slidetoggle').slideToggle(150, function(){
			var o = jQuery(this).offset();
			window.scrollTo(0, o.top-36);
		});
		jQuery(this).parent().children('.toggle').toggle();
		jQuery(this).siblings('a.toggle').focus();
		return false;
	});

	// Bind AJAX to the new Delete button
	jQuery('#media-item-' + fileObj.id + ' a.delete').click(function(){
		// Tell the server to delete it. TODO: handle exceptions
		var answer = true;//confirm("Do you really wnat to delete this "+fileObj.name+" file?");
        	if (answer){
                   jQuery.ajax({
                        url: wpdev_flash_uploader_path, //'../wp-content/plugins/menu-compouser/include/wpdev-flash-uploader.php',
                        type:'post',
                        success:wpdev_deleteSuccess,
                        error:wpdev_deleteError,
                        id:fileObj.id, // this is needs for working with this.id inside this script
                        data:{
                            ajax_action : 'delete-image',
                            file_name : fileObj.name,
                            file_name_org : jQuery('#media-item-' + fileObj.id +' input.filename').val(),
                            file_name_dir : jQuery('#media-item-' + fileObj.id +' input.filedir').val(),
                            fileicon_size : jQuery('#media-item-' + fileObj.id +' input.fileicon_size').val(),
                            _ajax_nonce : this.href.replace(/^.*wpnonce=/,'')}
                            });
                }
		return false;
	});

	// Open this item if it says to start open (e.g. to display an error)
	jQuery('#media-item-' + fileObj.id + '.startopen').removeClass('startopen').slideToggle(500).parent().children('.toggle').toggle();


        /**/
}
// Edited
function wpdev_itemAjaxError(id, html) {
	var error = jQuery('#media-item-error' + id);

	error.html('<div class="wpdev-file-error" style="color:#FF0000;padding:5px 10px;text-align:right;border-top:1px solid #eee;font-weight:bold;"><button type="button" id="dismiss-'+id+'" class="button dismiss" >'+swfuploadL10n.dismiss+'</button>'+html+'</div>');
	jQuery('#dismiss-'+id).click(function(){jQuery(this).parents('.file-error').slideUp(200, function(){jQuery(this).empty();})});

    /*
	var error = jQuery('#media-item-' + id + ' div.media-item-error');

	error.html('<div class="wpdev-file-error" style="color:#FF0000;padding:5px 10px;text-align:right;border-top:1px solid #eee;font-weight:bold;"><button type="button" id="dismiss-'+id+'" class="button dismiss" >'+swfuploadL10n.dismiss+'</button>'+html+'</div>');
	jQuery('#dismiss-'+id).click(function(){jQuery(this).parents('#media-item-' + id).slideUp(200, function(){jQuery(this).empty();})});
        /**/
}

//Edited may be need additional editing
function wpdev_deleteSuccess(data, textStatus) {
	if ( data == '-1' )
		return wpdev_itemAjaxError(this.id, 'You do not have permission. Has your session expired?');
	if ( data == '0' )
		return wpdev_itemAjaxError(this.id, 'Could not be deleted. Has it been deleted already?');

	var id = this.id, item = jQuery('#media-item-' + id);
/*
	// Decrement the counters.
	if ( type = jQuery('#type-of-' + id).val() )
		jQuery('#' + type + '-counter').text( jQuery('#' + type + '-counter').text() - 1 );
	if ( item.hasClass('child-of-'+post_id) )
		jQuery('#attachments-count').text( jQuery('#attachments-count').text() - 1 );

	if ( jQuery('form.type-form #media-items').children().length == 1 && jQuery('.hidden', '#media-items').length > 0 ) {
		jQuery('.toggle').toggle();
		jQuery('.slidetoggle').slideUp(200).siblings().removeClass('hidden');
	}

	// Vanish it.
	jQuery('.toggle', item).toggle();
	jQuery('.slidetoggle', item).slideUp(200).siblings().removeClass('hidden');
	item.css( {backgroundColor:'#faa'} ).animate( {backgroundColor:'#f4f4f4'}, {queue:false, duration:500} ).addClass('undo');

	jQuery('.filename:empty', item).remove();
	jQuery('.filename .title', item).css('font-weight','bold');
	jQuery('.filename', item).append('<span class="trashnotice"> ' + swfuploadL10n.deleted + ' </span>').siblings('a.toggle').hide();
	jQuery('.filename', item).append( jQuery('a.undo', item).removeClass('hidden') );
	jQuery('.menu_order_input', item).hide();

/**/
	jQuery('#media-item-' + this.id).children('.describe').css({backgroundColor:'#fff'}).end()
			.animate({backgroundColor:'#ffc0c0'}, {queue:false,duration:50})
			.animate({minHeight:0,height:36}, 400, null, function(){jQuery(this).children('.describe').remove()})
			.animate({backgroundColor:'#fff'}, 400)
			.animate({height:0}, 800, null, function(){jQuery(this).remove();wpdev_updateMediaForm();});

	return 0;


	return;


	if ( data == '-1' )
		return wpdev_itemAjaxError(this.id, 'You do not have permission. Has your session expired?');
	if ( data == '0' )
		return wpdev_itemAjaxError(this.id, 'Could not be deleted. Has it been deleted already?');

	var item = jQuery('#media-item-' + this.id);
//item.html(data);
        /*
	// Decrement the counters.
	if ( type = jQuery('#type-of-' + this.id).val() )
		jQuery('#' + type + '-counter').text(jQuery('#' + type + '-counter').text()-1);
	if ( item.hasClass('child-of-'+post_id) )
		jQuery('#attachments-count').text(jQuery('#attachments-count').text()-1);

	if ( jQuery('.type-form #media-items>*').length == 1 && jQuery('#media-items .hidden').length > 0 ) {
		jQuery('.toggle').toggle();
		jQuery('.slidetoggle').slideUp(200).siblings().removeClass('hidden');
	}
        /**/
	// Vanish it.
	//jQuery('#media-item-' + this.id + ' .filename:empty').remove();
	//jQuery('#media-item-' + this.id + ' .filename').append(' <span class="file-error">'+swfuploadL10n.deleted+'</span>').siblings('a.toggle').remove();
	jQuery('#media-item-' + this.id).children('.describe').css({backgroundColor:'#fff'}).end()
			.animate({backgroundColor:'#ffc0c0'}, {queue:false,duration:50})
			.animate({minHeight:0,height:36}, 400, null, function(){jQuery(this).children('.describe').remove()})
			.animate({backgroundColor:'#fff'}, 400)
			.animate({height:0}, 800, null, function(){jQuery(this).remove();wpdev_updateMediaForm();});

	return 0;
}

function wpdev_deleteError(X, textStatus, errorThrown) {
	// TODO
}
//Edited
function wpdev_updateMediaForm() {
    	var one = jQuery('form.type-form #media-items').children(), items = jQuery('#media-items').children();

	// Just one file, no need for collapsible part
	if ( one.length == 1 ) {
		jQuery('.slidetoggle', one).slideDown(500).siblings().addClass('hidden').filter('.toggle').toggle();
	}

	// Only show Save buttons when there is at least one file.
	if ( items.not('.media-blank').length > 0 )
		jQuery('.savebutton').show();
	else
		jQuery('.savebutton').hide();

	// Only show Gallery button when there are at least two files.
	if ( items.length > 1 )
		jQuery('.insert-gallery').show();
	else
		jQuery('.insert-gallery').hide();


    /*
	storeState();
	// Just one file, no need for collapsible part
	if ( jQuery('.type-form #media-items>*').length == 1 ) {
		jQuery('#media-items .slidetoggle').slideDown(500).parent().eq(0).children('.toggle').toggle();
		jQuery('.type-form .slidetoggle').siblings().addClass('hidden');
	}

	// Only show Save buttons when there is at least one file.
	if ( jQuery('#media-items>*').not('.media-blank').length > 0 )
		jQuery('.savebutton').show();
	else
		jQuery('.savebutton').hide();

	// Only show Gallery button when there are at least two files.
	if ( jQuery('#media-items>*').length > 1 )
		jQuery('.insert-gallery').show();
	else
		jQuery('.insert-gallery').hide();/**/
}
//Edited
function wpdev_uploadSuccess(fileObj, serverData) {
	// if async-upload returned an error message, place it in the media item div and return
	if ( serverData.match('media-upload-error') ) {
		jQuery('#media-item-' + fileObj.id).html(serverData);
		return;
	}

	wpdev_prepareMediaItem(fileObj, serverData);
	wpdev_updateMediaForm();

	// Increment the counter.
	if ( jQuery('#media-item-' + fileObj.id).hasClass('child-of-' + post_id) )
		jQuery('#attachments-count').text(1 * jQuery('#attachments-count').text() + 1);

        /*
// if async-upload returned an error message, place it in the media item div and return
	if ( serverData.match('media-upload-error') ) {
		jQuery('#media-item-' + fileObj.id).html(serverData);
		return;
	}

	wpdev_prepareMediaItem(fileObj, serverData);
	wpdev_updateMediaForm();

	// Increment the counter.
	if ( jQuery('#media-item-' + fileObj.id).hasClass('child-of-' + post_id) )
		jQuery('#attachments-count').text(1 * jQuery('#attachments-count').text() + 1); /**/
}

function wpdev_uploadComplete(fileObj) {   //jQuery('#media-item-' + fileObj.id).fadeOut(2500); //alert(fileObj.name)
	// If no more uploads queued, enable the submit button
	if ( swfu.getStats().files_queued == 0 ) {
		jQuery('#cancel-upload').attr('disabled', 'disabled');
		jQuery('#insert-gallery').attr('disabled', '');
	}
}


// wp-specific error handlers

// generic message
function wpdev_wpQueueError(message) {
//	jQuery('#media-upload-error').show().text(message);
    message = '<hr/>'+ message;
    jQuery('#media-upload-error').show().html(  jQuery('#media-upload-error').show().html() + message);

}

// file-specific message
function wpdev_wpFileError(fileObj, message) {
	jQuery('#media-item-' + fileObj.id + ' .filename').after('<div class="file-error"><button type="button" id="dismiss-' + fileObj.id + '" class="button dismiss">'+swfuploadL10n.dismiss+'</button>'+message+'</div>').siblings('.toggle').remove();
	jQuery('#dismiss-' + fileObj.id).click(function(){jQuery(this).parents('.media-item').slideUp(200, function(){jQuery(this).remove();})});
}

function wpdev_fileQueueError(fileObj, error_code, message)  {
	// Handle this error separately because we don't want to create a FileProgress element for it.
	if ( error_code == SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED ) {
		wpdev_wpQueueError(swfuploadL10n.queue_limit_exceeded);
	}
	else if ( error_code == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT ) {
		wpdev_fileQueued(fileObj);
		wpdev_wpFileError(fileObj, swfuploadL10n.file_exceeds_size_limit);
	}
	else if ( error_code == SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE ) {
		wpdev_fileQueued(fileObj);
		wpdev_wpFileError(fileObj, swfuploadL10n.zero_byte_file);
	}
	else if ( error_code == SWFUpload.QUEUE_ERROR.INVALID_FILETYPE ) {
		wpdev_fileQueued(fileObj);
		wpdev_wpFileError(fileObj, swfuploadL10n.invalid_filetype);
	}
	else {
		wpdev_wpQueueError(swfuploadL10n.default_error);
	}
}

function wpdev_fileDialogComplete(num_files_queued) { 
	try {
		if (num_files_queued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function wpdev_switchUploader(s) {
	var f = document.getElementById(swfu.customSettings.swfupload_element_id), h = document.getElementById(swfu.customSettings.degraded_element_id);
	if ( s ) {
		f.style.display = 'block';
		h.style.display = 'none';
	} else {
		f.style.display = 'none';
		h.style.display = 'block';
	}
}

function wpdev_swfuploadPreLoad() {
	if ( !uploaderMode ) {
		wpdev_switchUploader(1);
	} else {
		wpdev_switchUploader(0);
	}
}

function wpdev_swfuploadLoadFailed() {
	wpdev_switchUploader(0);
	jQuery('.upload-html-bypass').hide();
}

function wpdev_uploadError(fileObj, errorCode, message) {

	switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
			wpdev_wpFileError(fileObj, swfuploadL10n.missing_upload_url);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			wpdev_wpFileError(fileObj, swfuploadL10n.upload_limit_exceeded);
			break;
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			wpdev_wpQueueError(swfuploadL10n.http_error);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			wpdev_wpQueueError(swfuploadL10n.upload_failed);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			wpdev_wpQueueError(swfuploadL10n.io_error);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			wpdev_wpQueueError(swfuploadL10n.security_error);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			jQuery('#media-item-' + fileObj.id).remove();
			break;
		default:
			wpdev_wpFileError(fileObj, swfuploadL10n.default_error);
	}
}

function wpdev_cancelUpload() {
	swfu.cancelQueue();
}


// remember the last used image size, alignment and url
jQuery(document).ready(function($){
	$('input[type="radio"]', '#media-items').live('click', function(){
		var tr = $(this).closest('tr');

		if ( $(tr).hasClass('align') )
			setUserSetting('align', $(this).val());
		else if ( $(tr).hasClass('image-size') )
			setUserSetting('imgsize', $(this).val());
	});

	$('button.button', '#media-items').live('click', function(){
		var c = this.className || '';
		c = c.match(/url([^ '"]+)/);
		if ( c && c[1] ) {
			setUserSetting('urlbutton', c[1]);
			$(this).siblings('.urlfield').val( $(this).attr('title') );
		}
	});
});

