<?php
/*
Plugin Name: Simple Facebook Share Button
Plugin URI: http://www.ethitter.com/plugins/simple-facebook-share-button/
Description: Painlessly add a Facebook® Share button to your posts and/or pages. Supports all five button types, including custom button text, and placement above or below content. Includes compatibility modes to ensure seamless theme integration. Button can also be added using a shortcode or by inserting a function in your template.
Author: Erick Hitter
Version: 2.0.1
Author URI: http://www.ethitter.com/
*/

require( 'simple_fb_share_button_options.php' );

/*
 * Set up options, convert old options, add filters if automatic display is enabled, and enqueue scripts
 * @uses get_option, update_option, add_filter, wp_enqueue_script
 * @return null
 */
function SFBSB_setup() {
	//Convert options or set up defaults
	if( !$options = get_option( 'SFBSB' ) ) {
		if( get_option( 'SFBSB_button' ) ) {
			$options = array();
			$keys = array(
				'SFBSB_button' => 'button',
				'SFBSB_display' => 'display',
				'SFBSB_content' => 'content',
				'SFBSB_placement' => 'placement',
				'SFBSB_style' => 'style',
				'SFBSB_align' => 'align',
				'SFBSB_custom_text' => 'custom_text',
				'SFBSB_tpad' => 'tpad',
				'SFBSB_bpad' => 'bpad',
				'SFBSB_lpad' => 'lpad',
				'SFBSB_rpad' => 'rpad',
				'SFBSB_compatibility' => 'compatibility',
				'SFBSB_uninstall' => 'uninstall'
			);
			
			foreach( $keys as $old_key => $new_key ) {
				if( $old_option = get_option( $old_key ) ) $options[ $new_key ] = $old_option;
				delete_option( $old_key );
			}
		}
		else {
			$options = array(
				'display' => 0,
				'button' => 'button',
				'placement' => 'tl',
				'content' => 'post'
			);
		}
		
		update_option( 'SFBSB', $options );
	}
	
	//Add filters if set to automatic display
	if( $options[ 'display' ] == 1 ) add_filter( 'the_content', 'SFBSB_auto' );
	if( $options[ 'display' ] == 1 && $options[ 'content-excerpt' ] == 1 ) add_filter( 'the_excerpt', 'SFBSB_auto' );

	//Register scripts 
	wp_enqueue_script( 'FB-Loader', 'http://static.ak.fbcdn.net/connect.php/js/FB.Loader', array(), 322597, true );
	wp_enqueue_script( 'FB-Share', 'http://static.ak.fbcdn.net/connect.php/js/FB.Share', array( 'FB-Loader' ), 322597, true );
}
add_action( 'plugins_loaded', 'SFBSB_setup' );

/*
 * Remove plugin options on deactivation if requested to do so.
 * @uses get_option, delete_option
 * @action register_deactivation_hook
 */
function SFBSB_deactivate() {
	$options = get_option( 'SFBSB' );
	if ($options[ 'uninstall' ] == 1 ) delete_option('SFBSB');
}
register_deactivation_hook( __FILE__, 'SFBSB_deactivate' );

/*
 * On-demand Share button implementation.
 * Must be used within the loop.
 * @params string button type, string custom button text
 * @uses get_permalink
 * @return html
 */
function SFBSB_direct( $button = 'button_count', $custom_text = false ) {
	//Possible button types
	$buttons = array(
		'box_count',
		'button_count',
		'button',
		'icon'
	);

	//Check button type
	if( $button == 'icon_link' && $custom_text ) $button_type = $button;
	elseif( in_array( $button, $buttons ) ) $button_type = $button;
	else $button_type = 'button_count';

	return '<a name="fb_share" type="' . $button_type . '" share_url="' . get_permalink() . '">' . $custom_text . '</a>';
}

/*
 * Shortcode Share button implementation.
 * Must be used within the loop.
 * @params array shortcode attributes
 * @uses shortcode_atts, get_permalink, add_shortcode
 */
function SFBSB_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'button' => 'button',
		'align' => false,
		'style' => 'float:left;',
		'custom_text' => ''
	), $atts ) );
	
	//Button type
	if( $button == 'custom' ) $button = 'icon_link';
	
	$style_do = ' style="' . $style . '"';
	
	if( $align !== false ) $align_do = ' align="' . $align . '"';

	return '<div' . $align_do . $style_do . '><a name="fb_share" type="' . $button_type . '" share_url="' . get_permalink() . '">' . $custom_text . '</a></div>';
}
add_shortcode( 'SFBSB', 'SFBSB_shortcode' );

