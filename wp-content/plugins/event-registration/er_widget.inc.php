<?php

/**
 * @author Edge Technology Consulting
 * @copyright 2009
 */

function events_view_widget() {

	global $wpdb,$events_lang;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$curdate = date ( "Y-m-j" );
	$month = date ('M');
	$day = date('j');
	$year = date('Y');
	$currency_format = get_option ( 'currency_format' );
    $events_organization_tbl = get_option ( 'events_organization_tbl' );
    
    $sql3 = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	
		   		$result = mysql_query($sql3);
		   	while ($row = mysql_fetch_assoc ($result))
					{
		  			
                    $calendar_url = $row['calendar_url'];
					
					}

	
	//$sql = "SELECT * FROM " . $events_detail_tbl ." WHERE start_date >= '".date ( 'Y-m-j' )."' ORDER BY start_date";
    $sql = "SELECT * FROM " . get_option('events_detail_tbl') ." WHERE str_to_date(start_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";	
	$result = mysql_query ( $sql );
	
	while ( $row = mysql_fetch_assoc ( $result ) ) {
		$event_id = $row ['id'];
		$event_name = $row ['event_name'];
		$identifier = $row ['event_identifier'];
		$image = $row ['image_link'];
		$event_location = $row ['event_location'];
		$more_info = $row ['more_info'];
		$start_date = $row ['start_date'];
		$end_date = $row ['end_date'];
		$start_time = $row ['start_time'];
		$end_time = $row ['end_time'];
		$cost = $row ['event_cost'];
		$custom_cur = $row ['custom_cur'];
		$checks = $row ['allow_checks'];
		$active = $row ['is_active'];
		$reg_limit = $row ['reg_limit'];
		$timestamp = strtotime($start_date);
		$new_start_date = date("M d, Y", $timestamp);
		;
 
		
		if ($cost == ""){$cost = "FREE";}
		
	    
		$sql2= "SELECT SUM(num_people) FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
		$result2 = mysql_query($sql2);
		while($row = mysql_fetch_array($result2)){$num =  $row['SUM(num_people)'];}
		
				
		if ($custom_cur == ""){if ($currency_format == "USD" || $currency_format == "") {$currency_format = "$";}}
		if ($custom_cur != "" || $custom_cur != "USD"){$currency_format = $custom_cur;}
		if ($custom_cur == "USD") {$currency_format = "$";}
		if ($reg_limit != ""){$available_spaces = $reg_limit - $num;}
	    if ($reg_limit == ""){$available_spaces = "Unlimited";}

$arr_params = array ('regevent_action'=>'register', 'event_id' => $event_id, 'name_of_event'=> $event_name);
$link =  add_query_arg($arr_params, $calendar_url);

		echo "<br></hr><a href='".$link."'><B>" . $event_name . "   </b></a><br>";
		echo "Location:<b>  ".$event_location."</b><br>";
		echo "Start Date:<b>  ".$new_start_date."</b><br>";
	//	echo "Start Time:<b>  ".$start_time."</b><br>";
	/*	echo "Price:<b>  ";
		if ($cost != "FREE"){echo $currency_format;}
		echo " ".$cost."</b><br>";
	*/	
	//	echo "Spaces Available:<b>  ".$available_spaces."</b><br>";
		if ($more_info != ""){echo '<a href="'.$more_info.'"> More Info...</a>';}
	/*	echo "<form name='form' method='post' action='".request_uri()."'>";
		echo "<input type='hidden' name='regevent_action' value='register'>";
		echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
		echo "<input type='SUBMIT' value='$events_lang[register]'></form><br>";
		*/
		echo "<br>----------------<br>";

}}


