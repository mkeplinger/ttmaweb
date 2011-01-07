<div id="pagetop">
	<div class="container">
		<h1>
			<?php if (is_category()) single_cat_title();
				  elseif (is_tag()) single_tag_title();
				  elseif (is_day()) the_time('F jS, Y');
				  elseif (is_month()) the_time('F, Y');
				  elseif (is_year()) the_time('Y');
				  elseif (is_search()) the_search_query();
				  elseif (is_author()) {
						global $wp_query;
						$curauth = $wp_query->get_queried_object();
						echo $curauth->nickname;
				  }
				  else the_title(); 
			?>
		</h1>
		<?php include(TEMPLATEPATH . '/includes/postinfo.php'); ?>
	</div> <!-- end .container -->
</div> <!-- end #pagetop -->

<div id="breadcrumbs">
	<div class="container">
		
		<?php if(function_exists('bcn_display')) { bcn_display(); } 
			  else { ?>
				    <a href="<?php bloginfo('url'); ?>"><?php _e('Home','TheCorporation') ?></a> &raquo;
					
					<?php if( is_tag() ) { ?>
						<?php _e('Posts Tagged &quot;','TheCorporation') ?><?php single_tag_title(); echo('&quot;'); ?>
					<?php } elseif (is_day()) { ?>
						<?php _e('Posts made in','TheCorporation') ?> <?php the_time('F jS, Y'); ?>
					<?php } elseif (is_month()) { ?>
						<?php _e('Posts made in','TheCorporation') ?> <?php the_time('F, Y'); ?>
					<?php } elseif (is_year()) { ?>
						<?php _e('Posts made in','TheCorporation') ?> <?php the_time('Y'); ?>
					<?php } elseif (is_search()) { ?>
						<?php _e('Search results for','TheCorporation') ?> <?php the_search_query() ?>
					<?php } elseif (is_single()) { ?>
						<?php $category = get_the_category();
							  $catlink = get_category_link( $category[0]->cat_ID );
							  echo ('<a href="'.$catlink.'">'.$category[0]->cat_name.'</a> &raquo; '.get_the_title()); ?>
					<?php } elseif (is_category()) { ?>
						<?php single_cat_title(); ?>
					<?php } elseif (is_author()) { ?>
						<?php _e('Posts by ','TheCorporation'); echo ' ',$curauth->nickname; ?>
					<?php } elseif (is_page()) { ?>
						<?php wp_title(''); ?>
					<?php }; ?>
		<?php }; ?>
	
	</div> <!-- end .container -->
</div> <!-- end #breadcrumbs -->