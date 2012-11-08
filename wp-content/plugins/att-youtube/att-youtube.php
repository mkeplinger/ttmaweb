<?php
/*
Plugin Name: ATT YouTube Widget
Description: Using this widget you can easily place a YouTube video in the sidebar.
Version: 1.0
Author: ATinyTeam
Author URI: http://atinyteam.com
*/
?>
<?php
/*	Copyright 2012	ATinyTeam	(email : atinyteam@gmail.com)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA	 02110-1301	 USA
*/
?>
<?php

// Inspired by the built-in WP_Widget_Text class
class ATT_YouTube extends WP_Widget {

  // Set up the widget classname and description
	function ATT_YouTube() {
		$widget_ops = array('classname' => 'att_youtube', 'description' => __('Display a YouTube video'));
		$control_ops = array();
		$this->WP_Widget('att_youtube', __('ATT YouTube'), $widget_ops, $control_ops);
	}

  // Display the widget on the web site
	function widget( $args, $instance ) {
		extract($args);

		// Optionally generate the link code
		
		echo $before_widget; ?>
		<div class="attyoutube">
			<?php 
			$title = $instance['title'];
			$link = $instance['link'];
			$link = explode("/", $link);
			$size = $instance['size'];
			$attwidth = $instance['width'];
			$attheight = $instance['height'];
			$option1 = $instance['option1'];
			$option2 = $instance['option2'];
			$option3 = $instance['option3'];
			$option4 = $instance['option4'];
			
			if($size!='custom'){
				$size = explode('x', $size);
				$attwidth = $size[0]; 
				$attheight = $size[1];
			}
			if($option1==1) {$option1='';} else {$option1='?rel=0';}
			if($option2==2) {$option2='s';} else {$option2='';}
			if($option3==3) {$option3='www.youtube-nocookie.com';} else {$option3='www.youtube.com';}
			echo $before_title.$title.$after_title;
			?>
			<?php
				//video description options
				$attyoutubedest = get_option('att_youtube_title');
				$attyoutubedesc = get_option('att_youtube_content');
			?>
			<div class="att_youtube_des">
				<div class="att_youtube_des_title">
					<?php echo $before_title.$attyoutubedest.$after_title;?>
				</div>
				<div class="att_youtube_des_content">
					<?php echo $attyoutubedesc;?>
				</div>
			</div>	
			<?php
			if($option4!=4) {
			?>
			<iframe
				width="<?php echo $attwidth; ?>"
				height="<?php echo $attheight; ?>" 
				<?php echo 'src="http'.$option2.'://'.$option3.'/embed/'.$link[3].$option1.'"'; ?>
				frameborder="0" 
				allowfullscreen>
			</iframe>
			<?php	
			} else {
				?>
				<object 
					width="<?php echo $attwidth; ?>"
					height="<?php echo $attheight; ?>"
				>
					<param 
						name="movie" 
						value="http<?php echo $option2; ?>://<?php echo $option3; ?>/v/<?php echo $link[3]; ?>?version=3&amp;hl=en_US&amp;<?php echo $option1; ?>">
					</param>
					<param 
						name="allowFullScreen" 
						value="true">
					</param>
					<param 
						name="allowscriptaccess" 
						value="always">
					</param>
					<embed 
						src="http<?php echo $option2; ?>://<?php echo $option3; ?>/v/<?php echo $link[3]; ?>?version=3&amp;hl=en_US&amp;<?php echo $option1; ?>" 
						type="application/x-shockwave-flash" 
						width="<?php echo $attwidth; ?>"
						height="<?php echo $attheight; ?>"
						allowscriptaccess="always" 
						allowfullscreen="true">
					</embed>
				</object>
				<?php
			};
			
			?>
			
		</div>
		<?php echo $after_widget;
	}

  // Save the settings for this instance
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['size'] = strip_tags($new_instance['size']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['option1'] = strip_tags($new_instance['option1']);
		$instance['option2'] = strip_tags($new_instance['option2']);
		$instance['option3'] = strip_tags($new_instance['option3']);
		$instance['option4'] = strip_tags($new_instance['option4']);
		$instance['option5'] = strip_tags($new_instance['option5']);
		
		return $instance;
	}

  // Display the widget form in the admin interface
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
														'title'=>'ATinyTeam YouTube Plugin', 
														'link' => 'http://youtu.be/9LpPZbscjJk', 
														'width'=>'320', 
														'height'=>'180',
														'size'=> 'custom',
														'option1'=> '',
														'option2'=> '',
														'option3'=> '',
														'option4'=> '',			
													) );
		
		$title = $instance['title'];
		$link = $instance['link'];
		$width = $instance['width'];
		$height = $instance['height'];
		$size = $instance['size'];
		$option1 = $instance['option1'];
		$option2 = $instance['option2'];
		$option3 = $instance['option3'];
		$option4 = $instance['option4'];

