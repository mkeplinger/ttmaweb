<?php

/**
 * @author David Fleming
 * @copyright 2010
 */
//create question and answer tables
function events_question_tbl_install() {
//Define global variables
   global $wpdb;
   global $events_question_tbl_version;
//Create new variables for this function   
   $table_name = $wpdb->prefix . "events_question_tbl";
   $events_question_tbl_version = "5.31";
//check the SQL database for the existence of the Event Question Table - if it does not exist create it. 
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
			id int(11) unsigned NOT NULL auto_increment,
			event_id int(11) NOT NULL default '0',
			sequence int(11) NOT NULL default '0',
			question_type enum('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN') NOT NULL default 'TEXT',
			question text NOT NULL,
			response text NOT NULL,
			required ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N',
			PRIMARY KEY  (id)
			);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
//create option in the wordpress options tale for the event question table name
    	$option_name = 'events_question_tbl' ;
    	$newvalue = $table_name;
     	if ( get_option($option_name) ) {
    	   update_option($option_name, $newvalue);
    	} else {
    	   $deprecated=' ';
    	   $autoload='no';
    	   add_option($option_name, $newvalue, $deprecated, $autoload);
    	} 
    //create option in the wordpress options table for the event question table version
    	$option_name = 'events_question_tbl_version' ;
    	$newvalue = $events_question_tbl_version;
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
    $installed_ver = get_option( "events_question_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below.    
    if( $installed_ver != $events_question_tbl_version ) {
			$sql = "CREATE TABLE " . $table_name . " (
			id int(11) unsigned NOT NULL auto_increment,
			event_id int(11) NOT NULL default '0',
			sequence int(11) NOT NULL default '0',
			question_type enum('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN') NOT NULL default 'TEXT',
			question text NOT NULL,
			response text NOT NULL,
			required ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N',
			PRIMARY KEY  (id)
			);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
//update the table version number to match the updated sql
    update_option( "events_question_tbl_version", $events_question_tbl_version );
    }
}
//
//Create the table for the answers for the questions
function events_answer_tbl_install() {
//Define global variables
   global $wpdb;
   global $events_answer_tbl_version;
//Create new variables for this function    
    $table_name = $wpdb->prefix . "events_answer_tbl";
    $events_answer_tbl_version = "5.31";
//check the SQL database for the existence of the Event Answer Database - if it does not exist create it. 
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
			registration_id int(11) NOT NULL default '0',
			question_id int(11) NOT NULL default '0',
			answer text NOT NULL,
			PRIMARY KEY  (registration_id, question_id)
			);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
//create option in the wordpress options tale for the event answer table name
		$option_name = 'events_answer_tbl' ;
    	$newvalue = $table_name;
     	if ( get_option($option_name) ) {
    	   update_option($option_name, $newvalue);
    	} else {
    	   $deprecated=' ';
    	   $autoload='no';
    	   add_option($option_name, $newvalue, $deprecated, $autoload);
    	} 
   //create option in the wordpress options table for the event answer table version    
        $option_name = 'events_answer_tbl_version' ;
    	$newvalue = $events_question_tbl_version;
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
    $installed_ver = get_option( "events_answer_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below. 
    if( $installed_ver != $events_answer_tbl_version ) {
	$sql = "CREATE TABLE " . $table_name . " (
			registration_id int(11) NOT NULL default '0',
			question_id int(11) NOT NULL default '0',
			answer text NOT NULL,
			PRIMARY KEY  (registration_id, question_id)
			);";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
//update the table version number to match the updated sql
    update_option( "events_answer_tbl_version", $events_answer_tbl_version );
    }
}
?>