<?php
/*
Plugin Name: Events Registration
Plugin URI: http://www.edgetechweb.com
Description: This wordpress plugin is designed to run on a Wordpress webpage and provide registration for an event or class. It allows you to capture the registering persons contact information to a database and provides an association to an events database. It provides the ability to send the register to either a Paypal, Google Pay, or Authorize.net online payment site for online collection of event fees..  Additionally it allows support for checks and cash payments.  Detailed payment management system to track and record event payments.  Reporting features provide a list of events, list of attendees, and excel export.  Events can be created in an Excel spreadsheet and uploaded via the event upload tool.  Dashboard widget allows for quick reference to events from the dashboard.  Inline menu navigation allows for ease of use.  
Version: 5.43
Author: David Fleming - Edge Technology Consulting
Author URI: http://www.edgetechweb.com
*/
/*  Copyright 2010  DAVID_FLEMING  (email : CONSULTANT@AVDUDE.COM)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/* this does not only affect language but also format of date, and which fields are displayes in the form */


define ( "EVNT_RGR_PLUGINPATH", "/" . plugin_basename ( dirname ( __FILE__ ) ) . "/" );
define ( "EVNT_RGR_PLUGINFULLURL", WP_PLUGIN_URL . EVNT_RGR_PLUGINPATH );
$url = EVNT_RGR_PLUGINFULLURL;

$events_lang_flag = "en"; //switch to en for changing language and form 

/*
function Mod_addslashes ( $string ) 
	{if (get_magic_quotes_gpc()==1) 
		{
		return ( $string );
		}
		else 
		{ 
		return ( addslashes ( $string ) );
		} 
	}
 */   
function er_plugin_menu(){
    ?>
 <ul id="eventsnav">
  <li><a href="admin.php?page=config">Configure Organization</a></li>
  <li><a href="admin.php?page=attendees">Attendees</a></li>
  <li><a href="admin.php?page=events">Events</a></li>
  <li><a href="admin.php?page=import">Import Events</a></li>
  <li><a href="admin.php?page=event_categories">Categories</a></li>
  <li><a href="admin.php?page=form">Custom Questions</a></li>
  <li><a href="admin.php?page=attendee">Payments</a></li>
  <li><a href="admin.php?page=sample">Samples</a></li>
  <li><a href="admin.php?page=support">Support</a></li>
</ul>
<?php 
}   

require_once ('admin_header.php');
require_once ('event_regis_config.php');
require_once ('er_calendar.php');
require_once ('er_followup_mail.php');
require_once ('er_sample_page.php');
require_once ('er_dashboad_widget.php');
require_once ('attendee_list_page_view.php');
require_once ('event_language-en.inc.php');
require_once ('events_install.inc.php');
require_once ('er_drop_tables.php');
require_once ('event_config_info.inc.php');
require_once ('er_admin_event_categories.php');
require_once ('event_regis_admin_config_org.php');
require_once ('er_forms.inc.php');
require_once ('csv_import.php');
require_once ('event_register_attendees.inc.php');
require_once ('event_payments.inc.php');
require_once ('event_attendee_edit.inc.php');
require_once ('event_regis_form_build.inc.php');
require_once ('event_regis_admin_questions.php');
require_once ('er_widget.inc.php');
require_once ('er_core_functions.php');
//require_once ('event_registration_forms.inc.php');
//require_once ("er_payment_functions.php");

//Install/Update Tables when plugin is activated
register_activation_hook ( __FILE__, 'events_data_tables_install' );
//register_deactivation_hook( __FILE__, 'er_pluginUninstall' );

//ADMIN MENU
add_action ('admin_head', 'admin_register_head');
add_action ('admin_menu', 'add_event_registration_menus' );
add_action ('plugins_loaded', 'init_er_widget');

// Enable the ability for the event_funct to be loaded from pages
add_filter ('the_content', 'event_regis_insert');
add_filter ('the_content', 'er_widget_insert');
add_filter ('the_content', 'event_regis_pay_insert');
add_filter ('the_content', 'event_pay_txn_insert');
add_filter ('the_content', 'evr_upcoming_event_list');

