<div id="sidebar" class="home">
	<div id="fromblog" class="widget clearfix">
		<a href="<?php echo get_category_feed_link(get_catid($thecorporation_blog_cat), '');?>">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png" alt="" id="rss-icon" />
		</a>
		<h3 class="widgettitle"><?php _e('From the Blog','TheCorporation'); ?></h3>
		
		<?php query_posts("showposts=".get_option('thecorporation_fromblog_recent')."&cat=".get_catid(get_option('thecorporation_blog_cat')));
			if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/fromblog_post.php'); ?>
			<?php endwhile; endif; 
		wp_reset_query(); ?>	
	</div> <!-- end .widget -->

</div> <!-- end #sidebar -->	