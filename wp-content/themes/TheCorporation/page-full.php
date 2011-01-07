<?php 
/* 
Template Name: Full Width Page
*/
?>

<?php if (is_front_page()) { ?>
	<?php include(TEMPLATEPATH . '/home.php'); ?>
<?php } else { ?>
	<?php get_header(); ?>
	<?php the_post(); ?>
		
		<div id="content-area" class="clearfix fullwidth">
	
			<div class="post clearfix">
				<?php $width = get_option('thecorporation_thumbnail_width_pages');
					  $height = get_option('thecorporation_thumbnail_height_pages');
					  $classtext = 'thumbnail-post alignleft';
					  $titletext = get_the_title();
					
					  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
					  $thumb = $thumbnail["thumb"]; ?>
				
				<?php if($thumb <> '' && get_option('thecorporation_page_thumbnails') == 'on') { ?>
					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
				<?php }; ?>
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php edit_post_link(__('Edit this page','TheCorporation')); ?>
				<div class="clear"></div>
			</div> <!-- end .post -->
			
			<?php if (get_option('thecorporation_show_pagescomments') == 'on') comments_template('', true); ?>
			
		</div> <!-- end #content-area -->
	
	<?php get_footer(); ?>
<?php } ?>