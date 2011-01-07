<?php

/**
 * @author Edge Technology Consulting
 * @copyright 2009
 */

//Event Registration Subpage 2 - Configure Organization

function event_config_mnu() {
    
    er_plugin_menu();
	
	global $wpdb;
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_organization_tbl = get_option ( 'events_organization_tbl' );

	
	if (isset ( $_POST ['Submit'] )) {
		
		$org_id = $_REQUEST ['org_id'];
		$org_name = $_REQUEST ['org_name'];
		$org_street1 = $_REQUEST ['org_street1'];
		$org_street2 = $_REQUEST ['org_street2'];
		$org_city = $_REQUEST ['org_city'];
		$org_state = $_REQUEST ['org_state'];
		$org_zip = $_REQUEST ['org_zip'];
		$email = $_REQUEST ['email'];
		$show_thumb = $_REQUEST['show_thumb'];
        $payment_vendor = $_REQUEST['payment_vendor'];
		$payment_vendor_id = $_REQUEST ['payment_vendor_id'];
        $txn_key = $_REQUEST['txn_key'];
		$currency_format = $_REQUEST ['currency_format'];
        $accept_donations = $_REQUEST ['accept_donations'];
		$return_url = $_REQUEST ['return_url'];
					   $cancel_return = $_REQUEST['cancel_return'];
					   $notify_url = $_REQUEST['notify_url'];
					   $return_method = $_REQUEST['return_method'];
					   $use_sandbox = $_REQUEST['use_sandbox'];
					   $image_url = $_REQUEST['image_url'];
		$events_listing_type = $_REQUEST['events_listing_type'];
        $calendar_url = $_REQUEST['calendar_url'];
		$default_mail = $_REQUEST ['default_mail'];
		$message = $_REQUEST['message'];
        $payment_subj = $_REQUEST['payment_subj'];
        $payment_message = $_REQUEST['payment_message'];
        $captcha = $_REQUEST['captcha'];
        
        $sql = "UPDATE " . $events_organization_tbl . 
        " SET organization='$org_name',
         organization_street1='$org_street1',
         organization_street2='$org_street2',
         organization_city='$org_city', 
         organization_state='$org_state', 
         organization_zip='$org_zip', 
         contact_email='$email',
         show_thumb='$show_thumb',
         payment_vendor ='$payment_vendor',
         payment_vendor_id ='$payment_vendor_id',
         txn_key = '$txn_key',
         currency_format='$currency_format', 
         accept_donations = '$accept_donations',
         events_listing_type='$events_listing_type',
         default_mail='$default_mail',
         return_url = '$return_url',
         cancel_return= '$cancel_return',
         notify_url= '$notify_url',
         return_method= '$return_method',
         use_sandbox ='$use_sandbox',
         image_url='$image_url', 
         calendar_url = '$calendar_url',
         message='$message', 
         payment_subj='$payment_subj',
         payment_message='$payment_message',
         captcha = '$captcha' 
         WHERE id ='1'";
		
		$wpdb->query ( $sql ) or die(mysql_error());
		

        $option_name = 'payment_vendor_id';
        $newvalue = '';	
		if (get_option ( $option_name )) {
			} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
		
		$option_name = 'show_thumb';
		$newvalue = '';
		if (get_option ( $option_name )) {} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
		
        $option_name = 'er_link_for_calendar_url';
		$newvalue = '';
		if (get_option ( $option_name )) {	} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}

        $option_name = 'er_link_for_calendar_url';
		$newvalue = '';
		if (get_option ( $option_name )) { } else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
        
	   $option_name = 'currency_format';
	   $newvalue = '';
	   if (get_option ( $option_name )) { } else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
        
		$option_name = 'events_listing_type';
		$newvalue = '';
		if (get_option ( $option_name )) { } else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
		
		$option_name = 'return_url';
		$newvalue = '';
		if (get_option ( $option_name )) {	} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
		
		$option_name = 'cancel_return' ;
		$newvalue = $cancel_return;
		if ( get_option($option_name) ) {  } else {
            $deprecated=' ';
            $autoload='no';
            add_option($option_name, $newvalue, $deprecated, $autoload);
		}
					  
        $option_name = 'notify_url' ;
        $newvalue = '';
        if ( get_option($option_name) ) { } else {
            $deprecated=' ';
            $autoload='no';
            add_option($option_name, $newvalue, $deprecated, $autoload);
        }
					  
        $option_name = 'return_method' ;
        $newvalue = $return_method;
        if ( get_option($option_name) ) {  } else {
            $deprecated=' ';
            $autoload='no';
            add_option($option_name, $newvalue, $deprecated, $autoload);
        }
					  
		$option_name = 'use_sandbox' ;
		$newvalue = $use_sandbox;
        if ( get_option($option_name) ) { } else {
            $deprecated=' ';
            $autoload='no';
            add_option($option_name, $newvalue, $deprecated, $autoload);
        }
					  
        $option_name = 'image_url' ;
        $newvalue = $image_url;
        if ( get_option($option_name) ) {  } else {
            $deprecated=' ';
            $autoload='no';
            add_option($option_name, $newvalue, $deprecated, $autoload);
        }

		$option_name = 'registrar';
		$newvalue = $email;
		if (get_option ( $option_name )) {} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
        
        $option_name = 'er_captcha';
        $newvalue = $email;
		if (get_option ( $option_name )) {} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}
	
        $option_name = 'payment_vendor_id';
        $newvalue = $payment_vendor_id;		
        update_option( $option_name, $newvalue ); 
        
        
        $option_name = 'er_captcha';
        $newvalue = $captcha;		
        update_option( $option_name, $newvalue );
        
        $option_name = 'show_thumb';
        $newvalue = $show_thumb;
        update_option( $option_name, $newvalue );
        
        $option_name = 'er_link_for_calendar_url';
        $newvalue = $calendar_url;
        update_option( $option_name, $newvalue );
        
        $option_name = 'currency_format';
        $newvalue = $currency_format;
        update_option( $option_name, $newvalue );
        
        $option_name = 'events_listing_type';
        $newvalue = $events_listing_type;
        update_option( $option_name, $newvalue );
        
        $option_name = 'return_url';
        $newvalue = $return_url;
        update_option( $option_name, $newvalue );        
                
        $option_name = 'cancel_return' ;
        $newvalue = $cancel_return;        
        update_option( $option_name, $newvalue ); 
        
        $option_name = 'notify_url' ;
        $newvalue = $notify_url;
        update_option( $option_name, $newvalue ); 
        
        $option_name = 'return_method' ;
        $newvalue = $return_method;
        update_option( $option_name, $newvalue ); 
        
        $option_name = 'use_sandbox' ;
        $newvalue = $use_sandbox;
        update_option( $option_name, $newvalue ); 
        
        $option_name = 'image_url' ;
        $newvalue = $image_url;
        update_option( $option_name, $newvalue );
        
        $option_name = 'registrar';
        $newvalue = $email;
        update_option( $option_name, $newvalue );

}
$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";	 
$result = mysql_query ( $sql );
$ER_org_data = mysql_fetch_array($result) or die(mysql_error());
?>

