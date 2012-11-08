<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

get_header(); ?>

	<div id="yui-main">
	   <div class="yui-b">
			<div class="archive-meta rounded">
				<h1 class="page-title"><?php printf( __( '%s', 'travelblogger' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
				<?php 
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo $category_description;
				?>
			</div>
			 <div id="content" class="main-content hfeed list rounded">
					<?php
	
					/* Run the loop for the category page to output the posts.
					 * If you want to overload this in a child theme then include a file
					 * called loop-category.php and that will be used instead.
					 */
					get_template_part( 'loop', 'category' );
					?>
	
			</div><!-- /.main-content -->
			<?php get_sidebar('secondary'); ?>
	   </div><!--yui-b-main-->
	</div><!--yui-main-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>