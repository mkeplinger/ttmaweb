
<h4><a href="<?php the_permalink() ?>" title="<?php printf(__('Permanent Link to %s', 'TheCorporation'), get_the_title()) ?>"><?php the_title(); ?></a></h4>

<p class="meta-info">
	<?php if (get_option('thecorporation_postinfo_fromblog') <> '') { ?>
		<?php _e('Posted ','TheCorporation'); ?>
		<?php if (in_array('author', get_option('thecorporation_postinfo_fromblog'))) { _e(' by ','TheCorporation'); the_author_posts_link(); }; 
			  if (in_array('date', get_option('thecorporation_postinfo_fromblog'))) { _e(' on ','TheCorporation'); the_time(get_option('thecorporation_date_format')); }; ?>
	<?php }; ?>
</p>
