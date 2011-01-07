<?php get_header(); ?>

<?php if (get_option('thecorporation_services') == 'on') { ?>
	<div id="services" class="clearfix">
				
		<div class="one-third">
			<?php query_posts('page_id=' . get_pageId(html_entity_decode(get_option('thecorporation_service_1')))); while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/service_content.php'); ?>
			<?php endwhile; wp_reset_query(); ?>
		</div> <!-- end .one-third -->
		
		<div class="one-third">
			<?php query_posts('page_id=' . get_pageId(html_entity_decode(get_option('thecorporation_service_2')))); while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/service_content.php'); ?>
			<?php endwhile; wp_reset_query(); ?>
		</div> <!-- end .one-third -->
		
		<div class="one-third">
			<?php query_posts('page_id=' . get_pageId(html_entity_decode(get_option('thecorporation_service_3')))); while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/service_content.php'); ?>
			<?php endwhile; wp_reset_query(); ?>
		</div> <!-- end .one-third -->
		
	</div> <!-- end #services -->
<?php }; ?>

<?php include(TEMPLATEPATH . '/includes/default.php'); ?>

<?php get_footer(); ?>