function events_calendar_widget() {

	global $wpdb, $events_detail_tbl,$url;
$calendar_url = get_option ( 'er_link_for_calendar_url');
$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
$url = EVNT_RGR_PLUGINFULLURL;
	
    
$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
	
		   		$result = mysql_query($sql);
		   	while ($row = mysql_fetch_assoc ($result))
					{
		  			$org_id =$row['id'];
					$Organization =$row['organization'];
					$Organization_street1 =$row['organization_street1'];
					$Organization_street2=$row['organization_street2'];
					$Organization_city =$row['organization_city'];
					$Organization_state=$row['organization_state'];
					$Organization_zip =$row['organization_zip'];
					$contact =$row['contact_email'];
	 				$registrar = $row['contact_email'];
                    $payment_vendor = $row['payment_vendor'];
					$payment_vendor_id =$row['payment_vendor_id'];
					$currency_format =$row['currency_format'];
					$return_url = $row['return_url'];
					$cancel_return = $row['cancel_return'];
                    $accept_donations = $row['accept_donations'];
                    $txn_key = $row['txn_key'];
					$notify_url = $row['notify_url'];
					$return_method = $row['return_method'];
					$use_sandbox = $row['use_sandbox'];
					$image_url = $row['image_url'];
					$events_listing_type =$row['events_listing_type'];
                    $calendar_url = $row['calendar_url'];
					$default_mail = $row['default_mail'];
					$message =$row['message'];
                    $show_thumb=$row['show_thumb'];
                    $payment_subj=$row['payment_subj'];
                    $payment_message=$row['payment_message'];
					}
	
	
//$d = date("j");
//$month = date("n");
//$year = date("Y");   
    
  
    
    
?>
<script type="text/javascript">

function goPreviousMonth(month, year){
	// If the month is January, decrement the year
	if(month == 1){	
	   --year;	
       month = 13;
       }
    <?php  
    $link = add_query_arg('d', '1',get_page_link()); 
    ?>
    //link for previous month
	document.location.href = '<?php echo $link;?>&month='+(month-1)+'&year='+year;
    }


function goFollowingMonth(month, year){
	// If the month is December, increment the year
	if(month == 12){
	   ++year;	
       month = 0;
       }
   <?php $link = add_query_arg('d', '1',get_page_link());  ?>
   //link for next month
	document.location.href = '<?php echo $link;?>month='+(month+1)+'&year='+year;
    
    }  

</script>

<style type="text/css">

.today{
	/*background-color:#00CCCC;*/
	font-weight:bold;
	background-image:url(<?php echo $url; ?>Images/calBg.jpg);
	background-repeat:no-repeat;
	background-position:center;
	position:relative;
}

.today span{
	position:absolute;
	left:0;
	top:0;	
}

.today a{
	color:#000000;
	padding-top:10px;
}

.selected {
color: #FFFFFF;
background-color: #C00000;
}

.event {
/*	background-color: #C6D1DC; */
    font-weight:bold;
    background-image:url(<?php echo $url; ?>Images/events_icon_32.png);
    background-repeat:no-repeat;
	background-position:center;
    border:3px solid #ffffff;
} 

.normal {

} 

table{
	border:1px solid #cccccc;
	padding:3px;
}

th{
	width:16px;
	background-color:#cccccc;
	text-align:center;
	color:#ffffff;
	border-left:1px solid #ffffff;
}

td{
	text-align:center;
	padding:2px;
	margin:0;
}

table.tableClass{
	width:100%;
	border:none;
	border-collapse: collapse;
	font-size:85%;
	border:1px dotted #cccccc;
}

table.tableClass input,textarea{
	font-size:90%;
}

#form1{
	margin:5px 0 0 0;
}

#greyBox{
	height:10px;
	width:10px;
	background-color:#C6D1DC;
	border:1px solid #666666;
	margin:2px;
}

#legend{

	margin:5px 0 10px 50px;
	width:200px;
}

