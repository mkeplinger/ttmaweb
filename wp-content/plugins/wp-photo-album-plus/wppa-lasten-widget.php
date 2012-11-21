<?php
/* wppa-lasten-widget.php
* Package: wp-photo-album-plus
*
* display the last uploaded photos
* Version 4.8.0
*/

class LasTenWidget extends WP_Widget {
    /** constructor */
    function LasTenWidget() {
        parent::WP_Widget(false, $name = 'Last Ten Photos');	
		$widget_ops = array('classname' => 'wppa_lasten_widget', 'description' => __( 'WPPA+ Last Ten Uploaded Photos', 'wppa') );
		$this->WP_Widget('wppa_lasten_widget', __('Last Ten Photos', 'wppa'), $widget_ops);
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $wpdb;
		global $wppa_opt;
		global $wppa;

        extract( $args );
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'album' => '', 'albumenum' => '', 'timesince' => 'yes' ) );

 		$widget_title = apply_filters('widget_title', $instance['title'] );
		$page = $wppa_opt['wppa_lasten_widget_linkpage'];
		$max  = $wppa_opt['wppa_lasten_count'];
		$timesince = $instance['timesince'];
		
		$album = $instance['album'];
		$albumenum = $instance['albumenum'];
		if ($album == '-99') $album = implode(' OR `album` = ', explode(',', $albumenum));
		if ($album) {
			$thumbs = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM '.WPPA_PHOTOS.' WHERE album = '.$album.' ORDER BY timestamp DESC LIMIT '.$max ), 'ARRAY_A' );
		}
		else {
			$thumbs = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM '.WPPA_PHOTOS.' ORDER BY timestamp DESC LIMIT '.$max ), 'ARRAY_A' );
		}
		$widget_content = "\n".'<!-- WPPA+ LasTen Widget start -->';
		$maxw = $wppa_opt['wppa_lasten_size'];
		$maxh = $maxw + 18;
		if ($thumbs) foreach ($thumbs as $image) {
			
			global $thumb;
			$thumb = $image;
			// Make the HTML for current picture
			$widget_content .= "\n".'<div class="wppa-widget" style="width:'.$maxw.'px; height:'.$maxh.'px; margin:4px; display:inline; text-align:center; float:left;">'; 
			if ($image) {
				$no_album = !$album;
				if ($no_album) $tit = __a('View the most recent uploaded photos', 'wppa_theme'); else $tit = esc_attr(wppa_qtrans(stripslashes($image['description'])));
				$link       = wppa_get_imglnk_a('lasten', $image['id'], '', $tit, '', $no_album, $albumenum);
				$file       = wppa_get_thumb_path_by_id($image['id']);
				$imgstyle_a = wppa_get_imgstyle_a($file, $maxw, 'center', 'ltthumb');
				$imgstyle   = $imgstyle_a['style'];
				$width      = $imgstyle_a['width'];
				$height     = $imgstyle_a['height'];
				$cursor		= $imgstyle_a['cursor'];
				$usethumb	= wppa_use_thumb_file($image['id'], $width, $height) ? '/thumbs' : '';
				$imgurl 	= WPPA_UPLOAD_URL . $usethumb . '/' . $image['id'] . '.' . $image['ext'];

				$imgevents = wppa_get_imgevents('thumb', $image['id'], true);

				if ($link) $title = esc_attr(stripslashes($link['title']));
				else $title = '';
				
				if ($link) {
					if ( $link['is_url'] ) {	// Is a href
						$widget_content .= "\n\t".'<a href="'.$link['url'].'" title="'.$title.'" target="'.$link['target'].'" >';
							$widget_content .= "\n\t\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.' cursor:pointer;" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
						$widget_content .= "\n\t".'</a>';
					}
					elseif ( $link['is_lightbox'] ) {
						$title = wppa_get_lbtitle('thumb', $image);
						$widget_content .= "\n\t".'<a href="'.$link['url'].'" rel="'.$wppa_opt['wppa_lightbox_name'].'[lasten-'.$album.']" title="'.$title.'" target="'.$link['target'].'" >';
							$widget_content .= "\n\t\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.wppa_zoom_in().'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.$cursor.'" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
						$widget_content .= "\n\t".'</a>';
					}
					else { // Is an onclick unit
						$widget_content .= "\n\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.' cursor:pointer;" '.$imgevents.' onclick="'.$link['url'].'" alt="'.esc_attr(wppa_qtrans($image['name'])).'">';					
					}
				}
				else {
					$widget_content .= "\n\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.'" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
				}
			}
			else {	// No image
				$widget_content .= __a('Photo not found.', 'wppa_theme');
			}
			if ($timesince == 'yes') {
				$widget_content .= "\n\t".'<span style="font-size:9px;">'.wppa_get_time_since($image['timestamp']);
				$widget_content .= '</span>';
			}
			$widget_content .= "\n".'</div>';
		}	
		else $widget_content .= 'There are no uploaded photos (yet).';
		
		$widget_content .= "\n".'<!-- WPPA+ LasTen Widget end -->';

		echo "\n" . $before_widget;
		if ( !empty( $widget_title ) ) { echo $before_title . $widget_title . $after_title; }
		echo $widget_content . $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['album'] = $new_instance['album'];
		$instance['albumenum'] = $new_instance['albumenum'];
		if ( $instance['album'] != '-99' ) $instance['albumenum'] = '';
		$instance['timesince'] = $new_instance['timesince'];
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	
		global $wppa_opt;
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Last Ten Photos', 'wppa'), 'album' => '0', 'albumenum' => '', 'timesince' => 'yes' ) );
 		$widget_title = apply_filters('widget_title', $instance['title']);

		$album = $instance['album'];
		$timesince = $instance['timesince'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $widget_title; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('album'); ?>"><?php _e('Album:', 'wppa'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('album'); ?>" name="<?php echo $this->get_field_name('album'); ?>" >

				<?php echo wppa_album_select('', $album, true, '', '', true, '', '', true, true); ?>

			</select>
		</p>
		
		<?php $album_enum = $instance['albumenum'] ?>
		<p id="wppa-albums-enum" style="display:block;" ><label for="<?php echo $this->get_field_id('albumenum'); ?>"><?php _e('Albums:', 'wppa'); ?></label>
		<small style="color:blue;" ><br /><?php _e('Select --- multiple see below --- in the Album selection box. Then enter album numbers seperated by commas', 'wppa') ?></small>
			<input class="widefat" id="<?php echo $this->get_field_id('albumenum'); ?>" name="<?php echo $this->get_field_name('albumenum'); ?>" type="text" value="<?php echo $album_enum ?>" />
		</p>
		
		<p>
			<?php _e('Show time since:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('timesince'); ?>" name="<?php echo $this->get_field_name('timesince'); ?>">
				<option value="no" <?php if ($timesince == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($timesince == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>


		<p><?php _e('You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wppa'); ?></p>
<?php
    }

} // class LasTenWidget

// register LasTenWidget widget
add_action('widgets_init', create_function('', 'return register_widget("LasTenWidget");'));
