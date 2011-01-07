<?php if (get_option('thecorporation_duplicate') == 'false') {
		$args=array(
		   'showposts'=>get_option('thecorporation_homepage_posts'),
		   'post__not_in' => $ids,
		   'paged'=>$paged,
		   'category__not_in' => get_option('thecorporation_exlcats_recent'),
		);
	} else {
		$args=array(
		   'showposts'=>get_option('thecorporation_homepage_posts'),
		   'paged'=>$paged,
		   'category__not_in' => get_option('thecorporation_exlcats_recent'),
		);
	};
	query_posts($args); ?>
		<div class="post index">
			<?php $i = 0; 	
			if (have_posts()) : while (have_posts()) : the_post();
				$i++;?>
				<?php include(TEMPLATEPATH . '/includes/entry.php'); ?>
			<?php endwhile; ?>
			<div class="clear"></div>
		</div> <!-- end .post -->
		
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { ?>
					<?php include(TEMPLATEPATH . '/includes/navigation.php'); ?>
				<?php } ?>
				
			<?php else : ?>
				<?php include(TEMPLATEPATH . '/includes/no-results.php'); ?>
			<?php endif; wp_reset_query(); ?>