<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 * @author Based WordPress Team Twenty Ten theme
 */
 global $esp_layout;
?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'travelblogger' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'travelblogger' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php $i=0; $teasers = 1; ?>
<?php while ( have_posts() ) : the_post(); ?>

		<?php if ($i < 4) : ?>
		
		<div class="outer">
			<div class="third">
				<div class="headliner">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'travelblogger' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<div class="entry-meta clearfix">
						<?php travelblogger_posted_on(); ?>
					</div><!-- .entry-meta -->
				</div>
				<div class="col1">
					<div class="entry-content">
						<?php
						 	if(!empty($esp_layout['theme_loop_content'])) {
								echo '<div class="entry-content">';
								the_content('<span class="read_more">read more</span>');
								echo '</div>';
							} else {
								echo '<div class="entry-summary">';
								the_excerpt();
								echo '</div>';
							}
						?>
					</div><!-- .entry-content -->
				</div>
				<div class="col2">
					<?php exp_get_comments($post->ID); ?>
				</div>
			</div><!--cols-->
		</div><!--outer-->	
		
		<?php else : ?>	
		
		<?php if (($teasers % 2) ==1 ) { echo '<div class="teaser_box clearfix">'; $class = '';} else { $class = 'right_box'; } ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'travelblogger' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="entry-meta">
					<?php travelblogger_posted_on(); ?>
				</div><!-- .entry-meta -->
	
				<div class="entry-content">
					<?php the_excerpt(); ?>
				</div><!-- .entry-content -->
	
				<div class="entry-utility">
					&nbsp;
				</div><!-- .entry-utility -->
			</div><!-- #post-## -->	
			
		<?php if (($i % 2) == 1 || $i == $wp_query->post_count-1) echo '</div>'; ?> 
		<?php $teasers++;?>
	<?php endif; ?>
<?php $i++; ?>
<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
		<div class="nav-outter clearfix">
				<div id="nav-below" class="navigation clearfix">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'travelblogger' ) ); ?></div>
				</div><!-- #nav-below -->
		</div><!-- .nav-outter -->	
<?php endif; ?>
