<?php
/*
Plugin Name: Custom Field Matrix
Plugin URI: http://plugins.elliotcondon.com/custom-field-matrix/
Description: Custom Field Matrix is the perfect solution for any photo gallery, home page boxes, or any loop based content. Custom Field Matrix allows you to escape the static WordPress custom field nature and create infinite possibilities or data matries.
Version: 1.1
Author: Elliot Condon
Author URI: http://www.elliotcondon.com/
License: GPL
Copyright: Elliot Condon
*/


include('core/api.php');
include('core/matrix.php');

$cf_matrix = new Cf_matrix();

class Cf_matrix
{ 
	var $name;
	var $dir;
	var $path;
	var $siteurl;
	var $wpadminurl;
	var $version;
	
	var $matrix_post_type;
	
	function Cf_matrix()
	{
		
		// set class variables
		$this->name = 'Custom Field Matrix';
		$this->path = dirname(__FILE__).'';
		$this->dir = plugins_url('',__FILE__);
		$this->siteurl = get_bloginfo('url');
		$this->wpadminurl = admin_url();
		$this->version = '1.1';
		
		load_plugin_textdomain('cfm', false, $this->path.'/lang' );
		
		$this->matrix_post_type = new Matrix_post_type($this);
	
		add_action('admin_menu', array($this,'admin_menu'));
		add_action('admin_footer-edit.php', array($this,'admin_footer'));
		
		register_activation_hook(__FILE__, array($this,'activate'));
		
		return true;
	}


	/*---------------------------------------------------------------------------------------------
	 * Admin Menu
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function admin_menu() {
	
		
		add_submenu_page('options-general.php', __('CF Matrix','cfm'), __('Custom Field Matrix','cfm'), 'manage_options','edit.php?post_type=cf_matrix');

		global $menu;
		//global $submenu;
	
		$restricted = array('Custom&nbsp;Field&nbsp;Matrix');
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
		
		//unset($submenu['edit.php?post_type=cf_matrix'][10]);
	}
	
	/*---------------------------------------------------------------------------------------------
	 * admin_head
	 *
	 * @author Elliot Condon
	 * @since 1.0.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function admin_footer()
	{
		if($_GET['post_type'] != 'cf_matrix'){return false;}
		
		echo '<link rel="stylesheet" href="'.$this->dir.'/css/cf_matrix_admin.css" type="text/css" media="all" />';
		echo '<script type="text/javascript" src="'.$this->dir.'/js/admin.js"></script>';
		include('core/meta_box_4.php');
	}
	
	/*---------------------------------------------------------------------------------------------
	 * activate
	 *
	 * @author Elliot Condon
	 * @since 1.0.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function activate()
	{
		include('core/update.php');
	}
	
	
}