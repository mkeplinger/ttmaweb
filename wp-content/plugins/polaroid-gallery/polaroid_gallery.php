<?php
/*
Plugin Name: Polaroid Gallery
Plugin URI: http://www.mikkonen.info/polaroid_gallery/
Description: Used to overlay images as polaroid pictures on the current page or post and uses WordPress Media Library.
Version: 2.0.5
Author: Jani Mikkonen
Author URI: http://www.mikkonen.info
License: Unlicense
*/

/*
This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>
*/

/** admin code **/

add_action('admin_init', 'polaroid_gallery_options_init');
add_action('admin_menu', 'polaroid_gallery_options_add_page');

function polaroid_gallery_options_init() {
	register_setting('polaroid_gallery_options', 'image_size');
	register_setting('polaroid_gallery_options', 'ignore_columns');
	register_setting('polaroid_gallery_options', 'custom_text');
	register_setting('polaroid_gallery_options', 'custom_text_value');
	register_setting('polaroid_gallery_options', 'thumbnail_caption');
	register_setting('polaroid_gallery_options', 'thumbnail_option');
	register_setting('polaroid_gallery_options', 'image_option');
	register_setting('polaroid_gallery_options', 'scratches');
}

function polaroid_gallery_options_add_page() {
	add_options_page('Polaroid Gallery Options', 'Polaroid Gallery', 'manage_options', 'polaroid_gallery_options', 'polaroid_gallery_options_do_page');
}

