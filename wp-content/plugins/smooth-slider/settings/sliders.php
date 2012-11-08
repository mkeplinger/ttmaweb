<?php // This function displays the page content for the Smooth Slider Options submenu
function smooth_slider_create_multiple_sliders() {
global $smooth_slider;
?>

<div class="wrap" style="clear:both;">
                     <div style="margin:10px auto;clear:left;">
                        <a href="http://slidervilla.com/" title="Premium WordPress Slider Plugins" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/slidervilla-728.jpg');?>" alt="Premium WordPress Slider Plugins" /></a>
                     </div>
<h2 style="float:left;"><?php _e('Sliders Created','smooth-slider'); ?></h2>
<form  style="float:left;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8046056">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<?php 
if ($_POST['remove_posts_slider']) {
   if ( $_POST['slider_posts'] ) {
       global $wpdb, $table_prefix;
       $table_name = $table_prefix.SLIDER_TABLE;
	   $current_slider = $_POST['current_slider_id'];
	   foreach ( $_POST['slider_posts'] as $post_id=>$val ) {
		   $sql = "DELETE FROM $table_name WHERE post_id = '$post_id' AND slider_id = '$current_slider' LIMIT 1";
		   $wpdb->query($sql);
	   }
   }
   if ($_POST['remove_all'] == __('Remove All at Once','smooth-slider')) {
       global $wpdb, $table_prefix;
       $table_name = $table_prefix.SLIDER_TABLE;
	   $current_slider = $_POST['current_slider_id'];
	   if(is_slider_on_slider_table($current_slider)) {
		   $sql = "DELETE FROM $table_name WHERE slider_id = '$current_slider';";
		   $wpdb->query($sql);
	   }
   }
   if ($_POST['remove_all'] == __('Delete Slider','smooth-slider')) {
       $slider_id = $_POST['current_slider_id'];
       global $wpdb, $table_prefix;
       $slider_table = $table_prefix.SLIDER_TABLE;
       $slider_meta = $table_prefix.SLIDER_META;
	   $slider_postmeta = $table_prefix.SLIDER_POST_META;
	   if(is_slider_on_slider_table($slider_id)) {
		   $sql = "DELETE FROM $slider_table WHERE slider_id = '$slider_id';";
		   $wpdb->query($sql);
	   }
	   if(is_slider_on_meta_table($slider_id)) {
		   $sql = "DELETE FROM $slider_meta WHERE slider_id = '$slider_id';";
		   $wpdb->query($sql);
	   }
	   if(is_slider_on_postmeta_table($slider_id)) {
		   $sql = "DELETE FROM $slider_postmeta WHERE slider_id = '$slider_id';";
		   $wpdb->query($sql);
	   }
   }
}
if ($_POST['create_new_slider']) {
   $slider_name = $_POST['new_slider_name'];
   global $wpdb,$table_prefix;
   $slider_meta = $table_prefix.SLIDER_META;
   $sql = "INSERT INTO $slider_meta (slider_name) VALUES('$slider_name');";
   $result = $wpdb->query($sql);
}
if ($_POST['reorder_posts_slider']) {
   $i=1;
   global $wpdb, $table_prefix;
   $table_name = $table_prefix.SLIDER_TABLE;
   foreach ($_POST['order'] as $slide_order) {
    $slide_order = intval($slide_order);
    $sql = 'UPDATE '.$table_name.' SET slide_order='.$i.' WHERE post_id='.$slide_order.'';
    $wpdb->query($sql);
    $i++;
  }
}
?>
<div style="clear:both"></div>
<?php $url = sslider_admin_url( array( 'page' => 'smooth-slider-settings' ) );?>
<a href="<?php echo $url; ?>" title="<?php _e('Settings Page for Smooth Slider where you can change the color, font etc. for the sliders','smooth-slider'); ?>"><?php _e('Go to Smooth Slider Settings page','smooth-slider'); ?></a>
<?php $sliders = ss_get_sliders(); ?>

<div id="slider_tabs">
        <ul class="ui-tabs">
        <?php foreach($sliders as $slider){?>
            <li><a href="#tabs-<?php echo $slider['slider_id'];?>"><?php echo $slider['slider_name'];?></a></li>
        <?php } ?>
        <?php if($smooth_slider['multiple_sliders'] == '1') {?>
            <li><a href="#new_slider"><?php _e('Create New Slider','smooth-slider'); ?></a></li>
        <?php } ?>
        </ul>

<?php foreach($sliders as $slider){?>
<div id="tabs-<?php echo $slider['slider_id'];?>">
<form action="" method="post">
<?php settings_fields('smooth-slider-group'); ?>

<input type="hidden" name="remove_posts_slider" value="1" />
<div id="tabs-<?php echo $slider['slider_id'];?>">
<h3><?php _e('Posts/Pages Added To','smooth-slider'); ?> <?php echo $slider['slider_name'];?><?php _e('(Slider ID','smooth-slider'); ?> = <?php echo $slider['slider_id'];?>)</h3>
<p><em><?php _e('Check the Post/Page and Press "Remove Selected" to remove them From','smooth-slider'); ?> <?php echo $slider['slider_name'];?>. <?php _e('Press "Remove All at Once" to remove all the posts from the','smooth-slider'); ?> <?php echo $slider['slider_name'];?>.</em></p>

    <table class="widefat">
    <thead><tr><th><?php _e('Post/Page Title','smooth-slider'); ?></th><th><?php _e('Author','smooth-slider'); ?></th><th><?php _e('Post Date','smooth-slider'); ?></th><th><?php _e('Remove Post','smooth-slider'); ?></th></tr></thead><tbody>

<?php  
	/*global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;*/
	$slider_id = $slider['slider_id'];
	//$slider_posts = $wpdb->get_results("SELECT post_id FROM $table_name WHERE slider_id = '$slider_id'", OBJECT); 
    $slider_posts=get_slider_posts_in_order($slider_id); ?>
	
    <input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
    
<?php    $count = 0;	
	foreach($slider_posts as $slider_post) {
	  $slider_arr[] = $slider_post->post_id;
	  $post = get_post($slider_post->post_id);	  
	  if ( in_array($post->ID, $slider_arr) ) {
		  $count++;
		  $sslider_author = get_userdata($post->post_author);
          $sslider_author_dname = $sslider_author->display_name;
		  echo '<tr' . ($count % 2 ? ' class="alternate"' : '') . '><td><strong>' . $post->post_title . '</strong><a href="'.get_edit_post_link( $post->ID, $context = 'display' ).'" target="_blank"> '.__( '(Edit)', 'smooth-slider' ).'</a> <a href="'.get_permalink( $post->ID ).'" target="_blank"> '.__( '(View)', 'smooth-slider' ).' </a></td><td>By ' . $sslider_author_dname . '</td><td>' . date('l, F j. Y',strtotime($post->post_date)) . '</td><td><input type="checkbox" name="slider_posts[' . $post->ID . ']" value="1" /></td></tr>'; 
	  }
	}
		
	if ($count == 0) {
		echo '<tr><td colspan="4">'.__( 'No posts/pages have been added to the Slider - You can add respective post/page to slider on the Edit screen for that Post/Page', 'smooth-slider' ).'</td></tr>';
	}
	echo '</tbody><tfoot><tr><th>'.__( 'Post/Page Title', 'smooth-slider' ).'</th><th>'.__( 'Author', 'smooth-slider' ).'</th><th>'.__( 'Post Date', 'smooth-slider' ).'</th><th>'.__( 'Remove Post', 'smooth-slider' ).'</th></tr></tfoot></table>'; 
    
	echo '<div class="submit">';
	
	if ($count) {echo '<input type="submit" value="'.__( 'Remove Selected', 'smooth-slider' ).'" onclick="return confirmRemove()" /><input type="submit" name="remove_all" value="'.__( 'Remove All at Once', 'smooth-slider' ).'" onclick="return confirmRemoveAll()" />';}
	
	if($slider_id != '1') {
	   echo '<input type="submit" value="'.__( 'Delete Slider', 'smooth-slider' ).'" name="remove_all" onclick="return confirmSliderDelete()" />';
	}
	
	echo '</div>';
?>    
    </tbody></table>
 </form>
 
 
 <form action="" method="post">
    <input type="hidden" name="reorder_posts_slider" value="1" />
    <h3><?php _e('Reorder the Posts/Pages Added To','smooth-slider'); ?> <?php echo $slider['slider_name'];?>(Slider ID = <?php echo $slider['slider_id'];?>)</h3>
    <p><em><?php _e('Click on and drag the post/page title to a new spot within the list, and the other items will adjust to fit.','smooth-slider'); ?> </em></p>
    <ul id="sslider_sortable_<?php echo $slider['slider_id'];?>" style="color:#326078">    
    <?php  
    /*global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;*/
	$slider_id = $slider['slider_id'];
	//$slider_posts = $wpdb->get_results("SELECT post_id FROM $table_name WHERE slider_id = '$slider_id'", OBJECT); 
    $slider_posts=get_slider_posts_in_order($slider_id);?>
        
        <input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
        
    <?php    $count = 0;	
        foreach($slider_posts as $slider_post) {
          $slider_arr[] = $slider_post->post_id;
          $post = get_post($slider_post->post_id);	  
          if ( in_array($post->ID, $slider_arr) ) {
              $count++;
              $sslider_author = get_userdata($post->post_author);
              $sslider_author_dname = $sslider_author->display_name;
              echo '<li id="'.$post->ID.'"><input type="hidden" name="order[]" value="'.$post->ID.'" /><strong> &raquo; &nbsp; ' . $post->post_title . '</strong></li>'; 
          }
        }
            
        if ($count == 0) {
            echo '<li>'.__( 'No posts/pages have been added to the Slider - You can add respective post/page to slider on the Edit screen for that Post/Page', 'smooth-slider' ).'</li>';
        }
		        
        echo '</ul><div class="submit">';
        
        if ($count) {echo '<input type="submit" value="Save the order"  />';}
                
        echo '</div>';
    ?>    
       </div>       
  </form>
</div> 
 
<?php } ?>

