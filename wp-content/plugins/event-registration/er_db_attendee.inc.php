<?php

/**
 * @author David Fleming
 * @copyright 2010
 */

function events_attendee_tbl_install () {
//Define global variables
            global $wpdb;
            global $events_attendee_tbl_version;
//Create new variables for this function            
            $table_name = $wpdb->prefix . "events_attendee";
            $events_attendee_tbl_version = "5.31";
//check the SQL database for the existence of the Event Attendee Database - if it does not exist create it.            
            if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                $sql = "CREATE TABLE " . $table_name . " (
					  id int(10) unsigned NOT NULL AUTO_INCREMENT,
					  lname VARCHAR(45) DEFAULT NULL,
					  fname VARCHAR(45) DEFAULT NULL,
					  address VARCHAR(45) DEFAULT NULL,
					  city VARCHAR(45) DEFAULT NULL,
					  state VARCHAR(45) DEFAULT NULL,
					  zip VARCHAR(45) DEFAULT NULL,
					  num_people VARCHAR (45) DEFAULT NULL,
                      add_names VARCHAR (300) DEFAULT NULL,
					  email VARCHAR(65) DEFAULT NULL,
					  phone VARCHAR(45) DEFAULT NULL,
					  hear VARCHAR(45) DEFAULT NULL,
					  payment VARCHAR(45) DEFAULT NULL,
					  date timestamp NOT NULL default CURRENT_TIMESTAMP,
					  paystatus VARCHAR(45) DEFAULT NULL,
					  txn_type VARCHAR(45) DEFAULT NULL,
					  txn_id VARCHAR(45) DEFAULT NULL,
					  amount_pd VARCHAR(45) DEFAULT NULL,
					  paydate VARCHAR(45) DEFAULT NULL,
                      coupon VARCHAR(45) DEFAULT NULL,
					  quantity VARCHAR(45) DEFAULT NULL,
					  event_id VARCHAR(45) DEFAULT NULL,
					  UNIQUE KEY id (id)
					);";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);

        //create option in the wordpress options tale for the event attendee table name
				$option_name = 'events_attendee_tbl' ;
				$newvalue = $table_name;
				  if ( get_option($option_name) ) {
					    update_option($option_name, $newvalue);
					  } else {
					    $deprecated=' ';
					    $autoload='no';
					    add_option($option_name, $newvalue, $deprecated, $autoload);
				  }
		

        //create option in the wordpress options table for the event attendee table version
				$option_name = 'events_attendee_tbl_version' ;
				$newvalue = $events_attendee_tbl_version;
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
// Retrieve the installed version of the events attendee table and assign a variable		 
		 $installed_ver = get_option( "events_attendee_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below.
	     if( $installed_ver != $events_attendee_tbl_version ) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id int(10) unsigned NOT NULL AUTO_INCREMENT,
					  lname VARCHAR(45) DEFAULT NULL,
					  fname VARCHAR(45) DEFAULT NULL,
					  address VARCHAR(45) DEFAULT NULL,
					  city VARCHAR(45) DEFAULT NULL,
					  state VARCHAR(45) DEFAULT NULL,
					  zip VARCHAR(45) DEFAULT NULL,
					  num_people VARCHAR (45) DEFAULT NULL,
                      add_names VARCHAR (300) DEFAULT NULL,
					  email VARCHAR(65) DEFAULT NULL,
					  phone VARCHAR(45) DEFAULT NULL,
					  hear VARCHAR(45) DEFAULT NULL,
					  payment VARCHAR(45) DEFAULT NULL,
					  date timestamp NOT NULL default CURRENT_TIMESTAMP,
					  paystatus VARCHAR(45) DEFAULT NULL,
					  txn_type VARCHAR(45) DEFAULT NULL,
					  txn_id VARCHAR(45) DEFAULT NULL,
					  amount_pd VARCHAR(45) DEFAULT NULL,
					  paydate VARCHAR(45) DEFAULT NULL,
                      coupon VARCHAR(45) DEFAULT NULL,
					  quantity VARCHAR(45) DEFAULT NULL,
					  event_id VARCHAR(45) DEFAULT NULL,
					  UNIQUE KEY id (id)
					);";
	      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	      dbDelta($sql);
//update the table version number to match the updated sql
	      update_option( "events_attendee_tbl_version", $events_attendee_tbl_version );
	      }
}
?>