/*
 * Add button to content via the_content and the_excerpt filters
 * @param string $content post content
 * @uses $post, get_option
 * @return string post content
 */
function SFBSB_auto( $content ) {
	global $post;
	
	$options = get_option( 'SFBSB' );

	//Button
	if( $options[ 'button' ] == 'custom' ) {
		$button_type = 'icon_link';
	}
	else $button_type = $options[ 'button'];

	//Padding
	$default_padding = '5px';
	
	$_padding = array();
	
	if( ( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] == 'tr' ) && !isset( $options[ 'tpad' ] ) ) $options[ 'tpad' ] = '0';
	if( ( $options[ 'placement' ] == 'bl' || $options[ 'placement' ] == 'br' ) && !isset( $options[ 'bpad' ] ) ) $options[ 'bpad' ] = '0';
	if( ( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] == 'bl' ) && !isset( $options[ 'lpad' ] ) ) $options[ 'lpad' ] = '0';
	if( ( $options[ 'placement' ] == 'tr' || $options[ 'placement' ] == 'br' ) && !isset( $options[ 'rpad' ] ) ) $options[ 'rpad' ] = '0';

	$_padding[ 0 ] = strlen( $options[ 'tpad' ] ) > 0 ? $options[ 'tpad' ] . 'px' : $default_padding;
	$_padding[ 1 ] = strlen( $options[ 'rpad' ] ) > 0 ? $options[ 'rpad' ] . 'px' : $default_padding;
	$_padding[ 2 ] = strlen( $options[ 'bpad' ] ) > 0 ? $options[ 'bpad' ] . 'px' : $default_padding;
	$_padding[ 3 ] = strlen( $options[ 'lpad' ] ) > 0 ? $options[ 'lpad' ] . 'px' : $default_padding;

	$padding = trim( implode( ' ', $_padding ) );
	
	//Placement
	if( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] == 'bl' ) $align=' align="left"';
	elseif( $options[ 'placement' ] == 'tr' || $options[ 'placement' ] == 'br' ) $align=' align="right"';
	
	//Compatibility mode
	if( $options[ 'compatibility' ] == 1 ) $float = 'none';
	elseif( $options[ 'compatibility' ] == 2 ) {
		if( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] == 'bl' ) $float = 'left; clear:right';
		elseif( $options[ 'placement' ] == 'tr' || $options[ 'placement' ] == 'br' ) $float = 'right; clear:left';
	}
	elseif( $options[ 'compatibility' ] == 3 ) {
		if( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] == 'bl' ) $float = 'none; clear:right';
		elseif( $options[ 'placement' ] == 'tr' || $options[ 'placement' ] == 'br' ) $float = 'none; clear:left';
	}
	else {
		if( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] ==' bl' ) $float = 'left';
		elseif( $options[ 'placement' ] == 'tr' || $options[ 'placement' ] == 'br' ) $float = 'right';
	}
		
	//Style
	if( !empty( $options[ 'style' ] ) ) $style_do = ' style="' . $style . '"';
	else $style_do = ' style="float: ' . $float . '; padding: ' . $padding . ';"';
	
	
	//Build button
	$fb = '<div' . $align . $style_do . '><a name="fb_share" type="' . $button_type . '" share_url="' . get_permalink() . '">' . $options[ 'custom_text' ] . '</a></div>';

	//Add button to $content
	if ( ( $post->post_type == $options[ 'content' ] || $options[ 'content' ] == 'both' ) && ( $options[ 'placement' ] == 'tl' || $options[ 'placement' ] == 'tr' ) ) $content = $fb . $content;
	elseif( ( $post->post_type == $options[ 'content' ] || $options[ 'content' ] == 'both' ) && ( $options[ 'placement' ] == 'bl' || $options[ 'placement' ] == 'br' ) ) $content = $content . $fb;
	
	return $content;
}

/*
 * LEGACY FUNCTION
 * @uses _deprecated_function
 * @return text
 */
function SFBSB_do() {
	_deprecated_function( __FUNCTION__, '2.0', 'SFBSB_direct()' );
	return '<!-- Simple Facebook Share Button: this function is no longer supported. See the plugin documentation and replace this function with SFBSB_direct(). -->';
}
?>