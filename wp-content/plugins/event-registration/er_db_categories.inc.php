<?php

/**
 * @author David Fleming
 * @copyright 2010
 */

function events_cat_detail_tbl_install() {
//Define global variables
		   global $wpdb;
		   global $events_cat_detail_tbl_version;
//Create new variables for this function 			
		   $table_name = $wpdb->prefix . "events_cat_detail_tbl";
		   $events_cat_detail_tbl_version = "5.35";
//check the SQL database for the existence of the Event Category Table - if it does not exist create it.     	
		   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				   $sql = "CREATE TABLE " . $table_name . " (
					  id int(10) unsigned NOT NULL AUTO_INCREMENT,
					  category_name VARCHAR(100) DEFAULT NULL,
					  category_identifier VARCHAR(45) DEFAULT NULL,
					  category_desc TEXT,
					  display_desc VARCHAR (4) DEFAULT NULL,
                      category_color VARCHAR(30) NOT NULL ,
                      font_color VARCHAR(30) NOT NULL DEFAULT '#000000',
					   UNIQUE KEY id (id)
					);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
//create option in the wordpress options table for the event category table 
				$option_name = 'events_cat_detail_tbl' ;
				$newvalue = $table_name;
				  if ( get_option($option_name) ) {
						update_option($option_name, $newvalue);
					  } else {
						$deprecated=' ';
						$autoload='no';
						add_option($option_name, $newvalue, $deprecated, $autoload);
				  }	
//create option in the wordpress options table for the event attendee table version
				$option_name = 'events_cat_detail_tbl_version' ;
				$newvalue = $events_cat_detail_tbl_version;
				  if ( get_option($option_name) ) {
						update_option($option_name, $newvalue);
					  } else {
						$deprecated=' ';
						$autoload='no';
						add_option($option_name, $newvalue, $deprecated, $autoload);
				  }
        }
// Code here with new database upgrade info/table Must change version number to work.
// Note: SQL syntex should be the same in both places to ensure new table/ table update match.
// Retrieve the installed version of the events category table and assign a variable	
		 $installed_ver = get_option( "events_cat_detail_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below.
		 if( $installed_ver != $events_cat_detail_tbl_version ) {
	
				   $sql = "CREATE TABLE " . $table_name . " (
					  id int(10) unsigned NOT NULL AUTO_INCREMENT,
					  category_name VARCHAR(100) DEFAULT NULL,
					  category_identifier VARCHAR(45) DEFAULT NULL,
					  category_desc TEXT,
					  display_desc VARCHAR (4) DEFAULT NULL,
                      category_color VARCHAR(30) NOT NULL ,
                      font_color VARCHAR(30) NOT NULL DEFAULT '#000000',
					   UNIQUE KEY id (id)
					);";
	
		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		  dbDelta($sql);
//update the table version number to match the updated sql		  
		  update_option( "events_cat_detail_tbl_version", $events_cat_detail_tbl_version );
		  }
		
		
}	
?>