<?php
/*
Plugin Name: Yet Another Simple Gallery
Plugin URI: http://wordpress.org/extend/plugins/yet-another-simple-gallery/
Description: Gallery plugin that uses the Wordpress built in gallery extension and displays it as a set of thumbnails and a main image. Clicking a thumbnail displays it as the main image.
Author: Tsfardeo.net
Version: 1.0.0
Author URI: http://www.tsfardeo.net/
*/


/* Front End */
$yasg_thumb_width = 55;
$yasg_thumb_height = 55;
$yasg_full_width = 748;
$yasg_full_height = 360;
$yasg_spacing = 10;

function print_yasg_styles () {
	$content .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/yet-another-simple-gallery/yasg.css" type="text/css" media="screen" />'."\n";
	if (file_exists(get_stylesheet_directory().'/yasg.css')) {
		$content .= '<link rel="stylesheet" href="' . get_stylesheet_directory_uri(). '/yasg.css" type="text/css" media="screen" />'."\n";
	}
	echo $content;
}
function print_yasg_scripts () {
	wp_enqueue_script('yasg', WP_PLUGIN_URL . '/yet-another-simple-gallery/yasg.js', array('jquery'));
}

function yas_gallery ($atts, $content = null) {
	global $post;
	global $yasg_thumb_width;
	global $yasg_thumb_height;
	global $yasg_spacing;

	extract( shortcode_atts( array(
	  'post_id' => '',
	  'box_width' => '600',
	  'box_height' => '770',
	  'title' => 'Gallery',
	  'thumbnail' => false,
	  'thumb_class' => 'alignright',
	  ), $atts ) );
	$post_id = $post -> ID;
	$args = array(
		'post_type'	  => 'attachment',
		'numberposts' => -1, // bring them all
		'exclude' 	  =>  get_post_thumbnail_id( $post_id ), /* exclude the featured image */
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
		'post_status' => null,
		'post_parent' => $post_id /* post id with the gallery */
	); 
	$slides = get_posts($args);
	$total_slides = count($slides);

	$strip_width = ($yasg_thumb_width + ($yasg_spacing * 2)) * $total_slides;
	/* get the full size img src */
	$main_img = wp_get_attachment_image_src($slides[0]->ID, 'yasg_full');
	$full_img = wp_get_attachment_image_src($slides[0]->ID, 'full');
	
	$main_img_url = $main_img[0];
	$full_img_url = $full_img[0];
	$main_img_alt = $lides[0] -> post_excerpt;
	
	$gallery = "\n<!-- Yet-another-simple-gallery plugin -->\n<div class=\"galleryHolder\" id=\"galleryHolder_$post_id\">";
	$gallery .= "<div class=\"mainImgHolder\"><a href=\"$full_img_url\" class=\"lightbox\"><img class=\"main_img\" src=\"$main_img_url\" alt=\"$main_img_alt\" /></a></div>";
	$gallery .= "<div class=\"gallery_thumbs\">";
	$gallery .= "<p class=\"navArrows prev\"><img id=\"main_img_$post_id\" src=\"" . WP_PLUGIN_URL . "/yet-another-simple-gallery/images/prev.png\" width=\"10px\" height=\"20px\" alt=\"previous\" title=\"previous\" class=\"arrow\" id=\"prev_$post_id\" /></p>";
	$gallery .= "<div id=\"navHolder_$post_id\" class=\"navHolder\">";
	$gallery .= "<ul id=\"nav_$post_id\" class=\"nav\" style=\"width: ".$strip_width."px;\">";
	$is_first = true;
	foreach ($slides as $slide) {
		/* get each thumbnail src */
		$thumbnailObj = wp_get_attachment_image_src($slide->ID, 'yasg_thumb');
		$thumbnailURL = $thumbnailObj[0];
		$thumb_css_class = ($is_first)?'current':'reg';
		/* get each main size img src */
		$slideObj = wp_get_attachment_image_src($slide->ID, 'yasg_full');
		$slideURL = $slideObj[0];
		$fullObj = wp_get_attachment_image_src($slide->ID, 'full');
		$fullURL = $fullObj[0];
		$gallery .= '<li style="margin: 0 '.$yasg_spacing.'px" class="'.$thumb_css_class.'"><a title="'.$fullURL.'" href="'.$slideURL.'"><img width="'.$yasg_thumb_width.'" height="'.$yasg_thumb_height.'" src="'.$thumbnailURL.'" title="'.$slide->post_excerpt.'" alt="'.$slide->post_content.'"></a></li>'.PHP_EOL;
		$is_first = false;
	}
	$gallery .= "</ul>\n";
	$gallery .= "</div>\n";
	$css_visibility = (count($slides) > 10)?'visible':'hidden';
	$gallery .= "<p style=\"visibility: $css_visibility\" class=\"navArrows next\"><img src=\"" . WP_PLUGIN_URL . "/yet-another-simple-gallery/images/next.png\" width=\"10px\" height=\"20px\" alt=\"next\" title=\"next\" class=\"arrow\" id=\"next_$post_id\" /></p>";
	$gallery .= "</div>\n";
	$gallery .= "</div>\n<!-- End Yet-another-simple-gallery-plugin -->";
	return $gallery;
}

if (!is_admin()) {
	add_action('wp_print_scripts', 'print_yasg_scripts');
	add_action('wp_head', 'print_yasg_styles');
	
	/* replace the gallery shortcode with yasg */	
	remove_shortcode('gallery');
	add_shortcode('gallery', 'yas_gallery');
}
/* End Front End */

/* add custome thumbnail size */
add_image_size( 'yasg_thumb', $yasg_thumb_width, $yasg_thumb_height, true );
add_image_size( 'yasg_full', $yasg_full_width, $yasg_full_height, false );
?>
