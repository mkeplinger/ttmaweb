<?php
/** Tell WordPress to run travelblogger_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'travelblogger_setup' );

define('WP_VERSION', $wp_version);

if ( ! function_exists( 'travelblogger_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since TravelBlogger Theme 1.0
 */
function travelblogger_setup() {
 	global $exp_theme_color_default;

 	// Checks to see if WordPress installation is compatible with theme
	if (WP_VERSION < 3.0): // disable theme front end if wp < 3.0
	
	  function exp_unsupported_wp_version(){ ?>
	  <div class='error fade'>
		   <p>
			   <?php
			    printf(__('Your site is running on %1$s. Travel Blogger Theme requires at least %2$s.','travelblogger'), 'Wordpress '.WP_VERSION, '<a href="http://codex.wordpress.org/Upgrading_WordPress">Wordpress 3.0</a>');
			    if (current_user_can('switch_themes') && !is_admin()) echo '<br /><a href="'.site_url().'/wp-admin/">'.__("(Dashboard)","travelblogger").'</a>';
			   ?>
		   </p>
	  </div>
	  <?php if(!is_admin()) die();
	  }
	  add_action('admin_notices', 'exp_unsupported_wp_version');
	  add_action('wp', 'exp_unsupported_wp_version');
	
	else :
		
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		
		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '' );
		
		// Set default background image for theme, just used as fallback.
		// The %s is a placeholder for the theme template directory URI.
		define( 'BACKGROUND_IMAGE', '%s/images/backgrounds/background-default.jpg' );
		
		// Default background color
		define( 'BACKGROUND_COLOR', 'BBD9EE');
	
		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to travelblogger_header_image_width and travelblogger_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'travelblogger_header_image_width', 975 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'travelblogger_header_image_height', 130 ) );
		
		define( 'FOOTER_IMAGE_WIDTH', 975 );
		define( 'FOOTER_IMAGE_HEIGHT', 130 );
		
		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', false );
		
		// This theme allows users to set a footer background
		add_custom_footer();

		// This theme allows users to set a custom background
		add_custom_background('exp_custom_background_cb','esp_background_div_admin');
		
		// This theme allows users to set a header background
		add_custom_image_header('exp_header_style','');
		
		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Size for thumbnails in post listings and feature
		set_post_thumbnail_size( 190, 190 );
	
		// Size for widget thumbnails
		add_image_size( 'widget-thumbnail', 60, 80 ); // Permalink thumbnail size
		
		add_image_size( 'feature-thumbnail', 400, 335 ); // Permalink thumbnail size
	
		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );
	
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'travelblogger' ),
			'footer' => __( 'Footer Navigation', 'travelblogger' )
		) );
		
		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'clouds-compass' => array(
				'url' => '%s/images/headers/clouds-compass.jpg',
				'thumbnail_url' => '%s/images/headers/clouds-compass-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Clouds Compass', 'travelblogger' )
			),
			'forest-sunset' => array(
				'url' => '%s/images/headers/forest-sunset.jpg',
				'thumbnail_url' => '%s/images/headers/forest-sunset-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Forest Sunset', 'travelblogger' )
			),
			'lake' => array(
				'url' => '%s/images/headers/lake.jpg',
				'thumbnail_url' => '%s/images/headers/lake-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Lake', 'travelblogger' )
			),
			'mountains' => array(
				'url' => '%s/images/headers/mountains.jpg',
				'thumbnail_url' => '%s/images/headers/mountains-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Mountains', 'travelblogger' )
			),
			'red-sunset' => array(
				'url' => '%s/images/headers/red-sunset.jpg',
				'thumbnail_url' => '%s/images/headers/red-sunset-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Red Sunset', 'travelblogger' )
			),
			'st-basils' => array(
				'url' => '%s/images/headers/st-basils.jpg',
				'thumbnail_url' => '%s/images/headers/st-basils-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'St. Basils', 'travelblogger' )
			),
			'tent' => array(
				'url' => '%s/images/headers/tent.jpg',
				'thumbnail_url' => '%s/images/headers/tent-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Tent', 'travelblogger' )
			)
		) );
		
		if(is_admin()) {
			global $custom_image_header,$custom_background;
			remove_action( 'admin_menu', array( &$custom_image_header, 'init' ) );
			remove_action( 'admin_menu', array( &$custom_background, 'init' ) );
			$custom_image_header = new EXP_Custom_Image_Header( 'esp_admin_header_style', '' );
			add_action( 'admin_menu', array( &$custom_image_header, 'init' ) );
			$custom_background = new EXP_Custom_Background( '', 'esp_background_div_admin' );
			add_action( 'admin_menu', array( &$custom_background, 'init' ) );
		}
		
		exp_footer_links();
				
		// Theme layout default.
		$exp_theme_layout_defaults = array('theme_grid'=>'yui-t2','theme_color'=>'default','theme_loop_content'=>'');
		
		// Now add default theme layouts to database.
		add_option('exp_theme_layout', $exp_theme_layout_defaults);
		
		// Social defaults.
		$exp_social_feeds = array('facebook'=>'','twitter'=>'','rss'=>'','facebook_like_url'=>'');
		
		// Now add default social settings to database.
		add_option('exp_social_feeds', $exp_social_feeds);
		
		$exp_theme_font = array(
			'value' => 'Candara, Verdana, sans-serif',
			'css' => 'body { font-family: ',
			'key' => 'candara'
		);
		
		// Now add the default font to database.
		add_option('exp_theme_font', $exp_theme_font);
		
		// Add default settings for featured area
		add_option('exp_featured_area', 'show');
		
		// Add footer settings
		add_option('exp_show_footer_links', array('credit'=>'show','add_credit'=>'','custom_copy'=>'') );
		
		// Writes version of theme
		if(!get_option('exp_theme_ver')) {
			$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
			add_option('exp_theme_ver',$theme_data['Version']);
		}
		
	endif;
}
endif;

?>