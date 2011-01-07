<?php

/**
 * @author Edge Technology Consulting
 * @copyright 2009
 */
require_once ("er_add_event_form.php");
$url = EVNT_RGR_PLUGINFULLURL;
function display_event_details($all = 0) {
        global $wpdb;
		$events_detail_tbl = get_option ( 'events_detail_tbl' );
        
		$currency_format = get_option ('currency_format');
		?><div style="float:right; margin-right:20px;">
        <?php
		echo "<form name='form' method='post' action='". request_uri() ."'>";
		echo "<input type='hidden' name='action' value='add_new'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='ADD NEW EVENT'>" ;
		echo "</form></div>";
     
        
        echo "<h2><a href='admin.php?page=events&events=current'>Current Events</a>  |  <a href='admin.php?page=events&events=expired'>Expired Events</a> 
        </h2>"; 
  	
		
		$curdate = date("Y-m-d");
		//$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." ORDER BY date(start_date) ASC";
       $id = $_REQUEST ['event_id'];
	   $sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id =" . $id;
		$result = mysql_query ($sql);

		while ($row = mysql_fetch_assoc ($result)){
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
                    $start_date = $row['start_date'];
					if ($start_date ==""){
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
					$start_date = $start_year."-".$month_no."-".$start_day;}
                    $end_date = $row['end_date'];
                    if ($end_date ==""){
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
					$end_date = $end_year."-".$end_month_no."-".$end_day;}
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
             
			
            	$sql2= "SELECT SUM(num_people) FROM " . get_option('events_attendee_tbl') . " WHERE event_id='$event_id'";
				$result2 = mysql_query($sql2);
	
				while($row = mysql_fetch_array($result2)){
					$number_attendees =  $row['SUM(num_people)'];
				}
				
				if ($number_attendees == '' || $number_attendees == 0){
					$number_attendees = '0';
				}
				
				if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){
					$reg_limit = "&#8734;";
				}
				
				if ($start_date <= date('Y-m-d')){
					$active_event = '<span style="color: #F00; padding-left:30px; font-weight:bold;">EXPIRED</span>';
				} elseif ($active == "yes"){
					$active_event = '<span style="color: #090; padding-left:20px; font-weight:bold;">ACTIVE EVENT</span>';
				} else if ($active == "no"){
					$active_event = '<span style="color: #F00; padding-left:30px; font-weight:bold;">NOT ACTIVE</span>';
				}
                   
             
?>
<div class="metabox-holder"><div class="postbox" STYLE="background-color:#EECBAD;" ><h3>
<?php
echo $event_name." | Start Date: ".$start_date." ".$start_time." | End Date: ".$end_date." ".$end_time." | Attendees: ".$number_attendees." / ".$reg_limit." ".$active_event;
?>  
</h3>
<ul>
  <li>
    
        <div style="float:left"> <?php    
		echo "<form name='form' method='post' action='". request_uri() ."'>";
		echo "<input type='hidden' name='action' value='edit'>";
		echo "<input type='hidden' name='id' value='" . $event_id . "'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='EDIT' ONCLICK=\"return confirm('Are you sure you want to edit ".$event_name."?')\">";
		echo "</form></div>";
    
        ?><div style='float:left; margin-left:20px;'><?php
        echo "<form name='form' method='post' action='".request_uri()."'>";
		echo "<input type='hidden' name='action' value='copy'>";
		echo "<input type='hidden' name='id' value='" . $event_id . "'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='COPY' ONCLICK=\"return confirm('Are you sure you want to copy the event ".$event_name."?')\">";
		echo "</form></div>";
     
        ?><div style="float:left; margin-left:20px;"><?php
     	echo "<form name='form' method='post' action='".request_uri()."'>";
		echo "<input type='hidden' name='action' value='delete'>";
		echo "<input type='hidden' name='id' value='" . $event_id . "'>";
		echo "<INPUT CLASS='button-primary' type='SUBMIT' value='DELETE' ONCLICK=\"return confirm('Are you sure you want to delete " . $event_name . "?')\">";
		echo "</form></div>";
  
        ?><div style="float:left; margin-left:20px;"><?php
		echo "<form name='form' method='post' action='". request_uri() ."'>";
		echo "<input type='hidden' name='action' value='add_new'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='ADD NEW EVENT'>" ;
		echo "</form></div>";
  
        ?>
        <?php
        if ($number_attendees =='0'){?>
        <div style="float:left; margin-left:20px;">
        <button  style="font-size:90%; background-color: #DB7093; color: #FFF; font-weight:bold; width:180; height: 20;
        onclick="window.location='admin.php?page=events'">NO ATTENDEES</button>
        </div>
        <?php }
        if ($number_attendees >'0'){?>
        <div style="float:left; margin-left:20px;">
        <button style="font-size:90%; background-color: #71C671; color: #FFF; font-weight: bolder; width:180; height: 20;" 
        onclick="window.location='<?php	echo "admin.php?page=attendees&action=view&event=". $event_id;
		?>'" >VIEW ATTENDEES</button>
        </div>
        <?php } ?>
        
        <div style="float:left; margin-left:20px;">
        <button style="font-size:90%; background-color: #8B8970; color: #FFF; font-weight: bolder; width:180; height: 20;" 
        onclick="window.location='<?php	echo "admin.php?page=form&action=write&event_id=". $event_id . "&event_name=".$event_name;	?>'" >ADD/VIEW QUESTIONS</button>
        </div>
           
        
        
        
         
 
<?php /* ?>
<button style="margin-left:20px" class="button-primary" <?php if ($number_attendees == '0'){echo 'disabled="disabled" value="No Attendees"';}else {echo 'value="View Attendees"';}?> onclick="window.location='<?php echo "/wp-admin/admin.php?event_regis&id=".$event_id."&export=report&action=payment";?>'">Export Payment List to Excel </button>
<?php */ ?>
<div style="clear:both"></div>



<div class="col-container">
<div class="col-right">
<p><?php if ($header_image != ""){echo "<img src='".$header_image."' width='450' height='75'>";} ?></p>
    <table><tr><td>
    <p><?php if ($image_link != ""){echo "<img src='".$image_link."' width='75' height='56'>";} ?></p></td><td></td><td><strong>Event Description:</strong></p>
    <?php
		if ($display_desc ==""){
			echo " <p class='red_text'><strong><i>PLEASE UPDATE THIS EVENT</i></strong></p>";
		}
?>		
    <?php echo htmlspecialchars_decode($event_desc)?> </td></tr></table>
    
    <p><strong>Do you want to display the event description on registration page?</strong>
      
      <?php  
       
		if ($display_desc =="Y"){
			echo "Yes";
		}
		if ($display_desc =="N"){
			echo "No";
		}
?>
    </p>
    <p><strong>Event Location: </strong><?php echo " ".$event_location; ?></p>
    <p><strong>More Info Link:(enter url i.e. http://yourdomain.com/info_page) </strong><br /><?php echo " ".$more_info; ?></p>
    <hr /><p><strong>Send custom confirmation messages for this event?</strong>
      
      <?php 
	  if ($send_mail ==""){
			echo "<p class='red_text'><strong><i>PLEASE UPDATE THIS EVENT</i></strong>";
		}
		if ($send_mail =="Y"){
			echo "Yes";
		}
		if ($send_mail =="N"){
			echo "No</p>";
		}
       
?>
  <p><strong>Custom Confirmation Mail:</strong></p>
    <?php echo htmlspecialchars_decode($conf_mail)?>
</div>
  <div id="col-left">
  <div style=" border:#999 1px solid; background:#00FF7F; padding:10px; margin:10px 0;"> 

<p><strong>Shortcode for displaying this event only:</strong><br />[Event_Registration_Single event_id="<?php echo $event_id;?>"]</p>
</div>
  
  <p><strong>Event Identifier:</strong> <?php echo $event_identifier;?></p>
  <p><strong>Currency Format:</strong> <?php echo $custom_cur;?></p>
    
    <p><strong>Cost:</strong> <?php echo $custom_cur." ".$event_cost;?>
    </p>
    <p><strong>Allow Cash/Check Payments for this event?</strong> 
    <?php
    if ($allow_checks ==""){
			echo "<p class='red_text'><strong><i>PLEASE UPDATE THIS EVENT</i></strong>";
		}
		if ($allow_checks =="yes"){
			echo "Yes";
		}
		if ($allow_checks =="no"){
			echo "No</p>";
		} 
     ?>
    </p>
    <p><strong>Allow coupon code for this event?</strong>
        <?php
    if ($use_coupon ==""){
			echo "<p class='red_text'><strong><i>PLEASE UPDATE THIS EVENT</i></strong>";
		}
		if ($use_coupon =="Y"){
			echo "Yes";
		}
		if ($use_coupon =="N"){
			echo "No</p>";
		} 
     ?></p>
    <p><strong>Coupon Code:</strong> <?php echo $coupon_code;?></p>
    <p><strong>Coupon discount amount:</strong> -<?php echo $custom_cur." ".$coupon_code_price;?> </p>
     
    <p><strong>Allow payment for more than one person at a time? (max # people 5)</strong>: 
    <?php 
       
    if ($multiple ==""){
			echo "<p class='red_text'><strong><i>PLEASE UPDATE THIS EVENT</i></strong>";
		}
		if ($multiple =="Y"){
			echo "Yes";
		}
		if ($multiple =="N"){
			echo "No</p>";
		} 
     
    ?>
    </p>
    
    <p>Default Registration Information (Name and Email Required)</br>
    <b><i>Name, Email 
    <?php if ($inc_address == "Y"){echo ", Address";}?>
    <?php if ($inc_city == "Y"){echo ", City";}?>
    <?php if ($inc_state == "Y"){echo ", State";}?>
    <?php if ($inc_zip == "Y"){echo ", Zip";}?>
    <?php if ($inc_phone == "Y"){echo ", Phone #";}?>
    </i></b></p>

    
    <p><strong>Event Categories:</strong>
   
   <?php if (is_array($event_category))
                    { ?>  <?php
                      foreach ($event_category as $category)
                        {   
                        //  <ul style="padding:0;">  
					    $sql2= "SELECT * FROM " . get_option('events_cat_detail_tbl') . " WHERE id = ".$category;
					    $result2 = mysql_query($sql2);
						while($row = mysql_fetch_assoc($result2))
                            {
							$category_id= $row['id'];				
                            $category_identifier=$row['category_identifier'];
                            echo " ".$row['category_name']." ";
                            }}
       ?></p><div style=" border:#999 1px solid; background:#87CEFF; padding:10px; margin:10px 0;">
                      <?php                         
                      foreach ($event_category as $category)
                        {   
                        //  <ul style="padding:0;">  
					    $sql2= "SELECT * FROM " . get_option('events_cat_detail_tbl') . " WHERE id = ".$category;
					    $result2 = mysql_query($sql2);
						while($row = mysql_fetch_assoc($result2))
                            {
							$category_id= $row['id'];				
                            $category_identifier=$row['category_identifier'];
                            $category_name = $row['category_name'];
                            ?>
                             
<p><strong>Shortcode for displaying all events in <u><?php echo $category_name." ";?></u> category:</strong><br />
<p>[EVENT_REGIS_CATEGORY event_category_id="<?php echo $category_identifier?>"]</p>
					    
						    <?php  } 
					       } ?> </div><?php
	
                           }
                      	 else { echo "<p>No category selected</p>"; }  ?>
                    

 </li>
    </ul>
</div></div>
<?php	} 

}


function display_events_all($all = 0) {
    global $wpdb;
    $url = EVNT_RGR_PLUGINFULLURL;
    $currency_format = get_option ('currency_format');
    $curdate = date("Y-m-d");
    $events_detail_tbl = get_option ( 'events_detail_tbl' );
	$id = $_REQUEST ['id'];
		?><div style="float:left; margin-right:20px;"><?php
		echo "<form name='form' method='post' action='". request_uri() ."'>";
		echo "<input type='hidden' name='action' value='add_new'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='ADD NEW EVENT'>" ;
		echo "</form></div>";
             
        
        //echo "<h3><a href='admin.php?page=events'>Current Events</a></h3>"; 
echo "<h2><a href='admin.php?page=events&events=current'>Current Events</a>  |  <a href='admin.php?page=events&events=expired'>Expired Events</a> 
        </h2>";
		
		$curdate = date("Y-m-d");
        if ($_REQUEST['events'] == "current"){
		$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) >= '".$curdate."' ORDER BY date(start_date) ASC";}
		else if ($_REQUEST['events'] == "expired"){
		$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) <= '".$curdate."' ORDER BY date(start_date) ASC";}
        else {$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) >= '".$curdate."' ORDER BY date(start_date) ASC";}
        
        $result = mysql_query ($sql);

		while ($row = mysql_fetch_assoc ($result)){
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
            		$event_category =  unserialize($row ['event_category']);
                    $start_date = $row['start_date'];
					if ($start_date ==""){	if ($start_month == "Jan"){$month_no = '01';}
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
					$start_date = $start_year."-".$month_no."-".$start_day;}
                    
                    $end_date = $row['end_date'];
                    if ($end_date == ""){
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
					$end_date = $end_year."-".$end_month_no."-".$end_day;}
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
             
			
            	$sql2= "SELECT SUM(num_people) FROM " . get_option('events_attendee_tbl') . " WHERE event_id='$event_id'";
				$result2 = mysql_query($sql2);
	
				while($row = mysql_fetch_array($result2)){
					$number_attendees =  $row['SUM(num_people)'];
				}
				
				if ($number_attendees == '' || $number_attendees == 0){
					$number_attendees = '0';
				}
				
				if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){
					$reg_limit = "&#8734;";
				}
				
				if ($start_date <= $curdate){
					$status = '<span style="color: #F00; padding-left:30px; font-weight:bold;">EXPIRED</span>';
				} else {
					$days_till_event = dateDiffer("-", $start_date, $curdate);
                    $status = '<span style="color: blue; padding-left:30px; font-weight:bold;">'.$days_till_event.' DAYS UNTIL EVENT</span>';
				}
                   
             
?>
<div class="metabox-holder"><div class="postbox"><h3>
<?php
echo "<a href='admin.php?page=events&event_id=". $event_id . "&action=get_details'>".$event_name."</a> | Start Date: ".$start_date." ".$start_time." | End Date: ".$end_date." ".$end_time." | Attendees: ".$number_attendees." / ".$reg_limit." ".$status;
?>  
</h3>
</div></div>
<?php } 
}


