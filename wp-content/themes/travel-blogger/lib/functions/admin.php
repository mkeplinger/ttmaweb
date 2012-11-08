<?php

function esp_background_div_admin() { ?>
	<h3><?php _('Background Image'); ?></h3>
	<table class="form-table">
	<tbody>
	<tr valign="top">
	<th scope="row"><?php _('Preview'); ?></th>
	<td>
	<?php
	$background_styles = '';
	if ( $bgcolor = get_background_color() )
		$background_styles .= 'background-color: #' . $bgcolor . ';';

	if ( get_background_image() ) {
		// background-image URL must be single quote, see below
		$background_styles .= ' background-image: url(\'' . get_theme_mod('background_image_thumb', '%s/images/backgrounds/background-default-thumbnail.jpg') . '\');'
			. ' background-repeat: ' . get_theme_mod('background_repeat', 'no-repeat') . ';'
			. ' background-position: top ' . get_theme_mod('background_position_x', 'center');
	}
	?>
	<div id="custom-background-image" style="<?php echo $background_styles; ?>"><?php // must be double quote, see above ?>
	<?php if ( get_background_image() ) { ?>
	<img class="custom-background-image" src="<?php echo get_theme_mod('background_image_thumb', '%s/images/backgrounds/background-default-thumbnail.jpg'); ?>" style="visibility:hidden;" alt="" /><br />
	<img class="custom-background-image" src="<?php echo get_theme_mod('background_image_thumb', '%s/images/backgrounds/background-default-thumbnail.jpg'); ?>" style="visibility:hidden;" alt="" />
	<?php } ?>
	</div>
<?php }

function esp_admin_header_style() {
?>
<style type="text/css">
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
</style>
<?php
}

function exp_load_theme_scripts() {
	wp_enqueue_script( 'exp-jscolor', get_stylesheet_directory_uri() . '/scripts/jscolor/jscolor.js', false, '1.3.1', false );
	wp_enqueue_script( 'exp-js', get_stylesheet_directory_uri() . '/lib/admin/js/admin.js', 900);
}
function exp_load_theme_styles() {
	wp_enqueue_style( 'exp-theme-styles', get_stylesheet_directory_uri() . '/lib/admin/css/admin.css', 997, '1.3.1', false );
}

// Populates new settings in the custom style sheet
function exp_update_style_sheet() {
 	$styles = get_option('exp_theme_colors');
	$fonts = get_option('exp_theme_font');
	$data='';

	if (!empty($fonts)) { 
		foreach ($fonts['sizes'] as $font => $key ) {
			if(!empty($key['value']))
				$data .= $key['css']."}"."\n";
	 	}
		if(!empty($fonts['value']))
			$data .= $fonts['css'].$fonts['value']."}"."\n";
	}
	if (!empty($styles)) {
	 	foreach ($styles as $style) {
			$data .= $style['css'].$style['value']."}"."\n";
	 	}
	}
	
	update_option('exp_custom_css',$data);
}

//Adds feature checkbox to admin area
function exp_feature_content() {
global $post;	
	
$feature = false;
if (get_post_meta($post->ID,'exp_main_feature',true)) {
	$feature = true;
} 
$feature_widget = false;
if (get_post_meta($post->ID,'exp_widget_feature',true)) {
	$feature_widget = true;
}
$exp_cat_feature = false;
if (get_post_meta($post->ID,'exp_cat_feature',true)) {
	$exp_cat_feature = true;
}

echo'<input type="hidden" name="exp_feature_noncename" id="exp_feature_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />'; 
?>
	<input type="checkbox" id="exp_main_feature" name="exp_main_feature" <?php if($feature) echo'checked="checked"'?>> <label for="exp_main_feature">Feature Post on Home Page</label>
	<br/><br/>
	<input type="checkbox" id="exp_cat_feature" name="exp_cat_feature" <?php if($exp_cat_feature) echo'checked="checked"'?>> <label for="exp_cat_feature">Feature in Category</label>
	<br/><br/>
	<input type="checkbox" id="exp_widget_feature" name="exp_widget_feature" <?php if($feature_widget) echo'checked="checked"'?>> <label for="exp_widget_feature">Feature in Featured Content Widget</label>
<?php }

