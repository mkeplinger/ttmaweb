<?php
/*
Plugin Name: Live Chat Software for Wordpress
Plugin URI: http://www.livechatinc.com
Description: Live chat software for live help, online sales and customer support. This plugin allows to quickly install the live chat button and monitoring code on any WordPress website.
Author: LIVECHAT Software
Author URI: http://www.livechatinc.com
Version: 2.1.7
*/


//
// Admin panel
//

/**
 * Loads CSS styles for admin panel styling
 */

define ('LIVECHAT_PLUGIN_URL', WP_PLUGIN_URL . str_replace('\\', '/', strrchr(dirname(__FILE__), DIRECTORY_SEPARATOR)) . '/plugin_files');
define ('LIVECHAT_LICENSE_INSTALLED', (bool)(strlen(get_option('livechat_license_number') > 0)));

function livechat_admin_head()
{
	echo '<style type="text/css">';
	echo '@import url('.LIVECHAT_PLUGIN_URL.'/css/styles.css);';
	echo '</style>';
}

/**
 * Loads jQuery scripts in admin panel
 */
function livechat_admin_footer()
{
	echo '<script type="text/javascript" src="'.LIVECHAT_PLUGIN_URL.'/js/scripts.js?v=2010-06-23"></script>';
	echo '<script type="text/javascript" src="'.LIVECHAT_PLUGIN_URL.'/js/signup.js?v=2010-06-23"></script>';
}

/**
 * Registers livechat settings variables
 */
function livechat_sanitize_license_number ($license_number)
{
	$license_number = trim($license_number);
	if (intval($license_number) > 0) return intval($license_number);
	if (preg_match('/^\d{2,}$/', $license_number)) return $license_number;

	return '';;
}

function livechat_sanitize_lang ($lang)
{
	$lang = trim($lang);
	if (preg_match('/^[a-z]{2}$/', $lang)) return $lang;

	return 'en';
}

function livechat_sanitize_groups ($groups)
{
	$groups = trim($groups);
	if (preg_match('/^(\d+)$/', $groups)) return $groups;

	return '0';
}

function livechat_admin_register_settings()
{
	register_setting ('livechat_license_information', 'livechat_license_not_installed');
	register_setting ('livechat_license_information', 'livechat_license_number', 'livechat_sanitize_license_number');
	register_setting ('livechat_license_information', 'livechat_lang', 'livechat_sanitize_lang');
	register_setting ('livechat_license_information', 'livechat_groups', 'livechat_sanitize_groups');
	register_setting ('livechat_license_information', 'livechat_params');
	register_setting ('livechat_license_information', 'livechat_license_created_flag');
}

function livechat_read_options()
{
	$license_number = get_option('livechat_license_number');

	$lang = get_option('livechat_lang');
	if (empty ($lang)) $lang = 'en';

	$groups = get_option('livechat_groups');
	if (empty ($groups)) $groups = '0';

	$params = get_option('livechat_params');

	return array ($license_number, $lang, $groups, $params);
}

/**
 * Creates new admin menu
 */
function livechat_settings_link($links)
{
	$settings_link = sprintf( '<a href="admin.php?page=livechat_settings">%s</a>', __('Settings'));
	array_unshift ($links, $settings_link); 
	return $links;
}

function livechat_admin_menu()
{
	global $wp_registered_sidebars;
	define('LIVECHAT_WIDGETS_ENABLED', (bool)(sizeof($wp_registered_sidebars) > 0));


	add_menu_page ('Live chat settings', 'Live chat', 'administrator', 'livechat_settings', 'livechat_settings', LIVECHAT_PLUGIN_URL.'/images/favicon.png');
	add_submenu_page ('livechat_settings', 'Live chat settings', 'Settings', 'administrator', 'livechat_settings', 'livechat_settings');
	if (LIVECHAT_LICENSE_INSTALLED && LIVECHAT_WIDGETS_ENABLED == false) add_submenu_page ('livechat_settings', 'Chat button', 'Chat button', 'administrator', 'livechat_chat_button', 'livechat_chat_button');
	add_submenu_page ('livechat_settings', 'Control Panel', 'Control Panel', 'administrator', 'livechat_control_panel', 'livechat_control_panel');

	// Settings link
	$plugin = plugin_basename(__FILE__);
	add_filter( 'plugin_action_links_'.$plugin, 'livechat_settings_link');
}

add_action ('admin_head', 'livechat_admin_head');
add_action ('admin_footer', 'livechat_admin_footer');
add_action ('admin_menu', 'livechat_admin_menu');
add_action ('admin_init', 'livechat_admin_register_settings');

//
// Main settings page
//

function livechat_settings()
{
	require_once (dirname(__FILE__).'/plugin_files/settings.php');

	_livechat_settings();
}

//
// Control panel page
//

function livechat_control_panel()
{
   echo '<iframe id="control_panel" src="https://panel.livechatinc.com/" frameborder="0"></iframe>';
   echo '<p>Optionally, open the Control Panel in <a href="https://panel.livechatinc.com/" target="_blank">external window</a>.</p>';
}


//
// Monitoring code installation
//

function livechat_monitoring_code()
{
	require_once (dirname(__FILE__).'/plugin_files/monitoring_code.php');

	list ($license_number, $lang, $groups, $params) = livechat_read_options();

	_livechat_monitoring_code ($license_number, $lang, $groups, $params);
}

add_action ('wp_head', 'livechat_monitoring_code');


//
// Chat button info page
//

function livechat_chat_button()
{
	require_once (dirname(__FILE__).'/plugin_files/chat_button.php');

	list ($license_number, $lang, $groups, $params) = livechat_read_options();

	_livechat_chat_button_code ($license_number, $lang, $groups);
}

//
// Chat button Widget
//

function livechat_chat_button_widget()
{
	require_once (dirname(__FILE__).'/plugin_files/chat_button.php');

	list ($license_number, $lang, $groups, $params) = livechat_read_options();

	_livechat_chat_button_widget ($license_number, $lang, $groups);
}

function livechat_chat_button_widget_control()
{
	require_once (dirname(__FILE__).'/plugin_files/chat_button.php');

	list ($license_number, $lang, $groups, $params) = livechat_read_options();

	_livechat_chat_button_widget_control ($license_number, $lang, $groups);
}


wp_register_sidebar_widget ('livechat_widget', 'Live chat for Wordpress', 'livechat_chat_button_widget');
wp_register_widget_control ('livechat_widget', 'Live chat for Wordpress', 'livechat_chat_button_widget_control');
