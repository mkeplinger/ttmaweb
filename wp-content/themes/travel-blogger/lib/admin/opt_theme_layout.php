<?php
/**
 * Admin template for theme layout
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

function exp_add_theme_menu() {
	$page = add_theme_page(
	   __('Theme Layout & Skins', 'travelblogger'),
	   __('Theme Layout & Skins', 'travelblogger'),
	   'edit_theme_options',
	   'exp-theme-settings',
	   'exp_theme_settings'
	);
	add_action("admin_print_styles-$page", 'exp_load_theme_styles');
	add_action("admin_print_scripts-$page", 'exp_load_theme_scripts');
	add_action("load-$page", 'exp_theme_layout_option_updates');
}
add_action('admin_menu', 'exp_add_theme_menu');

function exp_theme_layout_option_updates() {
	// See if the user has posted us some information
	if (!empty($_POST['update-theme-layout'])) {
		if ( check_admin_referer('exp_theme_settings-update') ) {
			$exp_theme_layout = get_option('exp_theme_layout');
			if (isset($_POST['theme_grid'])) {
				$exp_theme_layout['theme_grid'] = esc_attr($_POST['theme_grid']);
			}
			if (isset($_POST['theme_color'])) {
				global ${'exp_body_bg_'.$_POST['theme_color']},${'exp_theme_color_'.$_POST['theme_color']};
				$exp_theme_layout['theme_color'] = esc_attr($_POST['theme_color']);
				set_theme_mod('background_image', get_stylesheet_directory_uri().'/images/backgrounds/background-'.$_POST['theme_color'].'.jpg' );
				set_theme_mod('background_image_thumb', get_stylesheet_directory_uri().'/images/backgrounds/background-'.$_POST['theme_color'].'-thumbnail.jpg' );
				if(file_exists(TEMPLATEPATH .'/images/backgrounds/footer-'.$_POST['theme_color'].'.jpg')) {
					set_theme_mod('footer_image', get_stylesheet_directory_uri().'/images/backgrounds/footer-'.$_POST['theme_color'].'.jpg' );
				} else {
					set_theme_mod('footer_image','');
				}
				set_theme_mod( 'header_image', '' );
				set_theme_mod('background_repeat', 'repeat-x');
				set_theme_mod('background_attachment', 'fixed');
				set_theme_mod('background_position_x', 'center');
				set_theme_mod('background_color', ${'exp_body_bg_'.$_POST['theme_color']});
				$theme = ${'exp_theme_color_'.$_POST['theme_color']};
				update_option('exp_theme_colors', $theme);
				delete_option('exp_theme_font');
				delete_option('exp_custom_css');
			}
			if (isset($_POST['theme_loop_content'])) {
				if ( $_POST['theme_loop_content'] == 1 ) {
					$exp_theme_layout['theme_loop_content']='full';
				} elseif ( $_POST['theme_loop_content'] == 0  ) {
					$exp_theme_layout['theme_loop_content']='';
				}
			}
			update_option('exp_theme_layout', $exp_theme_layout);
			wp_redirect(admin_url('themes.php?page=exp-theme-settings&updated=true'));
		}
	} elseif (!empty($_GET['dontshow'])) {
		if ( check_admin_referer('exp_dont_show_again-optout') ) {
			update_option('exp_dont_bother','dontshow');
			wp_redirect(admin_url('themes.php?page=exp-theme-settings&updated=true'));
		}
	} elseif (!empty($_GET['activatelink'])) {
		if ( check_admin_referer('exp_activate_link-optin') ) {
			$footerlinks = get_option('exp_show_footer_links');
			$footerlinks['credit']='show';
			$footerlinks['add_credit']='show';
			update_option('exp_show_footer_links', $footerlinks );
			delete_option('exp_dont_bother');
			wp_redirect(admin_url('themes.php?page=exp-theme-settings&updated=true'));
		}
	} elseif (!empty($_GET['exp_updategmaps'])) {
		if ( check_admin_referer('exp_updategmaps-optin') ) {
			
			exp_get_geo_latlng();
			
			wp_redirect(admin_url('themes.php?page=exp-theme-settings&updated=true'));
		}
	}
	
}

function exp_theme_settings() {
	global $theme_data;
    //must check that the user has the required capability 
    if (!current_user_can('edit_theme_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.' , 'travelblogger') );
    }

    // Read in existing theme layout options from database
    $opt_layout = get_option('exp_theme_layout');

	// Set types of layouts
	$boxes = array('col-3'=>'yui-t2','col-2-left'=>'yui-t3','col-2-right'=>'yui-t6');

	// Set preset theme colors
	$colors = array('default','camping');
    
	$current='';
	
	$adminurl = admin_url('themes.php?page=exp-theme-settings');
?>
<?php if ( !empty($_GET['updated']) ) { ?>
<div id="message" class="updated">
	<p><?php printf( __( 'Settings updated. <a href="%s" target="_blank">Visit your site</a> to see how it looks.' , 'travelblogger' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>

<div class="wrap appearance_page_custom-header">
<div id="icon-themes" class="icon32"><br/></div>

		<h2>Theme Layout</h2>
		<h3>Theme Grid Layout</h3>
		<form action="" method="post" id="exp-theme-layout-form">
			<table class="form-table">
			<tbody>
				<tr valign="top">
				<th scope="row">Theme Grid</th>
					<td>
						<?php foreach ($boxes as $key => $value) { 
							$checked = ($opt_layout['theme_grid'] == $value) ? 'checked ' : '';
						?>
							<div class="layout-box">
								<label for="layout-settings-<?php echo $key ?>" class="layout <?php echo $checked ?> <?php echo $key ?>"></label>
								<input class="radio-layout" type="radio" name="theme_grid" id="layout-settings-<?php echo $key ?>" <?php echo $checked ?> value="<?php echo $value ?>" />
							</div>
						<?php } ?>
					</td>
				</tr>
			</tbody>
			</table>		
		<h3>Default Theme Skins</h3>
			<table class="form-table">
			<tbody>
				<tr valign="top">
				<th scope="row">&nbsp;</th>
					<td>
						<?php foreach ($colors as $color) { 
							$checked = ($opt_layout['theme_color'] == $color) ? 'checked ' : '';
						?>
							<div class="color-box">
								<b><?php echo $color ?></b>
								<label for="color-settings-<?php echo $color ?>" class="colors <?php echo $checked ?> <?php echo $color ?>"></label>
								<input class="radio-colors" type="radio" name="theme_color" id="color-settings-<?php echo $color ?>" <?php echo $checked ?> value="<?php echo $color ?>" />
							</div>
						<?php }?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">&nbsp;</th>
					<td><p style="margin-top:-10px;">Select a default travel skin to change the look and feel of the entire theme. WARNING: This will override any custom image, font or color settings you have made.</p></td>
				</tr>
			</tbody>
			</table>
			<h3>Blog Roll Content Setting</h3>
				<table class="form-table">
				<tbody>
					<tr valign="top">
					<th scope="row">Display in Full HTML<br/> or Summary Text</th>
					<td>
						<p>
						<label><input type="radio" value="1" name="theme_loop_content" id="fulltext"<?php checked( ( !empty( $opt_layout['theme_loop_content'] ) )  ? true : false ); ?> /> Full Text</label>
						<label><input type="radio" value="0" name="theme_loop_content" id="summarytext"<?php checked( ( empty( $opt_layout['theme_loop_content'] ) ) ? true : false ); ?> /> Summary</label>
						</p>
						<p>This option allows you to set whether to display the full text of your posts in the blog roll, or simply the excerpts.</p>
					</td>
				</tbody>
				</table>
			<table>
				<tr valign="top">
					<td>
						<p class="submit">
							<input type="submit" name="update-theme-layout" class="button-primary" value="<?php echo esc_attr('Save Changes') ?>" />
						</p>
					</td>
				</tr>
			</table>
			<?php wp_nonce_field('exp_theme_settings-update'); ?>
		</form>

		<h2>Travel Blogger Help &amp; Support</h2>
			<table class="form-table">
			<tbody>
				<tr valign="top">
					<td>
						<p>Please visit our main Travel Blogger support website for instructions on theme installation &amp; setup, configuration and complete information about use of the theme features: <a href="<?php echo $theme_data['AuthorURI']; ?>" target="_blank">http://www.freetravelwebsitetemplates.com/</a></p>

						<p>This site is where more frequent updates and bug fixes to the theme may be found. Please visit every once in a while to download the latest version.</p>
					</td>
				</tr>
				<tr valign="top">
					<td>
						<p><a href="<?php echo wp_nonce_url($adminurl.'&exp_updategmaps=1','exp_updategmaps-optin') ?>">Click here</a> to update your current locations. This fixes an issue with Google Maps.</p>
					</td>
				</tr>
			</tbody>
			</table>

</div><!-- #wrapper -->
<?php
}
