<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=bd div and all content
 * after. 
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

 $footerlinks = get_option('exp_show_footer_links');
?>
	</div><!-- #bd -->
	<div id="ft">
				<div id="site-info">
						<?php wp_nav_menu( array( 'menu_class' => 'footer-links clearfix', 'depth' => '1', 'theme_location' => 'footer', 'fallback_cb' => 'wp_page_menu'  ) ); ?>
				</div><!-- #site-info -->
				<div class="footer-bg">
					<p class="footer-copy">
						<?php if($footerlinks['custom_copy'] != '') { 
							echo html_entity_decode(stripslashes($footerlinks['custom_copy']));
						} else { ?>
							&copy; <?php echo date(' Y '); bloginfo( 'name' );
						} ?>
					</p><!-- .footer-links -->
				</div>
				<div id="site-disclaimer">
					<?php
							echo '<p class="theme-links credit-links">TravelBlogger Theme developed by <a href="'.esc_url( __( 'http://www.freetravelwebsitetemplates.com/themes/travel-blogger-theme-for-wordpress/', 'travelblogger' ) ).'" target="_blank">FreeTravelWebsiteTemplates.com</a> | Powered by <a href="http://wordpress.org/" target="_blank">WordPress</a>';
							
							if( (is_home() || is_front_page() || is_page() || is_category()) && $footerlinks['add_credit'] !='') {
								$footerlink = get_option('esp_theme_links',array('url'=>'http://www.expedia.com','kw'=>'Travel')); ?>
								<p class="theme-links add-links">Designed by the Expedia <a href="<?php echo $footerlink['url']; ?>"><?php echo $footerlink['kw']; ?></a> Team.</p>
							<?php } ?>
				</div><!-- #site-disclaimer -->
	</div><!-- #ft -->

</div><!-- #doc -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */
	wp_footer();
?>
</body>
</html>
