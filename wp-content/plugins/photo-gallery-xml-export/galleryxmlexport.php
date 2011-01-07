<?php
/*
Plugin Name: Photo Gallery XML export
Version: 1.2
Description: Generates XML file for use in Flash photo galleries
Author: Laura Gentry
Author URI: http://www.lauragentry.com
Plugin URI: http://www.lauragentry.com/wordpress/?p=216
*/

// determine xml variable name, get template
function galleryxml_feed(  ) {
  global $wp_rewrite;
  add_feed('galleryxml', 'format_xml');
  add_action('generate_rewrite_rules','createRewriteRules');
  $wp_rewrite->flush_rules();
}
function format_xml(  ) {
  include_once('template.php');
}
add_action('init', 'galleryxml_feed');

// create rewrite rules and append a variable to the URL
function createRewriteRules() {
	global $wp_rewrite;
 
	// add rewrite tokens
	$keytag = '%feed%';
	$wp_rewrite->add_rewrite_tag($keytag, '(.+?)', 'feed='.$wp_rewrite->preg_index(1));
 
	$keywords_structure = $wp_rewrite->root . "feed/$keytag/";
	$keywords_rewrite = $wp_rewrite->generate_rewrite_rules($keywords_structure);
 
	$wp_rewrite->rules = $keywords_rewrite + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'createRewriteRules');

// *************** OPTIONS PAGE SET UP ****************
// Add options to database
add_option("customfield1", '', '', 'yes'); 
add_option("customfield2", '', '', 'yes'); 
add_option("customfield3", '', '', 'yes'); 
add_option("customfield4", 'off', '', 'yes'); 
add_option("customfield5", 'off', '', 'yes'); 
add_option("tagname1", '', '', 'yes'); 
add_option("tagname2", '', '', 'yes'); 
add_option("tagname3", '', '', 'yes'); 
add_option("tagname4", '', '', 'yes'); 
add_option("tagname5", '', '', 'yes');
add_option("tagname6", '', '', 'yes');
add_option("tagname7", '', '', 'yes');
add_option("tagname8", '', '', 'yes');
add_option("numberposts", '', '', 'yes'); 
add_option("categoryname", '', '', 'yes'); 
add_option("checkbox1", '', '', 'yes');
add_option("checkbox2", '', '', 'yes');
add_option("checkbox3", '', '', 'yes');

// Add Gallery XML to the admin menu
function gallery_xml_menu() {
  add_options_page('Photo Gallery XML', 'Photo Gallery XML', 'manage_options', __FILE__, 'get_options_page');
}

// The form used to build the options page
function get_options_page() {
  include("galleryxmloptions.php");
}

function get_gallery_xml_options(){
 $options = array();
 $options['customfield1'] = get_option(customfield1);
 $options['customfield2'] = get_option(customfield2);
 $options['customfield3'] = get_option(customfield3);
 $options['customfield4'] = get_option(customfield4);
 $options['customfield5'] = get_option(customfield4);
 $options['tagname1'] = get_option(tagname1);
 $options['tagname2'] = get_option(tagname2);
 $options['tagname3'] = get_option(tagname3);
 $options['tagname4'] = get_option(tagname4);
 $options['tagname5'] = get_option(tagname5);
 $options['tagname6'] = get_option(tagname6);
 $options['tagname7'] = get_option(tagname7);
 $options['tagname8'] = get_option(tagname8);
 $options['numberposts'] = get_option(numberposts);
 $options['categoryname'] = get_option(categoryname);
 $options['checkbox1'] = get_option(checkbox1);
 $options['checkbox2'] = get_option(checkbox2);
 $options['checkbox3'] = get_option(checkbox3);
 
 
return $options;
}

$options = get_gallery_xml_options();
extract($options);


function register_my_settings() { // whitelist options
register_setting('gallery_xml_group','customfield1');
register_setting('gallery_xml_group','customfield2');
register_setting('gallery_xml_group','customfield3');
register_setting('gallery_xml_group','customfield4');
register_setting('gallery_xml_group','customfield5');
register_setting('gallery_xml_group','tagname1');
register_setting('gallery_xml_group','tagname2');
register_setting('gallery_xml_group','tagname3');
register_setting('gallery_xml_group','tagname4');
register_setting('gallery_xml_group','tagname5');
register_setting('gallery_xml_group','tagname6');
register_setting('gallery_xml_group','tagname7');
register_setting('gallery_xml_group','tagname8');
register_setting('gallery_xml_group','numberposts');
register_setting('gallery_xml_group','categoryname');
register_setting('gallery_xml_group','checkbox1');
register_setting('gallery_xml_group','checkbox2');
register_setting('gallery_xml_group','checkbox3');
}

//add the admin page
add_action('admin_menu', 'gallery_xml_menu');
add_action( 'admin_init', 'register_my_settings' );

?>
