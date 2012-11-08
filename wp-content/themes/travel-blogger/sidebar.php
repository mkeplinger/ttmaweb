<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */
?>
 <div class="yui-b">
		<div id="primary" class="widget-area">
			<ul class="xoxo">
				<?php if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

					<li id="archives" class="widget-container">
						<h3 class="widget-title"><?php _e( 'Cateories', 'travelblogger' ); ?></h3>
						<ul>
							<?php wp_list_categories(); ?>
						</ul>
					</li>

					<li id="meta" class="widget-container">
						<h3 class="widget-title"><?php _e( 'Calendar', 'travelblogger' ); ?></h3>
						<ul>
							<?php get_calendar(); ?>
						</ul>
					</li>
				
				<?php endif; // end primary widget area ?>
				
			</ul>
		</div><!-- #primary .widget-area -->
</div><!-- .yui-b -->