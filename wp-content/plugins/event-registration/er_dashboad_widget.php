<?php

/**
 * @author David Fleming
 * @copyright 2010
 */


/**
 * Content of Dashboard-Widget
 */
function events_dashboard_window() {

    er_dashboard_menu();
    er_dashboard_events_listing();    
}
 
/**
 * add Dashboard Widget via function wp_add_dashboard_widget()
 */
function er_dashboard_setup() {
	wp_add_dashboard_widget( 'events_dashboard_window', __( '<a href ="admin.php?page=events-registration/event_regis.php"><b> EVENTS REGISTRATION DASHBOARD</b></a>' ), 'events_dashboard_window' );
}
 
/**
 * use hook, to integrate new widget
 */
add_action('wp_dashboard_setup', 'er_dashboard_setup');



//Add Dashboard Functions

function er_dashboard_menu(){
        ?>
 <div><p><ul id="eventsnav">
   <li><a href="admin.php?page=events">View Events</a></li>
   <li><a href="admin.php?page=attendees">View Attendees</a></li>
   <li><a href="admin.php?page=import">Import Events</a></li>
  <li><a href="admin.php?page=attendee">Payments</a></li>
  <li><a href="admin.php?page=support">Support</a></li>
</ul></p></div>
<br />
<br />
<?php
}

function er_dashboard_events_listing(){
    	global $wpdb;
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$events_listing_type = get_option ( 'events_listing_type' );
    
    
  ?>  <table style="width:99%;" class="events_dashboard_window">
            <thead>
                <tr  style="text-align:left">
                     <th><font color="green"><b>Next 5 Upcoming Events </b></font> </th><th><form name='form' method='post' action='/Plugin_Test/wp-admin/admin.php?page=events'><input type='hidden' name='action' value='add_new'><INPUT CLASS='button-primary' TYPE='SUBMIT' VALUE='ADD NEW EVENT'></form></th>
                     </tr>
                     </thead> 
                     <tbody>
               
               <?php 
			  $sql = "SELECT * FROM $events_detail_tbl WHERE start_date >= '".date ( 'Y-m-d' )."' ORDER BY date(start_date) LIMIT 5"; 
			  $result = mysql_query ($sql);
			  while ($row = mysql_fetch_assoc ($result)){
				$event_id=$row['id'];
				$event_name=$row['event_name'];   
				$reg_limit = $row['reg_limit'];
				$start_date =$row['start_date'];
                $start_time = $row['start_time'];
	
                 
                if ($start_date !=""){$newStart = date("F j, Y", strtotime($start_date));}
                    else {$newStart = "";} 
                if ($start_time !=""){$newTime =  date("g:i a", strtotime($start_time));}
                    else {$newTime = "";}

				
				$sql2= "SELECT SUM(num_people) FROM $events_attendee_tbl WHERE event_id='$event_id'";
				$result2 = mysql_query($sql2);
	
				while($row = mysql_fetch_array($result2)){
					$number_attendees =  $row['SUM(num_people)'];
				}
				
				if ($number_attendees == '' || $number_attendees == 0){
					$number_attendees = '0';
				}
				if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){
					$reg_limit = "Unlimted";
				}
				?>
                
                <tr>
                	<td style="text-align:left; padding:2px">
                    <a title="View event" href="admin.php?page=events&event_id=<?php echo $event_id?>&action=get_details"><?php echo $event_name?></a><br />&nbsp;&nbsp;&nbsp;  <?php echo $newStart;?> @ <?php echo $newTime?> <hr /></td><td> 
                    <a href="admin.php?page=attendees&action=view&event=<?php echo $event_id;?>">Attendees</a> <br />&nbsp;&nbsp;<?php echo $number_attendees?> / <?php echo $reg_limit?>
                    
                    
                    </td>
                </tr>
                <?php
			  }
			   ?>
            </tbody>
        </table>
        
        <table style="width:99%;" class="events_dashboard_window">
            <thead>
                <tr  style="text-align:left">
                     <th><font color="red" ><b>Last 3 Events</b></font></th><th></th>
                     </tr>
                     </thead>
                     <tbody>
               
               <?php 
			  //$sql = "SELECT * FROM ".get_option('events_detail_tbl')." WHERE start_date < '".date ( 'Y-m-d' )."' ORDER BY date(start_date) DESC LIMIT 3"; 
			  $sql = "SELECT * FROM " . get_option('events_detail_tbl') ." WHERE str_to_date(start_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e') DESC LIMIT 3";
              
              $result = mysql_query ($sql);
			  while ($row = mysql_fetch_assoc ($result)){
				$event_id=$row['id'];
				$event_name=$row['event_name'];   
				$reg_limit = $row['reg_limit'];
				$start_date =$row['start_date'];
                                 
                if ($start_date !=""){$newStart = date("F j, Y", strtotime($start_date));}
                    else {$newStart = "";} 
                if ($start_time !=""){$newTime =  date("g:i a", strtotime($start_time));}
                    else {$newTime = "";}
	
				
				$sql2= "SELECT SUM(num_people) FROM " . get_option('events_attendee_tbl') . " WHERE event_id='$event_id'";
				$result2 = mysql_query($sql2);
	
				while($row = mysql_fetch_array($result2)){
					$number_attendees =  $row['SUM(num_people)'];
				}
				
				if ($number_attendees == '' || $number_attendees == 0){
					$number_attendees = '0';
				}
				
				if ($reg_limit == "" || $reg_limit == " " || $reg_limit == "999"){
					$reg_limit = "Unlimited";
				}
				?>
                
                <tr>
                	<td style="text-align:left; padding:2px">
                    <a title="View event" href="admin.php?page=events&event_id=<?php echo $event_id?>&action=get_details"><?php echo $event_name?></a><br />&nbsp;&nbsp;&nbsp;  <?php echo $newStart;?> @ <?php echo $newTime?> <hr /></td><td> 
                    <a href="admin.php?page=attendees&action=view&event=<?php echo $event_id;?>">Attendees:</a><br />&nbsp;&nbsp; <?php echo $number_attendees?> / <?php echo $reg_limit?>
                    
                   
                    </td>
                </tr>
                <?php
			  }
			   ?>
            </tbody>
        </table>
<?php
}

?>