add_shortcode ('Event_Registration_Single', 'display_single_event');
add_shortcode ('EVENT_REGIS_CATEGORY', 'display_events_by_category');
add_shortcode ('ER_Widget_View', 'events_regis_widget');
add_shortcode ('Event_Registration_Calendar', 'er_calendar_display');   // also fix database
//UPDATE `wp`.`wp_posts` SET `post_content` =  '[Event_Registration_Calendar]'  WHERE `wp_posts`.`post_content` = '[Event_Registraiton_Calendar]';
//UPDATE `wp`.`wp_posts` SET `post_content` = '[Event_Registration_Calendar] <hr /> {EVENTREGIS} ' WHERE `wp_posts`.`post_content` = '[Event_Registraiton_Calendar]\r\n<hr />\r\n{EVENTREGIS}\r\n';


//Function to make compatible with Windows Servers as well as Apache

function request_uri() {
    $uri = null;    //PPAY
		  if (isset($_SERVER['REQUEST_URI'])) {
    			$uri = $_SERVER['REQUEST_URI'];
  				}
     	else {
    		if (isset($_SERVER['argv'])) {
      		$uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['argv'][0];
    		}
   			elseif (isset($_SERVER['QUERY_STRING'])) {
  				$uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
  				}
    		else {
      			$uri = $_SERVER['SCRIPT_NAME'];
    			}
			}
		return $uri;
}



// Function for Attendee List for Active Event




function event_attendee_list_run() {
	global $wpdb;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	
	$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE is_active='yes'";
	$result = mysql_query ( $sql );
        $event_id = null;   //PPAY
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$event_id = $row ['id'];
		$event_name = $row ['event_name'];
		$event_desc = $row ['event_desc'];
		echo "<h2>Attendee Listing For: <u>" . $event_name . "</u></h2>";
		echo "<p>$event_desc</p><hr />";
	}
	
	$sql = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		//$id = $row ['id'];    //PPAY: unused
		$lname = $row ['lname'];
		$fname = $row ['fname'];
		echo $fname . " " . $lname . "<br />";
	}
}



// Main Function for Script - selects what action to be taken when EVENTREGIS is run
function event_regis_run($id) {

	global $wpdb;
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );  
	$events_detail_tbl = get_option ( 'events_detail_tbl' );    
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$events_listing_type = get_option ( 'events_listing_type' );
	$event_id = $id;
	$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$events_listing_type = $row ['events_listing_type'];
	}
	
	if ($events_listing_type == "") {
		echo "<p><b>Please setup Organization in the Admin Panel!</b></p>";
	}
    
         $regevent_action = $_REQUEST ['regevent_action'];
         switch ($regevent_action) {
        	
            case "post_attendee":
               $form_token = $_REQUEST["submitted_token"] - '20';
               $valid_token = get_option('awr_form_token');
               if ($form_token == $valid_token){  
                        $new_token = $valid_token + 1;
                        update_option( 'awr_form_token', $new_token);
                        add_attendees_to_db ();
                        } 
                        else {echo "Invalid Form Submission!";}
                
                
                
            break;
            
            case "pay":
                event_regis_pay ();
            break;

            //PPAY: undefined. Check ~/events_registration/er_payment_functions.php:er_paypal_pay()
            case "paypal_txn":
                //event_regis_paypal_txn();            //PPAY: undefined.
                event_paypal_txn();     //from event_paypal_ipn.inc.php or er_core_functions.php
            break;
            
            case "register":
               register_attendees ($event_id);
            break;
            
            case "process":
            break;
            
            default:
            $none="";
            if ($event_id != ""){register_attendees($event_id);}
            else if ($events_listing_type == 'single') {register_attendees ($none);}
                else {display_all_events($none);} 
            break;
            }

}

//ADD EVENT_REGIS PLUGIN - ACTIVATED



function add_event_registration_menus() {
	
	add_menu_page ( 'Event Registration', 'Event Registration', 8, __FILE__ ,'event_main' );
	add_submenu_page ( __FILE__, 'Configure Org', 'Organization', 8, 'config', 'event_config_mnu' );
    add_submenu_page ( __FILE__, 'Event Reports', 'Attendees', 8, 'attendees', 'attendee_display_edit' );
    add_submenu_page ( __FILE__, 'Send Mail', 'Mail', 8, 'mail', 'er_mail_followup' );
	add_submenu_page ( __FILE__, 'Manage Events', 'Events', 8, 'events', 'events_management_process' );
	add_submenu_page ( __FILE__, 'Manage Event Categories', 'Categories', 8, 'event_categories', 'event_categories_config' );
    add_submenu_page ( __FILE__, 'Add Questions', 'Add Questions', 8, 'form', 'event_form_config' );
	add_submenu_page ( __FILE__, 'Event Import', 'Import Events', 8, 'import', 'events_import' );
    add_submenu_page ( __FILE__, 'Manage Payments', 'Manage Payments', 8, 'attendee', 'event_process_payments' );
    add_submenu_page ( __FILE__, 'Sample Events', 'Sample Events', 8, 'sample', 'create_events_sample_page' );
    add_submenu_page ( __FILE__, 'Support', 'Support', 8, 'support', 'event_config_info' );

}