//Adds feature checkbox to admin area
function exp_post_geo_address() {
global $post;
$feature_widget = false;

$location = (array) get_post_meta($post->ID,'exp_post_geo_address',true);

echo'<input type="hidden" name="exp_feature_noncename" id="exp_feature_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />'; 
?>
	<p>By entering an address here, you can load a map within the content using the shortcode <b>[tb_google_map]</b>, or simply enabling the map widget in one of the sidebars.</p>
	<input type="text" id="exp_post_geo_address" name="exp_post_geo_address" value="<?php if(isset($location['address'])) echo $location['address']; ?>" style="width:98%; margin-top:4px;">
	<p class="howto">You must enter a valid address. It can be either the full address of a location, or just city/state name or just a zipcode, e.g.: 1600 Amphitheatre Parkway Mountain View, CA 94043 <b>or</b> San Diego, CA <b>or</b> 92110</p>
<?php }

// Adds custom features to post page
function exp_add_feature_boxes() {
	if (function_exists('add_meta_box') ) {
		add_meta_box( 'exp-feature-posts', 'Feature Post', 'exp_feature_content', 'post', 'side', 'high' );
		add_meta_box( 'exp-geo-location-posts', 'Enter Address Related to Post', 'exp_post_geo_address', 'post', 'side', 'high' );
	} 
}

// Saves custom feature post data. Triggered when post is saved.
function exp_save_feature_data( $post_id ) {
	global $post;
	// Verify  
	if ( !wp_verify_nonce( isset($_POST['exp_feature_noncename']), plugin_basename(__FILE__) )) {  
		return $post_id;  
	}  
	
	if ( !current_user_can( 'edit_post', $post_id ))  
		return $post_id;
	
	$data = isset($_POST['exp_main_feature']) ? $_POST['exp_main_feature'] : '';
	$data_widget = isset($_POST['exp_widget_feature']) ? $_POST['exp_widget_feature'] : '';
	$geo_data_post = isset($_POST['exp_post_geo_address']) ? $_POST['exp_post_geo_address'] : '';
	$exp_cat_feature = isset($_POST['exp_cat_feature']) ? $_POST['exp_cat_feature'] : '';
	
	// Store main feature
	if ($data == "") {
		delete_post_meta($post_id, 'exp_main_feature', get_post_meta($post_id, 'exp_main_feature', true));  
	} elseif ($data != get_post_meta($post_id, 'exp_main_feature', true)) {
		update_post_meta($post_id, 'exp_main_feature', $data);  
	}
	
	// Store category feature
	if ($exp_cat_feature == "") {
		delete_post_meta($post_id, 'exp_cat_feature', get_post_meta($post_id, 'exp_cat_feature', true));  
	} elseif ($exp_cat_feature != get_post_meta($post_id, 'exp_cat_feature', true)) {
		update_post_meta($post_id, 'exp_cat_feature', $exp_cat_feature);  
	}
	
	// Store widget feature
	if ($data_widget == "") {
		delete_post_meta($post_id, 'exp_widget_feature', get_post_meta($post_id, 'exp_widget_feature', true));  
	} elseif ($data_widget != get_post_meta($post_id, 'exp_widget_feature', true)) {
		update_post_meta($post_id, 'exp_widget_feature', $data_widget);  
	}
	
	// Store post geo data
	$geo_data = (array) get_post_meta($post_id, 'exp_post_geo_address', true);
	$geo_data['address'] = isset($geo_data['address']) ? $geo_data['address'] : '';
	if ($geo_data_post == "") {
		delete_post_meta($post_id, 'exp_post_geo_address', get_post_meta($post_id, 'exp_post_geo_address', true));  
	} elseif ($geo_data_post != $geo_data['address']) {
		// Look up lat and long for inputted address
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($geo_data_post).'&sensor=false';
		$json_data = wp_remote_retrieve_body(wp_remote_get($url));
		$json = json_decode($json_data);
		if($json_data =='' || $json->status !='OK')
			return $post_id;
	
		$geo_data = array();
		$geo_data['address'] = $geo_data_post;
		$geo_data['lat'] = $json->results[0]->geometry->location->lat;
		$geo_data['lng'] = $json->results[0]->geometry->location->lng;
		update_post_meta($post_id, 'exp_post_geo_address', $geo_data);
	}

}
add_action('admin_menu', 'exp_add_feature_boxes');
add_action('save_post', 'exp_save_feature_data');

