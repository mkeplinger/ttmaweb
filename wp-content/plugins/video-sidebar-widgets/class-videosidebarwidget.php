<?php
/*
* Widget - Video Sidebar Widget
*/ 

//Video Sidebar Widget Class to extend WP_Widget class
class VideoSidebarWidget extends WP_Widget {

		//function to set up widget in admin
		function VideoSidebarWidget() {
		
				$widget_ops = array( 'classname' => 'videosidebar', 
				'description' => __('A Video Widget to display video in sidebar from various video sharing networks', 'videosidebar') );
				
				$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'videosidebar' );
				$this->WP_Widget( 'videosidebar', __('Video Sidebar Widget', 'videosidebar'), $widget_ops, $control_ops );
		
		}


		//function to echo out widget on sidebar
		function widget( $args, $instance ) {
		extract( $args );
		
				$title2 = apply_filters('widget_title2', $instance['title2'] );
		        $cap2 = $instance['v_cap2'];
				
				echo $before_widget;
		
				// if user written title echo out
				if ( $title2 ){
				echo $before_title . $title2 . $after_title;
				}
			
				//get settings from Widget Admin Form to assign to function VSWShowVideo
				$autoplaysetting = $instance['v_autoplay2'];
				$videoid = $instance['v_id2'];
				$videosource = $instance['v_source']; 
				$videowidth = $instance['v_width2'];
				$videoheight = $instance['v_height2'];
				
				//function to show video in blog sidebar, please look for it below
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'false','false');
				
				if($cap2){
				echo "<p class=\"VideoCaption\">$cap2</p>";
				}	
		
				echo $after_widget;
		
		}//end of function widget



		//function to update widget setting
		function update( $new_instance, $old_instance ) {
		
				$instance = $old_instance;
				$instance['title2'] = strip_tags( $new_instance['title2'] );
				$instance['v_width2'] = strip_tags( $new_instance['v_width2'] );
				$instance['v_height2'] = strip_tags( $new_instance['v_height2'] );
				$instance['v_autoplay2'] = strip_tags( $new_instance['v_autoplay2'] );
				$instance['v_id2'] = strip_tags( $new_instance['v_id2'] );
				$instance['v_source'] = strip_tags( $new_instance['v_source'] );
				$instance['v_cap2'] = $new_instance['v_cap2'];
				return $instance;
		
		}//end of function update


		//function to create Widget Admin form
		function form($instance) {
		
				$instance = wp_parse_args( (array) $instance, array( 'title2' => '', 'v_width2' => '', 'v_height2' => '', 
				'v_autoplay2' => '','v_id2' => '','v_source' => '','v_cap2' => '') );
				
				$instance['title2'] = strip_tags( $instance['title2'] );
				$instance['v_width2'] = strip_tags( $instance['v_width2'] );
				$instance['v_height2'] = strip_tags( $instance['v_height2'] );
				$instance['v_autoplay2'] = strip_tags( $instance['v_autoplay2'] );
				$instance['v_id2'] = strip_tags( $instance['v_id2'] );
				$instance['v_source'] = strip_tags( $instance['v_source'] );
				$instance['v_cap2'] = $instance['v_cap2'];	
				
				//function to show video in widget admin form fixed width and height, please look for it below
				$autoplaysetting = '0';
				$videoid = $instance['v_id2'];
				$videosource = $instance['v_source']; 
				$videowidth = "";
				$videoheight = "";
				//$admin = true // to show video in admin
				
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'true','false');
						
				?>
				
						
				<p>
				<label for="<?php echo $this->get_field_id('title2'); ?>">Widget Title:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title2'); ?>" name="<?php echo $this->get_field_name('title2'); ?>"
				 type="text" value="<?php echo $instance['title2']; ?>" />
				</p>
				
				<p>
				<label for="<?php echo $this->get_field_id( 'v_source' ); ?>">Select Video Source:</label> 
				<select id="<?php echo $this->get_field_id( 'v_source' );?>" name="<?php echo $this->get_field_name( 'v_source' );?>" class="widefat" style="width:100%;">
				
				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
				
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($instance['v_source'] == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>
				

				</select>
				</p>
				
				<p>
				<label for="<?php echo $this->get_field_id('v_id2'); ?>">Video ID: </label>
				<input class="widefat" id="<?php echo $this->get_field_id('v_id2'); ?>" 
                name="<?php echo $this->get_field_name('v_id2'); ?>" type="text" value="<?php echo $instance['v_id2']; ?>" /></p>
				
				<p>
				<label for="<?php echo $this->get_field_id('v_width2'); ?>">Video Width: </label>
				<input class="widefat" id="<?php echo $this->get_field_id('v_width2'); ?>" 
                name="<?php echo $this->get_field_name('v_width2'); ?>" type="text" value="<?php echo $instance['v_width2']; ?>" />
				</p>
				
				<p>
				<label for="<?php echo $this->get_field_id('v_height2'); ?>">Video Height: </label>
				<input class="widefat" id="<?php echo $this->get_field_id('v_height2'); ?>" 
                name="<?php echo $this->get_field_name('v_height2'); ?>" type="text" value="<?php echo $instance['v_height2']; ?>" />
				</p>
				
                <p>
				<label for="<?php echo $this->get_field_id('v_cap2'); ?>">Video Caption: </label>
				<input class="widefat" id="<?php echo $this->get_field_id('v_cap2'); ?>" 
                name="<?php echo $this->get_field_name('v_cap2'); ?>" type="text" value="<?php echo $instance['v_cap2']; ?>" />
				</p>
                
                
				<p>
				
				<?php
				
				// check whether autoplay feature supported by video network
				$source = $instance['v_source']; 
				$msg = "<p>Sorry, auto play option not supported by ".$source."</p>";
				switch ($source) {
						
						case 'WordPress':
						echo $msg;
						break;
						
						case 'Tudou':
						echo $msg;
						break;
						
						case 'Youku':
						echo $msg;
						break;
						
						case 'Blip':
						echo "<p>Sorry, auto play option removed as it is causing error in Internet Explorer</p>";
						break;
						
						case 'cn6':
						echo "<p>Sorry, auto play option not supported by 6.cn</p>";
						break;
					}	
				
				?>
				
				<label for="<?php echo $this->get_field_id( 'v_autoplay2' ); ?>">Auto Play:</label> 
				<select id="<?php echo $this->get_field_id( 'v_autoplay2' );?>" 
                name="<?php echo $this->get_field_name( 'v_autoplay2' );?>" class="widefat" style="width:100%;">';
				<option value='1' <?php  if($instance['v_autoplay2'] == '1'){echo 'selected="selected"';}?>>Yes</option>
				<option value='0' <?php  if($instance['v_autoplay2'] == '0'){echo 'selected="selected"';}?>>No</option>
				</select>
				</p>
				
				<?php
		
	      }//end of function form($instance)

}//end of Video Sidebar Widget Class
?>