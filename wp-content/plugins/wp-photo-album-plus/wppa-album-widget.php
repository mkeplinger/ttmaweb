<?php
/* wppa-album-widget.php
* Package: wp-photo-album-plus
*
* display thumbnail photos
* Version 4.8.0
*/

class AlbumWidget extends WP_Widget {
    /** constructor */
    function AlbumWidget() {
        parent::WP_Widget(false, $name = 'Thumbnail Albums');	
		$widget_ops = array('classname' => 'wppa_album_widget', 'description' => __( 'WPPA+ Albums', 'wppa') );
		$this->WP_Widget('wppa_album_widget', __('Thumbnail Albums', 'wppa'), $widget_ops);
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
	//	global $widget_content;
		global $wpdb;
		global $wppa_opt;
		global $wppa;

        extract( $args );
		
		$instance = wp_parse_args( (array) $instance, array( 
													'title' => '',		// Widget title
													'parent' => 'none',	// Parent album
													'name' => 'no',		// Display album name?
													'skip' => 'yes'		// Skip empty albums
							//						'count' => $wppa_opt['wppa_album_widget_count'],	// to be added
							//						'size' => $wppa_opt['wppa_album_widget_size']
													) );
 
		$widget_title = apply_filters('widget_title', $instance['title']);

		$page = $wppa_opt['wppa_album_widget_linkpage'];
		$max  = $wppa_opt['wppa_album_widget_count'];
		if ( !$max ) $max = '10';
		
		$parent = $instance['parent'];
		$name = $instance['name'];
		$skip = $instance['skip'];
		
		if ( is_numeric($parent) ) {
			$albums = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM `'.WPPA_ALBUMS.'` WHERE `a_parent` = %s '.wppa_get_album_order(), $parent ), 'ARRAY_A' );
		}
		else {
			switch ($parent) {
				case 'all':
					$albums = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM `'.WPPA_ALBUMS.'` '.wppa_get_album_order() ), 'ARRAY_A' );
					break;
				case 'last':
					$albums = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM `'.WPPA_ALBUMS.'` ORDER BY `timestamp` DESC' ), 'ARRAY_A' );
					break;
				default:
					wppa_dbg_msg('Error, unimplemented album selection: '.$parent.' in Album widget.', 'red', true);
				}
		}
		
		$widget_content = "\n".'<!-- WPPA+ album Widget start -->';
		$maxw = $wppa_opt['wppa_album_widget_size'];
		$maxh = $maxw;
		if ( $name == 'yes' ) $maxh += 18;
		
		$count = 0;
		if ( $albums ) foreach ( $albums as $album ) {
			if ( $count < $max ) {
				global $thumb;
				
				$imageid = wppa_get_coverphoto_id($album['id']);
				$image = $wpdb->get_row($wpdb->prepare('SELECT * FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s', $imageid), 'ARRAY_A');
				$imgcount = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM '.WPPA_PHOTOS.' WHERE `album` = %s', $album['id']));
				$thumb = $image;
				// Make the HTML for current picture
				if ( $image && $imgcount > $wppa_opt['wppa_min_thumbs'] ) {
					$link       = wppa_get_imglnk_a('albwidget', $image['id']);
					$file       = wppa_get_thumb_path_by_id($image['id']);
					$imgevents  = wppa_get_imgevents('thumb', $image['id'], true);
					$imgstyle_a = wppa_get_imgstyle_a($file, $maxw, 'center', 'albthumb');
					$imgstyle   = $imgstyle_a['style'];
					$width      = $imgstyle_a['width'];
					$height     = $imgstyle_a['height'];
					$cursor		= $imgstyle_a['cursor'];
					$title 		= esc_attr(strip_tags(__(stripslashes($album['description']))));
					$usethumb	= wppa_use_thumb_file($image['id'], $width, $height) ? '/thumbs' : '';
					$imgurl 	= WPPA_UPLOAD_URL . $usethumb . '/' . $image['id'] . '.' . $image['ext'];
				}
				else {
					$link       = '';
					$file 		= '';
					$imgevents  = '';
					$imgstyle   = 'width:'.$maxw.';height:'.$maxh.';';
					$width      = $maxw;
					$height     = $maxw; // !!
					$cursor		= 'default';
					$title 		= sprintf(__a('Upload at least %d photos to this album!', 'wppa_theme'), $wppa_opt['wppa_min_thumbs'] - $imgcount + 1);
					$imgurl		= wppa_get_imgdir().'album32.png';
				}
					

				if ( $imgcount > $wppa_opt['wppa_min_thumbs'] || $skip == 'no' ) {
				
					$widget_content .= "\n".'<div class="wppa-widget" style="width:'.$maxw.'px; height:'.$maxh.'px; margin:4px; display:inline; text-align:center; float:left;">'; 
				
					if ($link) {
						if ( $link['is_url'] ) {	// Is a href
							$widget_content .= "\n\t".'<a href="'.$link['url'].'" title="'.$title.'" target="'.$link['target'].'" >';
								$widget_content .= "\n\t\t".'<img id="i-'.$image['id'].'-'.$wppa['master_occur'].'" title="'.$title.'" src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" style="'.$imgstyle.' cursor:pointer;" '.$imgevents.' alt="'.esc_attr(wppa_qtrans($image['name'])).'">';
							$widget_content .= "\n\t".'</a>';
						}
						elseif ( $link['is_lightbox'] ) {
							$title = wppa_get_lbtitle('thumb', $image);
							$widget_content .= "\n\t".'<a href="'.$link['url'].'" rel="'.$wppa_opt['wppa_lightbox_name'].'[thumbnail-'.$album.']" title="'.$title.'" target="'.$link['target'].'" >';
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
				
					if ($name == 'yes') $widget_content .= "\n\t".'<span style="font-size:9px;">'.__(stripslashes($album['name'])).'</span>';

					$widget_content .= "\n".'</div>';
					$count++;
				}
			}
		}			
		else $widget_content .= 'There are no albums (yet).';
		
		$widget_content .= '<div style="clear:both"></div>';
		
		$widget_content .= "\n".'<!-- WPPA+ thumbnail Widget end -->';

		echo "\n" . $before_widget;
		if ( !empty( $widget_title ) ) { echo $before_title . $widget_title . $after_title; }
		echo $widget_content . $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['parent'] = $new_instance['parent'];
		$instance['name'] = $new_instance['name'];
		$instance['skip'] = $new_instance['skip'];
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		global $wpdb;
		global $wppa_opt;
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 
															'title' => __('Thumbnail Albums', 'wppa'),
															'parent' => '0',
															'name' => 'no',
															'skip' => 'yes' ) );
 		$album = $instance['parent'];
		$name = $instance['name'];
		$skip = $instance['skip'];
		$widget_title = $instance['title'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppa'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $widget_title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('parent'); ?>"><?php _e('Album selection or Parent album:', 'wppa'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('parent'); ?>" name="<?php echo $this->get_field_name('parent'); ?>" >

				<?php //echo wppa_album_select('', $album, true, '', '', true); ?>
				<option value="all" <?php if ($album == 'all') echo 'selected="selected"' ?>><?php _e('--- all albums ---', 'wppa') ?></option>
				<option value="0"  <?php if ($album == '0')  echo 'selected="selected"' ?>><?php _e('--- all generic albums ---', 'wppa') ?></option>
				<option value="-1" <?php if ($album == '-1') echo 'selected="selected"' ?>><?php _e('--- all separate albums ---', 'wppa') ?></option>
				<option value="last" <?php if ($album == 'last') echo 'selected="selected"' ?>><?php _e('--- most recently added albums ---', 'wppa') ?></option>
				<?php $albs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".WPPA_ALBUMS."` ORDER BY `name`"), 'ARRAY_A');
				if ( $albs ) foreach( $albs as $alb ) {
					echo '<option value="'.$alb['id'].'" '; 
					if ( $album == $alb['id'] ) echo 'selected="selected" '; 
					if ( !wppa_has_children($alb['id']) ) echo 'disabled="disabled" '; 
					echo '>'.__(stripslashes($alb['name'])).'</option>';
				} ?>

			</select>
		</p>
		<p>
			<?php _e('Show album names:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>">
				<option value="no" <?php if ($name == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($name == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>
		<p>
			<?php _e('Skip "empty" albums:', 'wppa'); ?>
			<select id="<?php echo $this->get_field_id('skip'); ?>" name="<?php echo $this->get_field_name('skip'); ?>">
				<option value="no" <?php if ($name == 'no') echo 'selected="selected"' ?>><?php _e('no.', 'wppa'); ?></option>
				<option value="yes" <?php if ($name == 'yes') echo 'selected="selected"' ?>><?php _e('yes.', 'wppa'); ?></option>
			</select>
		</p>

		<p><?php _e('You can set the sizes in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wppa'); ?></p>
<?php
    }

} // class thumbnailWidget

// register thumbnailWidget widget
add_action('widgets_init', create_function('', 'return register_widget("AlbumWidget");'));
