<?php global $thecorporation_catnum_posts, $thecorporation_grab_image, $thecorporation_blog_cat, $thecorporation_fromblog_recent, $thecorporation_fromblog_popular, $thecorporation_fromblog_random, $thecorporation_home_page_1; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php elegant_titles(); ?></title>
<?php elegant_description(); ?>
<?php elegant_keywords(); ?>
<?php elegant_canonical(); ?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie6style.css" />
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('#logo,div#top-menu,a#prevlink,a#nextlink,#featured-slider a.readmore,#featured-slider a.readmore span,#featured-slider img.thumb, a#search-icon,ul.nav li ul li,#services img.icon,#footer h3.widgettitle,#footer .widget ul li, .reply-container, .bubble');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie7style.css" />
<![endif]-->

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>
<body<?php if (is_front_page()) echo(' id="home"'); ?>>

	<div id="header">
		<div class="container">
			
			<!-- LOGO -->
			<a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="logo" id="logo" /></a>
			
			<!-- TOP MENU -->
			<div id="top-menu">
				<?php $menuClass = 'superfish nav clearfix';
				$primaryNav = '';
				
				if (function_exists('wp_nav_menu')) {
					$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'echo' => false ) );
				};
				if ($primaryNav == '') { ?>
					<ul class="<?php echo $menuClass; ?>">
						<?php if (get_option('thecorporation_home_link') == 'on') { ?>
							<li <?php if (is_front_page()) echo('class="current_page_item"') ?>><a href="<?php bloginfo('url'); ?>"><?php _e('Home','TheCorporation'); ?></a></li>
						<?php }; ?>

						<?php show_categories_menu($menuClass,false); ?>
						
						<?php show_page_menu($menuClass,false,false); ?>
					</ul> <!-- end ul.nav -->
				<?php }
				else echo($primaryNav); ?>
			</div> <!-- end #top-menu -->
			
			<a href="#" id="search-icon"><?php _e('search','TheCorporation'); ?></a>
			
			<div id="search-form">
				<form method="get" id="searchform1" action="<?php bloginfo('url'); ?>/">
					<input type="text" value="<?php _e('search this site...','TheCorporation'); ?>" name="s" id="searchinput" />
				</form>
			</div> <!-- end searchform -->
		
		</div> <!-- end .container -->	
	</div> <!-- end #header -->
	
	<?php if (is_front_page() && get_option('thecorporation_featured') == 'on') include(TEMPLATEPATH . '/includes/featured.php');
		  elseif (!is_front_page()) include(TEMPLATEPATH . '/includes/pagetop.php'); ?>
	
	<div id="content" class="clearfix">
		<div class="container">