//Event Registration Main Admin Page

//This runs the Admin reports page
function event_regis_main_mnu() {
/*  The following functions are what I wish to add to the main menu page
1. Display current count of attendees for active event (show event name, description and id)- shows by default
*/
	event_registration_reports ();

}



function event_main(){
    $url = EVNT_RGR_PLUGINFULLURL;
    er_plugin_menu();
    echo "<a href='http://www.edgetechweb.com'><img src='".$url."Images/ER_logo.png' /></a>";
}

function event_regis_drop_tables() {
    require_once ("er_drop_tables.php");
}




function event_registration_reports() {
	
	global $wpdb;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$current_event = get_option ( 'current_event' );    
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );    
	$url = EVNT_RGR_PLUGINFULLURL;    //PPAY: unused
	$sql = "SELECT * FROM " . $events_detail_tbl;
	$result = mysql_query ( $sql );
	Echo "<p align='center'><p align='left'>SELECT EVENT TO VIEW ATTENDEES:</p><table width = '400'>";
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$event_id = $row ['id'];    
		$event_name = $row ['event_name'];
		
		echo "<tr><td width='25'></td><td><form name='form' method='post' action='";
		request_uri();
		echo "'>";
		echo "<input type='hidden' name='action' value='view'>";
		echo "<input type='hidden' name='event' value='" . $row ['id'] . "'>";
		echo "<input type='SUBMIT' value='" . $event_name . "'></form></td><tr>";
	}
	echo "</table>";

	if ($_REQUEST ['action'] == 'view_list') {
		attendee_display_edit ();
	}
}

