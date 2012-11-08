<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

get_header(); ?>

	<div id="yui-main">
	   <div class="yui-b">
			<?php
				/* Queue the first post, that way we know who
				 * the author is when we try to get their name,
				 * URL, description, avatar, etc.
				 *
				 * We reset this later so we can run the loop
				 * properly with a call to rewind_posts().
				 */
				if ( have_posts() )
					the_post();
			?>
							<div class="archive-meta rounded">
								<h1 class="page-title author"><?php printf( __( 'Author Archives: %s', 'travelblogger' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" ); ?></h1>
							</div>
		
			 <div id="content" class="main-content hfeed list rounded">
				<?php
				// If a user has filled out their description, show a bio on their entries.
				if ( get_the_author_meta( 'description' ) ) : ?>
									<div id="entry-author-info">
										<div id="author-avatar">
											<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'travelblogger_author_bio_avatar_size', 60 ) ); ?>
										</div><!-- #author-avatar -->
										<div id="author-description">
											<h2><?php printf( __( 'About %s', 'travelblogger' ), get_the_author() ); ?></h2>
											<?php the_author_meta( 'description' ); ?>
										</div><!-- #author-description	-->
									</div><!-- #entry-author-info -->
				<?php endif; ?>
				
				<?php
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();
				
					/* Run the loop for the author archive page to output the authors posts
					 * If you want to overload this in a child theme then include a file
					 * called loop-author.php and that will be used instead.
					 */
					 get_template_part( 'loop', 'author' );
				?>

				</div><!-- /.main-content -->
				<?php get_sidebar('secondary'); ?>
		   </div><!--yui-b-main-->
	</div><!--yui-main-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
