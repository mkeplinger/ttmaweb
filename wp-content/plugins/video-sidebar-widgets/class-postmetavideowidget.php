<?php
/*
* Widget - Post Meta Video Widget
*/ 

//Post Meta Video Widget Class to extend WP_Widget class
class PostMetaVideoWidget extends WP_Widget {

		//function to set up widget in admin
		function PostMetaVideoWidget() {
		
				$widget_ops = array( 'classname' => 'postmetavideo', 
				'description' => __('A Video Widget that is controlled by Post or Page custom field settings.', 'postmetavideo') );
				
				$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'postmetavideo' );
				$this->WP_Widget( 'postmetavideo', __('Post Meta Video Widget', 'postmetavideo'), $widget_ops, $control_ops );
		
		}


		//function to echo out widget on sidebar
		function widget( $args, $instance ) {
		extract( $args );
		
		        if(is_single()||is_page()):
						
				//get post meta and output video				
				global $post;
				$post_id = $post->ID;
				$videosource = get_post_meta($post_id,'vsw_pmvw_video_source',true);
				$videoid = get_post_meta($post_id,'vsw_pmvw_video_id',true);
				$videowidth = get_post_meta($post_id,'vsw_pmvw_video_width',true);
				$videoheight = get_post_meta($post_id,'vsw_pmvw_video_height',true);
				$videocaption = get_post_meta($post_id,'vsw_pmvw_video_caption',true);
				$autoplaysetting = get_post_meta($post_id,'vsw_pmvw_video_autoplay',true);
				
				if(!empty($videoid)): // output only if video id is present!
								
				$title = $instance['title'];
				
				echo $before_widget;
		
				// if user written title echo out
				if ($title){
				echo $before_title . $title . $after_title;
				}

				//function to show video in blog sidebar, please look for it in helper-functions.php
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'false','false');
				
				if($videocaption){
				echo "<p class=\"VideoCaption\">$videocaption</p>";
				}	
		
				echo $after_widget;
				
				endif; // !empty($videoid) check
				
				endif; // is_single()||is_page() check
		
		}//end of function widget



		//function to update widget setting
		function update( $new_instance, $old_instance ) {
		
				$instance = $old_instance;
				$instance['title'] = strip_tags( $new_instance['title'] );
				return $instance;
		
		}//end of function update


		//function to create Widget Admin form
		function form($instance) {
		
				$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
				
				$instance['title'] = strip_tags( $instance['title'] );
?>
				<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Widget Title:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
				 type="text" value="<?php echo $instance['title']; ?>" />
				</p>
				
				 <div class="description">
				 <p>
				 Please use only one widget per sidebar. If there is multiple sidebars per post/page, please use only one widget on one sidebar.
				 </p>
  				<p>
  				The video output by this widget, will only be shown on Posts or Pages.
  				</p>
  				<p>
  				The settings for this widget is in your <a href="<?php echo admin_url().'post-new.php#vsw_post_meta_video_widget_setting';?>">Post Editor</a> or <a href="<?php echo admin_url().'post-new.php?post_type=page#vsw_post_meta_video_widget_setting';?>">Page Editor</a>. Please look for "Post Meta Video Widget Settings". This Widget will output Video according to individual post meta setting (Custom Field Setting).
  				</p>
  				<p>If you are not using this widget, you can disable this component <a href="<?php echo admin_url().'options-general.php?page=video_sidebar_widget_settings';?>">here</a> by checking on it and save changes.
  				</p>
  				</div>	
				<p>
				Thank you for reading this!
				</p>
				
				<?php
		
	      }//end of function form($instance)

}//end of Class



/* 
*Post meta box on right side of post editor
*/


// WP 3.0+
// add_action( 'add_meta_boxes', 'vsw_add_custom_box' );

$options = get_option('vsw_plugin_options'); // check whether to load post meta box.
if(empty($options['hide_pmvw'])){

	// backwards compatible
	add_action( 'admin_init', 'vsw_add_custom_box', 1);

	/* Do something with the data entered */
	add_action( 'save_post', 'vsw_save_postdata' );
}


/* Adds a box to the main column on the Post and Page edit screens */
function vsw_add_custom_box() {
    add_meta_box( 
        'vsw_post_meta_video_widget_setting',
        __( 'Post Meta Video Widget Settings', 'vsw_textdomain' ),
        'vsw_inner_custom_box',
        'post',
        'side' 
    );
    add_meta_box(
        'vsw_post_meta_video_widget_setting',
        __( 'Post Meta Video Widget Settings', 'vsw_textdomain' ), 
        'vsw_inner_custom_box',
        'page',
        'side'
    );
}

/* Prints the box content */
function vsw_inner_custom_box(){

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'vsw_noncename' );
  ?>
  
  <div class="description" id="vsw_post_meta_description" style="display:none;">
  <p>
  This is the widgets setting form for Post Meta Video Widget, which is a component of Video Sidebar Widgets Plugin. You can find the widget <a href="<?php echo admin_url().'widgets.php';?>">here</a>. 
  </p>
  <p>
  The video output by this widget, will only be shown on Posts or Pages.
  </p>
  <p>If you are not using this widget, you can disable this component <a href="<?php echo admin_url().'options-general.php?page=video_sidebar_widget_settings';?>">here</a> by checking on it and save changes.
  </p>
  <p>
  Thank you for reading this!
  </p>
  </div>
  <div>
    <p>
  <a href="#" onclick="document.getElementById('vsw_post_meta_description').style.display='block';return false;">What are these settings for?</a>
  </p>
  </div>



