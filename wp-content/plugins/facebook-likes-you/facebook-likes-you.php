<?php
/*
Plugin Name: Facebook Likes You!
Plugin URI: http://www.sproject.name/download/wp-facebook-likes-you/
Description: Facebook Likes You! is simple plugin which makes it easy to add Facebook Like button and widgetable Like box. It's fully configurable, so you can decide where to append the button. It's modified version of unsupported since 2011 Facebook Likes it!
Version: 1.0.1
Author: Piotr Sochalewski
Author URI: http://www.sproject.name/
License: GPL3
*/

/*  Copyright 2010-2011 Piotr Sochalewski (sproject@sproject.name)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('FB_LIKE_INIT')) define('FB_LIKE_INIT', 1);
else return;

$fb_like_settings = array();

$fb_like_layouts      = array('standard', 'button_count');
$fb_like_verbs        = array('like', 'recommend');
$fb_like_colorschemes = array('light', 'dark');
$fb_like_font         = array('', 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana');


if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


function fb_register_like_settings() {
    register_setting('fb_like', 'fb_like_width');
    register_setting('fb_like', 'fb_like_layout');
    register_setting('fb_like', 'fb_like_verb');
    register_setting('fb_like', 'fb_like_colorscheme');
    register_setting('fb_like', 'fb_like_font');
    register_setting('fb_like', 'fb_like_showfaces');
	register_setting('fb_like', 'fb_like_xfbml');
	register_setting('fb_like', 'fb_like_appid');
    register_setting('fb_like', 'fb_like_show_at_top');
    register_setting('fb_like', 'fb_like_show_at_bottom');
    register_setting('fb_like', 'fb_like_show_on_page');
    register_setting('fb_like', 'fb_like_show_on_post');
    register_setting('fb_like', 'fb_like_show_on_home');
    register_setting('fb_like', 'fb_like_show_on_search');
    register_setting('fb_like', 'fb_like_show_on_archive');
    register_setting('fb_like', 'fb_like_margin_top');
    register_setting('fb_like', 'fb_like_margin_bottom');
    register_setting('fb_like', 'fb_like_margin_left');
    register_setting('fb_like', 'fb_like_margin_right');
	register_setting('fb_like', 'fb_like_excl_post');
	register_setting('fb_like', 'fb_like_excl_cat');
	register_setting('fb_like', 'fb_like_css_style');

}

function fb_like_init()
{
    global $fb_like_settings;

    if ( is_admin() )
        add_action( 'admin_init', 'fb_register_like_settings' );

    add_filter('the_content', 'fb_like_button');
    add_filter('admin_menu', 'fb_like_admin_menu');
    add_filter('widget_text', 'do_shortcode');
    add_action('widgets_init', create_function('', 'return register_widget("fb_like_widget");'));

    add_option('fb_like_width', '450');
    add_option('fb_like_layout', 'standard');
    add_option('fb_like_verb', 'like');
    add_option('fb_like_font', '');
    add_option('fb_like_colorscheme', 'light');
    add_option('fb_like_showfaces', 'false');
	add_option('fb_like_xfbml', 'true');
	add_option('fb_like_appid', '');
    add_option('fb_like_show_at_top', 'false');
    add_option('fb_like_show_at_bottom', 'true');
    add_option('fb_like_show_on_page', 'false');
    add_option('fb_like_show_on_post', 'true');
    add_option('fb_like_show_on_home', 'false');
    add_option('fb_like_show_on_search', 'false');
    add_option('fb_like_show_on_archive', 'false');
    add_option('fb_like_margin_top', '0');
    add_option('fb_like_margin_bottom', '0');
    add_option('fb_like_margin_left', '0');
    add_option('fb_like_margin_right', '0');
	add_option('fb_like_excl_post', '');	
	add_option('fb_like_excl_cat', '');	
	add_option('fb_like_css_style', '');

    $fb_like_settings['width'] = get_option('fb_like_width');
    $fb_like_settings['layout'] = get_option('fb_like_layout');
    $fb_like_settings['verb'] = get_option('fb_like_verb');
    $fb_like_settings['font'] = get_option('fb_like_font');
    $fb_like_settings['colorscheme'] = get_option('fb_like_colorscheme');
    $fb_like_settings['showfaces'] = get_option('fb_like_showfaces') === 'true';
	$fb_like_settings['xfbml'] = get_option('fb_like_xfbml') === 'true';
	$fb_like_settings['appid'] = get_option('fb_like_appid');
    $fb_like_settings['showattop'] = get_option('fb_like_show_at_top') === 'true';
    $fb_like_settings['showatbottom'] = get_option('fb_like_show_at_bottom') === 'true';
    $fb_like_settings['showonpage'] = get_option('fb_like_show_on_page') === 'true';
    $fb_like_settings['showonpost'] = get_option('fb_like_show_on_post') === 'true';
    $fb_like_settings['showonhome'] = get_option('fb_like_show_on_home') === 'true';
    $fb_like_settings['showonsearch'] = get_option('fb_like_show_on_search') === 'true';
    $fb_like_settings['showonarchive'] = get_option('fb_like_show_on_archive') === 'true';
    $fb_like_settings['margin_top'] = get_option('fb_like_margin_top');
    $fb_like_settings['margin_bottom'] = get_option('fb_like_margin_bottom');
    $fb_like_settings['margin_left'] = get_option('fb_like_margin_left');
    $fb_like_settings['margin_right'] = get_option('fb_like_margin_right');
	$fb_like_settings['excl_post'] = get_option('fb_like_excl_post');
	$fb_like_settings['excl_cat'] = get_option('fb_like_excl_cat');
	$fb_like_settings['css_style'] = get_option('fb_like_css_style');
	
	$locale = defined(WPLANG) ? WPLANG : 'en_US';
		
	add_action('wp_head', 'fb_like_js_sdk');

    $plugin_path = plugin_basename( dirname( __FILE__ ) .'/languages' );
    load_plugin_textdomain( 'fb_like_trans_domain', '', $plugin_path );

}

/* Load Facebook SDK if needed */
function fb_like_js_sdk() {
	global $fb_like_settings;
	
	if($fb_like_settings['xfbml']=='true') {
	global $locale;
		
	$appid = trim($fb_like_settings['appid']);
	
	/* Deafult app ID */
	if ($appid == '')
		$appid = '113869198637480';
	
echo <<<END
<script type="text/javascript">
  window.fbAsyncInit = function() {
    FB.init({appId: '$appid', status: true, cookie: true, xfbml: true});
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol + '//connect.facebook.net/$locale/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>

END;
	}
} 

/* Show the button */
function fb_like_button($content)
{
    global $fb_like_settings;

    if (is_feed()) return $content;

    if(is_single() && !$fb_like_settings['showonpost'])
	return $content;

    if(is_page() && !$fb_like_settings['showonpage'])
	return $content;

    if(is_front_page() && !$fb_like_settings['showonhome'])
	return $content;

    if(is_search() && !$fb_like_settings['showonsearch'])
	return $content;

    if(is_archive() && !$fb_like_settings['showonarchive'])
	return $content;
	
	/* Exclude posts and pages */
	if(trim($fb_like_settings['excl_post'])!='')
	{
		$excl_post_array = explode(",", $fb_like_settings['excl_post']);
		for ( $i = 0; $i < count($excl_post_array); $i++ ) {
			$excl_post_array[$i] = trim($excl_post_array[$i]);
			if(is_single($excl_post_array[$i])==true or is_page($excl_post_array[$i])==true)
				return $content;
		}	
	}
	
	/* Exclude categories */
	if(trim($fb_like_settings['excl_cat'])!='')
	{	
		$excl_cat_array = explode(",", $fb_like_settings['excl_cat']);	
		for ( $i = 0; $i < count($excl_cat_array); $i++ ) {
			$excl_cat_array[$i] = trim($excl_cat_array[$i]);
			if(in_category($excl_cat_array[$i])==true)
				return $content;
		}
	}
 
    /* Show the button where user wants to */
    if($fb_like_settings['showattop']=='true')
		$content = generate_button() . $content;

    if($fb_like_settings['showatbottom']=='true')
	    $content .= generate_button();
	    
	return $content;
}

/* Return button's body (to fb_like_button() and shortcode [fb-like-button]) */
function generate_button()
{
	global $fb_like_settings;
	
	$margin = $fb_like_settings['margin_top'] . 'px '
		. $fb_like_settings['margin_right'] . 'px ' 
		. $fb_like_settings['margin_bottom'] . 'px '
		. $fb_like_settings['margin_left'] . 'px';	

	if($fb_like_settings['xfbml'] == true) {
		/* XFBML VERSION */
		global $locale;
		
		$url = ' href="' . urlencode(get_permalink()) . '"';
				
		if($fb_like_settings['layout']=='button_count')
		$url .= ' layout="button_count"';
		
		if($fb_like_settings['showfaces']!='true')
		$url .= ' show_faces="false"';
		
		if($fb_like_settings['width']!='450')
		$url .= ' width="' . $fb_like_settings['width'] . '"';
		
		if($fb_like_settings['verb']=='recommend')
		$url .= ' action="recommend"';
		
		if($fb_like_settings['font']!='')
		$url .= ' font="' . $fb_like_settings['font'] . '"';
		
		if($fb_like_settings['colorscheme']=='dark')
		$url .= ' colorscheme="dark"';
		
		$url .= ' style="margin:' . $margin . ';';	
		
		$url .= ($fb_like_settings['css_style']!='') ? ' ' . $fb_like_settings['css_style'] . '"' : '"';
		
		return '<script src="http://connect.facebook.net/' . $locale . '/all.js#xfbml=1" type="text/javascript"></script> <fb:like' . $url . '></fb:like>';
		/* END OF XFBML VERSION */
	}
	else {
		/* STANDARD (NON-XFBML) VERSION */
		$height = ($fb_like_settings['showfaces']=='true') ? 80 : 35;
			
		$url = urlencode(get_permalink()) . '&amp;layout=' . $fb_like_settings['layout']
		. '&amp;show_faces=' . (($fb_like_settings['showfaces']=='true')?'true':'false')
		. '&amp;width=' . $fb_like_settings['width']
		. '&amp;action=' . $fb_like_settings['verb'] 
		. '&amp;colorscheme=' . $fb_like_settings['colorscheme'] . '&amp;height=' . $height;
		
		if($fb_like_settings['font']!='')
			$url .= '&amp;font=' . urlencode($fb_like_settings['font']);
			
		return '<iframe class="fblikes" src="http://www.facebook.com/plugins/like.php?href='.$url.'" style="scrolling: no; allowTransparency: true; border:none; overflow:hidden; width:'.$fb_like_settings['width'].'px; height:'.$height.'px; margin:'.$margin.'; '.$fb_like_settings['css_style'].'"></iframe>';
		/* END OF STANDARD (NON-XFBML) VERSION */
	}
}

/* Shortcode [fb-like-button] linked to generate_button() */
add_shortcode('fb-like-button', 'generate_button');

/* Widget Facebook Like Box */
class fb_like_widget extends WP_Widget {
    /** constructor */
    function fb_like_widget() {
        parent::WP_Widget(false, $name = 'Facebook Like Box');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
    	global $fb_like_settings;
    	global $locale;
    	
        extract( $args );
        $title     = apply_filters('fly-fb-likebox', $instance['fb_likebox_title']);
        $url       = apply_filters('fly-fb-likebox', $instance['fb_likebox_url']);
        $width     = apply_filters('fly-fb-likebox', $instance['fb_likebox_width']);
        $height    = apply_filters('fly-fb-likebox', $instance['fb_likebox_height']);
        $showfaces = $instance['fb_likebox_showfaces'];
        $stream    = $instance['fb_likebox_stream'];
        $header    = $instance['fb_likebox_header'];
        
        echo $before_widget;
         
        if ( $title )
        	echo $before_title . $title . $after_title;
                  
        if($fb_like_settings['xfbml']) {
        	/* XFBML VERSION OF LIKE BOX */
        	echo "<script src=\"http://connect.facebook.net/";
        	echo $locale;
       		echo "/all.js#xfbml=1\"></script><fb:like-box href=\"";
        	echo $url;
        	echo "\" width=\"";
        	echo $width;
        	echo "\" show_faces=\"";
       		echo ($showfaces ? 'true' : 'false');
       		echo "\" stream=\"";
        	echo ($stream ? 'true' : 'false');
         	echo "\" header=\"";
        	echo ($header ? 'true' : 'false');
        	echo "\"";
         	if($fb_like_settings['colorscheme']=="dark")
            	echo " colorscheme=\"dark\"";
         	echo "></fb:like-box>"; }
        else {
            /* IFRAME VERSION OF LIKE BOX */
        	echo "<iframe src=\"http://www.facebook.com/plugins/likebox.php?href=";
        	echo $url;
        	echo "&amp;width=";
        	echo $width;
        	echo "&amp;colorscheme=";
        	echo $fb_like_settings['colorscheme'];
        	echo "&amp;show_faces=";
        	echo ($showfaces ? 'true' : 'false');
        	echo "&amp;stream=";
        	echo ($stream ? 'true' : 'false');
        	echo "&amp;header=";
        	echo ($header ? 'true' : 'false');
        	echo "&amp;height=";
        	echo $height;
        	echo "\" style=\"scrolling: no; allowTransparency: true; border:none; overflow:hidden; width:";
        	echo $width;
        	echo "px; height:";
        	echo $height;
        	echo "px;\"></iframe>";
        }
         	
       		echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['fb_likebox_title']     = strip_tags($new_instance['fb_likebox_title']);
		$instance['fb_likebox_url']       = strip_tags($new_instance['fb_likebox_url']);
		$instance['fb_likebox_width']     = strip_tags($new_instance['fb_likebox_width']);
		$instance['fb_likebox_height']    = strip_tags($new_instance['fb_likebox_height']);
		$instance['fb_likebox_showfaces'] = (isset($new_instance['fb_likebox_showfaces']) ? 1 : 0);
		$instance['fb_likebox_stream']    = (isset($new_instance['fb_likebox_stream']) ? 1 : 0);
		$instance['fb_likebox_header']    = (isset($new_instance['fb_likebox_header']) ? 1 : 0);
	
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	
    
    	global $fb_like_settings;
    	
    	/* Some default widget settings */
		$defaults = array( 'fb_likebox_width' => 292,
		                   'fb_likebox_height' => 427,
		                   'fb_likebox_stream' => 1,
		                   'fb_likebox_header' => 1 );
						   
		$instance = wp_parse_args( (array) $instance, $defaults );
    			
        $title     = esc_attr($instance['fb_likebox_title']);
        $url       = esc_attr($instance['fb_likebox_url']);
        $width     = esc_attr($instance['fb_likebox_width']);
        $height    = esc_attr($instance['fb_likebox_height']);
        $showfaces = isset($instance['fb_likebox_showfaces']) ? 1 : 0;
        $stream    = isset($instance['fb_likebox_stream']) ? 1 : 0;
        $header    = isset($instance['fb_likebox_header']) ? 1 : 0;
        
        ?>
            <p><label for="<?php echo $this->get_field_id('fb_likebox_title'); ?>"><?php _e("Title:", 'fb_like_trans_domain' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_likebox_title'); ?>" name="<?php echo $this->get_field_name('fb_likebox_title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('fb_likebox_url'); ?>"><?php _e("Facebook Page URL:", 'fb_like_trans_domain' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_likebox_url'); ?>" name="<?php echo $this->get_field_name('fb_likebox_url'); ?>" type="text" value="<?php echo $url; ?>" /></label><br /><small><?php _e("The URL of the FB Page for this Like box.", 'fb_like_trans_domain' ); ?></small></p>
            
            <p><label for="<?php echo $this->get_field_id('fb_likebox_width'); ?>"><?php _e("Width:", 'fb_like_trans_domain' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('fb_likebox_width'); ?>" name="<?php echo $this->get_field_name('fb_likebox_width'); ?>" type="number" value="<?php echo $width; ?>" /></label><br /><small><?php _e("The width of the widget in pixels.", 'fb_like_trans_domain' ); ?></small></p>
            
            <?php if($fb_like_settings['xfbml'] != true) {
            	echo "<p><label for=\"";
            	echo $this->get_field_id('fb_likebox_height');
            	echo "\">";
            	_e("Height:", 'fb_like_trans_domain' );
            	echo "<input class=\"widefat\" id=\"";
            	echo $this->get_field_id('fb_likebox_height');
            	echo "\" name=\"";
            	echo $this->get_field_name('fb_likebox_height');
            	echo "\" type=\"number\" value=\"";
            	echo $height;
            	echo "\" /></label><br /><small>";
            	_e("In pixels too. Needed if you don't use XFBML.", 'fb_like_trans_domain' );
            	echo "</small></p>";
            } ?>
            
            <p><input class="checkbox" type="checkbox" <?php checked( (bool) $instance['fb_likebox_showfaces'], true ); ?> id="<?php echo $this->get_field_id( 'fb_likebox_showfaces' ); ?>" name="<?php echo $this->get_field_name( 'fb_likebox_showfaces' ); ?>" /><label for="<?php echo $this->get_field_id( 'fb_likebox_showfaces' ); ?>">&nbsp;<?php _e("Show Faces", 'fb_like_trans_domain' ); ?></label></p>
            
            <p><input class="checkbox" type="checkbox" <?php checked( (bool) $instance['fb_likebox_stream'], true ); ?> id="<?php echo $this->get_field_id( 'fb_likebox_stream' ); ?>" name="<?php echo $this->get_field_name( 'fb_likebox_stream' ); ?>" /><label for="<?php echo $this->get_field_id( 'fb_likebox_stream' ); ?>">&nbsp;<?php _e("Stream", 'fb_like_trans_domain' ); ?></label><br /><small><?php _e("Show the profile stream for the public profile.", 'fb_like_trans_domain' ); ?></small></p>
			
			<p><input class="checkbox" type="checkbox" <?php checked( (bool) $instance['fb_likebox_header'], true ); ?> id="<?php echo $this->get_field_id( 'fb_likebox_header' ); ?>" name="<?php echo $this->get_field_name( 'fb_likebox_header' ); ?>" /><label for="<?php echo $this->get_field_id( 'fb_likebox_header' ); ?>">&nbsp;<?php _e("Header", 'fb_like_trans_domain' ); ?></label><br /><small><?php _e("Show the 'Find us on Facebook' bar at top.<br /><small>Only when either stream or connections are present.</small>", 'fb_like_trans_domain' ); ?></small></p>
            
        <?php 
    }
}

/* Admin menu page linked to fb_plugin_options() */
function fb_like_admin_menu()
{
    add_options_page('Facebook Likes You! Options', 'Facebook Likes You!', 8, __FILE__, 'fb_plugin_options');
}

function fb_plugin_options()
{
    global $fb_like_layouts;
    global $fb_like_verbs;
    global $fb_like_font;
    global $fb_like_colorschemes;
    global $fb_like_aligns;

?>

    <div class="wrap">
    <h2>Facebook Likes You! <small>by <a href="http://www.sproject.name/" target="_blank">Piotr Sochalewski</a></small></h2>

    <form method="post" action="options.php">

    <?php settings_fields('fb_like'); ?>
	
    <table class="form-table">
    	<?php if (@fopen("http://www.sproject.name/facebook-likes-you.html", "r")) {
    		echo "<tr valign=\"top\"><th scope=\"row\"><h3>";
    		_e("Important info for users", 'fb_like_trans_domain' );
    		echo "</h3></th></tr><tr valign=\"top\"><th scope=\"row\" colspan=\"2\"><iframe src=\"http://www.sproject.name/facebook-likes-you.html\" width=\"100%\" height=\"75\"><p>Your browser does not support iframes.</p></iframe></th></tr>"; }
    	?>
    		
        <tr valign="top">
            <th scope="row"><h3><?php _e("Appearance", 'fb_like_trans_domain' ); ?></h3>
			</th>
		</tr>
        <tr valign="top">
            <th scope="row"><?php _e("Width:", 'fb_like_trans_domain' ); ?></th>
            <td><input size="4" type="text" name="fb_like_width" style="text-align:right" value="<?php echo get_option('fb_like_width'); ?>" /> px</td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Layout:", 'fb_like_trans_domain' ); ?></th>
            <td>
                <select name="fb_like_layout">
                <?php
                    $curmenutype = get_option('fb_like_layout');
                    foreach ($fb_like_layouts as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Verb to display:", 'fb_like_trans_domain' ); ?></th>
            <td>
                <select name="fb_like_verb">
                <?php
                    $curmenutype = get_option('fb_like_verb');
                    foreach ($fb_like_verbs as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Font:", 'fb_like_trans_domain' ); ?></th>
            <td>
                <select name="fb_like_font">
                <?php
                    $curmenutype = get_option('fb_like_font');
                    foreach ($fb_like_font as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Color Scheme:", 'fb_like_trans_domain' ); ?></th>
			<td>
                <select name="fb_like_colorscheme">
                <?php
                    $curmenutype = get_option('fb_like_colorscheme');
                    foreach ($fb_like_colorschemes as $type)
                        echo "<option value=\"$type\"". ($type == $curmenutype ? " selected":""). ">$type</option>";
                ?>
                </select>
			</td>	
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show Faces:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_showfaces" value="true" <?php echo (get_option('fb_like_showfaces') == 'true' ? 'checked' : ''); ?>/> <small><?php _e("Automatically increase the height accordingly", 'fb_like_trans_domain' ); ?></small></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Use <span title='XFBML version is more versatile, but requires use of the JavaScript SDK' style='border-bottom: 1px dotted #CCC; cursor: help; '>XFBML</span>:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_xfbml" value="true" <?php echo (get_option('fb_like_xfbml') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Your app ID:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="21" type="text" name="fb_like_appid" value="<?php echo get_option('fb_like_appid'); ?>" /> <br /><small><?php _e("If you have no app ID, you cen leave this empty to use default Facebook app ID <code>113869198637480</code>,<br />but remember that you can get your own an app ID by <a href='http://developers.facebook.com/setup/' target='_blank'>registering your application</a>.", 'fb_like_trans_domain' ); ?></small></td>
        </tr>
        <tr valign="top">
            <th scope="row"><h3><?php _e("Position", 'fb_like_trans_domain' ); ?></h3></th>
		</tr>
		<tr valign="top">
			<th <th scope="row" colspan="2" style="line-height: 100%;"><small><?php _e("Remember that you can place it manually by <code>[fb-like-button]</code> whenever you want.", 'fb_like_trans_domain' ); ?></small></th>
		</tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show at Top:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_at_top" value="true" <?php echo (get_option('fb_like_show_at_top') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show at Bottom:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_at_bottom" value="true" <?php echo (get_option('fb_like_show_at_bottom') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show on Page:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_on_page" value="true" <?php echo (get_option('fb_like_show_on_page') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show on Post:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_on_post" value="true" <?php echo (get_option('fb_like_show_on_post') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show on Home:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_on_home" value="true" <?php echo (get_option('fb_like_show_on_home') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show on Search:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_on_search" value="true" <?php echo (get_option('fb_like_show_on_search') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Show on Archive:", 'fb_like_trans_domain' ); ?></th>
            <td><input type="checkbox" name="fb_like_show_on_archive" value="true" <?php echo (get_option('fb_like_show_on_archive') == 'true' ? 'checked' : ''); ?>/></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Margin Top:", 'fb_like_trans_domain' ); ?></th>
            <td><input size="3" type="text" style="text-align:right" name="fb_like_margin_top" value="<?php echo get_option('fb_like_margin_top'); ?>" /> px</td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Margin Bottom:", 'fb_like_trans_domain' ); ?></th>
            <td><input size="3" type="text" style="text-align:right" name="fb_like_margin_bottom" value="<?php echo get_option('fb_like_margin_bottom'); ?>" /> px</td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Margin Left:", 'fb_like_trans_domain' ); ?></th>
            <td><input size="3" type="text" style="text-align:right" name="fb_like_margin_left" value="<?php echo get_option('fb_like_margin_left'); ?>" /> px</td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e("Margin Right:", 'fb_like_trans_domain' ); ?></th>
            <td><input size="3" type="text" style="text-align:right" name="fb_like_margin_right" value="<?php echo get_option('fb_like_margin_right'); ?>" /> px</td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Exclude posts and pages:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="50" type="text" name="fb_like_excl_post" value="<?php echo get_option('fb_like_excl_post'); ?>" /> <br /><small><?php _e("You can type for each post/page ID, title, or slug seperated with commas.<br />E.g. <code>17, Irish Stew, beef-stew</code>.", 'fb_like_trans_domain' ) ?></small></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Exclude categories:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="50" type="text" name="fb_like_excl_cat" value="<?php echo get_option('fb_like_excl_cat'); ?>" /> <br /><small><?php _e("You can type for each category ID, name, or slug seperated with commas.<br />E.g. <code>9, Stinky Cheeses, blue-cheese</code>.", 'fb_like_trans_domain' ) ?></small></td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php _e("Additional CSS style:", 'fb_like_trans_domain' ); ?></th>
            <td style="line-height: 100%;"><input size="80" type="text" name="fb_like_css_style" value="<?php echo get_option('fb_like_css_style'); ?>" /> <br /><small><?php _e("Added properties will be placed between <code>style=\"</code> and <code>\"</code>. If you want refer to Like button in e.g. <strong>style.css</strong>,<br />try to use <code>iframe.fblikes</code> or if you use XFBML <code>.fb_edge_widget_with_comment</code>.", 'fb_like_trans_domain' ) ?><small></td>
        </tr>
        
        <tr valign="top">
            <th scope="row"><h3><?php _e("Help and Support", 'fb_like_trans_domain' ); ?></h3></th>
		</tr>

        <tr valign="top">
            <th scope="row" colspan="2"><strong><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=XEW99SDMTN9T6&lc=PL&item_name=Facebook%20Likes%20You%21%20Donate&item_number=fly%2ddonate&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank" style="color: rgb(255,0,0)"><?php _e("Donate to this plugin", 'fb_like_trans_domain' ); ?></a></strong></th>
        </tr>
        <tr valign="top">
            <th scope="row" colspan="2"><a href="http://www.sproject.name/download/wp-facebook-likes-you/" target="_blank"><?php _e("Read the plugin homepage and its comments", 'fb_like_trans_domain' ); ?></a></th>
        </tr>
    </table>
    
	<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>
<?php
}

fb_like_init();
?>