function polaroid_gallery_options_do_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Polaroid Gallery Options') ?></h2>
		<form method="post" action="options.php">
			<?php 
			settings_fields('polaroid_gallery_options');
			$image_size			= get_option('image_size', 'large'); 
			$ignore_columns		= get_option('ignore_columns', 'no'); 
			$custom_text		= get_option('custom_text', 'no');
			$custom_text_value	= get_option('custom_text_value', 'Image');
			$thumbnail_caption	= get_option('thumbnail_caption', 'show'); 
			$thumbnail_option	= get_option('thumbnail_option', 'none'); 
			$image_option		= get_option('image_option', 'title3');
			$scratches			= get_option('scratches', 'yes');
			?>
			<h3><?php _e('Gallery Settings'); ?></h3>
			<p><?php _e('Choose the image size to display when user clicks the thumbnail. Images will be scaled to fit the screen if they are too large.'); ?></p>
			<p><?php _e('You can adjust the image sizes via Settings -> Media.'); ?></p>
			<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Image sizes') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Size') ?></span></legend>
						<label title='<?php _e("Medium"); ?>'><input type='radio' name='image_size' value='medium' <?php checked('medium', $image_size); ?>/> <?php _e("Medium"); ?></label><br />
						<label title='<?php _e("Large"); ?>'><input type='radio' name='image_size' value='large' <?php checked('large', $image_size); ?>/> <?php _e("Large"); ?></label><br />
						<label title='<?php _e("Full size"); ?>'><input type='radio' name='image_size' value='full' <?php checked('full', $image_size); ?>/> <?php _e("Full size"); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Ignore Gallery columns') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Ignore Gallery columns') ?></span></legend>
						<label title='<?php _e("No"); ?>'><input type='radio' name='ignore_columns' value='no' <?php checked('no', $ignore_columns); ?>/> <?php _e("No"); ?></label><br />
						<label title='<?php _e("Yes"); ?>'><input type='radio' name='ignore_columns' value='yes' <?php checked('yes', $ignore_columns); ?>/> <?php _e("Yes (good for fluid layouts)"); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Add scratches to thumbnails') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Add scratches to thumbnails') ?></span></legend>
						<label title='<?php _e("No"); ?>'><input type='radio' name='scratches' value='no' <?php checked('no', $scratches); ?>/> <?php _e("No"); ?></label><br />
						<label title='<?php _e("Yes"); ?>'><input type='radio' name='scratches' value='yes' <?php checked('yes', $scratches); ?>/> <?php _e("Yes"); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Custom text for "Image"') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Custom text for "Image"') ?></span></legend>
						<label title='<?php _e("No"); ?>'><input type='radio' name='custom_text' value='no' <?php checked('no', $custom_text); ?>/> <?php _e("No (localized default text)"); ?></label><br />
						<label title='<?php _e("Yes"); ?>'><input type='radio' name='custom_text' value='yes' <?php checked('yes', $custom_text); ?>/> <?php _e("Yes"); ?></label><br />
						<label title='<?php _e("Text"); ?>'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Text"); ?>: <input type='text' name='custom_text_value' value='<?php print $custom_text_value; ?>' /></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Thumbnail text visibility') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Thumbnail text visibility') ?></span></legend>
						<label title='<?php _e("Always visible"); ?>'><input type='radio' name='thumbnail_caption' value='show' <?php checked('show', $thumbnail_caption); ?>/> <?php _e("Always visible"); ?></label><br />
						<label title='<?php _e("Visible with mouseover"); ?>'><input type='radio' name='thumbnail_caption' value='hide' <?php checked('hide', $thumbnail_caption); ?>/> <?php _e("Visible with mouseover"); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Thumbnail text settings') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Thumbnail text settings') ?></span></legend>
						<label title='<?php _e("None"); ?>'><input type='radio' name='thumbnail_option' value='none' <?php checked('none', $thumbnail_option); ?>/> <?php _e("None"); ?></label><br />
						<label title='<?php _e("Caption text"); ?>'><input type='radio' name='thumbnail_option' value='caption' <?php checked('caption', $thumbnail_option); ?>/> <?php _e("Caption text"); ?></label><br />
						<label title='<?php _e("Image #"); ?>'><input type='radio' name='thumbnail_option' value='image1' <?php checked('image1', $thumbnail_option); ?>/> <?php _e("Image #"); ?></label><br />
						<label title='<?php _e("Image #/#"); ?>'><input type='radio' name='thumbnail_option' value='image2' <?php checked('image2', $thumbnail_option); ?>/> <?php _e("Image #/#"); ?></label><br />
						<label title='<?php _e("#"); ?>'><input type='radio' name='thumbnail_option' value='number1' <?php checked('number1', $thumbnail_option); ?>/> <?php _e("#"); ?></label><br />
						<label title='<?php _e("#/#"); ?>'><input type='radio' name='thumbnail_option' value='number2' <?php checked('number2', $thumbnail_option); ?>/> <?php _e("#/#"); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Image text settings') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Image text settings') ?></span></legend>
						<label title='<?php _e("None"); ?>'><input type='radio' name='image_option' value='none' <?php checked('none', $image_option); ?>/> <?php _e("None"); ?></label><br />
						<label title='<?php _e("Title text"); ?>'><input type='radio' name='image_option' value='title1' <?php checked('title1', $image_option); ?>/> <?php _e("Title text"); ?></label><br />
						<label title='<?php _e("#  Title text"); ?>'><input type='radio' name='image_option' value='title2' <?php checked('title2', $image_option); ?>/> <?php _e("#  Title text"); ?></label><br />
						<label title='<?php _e("#/#  Title text"); ?>'><input type='radio' name='image_option' value='title3' <?php checked('title3', $image_option); ?>/> <?php _e("#/#  Title text"); ?></label><br />
						<label title='<?php _e("Image #  Title text"); ?>'><input type='radio' name='image_option' value='title4' <?php checked('title4', $image_option); ?>/> <?php _e("Image #  Title text"); ?></label><br />
						<label title='<?php _e("Image #/#  Title text"); ?>'><input type='radio' name='image_option' value='title5' <?php checked('title5', $image_option); ?>/> <?php _e("Image #/#  Title text"); ?></label><br />
						<label title='<?php _e("Image #"); ?>'><input type='radio' name='image_option' value='image1' <?php checked('image1', $image_option); ?>/> <?php _e("Image #"); ?></label><br />
						<label title='<?php _e("Image #/#"); ?>'><input type='radio' name='image_option' value='image2' <?php checked('image2', $image_option); ?>/> <?php _e("Image #/#"); ?></label><br />
						<label title='<?php _e("#"); ?>'><input type='radio' name='image_option' value='number1' <?php checked('number1', $image_option); ?>/> <?php _e("#"); ?></label><br />
						<label title='<?php _e("#/#"); ?>'><input type='radio' name='image_option' value='number2' <?php checked('number2', $image_option); ?>/> <?php _e("#/#"); ?></label><br />
					</fieldset>
				</td>
			</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php
}

/** plugin code **/

$polaroid_gallery_plugin_prefix = WP_PLUGIN_URL . "/polaroid-gallery/";

