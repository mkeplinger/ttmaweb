<?php
if ( ! isset( $content_width ) )
	$content_width = 510;
	
$esp_layout = get_option('exp_theme_layout');

/**
* Add custom style sheets and reset file
**/
if (!is_admin()) {
	wp_enqueue_style( 'exp-reset-fonts-grids', get_stylesheet_directory_uri() . '/css/reset-fonts-grids.css');
	wp_enqueue_style( 'exp-main', get_stylesheet_directory_uri() . '/style.css');
	wp_deregister_script('jquery'); 
	wp_register_script('jquery', ('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'), false, '1.4.2'); 
	wp_enqueue_script('jquery');
	wp_enqueue_script('exp-gmaps','http://maps.google.com/maps/api/js?sensor=false');
} else {
	$exp_body_bg_default = 'BBD9EE';
	$exp_theme_color_default = array(
		'exp_text_links' => array('value' => 'B58A00','css' =>'a:link, a:visited, a:hover {color:#'),
		'exp_body_text' => array('value' => '111111','css' =>'body, input, textarea {color:#'),
		'exp_top_menu' => array('value' => 'E6B907','css' =>'#nav,#nav ul ul a {background-color:#'),
		'exp_author_photo' => array('value' => 'E6B907','css' =>'.author_photo {border-color:#'),
		'exp_feature_box' => array('value' => 'E6B907','css' =>'.feature_ft {background-color:#'),
		'exp_archive_meta' => array('value' => '143D8F','css' =>'.archive-meta {background:#'),
		'exp_footer_copy' => array('value' => 'E6B907','css' =>'.footer-copy {border-top-color:#'),
		'exp_top_menu_hover' => array('value' => 'ffffff','css' =>'#nav li:hover > a,#nav ul ul :hover > a {color:#'),
		'exp_top_menu_selected' => array('value' => '143D8F','css' =>'#nav ul li.current_page_item > a, #nav ul li.current-menu-ancestor > a, #nav ul li.current-menu-item > a, #nav ul li.current-menu-parent > a {color:#'),
		'exp_top_menu_unselected' => array('value' => 'FFF2A1','css' =>'#nav a {color:#'),
		'exp_author_name' => array('value' => 'ffffff','css' =>'.logo a,.logo h1.site-title a {color:#'),
		'exp_author_description' => array('value' => 'EBEBEB','css' =>'.site-description {color:#'),
		'exp_page_title_h1' => array('value' => 'B58A00','css' =>'h1.entry-title {color:#'),
		'exp_category_title_h1' => array('value' => '94B8FF','css' =>'.archive-meta h1.page-title {color:#'),
		'exp_category_title_h1_bg' => array('value' => '143D8F','css' =>'.archive-meta {background-color:#'),
		'exp_category_text' => array('value' => '94B8FF','css' =>'.archive-meta p {color:#'),
		'exp_widget_text' => array('value' => 'FFF2A1','css' =>'.widget-area h3 {color:#'),
		'exp_widget_bg' => array('value' => 'E6B907','css' =>'.widget-area h3 {background-color:#'),
		'exp_page_title_h2' => array('value' => 'B58A00','css' =>'h2 a:link, h2 a:visited,.widget-area h2 a:link,.widget-area h2 a:visited {color:#'),
		'exp_content_titles' => array('value' => 'B58A00','css' =>'.entry-content h1,.entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6 {color:#'),
		'exp_footer_text' => array('value' => '000000','css' =>'#site-disclaimer, #site-disclaimer a {color:#'),
		'exp_footer_menu_hover' => array('value' => 'ffffff','css' =>'#site-info li:hover > a,#site-info ul ul :hover > a {color:#'),
		'exp_footer_menu_selected' => array('value' => '143D8F','css' =>'#site-info ul li.current_page_item > a, #site-info ul li.current-menu-ancestor > a, #site-info ul li.current-menu-item > a, #site-info ul li.current-menu-parent > a {color:#'),
		'exp_footer_menu_unselected' => array('value' => 'FFF2A1','css' =>'#site-info .footer-links a {color:#'),
		'exp_footer_bg' => array('value' => 'E6B907','css' =>'#site-info {background-color:#')
	);
	$exp_body_bg_camping = '255926';
	$exp_theme_color_camping = array(
		'exp_text_links' => array('value' => 'B58A00','css' =>'a:link, a:visited, a:hover {color:#'),
		'exp_body_text' => array('value' => '384006','css' =>'body, input, textarea {color:#'),
		'exp_top_menu' => array('value' => '698C00','css' =>'#nav,#nav ul ul a {background-color:#'),
		'exp_author_photo' => array('value' => '698C00','css' =>'.author_photo {border-color:#'),
		'exp_feature_box' => array('value' => '698C00','css' =>'.feature_ft {background-color:#'),
		'exp_archive_meta' => array('value' => '698C00','css' =>'.archive-meta {background:#'),
		'exp_footer_copy' => array('value' => 'FFFFFF','css' =>'.footer-copy {color:#'),
		'exp_top_menu_hover' => array('value' => 'ffffff','css' =>'#nav li:hover > a,#nav ul ul :hover > a {color:#'),
		'exp_top_menu_selected' => array('value' => 'F5C60A','css' =>'#nav ul li.current_page_item > a, #nav ul li.current-menu-ancestor > a, #nav ul li.current-menu-item > a, #nav ul li.current-menu-parent > a {color:#'),
		'exp_top_menu_unselected' => array('value' => 'FFED94','css' =>'#nav a {color:#'),
		'exp_author_name' => array('value' => 'ffffff','css' =>'.logo a,.logo h1.site-title a {color:#'),
		'exp_author_description' => array('value' => 'EBEBEB','css' =>'.site-description {color:#'),
		'exp_page_title_h1' => array('value' => '505C09','css' =>'h1.entry-title {color:#'),
		'exp_category_title_h1' => array('value' => '505C09','css' =>'.archive-meta h1.page-title {color:#'),
		'exp_category_title_h1_bg' => array('value' => 'FFED94','css' =>'.archive-meta {background-color:#'),
		'exp_category_text' => array('value' => '505C09','css' =>'.archive-meta p {color:#'),
		'exp_widget_text' => array('value' => '505C09','css' =>'.widget-area h3 {color:#'),
		'exp_widget_bg' => array('value' => 'FFED94','css' =>'.widget-area h3 {background-color:#'),
		'exp_page_title_h2' => array('value' => 'B58A00','css' =>'h2 a:link, h2 a:visited,.widget-area h2 a:link,.widget-area h2 a:visited {color:#'),
		'exp_content_titles' => array('value' => '505C09','css' =>'.entry-content h1,.entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6 {color:#'),
		'exp_footer_text' => array('value' => 'ffffff','css' =>'#site-disclaimer, #site-disclaimer a {color:#'),
		'exp_footer_menu_hover' => array('value' => 'ffffff','css' =>'#site-info li:hover > a,#site-info ul ul :hover > a {color:#'),
		'exp_footer_menu_selected' => array('value' => 'F5CC00','css' =>'#site-info ul li.current_page_item > a, #site-info ul li.current-menu-ancestor > a, #site-info ul li.current-menu-item > a, #site-info ul li.current-menu-parent > a {color:#'),
		'exp_footer_menu_unselected' => array('value' => 'FFF2A1','css' =>'#site-info .footer-links a {color:#'),
		'exp_footer_bg' => array('value' => '698C00','css' =>'#site-info {background-color:#')
	);
}