<?php
//retrieve all post meta setting to fill the form.
global $post;
$post_id = $post->ID;
$vsw_pmvw_video_source = get_post_meta($post_id,'vsw_pmvw_video_source',true);
$vsw_pmvw_video_id = get_post_meta($post_id,'vsw_pmvw_video_id',true);
$vsw_pmvw_video_width = get_post_meta($post_id,'vsw_pmvw_video_width',true);
$vsw_pmvw_video_height = get_post_meta($post_id,'vsw_pmvw_video_height',true);
$vsw_pmvw_video_caption = get_post_meta($post_id,'vsw_pmvw_video_caption',true);
$vsw_pmvw_video_autoplay = get_post_meta($post_id,'vsw_pmvw_video_autoplay',true);
?>

				<p><u>Video Preview in fixed width and height</u></p>

				<p>
<?php
VSWShowVideo($vsw_pmvw_video_source,$vsw_pmvw_video_id,$vsw_pmvw_video_autoplay,$vsw_pmvw_video_width,$vsw_pmvw_video_height,'true','false');
?>
				</p>

				
				<p>
				<label for="vsw_pmvw_video_source">Select Video Source:</label> 
				<select id="vsw_pmvw_video_source" name="vsw_pmvw_video_source" class="widefat" style="width:100%;">
				
				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
								
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($vsw_pmvw_video_source == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>
				</select>
				</p>


				<p>
				<label for="vsw_pmvw_video_id">Video ID: </label>
				<input class="widefat" id="vsw_pmvw_video_id" name="vsw_pmvw_video_id" type="text" value="<?php echo $vsw_pmvw_video_id; ?>" /></p>
				
				<p>
				<label for="vsw_pmvw_video_width">Video Width: </label>
				<input class="widefat" id="vsw_pmvw_video_width" name="vsw_pmvw_video_width" type="text" value="<?php echo $vsw_pmvw_video_width; ?>" />
				</p>
				
				<p>
				<label for="vsw_pmvw_video_height">Video Height: </label>
				<input class="widefat" id="vsw_pmvw_video_height" name="vsw_pmvw_video_height" type="text" value="<?php echo $vsw_pmvw_video_height; ?>" />
				</p>
				
                <p>
				<label for="vsw_pmvw_video_caption">Video Caption: </label>
				<input class="widefat" id="vsw_pmvw_video_caption" name="vsw_pmvw_video_caption" type="text" value="<?php echo $vsw_pmvw_video_caption; ?>" />
				</p>
                

				<?php
				
				// check whether autoplay feature supported by video network
				if($vsw_pmvw_video_autoplay == '1'):
				$source = $vsw_pmvw_video_source; 
				$msg = "<p class='description'>Sorry, auto play option not supported by ".$source."</p>";
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
						echo "<p class='description'>Sorry, auto play option for BlipTv was removed as it is causing error in Internet Explorer</p>";
						break;
						
						case 'cn6':
						echo "<p class='description'>Sorry, auto play option not supported by 6.cn</p>";
						break;
					}	
				
				endif;
				?>
				
				<p>
				<label for="vsw_pmvw_video_autoplay">Auto Play:</label> 
				<select id="vsw_pmvw_video_autoplay" 
                name="vsw_pmvw_video_autoplay" class="widefat" style="width:100%;">';
				<option value='0' <?php  if($vsw_pmvw_video_autoplay == '0'){echo 'selected="selected"';}?>>No</option>
				<option value='1' <?php  if($vsw_pmvw_video_autoplay == '1'){echo 'selected="selected"';}?>>Yes</option>
				</select>
				</p>

  
<?php
}

/* When the post is saved, saves our custom data */
function vsw_save_postdata( $post_id ){
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['vsw_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data
  
  $data1 =  $_POST['vsw_pmvw_video_source'];
  $data2 =  $_POST['vsw_pmvw_video_id'];
  $data3 =  $_POST['vsw_pmvw_video_width'];
  $data4 =  $_POST['vsw_pmvw_video_height'];
  $data5 =  $_POST['vsw_pmvw_video_caption']; 
  $data6 =  $_POST['vsw_pmvw_video_autoplay'];   
  
  
  global $post;
  $post_id = $post->ID;
  update_post_meta($post_id,'vsw_pmvw_video_source',$data1);
  update_post_meta($post_id,'vsw_pmvw_video_id',$data2);
  update_post_meta($post_id,'vsw_pmvw_video_width',$data3);
  update_post_meta($post_id,'vsw_pmvw_video_height',$data4);  
  update_post_meta($post_id,'vsw_pmvw_video_caption',$data5);
  update_post_meta($post_id,'vsw_pmvw_video_autoplay',$data6);  
  
}
?>