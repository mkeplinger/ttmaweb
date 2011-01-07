<?php 
function er_mail_followup(){
    
er_plugin_menu();    
global $wpdb;
$count = 0;
$events_detail_tbl = get_option('events_detail_tbl');
$events_attendee_tbl = get_option('events_attendee_tbl');
$er_mail_action = $_REQUEST ['action'];
$event_id = $_REQUEST['event'];
$curdate = date("Y-m-d");

 ?><div style="float:left; margin-left:20px;"><table><tr><td><?php
		echo "<form name='form' method='post' action='". request_uri() ."'>";
		echo "<input type='hidden' name='type' value='current'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='Current Events'>" ;
		echo "</form></td><td>";
		echo "<form name='form' method='post' action='". request_uri() ."'>";
		echo "<input type='hidden' name='type' value='expired'>";
		echo "<INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='Expired Events'>" ;
		echo "</form></td></tr></table></div>";				
						
	$sql = "SELECT * FROM ". $events_detail_tbl . " WHERE id = '".$event_id."'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc ($result))
		{
		$event_name = $row['event_name'];
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
		echo "Attendee Email: ";
        echo "<u>".$event_name."</u>";
        echo " - ".$event_location." - ".$start_date."<br>";
		
        if ($start_date <= $curdate){
					$status = '<span style="color: #F00; padding-left:30px; font-weight:bold;">EXPIRED</span>';
				} else {
					$days_till_event = dateDiffer("-", $start_date, $curdate);
                    $status = '<span style="color: blue; padding-left:30px; font-weight:bold;">'.$days_till_event.' DAYS UNTIL EVENT</span>';
				}
        echo $status;        
        echo "<br><br>";
	}

switch ($er_mail_action){

case "send":
    

	
	$name = stripslashes($_POST["name"]);
    $from = $_POST['from'];
	$body = stripslashes($_POST["body"]);
	$cc = $_POST["cc"];
	$bcc = $_POST["bcc"];
	$reply = $_POST["reply"];
    $addresses=$_POST['addresses'];
    $subject = $_POST["subject"];
    $subject = stripslashes($subject);


while (list ($key,$val) = @each ($addresses)) { 
        $distro = $val;

        $headers  = "MIME-Version: 1.0\r\n";
	    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
        $headers .= 'From: "' . $name . '" <' . $from . ">\r\n";
	    $user_count++; 
       wp_mail( $distro, $subject, $body, $headers);
          
   
     echo "<div class='updated fade'>Email was sent sent to ".$distro.".</div>";
        } 
    echo "<META HTTP-EQUIV='refresh' content='6;URL=admin.php?page=mail'>";
    echo "Event Listing will refresh momentarily!";
    

  
    break;

    case "mailform":

?>
<div class="metabox-holder"><div class="postbox">
<form id="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<input type="hidden" name="event" value="<?php echo $event_id; ?>">
<input type="hidden" name="action" value="send">


<style>
td {padding:5px;}
.address tr { background-color:#FFF;}
.address tr:hover {background-color:#FF5;}
.address {overflow:scroll; display:block; border:solid 1px #999;}
</style>

<h3>Message Content</h3>

<em>Write your message below</em>
<br /><br />
<?php

//Use these setting to pull blog company/email otherwise pull from plugin Organization settinsg
//$your_company = get_bloginfo('name');
//$your_email = get_bloginfo('admin_email');

//Query Database for Event Organization Info to email registrant BHC
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	// $sql  = "SELECT * FROM wp_events_organization WHERE id='1'"; 
	

	$result = mysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$your_company = $row ['organization'];
		$your_email = $row ['contact_email'];
		}
?>

<div id="hide">If you see this message, please enable JavaScript</div>
<br />
<table width="450">
<tr><td></td><td>Your Name: </td><td><input name="name" size="50" id="name" value="<?php echo $your_company;?>" /></td></tr>
<tr><td></td><td>Reply To Email Address:</td><td> <input name="from" size="50" id="from" value="<?php echo $your_email;?>" /></td></tr>
</table>
<br /><br />
Subject: <input name="subject" id="subject" style="font-size:20px" size="45"/><br />
Message:<textarea name="body" cols="74" rows="8" id="body"></textarea><br /> 
<br /><br />

<h3>Address Book for <?php echo $event_name;?></h3>
<?php /* This is the current attendee list for . Select the email address(s) you would like to send to. You can send multiple emails at once but make sure you:
<li>Ensure your web host allows you to send bulk mail messages</li>
<li>Enter a title</li>
<li>Enter your name</li>
<li>Use a valid email address in the From line</li>
<li>Optional fields are Reply-to, Cc, and Bcc</li>
<li>Limit sending messages to less than 2x per hour to avoid being marked as spam</li>
*/  ?>


  <div id="address">
  
  <?php

	echo "<table>";				
	$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id' ORDER BY lname ASC";
	$eresult = mysql_query($sql);
	while ($erow = mysql_fetch_assoc ($eresult))
		{
	    $id = $erow['id'];
		$lname = $erow['lname'];
		$fname = $erow['fname'];
   		$email = $erow['email'];
        $name = $fname." ".$lname;
        
    ?>
   
    
    <tr><td><INPUT type="checkbox" name="addresses[]" value="<?php echo $email;?>" checked><?php echo $name;?></td></tr>
    
	<?php
		$count++ ;
		}

?>
</table>

<hr /><small>There are total <?php echo $count;?> record(s) in Address Book.</small>
<div align="left"><p>
<input class="button" type="button" value="Select All" onclick="selectall()"/>
<input class="button" type="button" value="Unselect All" onclick="unselectall()"/>
<input class="button-primary" type="submit" value="Send Email Messages" onclick="alert('Sending Multiple Emails, Please be patient')" />
</p></div>
</form>

</div>
<script>
document.getElementById("hide").style.display = "none";
tr = document.getElementById("address").getElementsByTagName("tr");
for (i=0;i<tr.length;i++) {
	tr[i].onclick = mail_address_click;
}
function mail_address_click () {
	stat = this.getElementsByTagName("input")[0];
	if (stat.checked) {
		this.style.backgroundColor = "#FFF";
		stat.checked = false;
	} else {
		this.style.backgroundColor = "#FFC";
		stat.checked = true;
	}
}
function selectall() {
	input = document.getElementById("address").getElementsByTagName("input");
	for (i=0;i<input.length;i++) {
		input[i].checked = true;
	}
}
function unselectall() {
	input = document.getElementById("address").getElementsByTagName("input");
	for (i=0;i<input.length;i++) {
		input[i].checked = false;
	}
}
form = document.getElementById("form");
form.onsubmit = function () {
	to = "";
	address = document.getElementById("address").getElementsByTagName("input");
	for (i=0;i<address.length;i++) {
		if (address[i].checked) to+=address[i].value+",";
	}
	input = document.createElement("input");
	input.name = "to";
	input.value = to;
	form.appendChild(input);
}
</script>
</div></div>
<?php  
break;

default:
global $wpdb;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$current_event = get_option ( 'current_event' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	define ( "EVNT_RGR_PLUGINPATH", "/" . plugin_basename ( dirname ( __FILE__ ) ) . "/" );
	define ( "EVNT_RGR_PLUGINFULLURL", WP_PLUGIN_URL . EVNT_RGR_PLUGINPATH );
	$url = EVNT_RGR_PLUGINFULLURL;
	
	//$this->wp_content_dir.'/plugins/'.plugin_basename(dirname(__FILE__)); » TO $plugin_path = dirname(__FILE__);
	?>   <h3>Event Listing for Emailing Attendees</h3>Select event name to send email<br />
                <div class="metabox-holder"><div class="postbox">
                <table class="widefat">
                <thead>
                <tr><th>Status</th><th>Event</th><th>Location</th><th>Identifier</th><th>Categories</th><th>Attendees</th></tr>
                </thead>
                <tbody>
                <?php
                +
         $curdate = date("Y-m-d");
        if ($_REQUEST['type'] == "current"){
		$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) >= '".$curdate."' ORDER BY date(start_date) ASC";}
		else if ($_REQUEST['type'] == "expired"){
		$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) <= '".$curdate."' ORDER BY date(start_date) ASC";}
        else {$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) >= '".$curdate."' ORDER BY date(start_date) ASC";}       

		$result = mysql_query ( $sql );
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
        
        
        
        
        
        
        
        
        if ($reg_limit == ''){$reg_limit = 999;}
        if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){$available_spaces = "Unlimited";}
                 
  		$sql2= "SELECT SUM(num_people) FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
		$result2 = mysql_query($sql2);
		while($row2 = mysql_fetch_array($result2)){$num =  $row2['SUM(num_people)'];}
        
        if ($start_date <= $curdate){
					$status = '<span style="color: #F00; padding-left:30px; font-weight:bold;">EXPIRED</span>';
				} else {
					$days_till_event = dateDiffer("-", $start_date, $curdate);
                    $status = '<span style="color: blue; padding-left:30px; font-weight:bold;">'.$days_till_event.' DAYS UNTIL EVENT</span>';
				}
        
		echo "<tr><td>".$status."</td><td><a href='".request_uri()."&action=mailform&event=".$event_id."'>".$event_name."</a></td><td>".$event_location."</td><td>".$event_identifier."</td><td>".$event_category."</td><td>";
        if ($num !=""){echo "<font color='green'>".$num."/".$available_spaces." Attendees</font>";}
           else {echo "<font color='red'>No Attendees</font>";}
        echo "</td></tr>";
        
        
       
	}
	echo "</table></div></div>";
    


break;



}
}?>