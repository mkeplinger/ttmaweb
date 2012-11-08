<?php
/**
 * Admin template for theme options
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

function exp_add_colors_menu() {
	$page = add_theme_page(
	   __('Custom Colors & Fonts', 'travelblogger'),
	   __('Custom Colors & Fonts', 'travelblogger'),
	   'edit_theme_options',
	   'exp-color-settings',
	   'exp_color_settings'
	);
	add_action("admin_print_styles-$page", 'exp_load_theme_styles');
	add_action("admin_print_scripts-$page", 'exp_load_theme_scripts');
	add_action("load-$page", 'exp_color_option_updates');
}
add_action('admin_menu', 'exp_add_colors_menu');

function exp_color_option_updates() {
	global $exp_theme_color_camping,$exp_theme_color_default,$exp_body_bg_default,$exp_body_bg_camping;
	// See if the user has posted us some information
	if (!empty($_GET['reset'])) {	
		if ( check_admin_referer('exp_theme_settings-reset') ) {
			$exp_theme_layout = get_option('exp_theme_layout');
			$exp_theme_layout['theme_color']='default';
			delete_option('exp_theme_colors');
			delete_option('exp_theme_font');
			delete_option('exp_custom_css');
			set_theme_mod('background_image', get_stylesheet_directory_uri().'/images/backgrounds/background-default.jpg' );
			set_theme_mod('background_image_thumb', get_stylesheet_directory_uri().'/images/backgrounds/background-default-thumbnail.jpg' );
			set_theme_mod('footer_image', get_stylesheet_directory_uri().'/images/backgrounds/footer-default.jpg' );
			set_theme_mod('background_repeat', 'repeat-x');
			set_theme_mod('background_attachment', 'fixed');
			set_theme_mod('background_position_x', 'center');
			set_theme_mod('background_color', $exp_body_bg_default);
			update_option('exp_theme_layout', $exp_theme_layout);
			wp_redirect(admin_url('themes.php?page=exp-color-settings&reseted=true'));
		}
	} elseif (!empty($_POST['update-theme-colors'])) {
		if ( check_admin_referer('exp_theme_settings-update') ) {
			$exp_theme_layout = get_option('exp_theme_layout');
			$exp_theme_layout['theme_color'] = 'custom';
			update_option('exp_theme_layout', $exp_theme_layout);
			if (isset($_POST['exp_text_links'])) {
				$exp_theme_colors['exp_text_links']['value'] = esc_attr($_POST['exp_text_links']['value']);
				$exp_theme_colors['exp_text_links']['css'] = 'a:link, a:visited, a:hover {color:#';
			}
			if (isset($_POST['exp_body_text'])) {
				$exp_theme_colors['exp_body_text']['value'] = esc_attr($_POST['exp_body_text']['value']);
				$exp_theme_colors['exp_body_text']['css'] = 'body, input, textarea {color:#';
			}
			if (isset($_POST['exp_top_menu']['value'])) {
				$exp_theme_colors['exp_top_menu']['value'] = esc_attr($_POST['exp_top_menu']['value']);
				$exp_theme_colors['exp_top_menu']['css'] = '#nav,#nav ul ul a {background-color:#';
				$exp_theme_colors['exp_author_photo']['value'] = esc_attr($_POST['exp_top_menu']['value']);
				$exp_theme_colors['exp_author_photo']['css'] = '.author_photo {border-color:#';
				$exp_theme_colors['exp_feature_box']['value'] = esc_attr($_POST['exp_top_menu']['value']);
				$exp_theme_colors['exp_feature_box']['css'] = '.feature_ft {background-color:#';
				$exp_theme_colors['exp_archive_meta']['value'] = esc_attr($_POST['exp_top_menu']['value']);
				$exp_theme_colors['exp_archive_meta']['css'] = '.archive-meta {background:#';
			}
			if (isset($_POST['exp_top_menu_hover'])) {
				$exp_theme_colors['exp_top_menu_hover']['value'] = esc_attr($_POST['exp_top_menu_hover']['value']);
				$exp_theme_colors['exp_top_menu_hover']['css'] = '#nav li:hover > a,#nav ul ul :hover > a {color:#';
			}
			if (isset($_POST['exp_top_menu_selected'])) {
				$exp_theme_colors['exp_top_menu_selected']['value'] = esc_attr($_POST['exp_top_menu_selected']['value']);
				$exp_theme_colors['exp_top_menu_selected']['css'] = '#nav ul li.current_page_item > a, #nav ul li.current-menu-ancestor > a, #nav ul li.current-menu-item > a, #nav ul li.current-menu-parent > a {color:#';
			}
			if (isset($_POST['exp_top_menu_unselected'])) {
				$exp_theme_colors['exp_top_menu_unselected']['value'] = esc_attr($_POST['exp_top_menu_unselected']['value']);
				$exp_theme_colors['exp_top_menu_unselected']['css'] = '#nav a {color:#';
			}
			if (isset($_POST['exp_author_name'])) {
				$exp_theme_colors['exp_author_name']['value'] = esc_attr($_POST['exp_author_name']['value']);
				$exp_theme_colors['exp_author_name']['css'] = '.logo a,.logo h1.site-title a {color:#';
			}
			if (isset($_POST['exp_author_description'])) {
				$exp_theme_colors['exp_author_description']['value'] = esc_attr($_POST['exp_author_description']['value']);
				$exp_theme_colors['exp_author_description']['css'] = '.site-description {color:#';
			}
			if (isset($_POST['exp_page_title_h1'])) {
				$exp_theme_colors['exp_page_title_h1']['value'] = esc_attr($_POST['exp_page_title_h1']['value']);
				$exp_theme_colors['exp_page_title_h1']['css'] = 'h1.entry-title,.feature_panel h1 a {color:#';
			}
			if (isset($_POST['exp_category_title_h1'])) {
				$exp_theme_colors['exp_category_title_h1']['value'] = esc_attr($_POST['exp_category_title_h1']['value']);
				$exp_theme_colors['exp_category_title_h1']['css'] = '.archive-meta h1.page-title {color:#';
			}
			if (isset($_POST['exp_category_title_h1_bg'])) {
				$exp_theme_colors['exp_category_title_h1_bg']['value'] = esc_attr($_POST['exp_category_title_h1_bg']['value']);
				$exp_theme_colors['exp_category_title_h1_bg']['css'] = '.archive-meta {background-color:#';
			}
			if (isset($_POST['exp_category_text'])) {
				$exp_theme_colors['exp_category_text']['value'] = esc_attr($_POST['exp_category_text']['value']);
				$exp_theme_colors['exp_category_text']['css'] = '.archive-meta p {color:#';
			}
			if (isset($_POST['exp_widget_text'])) {
				$exp_theme_colors['exp_widget_text']['value'] = esc_attr($_POST['exp_widget_text']['value']);
				$exp_theme_colors['exp_widget_text']['css'] = '.widget-area h3 {color:#';
			}
			if (isset($_POST['exp_widget_bg'])) {
				$exp_theme_colors['exp_widget_bg']['value'] = esc_attr($_POST['exp_widget_bg']['value']);
				$exp_theme_colors['exp_widget_bg']['css'] = '.widget-area h3 {background-color:#';
			}
			if (isset($_POST['exp_page_title_h2'])) {
				$exp_theme_colors['exp_page_title_h2']['value'] = esc_attr($_POST['exp_page_title_h2']['value']);
				$exp_theme_colors['exp_page_title_h2']['css'] = 'h2 a:link, h2 a:visited,.widget-area h2 a:link,.widget-area h2 a:visited {color:#';
			}
			if (isset($_POST['exp_content_titles'])) {
				$exp_theme_colors['exp_content_titles']['value'] = esc_attr($_POST['exp_content_titles']['value']);
				$exp_theme_colors['exp_content_titles']['css'] = '.entry-content h1,.entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6 {color:#';
			}
			if (isset($_POST['exp_footer_text'])) {
				$exp_theme_colors['exp_footer_text']['value'] = esc_attr($_POST['exp_footer_text']['value']);
				$exp_theme_colors['exp_footer_text']['css'] = '#site-disclaimer, #site-disclaimer a {color:#';
				$exp_theme_colors['exp_footer_copy']['value'] = esc_attr($_POST['exp_footer_text']['value']);
				$exp_theme_colors['exp_footer_copy']['css'] = '.footer-copy,.footer-copy a,.footer-copy {color:#';
			}
			if (isset($_POST['exp_footer_menu_hover'])) {
				$exp_theme_colors['exp_footer_menu_hover']['value'] = esc_attr($_POST['exp_footer_menu_hover']['value']);
				$exp_theme_colors['exp_footer_menu_hover']['css'] = '#site-info li:hover > a,#site-info ul ul :hover > a {color:#';
			}
			if (isset($_POST['exp_footer_menu_selected'])) {
				$exp_theme_colors['exp_footer_menu_selected']['value'] = esc_attr($_POST['exp_footer_menu_selected']['value']);
				$exp_theme_colors['exp_footer_menu_selected']['css'] = '#site-info ul li.current_page_item > a, #site-info ul li.current-menu-ancestor > a, #site-info ul li.current-menu-item > a, #site-info ul li.current-menu-parent > a {color:#';
			}
			if (isset($_POST['exp_footer_menu_unselected'])) {
				$exp_theme_colors['exp_footer_menu_unselected']['value'] = esc_attr($_POST['exp_footer_menu_unselected']['value']);
				$exp_theme_colors['exp_footer_menu_unselected']['css'] = '#site-info .footer-links a {color:#';
			}			
			if (isset($_POST['exp_footer_bg'])) {
				$exp_theme_colors['exp_footer_bg']['value'] = esc_attr($_POST['exp_footer_bg']['value']);
				$exp_theme_colors['exp_footer_bg']['css'] = '#site-info {background-color:#';
			}
			if (isset($_POST['fonts'])) {
				$exp_theme_font = $_POST['fonts'];

				$fonts = exp_font_list();
				foreach ($fonts as $font_key => $font) {
					if ($exp_theme_font == $font_key) {
						$exp_font['value'] = $font['family'];
						$exp_font['css'] = 'body { font-family:';
						$exp_font['key'] = $exp_theme_font;
						update_option('exp_theme_font', $exp_font);
						break;
					}
				}
			}
			if (isset($_POST['font-size-body'])) {
				$exp_font['sizes']['body']['value'] = intval($_POST['font-size-body']);
				$exp_font['sizes']['body']['css'] = '.widget-area, #content {font-size:'.intval($_POST['font-size-body']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['font-size-h1'])) {
				$exp_font['sizes']['h1']['value'] = intval($_POST['font-size-h1']);
				$exp_font['sizes']['h1']['css'] = 'h1,h1.entry-title,h1.page-title {font-size:'.intval($_POST['font-size-h1']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['font-size-h2'])) {
				$exp_font['sizes']['h2']['value'] = intval($_POST['font-size-h2']);
				$exp_font['sizes']['h2']['css'] = 'h2 {font-size:'.intval($_POST['font-size-h2']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['font-size-h3'])) {
				$exp_font['sizes']['h3']['value'] = intval($_POST['font-size-h3']);
				$exp_font['sizes']['h3']['css'] = 'h3 {font-size:'.intval($_POST['font-size-h3']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['top_menu'])) {
				$exp_font['sizes']['top_menu']['value'] = intval($_POST['top_menu']);
				$exp_font['sizes']['top_menu']['css'] = '#nav .menu {font-size:'.intval($_POST['top_menu']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['footer_menu'])) {
				$exp_font['sizes']['footer_menu']['value'] = intval($_POST['footer_menu']);
				$exp_font['sizes']['footer_menu']['css'] = '#site-info .footer-links {font-size:'.intval($_POST['footer_menu']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['font-size-site_title'])) {
				$exp_font['sizes']['site_title']['value'] = intval($_POST['font-size-site_title']);
				$exp_font['sizes']['site_title']['css'] = '.logo a {font-size:'.intval($_POST['font-size-site_title']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			if (isset($_POST['font-size-site_tagline'])) {
				$exp_font['sizes']['site_tagline']['value'] = intval($_POST['font-size-site_tagline']);
				$exp_font['sizes']['site_tagline']['css'] = 'p.site-description,h2.site-description {font-size:'.intval($_POST['font-size-site_tagline']).'px';
				update_option('exp_theme_font', $exp_font);
			}
			update_option('exp_theme_colors', $exp_theme_colors);
			exp_update_style_sheet();
			wp_redirect(admin_url('themes.php?page=exp-color-settings&updated=true'));
		}
	}
	
}

function exp_color_settings() {
	global $exp_theme_color_default;
    //must check that the user has the required capability 
    if (!current_user_can('edit_theme_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.' , 'travelblogger') );
    }

	// Read in existing color options from database
    $opt_colors = get_option('exp_theme_colors',$exp_theme_color_default);

    // Load user selected font from database
    $fonts = get_option('exp_theme_font');
	
	$font_sizes = array(
		'default' => array(10, 11, 12, 13, 14, 15, 16, 18, 20),
		'title' => array(26, 28, 30, 32, 34, 36, 38, 40, 45, 50, 55, 60),
		'headers' => array(10, 11, 12, 13, 14, 15, 16, 18, 20, 22, 24, 26, 28, 30,32)
	);
    
    // Load set of fonts to use
    $font_list = exp_font_list();
    
    // Just to make sure we have some data, if not fill it up
    if ($opt_colors =='' && empty($_GET['reset'])) {
    	delete_option('exp_theme_colors');
    	wp_redirect(admin_url('themes.php?page=exp-color-settings'));
    }
    
	$current='';
?>
<?php if ( !empty($_GET['updated']) ) { ?>
<div id="message" class="updated">
	<p><?php printf( __( 'Settings updated. <a href="%s" target="_blank">Visit your site</a> to see how it looks.' , 'travelblogger' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>
<?php if ( !empty($_GET['reseted']) ) { ?>
<div id="message" class="updated">
	<p><?php printf( __( 'Settings reseted successfully!' , 'travelblogger' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>

<div class="wrap appearance_page_custom-header">
	<div id="icon-themes" class="icon32"><br/></div>

	<h2>Theme Colors &amp; Fonts</h2>
	<p>Here you can customize the colors and fonts of your theme. <strong style="color:#f00;">WARNING:</strong> This will override any colors and fonts from any default theme skin you have selected; however, the background, header and footer images will remain unchanged unless you update them separately.</p>
	<form action="" method="post" id="exp-colors-form">
			<table class="form-table">
			<tbody>
				<tr valign="top">
				<th scope="row">Site Font Style</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Site Font Style</span></legend>
							<select id="fonts" name="fonts" size="1">
								<?php
									$current = isset($fonts['key']) ? $fonts['key'] : '';
									foreach ($font_list as $font_key => $font) {
										$web_safe = ($font['web_safe']) ? ' *' : '';
										echo "<option" . selected( $current, $font_key ) . " value=\"" . $font_key . "\">" . $font['name'] . $web_safe . "</option>\n";	
									}
								?>
							</select><br/>
							<span class="small"><?php _e('&nbsp;Asterisks (*) denote web-safe fonts.', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Body Text</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Body Text</span></legend>
							<input type="text" class="color" name="exp_body_text[value]" id="exp_body_text[value]" value="<?php echo esc_attr($opt_colors['exp_body_text']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Body Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Body Font Size</span></legend>
							<select id="fonts" name="font-size-body" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['body']['value']) ? $fonts['sizes']['body']['value'] : '';
									foreach ($font_sizes['default'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select><br/>
							<span class="small"><?php _e('&nbsp;This will change the font size throughout the entire site', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Body Text Link</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Text Link</span></legend>
							<input type="text" class="color" name="exp_text_links[value]" id="exp_text_links[value]" value="<?php echo esc_attr($opt_colors['exp_text_links']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Top Menu Background</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Top Menu Background</span></legend>
							<input type="text" class="color" name="exp_top_menu[value]" id="exp_top_menu[value]" value="<?php echo esc_attr($opt_colors['exp_top_menu']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Top Menu Text Rollover</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Top Menu Text Rollover</span></legend>
							<input type="text" class="color" name="exp_top_menu_hover[value]" id="exp_top_menu_hover[value]" value="<?php echo esc_attr($opt_colors['exp_top_menu_hover']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Top Menu Text Selected</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Top Menu Text Selected</span></legend>
							<input type="text" class="color" name="exp_top_menu_selected[value]" id="exp_top_menu_selected[value]" value="<?php echo esc_attr($opt_colors['exp_top_menu_selected']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Top Menu Text Unselected</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Top Menu Text Unselected</span></legend>
							<input type="text" class="color" name="exp_top_menu_unselected[value]" id="exp_top_menu_unselected[value]" value="<?php echo esc_attr($opt_colors['exp_top_menu_unselected']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Top Menu Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Top Menu Font Size</span></legend>
							<select id="fonts" name="top_menu" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['top_menu']['value']) ? $fonts['sizes']['top_menu']['value'] : '';
									foreach ($font_sizes['default'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Site Title</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Site Title</span></legend>
							<input type="text" class="color" name="exp_author_name[value]" id="exp_author_name[value]" value="<?php echo esc_attr($opt_colors['exp_author_name']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Site Title Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Site Title Font Size</span></legend>
							<select id="fonts" name="font-size-site_title" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['site_title']['value']) ? $fonts['sizes']['site_title']['value'] : '';
									foreach ($font_sizes['title'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";
									}
								?>
							</select>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Site Tagline</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Site Tagline</span></legend>
							<input type="text" class="color" name="exp_author_description[value]" id="exp_author_description[value]" value="<?php echo esc_attr($opt_colors['exp_author_description']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Site Tagline Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Site Tagline Font Size</span></legend>
							<select id="fonts" name="font-size-site_tagline" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['site_tagline']['value']) ? $fonts['sizes']['site_tagline']['value'] : '';
									foreach ($font_sizes['default'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Category Titles (H1)</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Category Titles  (H1)</span></legend>
							<input type="text" class="color" name="exp_category_title_h1[value]" id="exp_category_title_h1[value]" value="<?php echo esc_attr($opt_colors['exp_category_title_h1']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Category Description Background</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Category Description </span></legend>
							<input type="text" class="color" name="exp_category_title_h1_bg[value]" id="exp_category_title_h1_bg[value]" value="<?php echo esc_attr($opt_colors['exp_category_title_h1_bg']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Category Text</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Category Text</span></legend>
							<input type="text" class="color" name="exp_category_text[value]" id="exp_category_text[value]" value="<?php echo esc_attr($opt_colors['exp_category_text']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Page Titles (H1)</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Page Titles (H1)</span></legend>
							<input type="text" class="color" name="exp_page_title_h1[value]" id="exp_page_title_h1[value]" value="<?php echo esc_attr($opt_colors['exp_page_title_h1']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">H1 Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>H1 Font Size</span></legend>
							<select id="fonts" name="font-size-h1" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['h1']['value']) ? $fonts['sizes']['h1']['value'] : '';
									foreach ($font_sizes['headers'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select><br/>
							<span class="small"><?php _e('&nbsp;Changes only H1s', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Page Titles (H2)</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Page Titles (H2)</span></legend>
							<input type="text" class="color" name="exp_page_title_h2[value]" id="exp_page_title_h2[value]" value="<?php echo esc_attr($opt_colors['exp_page_title_h2']['value']) ?>" />
							<br/><span class="small"><?php _e('Titles in blog roll', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Blog Roll Title Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Blog Roll Title Font Size</span></legend>
							<select id="fonts" name="font-size-h2" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['h2']['value']) ? $fonts['sizes']['h2']['value'] : '';
									foreach ($font_sizes['headers'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select><br/>
							<span class="small"><?php _e('&nbsp;Changes only blog roll titles', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Widget Title Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Widget Title Font Size</span></legend>
							<select id="fonts" name="font-size-h3" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['h3']['value']) ? $fonts['sizes']['h3']['value'] : '';
									foreach ($font_sizes['headers'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select><br/>
							<span class="small"><?php _e('&nbsp;Changes only widget titles', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Widget Title Text</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Widget Title Text</span></legend>
							<input type="text" class="color" name="exp_widget_text[value]" id="exp_widget_text[value]" value="<?php echo esc_attr($opt_colors['exp_widget_text']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Widget Title Background</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Widget Title Background</span></legend>
							<input type="text" class="color" name="exp_widget_bg[value]" id="exp_widget_bg[value]" value="<?php echo esc_attr($opt_colors['exp_widget_bg']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Content Titles (H1,H2,H3,H4,H5,H6)</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Content Titles (H1,H2,H3,H4,H5,H6)</span></legend>
							<input type="text" class="color" name="exp_content_titles[value]" id="exp_content_titles[value]" value="<?php echo esc_attr($opt_colors['exp_content_titles']['value']) ?>" />
							<br/><span class="small"><?php _e('Titles within blog post content.', 'travelblogger'); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Footer Menu Text Rollover</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Footer Menu Rollover</span></legend>
							<input type="text" class="color" name="exp_footer_menu_hover[value]" id="exp_footer_menu_hover[value]" value="<?php echo esc_attr($opt_colors['exp_footer_menu_hover']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Footer Menu Text Selected</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Footer Menu Selected</span></legend>
							<input type="text" class="color" name="exp_footer_menu_selected[value]" id="exp_footer_menu_selected[value]" value="<?php echo esc_attr($opt_colors['exp_footer_menu_selected']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Footer Menu Text Unselected</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Footer Menu Unselected</span></legend>
							<input type="text" class="color" name="exp_footer_menu_unselected[value]" id="exp_footer_menu_unselected[value]" value="<?php echo esc_attr($opt_colors['exp_footer_menu_unselected']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Footer Menu Font Size</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Footer Menu Font Size</span></legend>
							<select id="fonts" name="footer_menu" size="1">
								<option value="">Select font size</option>
								<?php
									$current = isset($fonts['sizes']['footer_menu']['value']) ? $fonts['sizes']['footer_menu']['value'] : '';
									foreach ($font_sizes['default'] as $font_size) {
										echo "<option" . selected( $current, $font_size ) . " value=\"" . $font_size . "\">&nbsp;" . $font_size. "&nbsp;&nbsp;</option>\n";	
									}
								?>
							</select>
						</fieldset>
					</td>
				</tr>						
				<tr valign="top">
				<th scope="row">Footer Menu Background</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Footer Menu Background</span></legend>
							<input type="text" class="color" name="exp_footer_bg[value]" id="exp_footer_bg[value]" value="<?php echo esc_attr($opt_colors['exp_footer_bg']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
				<th scope="row">Footer Copyright Text</th>
					<td>
						<fieldset>
						<legend class="screen-reader-text"><span>Footer Copyright Text</span></legend>
							<input type="text" class="color" name="exp_footer_text[value]" id="exp_footer_text[value]" value="<?php echo esc_attr($opt_colors['exp_footer_text']['value']) ?>" />
						</fieldset>
					</td>
				</tr>
			</tbody>
			</table>
			<table class="form-table">
			<tbody>	
				<tr valign="top">
					<th scope="row" style="padding-top:18px;"><strong>Reset Custom Settings</strong></th>
					<td>
					<fieldset>
					<legend class="screen-reader-text"><span>Reset Custom Settings</span></legend>
						<p><?php _e('This will remove all of your custom settings. You will not be able to restore any customizations.' , 'travelblogger') ?></p>
						<?php $urla = wp_nonce_url("themes.php?page=exp-color-settings&reset=true",'exp_theme_settings-reset'); ?>
						<p class="submit" style="margin:5px 0 0px 0px; padding:3px;"><a href="<?php echo $urla; ?>" class="button" ><?php echo esc_attr('Reset to Defaults'); ?></a></p>
					</fieldset>
					</td>
				</tr>			
			</tbody>
			</table>
			<table>
				<tr valign="top">
					<td>
						<p class="submit">
							<input type="submit" name="update-theme-colors" class="button-primary" value="<?php echo esc_attr('Save Changes') ?>" />
						</p>
					</td>
				</tr>
			</table>
			<?php wp_nonce_field('exp_theme_settings-update'); ?>
	</form>
</div><!-- #wrapper -->
<?php
}