?>
 
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Title:'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>">
				<?php _e('YouTube video URL (required, press Share button under the YouTube Video Player and copy):'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('size'); ?>">
				<?php _e('Size:<br />'); ?>
				<input class="attradio" id="<?php echo $this->get_field_id('size1'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="radio" value="560x315" <?php if($size =='560x315'){ echo ' checked="checked" ';}?>/>
				<?php _e('560x315<br />');?>
				<input class="attradio" id="<?php echo $this->get_field_id('size2'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="radio" value="640x360" <?php if($size =='640x360'){ echo ' checked="checked" ';}?>/>
				<?php _e('640x360<br />');?>
				<input class="attradio" id="<?php echo $this->get_field_id('size3'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="radio" value="853x480" <?php if($size =='853x480'){ echo ' checked="checked" ';}?>/>
				<?php _e('853x480<br />');?>
				<input class="attradio" id="<?php echo $this->get_field_id('size4'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="radio" value="1280x720" <?php if($size =='1280x720'){ echo ' checked="checked" ';}?>/>
				<?php _e('1280x720<br />');?>
				<br />
				<input class="attradio" id="<?php echo $this->get_field_id('size5'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="radio" value="custom" <?php if($size =='custom'){ echo ' checked="checked" ';}?>/>
				<?php _e('Custom size:');?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>">
				<?php _e('Width:'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>">
				<?php _e('Height:'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('option'); ?>">
			
				<?php _e('Option:<br />'); ?>
				<input class="attcheckbox" id="<?php echo $this->get_field_id('option1'); ?>" name="<?php echo $this->get_field_name('option1'); ?>" type="checkbox" value="1" <?php if($option1 =='1'){ echo ' checked="checked" ';}?>/>
				<?php _e('Show suggested videos when the video finishes<br />');?>
				<input class="attcheckbox" id="<?php echo $this->get_field_id('option2'); ?>" name="<?php echo $this->get_field_name('option2'); ?>" type="checkbox" value="2" <?php if($option2 =='2'){ echo ' checked="checked" ';}?>/>
				<?php _e('Use HTTPS [<a target="_blank" href="http://www.google.com/support/youtube/bin/answer.py?answer=171780&expand=UseHTTPS#HTTPS">?</a>]<br />');?>
				<input class="attcheckbox" id="<?php echo $this->get_field_id('option3'); ?>" name="<?php echo $this->get_field_name('option3'); ?>" type="checkbox" value="3" <?php if($option3 =='3'){ echo ' checked="checked" ';}?>/>
				<?php _e('Enable privacy-enhanced mode [<a target="_blank" href="http://www.google.com/support/youtube/bin/answer.py?answer=171780&expand=PrivacyEnhancedMode#privacy">?</a>]<br />');?>
				<input class="attcheckbox" id="<?php echo $this->get_field_id('option4'); ?>" name="<?php echo $this->get_field_name('option4'); ?>" type="checkbox" value="4" <?php if($option4 =='4'){ echo ' checked="checked" ';}?>/>
				<?php _e('Use old embed code [<a target="_blank" href="http://www.google.com/support/youtube/bin/answer.py?answer=171780&expand=UseOldEmbedCode#oldcode">?</a>]<br />');?>
		
			</label>
		</p>
<?php
	}
}

// Init function for registering the widget
function widget_att_youtube_init() {
  register_widget('ATT_YouTube');
}
add_action('init', 'widget_att_youtube_init', 1);

//=====================Admin panel========================================================================
// create custom plugin settings menu

function att_youtube_menu(){
	add_menu_page( 'ATT YouTube', 'ATT YouTube', 8, 'att_youtube_options_menu', 'att_youtube_options_page', plugin_dir_url( __FILE__ ).'menu.png' );
	//add_management_page( 'Custom Permalinks', 'Custom Permalinks', 5, __FILE__, 'att_youtube_options_page' );
}
add_action('admin_menu', 'att_youtube_menu');

/*
//First use the add_action to add onto the WordPress menu.
add_action('admin_menu', 'att_add_options');

//Make our function to call the WordPress function to add to the correct menu.
function att_add_options() {
	add_theme_page('ATT YouTube Options', 'ATT YouTube Options', 8, 'att_youtube_options_menu', 'att_youtube_options_page');
}
*/
function att_youtube_options_page() {
    // variables for the field and option names
    $opt_name = array(	'title' =>'att_youtube_title',
						'content' => 'att_youtube_content'
				      );
    $submit = 'att_youtube_submit';
	// Read in existing option value from database
	$opt_val = array(	'title' =>  get_option( $opt_name['title']) ,
						'content' =>  get_option( $opt_name['content']) 
					);
	
	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Save'
	if(isset($_POST[ $submit ]) && $_POST[ $submit ] == 'Save' ) {
		// Read their posted value
		$opt_val = array(	'title' =>  stripslashes($_POST[ $opt_name['title'] ]),
							'content' =>  stripslashes($_POST[ $opt_name['content'] ])
						);
		// Save the posted value in the database
        update_option( $opt_name['title'],  $opt_val['title'] );
		update_option( $opt_name['content'],  $opt_val['content'] );

        // Put an options updated message on the screen

		?>
		<div id="message" class="updated fade">
			<p>
				<strong>
					<?php _e('Options saved.' ); ?>
				</strong>
			</p>
		</div>
<?php
	}
	?>
	<div class="wrap">
		<div  id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'The description of YouTube video: ' ); ?></h2>
		
		<form name="att_img_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<div id="titlediv">
				<div id="titlewrap">
					<input type="text" name="<?php echo $opt_name['title']?>" id="title" value="<?php echo $opt_val['title']; ?>" />
				</div>
			</div>
			
			<?php the_editor ( $opt_val['content'], $opt_name['content'], 'title', true,  2, true ); ?>
			<br /><br />
			<input class="button-primary" id="submitbutton" type="submit" name="<?php echo $submit; ?>" value="Save">
			
		</form>
	</div>
<?php	
}
?>