function dateDiffer($dformat, $endDate, $beginDate)
{
$date_parts1=explode($dformat, $beginDate);
$date_parts2=explode($dformat, $endDate);
$start_date=gregoriantojd( $date_parts1[1], $date_parts1[2],$date_parts1[0]);
$end_date=gregoriantojd( $date_parts2[1], $date_parts2[2],$date_parts2[0]);
return $end_date - $start_date;
}



//Event Management Functions

function events_management_process() {
    
    er_plugin_menu();
	$er_management_action = $_REQUEST ['action'];
	switch ($er_management_action) {
		case "delete" : 
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$id = $_REQUEST ['id'];
			$sql = "DELETE FROM $events_detail_tbl WHERE id='$id'";
			$wpdb->query ( $sql );
               
            echo "<div id='message' class='updated fade'><p><strong>The event has been deleted.</strong></p></div>";
            echo "<META HTTP-EQUIV='refresh' content='2;URL=".request_uri()."'>";
		break;


		case "copy" :
			global $wpdb;
			$id = $_REQUEST ['id'];
			$event_id = $_REQUEST ['id'];
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id =" . $id;
			$result = mysql_query ( $sql );
			while ( $row = mysql_fetch_assoc ( $result ) ) {
                    $old_event_name = $row ['event_name'];
			        $event_name = "Copy of ".$old_event_name;
					$old_event_identifier = $row ['event_identifier'];
                    $event_identifier = $old_event_identifier."-2";
					$event_desc = $row ['event_desc'];
					$image_link = $row ['image_link'];
					$header_image = $row ['header_image'];
					$display_desc = $row ['display_desc'];
					$event_location = $row ['event_location'];
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
					$conf_mail = $row ['conf_mail'];
					$send_mail = $row ['send_mail'];
                    $use_coupon= $row ['use_coupon'];
            		$coupon_code= $row ['coupon_code'];
            		$coupon_code_price= $row ['coupon_code_price'];
            		$use_percentage= $row ['use_percentage'];
            		$event_category = $row ['category_id'];
                    
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
                    $reg_form_defaults = $row ['reg_form_defaults'];
                    if ($reg_limit == ''){$reg_limit = 999;}
                    if ($event_cost == ''){$event_cost= 0;}
                    if ($coupon_code_price == ''){$coupon_code_price = 0;}
                    
                    
                    
$sql=array('event_name'=>$event_name, 'event_desc'=>$event_desc, 'event_location'=>$event_location, 'display_desc'=>$display_desc, 
'image_link'=>$image_link, 'header_image'=>$header_image,'event_identifier'=>$event_identifier,  'more_info'=>$more_info, 
'start_month'=>$start_month, 'start_day'=>$start_day, 'start_year'=>$start_year, 'start_time'=>$start_time, 'start_date'=>$start_date,
'end_month'=>$end_month, 'end_day'=>$end_day,'end_year'=>$end_year, 'end_date'=>$end_date, 'end_time'=>$end_time, 'reg_limit'=>$reg_limit,
'event_cost'=>$event_cost,'custom_cur'=>$custom_cur, 'multiple'=>$multiple, 'allow_checks'=>$allow_checks, 'send_mail'=>$send_mail,             'is_active'=>$is_active, 'conf_mail'=>$conf_mail, 'use_coupon'=>$use_coupon, 'coupon_code'=>$coupon_code, 'coupon_code_price'=>$coupon_code_price, 'category_id'=>$event_category, 'use_percentage'=>$use_percentage); 
}                  
		
                $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
	
	           if ($wpdb->insert( get_option('events_detail_tbl'), $sql, $sql_data )){
                echo '<div id="message" class="updated fade"><p><strong>The event ';
                
                echo "has been added.  Now Updating the Extra Questions</strong></p></div>";
                //Code to copy questions from questions database
                $new_id = mysql_insert_id();
					$sql = "SELECT * FROM $events_question_tbl  WHERE event_id = '$event_id'";
					$values = array();
					$result = mysql_query ( $sql );
					//$num=mysql_numrows($result);
					$num = mysql_num_rows($result);
					$i=0;
					while ($i < $num) {
						$event_id = mysql_result($result,$i,"event_id");
						$sequence = mysql_result($result,$i,"sequence");
						$question_type = mysql_result($result,$i,"question_type");
						$question = mysql_result($result,$i,"question");
						$values = mysql_result($result,$i,"response");
						$required = mysql_result($result,$i,"required");
							
						$sql2 = "INSERT INTO ".$events_question_tbl." (event_id, sequence, question_type, question, response, required ) VALUES 
						('$new_id', '$sequence', '$question_type', '$question', '$values', '$required')";
						$result2 = mysql_query("$sql2");
						
						$i++;
						}
                        
                }
                else { 
                echo '<div id="message" class="error"><p><strong>There was an error in your submission, please try again. 
                The event was not saved!';
                print mysql_error(); 
                echo "The page will refresh momentarily</strong></p></div>";}
	       		echo "<meta http-equiv='refresh' content='4'>";
                
			
			echo "<META HTTP-EQUIV='refresh' content='0;URL=".request_uri()."'>";
		break;
		
		case "edit" :

			edit_event_form();
					
		break;


		case "update" :
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			
                    $id=$_REQUEST['id'];
                    $event_name = esc_html($_REQUEST ['event_name']);
					$event_identifier = esc_html($_REQUEST ['event_identifier']);
					$event_desc = esc_html($_REQUEST ['event_desc']);
					$image_link = $_REQUEST ['image_link'];
					$header_image = $_REQUEST ['header_image'];
					$display_desc = $_REQUEST ['display_desc'];
					$event_location = $_REQUEST ['event_location'];
					$more_info = $_REQUEST ['more_info'];
					$reg_limit = $_REQUEST ['reg_limit'];
					$event_cost = $_REQUEST ['cost'];
					$custom_cur = $_REQUEST ['custom_cur'];
					$multiple = $_REQUEST ['multiple'];
					$allow_checks = $_REQUEST ['allow_checks'];
					$is_active = $_REQUEST ['is_active'];
					$start_month = $_REQUEST ['start_month'];
					$start_day = $_REQUEST ['start_day'];
					$start_year = $_REQUEST ['start_year'];
					$end_month = $_REQUEST ['end_month'];
					$end_day = $_REQUEST ['end_day'];
					$end_year = $_REQUEST ['end_year'];
					$start_time = $_REQUEST ['start_time'];
					$end_time = $_REQUEST ['end_time'];
					$conf_mail = esc_html($_REQUEST ['conf_mail']);
					$send_mail = $_REQUEST ['send_mail'];
                    $use_coupon=$_REQUEST['use_coupon'];
            		$coupon_code=$_REQUEST['coupon_code'];
            		$coupon_code_price=$_REQUEST['coupon_code_price'];
            		$use_percentage=$_REQUEST['use_percentage'];
            		$event_category = serialize($_REQUEST['event_category']);
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
                    $reg_form_defaults = serialize($_REQUEST ['reg_form_defaults']);
                    if ($reg_limit == ''){$reg_limit = 999;}
                    if ($event_cost == ''){$event_cost= 0;}
                    if ($coupon_code_price == ''){$coupon_code_price = 0;}
				
					//When the posted record is set to active, this checks records and deactivates them to set the current record as active
					update_option ( "current_event", $event_name );
					
					if ($is_active == "yes") {
						$sql = "UPDATE " . $events_detail_tbl . " SET is_active = 'no' WHERE is_active='$is_active'";
						$wpdb->query ( $sql );
						}

$sql=array('event_name'=>$event_name, 'event_desc'=>$event_desc, 'event_location'=>$event_location, 'display_desc'=>$display_desc, 
'image_link'=>$image_link, 'header_image'=>$header_image,'event_identifier'=>$event_identifier,  'more_info'=>$more_info, 
'start_month'=>$start_month, 'start_day'=>$start_day, 'start_year'=>$start_year, 'start_time'=>$start_time, 'start_date'=>$start_date,
'end_month'=>$end_month, 'end_day'=>$end_day,'end_year'=>$end_year, 'end_date'=>$end_date, 'end_time'=>$end_time, 'reg_limit'=>$reg_limit,
'event_cost'=>$event_cost,'custom_cur'=>$custom_cur, 'multiple'=>$multiple, 'reg_form_defaults'=>$reg_form_defaults, 'allow_checks'=>$allow_checks, 'send_mail'=>$send_mail,'is_active'=>$is_active, 'conf_mail'=>$conf_mail, 'use_coupon'=>$use_coupon, 'coupon_code'=>$coupon_code, 'coupon_code_price'=>$coupon_code_price, 'category_id'=>$event_category, 'use_percentage'=>$use_percentage); 
   
   $update_id = array('id'=> $id);               
		
                $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
  
  
 if ($wpdb->update( get_option('events_detail_tbl'), $sql, $update_id, $sql_data, array( '%d' ) )){
                echo '<div id="message" class="updated fade"><p><strong>The event ';
                
                echo "has been updated.  The page will refresh momentarily</strong></p></div>";}
                else { 
                echo '<div id="message" class="error"><p><strong>There was an error updating the event, please try again. 
                The event was not updated! ';
                print mysql_error(); 
                echo " The page will refresh momentarily</strong></p></div>";}
	       		echo "<meta http-equiv='refresh' content='2'>";
	
		break;
		
		case "add_new" :
		
        add_event_form();
                    
		break;
		
		case "post" :
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
       
                	$event_name = addslashes($_REQUEST ['event_name']);
					$event_identifier = addslashes($_REQUEST ['event_identifier']);
					$event_desc = addslashes($_REQUEST ['event_desc']);
					$image_link = $_REQUEST ['image_link'];
					$header_image = $_REQUEST ['header_image'];
					$display_desc = $_REQUEST ['display_desc'];
					$event_location = $_REQUEST ['event_location'];
					$more_info = $_REQUEST ['more_info'];
					$reg_limit = $_REQUEST ['reg_limit'];
					$event_cost = $_REQUEST ['event_cost'];
					$custom_cur = $_REQUEST ['custom_cur'];
					$multiple = $_REQUEST ['multiple'];
					$allow_checks = $_REQUEST ['allow_checks'];
					$is_active = $_REQUEST ['is_active'];
					$start_month = $_REQUEST ['start_month'];
					$start_day = $_REQUEST ['start_day'];
					$start_year = $_REQUEST ['start_year'];
					$end_month = $_REQUEST ['end_month'];
					$end_day = $_REQUEST ['end_day'];
					$end_year = $_REQUEST ['end_year'];
					$start_time = $_REQUEST ['start_time'];
					$end_time = $_REQUEST ['end_time'];
					$conf_mail = esc_html($_REQUEST ['conf_mail']);
					$send_mail = $_REQUEST ['send_mail'];
                    $use_coupon=$_REQUEST['use_coupon'];
            		$coupon_code=$_REQUEST['coupon_code'];
            		$coupon_code_price=$_REQUEST['coupon_code_price'];
            		$use_percentage=$_REQUEST['use_percentage'];
            		$event_category = serialize($_REQUEST['event_category']);
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
                    $reg_form_defaults = serialize($_REQUEST['reg_form_defaults']);
                    if ($reg_limit == ''){$reg_limit = 999;}
                    if ($event_cost == ''){$event_cost= 0;}
                    if ($coupon_code_price == ''){$coupon_code_price = 0;}
					
					//When the posted record is set to active, this checks records and deactivates them to set the current record as active
					update_option ( "current_event", $event_name );
					
					if ($is_active == "yes") {
						$sql = "UPDATE " . $events_detail_tbl . " SET is_active = 'no' WHERE is_active='$is_active'";
						$wpdb->query ( $sql );
						}

$sql=array('event_name'=>$event_name, 'event_desc'=>$event_desc, 'event_location'=>$event_location, 'display_desc'=>$display_desc, 
'image_link'=>$image_link, 'header_image'=>$header_image,'event_identifier'=>$event_identifier,  'more_info'=>$more_info, 
'start_month'=>$start_month, 'start_day'=>$start_day, 'start_year'=>$start_year, 'start_time'=>$start_time, 'start_date'=>$start_date,
'end_month'=>$end_month, 'end_day'=>$end_day,'end_year'=>$end_year, 'end_date'=>$end_date, 'end_time'=>$end_time, 'reg_limit'=>$reg_limit,
'event_cost'=>$event_cost,'custom_cur'=>$custom_cur, 'multiple'=>$multiple, 'reg_form_defaults'=>$reg_form_defaults, 'allow_checks'=>$allow_checks, 'send_mail'=>$send_mail,'is_active'=>$is_active, 'conf_mail'=>$conf_mail, 'use_coupon'=>$use_coupon, 'coupon_code'=>$coupon_code, 'coupon_code_price'=>$coupon_code_price, 'category_id'=>$event_category, 'use_percentage'=>$use_percentage); 
                  
		
                $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
	
	           if ($wpdb->insert( get_option('events_detail_tbl'), $sql, $sql_data )){
                echo '<div id="message" class="updated fade"><p><strong>The event ';
                
                echo "has been added.  The page will refresh momentarily</strong></p></div>";}
                else { 
                echo '<div id="message" class="error"><p><strong>There was an error in your submission, please try again. 
                The event was not saved!';
                print mysql_error(); 
                echo "The page will refresh momentarily</strong></p></div>";}
	       		echo "<meta http-equiv='refresh' content='2'>";
		break;
        
        case "get_details" :
			
            display_event_details ();
		break;
            
            
		default:
			            
            display_events_all();

		break;
//End Switch
	}
//End Function   
}

?>