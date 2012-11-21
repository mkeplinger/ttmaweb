<?php
/* wppa-tinymce.php
* Pachkage: wp-photo-album-plus
*
*
* Version 4.7.11
*
*/

if ( ! defined( 'ABSPATH' ) )
    die( "Can't load this file directly" );
 
class wppaGallery
{
    function __construct() {
    add_action( 'admin_init', array( $this, 'action_admin_init' ) );
	}
	 
	function action_admin_init() {
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts or pages
		if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
			if (!is_plugin_active('ultimate-tinymce/main.php')) {
				add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			}
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );	
		}
	}
	 
	function filter_mce_button( $buttons ) {
		// add a separation before our button.
		array_push( $buttons, '|', 'mygallery_button' );
		return $buttons;
	}
	 
	function filter_mce_plugin( $plugins ) {
		// this plugin file will work the magic of our button
		$plugins['mygallery'] = plugin_dir_url( __FILE__ ) . 'wppa-tinymce.js';
		return $plugins;
	}
 
}
 
$wppagallery = new wppaGallery();

add_action('admin_head', 'wppa_inject_js');

function wppa_inject_js() {
	// Things that wppa-tinymce.js needs to know
	echo('<script type="text/javascript">'."\n");
	echo('/* <![CDATA[ */'."\n");
		echo("\t".'wppaImageDirectory = "'.wppa_get_imgdir().'";'."\n");
		echo("\t".'wppaAjaxUrl = "'.admin_url('admin-ajax.php').'";'."\n");
		echo("\t".'wppaThumbDirectory = "'.WPPA_UPLOAD_URL.'/thumbs/";'."\n");
		echo("\t".'wppaNoPreview = "'.__('No Preview available', 'wppa').'";'."\n");
	echo("/* ]]> */\n");
	echo("</script>\n");
}