#hr{border-bottom:1px solid #cccccc;width:300px;}
.output{width:300px;border-bottom:1px dotted #ccc;margin-bottom:5px;padding:6px;}

h5{margin:0;}
</style>

<?php


	
// Get values from query string
//	$day = (isset($_GET["day"])) ? $_GET['day'] : "";
    $day = (isset($_GET["d"])) ? $_GET['d'] : "";
    $d  = (isset($_GET["d"])) ? $_GET['d'] : "";
	$month = (isset($_GET["month"])) ? $_GET['month'] : "";
	$year = (isset($_GET["year"])) ? $_GET['year'] : "";
     
	//set up vars for calendar etc
    if(empty($d)){ $d = date_i18n("j"); }
    if(empty($day)){ $day = date_i18n("j"); }
	if(empty($month)){ $month = date_i18n("n"); }
	if(empty($year)){ $year = date("Y"); } 
	$currentTimeStamp = strtotime("$year-$month-$d");
	$monthName = date_i18n("F", $currentTimeStamp);
	$numDays = date_i18n("t", $currentTimeStamp);
	$counter = 0;
    $t = date_i18n("j");

	//run a selec statement to hi-light the days

function ER_Widget_hiLightEvt($eMonth,$eDay,$eYear){
    global $wpdb,$url;
    $events_detail_tbl = get_option ( 'events_detail_tbl' );
    $today = date_i18n("j"); 
    $t = date_i18n("j");
    $thisMonth = date_i18n("n"); 
	$thisYear = date_i18n("Y"); 
        $aClass = 'normal';
        $month_name = '01';
		if ($today == $eDay && $thisMonth == $eMonth && $thisYear == $eYear){$aClass='class="today"';}
        else
        {
	
            $month_no = $eMonth;
                        if($month_no =="1"){$month_name ="01";}
                        if($month_no =="2"){$month_name ="02";}
                        if($month_no =="3"){$month_name ="03";}
                        if($month_no =="4"){$month_name ="04";}
                        if($month_no =="5"){$month_name ="05";}
                        if($month_no =="6"){$month_name ="06";}
                        if($month_no =="7"){$month_name ="07";}
                        if($month_no =="8"){$month_name ="08";}
                        if($month_no =="9"){$month_name ="09";}
                        if($month_no =="10"){$month_name ="10";}
                        if($month_no =="11"){$month_name ="11";}
                        if($month_no =="12"){$month_name ="12";}

			
            $sql = "SELECT count(start_date) as eCount FROM " . $events_detail_tbl .
            " where start_date = '"  .$eYear ."-". $month_name . "-" . $eDay .  "'";;
		
			
			$result = mysql_query($sql);
			while($row= mysql_fetch_array($result)){
				if($row['eCount'] >=1){
					$aClass = 'class="event"';
                    
				}elseif($row['eCount'] ==0){
					$aClass ='class="normal"';
				}
			}
		}
		echo $aClass;
	}
?>


							
							<h2>Events Calendar</h2><br />
							
										
								
							
							
<table width="90%" cellpadding="0" cellspacing="0">
<tr>
<td width="15%" colspan="1">
<input type="button" value=" < " onClick="goPreviousMonth(<?php echo $month . ", " . $year; ?>);">
</td>
<td width="70%" colspan="5">
<span class="title"><?php echo $monthName . " " . $year; ?></span><br>
</td>
<td width="15%" colspan="1" align="right">
<input type="button" value=" > " onClick="goFollowingMonth(<?php echo $month . ", " . $year; ?>);">
</td>
</tr>  
	<tr>
		<th scope="col" title="Sunday">S</th>
        <th scope="col" title="Monday">M</th>
		<th scope="col" title="Tuesday">T</th>
		<th scope="col" title="Wednesday">W</th>
		<th scope="col" title="Thursday">T</th>
		<th scope="col" title="Friday">F</th>
		<th scope="col" title="Saturday">S</th>
		
	</tr>

 
<tr>
<?php
	for($i = 1; $i < $numDays+1; $i++, $counter++){
		$timeStamp = strtotime("$year-$month-$i");
		if($i == 1){
			// Workout when the first day of the month is
			$firstDay = date_i18n("w", $timeStamp);
			for($j = 0; $j < $firstDay; $j++, $counter++){
				echo "<td>&nbsp;</td>";
			} 
		}
		if($counter % 7 == 0){
		?>
			</tr><tr>
        <?php
		}
        $day=strval($i);
        $arr_params = array ('month' => $month, 'd' => $i, 'year'=>$year, 'v'=>'1');
        $link =  add_query_arg($arr_params, get_page_link());
        //echo get_page_link() . '&month='. $month . '&day=' . $i . '&year=' . $year;
        
		?>
        <!--right here--><td width="50" <?php ER_Widget_hiLightEvt($month,$i,$year);?>><a href="<?php echo $link; ?>"><?php echo $i;?></a></td> 
    <?php
	}
?>
</tr>
	
	</table><br />
				

<?php
$month_name = ''; 
//if((isset($_GET['v']))||($t == $today)){  //Removed if clause to allow events to list when calendar first comes up for that day.
                        $month_no = $month;
                        if($month_no =="1"){$month_name ="Jan";}
                        if($month_no =="2"){$month_name ="Feb";}
                        if($month_no =="3"){$month_name ="Mar";}
                        if($month_no =="4"){$month_name ="Apr";}
                        if($month_no =="5"){$month_name ="May";}
                        if($month_no =="6"){$month_name ="Jun";}
                        if($month_no =="7"){$month_name ="Jul";}
                        if($month_no =="8"){$month_name ="Aug";}
                        if($month_no =="9"){$month_name ="Sep";}
                        if($month_no =="10"){$month_name ="Oct";}
                        if($month_no =="11"){$month_name ="Nov";}
                        if($month_no =="12"){$month_name ="Dec";}

            $sql="select * from ".$events_detail_tbl." where start_month = '" . $month_name ."' AND start_day ='".$d. "' AND start_year = '".$year."'" ;
            $result = mysql_query($sql);
            $numRows = mysql_num_rows($result);
            
            if($numRows == 0 ){
            	echo '<h3>No Events Scheduled For '.$month_name.' '.$d.'</h3>';
                }
                else{
            
                    echo '<h3>Events Scheduled For '.$month_name.' '.$d.'</h3>';
                    echo '';
                    	while($row = mysql_fetch_array($result)){
                                    ?>
                                    <div class="output">
                                    	<?php 
                                            global $event_cost, $more_info;
                                            
                                            $arr_params = array ('regevent_action'=>'register', 'event_id' => $row['id'], 'name_of_event'=> $row['event_name']);
                                            $link =  add_query_arg($arr_params, $calendar_url);
                                            
                                            if ($calendar_url != ""){
                                                    echo "<p align=left><b><u><a href='".$link."'>".$row['event_name']."</a></u></b></p>";
                                                    }
                                                else {
                                                    echo "<p align=left><b><u>".$row['event_name']."</u></b></p>";
                                                    }
                                            
                                    		echo "Location:<b>  ".$row['event_location']."</b><br>";
                                    		echo "Start Date:<b>  ".$row['start_date']."</b><br>";
                                    		echo "Price:<b>  ";
                                    		if ($event_cost != "0" || $event_cost != ""||$event_cost != "FREE" ||$event_cost != "0.00" ){
                                    		      echo $row['currency_format'];
                                    		      echo " ".$row['event_cost'];
                                                  }
                                                  else {
                                                    echo "Free Event";
                                                    }
                                            echo "</b><br>";
                                    		if ($more_info != ""){
                                    			 echo '<a href="'.$row['more_info'].'"> More Info...</a>';
                                    		     }
                                    		 
                                         if ($events_calendar_url != ""){
                                    		echo "<form name='form' method='post' action='".$events_calendar_url."'>";
                                    		echo "<input type='hidden' name='regevent_action' value='register'>";
                                           	echo "<input type='hidden' name='event_id' value='" . $row['id'] . "'>";
                                            echo "<input type='SUBMIT' value='REGISTER'></form></td></tr>"; 
                                            }
                                    ?>
                                    </div>
                                    
                                    <?php
                                }
                     }
    //}
echo "<hr />";
} 
 
function init_er_widget(){
register_sidebar_widget("Events Registraion", "events_view_widget"); 
register_sidebar_widget("Events Reg Calendar", "events_calendar_widget");  
//register_sidebar_widget("Events Reg Calendar", "er_show_calendar");  
}

?>