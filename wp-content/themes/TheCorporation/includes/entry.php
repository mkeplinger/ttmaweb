<?php $thumb = ''; 	  

	$width = get_option('thecorporation_thumbnail_width_usual');
	$height = get_option('thecorporation_thumbnail_height_usual');
	$classtext = 'thumbnail-post alignleft';
	$titletext = get_the_title();
	
	$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
	$thumb = $thumbnail["thumb"];	
?>

<div class="entry clearfix">

	<h2 class="title"><a href="<?php the_permalink() ?>" title="<?php printf(__ ('Permanent Link to %s', 'TheCorporation'), $titletext) ?>"><?php the_title(); ?></a></h2>

	<?php if (get_option('thecorporation_postinfo1') <> '') { ?>
		<p class="post-meta"><span class="inner"><?php _e('Posted','TheCorporation'); ?> <?php if (in_array('author', get_option('thecorporation_postinfo1'))) { ?> <?php _e('by','TheCorporation'); ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('date', get_option('thecorporation_postinfo1'))) { ?> <?php _e('on','TheCorporation'); ?> <?php the_time(get_option('thecorporation_date_format')) ?><?php }; ?><?php if (in_array('categories', get_option('thecorporation_postinfo1'))) { ?> <?php _e('in','TheCorporation'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('thecorporation_postinfo1'))) { ?> | <?php comments_popup_link(__('0 comments','TheCorporation'), __('1 comment','TheCorporation'), '% '.__('comments','TheCorporation')); ?><?php }; ?></span></p>
	<?php }; ?>

	<?php if($thumb <> '' && get_option('thecorporation_thumbnails_index') == 'on') { ?>
		<a href="<?php the_permalink() ?>" title="<?php printf(__ ('Permanent Link to %s', 'TheCorporation'), $titletext) ?>">
			<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
		</a>
	<?php }; ?>

	<?php if (get_option('thecorporation_blog_style') == 'on') the_content(""); else { ?>
		<p><?php truncate_post(400); ?></p>
	<?php }; ?>
	<a class="readmore" href="<?php the_permalink(); ?>"><span><?php _e('Read More','TheCorporation'); ?></span></a>
	
</div> <!-- end .entry -->