function display_all_events($event_category_id) {
	
   
    global $wpdb,$lang;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
    $events_cat_detail_tbl = get_option('events_cat_detail_tbl');
    $curdate = date ( "Y-m-j" );

	$currency_format = get_option('currency_format');
    $showthumb = get_option ('show_thumb');
    
    $category_id = null;    
    $sql = null;    
		if ($event_category_id != ""){
		   			$sql2  = "SELECT * FROM " . $events_cat_detail_tbl . " WHERE category_identifier = '".$event_category_id."'";
					$result = mysql_query($sql2);
					while ($row = mysql_fetch_assoc ($result)){
					$category_id= $row['id'];
                	$category_name=$row['category_name'];
                	$category_identifier=$row['category_identifier'];
                	$category_desc=$row['category_desc'];
                	$display_category_desc=$row['display_desc']; 
					echo "<p><b>".$category_name."</b><br>".$category_desc."</p>";
                    }
      //$sql = "SELECT * FROM " . $events_detail_tbl ." WHERE category_id LIKE '%\"$category_id\"%' AND start_date >= '".$curdate."' ORDER BY start_date";
        	$sql = "SELECT * FROM " . $events_detail_tbl ." WHERE category_id LIKE '%\"$category_id\"%' AND str_to_date(start_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')"; 
           
	
   
        }
    else {
        //$sql = "SELECT * FROM " . $events_detail_tbl ." WHERE start_date >= '".$curdate."' ORDER BY start_date";
        $sql = "SELECT * FROM " . $events_detail_tbl ." WHERE str_to_date(start_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
        }
	$result = mysql_query ( $sql );
	
	echo "<table width = '450'>";
        $month_no = $end_month_no = '01';  
        $start_date = $end_date = '';
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		            $event_id= $row['id'];
			        $event_name =  stripslashes($row ['event_name']);
					$event_identifier =  stripslashes($row ['event_identifier']); 
					$event_desc =  stripslashes($row ['event_desc']);  
					$image_link = $row ['image_link'];
					$header_image = $row ['header_image'];  
					$display_desc = $row ['display_desc']; 
					$event_location =  stripslashes($row ['event_location']);
					$more_info = $row ['more_info'];
					$reg_limit = $row ['reg_limit'];
					$event_cost = $row ['event_cost'];
					$custom_cur = $row ['custom_cur'];
					$multiple = $row ['multiple'];  
					$allow_checks = $row ['allow_checks'];  
					$is_active = $row ['is_active'];  
					$start_month = $row ['start_month'];
					$start_day = $row ['start_day'];
					$start_year = $row ['start_year'];
					$end_month = $row ['end_month'];
					$end_day = $row ['end_day'];
					$end_year = $row ['end_year'];
					$start_time = $row ['start_time'];
					$end_time = $row ['end_time'];  
					$conf_mail = stripslashes($row ['conf_mail']); 
					$send_mail = $row ['send_mail'];  
                    $use_coupon=$row ['use_coupon'];  
               		$coupon_code=$row ['coupon_code'];  
            		$coupon_code_price=$row ['coupon_code_price'];
              		$use_percentage=$row ['use_percentage'];  
              		$event_category =  unserialize($row ['category_id']);  
                    if ($row['start_date'] ==""){
						if ($start_month == "Jan"){$month_no = '01';}
						if ($start_month == "Feb"){$month_no = '02';}
						if ($start_month == "Mar"){$month_no = '03';}
						if ($start_month == "Apr"){$month_no = '04';}
						if ($start_month == "May"){$month_no = '05';}
						if ($start_month == "Jun"){$month_no = '06';}
						if ($start_month == "Jul"){$month_no = '07';}
						if ($start_month == "Aug"){$month_no = '08';}
						if ($start_month == "Sep"){$month_no = '09';}
						if ($start_month == "Oct"){$month_no = '10';}
						if ($start_month == "Nov"){$month_no = '11';}
						if ($start_month == "Dec"){$month_no = '12';}
					$start_date = $start_year."-".$month_no."-".$start_day;
                    } else {$start_date = $row['start_date'];}
                    
                    if ($row['end_date']==""){
						if ($end_month == "Jan"){$end_month_no = '01';}
						if ($end_month == "Feb"){$end_month_no = '02';}
						if ($end_month == "Mar"){$end_month_no = '03';}
						if ($end_month == "Apr"){$end_month_no = '04';}
						if ($end_month == "May"){$end_month_no = '05';}
						if ($end_month == "Jun"){$end_month_no = '06';}
						if ($end_month == "Jul"){$end_month_no = '07';}
						if ($end_month == "Aug"){$end_month_no = '08';}
						if ($end_month == "Sep"){$end_month_no = '09';}
						if ($end_month == "Oct"){$end_month_no = '10';}
						if ($end_month == "Nov"){$end_month_no = '11';}
						if ($end_month == "Dec"){$end_month_no = '12';}
					$end_date = $end_year."-".$end_month_no."-".$end_day;
                    } else {$end_date = $row['end_date'];}
                   $reg_form_defaults = unserialize($row['reg_form_defaults']);
                    
                    if ($reg_form_defaults !=""){ 
                        if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";}
                       if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";}
                      if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";}
                      if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";}
                        if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";}
                        }
   		            if ($reg_limit == ''){$reg_limit = 999;}
                    if ($event_cost == ''){$event_cost= 0;}
                    if ($coupon_code_price == ''){$coupon_code_price = 0;}
				
		          if ($event_cost == ""||$event_cost =="0"||$event_cost=="0.00"){$event_cost = "FREE";}
		          
	    
		$sql2= "SELECT SUM(num_people) FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
		$result2 = mysql_query($sql2);
                $num = 0;   
		while($row = mysql_fetch_array($result2)){$num =  $row['SUM(num_people)'];};
        
          
        
                                    
		if ($custom_cur == "" || $custom_cur == null){
		  if ($currency_format == "USD" || $currency_format == "") { $currency_format = "$"; }			
            }
        $currency_format = get_option('currency_format'); 
          
		if ($custom_cur != "" || $custom_cur != "USD"){$currency_format = $custom_cur;}
		if ($custom_cur == "USD") {$currency_format = "$";}
        
        
        $available_spaces = 0;  
		if ($reg_limit != ""){$available_spaces = $reg_limit - $num;}
	    if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){$available_spaces = "Unlimited";}




	if ($image_link == ""){
		echo "<tr><td></td><td><br><b>" . $event_name . "   </b><br>";
		echo "Location:<b>  ".$event_location."</b><br>";
		echo "Start Date:<b>  ".$start_date."</b><br>";
		echo "Start Time:<b>  ".$start_time."</b><br>";
		echo "Price:<b>  ";
		if ($event_cost != "FREE"){echo $currency_format;}
		echo " ".$event_cost."</b><br>";
		echo "Spaces Available:<b>  ".$available_spaces."</b><br>";
		if ($more_info != ""){
			echo '<a href="'.$more_info.'"> More Info...</a>';
		}
		echo "<hr></td><td>";}
	else {	
		//uncomment this line to use images on event list screen
		if ($showthumb == "Y"){echo "<tr><td width='80'><img src='".$image_link."' width='75' height='56'></td>";}
		else {echo "<tr><td></td>";}
	//	echo "<td><br><b>" . $event_name . "   </b><br>";
        echo "<td>";
        //$link = add_query_arg('regevent_action=register', '=.','name_of_event=$event_name', request_uri());
        $arr_params = array ('regevent_action' => 'register', 'event_id' => $event_id, 'name_of_event'=>$event_name);
        $link =  add_query_arg($arr_params);
        echo "<p align=left><b><a href='".$link."'>".$event_name."</a></b></p>";
		echo "Location:<b>  ".$event_location."</b><br>";
		echo "Start Date:<b>  ".$start_date."</b><br>";
		echo "Start Time:<b>  ".$start_time."</b><br>";
		echo "Price:<b>  ";
		if ($event_cost != "FREE"){echo $currency_format;}
		echo " ".$event_cost."</b><br>";
		echo "Spaces Available:<b>  ".$available_spaces."</b><br>";
			if ($more_info != ""){
			echo '<a href="'.$more_info.'"> More Info...</a>';
		}
		echo "<hr></td><td>";} 
     echo $events_lang['register'];
		echo "<form name='form' method='post' action='".request_uri()."'>";
		echo "<input type='hidden' name='regevent_action' value='register'>";
       	echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
        echo "<input type='SUBMIT' value='REGISTER'></form></td></tr>";
		// echo "<input type='SUBMIT' value='REGISTER' ONCLICK=\"return confirm('Are you sure you want to register for ".$event_name."?')\"></form>";
	}
	echo "</table>";
    
    
}

