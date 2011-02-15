<?php
    
	global $post;

	$title = esc_js($post->post_title);
	$url = get_permalink($post->ID);
	$via = get_option('twitter_via');
        $vk_text = __('Share',$this->plugin_domain);
	$customize_type = get_option('opt_customize_type');
        $mailru		="";
	$odkl		="";
	$twitter	="";
	$facebook	="";
	$vkontakte	="";
	$vk_type	="";


	if($via!='') { $via_new='&via='.$via; } else { $via_new = ''; }

	if($customize_type=='original_count') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-button'><a class='mrc__share' type='button_count' href='http://connect.mail.ru/share?share_url=$url'>".__('In My World',$this->plugin_domain)."</a></div>";

		/* Odnoklassniki */
		$odkl .= "<div class='odkl-button'><a class='odkl-klass-stat' href='".$url."' onclick='ODKL.Share(this);return false;' ><span>0</span></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-horizontal'><a href='http://twitter.com/share' data-url='".$url."' data-text='".$title."' class='twitter-share-button' data-count='horizontal' data-via='".$via."'>Tweet</a></div>";

		/* Facebook */
		$facebook .= "<div class=\"fb-share-button\"><a name=\"fb_share\" type=\"button_count\" share_url='".$url."'>".__('Share',$this->plugin_domain)."</a></div>";

		/* Vkontakte */
		$vk_type .= "button";
		$vkontakte .= "<div class=\"vk-button\">\r\n";


	} else if($customize_type=='original') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-button-nocount'><a class='mrc__share' type='button' href='http://connect.mail.ru/share?share_url=$url'>".__('In My World',$this->plugin_domain)."</a></div>";

		/* Odnoklassniki */
		$odkl .= "<div class='odkl-button-count'><a class='odkl-klass' href='".$url."' onclick='ODKL.Share(this);return false;' >Класс!</a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-none'><a href='http://twitter.com/share' data-url='".$url."' data-text='".$title."' class='twitter-share-button' data-count='none' data-via='".$via."'>Tweet</a></div>";

		/* Facebook */
		$facebook .= "<div class=\"fb-share-button\"><a name=\"fb_share\" type=\"button\" share_url=\"".$url."\">".__('Share',$this->plugin_domain)."</a></div>";

		/* Vkontakte */
		$vk_type .= "button_nocount";
		$vkontakte .= "<div class=\"vk-button\">\r\n";


	} else if($customize_type=='new_year') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-myicon'>";
		$mailru .= "<a href=\"#mailru\" name=\"mailru\" onclick=\"new_window('http://connect.mail.ru/share?share_url=$url');\" title=\"Mail.ru\">";
		$mailru .= "<img src='".$this->plugin_url."images/social/new_year/mailru.png' /></a></div>";

		/* Odnoklassniki */
                $odkl .= "<div class='odkl-myicon'>";
		$odkl .= "<a href=\"#odnoklassniki\" name=\"odnoklassniki\" onclick=\"new_window('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=$url');\" title=\"Odnoklassniki\">";
		$odkl .= "<img src='".$this->plugin_url."images/social/new_year/odnoklassniki.png' /></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-myicon'>";
		$twitter .= "<a href=\"#twitter\" name=\"twitter\" onclick=\"new_window('http://twitter.com/share?&text=$title%20-%20&url=$url$via_new');\" title=\"Twitter\">";
		$twitter .= "<img src='".$this->plugin_url."images/social/new_year/twitter.png' /></a></div>";

		/* Facebook */
		$facebook .= "<div class='fb-myicon'>";
		$facebook .= "<a href=\"#facebook\" name=\"facebook\" onclick=\"new_window('http://www.facebook.com/sharer.php?u=$url');\" title=\"Facebook\">";
		$facebook .= "<img src='".$this->plugin_url."images/social/new_year/facebook.png' /></a></div>";

		/* Vkontakte */
		$vk_type .= "custom";
		$vk_text = '<img src="'.$this->plugin_url.'images/social/new_year/vkontakte.png" / title="Vkontakte">';
		$vkontakte .= "<div class='vk-myicon'>";

	} else if($customize_type=='classic') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-myicon'>";
		$mailru .= "<a href=\"#mailru\" name=\"mailru\" onclick=\"new_window('http://connect.mail.ru/share?share_url=$url');\" title=\"Mail.ru\">";
		$mailru .= "<img src='".$this->plugin_url."images/social/classic/mailru.png' /></a></div>";

		/* Odnoklassniki */
                $odkl .= "<div class='odkl-myicon'>";
		$odkl .= "<a href=\"#odnoklassniki\" name=\"odnoklassniki\" onclick=\"new_window('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=$url');\" title=\"Odnoklassniki\">";
		$odkl .= "<img src='".$this->plugin_url."images/social/classic/odnoklassniki.png' /></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-myicon'>";
		$twitter .= "<a href=\"#twitter\" name=\"twitter\" onclick=\"new_window('http://twitter.com/share?&text=$title%20-%20&url=$url$via_new');\" title=\"Twitter\">";
		$twitter .= "<img src='".$this->plugin_url."images/social/classic/twitter.png' /></a></div>";

		/* Facebook */
		$facebook .= "<div class='fb-myicon'>";
		$facebook .= "<a href=\"#facebook\" name=\"facebook\" onclick=\"new_window('http://www.facebook.com/sharer.php?u=$url');\" title=\"Facebook\">";
		$facebook .= "<img src='".$this->plugin_url."images/social/classic/facebook.png' /></a></div>";

		/* Vkontakte */
		$vk_type .= "custom";
		$vk_text = '<img src="'.$this->plugin_url.'images/social/classic/vkontakte.png" / title="Vkontakte">';
		$vkontakte .= "<div class='vk-myicon'>";


	} else if($customize_type=='soft_rect') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-myicon'>";
		$mailru .= "<a href=\"#mailru\" name=\"mailru\" onclick=\"new_window('http://connect.mail.ru/share?share_url=$url');\" title=\"Mail.ru\">";
		$mailru .= "<img src='".$this->plugin_url."images/social/soft_rect/mailru.png' /></a></div>";

		/* Odnoklassniki */
                $odkl .= "<div class='odkl-myicon'>";
		$odkl .= "<a href=\"#odnoklassniki\" name=\"odnoklassniki\" onclick=\"new_window('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=$url');\" title=\"Odnoklassniki\">";
		$odkl .= "<img src='".$this->plugin_url."images/social/soft_rect/odnoklassniki.png' /></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-myicon'>";
		$twitter .= "<a href=\"#twitter\" name=\"twitter\" onclick=\"new_window('http://twitter.com/share?&text=$title%20-%20&url=$url$via_new');\" title=\"Twitter\">";
		$twitter .= "<img src='".$this->plugin_url."images/social/soft_rect/twitter.png' /></a></div>";

		/* Facebook */
		$facebook .= "<div class='fb-myicon'>";
		$facebook .= "<a href=\"#facebook\" name=\"facebook\" onclick=\"new_window('http://www.facebook.com/sharer.php?u=$url');\" title=\"Facebook\">";
		$facebook .= "<img src='".$this->plugin_url."images/social/soft_rect/facebook.png' /></a></div>";

		/* Vkontakte */
		$vk_type .= "custom";
		$vk_text = '<img src="'.$this->plugin_url.'images/social/soft_rect/vkontakte.png" / title="Vkontakte">';
		$vkontakte .= "<div class='vk-myicon'>";

	} else if($customize_type=='soft_round') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-myicon'>";
		$mailru .= "<a href=\"#mailru\" name=\"mailru\" onclick=\"new_window('http://connect.mail.ru/share?share_url=$url');\" title=\"Mail.ru\">";
		$mailru .= "<img src='".$this->plugin_url."images/social/soft_round/mailru.png' /></a></div>";

		/* Odnoklassniki */
                $odkl .= "<div class='odkl-myicon'>";
		$odkl .= "<a href=\"#odnoklassniki\" name=\"odnoklassniki\" onclick=\"new_window('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=$url');\" title=\"Odnoklassniki\">";
		$odkl .= "<img src='".$this->plugin_url."images/social/soft_round/odnoklassniki.png' /></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-myicon'>";
		$twitter .= "<a href=\"#twitter\" name=\"twitter\" onclick=\"new_window('http://twitter.com/share?&text=$title%20-%20&url=$url$via_new');\" title=\"Twitter\">";
		$twitter .= "<img src='".$this->plugin_url."images/social/soft_round/twitter.png' /></a></div>";

		/* Facebook */
		$facebook .= "<div class='fb-myicon'>";
		$facebook .= "<a href=\"#facebook\" name=\"facebook\" onclick=\"new_window('http://www.facebook.com/sharer.php?u=$url');\" title=\"Facebook\">";
		$facebook .= "<img src='".$this->plugin_url."images/social/soft_round/facebook.png' /></a></div>";

		/* Vkontakte */
		$vk_type .= "custom";
		$vk_text = '<img src="'.$this->plugin_url.'images/social/soft_round/vkontakte.png" / title="Vkontakte">';
		$vkontakte .= "<div class='vk-myicon'>";

	} else if($customize_type=='glossy_rect') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-myicon'>";
		$mailru .= "<a href=\"#mailru\" name=\"mailru\" onclick=\"new_window('http://connect.mail.ru/share?share_url=$url');\" title=\"Mail.ru\">";
		$mailru .= "<img src='".$this->plugin_url."images/social/glossy_rect/mailru.png' /></a></div>";

		/* Odnoklassniki */
                $odkl .= "<div class='odkl-myicon'>";
		$odkl .= "<a href=\"#odnoklassniki\" name=\"odnoklassniki\" onclick=\"new_window('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=$url');\" title=\"Odnoklassniki\">";
		$odkl .= "<img src='".$this->plugin_url."images/social/glossy_rect/odnoklassniki.png' /></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-myicon'>";
		$twitter .= "<a href=\"#twitter\" name=\"twitter\" onclick=\"new_window('http://twitter.com/share?&text=$title%20-%20&url=$url$via_new');\" title=\"Twitter\">";
		$twitter .= "<img src='".$this->plugin_url."images/social/glossy_rect/twitter.png' /></a></div>";

		/* Facebook */
		$facebook .= "<div class='fb-myicon'>";
		$facebook .= "<a href=\"#facebook\" name=\"facebook\" onclick=\"new_window('http://www.facebook.com/sharer.php?u=$url');\" title=\"Facebook\">";
		$facebook .= "<img src='".$this->plugin_url."images/social/glossy_rect/facebook.png' /></a></div>";

		/* Vkontakte */
		$vk_type .= "custom";
		$vk_text = '<img src="'.$this->plugin_url.'images/social/glossy_rect/vkontakte.png" / title="Vkontakte">';
		$vkontakte .= "<div class='vk-myicon'>";

	} else if($customize_type=='glossy_round') {

		/* Mail.ru */
		$mailru .= "<div class='mailru-myicon'>";
		$mailru .= "<a href=\"#mailru\" name=\"mailru\" onclick=\"new_window('http://connect.mail.ru/share?share_url=$url');\" title=\"Mail.ru\">";
		$mailru .= "<img src='".$this->plugin_url."images/social/glossy_round/mailru.png' /></a></div>";

		/* Odnoklassniki */
                $odkl .= "<div class='odkl-myicon'>";
		$odkl .= "<a href=\"#odnoklassniki\" name=\"odnoklassniki\" onclick=\"new_window('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=$url');\" title=\"Odnoklassniki\">";
		$odkl .= "<img src='".$this->plugin_url."images/social/glossy_round/odnoklassniki.png' /></a></div>";

		/* Tweet */
		$twitter .= "<div class='twitter-myicon'>";
		$twitter .= "<a href=\"#twitter\" name=\"twitter\" onclick=\"new_window('http://twitter.com/share?&text=$title%20-%20&url=$url$via_new');\" title=\"Twitter\">";
		$twitter .= "<img src='".$this->plugin_url."images/social/glossy_round/twitter.png' /></a></div>";

		/* Facebook */
		$facebook .= "<div class='fb-myicon'>";
		$facebook .= "<a href=\"#facebook\" name=\"facebook\" onclick=\"new_window('http://www.facebook.com/sharer.php?u=$url');\" title=\"Facebook\">";
		$facebook .= "<img src='".$this->plugin_url."images/social/glossy_round/facebook.png' /></a></div>";

		/* Vkontakte */
		$vk_type .= "custom";
		$vk_text = '<img src="'.$this->plugin_url.'images/social/glossy_round/vkontakte.png" / title="Vkontakte">';
		$vkontakte .= "<div class='vk-myicon'>";

	}

		$vkontakte .="<script type=\"text/javascript\">\r\n<!--\r\ndocument.write(VK.Share.button(\r\n{\r\n";
		$vkontakte .= "  url: '$url',\r\n";
		$vkontakte .= "  title: '$title',\r\n";
		$vkontakte .= "  description: '$descr'";
		$vkontakte .= $noparse == 'true' ? ",\r\n  noparse: $noparse \r\n}, \r\n{\r\n" : "  \r\n}, \r\n{\r\n";
		$vkontakte .= "  type: '$vk_type',\r\n";      
		$vkontakte .= "  text: '$vk_text'\r\n}));";
		$vkontakte .= "\r\n-->\r\n</script></div>\r\n";

	$array_buttons = array();
	$temp = array();

	$array_buttons = array($vkontakte, $mailru, $facebook, $odkl, $twitter);
        for($i=0;$i<5;$i++) {
	        $temp[get_option($this->social_name[$i])]=$array_buttons[$i];
		if($this->buttons_show[$i]<1) {
			unset($temp[get_option($this->social_name[$i])]);
		}
		array_values($temp);
	}

	ksort($temp);

	$button_code .= implode('', $temp);

?>