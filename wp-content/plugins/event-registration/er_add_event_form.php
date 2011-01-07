<?php

/**
 * @author David Fleming
 * @copyright 2010
 */
function add_event_form(){
    $currency_format = get_option ('currency_format');
    ?>
<div class="metabox-holder" >

<h3>Add An Event</h3><div class="postbox" STYLE="background-color:#EECBAD;">
  <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
  <input type="hidden" name="action" value="post">
 <div style="clear:both"></div> 
<div class="col-container">
<br>
<div class="col-right">
   <ul>
    <li><strong>Event Location: </strong> <input name="event_location" size="45"></li>
    <li><strong>Event Description:</strong><br />
            <textarea rows="5" cols="300" name="event_desc_new" id="event_desc_new"  class="my_ed"></textarea></li>
    <li><strong>Do you want to display the event description on registration page?</strong>
       	<input type="radio" name="display_desc" value="Y">Yes
    	<input type="radio" name="display_desc" value="N">No</li>
    <li><strong>MORE INFO<strong> <i>(enter url i.e. http://yourdomain.com/info_page)</i><li><input name="more_info" size="45"></li>
    <li><strong>Thumbnail Image URL</strong><i> (shows on event listing) display size 150 x112</i> <input name="image_link" size="45"></li>
    <li><strong>Event Header Image URL</strong><i> (shows on registration page) width should be 450 </i><input name="header_image" size="45"></li>
     <hr /><li> <strong>Do you want to send a custom confirmation email?</strong>
	<input type="radio" name="send_mail" value="Y">Yes
	<input type="radio" name="send_mail" value="N">No
   </li>
    <li>Custom Confirmation Email For This Event:</strong><br /> 
    <textarea rows='4' cols='125' name='conf_mail' id="conf_mail_new"  class="my_ed">
***This is an automated response - Do Not Reply***
Thank you [fname] [lname] for registering for [event].
We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].
If you have not done so already, please submit your payment in the amount of [cost].
Click here to reveiw your payment information [payment_url].
Thank You.</textarea>
      <br />
</li>   
    
    <li>
    <p>
            <input class="button-primary" type="submit" name="Submit" value="<?php _e('Submit New Event'); ?>" id="add_new_event" />
            </p>
    </li></ul>
    </div>
    
    
    <div class="col-left">
 <ul>       
  <li><label><strong>Event Name:</strong></label> <input name="event_name" size="50"></li>
   <li><label><strong>Unique Event Identifier:</strong></label> <input name="event_identifier" size="50"> </li>
   <li> <p><strong>Event Date(s):</strong></p>
              <?php displaySelectionBox (); ?>
                
      </li>
    <li>
		<p>START TIME <input name="start_time" size="10">    END TIME <input name="end_time" size="10"></p>
   </li>
 <li><strong>Attendee Limit:</strong> <input name="reg_limit" size="15">
 <font size="-2">(leave blank for unlimited attendees)</font></li>
    <li><strong>Allow payment for more than one person at a time?</strong>
    <input type="radio" name="allow_multiple" checked value="Y">Yes
	<input type="radio" name="allow_multiple" value="N">No
    <br />(max # people 5)
    </li>
    <li><strong>Custom Currency</strong><select name = "custom_cur"><?php
    //If ($custom_cur !=""){echo "<option value='" . $custom_cur . "'>" . $custom_cur . "</option>";}
    if ($currency_format !=""){echo "<option value='" . $currency_format . "'>" . $currency_format . "</option>";}
    echo "<option value='USD'>USD</option>
				<option value='AUD'>AUD</option>
				<option value='GBP'>GBP</option>
				<option value='CAD'>CAD</option>
				<option value='CZK'>CZK</option>
				<option value='DKK'>DKK</option>
				<option value='EUR'>EUR</option>
				<option value='HKD'>HKD</option>
				<option value='HUF'>HUF</option>
				<option value='ILS'>ILS</option>
				<option value='JPY'>JPY</option>
				<option value='MXN'>MXN</option>
				<option value='NZD'>NZD</option>
				<option value='NOK'>NOK</option>
				<option value='PLN'>PLN</option>
				<option value='SGD'>SGD</option>
				<option value='SEK'>SEK</option>
				<option value='CHF'>CHF</option></select>";
     ?>
    </li>
    <li><strong>Event Cost:</strong>
    <?php echo $currency_format;?><input name="cost" size="10"><font size="-2">(leave blank for free events, enter 2 place decimal i.e. <?php echo $currency_format;?>7.00)</li>
	<li><strong>Allow coupon code for this event?</font></strong> 
    <input type="radio" name="use_coupon_code" value="Y">Yes
              <input type="radio" name="use_coupon_code" value="N">No</li>
    <li><strong>Coupon Code:</strong> <input name="coupon_code" size="20" > </li>
    <li><strong>Amount of discount for coupon:</strong> -<?php echo $currency_format;?><input name="coupon_code_price" size="10" ><font size="-2">(enter 2 place decimal i.e. <?php echo $currency_format;?>7.00.)</font></li>
    
	
  

      
    <li> <strong>Is this event active?</strong>
      <select name="is_active">
        <option>yes</option>
        <option>no</option>
      </select></li>
      <li>Default Registration Information (Name and Email Required)</br>
<INPUT type="checkbox" name="reg_form_defaults[]" value="Address" checked>Street Address<BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="City" checked>City<BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="State" checked>State or Province<BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="Zip" checked>Zip or Postal Code<BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="Phone" checked>Phone Number<BR> 
</li>

      
      <li><strong>Event Category:</strong><br />
		<ul><?php 
            $sql = "SELECT * FROM ". get_option('events_cat_detail_tbl');
            $result = mysql_query ($sql);
            
            while ($row = mysql_fetch_assoc ($result)){
                $category_id= $row['id'];
                $category_name=$row['category_name'];
                //$checked = in_array( $category_id, $in_event_category );
                echo '<li id="event-category-', $category_id, '"><label for="in-event-category-', $category_id, '" class="selectit"><input value="', $category_id, '" type="checkbox" name="event_category[]" id="in-event-category-', $category_id, '"', ($checked ? ' checked="checked"' : "" ), '/> ', $category_name, "</label></li>";
            }
        ?></ul>
        </li> </ul>
      </div>
</div>
  </form>
	</div>
</div>

<?php
}

function edit_event_form(){
    global $wpdb;
    $currency_format = get_option ('currency_format');
    $curdate = date("Y-m-d");
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$id = $_REQUEST ['id'];
	$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id =" . $id;
	$result = mysql_query ( $sql );
		while ($row = mysql_fetch_assoc ($result)){
				    $event_id= $row['id'];
			        $event_name = stripslashes($row ['event_name']);
					$event_identifier = stripslashes($row ['event_identifier']);
					$event_desc = stripslashes($row ['event_desc']);
					$image_link = $row ['image_link'];
					$header_image = $row ['header_image'];
					$display_desc = $row ['display_desc'];
					$event_location = stripslashes($row ['event_location']);
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
         }
    update_option ( "current_event", $event_name );
    
    
    ?>
<div class="metabox-holder" >
  
<h3>Edit Event - <?php echo $event_name." - ".$event_identifier;?></h3>
  <div class="postbox" STYLE="background-color:#EECBAD;">
  <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
  <input type="hidden" name="action" value="post">
 <div style="clear:both"></div> 
<div class="col-container" >
<br>
<div class="col-right">
   <ul>
    <li><strong>Event Location: </strong> <input name="event_location" size="45" value="<?php echo $event_location;?>"></li>
    <li><strong>Event Description:</strong><br />
            <textarea rows="5" cols="300" name="event_desc" id="event_desc"  class="my_ed"><?php echo $event_desc;?></textarea></li>
    <li><strong>Do you want to display the event description on registration page?</strong>
<?php if ($display_desc == "") {
					echo "<INPUT TYPE='radio' NAME='display_desc' CHECKED VALUE='Y'>Yes";
					echo "<INPUT TYPE='radio' NAME='display_desc' VALUE='N'>No";
				}
				if ($display_desc == "Y") {
					echo "<INPUT TYPE='radio' NAME='display_desc' CHECKED VALUE='Y'>Yes";
					echo "<INPUT TYPE='radio' NAME='display_desc' VALUE='N'>No";
				}
				if ($display_desc == "N") {
					echo "<INPUT TYPE='radio' NAME='display_desc' VALUE='Y'>Yes";
					echo "<INPUT TYPE='radio' NAME='display_desc' CHECKED VALUE='N'>No";
				}?>

</li>
    <li><strong>MORE INFO<strong> <i>(enter url i.e. http://yourdomain.com/info_page)</i><li><input name="more_info" size="45" value="<?php echo $more_info;?>" ></li>
    <li><strong>Thumbnail Image URL</strong><i> (shows on event listing) display size 150 x112</i> <input name="image_link" size="45" value="<?php echo $image_link;?>"></li>
    <li><strong>Event Header Image URL</strong><i> (shows on registration page) width should be 450 </i><input name="header_image" size="45" value="<?php echo $header_image;?>"></li>
     <hr /><li> <strong>Do you want to send a custom confirmation email?</strong>
        <?php if ($send_mail == "") {
					echo "<INPUT TYPE='radio' NAME='send_mail' CHECKED VALUE='Y'>Yes";
					echo "<INPUT TYPE='radio' NAME='send_mail' VALUE='N'>No";
				}
				if ($send_mail == "Y") {
					echo "<INPUT TYPE='radio' NAME='send_mail' CHECKED VALUE='Y'>Yes";
					echo "<INPUT TYPE='radio' NAME='send_mail' VALUE='N'>No";
				}
				if ($send_mail == "N") {
					echo "<INPUT TYPE='radio' NAME='send_mail' VALUE='Y'>Yes";
					echo "<INPUT TYPE='radio' NAME='send_mail' CHECKED VALUE='N'>No";
				} ?>
   </li>
    <li>Custom Confirmation Email For This Event:</strong><br /> 
    <textarea rows='4' cols='125' name='conf_mail' id="conf_mail"  class="my_ed"><?php echo $conf_mail;?></textarea>
      <br />
</li>   
    
    <li>
    <p>
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <input class="button-primary" type="submit" name="Submit" value="UPDATE EVENT"/>
            </p>
    </li></ul>
    </div>
    
    
    <div class="col-left">
 <ul>       
  <li><label><strong>Event Name:</strong></label> <input name="event_name" size="50" value="<?php echo $event_name;?>"></li>
   <li><label><strong>Unique Event Identifier:</strong></label> <input name="event_identifier" value="<?php echo $event_identifier; ?>"> </li>
   <li><p><strong>Event Date(s):</strong><br>
              <?php displaySelectionBox ( $start_month, $start_day, $start_year, $end_month, $end_day, $end_year ); ?>
                
      </li>
    <li>
		<p>START TIME <input name="start_time" size="10" value="<?php echo $start_time; ?>">
        END TIME <input name="end_time" size="10" value="<?php echo $end_time;?>"></p>
   </li>
 <li><strong>Attendee Limit:</strong> <input name="reg_limit" size="15" value="<?php echo $reg_limit;?>">
 <font size="-2">(leave blank for unlimited attendees)</font></li>
    <li><strong>Allow payment for more than one person at a time?</strong>
			<?php	if ($multiple == "") {
					echo " <INPUT TYPE='radio' NAME='multiple' CHECKED VALUE='Y'>Yes";
					echo " <INPUT TYPE='radio' NAME='multiple' VALUE='N'>No";
				}
				if ($multiple == "Y") {
					echo " <INPUT TYPE='radio' NAME='multiple' CHECKED VALUE='Y'>Yes";
					echo " <INPUT TYPE='radio' NAME='multiple' VALUE='N'>No";
				}
				if ($multiple == "N") {
					echo " <INPUT TYPE='radio' NAME='multiple' VALUE='Y'>Yes";
					echo " <INPUT TYPE='radio' NAME='multiple' CHECKED VALUE='N'>No";
				} ?>
    <br />(max # people 5)
    </li>
        <li><strong>Custom Currency</strong><select name = "custom_cur"><?php
    If ($custom_cur !=""){echo "<option value='" . $custom_cur . "'>" . $custom_cur . "</option>";}
    if ($currency_format !=""){echo "<option value='" . $currency_format . "'>" . $currency_format . "</option>";}
    echo "<option value='USD'>USD</option>
				<option value='AUD'>AUD</option>
				<option value='GBP'>GBP</option>
				<option value='CAD'>CAD</option>
				<option value='CZK'>CZK</option>
				<option value='DKK'>DKK</option>
				<option value='EUR'>EUR</option>
				<option value='HKD'>HKD</option>
				<option value='HUF'>HUF</option>
				<option value='ILS'>ILS</option>
				<option value='JPY'>JPY</option>
				<option value='MXN'>MXN</option>
				<option value='NZD'>NZD</option>
				<option value='NOK'>NOK</option>
				<option value='PLN'>PLN</option>
				<option value='SGD'>SGD</option>
				<option value='SEK'>SEK</option>
				<option value='CHF'>CHF</option></select>";
     ?>
    </li>
    <li><strong>Event Cost:</strong>
    <?php echo $custom_cur;?><input name="cost" size="10" value="<?php echo $event_cost;?>"><font size="-2">(leave blank for free events, enter 2 place decimal i.e. <?php echo $custom_cur;?>7.00)</font></li>
	<li><strong>Allow Cash/Check Payments for this event?</strong>
    <?php	if ($allow_checks == "") {
					echo " <INPUT TYPE='radio' NAME='allow_checks' CHECKED VALUE='yes'>Yes";
					echo " <INPUT TYPE='radio' NAME='allow_checks' VALUE='no'>No";
				}
				else if ($allow_checks == "yes") {
					echo " <INPUT TYPE='radio' NAME='allow_checks' CHECKED VALUE='yes'>Yes";
					echo " <INPUT TYPE='radio' NAME='allow_checks' VALUE='no'>No";
				}
				else if ($allow_checks == "no") {
					echo " <INPUT TYPE='radio' NAME='allow_checks' VALUE='yes'>Yes";
					echo " <INPUT TYPE='radio' NAME='allow_checks' CHECKED VALUE='no'>No";
				} 
                else if ($allow_checks == "Yes") {
					echo " <INPUT TYPE='radio' NAME='allow_checks' CHECKED VALUE='yes'>Yes";
					echo " <INPUT TYPE='radio' NAME='allow_checks' VALUE='no'>No";
				}
				else if ($allow_checks == "No") {
					echo " <INPUT TYPE='radio' NAME='allow_checks' VALUE='yes'>Yes";
					echo " <INPUT TYPE='radio' NAME='allow_checks' CHECKED VALUE='no'>No";
				}
                else {
                    echo " <INPUT TYPE='radio' NAME='allow_checks' VALUE='yes'>Yes";
					echo " <INPUT TYPE='radio' NAME='allow_checks' CHECKED VALUE='no'>No";
                }
                ?>
    </li>
    <li><strong>Allow coupon code for this event?</strong> 
    			<?php	if ($use_coupon == "") {
					echo " <INPUT TYPE='radio' NAME='use_coupon' CHECKED VALUE='Y'>Yes";
					echo " <INPUT TYPE='radio' NAME='use_coupon' VALUE='N'>No";
				}
				if ($use_coupon == "Y") {
					echo " <INPUT TYPE='radio' NAME='use_coupon' CHECKED VALUE='Y'>Yes";
					echo " <INPUT TYPE='radio' NAME='use_coupon' VALUE='N'>No";
				}
				if ($use_coupon == "N") {
					echo " <INPUT TYPE='radio' NAME='use_coupon' VALUE='Y'>Yes";
					echo " <INPUT TYPE='radio' NAME='use_coupon' CHECKED VALUE='N'>No";
				} ?>
    </li>
    <li><strong>Coupon Code:</strong> <input name="coupon_code" size="20" value="<?php echo $coupon_code;?>" > </li>
    <li><strong>Amount of discount for coupon:</strong> -<?php echo $custom_cur;?> <input name="coupon_code_price" size="10" value="<?php echo $coupon_code_price;?>"><font size="-2">(enter 2 place decimal i.e. <?php echo $custom_cur;?> 7.00.)</font></li>
    
	
  

      
    <li> <strong>Is this event active?</strong>
      <select name="is_active">
      <?php	if ($active == "yes"){echo "<option>yes</option>";}
            if ($active == "no") {echo "<option>no</option>";}
			echo '<option>yes</option><option>no</option></select></p>';?>
     </li>
  

<li>Default Registration Information (Name and Email Required)</br>
<INPUT type="checkbox" name="reg_form_defaults[]" value="Address" <?php if ($inc_address == "Y"){echo "checked";}?> >Street Address</input><BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="City" <?php if ($inc_city == "Y"){echo "checked";}?> >City</input><BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="State" <?php if ($inc_state == "Y"){echo "checked";}?> >State or Province</input><BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="Zip" <?php if ($inc_zip == "Y"){echo "checked";}?> >Zip or Postal Code</input><BR>
<INPUT type="checkbox" name="reg_form_defaults[]" value="Phone" <?php if ($inc_phone == "Y"){echo "checked";}?> >Phone Number</input><BR> 
</li>

      <li><strong>Event Category:</strong><br />
		<ul><?php 
            $sql = "SELECT * FROM ". get_option('events_cat_detail_tbl');
            $result = mysql_query ($sql);
            
            while ($row = mysql_fetch_assoc ($result)){
                $category_id= $row['id'];
                          
                $category_name=$row['category_name'];
                $checked = in_array( $category_id, $event_category );
                
                echo '<li id="event-category-', $category_id, '"><label for="in-event-category-', $category_id, '" class="selectit"><input value="', $category_id, '" type="checkbox" name="event_category[]" id="in-event-category-', $category_id, '"', ($checked ? ' checked="checked"' : "" ), '/> ', $category_name, "</label></li>";
            }
        ?></ul>
        </li> </ul>
      </div>
</div>
  </form>
	</div>
</div>

<?php
}

?>