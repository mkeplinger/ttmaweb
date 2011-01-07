<?php

/**
 * @author David Fleming
 * @copyright 2010
 */

function events_payment_transactions_tbl_install  () {
//Define global variables
	   global $wpdb;
	   global $events_payment_transactions_tbl_version;
//Create new variables for this function  
	   $table_name = $wpdb->prefix . "events_payment_transactions";
	   $events_payment_transactions_tbl_version = "5.31";
//check the SQL database for the existence of the Event Attendee Database - if it does not exist create it. 
	   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			   $sql = "CREATE TABLE " . $table_name . " (
				  id int(10) NOT NULL AUTO_INCREMENT,
				  payer_id varchar(15) NOT NULL,
                  event_id varchar (15) NOT NULL,
				  payment_date varchar(30) DEFAULT NULL,
				  txn_id varchar(20) NOT NULL,
				  first_name varchar(50) NOT NULL,
				  last_name varchar(50) NOT NULL,
				  payer_email varchar(100) NOT NULL,
				  payer_status varchar(10) NOT NULL,
				  payment_type varchar(20) NOT NULL,
				  memo text NOT NULL,
				  item_name text NOT NULL,
				  item_number varchar(50) NOT NULL,
				  quantity int(3) NOT NULL,
				  mc_gross decimal(10,2) NOT NULL,
				  mc_currency varchar(3) NOT NULL,
				  address_name varchar(32) DEFAULT NULL,
				  address_street varchar(64) DEFAULT NULL,
				  address_city varchar(32) DEFAULT NULL,
				  address_state varchar(32) DEFAULT NULL,
				  address_zip varchar(10) DEFAULT NULL,
				  address_country varchar(64) DEFAULT NULL,
				  address_status varchar(11) DEFAULT NULL,
				  payer_business_name varchar(64) DEFAULT NULL,
				  payment_status varchar(17) NOT NULL,
				  pending_reason varchar(14) DEFAULT NULL,
				  reason_code varchar(15) DEFAULT NULL,
				  txn_type varchar(20) NOT NULL,
				  PRIMARY KEY (id)
				);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

//create option in the wordpress options tale for the event payment transaction table name
        $option_name = 'events_payment_transactions_tbl' ;
			$newvalue = $table_name;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);
			  }
              
//create option in the wordpress options table for the event payment transaction table version             
			$option_name = 'events_payment_transactions_tbl_version' ;
			$newvalue = $events_payment_transactions_tbl_version;
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
     $installed_ver = get_option( "$events_payment_transactions_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below.
     if( $installed_ver != $events_payment_transactions_tbl_version ) {

 			   $sql = "CREATE TABLE " . $table_name . " (
				  id int(10) NOT NULL AUTO_INCREMENT,
				  payer_id varchar(15) NOT NULL,
                  event_id varchar (15) NOT NULL,
				  payment_date varchar(30) DEFAULT NULL,
				  txn_id varchar(20) NOT NULL,
				  first_name varchar(50) NOT NULL,
				  last_name varchar(50) NOT NULL,
				  payer_email varchar(100) NOT NULL,
				  payer_status varchar(10) NOT NULL,
				  payment_type varchar(20) NOT NULL,
				  memo text NOT NULL,
				  item_name text NOT NULL,
				  item_number varchar(50) NOT NULL,
				  quantity int(3) NOT NULL,
				  mc_gross decimal(10,2) NOT NULL,
				  mc_currency varchar(3) NOT NULL,
				  address_name varchar(32) DEFAULT NULL,
				  address_street varchar(64) DEFAULT NULL,
				  address_city varchar(32) DEFAULT NULL,
				  address_state varchar(32) DEFAULT NULL,
				  address_zip varchar(10) DEFAULT NULL,
				  address_country varchar(64) DEFAULT NULL,
				  address_status varchar(11) DEFAULT NULL,
				  payer_business_name varchar(64) DEFAULT NULL,
				  payment_status varchar(17) NOT NULL,
				  pending_reason varchar(14) DEFAULT NULL,
				  reason_code varchar(15) DEFAULT NULL,
				  txn_type varchar(20) NOT NULL,
				  PRIMARY KEY (id)
				);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
//update the table version number to match the updated sql
      update_option( "events_payment_transactions_tbl_version", $events_payment_transactions_tbl_version );
      }

}
?>