<div id="configure_organization_form" class=wrap>
<div id="icon-options-event" class="icon32">
<br />
</div>
<h2>Event Registration Organization Settings</h2>
<div id="event_regis-col-left">
    <form method="post" action="<?php $_SERVER['REQUEST_URI']?>"> 
    <ul id="event_regis-sortables">
        <li>
            <div class="box-mid-head">
                <h2 class="events_reg f-wrench">Set up Your Organization Contact Info</h2>
            </div>
            <div class="box-mid-body" id="toggle2">
                <div class="padding">
                    <p align="center"><b>This information is required to provide email confirmations, <br>
                	"Make Check Payable" and online payment integration information. All areas marked by  *  must be filled in.</b></p>
                <ul>
                    <li>Organization Name: <input name="org_name" size="45" value="<?php  echo $ER_org_data ['organization']; ?>">*</li>
                    <li>Organization Street 1: <input name="org_street1" size="45" value="<?php  echo $ER_org_data ['organization_street1']; ?>">*</li>
                    <li>Organization Street 2: <input name="org_street2" size="45" value="<?php  echo $ER_org_data ['organization_street2']; ?>"></li>
                    <li>Organization City: <input name="org_city" size="45" value="<?php  echo $ER_org_data ['organization_city']; ?>">*</li>
                    <li>Organization State: <input name="org_state" size="3" value="<?php  echo $ER_org_data ['organization_state']; ?>">* 
                    	Organization Zip Code: <input name="org_zip" size="10" value="<?php  echo $ER_org_data ['organization_zip']; ?>">*</li>
                    <li>Primary contact email: <input name="email" size="45" value="<?php  echo $ER_org_data ['contact_email']; ?>">*</li>                         
                </ul>
                </div>
            </div>
        </li>
    <li>
        <div class="box-mid-head">
            <h2 class="events_reg f-wrench"><a href="#" id="m_general_OnOff" onClick="return doMore('general_OnOff')">Set up Your Organization Payment Info</a></h2>
        </div>
        <div style="display:none" id="general_OnOff_ex">
            <div class="box-mid-body" id="toggle2">
                 <!-- All the hidden HTML goes right here-->
                 <p><b>Online Payment Vendor</b> If you want to accept payments online you will need to provide an online vendor for collection of payments.  This plugin currently supports: PAYPAL, GOOGLE, AUTHORIZE.NET and the ability to add your own custom payment button.</p>
                 <p><b>Online Payment ID</b> The online payment id will be your email address you use to setup your paypal account or your Google account ID number or your authorize.net ID.</p>
                 <p><a href="https://ems.authorize.net/oap/home.aspx?SalesRepID=98&ResellerID=16334"><img src="http://www.authorize.net/images/reseller/oap_sign_up.gif" height="38" width="135" border="0" /></a></p>
                 <p><b>Transaction Key</b> Authorized.Net Accounts require a unique transaction key that was given when you created your account.
                 <p><b>Currency Format </b>  Is uesed by all payment methods for determining the local currency for transactions.</p>
                 <p><b>Accept Donations</b> If you would like to take online donations for free events, select yes and the online payment links will be displayed in the registration confirmation page.</p>
                 <p><b>Return URL</b> Create a page on your site and use the code <font color="red">{EVENTREGPAY}</font> to create a return page for collecting online payments from registered attendees.  This url will be transmitted in the confirmation email if you inclue the information, providing a link to click and return to make additional payments.</p>
                 <p><b>Image URL</b> Used by Paypal to display your personal logo on the PayPal website page.
                 <br /> 
                 <br />
                 <a href="#" onClick="return doHide('general_OnOff')">Close Help</a>
                 <!-- End Help Contents-->
            </div>	
        </div>
        <div class="box-mid-body" id="toggle2">
            <div class="padding">
                <ul>
                <li>
                Online Payment Vendor: <select name="payment_vendor">
                    <option value="<?php  echo $ER_org_data ['payment_vendor'];?>"><?php  echo $ER_org_data ['payment_vendor'];?></option>
                    <option value="NONE">NONE</option>
                    <option value="GOOGLE">GOOGLE</option>
                    <option value="PAYPAL">PAYPAL</option>
                    <option value="AUTHORIZE.NET">AUTHORIZE.NET</option>
                    <option value="MONSTER">MonsterPay</option>
                    <option value="CUSTOM">CUSTOM</option>
                </select> 
                <a href="https://ems.authorize.net/oap/home.aspx?SalesRepID=98&ResellerID=16334">
                <img src="http://www.authorize.net/images/reseller/oap_sign_up.gif" height="38" width="135" border="0"/></a>
                </li>
                
                <li>Online Payment ID(typically payment@yourdomain.com for paypal - leave blank if you are not accepting online payments):
                <input name="payment_vendor_id" size="45" value="<?php  echo $ER_org_data ['payment_vendor_id'];?>">
                </li>
                <li>Transaction Key (for Authorized.Net Accounts):<input name="txn_key" size="45" value="<?php  echo $ER_org_data ['txn_key'];?>">
                </li>
                <li>Currency Format: <select name = "currency_format">
                    <option value="<?php  echo $ER_org_data ['currency_format'];?>"><?php  echo $ER_org_data ['currency_format'];?> </option>
                    <option value="USD">USD</option>
                    <option value="AUD">AUD</option>
                    <option value="GBP">GBP</option>
                    <option value="CAD">CAD</option>
                    <option value="CZK">CZK</option>
                    <option value="DKK">DKK</option>
                    <option value="EUR">EUR</option>
                    <option value="HKD">HKD</option>
                    <option value="HUF">HUF</option>
                    <option value="ILS">ILS</option>
                    <option value="JPY">JPY</option>
                    <option value="MXN">MXN</option>
                    <option value="NZD">NZD</option>
                    <option value="NOK">NOK</option>
                    <option value="PLN">PLN</option>
                    <option value="SGD">SGD</option>
                    <option value="SEK">SEK</option>
                    <option value="CHF">CHF</option></select>
                </li>
                <li>Will you accept donations for free events: <select name = 'accept_donations'>
                    <option value="<?php  echo $ER_org_data ['accept_donations'];?>"><?php  echo $ER_org_data ['accept_donations'];?> </option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option></select>
                </li>            
                <li>Payment URL (used for attendee to return to make online payments.):<input name="return_url" size='75' value="<?php  echo $ER_org_data ['return_url'];?>"></li>
                <li>Image URL (used for your personal logo on the PayPal page):<input name="image_url: size"75" value="<?php  echo $ER_org_data ['image_url'];?>"></li>
                <input type="hidden" value="" name="cancel_return">	
                <input type="hidden" value="" name="notify_url">
                <input type="hidden" value="" name="return_method">
                <input type="hidden" value="" name="use_sandbox">
                <?php
                //Uncomment this code if you use Paypal IPN Support   
                /*	echo "Cancel Return URL (used for cancelled payments): <input name='cancel_return' size='75' value='".$cancel_return."'><br /><br />";
                			echo "Notify URL (used to process payments): <input name='notify_url' size='75' value='".$notify_url."'><br /><br />";
                			echo "Return Method: <select name='return_method'>";
                			if ($return_method ==""){
                				echo "<option value='2'>POST</option>";
                				echo "<option value='1'>GET</option>";}
                			if ($return_method =="2"){
                				echo "<option value='2' selected='selected'>POST</option>";
                				echo "<option value='1'>GET</option>";}
                			if ($return_method =="1"){
                				echo "<option value='2'>POST</option>";
                				echo "<option value='1' selected='selected'>GET</option>";}		
                			echo "</select><br /><br />";
                			
                			echo "Use PayPal Sandbox? ";
                			if ($use_sandbox =="1"){
                				echo "<input name='use_sandbox' type='checkbox' value='1' checked='checked' /><br><br />";
                			}else{
                				echo "<input name='use_sandbox' type='checkbox' value='1' /><br><br />";
                				}
                */
                ?>
                </ul>
            </div>
        </div>
    </li>
    
    <li>
        <div class="box-mid-head">
            <h2 class="events_reg f-wrench"><a href="#" id="m_general_OnOff1" onClick="return doMore('general_OnOff1')">Set up Your Organization Registration Defaults</a></h2>
        </div>
        <div style="display:none" id="general_OnOff1_ex">
            <div class="box-mid-body" id="toggle2">
             <!-- All the hidden HTML goes right here-->
            <p><b>Show a single event or all events</b>This allows you to show only 1 event on the events listing page.  This feature is mostly legacy and should be set to all for most organizations.  For specific events use the shortcode as displayed on the event or use event categories for custom regitration pages.</p>
            <p><b>Show thumbnails</b> By selecting yes, you can have thumbnail images (links noted under the event setup) next to each event in the event listing pages.</p>
            <p><b>Send Confirmation Emails</b> This option must be enable to send emails. Each event has the ability to add custom emails for that event.  When custom emails are not entered under the event setup, the default mail as entered here will be used.  This must be set to yes for any emails to be sent from the plugin.</p>
            <p><b>Confirmation Email Text</b> You have the ability to utilize data from the registration process in the emails. For customized confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
            <p>[fname], [lname], [phone], [event],[description], [cost], [company], [co_add1], [co_add2], [co_city],[co_state], [co_zip],[contact], [payment_url], [start_date], [start_time], [end_date], [end_time]</p>
            <hr /><strong><em>Sample Mail Send:</em></strong></p>
            <p>***This is an automated response - Do Not Reply***<br />
            Thank you [fname] [lname] for registering for [event]. <br />
            We hope that you will find this event both informative and enjoyable. <br />
            Should have any questions, please contact [contact].</p>
            <p>If you have not done so already, please submit your payment in the amount of [cost].</p>
            <p>Click here to reveiw your payment information [payment_url].</p>
            <p>Thank You.</p>
            <hr /> As a side note, I use the wordpress built-in mail send to send mails, so you will probably see mail from <a href="mailto:wordpress@yourdomain.com">wordpress@yourdomain.com</a>.  The email thing is a default Wordpress thing, not related to my plugin.  There is a great little plugin that resolves that issue.  <a href="http://wordpress.org/extend/plugins/mail-from/"><span style="color: #2255aa;">http://wordpress.org/extend/plugins/mail-from/</span></a></p>
            <p> </p> :               
            <br />
            <a href="#" onClick="return doHide('general_OnOff1')">Close Help</a>
             <!-- End Help Contents-->
            </div>
        </div> 
        <div class="box-mid-body" id="toggle2">
            <div class="padding">
                <ul>
                    <li>Do you want to show a single event or all events on the registration page?* 
                    <select name="events_listing_type"><option value="<?php  echo $ER_org_data ['events_listing_type']; ?>"><?php  echo $ER_org_data ['events_listing_type'];?></option>
                    <option value="single">Single Event</option>
                    <option value="all">All Events</option></select></li>
                    <li>Do you want to use Captcha validation on the registration pages? 
                    <?php if ($ER_org_data ['captcha'] == "") { ?>
                    		<input type="radio" NAME="captcha" value="Y">Yes
                    		<input type="radio" NAME="captcha" value="N">No
                    <?php	}
                    	if ($ER_org_data ['captcha'] == "Y") { ?>
                    		<input type="radio" NAME="captcha" CHECKED value="Y">Yes
                    		<input type="radio" NAME="captcha" value="N">No
                    <?php	}
                    	if ($ER_org_data ['captcha'] == "N") { ?>
                    		<input type="radio" NAME="captcha" value="Y">Yes
                    		<input type="radio" NAME="captcha" CHECKED value="N">No
                    <?php	} ?>
                    </li>
                    
                    
                    
                    <li>Do you want to show thumbnails on the Event Listing Page? 
                    <?php if ($ER_org_data ['show_thumb'] == "") { ?>
                    		<input type="radio" NAME="show_thumb" value="Y">Yes
                    		<input type="radio" NAME="show_thumb" value="N">No
                    <?php	}
                    	if ($ER_org_data ['show_thumb'] == "Y") { ?>
                    		<input type="radio" NAME="show_thumb" CHECKED value="Y">Yes
                    		<input type="radio" NAME="show_thumb" value="N">No
                    <?php	}
                    	if ($ER_org_data ['show_thumb'] == "N") { ?>
                    		<input type="radio" NAME="show_thumb" value="Y">Yes
                    		<input type="radio" NAME="show_thumb" CHECKED value="N">No
                    <?php	} ?>
                    </li>
                    <li>Calendar URL (used for registration links on the calendar page.):<input name="calendar_url" size="75" value="<?php  echo $ER_org_data ['calendar_url']; ?>"></li>
                    <li>Do You Want To Send Confirmation Emails? (This option must be enable to send custom mails in events)
                    <?php	
                    	if ($ER_org_data ['default_mail'] == "") { ?>
                    		<input type="radio" NAME="default_mail" value="Y">Yes
                    		<input type="radio" NAME="default_mail" value="N">No
                    <?php	}
                    	if ($ER_org_data ['default_mail']  == "Y") {  ?>
                    		<input type="radio" NAME="default_mail" CHECKED value="Y">Yes
                    		<input type="radio" NAME="default_mail" value="N">No
                    <?php	}
                    	if ($ER_org_data ['default_mail']  == "N") {  ?>
                    		<input type="radio" NAME="default_mail" value="Y">Yes
                    		<input type="radio" NAME="default_mail" CHECKED value="N">No
                    <?php	}  ?>
                    </li>
                    <li>Default Confirmation Email Text: </li>
                    <textarea rows="5" cols="125" name="message" ><?php  echo $ER_org_data ['message'];?></textarea></li>
                    <input type="hidden" value="<?php  echo $ER_org_data ['org_id']; ?>" name="org_id">
                    <input type="hidden" name="update_org" value="update">
                    <li><input type="submit" name="Submit" value="Update"></li></form>
                </ul>
            </div>
        </div>
    </li>
</div>
<?php
}
?>