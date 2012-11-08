<?php
/**
* Tinymce Editor Button
*
**/

//add tinymce button to editor
function dd_vsw_addbuttons() {
   //Stop if current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "dd_vsw_tinymce_plugin"); // add plugin url to plugin url array.
     add_filter('mce_buttons', 'dd_vsw_button'); //add button to first row.
   }
}

// init process for button control
add_action('init', 'dd_vsw_addbuttons');

//push button 
function dd_vsw_button($tiny_buttons) {
   array_push($tiny_buttons, "vsw");
   return $tiny_buttons;
}

// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function dd_vsw_tinymce_plugin($plugin_array) {
  
    //check if defined WP_PLUGINS_URL  	
   	if (defined('WP_PLUGINS_URL'))  {		
		
    $url_to_plugin =  WP_PLUGINS_URL."/video-sidebar-widgets/vsw_tinymce/editor_plugin.js";
	
	}else{
    //if not assume it is default location.
	$url_to_plugin = "../../../wp-content/plugins/video-sidebar-widgets/vsw_tinymce/editor_plugin.js";
	
	}
	
	//check option settings whether to load in TinyMCE button
	$options = get_option('vsw_plugin_options');
	if(empty($options['hide_tmb'])){
	$plugin_array['vsw'] = $url_to_plugin; //add to tinymce plugins array
    return $plugin_array;
    
	}else{
	return $plugin_array; // no addition to tinymce plugins array	
	}   

}
?>