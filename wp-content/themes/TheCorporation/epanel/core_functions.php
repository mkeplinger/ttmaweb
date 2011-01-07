<?php


/********* ePanel v.1.45 ************/


/* Adds jquery 1.3.2 for wordpress < 2.8 */
function jquery_script(){
	if ((substr($GLOBALS['wp_version'],0,3)) >= 2.8) { wp_enqueue_script('jquery'); }
	else { wp_deregister_script('jquery');
	   wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', false, '1.3.2'); }
};
add_action('wp_print_scripts', 'jquery_script',8);
/* --------------------------------------------- */

/* Admin scripts + ajax jquery code */
function admin_js(){
	if ( isset($_GET['page']) ) { 
		if ( $_GET['page'] == basename(__FILE__) ) {
	?>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/epanel/js/jquery.form.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/epanel/js/checkbox.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/epanel/js/functions-init.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/epanel/js/colorpicker.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/epanel/js/eye.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/epanel/js/layout.js"></script>

			<script type="text/javascript">
			/* <![CDATA[ */
				var clearpath = "<?php bloginfo('stylesheet_directory') ?>/epanel/images/empty.png";
			/* ]]> */
			</script>
			<script type="text/javascript">
			/* <![CDATA[ */
				jQuery(document).ready(function(){
					var $save_message = jQuery("#epanel-ajax-saving");
					
					jQuery('input#epanel-save').click(function($){
						var options_fromform = jQuery('#main_options_form').formSerialize();
						var save_button=jQuery(this);
						jQuery.ajax({
						   type: "POST",
						   url: "themes.php?page=<?php echo(basename(__FILE__));?>",
						   data: options_fromform,
						   success: function(response){
								$save_message.children("img").css("display","none");
								$save_message.children("span").css("margin","0px").html('Options Saved.');
								
								save_button.blur();
								
								setTimeout(function(){
									$save_message.fadeOut("slow");
								},500);
						   }
						});

						return false;
					});
					
					$save_message.ajaxStart(function(){
						jQuery(this).children("img").css("display","block");
						jQuery(this).children("span").css("margin","6px 0px 0px 30px").html('Saving...');
						jQuery(this).fadeIn('fast');
					});
					
				});
			/* ]]> */
			</script>
		<?php }
	}
}
/* --------------------------------------------- */

/* Save/Reset actions | Adds theme options to WP-Admin menu */
function mytheme_add_admin() {

    global $themename, $shortname, $options;
	$epanel = basename(__FILE__);
	
	if ( isset($_GET['page']) && $_GET['page'] == $epanel ) {
		wp_enqueue_script('myscript', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js');
		
		if ( isset($_REQUEST['action']) ) {
		
			if ( 'save' == $_REQUEST['action'] ) {
				foreach ($options as $value) {
					if( isset( $value['id'] ) ) { 
						if( isset( $_REQUEST[ $value['id'] ] ) ) {
							if ($value['type'] == 'textarea' || $value['type'] == 'text') update_option( $value['id'], stripslashes($_REQUEST[$value['id']]) );
							elseif ($value['type'] == 'select') update_option( $value['id'], htmlspecialchars($_POST[$value['id']]) );
							else update_option( $value['id'], $_POST[$value['id']] );
						}
						else {
							if ($value['type'] == 'checkbox' || $value['type'] == 'checkbox2') update_option( $value['id'] , 'false' );
							elseif ($value['type'] == 'different_checkboxes') {
								update_option( $value['id'] , $_POST[$value['id']] );
							}
							else delete_option( $value['id'] );
						}
					}
				}
				header("Location: themes.php?page=$epanel&saved=true");
				die;
				
			} else if( 'reset' == $_REQUEST['action'] ) {

				foreach ($options as $value) {
					if (isset($value['id'])) {
						delete_option( $value['id'] );
						if (isset($value['std'])) $$value['id'] = $value['std'];
					};
				}
				
				header("Location: themes.php?page=$epanel&reset=true");
				die;
				
			}
		};
	}
	
    add_theme_page($themename." Options", $themename." Theme Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}
/* --------------------------------------------- */

/* Displays ePanel */
function mytheme_admin() {

    global $themename, $shortname, $options;
	
	if (isset($_REQUEST['saved'])) {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
	};
	if (isset($_REQUEST['reset'])) {
		if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
	};
    
?>

<div id="wrapper">
  <div id="panel-wrap">
	<form method="post" id="main_options_form" enctype="multipart/form-data">
		<div id="epanel-wrapper">
			<div id="epanel">
				<div id="epanel-content-wrap">
					<div id="epanel-content">
						<img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/logo.png" alt="ePanel" class="pngfix" id="epanel-logo" />
						<?php global $epanelMainTabs; ?>
						<ul id="epanel-mainmenu">
							<?php if(in_array('general',$epanelMainTabs)) { ?>
								<li><a href="#wrap-general"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/general-icon.png" class="pngfix" alt="" />General Settings</a></li>
							<?php }; ?>
							<?php if(in_array('navigation',$epanelMainTabs)) { ?>
								<li><a href="#wrap-navigation"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/navigation-icon.png" class="pngfix" alt="" />Navigation</a></li>
							<?php }; ?>
							<?php if(in_array('layout',$epanelMainTabs)) { ?>
								<li><a href="#wrap-layout"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/layout-icon.png" class="pngfix" alt="" />Layout Settings</a></li>
							<?php }; ?>
							<?php if(in_array('ad',$epanelMainTabs)) { ?>
								<li><a href="#wrap-advertisements"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/ad-icon.png" class="pngfix" alt="" />Ad Management</a></li>
							<?php }; ?>
							<?php if(in_array('colorization',$epanelMainTabs)) { ?>
								<li><a href="#wrap-colorization"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/colorization-icon.png" class="pngfix" alt="" />Colorization</a></li>
							<?php }; ?>
							<?php if(in_array('seo',$epanelMainTabs)) { ?>
								<li><a href="#wrap-seo"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/seo-icon.png" class="pngfix" alt="" />SEO</a></li>
							<?php }; ?>
							<?php if(in_array('integration',$epanelMainTabs)) { ?>
								<li><a href="#wrap-integration"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/integration-icon.png" class="pngfix" alt="" />Integration</a></li>
							<?php }; ?>
							<?php if(in_array('support',$epanelMainTabs)) { ?>
								<li><a href="#wrap-support"><img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/support-icon.png" class="pngfix" alt="" />Support Docs</a></li>
							<?php }; ?>
						</ul><!-- end epanel mainmenu -->

<?php foreach ($options as $value) {
if (($value['type'] == "text") || ($value['type'] == "textlimit") || ($value['type'] == "textarea") || ($value['type'] == "select") || ($value['type'] == "checkboxes") || ($value['type'] == "different_checkboxes") || ($value['type'] == "colorpicker") || ($value['type'] == "textcolorpopup") || ($value['type'] == "upload") || ($value['type'] == "dummy_patcher") ) { ?>
			<div class="epanel-box">
			  <div class="box-title">
				<h3><?php echo $value['name']; ?></h3>
				<img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/help-image.png" alt="description" class="box-description" />
				<div class="box-descr">
					<p><?php echo $value['desc']; ?></p>
				</div> <!-- end box-desc-content div -->
		      </div> <!-- end div box-title -->
				<div class="box-content">
		
		<?php if ($value['type'] == "text") { ?>
		
			<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
			
		<?php } elseif ($value['type'] == "textlimit") { ?>
		
			<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" maxlength="<?php echo $value['max']; ?>" size="<?php echo $value['max']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
			
		<?php } elseif ($value['type'] == "colorpicker") { ?>
		
			<div id="colorpickerHolder"></div>
			
		<?php } elseif ($value['type'] == "textcolorpopup") { ?>
		
			<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" class="colorpopup" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
			
		<?php } elseif ($value['type'] == "textarea") { ?>
		
			<textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo stripslashes($value['std']); } ?></textarea>
			
		<?php } elseif ($value['type'] == "dummy_patcher") { ?>
		
			<a href="#" id="epanel_patch_xml" class="button">Patch the xml file</a>
			<p id="epanel_patch_result"><?php global $patch_message; echo($patch_message); ?></p>
			
		<?php } elseif ($value['type'] == "upload") { ?>
		
			<input id="<?php echo $value['id']; ?>" type="file" name="<?php echo $value['id']; ?>" />
			<input class="button etupload" type="submit" value="Upload"/>
			
		<?php } elseif ($value['type'] == "select") { ?>
		
			<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
            <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( htmlspecialchars(get_option( $value['id'] )) == htmlspecialchars($option)) { echo ' selected="selected"'; } elseif (isset($value['std']) && $option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
            <?php } ?>
            </select>
			
		<?php } elseif ($value['type'] == "checkboxes") {
		
			if (empty($value['options'])) {
				echo("You don't have pages");
			} else {
				$i = 1;
				$className = 'inputs';
				if ( isset($value['excludeDefault']) && $value['excludeDefault'] == 'true' ) $className = $className . ' different';
				
				foreach ($value['options'] as $option) {
					$checked = "";
					
					if (get_option( $value['id'])) {
						if (in_array($option, get_option( $value['id'] ))) $checked = "checked=\"checked\"";
					} ?>
					
					<p class="<?php echo $className; ?><?php if ($i%3 == 0) echo(' last'); ?>"><input type="checkbox" class="usual-checkbox" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id'],"-",$option; ?>" value="<?php echo ($option); ?>" <?php echo $checked; ?> />
					<label for="<?php echo $value['id'],"-",$option; ?>"><?php if ($value['usefor']=='pages') echo get_pagename($option); else echo get_categname($option); ?></label>
					</p>
					<?php if ($i%3 == 0) echo('<br class="clearfix"/>');?>
			  <?php $i++; }
			}; ?>
				<br class="clearfix"/>
			
		<?php } elseif ($value['type'] == "different_checkboxes") {
		
			foreach ($value['options'] as $option) {
				$checked = "";
				if (get_option( $value['id'])) {
					if (in_array($option, get_option( $value['id'] ))) $checked = "checked=\"checked\"";
				} ?>
				<p class="<?php echo ("postinfo-".$option) ?>"><input type="checkbox" class="usual-checkbox" name="<?php echo $value['id']; ?>[]" id="<?php echo ($value['id']."-".$option); ?>" value="<?php echo ($option); ?>" <?php echo $checked; ?> />
				</p>
		  <?php } ?>
			<br class="clearfix"/>
			
		<?php } ?>
		
				</div> <!-- end box-content div -->
			</div> <!-- end epanel-box div -->
			
<?php } elseif (($value['type'] == "checkbox") || ($value['type'] == "checkbox2")) { ?>

			<div class="epanel-box <?php if ($value['type'] == "checkbox") {echo('epanel-box-small-1');} else {echo('epanel-box-small-2');} ?>">
			  <div class="box-title"><h3><?php echo $value['name']; ?></h3>
				<img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/help-image.png" alt="description" class="box-description" />
				<div class="box-descr">
					<p><?php echo $value['desc']; ?></p>
				</div> <!-- end box-desc-content div -->
			  </div> <!-- end div box-title -->
				<div class="box-content">
	<?php $checked = '';
	if((get_option($value['id'])) <> '') {
		if((get_option($value['id'])) == 'on') { $checked = 'checked="checked"'; }
		else { $checked = ''; }
	}
	elseif ($value['std'] == 'on') { $checked = 'checked="checked"'; }
?>
    <input type="checkbox" class="checkbox" name="<?php echo($value['id']);?>" id="<?php echo($value['id']);?>" <?php echo($checked); ?> />
				</div> <!-- end box-content div -->
			</div> <!-- end epanel-box-small div -->
			
	<?php } elseif ($value['type'] == "support") { ?>
				
				<div class="inner-content">
					<?php include(TEMPLATEPATH . "/includes/functions/".$value['name'].".php"); ?>
				</div>
				
	<?php } elseif (($value['type'] == "contenttab-wrapstart") || ($value['type'] == "subcontent-start")) { ?>

				<div id="<?php echo $value['name']; ?>" class="<?php if ($value['type'] == "contenttab-wrapstart") {echo('content-div');} else {echo('tab-content');} ?>">
				
	<?php } elseif (($value['type'] == "contenttab-wrapend") || ($value['type'] == "subcontent-end")) { ?>

				</div> <!-- end <?php echo $value['name']; ?> div -->
				
	<?php } elseif ($value['type'] == "subnavtab-start") { ?>

				<ul class="idTabs">
				
	<?php } elseif ($value['type'] == "subnavtab-end") { ?>

				</ul>
				
	<?php } elseif ($value['type'] == "subnav-tab") { ?>

				<li><a href="#<?php echo $value['name']; ?>"><span class="pngfix"><?php echo $value['desc']; ?></span></a></li>
				
	<?php } elseif ($value['type'] == "clearfix") { ?>
				
				<div class="clearfix"></div>
				
	<?php } ?>


<?php } //end foreach ($options as $value) ?>
		
					</div> <!-- end epanel-content div -->
				</div> <!-- end epanel-content-wrap div -->
			</div> <!-- end epanel div -->
		</div> <!-- end epanel-wrapper div -->
		
		<div id="epanel-bottom">
        			<input name="save" type="submit" value="Save changes" id="epanel-save" />
			<input type="hidden" name="action" value="save" />
		
        <img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/defaults.png" class="defaults-button" alt="no" />
               
        </div><!-- end epanel-bottom div -->
		
    </form>
     
	<div style="clear: both;"></div>
        <div style="position: relative;">
			<div class="defaults-hover">
				This will return all of the settings throughout the options page to their default values. <strong>Are you sure you want to do this?</strong>
				<div class="clearfix"></div>
				<form method="post">
					<input name="reset" type="submit" value="Reset" id="epanel-reset" />
					<input type="hidden" name="action" value="reset" />
				</form>
				<img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/no.gif" class="no" alt="no" />
			</div> 
        </div>
        
	   </div> <!-- end panel-wrap div -->
	</div> <!-- end wrapper div -->
	
	<div id="epanel-ajax-saving">
		<img src="<?php bloginfo('stylesheet_directory') ?>/epanel/images/saver.gif" alt="loading" id="loading" />
		<span>Saving...</span>
	</div>
	
<?php
}
/* --------------------------------------------- */

/* Adds additional ePanel css */
function css_admin() { ?> 
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory') ?>/epanel/css/panel.css" type="text/css" />
	<style type="text/css">
	.lightboxclose { background: url("<?php bloginfo('stylesheet_directory') ?>/epanel/images/description-close.png") no-repeat; width: 19px; height: 20px; }
	</style>
	<!--[if IE 7]>
	<style type="text/css">
		#epanel-save, #epanel-reset { font-size: 0px; display:block; line-height: 0px; bottom: 18px;}
		.box-desc { width: 414px; }
		.box-desc-content { width: 340px; }
		.box-desc-bottom { height: 26px; }
		#epanel-content .epanel-box input, #epanel-content .epanel-box select, .epanel-box textarea {  width: 395px; }
		#epanel-content .epanel-box select { width:434px !important;}
		#epanel-content .epanel-box .box-content { padding: 8px 17px 15px 16px; }
	</style>
	<![endif]-->  
    	<!--[if IE 8]>
        <style type="text/css">
        		#epanel-save, #epanel-reset { font-size: 0px; display:block; line-height: 0px; bottom: 18px;}
</style>
        <![endif]-->  
<?php }
/* --------------------------------------------- */


global $options, $value, $shortname;
foreach ($options as $value) {
	if (isset($value['id'])) {
		if ( get_option( $value['id'] ) === FALSE) {
			if (array_key_exists('std', $value)) { 
				update_option( $value['id'], $value['std'] );
				$$value['id'] = $value['std'];
			}
		} else {
			$$value['id'] = get_option( $value['id'] ); }
	};
}


add_action('admin_menu', 'mytheme_add_admin');
add_action('admin_head', 'css_admin');
add_action('admin_head', 'admin_js');
?>