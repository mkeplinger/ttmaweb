<?php
	global $post;
	$url = get_permalink($post->ID);
	$title = $post->post_title;
	$facebook_like='';
	$vkontakte_like='';
	$mailru_like='';
	if(get_option('vkontakte_like_button_show')==1) {
		$type_vk = get_option('vkontakte_like_type');
		$verb_vk = get_option('vkontakte_like_verb');
		$api_id_vk = get_option('vkontakte_like_api');

		if($verb_vk=='like') { $verb_vk=0; } else { $verb_vk=1; }

		$vkontakte_like .= '<div id="vk_like_'.$post->ID.'"></div>';

		$vkontakte_like .= '<script type="text/javascript">';
		$vkontakte_like .= "\r\n";
/*		$vkontakte_like .= "window.onload = function() {";
		$vkontakte_like .= "\r\n";*/
		$vkontakte_like .= "VK.init({apiId: $api_id_vk, onlyWidgets: true});";
		$vkontakte_like .= "\r\n";    

		$vkontakte_like .= "VK.Widgets.Like('vk_like_".$post->ID."', {type: '$type_vk', pageTitle: '$title', pageUrl:'$url', verb: '$verb_vk'}, ".$post->ID.");";
		$vkontakte_like .= "\r\n";
/*		$vkontakte_like .= '}';
		$vkontakte_like .= "\r\n";*/
		$vkontakte_like .= '</script>';
		$vkontakte_like .= "\r\n\r\n";

	}

	if(get_option('mailru_like_button_show')==1) {

		$width_mailru = get_option('mailru_like_width');
		$show_text_mailru = get_option('mailru_like_show_text');

		$faces_mailru = get_option('mailru_like_faces');
		$verb_mailru = get_option('mailru_like_verb');
//		$verb_mailru = _e('Like');

		if(!empty($show_text_mailru))
			$show_text_mailru = ", 'show_text' : 'true'";
		else
			$width_mailru = 90;
		if(!empty($faces_mailru))
			$faces_mailru = ", 'show_faces' : 'true'";

		$mailru_like .= "<a target=\"_blank\" class=\"mrc__plugin_like_button\" href=\"http://connect.mail.ru/share?url=".$url."&title=".$title."\" rel=\"{'type' : 'button', 'width' : $width_mailru $show_text_mailru $faces_mailru }\">".$verb_mailru."</a><br/>";
		$mailru_like .= '<script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>';
	}

	if(get_option('facebook_like_button_show')==1) {
		$layout_fb = get_option('facebook_like_layout');
		$verb_fb   = get_option('facebook_like_verb');
		$width_fb  = get_option('facebook_like_width');
		$height_fb = get_option('facebook_like_height');
		$color_fb  = get_option('facebook_like_color');
		$faces_fb  = get_option('facebook_like_faces');

		if($faces==1) { $faces='true'; } else { $faces='false'; }
    
		$facebook_like .= '<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&amp;layout='.$layout_fb.'&amp;show_faces='.$faces_fb.'&amp;width='.$width_fb.'&amp;action='.$verb_fb.'&amp;font=tahoma&amp;colorscheme='.$color_fb.'&amp;height='.$height_fb.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width_fb.'px; height:'.$height_fb.'px;" allowTransparency="true"></iframe>';
	}

	$button_code .= '<br />'.$vkontakte_like.'<br/>'.$mailru_like.$facebook_like;

?>