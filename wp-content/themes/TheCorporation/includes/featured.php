<?php	
	$ids = array();
	$arr = array();
	$i=1;
	
	$width = 330;
	$height = 220;
	
	$width_small = 72;
	$height_small = 72;
		
	$featured_cat = get_option('thecorporation_feat_cat');
	$featured_num = get_option('thecorporation_featured_num');
	
	if (get_option('thecorporation_use_pages') == 'false') query_posts("showposts=$featured_num&cat=".get_catId($featured_cat));
	else { 
		global $pages_number;
		
		if (get_option('thecorporation_feat_pages') <> '') $featured_num = count(get_option('thecorporation_feat_pages'));
		else $featured_num = $pages_number;
		
		query_posts(array
						('post_type' => 'page',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'post__in' => get_option('thecorporation_feat_pages'),
						'showposts' => $featured_num
					));
	};
	
	while (have_posts()) : the_post();
			
		$arr[$i]["title"] = truncate_title(50,false);
		$arr[$i]["title_small"] = truncate_title(25,false);
		
		$arr[$i]["fulltitle"] = truncate_title(250,false);
		
		$arr[$i]["excerpt"] = truncate_post(470,false);
		$arr[$i]["excerpt_small"] = truncate_post(80,false);
		
		$arr[$i]["tagline"] = get_post_meta($post->ID, 'Tagline', $single = true);
		$arr[$i]["permalink"] = get_permalink();
		
		$arr[$i]["thumbnail"] = get_thumbnail($width,$height,'thumb',$arr[$i]["fulltitle"],$arr[$i]["tagline"]);
		$arr[$i]["thumb"] = $arr[$i]["thumbnail"]["thumb"];
		$arr[$i]["thumbnail_small"] = get_thumbnail($width_small,$height_small,'',$arr[$i]["fulltitle"],$arr[$i]["tagline"]);
		$arr[$i]["thumb_small"] = $arr[$i]["thumbnail_small"]["thumb"];
		
		$arr[$i]["use_timthumb"] = $arr[$i]["thumbnail"]["use_timthumb"];

		$i++;
		$ids[]= $post->ID;
	endwhile; wp_reset_query();	?>
	

<div id="featured-area">
	<div class="container clearfix">		
		<div id="featured-slider">
			
			<?php for ($i = 1; $i <= $featured_num; $i++) { ?>
			
				<div class="featitem clearfix">
					<h2 class="feat-heading"><?php echo($arr[$i]["title"]); ?></h2>
					<p class="tagline"><?php echo($arr[$i]["tagline"]); ?></p>					
					<div class="excerpt">
						<p><?php echo($arr[$i]["excerpt"]); ?></p>
						<a href="<?php echo($arr[$i]["permalink"]); ?>" title="<?php printf(__('Permanent Link to %s', 'TheCorporation'), $arr[$i]["fulltitle"]) ?>" class="readmore"><span><?php _e('read more','TheCorporation'); ?></span></a>
					</div> <!-- end .excerpt -->
					
					<a href="<?php echo($arr[$i]["permalink"]); ?>" title="<?php printf(__('Permanent Link to %s', 'TheCorporation'), $arr[$i]["fulltitle"]) ?>">
						<?php print_thumbnail($arr[$i]["thumb"], $arr[$i]["use_timthumb"], $arr[$i]["fulltitle"] , $width, $height, 'thumb'); ?>
					</a>
				</div> <!-- end .featitem -->
				
			<?php }; ?>
															
		</div> <!-- div #featured-slider -->
		
		<a id="prevlink" href="#"><?php _e('Previous','TheCorporation'); ?></a>
		<a id="nextlink" href="#"><?php _e('Next','TheCorporation'); ?></a>	
	</div> <!-- end .container -->
</div> <!-- end #featured-area -->


<div id="featured-thumbs">
	<div class="container clearfix">
	
		<?php for ($i = 1; $i <= $featured_num; $i++) { ?>
			<a href="#">
				<?php print_thumbnail($arr[$i]["thumb_small"], $arr[$i]["use_timthumb"], $arr[$i]["fulltitle"] , $width_small, $height_small); ?>
			</a>
			<div class="thumb_popup">
				<p class="heading"><?php echo($arr[$i]["title_small"]); ?></p>
				<p>“<?php echo($arr[$i]["excerpt_small"]); ?></p>
			</div> <!-- end .thumb_popup -->
		<?php }; ?>
		
		<div id="active_item"></div>
		
	</div> <!-- end .container -->
</div> <!-- end #featured-thumbs -->