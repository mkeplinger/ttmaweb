<?php $icon = get_post_meta($post->ID, 'Icon', $single = true); ?>

<img src="<?php echo $icon; ?>" alt="" class="icon" />


 
<h4 class="title"><?php the_title(); ?></h4>
<?php global $more;   
	  $more = 0;
	  the_content(""); ?>

<a href="<?php the_permalink(); ?>" class="readmore"><span><?php _e('Read more','TheCorporation'); ?></span></a>