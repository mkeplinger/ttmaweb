<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */
 global $esp_layout;
?>
<?php
	if($esp_layout['theme_grid'] == 'yui-t2') {
?>
<div class="sidebar">
		<div id="right-primary" class="widget-area">
			<ul class="xoxo">
				<?php if ( ! dynamic_sidebar( 'right-primary-widget-area' ) ) : ?>

					<li id="archives" class="widget-container">
						<h3 class="widget-title"><?php _e( 'Archives', 'travelblogger' ); ?></h3>
						<ul>
							<?php wp_get_archives( 'type=monthly' ); ?>
						</ul>
					</li>

					<li id="meta" class="widget-container">
						<h3 class="widget-title"><?php _e( 'Meta', 'travelblogger' ); ?></h3>
						<ul>
							<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
							<?php wp_meta(); ?>
						</ul>
					</li>
				

				<?php endif; // end primary widget area ?>
			</ul>
		</div><!-- #right-primary .widget-area -->
</div><!-- /.sidebar -->
<?php } ?>