function wppa_make_tinymce_dialog() {
global $wpdb;
global $wppa_opt;

	$result = 
	'<div id="mygallery-form">'.
		'<div style="height:156px; background-color:#eee; overflow:auto; margin-top:10px;" >'.
			'<div id="mygallery-album-preview" style="text-align:center;font-size:48px; line-height:21px; color:#fff;" class="mygallery-album" ><br /><br /><br />'.
			__('Album Preview', 'wppa').'<br /><span style="font-size:12px; color:#777" ><br/>'.__('A maximum of 100 photos can be previewd', 'wppa').'</span></div>'.
			'<div id="mygallery-photo-preview" style="text-align:center;font-size:48px; line-height:21px; color:#fff; display:none;" class="mygallery-photo" ><br /><br /><br />'.
			__('Photo Preview', 'wppa').'</div>'.
		'</div>'.
		'<table id="mygallery-table" class="form-table">'.
		
			'<tr>'.
				'<th><label for="mygallery-type">'.__('Type of Gallery display:', 'wppa').'</label></th>'.
				'<td>'.
					'<select id="mygallery-type" name="type" onchange="wppaGalleryTypeChange(this.value)">'.
						'<option value="cover">'.__('The cover of an album', 'wppa').'</option>'.
						'<option value="album">'.__('The sub-albums and/or thumbnails in an album', 'wppa').'</option>'.
						'<option value="slide">'.__('A slideshow of the photos in an album', 'wppa').'</option>'.
						'<option value="slideonly">'.__('A slideshow without supporting boxes', 'wppa').'</option>'.
						'<option value="slideonlyf">'.__('A slideshow with a filmstrip only', 'wppa').'</option>'.
						'<option value="photo">'.__('A single photo', 'wppa').'</option>'.
						'<option value="mphoto">'.__('A single photo with caption', 'wppa').'</option>'.
						'<option value="slphoto">'.__('A single photo in the style of a slideshow', 'wppa').'</option>'.
						'<option value="generic">'.__('A generic albums display', 'wppa').'</option>'.
					'</select>'.
					'<br />'.
					'<small>'.__('Specify the type of gallery', 'wppa').'</small>'.
				'</td>'.
			'</tr>'.
			
			'<tr class="mygallery-help" style="display:none;" >'.
				'<th><label for="mygallery-album" class="mygallery-help" >'.__('Explanation:', 'wppa').'</label></th>'.
				'<td>'.
					__('Use this gallerytype to display all the top-level album covers.', 'wppa').'<br/ >'.
					__('Also, make a page with this type of gallery display to be used as a target for the Search results and for links set in Table VI.', 'wppa').
				'</td>'.
			'</tr>'.
		
			'<tr class="mygallery-album" >'.
				'<th><label for="mygallery-album" class="mygallery-album" >'.__('The Album to be used:', 'wppa').'</label></th>'.
				'<td>'.
					'<select id="mygallery-album" name="album" style=width:270px;" class="mygallery-album" onchange="wppaTinyMceAlbumPreview(this.value)">';
						$albums = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name` FROM `".WPPA_ALBUMS."` ORDER BY `timestamp` DESC"), 'ARRAY_A');
						if ($albums) {
							$result .= 
							// Please select
							'<option value="0" disabled="disabled" selected="selected" >'.__('Please select an album', 'wppa').'</option>';
							// All standard albums
							foreach ( $albums as $album ) {
								$value = $album['id'];
								$alb = $album['id'];
								$photos = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name`, `album`, `ext` FROM `".WPPA_PHOTOS."` WHERE `album` = %s ".wppa_get_photo_order($alb)." LIMIT 100", $alb), 'ARRAY_A');
								if ( $photos ) foreach ( $photos as $photo ) {
									$value .= '|'.$photo['id'].'.'.$photo['ext'];
								}
								else $value .= '|';
								$note = ' ('.$album['id'].')';
								if ( count($photos) <= $wppa_opt['wppa_min_thumbs'] ) $note .= ' *';
								$result .= '<option value="'.$value.'" >'.stripslashes(__($album['name'])).$note.'</option>';
							}
							// #last
								$value = '#last';
								$alb = $albums[0]['id'];
								$photos = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name`, `album`, `ext` FROM `".WPPA_PHOTOS."` WHERE `album` = %s ".wppa_get_photo_order($alb)." LIMIT 100", $alb), 'ARRAY_A');
								if ( $photos ) foreach ( $photos as $photo ) {
									$value .= '|'.$photo['id'].'.'.$photo['ext'];
								}
								else $value .= '|';
								$result .= '<option value="'.$value.'" >'.__('- The latest created album -', 'wppa').'</option>';
							// #topten
								$value = '#topten';
								$photos = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name`, `album`, `ext` FROM `".WPPA_PHOTOS."` ORDER BY `mean_rating` DESC LIMIT ".$wppa_opt['wppa_topten_count']), 'ARRAY_A');
								if ( $photos ) foreach ( $photos as $photo ) {
									$value .= '|'.$photo['id'].'.'.$photo['ext'];
								}
								else $value .= '|';
								$result .= '<option value = "'.$value.'" >'.__('--- The top rated photos ---', 'wppa').'</option>';
							// #lasten
								$value = '#lasten';
								$photos = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name`, `album`, `ext` FROM `".WPPA_PHOTOS."` ORDER BY `timestamp` DESC LIMIT ".$wppa_opt['wppa_lasten_count']), 'ARRAY_A');
								if ( $photos ) foreach ( $photos as $photo ) {
									$value .= '|'.$photo['id'].'.'.$photo['ext'];
								}
								else $value .= '|';
								$result .= '<option value = "'.$value.'" >'.__('--- The most recently uploaded photos ---', 'wppa').'</option>';							
							// #all
								$value = '#all';
								$photos = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name`, `album`, `ext` FROM `".WPPA_PHOTOS."` ".wppa_get_photo_order('0')." LIMIT 100"), 'ARRAY_A');
								if ( $photos ) foreach ( $photos as $photo ) {
									$value .= '|'.$photo['id'].'.'.$photo['ext'];
								}
								else $value .= '|';
								$result .= '<option value = "'.$value.'" >'.__('--- All photos in the system ---', 'wppa').'</option>';
						}
						else {
							$result .= '<option value="0" >'.__('There are no albums yet', 'wppa').'</option>';
						}
					$result .=
					'</select>'.
					'<input type="text" id="mygallery-alb" name="alb" value="" style="width:50px; display:none; background-color:#ddd;" class="mygallery-extra" title="Enter albumnumber if not systemwide" />'.
					'<input type="text" id="mygallery-cnt" name="cnt" value="" style="width:50px; display:none; background-color:#ddd;" class="mygallery-extra" title="Enter count if not default" />'.
					'<br />'.
					'<small class="mygallery-album" >'.
						__('Specify the album to be used or --- A special selection of photos ---', 'wppa').'<br />'.
						__('* Album contains less than the minimun number of photos', 'wppa').
					'</small>'.
				'</td>'.
			'</tr>'.
			
			'<tr class="mygallery-photo" style="display:none;" >'.
				'<th><label for="mygallery-photo" style="display:none;" class="mygallery-photo" >'.__('The Photo to be used:', 'wppa').'</label></th>'.
				'<td>'.
					'<select id="mygallery-photo" name="photo" style="display:none;" class="mygallery-photo" onchange="wppaTinyMcePhotoPreview(this.value)" >';
						$photos = $wpdb->get_results($wpdb->prepare("SELECT `id`, `name`, `album`, `ext` FROM `".WPPA_PHOTOS."` ORDER BY `timestamp` DESC LIMIT 100"), 'ARRAY_A');
						if ($photos) {
							$result .= '<option value="0" disabled="disabled" selected="selected" >'.__('Please select a photo', 'wppa').'</option>';
							foreach ( $photos as $photo ) {
								$result .= '<option value="'.$photo['id'].'.'.$photo['ext'].'" >'.stripslashes(__($photo['name'])).' ('.__(wppa_get_album_name($photo['album'])).')'.'</option>';
							}
							$result .=  '<option value="#last" >'.__('--- The most recently uploaded photo ---', 'wppa').'</option>'.
										'<option value="#potd" >'.__('--- The photo of the day ---', 'wppa').'</option>';
						}
						else {
							$result .= '<option value="0" >'.__('There are no photos yet', 'wppa').'</option>';
						}
					$result .=
					'</select>'.
					'<br />'.
					'<small style="display:none;" class="mygallery-photo" >'.
						__('Specify the photo to be used', 'wppa').'<br />'.
						__('You can select from a maximum of 100 most recently added photos', 'wppa').'<br />'.
					'</small>'.
				'</td>'.
			'</tr>'.

			'<tr>'.
				'<th><label for="mygallery-size">'.__('The size of the display:', 'wppa').'</label></th>'.
				'<td>'.
					'<input type="text" id="mygallery-size" value="" />'.
					'<br />'.
					'<small>'.
						__('Specify the horizontal size in pixels or <span style="color:blue" >auto</span>.', 'wppa').' '.
						__('A value less than <span style="color:blue" >100</span> will automaticly be interpreted as a <span style="color:blue" >percentage</span> of the available space.', 'wppa').'<br />'.
						__('Leave this blank for default size', 'wppa').'</small>'.
				'</td>'.
			'</tr>'.
			
			'<tr>'.
				'<th><label for="mygallery-align">'.__('Horizontal alignment:', 'wppa').'</label></th>'.
				'<td>'.
					'<select id="mygallery-align" name="align" >'.
						'<option value="none" >'.__('--- none ---', 'wppa').'</option>'.
						'<option value="left" >'.__('left', 'wppa').'</option>'.
						'<option value="center" >'.__('center', 'wppa').'</option>'.
						'<option value="right" >'.__('right', 'wppa').'</option>'.
					'</select>'.
					'<br />'.
					'<small>'.__('Specify the alignment to be used or --- none ---', 'wppa').'</small>'.
				'</td>'.
			'</tr>'.

		'</table>'.
		'<p class="submit">'.
			'<input type="button" id="mygallery-submit" class="button-primary" value="'.__('Insert Gallery', 'wppa').'" name="submit" />&nbsp;'.
			'<input type="checkbox" id="mygallery-newstyle" />'.'&nbsp;'.__('Create new style shortcode', 'wppa').
		'</p>'.
	'</div>';
	return $result;
}
?>