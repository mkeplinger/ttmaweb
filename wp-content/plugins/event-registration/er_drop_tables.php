<?php

/**
 * @author David Fleming
 * @copyright 2010
 */
 

function er_pluginUninstall() {  

global $wpdb;  
//Drop Organization Table
$thetable = $wpdb->prefix."events_organization";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_organization_tbl');
delete_option('events_organization_tbl_version');  

//Drop Events Detail Table
$thetable = $wpdb->prefix."events_detail";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_detail_tbl');
delete_option('events_detail_tbl_version'); 

//Drop Events Attendee Table
$thetable = $wpdb->prefix."events_attendee";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_attendee_tbl');
delete_option('events_attendee_tbl_version'); 

//Drop Events Category Table
$thetable = $wpdb->prefix."events_cat_detail_tbl";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_cat_detail_tbl');
delete_option('events_cat_detail_tbl_version'); 

//Drop Events Payment Table
$thetable = $wpdb->prefix."events_payment_transactions";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_payment_transactions_tbl');
delete_option('events_payment_transactions_tbl_version'); 

//Drop Events Question Table
$thetable = $wpdb->prefix."events_question_tbl";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_question_tbl');
delete_option('events_question_tbl_version'); 

//Drop Events Answer Table
$thetable = $wpdb->prefix."events_answer_tbl";  
$wpdb->query("DROP TABLE IF EXISTS $thetable");
delete_option('events_answer_tbl');
delete_option('events_answer_tbl_version'); 


//Remove other Events Registration Options
delete_option('all_events_sample_page_id'); 
delete_option('er_link_for_calendar_url'); 
delete_option('single_event_sample_page_id'); 
delete_option('category_sample_page_id'); 
delete_option('current_event'); 
delete_option('payment_vendor_id'); 
delete_option('show_thumb'); 
delete_option('er_link_for_calendar_url'); 
delete_option('currency_format');
delete_option('events_listing_type');
delete_option('return_url');
delete_option('cancel_return');
delete_option('notify_url');
delete_option('return_method');
delete_option('use_sandbox');
delete_option('image_url');
delete_option('registrar');
delete_option('currency_format');
delete_option('attendee_first');
delete_option('attendee_last');
delete_option('attendee_name');
delete_option('attendee_email');
delete_option('attendee_id');

} 
?>