// Check for updates
add_action('admin_init','exp_check_theme_ver');
$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
$local_version = $theme_data['Version'];
$ver_last_check = get_option('exp_last_ver_check');
$new_ver_notice = get_option('exp_new_ver');
function exp_check_theme_ver() {
	global $local_version,$theme_data,$new_ver_notice,$ver_last_check;
	
	if($new_ver_notice == true) {
		add_action('admin_notices','exp_new_theme_notice');
	}
	
	// Checks every three days
	$update_check_freq = 3600 * 72;
	$now = time();

	if(empty($ver_last_check)) {
		add_option('exp_last_ver_check',$now);
		return;
	}
	
	$time_ago = $now - $ver_last_check;
	if ( $time_ago > $update_check_freq ) {
		$remote_version = wp_remote_retrieve_body(wp_remote_get($theme_data["AuthorURI"].'current.txt'));
		if ($remote_version != '') {
			$local = str_replace(".", "", $local_version);
			$remote = str_replace(".", "", $remote_version);
			if( trim($local) != trim($remote) ) {
				update_option('exp_new_ver',true);
			} else {
				delete_option('exp_new_ver');
				delete_option('exp_dont_bother');
			}
		}
		update_option('exp_last_ver_check',$now);
	}
	
	if( $local_version != get_option('exp_theme_ver') ) {
		update_option('exp_theme_ver',$local_version);
		delete_option('exp_new_ver');
	}
}

function exp_new_theme_notice() { 
	global $pagenow,$theme_data;
	if ( $pagenow == "themes.php" || $pagenow == 'index.php') {
?>
		<div id="message" class="updated fade">
			<p><?php echo $theme_data['Name']; ?> theme has a new version available. Please <a href="<?php echo $theme_data['AuthorURI'] ?>download/" target="_blank">visit here</a> to download the latest version.</p>
		</div>
<?php
	}
}

// Reminder to support theme
add_action('admin_init','exp_support_reminder');
function exp_support_reminder() {
	$footerlinks = get_option('exp_show_footer_links');
	
	if(isset($footerlinks['add_credit']) && $footerlinks['add_credit'] == '' && !get_option('exp_dont_bother')) {
		add_action('admin_notices','exp_support_notice');
	}
}
function exp_support_notice() { 
	global $pagenow;
	if ( $pagenow == "themes.php" || $pagenow == 'index.php') {
	$url = admin_url('themes.php?page=exp-theme-settings');
?>
		<div id="message" class="updated fade">
			<p>If you like this theme, please consider activating our sponsored links under APPEARANCE > FOOTER (or just <a href="<?php echo wp_nonce_url($url.'&activatelink=1','exp_activate_link-optin') ?>">Click Here</a> to activate). While not required, your support for this theme is greatly appreciated. To hide this message and leave the links inactive <a href="<?php echo wp_nonce_url($url.'&dontshow=1','exp_dont_show_again-optout') ?>">click here</a>. Thank you.</p>
		</div>
<?php
	}
}

// Updates old address format to new one.
function exp_get_geo_latlng() {
	global $wpdb;
	set_time_limit(0);
	
	$query = sprintf("
	    SELECT wposts.*,wpostmeta.*
	    FROM %s wposts, %s wpostmeta
	    WHERE wposts.ID = wpostmeta.post_id 
	    AND wpostmeta.meta_key = 'exp_post_geo_address'
	    AND wposts.post_status = 'publish'
	 ",
	mysql_real_escape_string($wpdb->posts),
	mysql_real_escape_string($wpdb->postmeta)
	);
	$results = $wpdb->get_results($query, ARRAY_A);
	
	if($results) {
		foreach ($results as $g ) {
			$data = maybe_unserialize($g['meta_value']);
			if(is_string($data)) {
				$post_id = $g['ID'];
				$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($data).'&sensor=false';
				$json_data = wp_remote_retrieve_body(wp_remote_get($url));
				$json = json_decode($json_data);
				if($json_data =='' || $json->status !='OK')
					continue;

				$geo_data = array();
				$geo_data['address'] = $data;
				$geo_data['lat'] = $json->results[0]->geometry->location->lat;
				$geo_data['lng'] = $json->results[0]->geometry->location->lng;
				update_post_meta($post_id, 'exp_post_geo_address', $geo_data);
				
				sleep(1);
			}
		}
	}
}

?>