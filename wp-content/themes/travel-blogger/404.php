<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

get_header(); ?>

	<div id="yui-main">
	   <div class="yui-b">			
			 <div id="content" class="main-content hfeed list rounded">
			 
					<div id="post-0" class="post error404 not-found">
						<h1 class="entry-title"><?php _e( 'Not Found', 'travelblogger' ); ?></h1>
						<div class="entry-content">
							<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'travelblogger' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</div><!-- #post-0 -->
			</div><!-- /.main-content -->
			<?php get_sidebar('secondary'); ?>
	   </div><!--yui-b-main-->
	</div><!--yui-main-->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_sidebar(); ?>
<?php get_footer(); ?>