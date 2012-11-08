<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

get_header(); ?>

	<div id="yui-main">
	   <div class="yui-b">
				<?php if ( have_posts() ) : ?>
					<div class="archive-meta rounded">
						<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'travelblogger' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					</div>
					 <div id="content" class="main-content hfeed list rounded">
						<?php
						/* Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called loop-search.php and that will be used instead.
						 */
						 get_template_part( 'loop', 'search' );
						?>
				<?php else : ?>
					 <div id="content" class="main-content hfeed list rounded">
						<div id="post-0" class="post no-results not-found">
							<h2 class="entry-title"><?php _e( 'Nothing Found', 'travelblogger' ); ?></h2>
							<div class="entry-content">
								<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'travelblogger' ); ?></p>
								<?php get_search_form(); ?>
							</div><!-- .entry-content -->
						</div><!-- #post-0 -->
				<?php endif; ?>

			</div><!-- /.main-content -->
			<?php get_sidebar('secondary'); ?>
	   </div><!--yui-b-main-->
	</div><!--yui-main-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