function exp_footer_scripts() { ?>
	<script type="text/javascript">
		var template_url = "<?php echo get_template_directory_uri(); ?>";
	</script>
	<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="https://apis.google.com/js/plusone.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/gmap.js"></script>
<?php }
add_action('wp_footer','exp_footer_scripts');

/**
 * Sets template class. Defines class type for body width, column location and column width.
 *
 * @since TravelBlogger Theme 1.0
 */
function exp_template_class($template_class = '') {
	if(is_page_template( 'onecolumn-page-nosidebar.php' )) {
		$class = 'yui-t7';
	} elseif ($template_class == '') {
		$class = 'yui-t2';	 
	} else {
		$class = trim($template_class);
	}
	
	echo $class;
}

// Set default footer image
define( 'FOOTER_IMAGE' , '%s/images/backgrounds/footer-default.jpg' );

/**
 * Retrieves footer image
 *
 * @uses FOOTER_IMAGE
 *
 * @return string
 */
function get_footer_image() {
	$default = defined('FOOTER_IMAGE') ? FOOTER_IMAGE : '';

	$url = get_theme_mod('footer_image', $default);

	if ( is_ssl() )
		$url = str_replace( 'http://', 'https://', $url );
	else
		$url = str_replace( 'https://', 'http://', $url );

	return $url;
}

/**
 * Display footer image path.
 */
function footer_image() {
	echo get_footer_image();
}

/**
 * Custom background callback.
 *
 */
function exp_custom_background_cb() {
	$background = get_background_image();
	$color = get_background_color();
	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', 'no-repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'center' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', 'fixed' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";

		$style .= $image . $repeat . $position . $attachment;
	}
?>
<style type="text/css">
body { <?php echo trim( $style ); ?> }
</style>
<?php
}

// custom header style
function exp_header_style() { ?>	
	<style type="text/css">
        #header {
            background:transparent url(<?php header_image(); ?>);
        }
    </style>
<?php }

// custom footer style
$footer =  get_footer_image();
function exp_footer_style() { ?>	
	<style type="text/css">
        .footer-bg {
            background:transparent url(<?php footer_image(); ?>);
			height:129px;
        }
    </style>
<?php }
if ( !empty($footer) )
add_filter('wp_head','exp_footer_style');
// custom site styles
$custom_css = get_option('exp_custom_css');
function exp_custom_css() { 
	global $custom_css;
	?>	
	<style type="text/css">
        <?php echo $custom_css; ?>
    </style>
<?php }
if ( !empty($custom_css) )
add_filter('wp_head','exp_custom_css');

function add_custom_footer() {
	if ( isset( $GLOBALS['custom_image_footer'] ) )
		return;

	if ( ! is_admin() )
		return;
	require_once( EXP_CLASSES . '/custom-footer.php' );
	$GLOBALS['custom_image_footer'] =& new EXP_Custom_Image_Footer();
	add_action( 'admin_menu', array( &$GLOBALS['custom_image_footer'], 'init' ) );
}

//Adds images to excerpts
function exp_add_image_excerpt($excerpt) {
	$thumb = exp_get_meta_image();
	if(!empty($thumb)) {$thumb = '<a href="'.get_permalink().'" style="background-image: url('.$thumb.');" class="thumb_crop">'.$thumb.'</a>'; }
	return $thumb.$excerpt;
}
add_filter('the_excerpt','exp_add_image_excerpt');

?>