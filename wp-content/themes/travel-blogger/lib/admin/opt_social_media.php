<?php
/**
 * Admin template for theme options
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

function exp_add_social_menu() {
	$page = add_theme_page(
	   __('Theme Social Media Settings', 'travelblogger'),
	   __('Theme Social Media Settings', 'travelblogger'),
	   'edit_theme_options',
	   'exp-social-settings',
	   'exp_social_settings'
	);
	add_action("admin_print_styles-$page", 'exp_load_theme_styles');
	add_action("admin_print_scripts-$page", 'exp_load_theme_scripts');
	add_action("load-$page", 'exp_social_option_updates');
}

add_action('admin_menu', 'exp_add_social_menu');

function exp_social_option_updates() {
	if (!empty($_POST['update-theme-social'])) {
		if ( check_admin_referer('exp_theme_settings-update') ) {
			
			if ( isset( $_POST['facebook_like'] ) ) {
				if ( $_POST['facebook_like'] == 1 ) {
					$exp_social['facebook_like_url'] = '';
					update_option('exp_social_feeds', $exp_social);
				} elseif ( $_POST['facebook_like'] == 0  ) {
					$exp_social['facebook_like_url'] = esc_attr($_POST['facebook_like_url']);
					update_option('exp_social_feeds', $exp_social);
				}
			}
			
			if (isset($_POST['twitter'])) {
				$exp_social['twitter'] = esc_attr($_POST['twitter']);
				update_option('exp_social_feeds', $exp_social);
			}
			if (isset($_POST['facebook'])) {
				$exp_social['facebook'] = esc_attr($_POST['facebook']);
				update_option('exp_social_feeds', $exp_social);
			}
			if (isset($_POST['rss'])) {
				$exp_social['rss'] = esc_attr($_POST['rss']);
				update_option('exp_social_feeds', $exp_social);
			}
			wp_redirect(admin_url('themes.php?page=exp-social-settings&updated=true'));
		}
	}
}

function exp_social_settings() {
	
    //must check that the user has the required capability 
    if (!current_user_can('edit_theme_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.' , 'travelblogger') );
    }	
    
	// Read in social information
	$opt_social = get_option('exp_social_feeds');
?>
<?php if ( !empty($_GET['updated']) ) { ?>
<div id="message" class="updated">
	<p><?php printf( __( 'Settings updated. <a href="%s" target="_blank">Visit your site</a> to see how it looks.' , 'travelblogger' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>

<div class="wrap appearance_page_custom-header">
	<div id="icon-themes" class="icon32"><br/></div>

	<h2>Social Settings</h2>
	<form action="" method="post" id="exp-social-form">
		<h3>Facebook Like Settings</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Facebook Like Settings</th>
					<td>
						<?php
						 	if($opt_social['facebook_like_url'] == '') {
								$facebook_like = '1'; ?>
								<script type="text/javascript">
							    	jQuery(document).ready(function($){
										$('.facebook_like_input').attr("disabled", "disabled");
										$('.facebook_like_input').toggleClass('disabled');
									});
								</script>
							<?php } else {
								$facebook_like = '0'; 
							}
						?>
						<label for="facebook"></label>
						<label><input type="radio" value="1" name="facebook_like" id="website_like"<?php checked( ( !empty( $facebook_like ) )  ? true : false ); ?> /> Show Like count for this website</label><br/>
						<label><input type="radio" value="0" name="facebook_like" id="facebok_like"<?php checked( ( empty( $facebook_like ) ) ? true : false ); ?> /> Show Like count for your Facebook page</label><br/>
						<input id="facebook" class="regular-text facebook_like_input" type="text" value="<?php echo esc_attr($opt_social['facebook_like_url']) ?>" name="facebook_like_url"/>
					<br/><span class="description">Enter the full path of your Facebook page URL. Eg. http://www.facebook.com/xxxxxx</span></td>
				</tr>
			</tbody>
		</table>
		<script type="text/javascript">
	    	jQuery(document).ready(function($){
				$('#facebok_like').click( function() {
					$('.facebook_like_input').removeAttr("disabled");
					$('.facebook_like_input').toggleClass('disabled');
				})
				$('#website_like').click( function() {
					$('.facebook_like_input').attr("disabled", "disabled");
					$('.facebook_like_input').toggleClass('disabled');
				})
			});
		</script>
		<h3>Follow Settings</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="twitter">Your twitter user account (username/path ONLY):</label></th>
					<td><p>Enter your Twitter User Account name if you would like people to be able to follow your Twitter feed.</p>
						<input id="twitter" class="regular-text" type="text" value="<?php echo esc_attr($opt_social['twitter']) ?>" name="twitter"/>
					<br/><span class="description">Eg. http://twitter.com/<strong style="color:#f00;">xxxxxx</strong> just the user name(highlighted), NOT the full path url</span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="facebook">Your facebook URL (username/path ONLY):</label></th>
					<td><p>Enter your Facebook URL if you would like people to be able to follow your Facebook page</p>
						<input id="facebook" class="regular-text" type="text" value="<?php echo esc_attr($opt_social['facebook']) ?>" name="facebook"/>
					<br/><span class="description">Enter the full path of your Facebook page URL. Eg. http://www.facebook.com/xxxxxx</span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="rss">RSS feed URL:</label></th>
					<td><input id="rss" class="regular-text" type="text" value="<?php echo esc_attr($opt_social['rss']) ?>" name="rss"/>
					<br/><span class="description">Eg. http://feeds.feedburner.com/xxxxxxx full path. (Leave blank to use default wordpress RSS) </span></td>
				</tr>
			</tbody>
		</table>
		<table>
			<tr valign="top">
				<td>
					<p class="submit">
						<input type="submit" name="update-theme-social" class="button-primary" value="<?php echo esc_attr('Save Changes') ?>" />
					</p>
				</td>
			</tr>
		</table>
		<?php wp_nonce_field('exp_theme_settings-update'); ?>
	</form>

</div><!-- #wrapper -->
<?php
}
