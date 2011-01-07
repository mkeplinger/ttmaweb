<?php get_header(); ?>

	<div id="content-area" class="clearfix">
		<?php global $query_string; query_posts($query_string . "&showposts=$thecorporation_catnum_posts&paged=$paged&cat=$cat"); ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<?php include(TEMPLATEPATH . '/includes/entry.php'); ?>

			<?php endwhile; ?>

				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { ?>
					<?php include(TEMPLATEPATH . '/includes/navigation.php'); ?>
				<?php } ?>

			<?php else : ?>
				<?php include(TEMPLATEPATH . '/includes/no-results.php'); ?>
			<?php endif; wp_reset_query(); ?>

	</div> <!-- #content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>