<?php if($smooth_slider['multiple_sliders'] == '1') {?>
    <div id="new_slider">
    <form action="" method="post" onsubmit="return slider_checkform(this);" >
    <h3><?php _e('Enter New Slider Name','smooth-slider'); ?></h3>
    <input type="hidden" name="create_new_slider" value="1" />
    
    <input name="new_slider_name" class="regular-text code" value="" style="clear:both;" />
    
    <div class="submit"><input type="submit" value="<?php _e('Create New','smooth-slider'); ?>" name="create_new" /></div>
    
    </form>
    </div>
<?php }?> 
</div>

<div style="margin:10px auto;clear:left;">
                        <a href="http://slidervilla.com/" title="Premium WordPress Slider Plugins" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/slidervilla-728.jpg');?>" alt="Premium WordPress Slider Plugins" /></a>
</div>

<div id="poststuff" class="metabox-holder has-right-sidebar"> 
   <div id="side-info-column" class="inner-sidebar" style="float:left;"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('About this Plugin:','smooth-slider'); ?></span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://www.clickonf5.org/smooth-slider" title="Smooth Slider Homepage" ><?php _e('Plugin Homepage','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org" title="Visit Internet Techies" ><?php _e('Plugin Parent Site','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org/phpbb/smooth-slider-f12/" title="Support Forum for Smooth Slider" ><?php _e('Support Forum','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="Smooth Slider Author Page" ><?php _e('About the Author','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org/go/smooth-slider/" title="<?php _e('Donate if you liked the plugin and support in enhancing Smooth Slider and creating new plugins','smooth-slider'); ?>" ><?php _e('Donate with Paypal','smooth-slider'); ?></a></li>
                </ul> 
              </div> 
			</div> 
     </div>
     
        <div id="side-info-column" class="inner-sidebar" style="float:left;margin-left:1em"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Our Facebook Fan Page','smooth-slider'); ?></h3> 
			  <div class="inside">
                <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_GB"></script><script type="text/javascript">FB.init("2aeebe9fb014836a6810ec4426d26f7e");</script><fb:fan profile_id="127760528543" stream="" connections="8" width="270" height="250"></fb:fan>
              </div> 
			</div> 
     </div>

     <div id="side-info-column" class="inner-sidebar" style="float:left;margin-left:1em"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('Credits:','smooth-slider'); ?></span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://sorgalla.com/jcarousel/" title="jCarousel jQuey plugin" >Riding carousels with jQuery</a></li>
                <li><a href="http://acko.net/dev/farbtastic" title="Farbtastic Color Picker by Steven Wittens" >Farbtastic Color Picker</a></li>
                <li><a href="http://jquery.com/" title="jQuery JavaScript Library - John Resig" >jQuery JavaScript Library</a></li>
                </ul> 
              </div> 
			</div> 
     </div>
     
          <div id="side-info-column" class="inner-sidebar" style="float:left;margin-left:1em"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('Top Supporters','smooth-slider'); ?></span></h3> 
			  <div class="inside">
                <div id="smooth_sldr_donations">
					 <ul>
                        <li><a href="http://www.jacobwiechman.com/wordpress//" title="Visit Jacob Wiechman - $50" >Jacob Wiechman - $50</a></li>
                        <li><a href="http://malamedconsulting.com/" title="Visit Connie Malamed - $25" >Connie Malamed - $25</a></li>
                        <li><a href="http://uwaterloo.ca/" title="Visit Trevor Bain - $25" >Trevor Bain - $25</a></li>
                        <li><a href="http://www.whatsthebigidea.com/" title="Visit WhatsTheBigIdea.com,Inc. - $20" >WhatsTheBigIdea.com,Inc. - $20</a></li>
                     </ul>  
                </div>
              </div> 
			</div> 
          </div>  
          <div style="clear:left;"></div>

     <div id="side-info-column" style="float:left;width:325px;"> 
        <div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Recommended WordPress Hosting','smooth-slider'); ?></h3> 
			  <div class="inside">
                  <div style="margin:10px 5px">
            <a href="http://slidervilla.com/go/hostgator/" title="Recommended Web Hosting" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/hostgator.gif');?>" alt="Recommended Web Hosting" /></a>
            <p><a href="http://slidervilla.com/go/hostgator/" title="Recommended Web Hosting" target="_blank">HostGator</a> is one of the world's top 10 largest web hosting companies with more than 5,000,000 hosted domains. You can host your own WordPress installation with custom themes, plugins, and your own domain name with HostGator from only $3.96 a month.</p>
            <p><strong>Features: </strong>UNLIMITED Disk Space and Bandwidth, FREE Site Building Tools and Templates, 24/7/365 Award Winning Technical Support</p>
            <p>For more info visit <a href="http://slidervilla.com/go/hostgator/" title="Recommended Web Hosting" target="_blank">HostGator.com</a></p>
                  </div>
          </div></div></div>
     
     		<div id="side-info-column" style="float:left;margin-left:1em;width:325px;"> 
            <div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Recommended Themes','smooth-slider'); ?></h3> 
			  <div class="inside">
                     <div style="margin:10px 5px">
                        <a href="http://slidervilla.com/go/elegantthemes/" title="Recommended WordPress Themes" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/elegantthemes.gif');?>" alt="Recommended WordPress Themes" /></a>
                        <p><a href="http://slidervilla.com/go/elegantthemes/" title="Recommended WordPress Themes" target="_blank">Elegant Themes</a> are attractive, compatible, affordable, SEO optimized WordPress Themes and have best support in community.</p>
                        <p><strong>Beautiful themes, Great support!</strong></p>
                        <p><a href="http://slidervilla.com/go/elegantthemes/" title="Recommended WordPress Themes" target="_blank">For more info visit ElegantThemes</a></p>
                     </div>
               </div></div></div>
     
     
     <div style="clear:left;"></div>
 </div> <!--end of poststuff --> 


</div> <!--end of float wrap -->
<?php	
}
?>