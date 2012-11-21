<?php
/* wppa-qr-widget.php
* Package: wp-photo-album-plus
*
* display thumbnail photos
* Version 4.8.2
*/


class wppaQRWidget extends WP_Widget {
    /** constructor */
    function wppaQRWidget() {
        parent::WP_Widget(false, $name = 'QR Widget');	
		$widget_ops = array('classname' => 'qr_widget', 'description' => __( 'WPPA+ QR Widget', 'xxx') );	//
		$this->WP_Widget('qr_widget', __('QR Widget', 'wppa'), $widget_ops);															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $wpdb;
		global $widget_content;
		global $wppa_opt;

        extract( $args );
        
 		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'QR Widget', 'xxx' ) : $instance['title']);

		$widget_content = '
		<div style="text-align:center;" ><img id="wppa-qr-img" src="" /></div>
		<div style="clear:both" ></div>';
		
		$widget_content .= '
		<script type="text/javascript">
			/*[CDATA[*/
			var wppaQRData = document.location.href;
			var wppaQRDataOld = "";
			var wppaQRSrc = "";
			var workData = "";
			
			wppaConsoleLog("doc.loc.href = "+wppaQRData);
			
			function wppaQRUpdate(arg) {
				if ( arg ) wppaQRData = arg;
				if ( wppaQRData != wppaQRDataOld ) {
					wppaQRDataOld = wppaQRData;
					workData = wppaQRData;
					wppaQRSrc = "http://api.qrserver.com/v1/create-qr-code/?data="+encodeURIComponent(workData)+"&size='.$wppa_opt['wppa_qr_size'].'x'.$wppa_opt['wppa_qr_size'].'&color='.trim($wppa_opt['wppa_qr_color'], '#').'&bgcolor='.trim($wppa_opt['wppa_qr_bgcolor'], '#').'";
					document.getElementById("wppa-qr-img").src = wppaQRSrc;
					document.getElementById("wppa-qr-img").title = workData;
				}
				return;			
			}

			jQuery(document).ready(function(){
				wppaQRUpdate();
			});
			/*]]*/
		</script>';
		
		
		echo $before_widget . $before_title . $title . $after_title . $widget_content . $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'sortby' => 'post_title', 'title' => '') );
		$title = esc_attr( $instance['title'] );
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'xxx'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><?php _e('You can set the sizes and colors in this widget in the <b>Photo Albums -> Settings</b> admin page.', 'wppa'); ?></p>

<?php
    }

} // class wppaQRWidget

// register wppaQRWidget widget
add_action('widgets_init', create_function('', 'return register_widget("wppaQRWidget");'));
?>
