<?php
/**
* Admin Settings
*
**/

//initiate menu
add_action('admin_menu', 'vsw_add_page');

// Add sub page to the Settings Menu
function vsw_add_page() {
add_options_page('Video Sidebar Widgets Settings', 'Video Sidebar Widgets','manage_options','video_sidebar_widget_settings', 'vsw_admin_page');
}

function vsw_admin_page(){
//define and initiate variables to prevent error in WordPress Debug Mode
$checked1 = $checked2 = $checked3 = $checked4 = '';
?>
    <div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Video Sidebar Widgets Settings</h2>
		
		<br/>

		<form action="options.php" method="post">
		<?php settings_fields('vsw_plugin_options'); ?>
		<?php $options = get_option('vsw_plugin_options');	?>
		
		<p><span class="description" style="font-size:14px">By default, all components of this plugin will be loaded. However you can use this setting to remove components not used. Please check to remove.</span></p>
		
		<div style="float:left;padding:5px;margin:10px 10px 10px 0px;text-align:center">
		<img style='border:1px solid #eee;padding:5px;' src="<?php echo WP_PLUGIN_URL.'/video-sidebar-widgets/source_image/vsw.png'?>"/>
		<br/>
		<label>Video Sidebar Widget</label>
        <?php
	       if(isset($options['hide_vsw'])) { $checked1 = ' checked="checked" '; }
	      echo "<input ".$checked1." id='vsw' name='vsw_plugin_options[hide_vsw]' type='checkbox' value='hide'/>";		
		?>
		</div>
		
	     <div style="float:left;padding:5px;margin:10px 10px 10px 0px;text-align:center">
		<img style='border:1px solid #eee;padding:5px;' src="<?php echo WP_PLUGIN_URL.'/video-sidebar-widgets/source_image/rvw.png'?>"/>
		<br/>
		<label>Random Video Widget</label>
        <?php
	       if(isset($options['hide_rvw'])) { $checked2 = ' checked="checked" '; }
	      echo "<input ".$checked2." id='rvw' name='vsw_plugin_options[hide_rvw]' type='checkbox' value='hide'/>";		
		?>
		</div>	
		
	    <div style="float:left;padding:5px;margin:10px 10px 10px 0px;text-align:center">
		<img style='border:1px solid #eee;padding:5px;' src="<?php echo WP_PLUGIN_URL.'/video-sidebar-widgets/source_image/tinybutton.png'?>"/>
		<br/>
		<label>TinyMCE Editor Button</label>
        <?php
	       if(isset($options['hide_tmb'])) { $checked3 = ' checked="checked" '; }
	      echo "<input ".$checked3." id='tmb' name='vsw_plugin_options[hide_tmb]' type='checkbox' value='hide'/>";		
		?>
		</div>
		
		
		<div style="float:left;padding:5px;margin:10px 10px 10px 0px;text-align:center">
		<img style='border:1px solid #eee;padding:5px;' src="<?php echo WP_PLUGIN_URL.'/video-sidebar-widgets/source_image/pmvw.png'?>"/>
		<br/>
		<label>Post Meta Video Widget</label>
        <?php
	       if(isset($options['hide_pmvw'])) { $checked4 = ' checked="checked" '; }
	      echo "<input ".$checked4." id='tmb' name='vsw_plugin_options[hide_pmvw]' type='checkbox' value='hide'/>";		
		?>
		</div>
		
		
		<br/>
		
		<div style="float:left;padding:5px;margin:10px 10px 10px 0px;clear:both">
		<p>
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>	
		<p class="description">If you ever need any modifications to the plugin, Please post it on <a href="http://wpquestions.com/affiliates/register/name/denzelchia" target="_blank">WP Questions.</a> Thanks!</p>	
			</div>
		</form>
	</div>
<?php
}

add_action('admin_init', 'vsw_register_options' );
// Register our settings. Add the settings section, and settings fields
function vsw_register_options(){
	register_setting('vsw_plugin_options', 'vsw_plugin_options');
}
?>