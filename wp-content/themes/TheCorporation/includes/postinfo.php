<?php if (is_page() || is_category()) { ?>
	<?php $tagline = get_post_meta($post->ID, 'Tagline', $single = true);
		  if (is_category()) $tagline = category_description();
		  if ($tagline <> '') {	?>
				<p class="tagline">
					<?php echo($tagline); ?>
				</p>
		  <?php }; ?>	
<?php } elseif (is_single() && get_option('thecorporation_postinfo2') <> '') { ?>
	<p class="tagline">
		<span><?php _e('Posted','TheCorporation'); ?> <?php if (in_array('author', get_option('thecorporation_postinfo2'))) { ?> <?php _e('by','TheCorporation'); ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('date', get_option('thecorporation_postinfo2'))) { ?> <?php _e('on','TheCorporation'); ?> <?php the_time(get_option('thecorporation_date_format')) ?><?php }; ?><?php if (in_array('categories', get_option('thecorporation_postinfo2'))) { ?> <?php _e('in','TheCorporation'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('thecorporation_postinfo2'))) { ?> | <?php comments_popup_link(__('0 comments','TheCorporation'), __('1 comment','TheCorporation'), '% '.__('comments','TheCorporation')); ?><?php }; ?></span>
	</p>
<?php }; ?>