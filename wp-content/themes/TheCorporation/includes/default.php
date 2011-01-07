<?php $fullWidthPage = is_page_template('page-full.php'); ?>
<div id="content-area" class="clearfix<?php if ( $fullWidthPage ) echo(' fullwidth_home');?>">

	<div class="entry<?php if (get_option('thecorporation_blog_style') == 'false') echo(' page'); ?>">
		<?php if (is_page()) { //if static homepage 
				 if (have_posts()) : while (have_posts()) : the_post(); 
					include(TEMPLATEPATH . '/includes/homepage_content.php');
				 endwhile; endif; 
			  } else {
				 if (get_option('thecorporation_blog_style') == 'on') include(TEMPLATEPATH . '/includes/blogstyle_home.php'); 
					else { 
					  query_posts('page_id=' . get_pageId(html_entity_decode(get_option('thecorporation_home_page_1'))) ); while (have_posts()) : the_post(); 
						include(TEMPLATEPATH . '/includes/homepage_content.php'); 
					  endwhile; wp_reset_query();
					}; 
			  }; ?>
	</div> <!-- end .entry -->
	
</div> <!-- end #content-area -->

<?php if ( !$fullWidthPage ) { ?>
	<?php if (get_option('thecorporation_blog_style') == 'false' && get_option('thecorporation_homepage_widgets') == 'false') include(TEMPLATEPATH . '/includes/fromblog.php'); else get_sidebar(); ?>
<?php }; ?>