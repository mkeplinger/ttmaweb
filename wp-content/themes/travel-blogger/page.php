<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

get_header(); ?>

	<div id="yui-main">
	   <div class="yui-b">
			 <div id="content" class="main-content hfeed rounded">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( is_front_page() ) { ?>
							<h2 class="entry-title"><?php the_title(); ?></h2>
						<?php } else { ?>	
							<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php } ?>				
					
						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'travelblogger' ), 'after' => '</div>' ) ); ?>
							<?php edit_post_link( __( 'Edit', 'travelblogger' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .entry-content -->
					</div><!-- #post-## -->
	
					<?php comments_template( '', true ); ?>
			
			<?php endwhile; ?>

			</div><!-- /.main-content -->
			<?php get_sidebar('secondary'); ?>
	   </div><!--yui-b-main-->
	</div><!--yui-main-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>