function view_attendee_list() {
	//Displays attendee information from current active event.
	global $wpdb;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	
	$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE is_active='yes'";
	$result = mysql_query ( $sql );
        $event_id = $event_name = $event_desc = $event_description = $image = $identifier = $cost = $checks = $active = $question1 = $question2 = $question3 = $question4 = null;   
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$event_id = $row ['id'];
		$event_name = $row ['event_name'];
		$event_desc = $row ['event_desc'];
		$event_description = $row ['event_desc'];
		$image = $row ['image_link'];
		$identifier = $row ['event_identifier'];
		$cost = $row ['event_cost'];
		$checks = $row ['allow_checks'];
		$active = $row ['is_active'];
		$question1 = $row ['question1'];
		$question2 = $row ['question2'];
		$question3 = $row ['question3'];
		$question4 = $row ['question4'];
	}
	
	$sql = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
	$result = mysql_query ( $sql );
	
	echo "<table>";
        $address = $city = $state = $zip = $date = $paystatus = $txn_type = $amt_pd = $date_pd = $custom1 = $custom2 = $custom3 = $custom4 = null;   
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$id = $row ['id'];
		$lname = $row ['lname'];
		$fname = $row ['fname'];
		$address = $row ['address'];
		$city = $row ['city'];
		$state = $row ['state'];
		$zip = $row ['zip'];
		$email = $row ['email'];
		$phone = $row ['phone'];
		$date = $row ['date'];
		$paystatus = $row ['paystatus'];
		$txn_type = $row ['txn_type'];
		$amt_pd = $row ['amount_pd'];
		$date_pd = $row ['paydate'];
		$event_id = $row ['event_id'];
		$custom1 = $row ['custom_1'];
		$custom2 = $row ['custom_2'];
		$custom3 = $row ['custom_3'];
		$custom4 = $row ['custom_4'];
		
		echo "<tr><td align='left'>" . $lname . ", " . $fname . "</td><td>" . $email . "</td><td>" . $phone . "</td>";
		echo "<td>";
		echo "<form name='form' method='post' action='";
		request_uri();
		echo "'>";
		echo "<input type='hidden' name='attendee_action' value='edit'>";
		echo "<input type='hidden' name='attendee_id' value='" . $id . "'>";
		// echo "<input type='SUBMIT' value='EDIT' ONCLICK=\"return confirm('Are you sure you want to edit record for ".$fname." ".$lname."?')\"></form>";
		echo "<input type='SUBMIT' value='EDIT'></form>";
		echo "</td></tr>";
	}
	echo "</table>";
}

require_once("event_regis_selections.inc.php");
?>