<?php
/*
Plugin Name: MEE News
Plugin URI: http://www.wp-newsletter.com/
Description:Commercial versions not actualize this plugin, download it in http://www.wp-newsletter.com, Newsletter manager, list, users, sends, etc..
Version: 5.2.0
Author:  Daniel Perez, Tierra Virtual.com
Author URI: http://www.tierravirtual.com/
*/

global $meenews_datas;


define('WPVERSIONACT', get_bloginfo ( 'version' ));

if(!$the_version) $the_version = "1";

if (!function_exists("gd_info")){
    define('GD_INSTALL', 'false');
}else{
    define('GD_INSTALL', 'true');
}

if(!function_exists('version_compare') || version_compare( phpversion(), '5', '<' )){
        define('PHPVERSION', '/php4/');
}else if(!extension_loaded("imagick")) {
        define('PHPVERSION', 'php4/');
}else{
        define('PHPVERSION', 'php5/');
}



add_option("TVnews_versionac",get_option("TVnews_versionac"));
add_option("meenews_uninstall","false");

define('MEENEWS_CATEGORY', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewscategories');
define('MEENEWS_USERS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewsusers');
define('MEENEWS_NEWSLETERS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewssavednewsletters');
define('MEENEWS_STATS_NEWS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewsstats');
define('MEENEWS_CLICKS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewstatsclick');
add_option("url_link","http://www.onbile.com/info/");
define('MEENEWS_VARIANT', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewvariantstats');
define('MEENEWS_USEDPOSTS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewsusedposts');
define('MEENEWS_PENDENTSENDS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewsendpended');
define('MEENEWS_LINKS', (isset($current_blog)?$wpdb->base_prefix:$wpdb->prefix)  . 'meenewslinks');
define('MEENEWS__CUSTOMIMAGES', plugins_url('meenews/customimages/'));
define('MEENEWS',  ABSPATH . 'wp-content/plugins/meenews/');
define('MEENEWS_LIB', MEENEWS.'inc/');
define('MEENEWS_STYLES', MEENEWS_LIB . 'css/styles.css');
define('MEENEWS_CLASSES', MEENEWS_LIB . 'classes/');
define('MEENEWS_MANAGERS', MEENEWS_LIB . 'managers/');
define('MEENEWS_TPL_SOURCES', MEENEWS_LIB .  'tpl/');
define('MEENEWS_TPL', MEENEWS_CLASSES . PHPVERSION);
define('MEENEWS_TEMPLATE', MEENEWS."templates/");

define('MEENEWS_URI', get_bloginfo("wpurl")."/wp-content/plugins/meenews/");
add_option("last_update","2001-11-01");
add_option("style_on","text-decoration:none;font-size:10px");
define('MEENEWS_LIB_URI', MEENEWS_URI.'inc/');
define('MEENEWS_STYLES_URI', MEENEWS_LIB_URI . 'css/styles.css');
define('MEENEWS_CLASSES_URI', MEENEWS_LIB_URI . 'classes/');
define('MEENEWS_TPL_SOURCES_URI', MEENEWS_LIB_URI .  'tpl/');
define('MEENEWS_MANAGERS_URI', MEENEWS_LIB_URI . 'managers/');
define('MEENEWS_AJAX_FILE', MEENEWS_LIB_URI . 'ajax/ajax_actions.php');
define('MEENEWS_TEMPLATE_URI', MEENEWS_URI."templates/");
add_option("link","Onbile");

$Plug_autoload['classes'] = array(
                             'meenews_manager',
                             'mee_users',
                             'mee_newsletter',
                             'mee_sender',
                             'class.phpmailer',
                             'class.smtp',
                             'createdbs',
                             'mee_stats',
                             'meenews_images',
                             'front_meenews');

$Plug_autoload['managers'] = array('general_settings',
                              'newsletter_configuration',
                              'front_form_configuration',
                              'users_manager',
                              'newsletter_manager',
                              'managenewsletter',
                              'stats_manager');

$Plug_autoload['tpl'] = array('class.tpl');


////////////////////////////////////////////////////////////////////////////////
// INCLUSION DE ARCHIVOS
////////////////////////////////////////////////////////////////////////////////
include_once(MEENEWS_LIB.'init_meenews.php');  // theme options panel


function widget_meenews_init(){
    if (!function_exists('register_sidebar_widget')) {
		return;
	}


class widget_meenewsletter_plugin extends WP_Widget {
      function widget_meenewsletter_plugin()
      {
        /* Widget settings. */
                    global $mee_datas;
                    $widget_ops = array( 'classname' => '', 'description' => __('MeeNews Plugin', 'examplesss') );

                    /* Widget control settings. */
                    $control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'widget_meenewsletter_plugin' );

                    /* Create the widget. */
                    $this->WP_Widget( 'widget_meenewsletter_plugin', __('MeeNews Plugin', 'example'), $widget_ops, $control_ops );
      }
       function widget( $args, $instance )
      {

         global $mee_datas;

         $defaults = array( 'title' => 'Newsletter');
         extract( $args );

         $instance = wp_parse_args( (array) $instance, $defaults );

         $title = apply_filters('widget_title', $instance['title'] );

         echo $before_widget;
         echo $before_title . $title . $after_title;

         $front = new FrontMeeNews();
         echo "<li>";
         echo  $front->showFront();

         echo "<br><br></li>";

         echo $after_widget;



      }

      function update( $new_instance, $old_instance ) {
          $instance = $old_instance;

          /* Strip tags for title and name to remove HTML (important for text inputs). */

          $instance['title'] =  $new_instance['title'];

          return $instance;

      }
      function form( $instance )
      {
             /* Set up some default widget settings. */
        $defaults = array( 'title' => 'Newsletter');

        $instance = wp_parse_args( (array) $instance, $defaults );

        ?>

        <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>

                <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
        </p>


       <?php

     }

}


function limpia($var,$tags_permitidos='',$key=null){
	if (!rtrim('_l',$key)) {
		//no hace strip_tags
		$var = preg_replace("%[^A-Za-zÃÃ‰ÃÃ“Ãšáéíóú0-9<>\_\-@\.ñ/:, ".$tags_permitidos."]%", "", $var);
		return $var;
	}
	if (is_array($tags_permitidos)) {
		$tags_permitidos=implode('',$tags_permitidos);
		$var = strip_tags($var,$tags_permitidos);
		
	} else {
		if (isset($tags_permitidos) and !empty($tags_permitidos)  and is_string($tags_permitidos) and $tags_permitidos!='all')
		$var = strip_tags($var,$tags_permitidos);
	}
	$var = preg_replace("%[^A-Za-zÃÃ‰ÃÃ“Ãšáéíóú0-9<>\-\_@\.ñ/:, ".$tags_permitidos."]%", "", $var);
	return $var;
}
 

# Funcion que aplica la funcion anterior
# para no tener que preocuparnos por
# ataques de XSS o SQLi
function LimpiarArray($datos, $tags_permitidos=''){
	if (empty($datos)) return false;
	if(is_array($datos)){
		foreach ($datos as $e=>$a) {
			$tags_permitidos=(preg_match('%txt_%', $e))? $tags_permitidos='all':$tags_permitidos;
			$data[$e] = limpia($a, $tags_permitidos,$e);
		}
	}else{
		die("<font color=#ff0000><b>Error:</b></font> La funcion <b>LimpiarArray</b> debe contener un array.");
	}
	if (isset($data) and is_array($data))
	return $data;	
}
// Register Widgets
register_widget( 'widget_meenewsletter_plugin' );
}

add_action('widgets_init', 'widget_meenews_init');
