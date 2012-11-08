<?php // Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'smooth_slider_settings');
  add_action( 'admin_init', 'register_mysettings' ); 
} 

// function for adding settings page to wp-admin
function smooth_slider_settings() {
    // Add a new submenu under Options:
  //  add_options_page('Smooth Slider', 'Smooth Slider', 9, basename(__FILE__), 'smooth_slider_settings_page');
	add_menu_page( 'Smooth Slider', 'Smooth Slider', 'manage_options','smooth-slider-admin', 'smooth_slider_create_multiple_sliders', smooth_slider_plugin_url( 'images/smooth_slider_icon.gif' ) );
	add_submenu_page('smooth-slider-admin', 'Smooth Sliders', 'Sliders', 'manage_options', 'smooth-slider-admin', 'smooth_slider_create_multiple_sliders');
	add_submenu_page('smooth-slider-admin', 'Smooth Slider Settings', 'Settings', 'manage_options', 'smooth-slider-settings', 'smooth_slider_settings_page');
}
include('sliders.php');
// This function displays the page content for the Smooth Slider Options submenu
function smooth_slider_settings_page() {
global $smooth_slider;
?>

<div class="wrap" style="clear:both;">

<h2 style="float:left;"><?php _e('Smooth Slider Settings ','smooth-slider'); ?></h2>
<form  style="float:left;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8046056">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<?php $url = sslider_admin_url( array( 'page' => 'smooth-slider-admin' ) );?>
<button><a href="<?php echo $url; ?>" title="<?php _e('Go to Sliders page where you can re-order the slide posts, delete the slides from the slider etc.','smooth-slider'); ?>"><?php _e('Go to Sliders Admin','smooth-slider'); ?></a></button>


<form method="post" action="options.php">
<h2 style="clear:left"><?php _e('Preview','smooth-slider'); ?></h2> 
<?php settings_fields('smooth-slider-group'); ?>
<div>
<?php 
get_smooth_slider();
?> </div>

<div id="poststuff" class="metabox-holder has-right-sidebar" style="float:right;width:28%;"> 
            <div class="postbox"> 
     		  <div class="inside">

            <div style="margin:10px auto;">
                        <a href="http://slidervilla.com/" title="Premium WordPress Slider Plugins" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/slidervilla-ad1.jpg');?>" alt="Premium WordPress Slider Plugins" /></a>
            </div>
            </div></div>
                
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('About this Plugin:','smooth-slider'); ?></span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://www.clickonf5.org/smooth-slider" title="<?php _e('Smooth Slider Homepage','smooth-slider'); ?>
" ><?php _e('Plugin Homepage','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org" title="<?php _e('Visit Internet Techies','smooth-slider'); ?>
" ><?php _e('Plugin Parent Site','smooth-slider'); ?></a></li>
                <li><a href="http://clickonf5.com/" title="<?php _e('Support Forum for Smooth Slider','smooth-slider'); ?>
" ><?php _e('Support Forum','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="<?php _e('Smooth Slider Author Page','smooth-slider'); ?>
" ><?php _e('About the Author','smooth-slider'); ?></a></li>
                <li><a href="http://www.clickonf5.org/go/smooth-slider/" title="<?php _e('Donate if you liked the plugin and support in enhancing Smooth Slider and creating new plugins','smooth-slider'); ?>" ><?php _e('Donate with Paypal','smooth-slider'); ?></a></li>
                </ul> 
              </div> 
			</div> 
     
			<div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Our Facebook Fan Page','smooth-slider'); ?></h3> 
			  <div class="inside">
                <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_GB"></script><script type="text/javascript">FB.init("2aeebe9fb014836a6810ec4426d26f7e");</script><fb:fan profile_id="127760528543" stream="" connections="8" width="270" height="250"></fb:fan>
              </div> 
			</div> 

     		<div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Recommended WordPress Hosting','smooth-slider'); ?></h3> 
			  <div class="inside">
                  <div style="margin:10px 5px">
            <a href="http://slidervilla.com/go/hostgator/" title="Recommended Web Hosting" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/hostgator.gif');?>" alt="Recommended Web Hosting" /></a>
            <p><a href="http://slidervilla.com/go/hostgator/" title="Recommended Web Hosting" target="_blank">HostGator</a> is one of the world's top 10 largest web hosting companies with more than 5,000,000 hosted domains. You can host your own WordPress installation with custom themes, plugins, and your own domain name with HostGator from only $3.96 a month.</p>
            <p><strong>Features: </strong>UNLIMITED Disk Space and Bandwidth, FREE Site Building Tools and Templates, 24/7/365 Award Winning Technical Support</p>
            <p>For more info visit <a href="http://slidervilla.com/go/hostgator/" title="Recommended Web Hosting" target="_blank">HostGator.com</a></p>
                  </div>
          </div></div>
     
     		<div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Recommended Themes','smooth-slider'); ?></h3> 
			  <div class="inside">
                     <div style="margin:10px 5px">
                        <a href="http://slidervilla.com/go/elegantthemes/" title="Recommended WordPress Themes" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/elegantthemes.gif');?>" alt="Recommended WordPress Themes" /></a>
                        <p><a href="http://slidervilla.com/go/elegantthemes/" title="Recommended WordPress Themes" target="_blank">Elegant Themes</a> are attractive, compatible, affordable, SEO optimized WordPress Themes and have best support in community.</p>
                        <p><strong>Beautiful themes, Great support!</strong></p>
                        <p><a href="http://slidervilla.com/go/elegantthemes/" title="Recommended WordPress Themes" target="_blank">For more info visit ElegantThemes</a></p>
                     </div>
               </div></div>
            
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

 </div> <!--end of poststuff --> 


<div style="float:left;width:60%;">
<h2><?php _e('Slider Box','smooth-slider'); ?></h2> 
<p><?php _e('Customize the looks of the Slider box wrapping the complete slideshow from here','smooth-slider'); ?></p> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Slide Pause Interval','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[speed]" id="smooth_slider_speed" class="small-text" value="<?php echo $smooth_slider['speed']; ?>" /> &nbsp;<?php _e('(in secs)','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Slide Transition Speed','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[transition]" id="smooth_slider_transition" class="small-text" value="<?php echo $smooth_slider['transition']; ?>" /><?php _e('*100(in millisecs)-duration of the slide animation Lower value indicates faster','smooth-slider'); ?><small style="color:#FF0000"><?php _e(' (IMP!! Enter numeric value > 0)','smooth-slider'); ?></small></td>
</tr>

<tr valign="top">
<th scope="row"></th>
<td><label for="smooth_slider_autostep"> 
<input name="smooth_slider_options[autostep]" type="checkbox" id="smooth_slider_autostep" value="1" <?php checked("1", $smooth_slider['autostep']); ?> /> 
<?php _e(' Enable autostepping of slides','smooth-slider'); ?></label></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Number of Posts in the Slideshow','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[no_posts]" id="smooth_slider_no_posts" class="small-text" value="<?php echo $smooth_slider['no_posts']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Background Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[bg_color]" id="color_value_1" value="<?php echo $smooth_slider['bg_color']; ?>" />&nbsp; <img id="color_picker_1" src="<?php echo smooth_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','smooth-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_1"></div> &nbsp; &nbsp; &nbsp; 
<label for="smooth_slider_bg"><input name="smooth_slider_options[bg]" type="checkbox" id="smooth_slider_bg" value="1" <?php checked('1', $smooth_slider['bg']); ?>  /><?php _e(' Use Transparent Background','smooth-slider'); ?></label> </td>
</tr>
 
<tr valign="top">
<th scope="row"><?php _e('Slider Height','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[height]" id="smooth_slider_height" class="small-text" value="<?php echo $smooth_slider['height']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>


<tr valign="top">
<th scope="row"><?php _e('Slider Width','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[width]" id="smooth_slider_width" class="small-text" value="<?php echo $smooth_slider['width']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Thickness','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[border]" id="smooth_slider_border" class="small-text" value="<?php echo $smooth_slider['border']; ?>" />&nbsp;<?php _e('px (put 0 if no border is required)','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[brcolor]" id="color_value_6" value="<?php echo $smooth_slider['brcolor']; ?>" />&nbsp; <img id="color_picker_6" src="<?php echo smooth_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','smooth-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_6"></div></td>
</tr>

<tr valign="top"> 
<th scope="row"><?php _e('Navigation Buttons','smooth-slider'); ?></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Navigation Buttons','smooth-slider'); ?></span></legend> 
<label for="smooth_slider_prev_next"> 
<input name="smooth_slider_options[prev_next]" type="checkbox" id="smooth_slider_prev_next" value="1" <?php checked("1", $smooth_slider['prev_next']); ?> /> 
 <?php _e('Show Prev/Next navigation arrows','smooth-slider'); ?></label><br /> 
<label for="smooth_slider_goto_slide"><?php _e('Show go to slide number links or images','smooth-slider'); ?></label><br />
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="0" <?php checked('0', $smooth_slider['goto_slide']); ?>  /> <?php _e('None ','smooth-slider'); ?><br /> 
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="1" <?php checked('1', $smooth_slider['goto_slide']); ?>  /> <?php _e('Numbers','smooth-slider'); ?> <br /> 
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="2" <?php checked('2', $smooth_slider['goto_slide']); ?>  /> <?php _e('Custom Images for Navigation','smooth-slider'); ?> &nbsp; &nbsp;<?php _e('Width: ','smooth-slider'); ?><input type="text" name="smooth_slider_options[navimg_w]" id="smooth_slider_navimg_w" class="small-text" value="<?php echo $smooth_slider['navimg_w']; ?>" /> <?php _e('px','smooth-slider'); ?> &nbsp;<?php _e('Height: ','smooth-slider'); ?><input type="text" name="smooth_slider_options[navimg_ht]" id="smooth_slider_navimg_ht" class="small-text" value="<?php echo $smooth_slider['navimg_ht']; ?>" /> <?php _e('px','smooth-slider'); ?><br /> 
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="3" <?php checked('3', $smooth_slider['goto_slide']); ?>  /> <?php _e('Enter Custom Text or HTML','smooth-slider'); ?> &nbsp; &nbsp; 
<input type="text" name="smooth_slider_options[custom_nav]" class="regular-text code" value="<?php echo htmlentities($smooth_slider['custom_nav'], ENT_QUOTES); ?>" />
</fieldset></td> 
</tr> 

</table>

<h2><?php _e('Slider Title','smooth-slider'); ?></h2> 
<p><?php _e('Customize the looks of the main title of the Slideshow from here','smooth-slider'); ?></p> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Default Title Text','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[title_text]" id="smooth_slider_title_text" value="<?php echo $smooth_slider['title_text']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Pick Slider Title From','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[title_from]" >
<option value="0" <?php if ($smooth_slider['title_from'] == "0"){ echo "selected";}?> ><?php _e('Default Title Text','smooth-slider'); ?></option>
<option value="1" <?php if ($smooth_slider['title_from'] == "1"){ echo "selected";}?> ><?php _e('Slider Name','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[title_font]" id="smooth_slider_title_font" >
<option value="Arial" <?php if ($smooth_slider['title_font'] == "Arial"){ echo "selected";}?> >Arial</option>
<option value="Book Antiqua" <?php if ($smooth_slider['title_font'] == "Book Antiqua"){ echo "selected";}?> >Book Antiqua</option>
<option value="Bookman Old Style" <?php if ($smooth_slider['title_font'] == "Bookman Old Style"){ echo "selected";}?> >Bookman Old Style</option>
<option value="Calibri" <?php if ($smooth_slider['title_font'] == "Calibri"){ echo "selected";}?> >Calibri</option>
<option value="Century Schoolbook" <?php if ($smooth_slider['title_font'] == "Century Schoolbook"){ echo "selected";}?> >Century Schoolbook</option>
<option value="Courier New" <?php if ($smooth_slider['title_font'] == "Courier New"){ echo "selected";}?> >Courier New</option>
<option value="Geneva" <?php if ($smooth_slider['title_font'] == "Geneva"){ echo "selected";}?> >Geneva</option>
<option value="Georgia" <?php if ($smooth_slider['title_font'] == "Georgia"){ echo "selected";} ?> >Georgia</option>
<option value="Helvetica" <?php if ($smooth_slider['title_font'] == "Helvetica"){ echo "selected";}?> >Helvetica</option>
<option value="Monotype Corsiva" <?php if ($smooth_slider['title_font'] == "Monotype Corsiva"){ echo "selected";}?> >Monotype Corsiva</option>
<option value="Times New Roman" <?php if ($smooth_slider['title_font'] == "Times New Roman"){ echo "selected";}?> >Times New Roman</option>
<option value="Trebuchet MS" <?php if ($smooth_slider['title_font'] == "Trebuchet MS"){ echo "selected";}?> >Trebuchet MS</option>
<option value="Verdana" <?php if ($smooth_slider['title_font'] == "Verdana"){ echo "selected";}?> >Verdana</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[title_fcolor]" id="color_value_2" value="<?php echo $smooth_slider['title_fcolor']; ?>" />&nbsp; <img id="color_picker_2" src="<?php echo smooth_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','smooth-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_2"></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[title_fsize]" id="smooth_slider_title_fsize" class="small-text" value="<?php echo $smooth_slider['title_fsize']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[title_fstyle]" id="smooth_slider_title_fstyle" >
<option value="bold" <?php if ($smooth_slider['title_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','smooth-slider'); ?></option>
<option value="bold italic" <?php if ($smooth_slider['title_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','smooth-slider'); ?></option>
<option value="italic" <?php if ($smooth_slider['title_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','smooth-slider'); ?></option>
<option value="normal" <?php if ($smooth_slider['title_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','smooth-slider'); ?></option>
</select>
</td>
</tr>
</table>

<h2><?php _e('Post Title','smooth-slider'); ?></h2> 
<p><?php _e('Customize the looks of the title of each of the sliding post here','smooth-slider'); ?></p> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Font','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[ptitle_font]" id="smooth_slider_ptitle_font" >
<option value="Arial" <?php if ($smooth_slider['ptitle_font'] == "Arial"){ echo "selected";}?> >Arial</option>
<option value="Book Antiqua" <?php if ($smooth_slider['ptitle_font'] == "Book Antiqua"){ echo "selected";}?> >Book Antiqua</option>
<option value="Bookman Old Style" <?php if ($smooth_slider['ptitle_font'] == "Bookman Old Style"){ echo "selected";}?> >Bookman Old Style</option>
<option value="Calibri" <?php if ($smooth_slider['ptitle_font'] == "Calibri"){ echo "selected";}?> >Calibri</option>
<option value="Century Schoolbook" <?php if ($smooth_slider['ptitle_font'] == "Century Schoolbook"){ echo "selected";}?> >Century Schoolbook</option>
<option value="Courier New" <?php if ($smooth_slider['ptitle_font'] == "Courier New"){ echo "selected";}?> >Courier New</option>
<option value="Geneva" <?php if ($smooth_slider['ptitle_font'] == "Geneva"){ echo "selected";}?> >Geneva</option>
<option value="Georgia" <?php if ($smooth_slider['ptitle_font'] == "Georgia"){ echo "selected";} ?> >Georgia</option>
<option value="Helvetica" <?php if ($smooth_slider['ptitle_font'] == "Helvetica"){ echo "selected";}?> >Helvetica</option>
<option value="Monotype Corsiva" <?php if ($smooth_slider['ptitle_font'] == "Monotype Corsiva"){ echo "selected";}?> >Monotype Corsiva</option>
<option value="Times New Roman" <?php if ($smooth_slider['ptitle_font'] == "Times New Roman"){ echo "selected";}?> >Times New Roman</option>
<option value="Trebuchet MS" <?php if ($smooth_slider['ptitle_font'] == "Trebuchet MS"){ echo "selected";}?> >Trebuchet MS</option>
<option value="Verdana" <?php if ($smooth_slider['ptitle_font'] == "Verdana"){ echo "selected";}?> >Verdana</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[ptitle_fcolor]" id="color_value_3" value="<?php echo $smooth_slider['ptitle_fcolor']; ?>" />&nbsp; <img id="color_picker_3" src="<?php echo smooth_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','smooth-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_3"></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[ptitle_fsize]" id="smooth_slider_ptitle_fsize" class="small-text" value="<?php echo $smooth_slider['ptitle_fsize']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[ptitle_fstyle]" id="smooth_slider_ptitle_fstyle" >
<option value="bold" <?php if ($smooth_slider['ptitle_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','smooth-slider'); ?></option>
<option value="bold italic" <?php if ($smooth_slider['ptitle_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','smooth-slider'); ?></option>
<option value="italic" <?php if ($smooth_slider['ptitle_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','smooth-slider'); ?></option>
<option value="normal" <?php if ($smooth_slider['ptitle_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','smooth-slider'); ?></option>
</select>
</td>
</tr>
</table>

<h2><?php _e('Thumbnail Image','smooth-slider'); ?></h2> 
<p><?php _e('Customize the looks of the thumbnail image for each of the sliding post here','smooth-slider'); ?></p> 
<table class="form-table">

<tr valign="top"> 
<th scope="row"><?php _e('Image Pick Preferences','smooth-slider'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','smooth-slider'); ?></small></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Image Pick Sequence','smooth-slider'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','smooth-slider'); ?></small> </span></legend> 
<input name="smooth_slider_options[img_pick][0]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][0]); ?>  /> <?php _e('Use Custom Field/Key','smooth-slider'); ?> &nbsp; &nbsp; 
<input type="text" name="smooth_slider_options[img_pick][1]" class="text" value="<?php echo $smooth_slider['img_pick'][1]; ?>" /> <?php _e('Name of the Custom Field/Key','smooth-slider'); ?>
<br />
<input name="smooth_slider_options[img_pick][2]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][2]); ?>  /> <?php _e('Use Featured Post/Thumbnail (Wordpress 3.0 +  feature)','smooth-slider'); ?>&nbsp; <br />
<input name="smooth_slider_options[img_pick][3]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][3]); ?>  /> <?php _e('Consider Images attached to the post','smooth-slider'); ?> &nbsp; &nbsp; 
<input type="text" name="smooth_slider_options[img_pick][4]" class="small-text" value="<?php echo $smooth_slider['img_pick'][4]; ?>" /> <?php _e('Order of the Image attachment to pick','smooth-slider'); ?> &nbsp; <br />
<input name="smooth_slider_options[img_pick][5]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][5]); ?>  /> <?php _e('Scan images from the post, in case there is no attached image to the post','smooth-slider'); ?>&nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row"><?php _e('Align to','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[img_align]" id="smooth_slider_img_align" >
<option value="left" <?php if ($smooth_slider['img_align'] == "left"){ echo "selected";}?> ><?php _e('Left','smooth-slider'); ?></option>
<option value="right" <?php if ($smooth_slider['img_align'] == "right"){ echo "selected";}?> ><?php _e('Right','smooth-slider'); ?></option>
<option value="none" <?php if ($smooth_slider['img_align'] == "none"){ echo "selected";}?> ><?php _e('Center','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Wordpress Image Extract Size','smooth-slider'); ?>
</th>
<td><select name="smooth_slider_options[crop]" id="smooth_slider_img_crop" >
<option value="0" <?php if ($smooth_slider['crop'] == "0"){ echo "selected";}?> ><?php _e('Full','smooth-slider'); ?></option>
<option value="1" <?php if ($smooth_slider['crop'] == "1"){ echo "selected";}?> ><?php _e('Large','smooth-slider'); ?></option>
<option value="2" <?php if ($smooth_slider['crop'] == "2"){ echo "selected";}?> ><?php _e('Medium','smooth-slider'); ?></option>
<option value="3" <?php if ($smooth_slider['crop'] == "3"){ echo "selected";}?> ><?php _e('Thumbnail','smooth-slider'); ?></option>
</select>
<small><?php _e('This is for fast page load, in case you choose \'Custom Size\' setting from below, you would not like to extract \'full\' size image from the media library. In this case you can use, \'medium\' or \'thumbnail\' image. This is because, for every image upload to the media gallery WordPress creates four sizes of the same image. So you can choose which to load in the slider and then specify the actual size.','smooth-slider'); ?></small>
</td>
</tr>


<tr valign="top"> 
<th scope="row"><?php _e('Image Size','smooth-slider'); ?></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Image Size','smooth-slider'); ?></span></legend> 
<input name="smooth_slider_options[img_size]" type="radio" value="0" <?php checked('0', $smooth_slider['img_size']); ?>  /> <?php _e('Original Size','smooth-slider'); ?> <small><?php _e('(In this case, the size would be equal to the extracted image (full/large/medium/thumbnail) from the above settings','smooth-slider'); ?></small><br />
<input name="smooth_slider_options[img_size]" type="radio" value="1" <?php checked('1', $smooth_slider['img_size']); ?>  /> <?php _e('Custom Size:','smooth-slider'); ?>&nbsp; 
<label for="smooth_slider_options[img_width]"><?php _e('Width','smooth-slider'); ?></label>
<input type="text" name="smooth_slider_options[img_width]" class="small-text" value="<?php echo $smooth_slider['img_width']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?> &nbsp;&nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row"><?php _e('Maximum Height of the Image','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[img_height]" class="small-text" value="<?php echo $smooth_slider['img_height']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?> &nbsp;&nbsp; <?php _e('(This is necessary in order to keep the maximum image height in control)','smooth-slider'); ?></td>
</tr>


<tr valign="top">
<th scope="row"><?php _e('Border Thickness','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[img_border]" id="smooth_slider_img_border" class="small-text" value="<?php echo $smooth_slider['img_border']; ?>" />&nbsp;<?php _e('px  (put 0 if no border is required)','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[img_brcolor]" id="color_value_4" value="<?php echo $smooth_slider['img_brcolor']; ?>" />&nbsp; <img id="color_picker_4" src="<?php echo smooth_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','smooth-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_4"></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Make pure Image Slider','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[image_only]" type="checkbox" value="1" <?php checked('1', $smooth_slider['image_only']); ?>  />&nbsp;<?php _e('(check this to convert Smooth Slider to Image Slider with no content)','smooth-slider'); ?></td>
</tr>
</table>

<h2><?php _e('Slider Content','smooth-slider'); ?></h2> 
<p><?php _e('Customize the looks of the content of each of the sliding post here','smooth-slider'); ?></p> 
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Font','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[content_font]" id="smooth_slider_content_font" >
<option value="Arial" <?php if ($smooth_slider['content_font'] == "Arial"){ echo "selected";}?> >Arial</option>
<option value="Book Antiqua" <?php if ($smooth_slider['content_font'] == "Book Antiqua"){ echo "selected";}?> >Book Antiqua</option>
<option value="Bookman Old Style" <?php if ($smooth_slider['content_font'] == "Bookman Old Style"){ echo "selected";}?> >Bookman Old Style</option>
<option value="Calibri" <?php if ($smooth_slider['content_font'] == "Calibri"){ echo "selected";}?> >Calibri</option>
<option value="Century Schoolbook" <?php if ($smooth_slider['content_font'] == "Century Schoolbook"){ echo "selected";}?> >Century Schoolbook</option>
<option value="Courier New" <?php if ($smooth_slider['content_font'] == "Courier New"){ echo "selected";}?> >Courier New</option>
<option value="Geneva" <?php if ($smooth_slider['content_font'] == "Geneva"){ echo "selected";}?> >Geneva</option>
<option value="Georgia" <?php if ($smooth_slider['content_font'] == "Georgia"){ echo "selected";} ?> >Georgia</option>
<option value="Helvetica" <?php if ($smooth_slider['content_font'] == "Helvetica"){ echo "selected";}?> >Helvetica</option>
<option value="Monotype Corsiva" <?php if ($smooth_slider['content_font'] == "Monotype Corsiva"){ echo "selected";}?> >Monotype Corsiva</option>
<option value="Times New Roman" <?php if ($smooth_slider['content_font'] == "Times New Roman"){ echo "selected";}?> >Times New Roman</option>
<option value="Trebuchet MS" <?php if ($smooth_slider['content_font'] == "Trebuchet MS"){ echo "selected";}?> >Trebuchet MS</option>
<option value="Verdana" <?php if ($smooth_slider['content_font'] == "Verdana"){ echo "selected";}?> >Verdana</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[content_fcolor]" id="color_value_5" value="<?php echo $smooth_slider['content_fcolor']; ?>" />&nbsp; <img id="color_picker_5" src="<?php echo smooth_slider_plugin_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice','smooth-slider'); ?>" /><div class="color-picker-wrap" id="colorbox_5"></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[content_fsize]" id="smooth_slider_content_fsize" class="small-text" value="<?php echo $smooth_slider['content_fsize']; ?>" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[content_fstyle]" id="smooth_slider_content_fstyle" >
<option value="bold" <?php if ($smooth_slider['content_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','smooth-slider'); ?></option>
<option value="bold italic" <?php if ($smooth_slider['content_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','smooth-slider'); ?></option>
<option value="italic" <?php if ($smooth_slider['content_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','smooth-slider'); ?></option>
<option value="normal" <?php if ($smooth_slider['content_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Pick content From','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[content_from]" id="smooth_slider_content_from" >
<option value="slider_content" <?php if ($smooth_slider['content_from'] == "slider_content"){ echo "selected";}?> ><?php _e('Slider Content Custom field','smooth-slider'); ?></option>
<option value="excerpt" <?php if ($smooth_slider['content_from'] == "excerpt"){ echo "selected";}?> ><?php _e('Post Excerpt','smooth-slider'); ?></option>
<option value="content" <?php if ($smooth_slider['content_from'] == "content"){ echo "selected";}?> ><?php _e('From Content','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum content size (in characters)','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[content_chars]" id="smooth_slider_content_chars" class="small-text" value="<?php echo $smooth_slider['content_chars']; ?>" />&nbsp;<?php _e('characters','smooth-slider'); ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Maximum content size (in words)','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[content_limit]" id="smooth_slider_content_limit" class="small-text" value="<?php echo $smooth_slider['content_limit']; ?>" />&nbsp;<?php _e('words (if specified will override the \'Maximum Content Size in Chracters\' setting above)','smooth-slider'); ?></td>
</tr>

</table>

<h2><?php _e('Miscellaneous','smooth-slider'); ?></h2> 

<table class="form-table">

<tr valign="top" <?php if($smooth_slider['ver']=='step') echo 'style="display:none;"';?>>
<th scope="row"><?php _e('Promote Smooth Slider','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[support]" >
<option value="1" <?php if ($smooth_slider['support'] == "1"){ echo "selected";}?> ><?php _e('Yes','smooth-slider'); ?></option>
<option value="0" <?php if ($smooth_slider['support'] == "0"){ echo "selected";}?> ><?php _e('No','smooth-slider'); ?></option>
</select>
<small><?php _e('Consider donating in case you select "No"','smooth-slider'); ?></small>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Retain these html tags','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[allowable_tags]" class="regular-text code" value="<?php echo $smooth_slider['allowable_tags']; ?>" />&nbsp;<?php _e('(read','smooth-slider'); ?> <a href="http://www.clickonf5.org/smooth-slider" title="<?php _e('how to retain html like line breaks and links in the Smooth Slider','smooth-slider'); ?>" target="_blank"><?php _e('Usage section of the plugin page','smooth-slider'); ?></a> <?php _e('to know more','smooth-slider'); ?>)</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Continue Reading Text','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[more]" class="regular-text code" value="<?php echo $smooth_slider['more']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Minimum User Level to add Post to the Slider','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[user_level]" >
<option value="manage_options" <?php if ($smooth_slider['user_level'] == "manage_options"){ echo "selected";}?> ><?php _e('Administrator','smooth-slider'); ?></option>
<option value="edit_others_posts" <?php if ($smooth_slider['user_level'] == "edit_others_posts"){ echo "selected";}?> ><?php _e('Editor and Admininstrator','smooth-slider'); ?></option>
<option value="publish_posts" <?php if ($smooth_slider['user_level'] == "publish_posts"){ echo "selected";}?> ><?php _e('Author, Editor and Admininstrator','smooth-slider'); ?></option>
<option value="edit_posts" <?php if ($smooth_slider['user_level'] == "edit_posts"){ echo "selected";}?> ><?php _e('Contributor, Author, Editor and Admininstrator','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Randomize Slides in Slider','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[rand]" type="checkbox" value="1" <?php checked('1', $smooth_slider['rand']); ?>  />&nbsp;<?php _e('check this if you want the slides added to appear in random order','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Text to display in the JavaScript disabled browser','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[noscript]" class="regular-text code" value="<?php echo $smooth_slider['noscript']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Add Shortcode Support','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[shortcode]" type="checkbox" value="1" <?php checked('1', $smooth_slider['shortcode']); ?>  />&nbsp;<?php _e('check this if you want to use Smooth Slider Shortcode i.e [smoothslider]','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Smooth Slider Styles to Use on Other than Post/Pages','smooth-slider'); ?> <small><?php _e('(i.e. for index.php,category.php,archive.php etc)','smooth-slider'); ?></small></th>
<td><select name="smooth_slider_options[stylesheet]" >
<?php 
$directory = SMOOTH_SLIDER_CSS_DIR;
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { ?>
      <option value="<?php echo $file;?>" <?php if ($smooth_slider['stylesheet'] == $file){ echo "selected";}?> ><?php echo $file;?></option>
 <?php  } }
    closedir($handle);
}
?>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Multiple Slider Feature','smooth-slider'); ?></th>
<td><label for="smooth_slider_multiple"> 
<input name="smooth_slider_options[multiple_sliders]" type="checkbox" id="smooth_slider_multiple" value="1" <?php checked("1", $smooth_slider['multiple_sliders']); ?> /> 
 <?php _e('Enable Multiple Slider Function on Edit Post/Page','smooth-slider'); ?></label></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Enable FOUC','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[fouc]" type="checkbox" value="1" <?php checked('1', $smooth_slider['fouc']); ?>  /><small><?php _e('(check this if you would not want to disable Flash of Unstyled Content in the slider when the page is loaded)','smooth-slider'); ?></small></td>
</tr>

<!--<tr valign="top">
<th scope="row"><?php _e('Version to use','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[ver]" >
<option value="j" <?php if ($smooth_slider['ver'] == "j"){ echo "selected";}?> ><?php _e('New','smooth-slider'); ?></option>
<option value="step" <?php if ($smooth_slider['ver'] == "step"){ echo "selected";}?> ><?php _e('Previous','smooth-slider'); ?></option>
</select>
</td>
</tr>
-->
</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div> <!--end of float left -->
</form>

                     <div style="margin:10px auto;clear:left;">
                        <a href="http://slidervilla.com/" title="Premium WordPress Slider Plugins" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/slidervilla-728.jpg');?>" alt="Premium WordPress Slider Plugins" /></a>
                     </div>


</div> <!--end of float wrap -->


<?php	
}
function register_mysettings() { // whitelist options
  register_setting( 'smooth-slider-group', 'smooth_slider_options' );
}
?>