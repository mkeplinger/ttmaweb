var is_custom = false;

jQuery(document).ready(function() {
			var id = '';
			jQuery('.upload_image_button').click(function() {
			 formfield = jQuery('.upload_image').attr('name');
			 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			 id = jQuery(this).attr('id');
			 is_custom = true;
			 return false;
			});

			window.postsendoriginal =  window.send_to_editor ;
			window.send_to_editor = function(html) {
			  
			  if (is_custom)
			  {
				 imgurl = jQuery('img',html).attr('src');
				 jQuery('#upload_image'+id).val(imgurl);
				 jQuery('#img_result_'+id).attr('src',imgurl);

				 tb_remove();

				 is_custom = false;
			  }else{
					window.postsendoriginal(html);
			  }
			}
			
	
	jQuery('.hndle').click(function() {
			jQuery(this).addClass('active')
			jQuery(this).next('.inside').slideToggle(300);
			//jQuery(this).removeClass('active');
	});
	
                    jQuery("#cancel").click(function(){
                             jQuery("#designbox").css({'display':'none'});
                    });

   });


              