<?php

/**
 * @author David Fleming
 * @copyright 2010
 */
  // database changes between 4.0 and 5.0 
function alter_event_regis_tables(){
 // for version 5.0 and higher   
// Add columns coupon VARCHAR(45) DEFAULT NULL, 
//add column quantity VARCHAR(45) DEFAULT NULL,
$installed_ver = get_option( "events_attendee_tbl_version" );
$table_name = $wpdb->prefix . "events_attendee";
if ($installed_ver <= "4.9"){
    echo "<BR>Modifying Event Attendee Table!<br>";
        $table_name = $wpdb->prefix . "events_attendee"; 			
        $sql = "ALTER TABLE $table_name ADD coupon VARCHAR(45) DEFAULT NULL";
        $wpdb->query ( '$sql' ) or die(mysql_error()) ;
        $sql = "ALTER TABLE $table_name  ADD add_names VARCHAR (300) DEFAULT NULL"; 
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD quantity VARCHAR(45) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error()); 

        //add line to update options
	$option_name = 'events_attendee_tbl_version' ;
	$newvalue = "5.0";
	if ( get_option($option_name) ) {
	    update_option($option_name, $newvalue);
	    } else {
	    $deprecated=' ';
	    $autoload='no';
	    add_option($option_name, $newvalue, $deprecated, $autoload);
	    }
}

$installed_ver = get_option( "events_detail_tbl_version" );
$table_name = $wpdb->prefix . "events_detail";
if ($installed_ver <= "4.9"){
        /*		 
		change from event_desc VARCHAR(500) DEFAULT NULL  to event_desc TEXT DEFAULT NULL,
		change from event_location VARCHAR(100) DEFAULT NULL to event_location VARCHAR(300) DEFAULT NULL
		change from	start_date DATE DEFAULT NULL to	start_date VARCHAR (15) DEFAULT NULL,
		change from end_date DATE DEFAULT NULL to  end_date VARCHAR (15) DEFAULT NULL,
		change from event_cost VARCHAR(45) DEFAULT NULL to 	 event_cost decimal(7,2) DEFAULT NULL,	  
		drop column question1 VARCHAR(200) DEFAULT NULL,
		drop column	question2 VARCHAR(200) DEFAULT NULL,
		drop column	question3 VARCHAR(200) DEFAULT NULL,
		drop column	question4 VARCHAR(200) DEFAULT NULL,	  
		ADD reg_form_defaults VARCHAR(100) DEFAULT NULL,
        ADD use_coupon VARCHAR(1) DEFAULT NULL,
		ADD coupon_code VARCHAR(50) DEFAULT NULL,
		ADD coupon_code_price decimal(7,2) DEFAULT NULL,
		ADD use_percentage VARCHAR(1) DEFAULT NULL,
		ADD category_id TEXT,
		*/
        echo "<BR>Modifying Events Detail Table!<br>";
        $sql = "ALTER TABLE $table_name CHANGE event_desc event_desc TEXT DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name CHANGE event_location event_location VARCHAR(300) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name CHANGE start_date start_date VARCHAR (15) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name CHANGE end_date end_date VARCHAR (15) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error()); 
        $sql = "ALTER TABLE $table_name CHANGE event_cost event_cost decimal(7,2) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name DROP question1";
	    $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name DROP question2";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name DROP question3";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name DROP question4";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD reg_form_defaults VARCHAR(100) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD use_coupon VARCHAR(1) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD coupon_code VARCHAR(50) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());    
        $sql = "ALTER TABLE $table_name ADD coupon_code_price decimal(7,2) DEFAULT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD use_percentage VARCHAR(1) DEFAULT NULL";  
        $wpdb->query ( '$sql' )  or die(mysql_error());     
        $sql = "ALTER TABLE $table_name ADD category_id TEXT DEFAULT NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error());

       //add line to update options
	$option_name = 'events_detail_tbl_version' ;
	$newvalue = "5.0";
	if ( get_option($option_name) ) {
	    update_option($option_name, $newvalue);
	    } else {
	    $deprecated=' ';
	    $autoload='no';
	    add_option($option_name, $newvalue, $deprecated, $autoload);
	    }	
	
}

//$sql = "ALTER TABLE `$table_name` modify column $column_name AFTER $column_name_next_to";        
        
        

          
  
$installed_ver = get_option( "events_organization_tbl_version" );
$table_name = $wpdb->prefix . "events_organization";
if ($installed_ver <= "4.9"){
    echo "<BR>Modifying Event Organization Table!<br>";
    /*
    
    rename column paypal_id varchar(55) default NULL to payment_vendor_id varchar(55) default NULL,
    ADD show_thumb varchar (5) default NULL,
    ADD payment_vendor varchar(100) default NULL,
    ADD txn_key varchar(45) default NULL,
    ADD accept_donations varchar (4) default NULL,			 
    ADD calendar_url varchar(100) default NULL,
    ADD payment_subj varchar (250) default NULL,
    add colum payment_message varchar (1000) default NULL,
    */
  
        $sql = "ALTER TABLE $table_name CHANGE  paypal_id payment_vendor_id varchar(55) default NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD show_thumb varchar (5) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error()); 
        $sql = "ALTER TABLE $table_name ADD payment_vendor varchar(100) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD txn_key varchar(45) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD accept_donations varchar (4) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD calendar_url varchar(100) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error());        
        $sql = "ALTER TABLE $table_name ADD payment_subj varchar (250) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name ADD payment_message varchar (1000) default NULL";       
        $wpdb->query ( '$sql' )  or die(mysql_error()); 
  
         //add line to update options
	$option_name = 'events_organization_tbl_version' ;
	$newvalue = "5.0";
	if ( get_option($option_name) ) {
	    update_option($option_name, $newvalue);
	    } else {
	    $deprecated=' ';
	    $autoload='no';
	    add_option($option_name, $newvalue, $deprecated, $autoload);
	    }
  
  
  
  } 
  
$installed_ver = get_option( "events_question_tbl_version" );       
$table_name = $wpdb->prefix . "events_question_tbl";
if ($installed_ver <= "4.9"){

/*    
    CHANGE question tinytext NOT NULL to question text NOT NULL
    CHANGE response tinytext NOT NULL to response text NOT NULL		
*/
echo "<BR>Modifying Event Question Table!<br>";
        $sql = "ALTER TABLE $table_name CHANGE  question question text NOT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());
        $sql = "ALTER TABLE $table_name CHANGE  response  response text NOT NULL";
        $wpdb->query ( '$sql' )  or die(mysql_error());

	       //add line to update options
	$option_name = 'events_question_tbl_version' ;
	$newvalue = "5.0";
	if ( get_option($option_name) ) {
	    update_option($option_name, $newvalue);
	    } else {
	    $deprecated=' ';
	    $autoload='no';
	    add_option($option_name, $newvalue, $deprecated, $autoload);
	    }
	}          
                                
}

?>