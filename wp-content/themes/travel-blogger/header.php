<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="bd">
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */
	global $esp_layout;

	$featured_area = get_option('exp_featured_area');
	if($featured_area == 'showall' || $featured_area == 'show' && (is_home() || is_category())) {
		$show_header = '1';
	} else {
		$show_header = '';
	}
	$grid = ($esp_layout['theme_grid'] == '') ? 'yui-t2' : $esp_layout['theme_grid'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 * We filter the output of wp_title() a bit -- see
	 * travelblogger_filter_wp_title() in functions.php.
	 */
	wp_title( '|', true, 'right' );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<!--[if IE]>
<style type="text/css">
  .clearfix, .third, .widget-container,.menu{
    zoom: 1; 
    }
</style>
<![endif]-->
</head>
<body <?php body_class($esp_layout['theme_color']); ?>>
<div id="doc4" class="<?php exp_template_class($grid); ?>">
   <div id="hd">
  		<div id="header"> 
  			<div class="logo">
  				<?php if(!get_option('exp_show_header_text')) {?>
					<?php $h1_tag = ( is_home() || is_front_page() ) ? 'h1' : 'span'; ?>
	   				<<?php echo $h1_tag; ?> class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></<?php echo $h1_tag; ?>>
					<?php $h2_tag = ( is_home() || is_front_page() ) ? 'h2' : 'p'; ?>
	   				<<?php echo $h2_tag; ?> class="site-description"><?php bloginfo( 'description' ); ?></<?php echo $h2_tag; ?>>
  				<?php } ?>
  			</div><!-- .logo -->
  		</div><!--#header-->
   		<div id="nav">
   			<?php wp_nav_menu( array( 'container_class' => 'menu', 'theme_location' => 'primary' ) ); ?>
   		</div><!-- #nav -->
		<div id="sub_nav" class="clearfix">
			<div class="social">
				<?php get_exp_social_links(); ?>
				
			</div>
			<?php exp_search_from(); ?>
		</div>
   </div><!-- header -->
	<?php if(is_page() && !is_page_template( 'onecolumn-page-nosidebar.php' ) && $show_header || $show_header && !is_single() && !is_search() && !is_tag()) { ?>
   		<?php exp_featured_post(); ?>
   <?php } ?>
   <div id="bd">
