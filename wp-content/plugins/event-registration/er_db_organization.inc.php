<?php

/**
 * @author David Fleming
 * @copyright 2010
 */

function events_organization_tbl_install () {
//Define global variables
        global $wpdb;
        global $events_organization_tbl_version;
//Create new variables for this function 
        $table_name = $wpdb->prefix . "events_organization";
        $events_organization_tbl_version = "5.32";
//check the SQL database for the existence of the Event Attendee Database - if it does not exist create it. 
	   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
				  id int(10) unsigned NOT NULL auto_increment,
				  organization varchar(45) default NULL,
				  organization_street1 varchar(45) default NULL,
				  organization_street2 varchar(45) default NULL,
				  organization_city varchar(45) default NULL,
				  organization_state varchar(45) default NULL,
				  organization_zip varchar(45) default NULL,
				  contact_email varchar(55) default NULL,
                  show_thumb varchar (5) default NULL,
                  payment_vendor varchar(100) default NULL,
				  payment_vendor_id varchar(55) default NULL,
                  txn_key varchar(45) default NULL,
				  currency_format varchar(45) default NULL,
                  accept_donations varchar (4) default NULL,
                  events_listing_type varchar(45) default NULL,
                  default_mail varchar(2) default NULL,
				  message varchar(500) default NULL,
                  return_url varchar(100) default NULL,
				  cancel_return varchar(100) default NULL,
				  notify_url varchar(100) default NULL,
				  return_method varchar(100) default NULL,
                  use_sandbox varchar(1) default 0,
                  calendar_url varchar(100) default NULL,
                  payment_subj varchar (250) default NULL,
                  payment_message varchar (1000) default NULL,
                  image_url varchar(100) default NULL,
                  captcha varchar (5) default NULL,
				  UNIQUE KEY id (id)
				);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
//Create variables to add default organization data to table
			$message=("Enter your custom confirmation message here.");
            $your_company = get_bloginfo('name');
            $your_email = get_bloginfo('admin_email');
            $message=("<p>***This is an automated response - Do Not Reply***</p> <p>Thank you [fname] [lname] for registering for [event].</p> <p> We hope that you will find this event both informative and enjoyable.");
            $payment_subj =("Payment Received");
            $payment_message = ("<p>***This is an automated response - Do Not Reply***</p> <p>Thank you [fname] [lname] for registering for [event].</p> <p> We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p> <p>If you have not done so already, please submit your payment in the amount of [cost].</p> <p>Click here to reveiw your payment information [payment_url].</p><p>Thank You.</p>");

			$sql="INSERT into $table_name (organization, contact_email, default_mail, message, payment_subj, payment_message, captcha) values 
                ('".$your_company."', '".$your_email."','Y', '".$message."','".$payment_subj."', '".$payment_message."','Y')";
			$wpdb->query($sql);
		
        //create option for table name
			$option_name = 'events_organization_tbl' ;
			$newvalue = $table_name;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);
			  }

        //create option for table version
			$option_name = 'events_organization_tbl_version' ;
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
     $installed_ver = get_option( "events_organization_tbl_version" );
//check the installed version to the version defined tin the top of this file, if version is different - update to the sql structure below.
     if( $installed_ver != $events_organization_tbl_version ) {

			$sql = "CREATE TABLE " . $table_name . " (
				  id int(10) unsigned NOT NULL auto_increment,
				  organization varchar(45) default NULL,
				  organization_street1 varchar(45) default NULL,
				  organization_street2 varchar(45) default NULL,
				  organization_city varchar(45) default NULL,
				  organization_state varchar(45) default NULL,
				  organization_zip varchar(45) default NULL,
				  contact_email varchar(55) default NULL,
                  show_thumb varchar (5) default NULL,
                  payment_vendor varchar(100) default NULL,
				  payment_vendor_id varchar(55) default NULL,
                  txn_key varchar(45) default NULL,
				  currency_format varchar(45) default NULL,
                  accept_donations varchar (4) default NULL,
                  events_listing_type varchar(45) default NULL,
                  default_mail varchar(2) default NULL,
				  message varchar(500) default NULL,
                  return_url varchar(100) default NULL,
				  cancel_return varchar(100) default NULL,
				  notify_url varchar(100) default NULL,
				  return_method varchar(100) default NULL,
				  use_sandbox varchar(1) default 0,
                  calendar_url varchar(100) default NULL,
                  payment_subj varchar (250) default NULL,
                  payment_message varchar (1000) default NULL,
                  image_url varchar(100) default NULL,
                  captcha varchar (5) default NULL,
				  UNIQUE KEY id (id)
				);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
//Create variables to add default organization data to table
		/*	$message=("Enter your custom confirmation message here.");
            $your_company = get_bloginfo('name');
            $your_email = get_bloginfo('admin_email');
            $message=("Enter your custom confirmation message here.");
            $payment_subj =("Payment Received");
            $payment_message = ("<p>***This is an automated response - Do Not Reply***</p> <p>Thank you [fname] [lname] for registering for [event].</p> <p> We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p> <p>If you have not done so already, please submit your payment in the amount of [cost].</p> <p>Click here to reveiw your payment information [payment_url].</p><p>Thank You.</p>");
*/

            //Get paypal id and update vendor id and set to paypal
            $sql="SELECT paypal_id FROM $table_name WHERE id = '1'";
            $result = mysql_query($sql);
		   	while ($row = mysql_fetch_assoc ($result)){$paypal_id = $row['paypal_id'];}

            if ($paypal_id != ""){
			$sql="UPDATE $table_name set payment_vendor_id = '$paypal_id', payment_vendor = 'PAYPAL' WHERE id = 1";
			$wpdb->query($sql);
            }
            
            $sql="SELECT captcha FROM $table_name WHERE id = '1'";
            $result = mysql_query($sql);
		   	while ($row = mysql_fetch_assoc ($result)){$captcha = $row['captcha'];}
            
            if ($captcha != ""){
			$sql="UPDATE $table_name set captcha = 'Y' WHERE id = 1";
			$wpdb->query($sql);
            }
//update the table version number to match the updated sql
      update_option( "events_organization_tbl_version", $events_organization_tbl_version );
      }
}
?>