if (!is_admin()) {
	// add javascript to head
	wp_enqueue_script('jquery.easing-1.3', ($polaroid_gallery_plugin_prefix.'js/jquery.easing-1.3.pack.js'), array('jquery'));
	wp_enqueue_script('jquery.mousewheel-3.0.4', ($polaroid_gallery_plugin_prefix.'js/jquery.mousewheel-3.0.4.pack.js'), array('jquery'));
	wp_enqueue_script('jquery.fancybox-1.3.4', ($polaroid_gallery_plugin_prefix.'js/jquery.fancybox-1.3.4.pack.js'), array('jquery'));
	wp_enqueue_script('polaroid_gallery-2.0', ($polaroid_gallery_plugin_prefix.'js/polaroid_gallery-2.1.js'), array('jquery'));
	// add css to head
	wp_enqueue_style('polaroid_gallery_fancybox', ($polaroid_gallery_plugin_prefix . 'css/jquery.fancybox-1.3.4.css'));
	wp_enqueue_style('polaroid_gallery_style', ($polaroid_gallery_plugin_prefix . 'css/polaroid_gallery.css'));
}

function polaroid_gallery_shortcode($output, $attr) {
	global $post, $wp_locale;
	
	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}
	
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));
	
	$image_size			= get_option('image_size', 'large'); 
	$ignore_columns		= get_option('ignore_columns', 'no'); 
	$thumbnail_caption	= get_option('thumbnail_caption', 'show');
	
	$id = intval($id);
	if ( 'RAND' == $order ) {
		$orderby = 'none';
	}
	
	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}
	
	if ( empty($attachments) ) {
		return '';
	}
	
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}
	
	$columns = intval($columns);
	if( $ignore_columns == 'yes' ) {
		$columns = 0;
	}
	$output .= "
		<div class='polaroid-gallery galleryid-{$id}'>";
	
	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$image = wp_get_attachment_image_src($id, $size=$image_size, $icon = false); 
		$thumb = wp_get_attachment_image_src($id, $size='thumbnail', $icon = false); 
		$title = wptexturize(trim($attachment->post_title));
		$alt = wptexturize(trim($attachment->post_excerpt));
		$caption_class = '';
		if($thumbnail_caption == 'show') {
			$caption_class = ' showcaption';
		}
		$output .= '
			<a href="'. $image[0] .'" title="'. $title .'" rel="polaroid_'. $post->ID .'" class="polaroid-gallery-item'. $caption_class .'"><span class="polaroid-gallery-image" title="'. $alt .'" style="background-image: url('. $thumb[0] .'); width: '. $thumb[1] .'px; height: '. $thumb[2] .'px;"></span></a>';
		
		if ( $columns > 0 && ++$i % $columns == 0 ){
			$output .= '
			<br style="clear: both;" />';
		}
	}
	if ( $columns > 0 && $i % $columns != 0 ) {
		$output .= '
			<br style="clear: both;" />';
	}
	if( $ignore_columns == 'yes' ) {
		$output .= '
			<br style="clear: both;" />';
	}
	$output .= "
		</div>\n";
	
	return $output;
}

function polaroid_gallery_head() {
	global $polaroid_gallery_plugin_prefix;
	
	$custom_text		= get_option('custom_text', 'no');
	$custom_text_value	= get_option('custom_text_value', 'Image');
	$thumbnail_option	= get_option('thumbnail_option', 'none'); 
	$image_option		= get_option('image_option', 'title3'); 
	$scratches			= get_option('scratches', 'yes');
	
	$text2image = __('Image');
	if($custom_text == 'yes') {
		$text2image = $custom_text_value;
	}
	
	$polaroid_gallery_script = "
<script type=\"text/javascript\">
/* <![CDATA[ */
var polaroid_gallery = { 
	text2image: '". $text2image ."', thumbnail: '". $thumbnail_option ."', image: '". $image_option ."', scratches: '". $scratches ."' 
};
/* ]]> */
</script>
<!--[if lte IE 8]>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"". $polaroid_gallery_plugin_prefix ."css/jquery.fancybox-old-ie.css\" />
<![endif]-->\n";
	print $polaroid_gallery_script;
}

add_action('wp_head', 'polaroid_gallery_head');
add_filter('post_gallery', 'polaroid_gallery_shortcode', 10, 2);

?>