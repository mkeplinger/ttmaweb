<?php

/**
 * @author David Fleming
 * @copyright 2010
 */

function events_detail_tbl_install  () {
//Define global variables
	   global $wpdb;
	   global $events_detail_tbl_version;
//Create new variables for this function  
	   $table_name = $wpdb->prefix . "events_detail";
       $events_detail_tbl_version = "5.31";
//check the SQL database for the existence of the Event Details Database - if it does not exist create it.        
       if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			   $sql = "CREATE TABLE " . $table_name . " (
				  id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  event_name VARCHAR(100) DEFAULT NULL,
				  event_desc TEXT DEFAULT NULL,
				  event_location VARCHAR(300) DEFAULT NULL,
				  display_desc VARCHAR (4) DEFAULT NULL,
				  image_link VARCHAR(100) DEFAULT NULL,
				  header_image VARCHAR(100) DEFAULT NULL,
				  event_identifier VARCHAR(45) DEFAULT NULL,
				  more_info VARCHAR(100) DEFAULT NULL,
				  start_month VARCHAR (15) DEFAULT NULL,
				  start_day VARCHAR (15) DEFAULT NULL,
				  start_year VARCHAR (15) DEFAULT NULL,
                  start_time VARCHAR (15) DEFAULT NULL,
				  start_date VARCHAR (15) DEFAULT NULL,
				  end_month VARCHAR (15) DEFAULT NULL,
				  end_day VARCHAR (15) DEFAULT NULL,
				  end_year VARCHAR (15) DEFAULT NULL,
				  end_date VARCHAR (15) DEFAULT NULL,
				  end_time VARCHAR (15) DEFAULT NULL,
				  reg_limit VARCHAR (15) DEFAULT NULL,
				  event_cost decimal(7,2) DEFAULT NULL,
				  custom_cur VARCHAR(10) DEFAULT NULL,
				  multiple VARCHAR(45) DEFAULT NULL,
                  reg_form_defaults VARCHAR(100) DEFAULT NULL,
				  allow_checks VARCHAR(45) DEFAULT NULL,
				  send_mail VARCHAR (2) DEFAULT NULL,
				  is_active VARCHAR(45) DEFAULT NULL,
				  conf_mail VARCHAR (1000) DEFAULT NULL,
                  use_coupon VARCHAR(1) DEFAULT NULL,
				  coupon_code VARCHAR(50) DEFAULT NULL,
				  coupon_code_price decimal(7,2) DEFAULT NULL,
				  use_percentage VARCHAR(1) DEFAULT NULL,
				  category_id TEXT,
				  UNIQUE KEY id (id)
				);";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

        //create option for table name
			$option_name = 'events_detail_tbl' ;
			$newvalue = $table_name;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);
			  }
        //create option for table version
			$option_name = 'events_detail_tbl_version' ;
			$newvalue = $events_detail_tbl_version;
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
// Retrieve the installed version of the events detail table and assign a variable	 
     $installed_ver = get_option( "$events_detail_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below.    
     if( $installed_ver != $events_detail_tbl_version ) {
 			   $sql = "CREATE TABLE " . $table_name . " (
				  id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  event_name VARCHAR(100) DEFAULT NULL,
				  event_desc TEXT DEFAULT NULL,
				  event_location VARCHAR(300) DEFAULT NULL,
				  display_desc VARCHAR (4) DEFAULT NULL,
				  image_link VARCHAR(100) DEFAULT NULL,
				  header_image VARCHAR(100) DEFAULT NULL,
				  event_identifier VARCHAR(45) DEFAULT NULL,
				  more_info VARCHAR(100) DEFAULT NULL,
				  start_month VARCHAR (15) DEFAULT NULL,
				  start_day VARCHAR (15) DEFAULT NULL,
				  start_year VARCHAR (15) DEFAULT NULL,
                  start_time VARCHAR (15) DEFAULT NULL,
				  start_date VARCHAR (15) DEFAULT NULL,
				  end_month VARCHAR (15) DEFAULT NULL,
				  end_day VARCHAR (15) DEFAULT NULL,
				  end_year VARCHAR (15) DEFAULT NULL,
				  end_date VARCHAR (15) DEFAULT NULL,
				  end_time VARCHAR (15) DEFAULT NULL,
				  reg_limit VARCHAR (15) DEFAULT NULL,
				  event_cost decimal(7,2) DEFAULT NULL,
				  custom_cur VARCHAR(10) DEFAULT NULL,
				  multiple VARCHAR(45) DEFAULT NULL,
                  reg_form_defaults VARCHAR(100) DEFAULT NULL,
				  allow_checks VARCHAR(45) DEFAULT NULL,
				  send_mail VARCHAR (2) DEFAULT NULL,
				  is_active VARCHAR(45) DEFAULT NULL,
				  conf_mail VARCHAR(1000) DEFAULT NULL,
                  use_coupon VARCHAR(1) DEFAULT NULL,
				  coupon_code VARCHAR(50) DEFAULT NULL,
				  coupon_code_price decimal(7,2) DEFAULT NULL,
				  use_percentage VARCHAR(1) DEFAULT NULL,
				  category_id TEXT,
				  UNIQUE KEY id (id)
				);";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
//update the table version number to match the updated sql
      update_option( "events_detail_tbl_version", $events_detail_tbl_version );
      }
}
?>