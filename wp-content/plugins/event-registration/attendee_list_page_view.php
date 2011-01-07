<?php 
//List Attendees - used for the {EVENTATTENDEES} tag
define("ER_PLUGINPATH", "/" . plugin_basename( dirname(__FILE__) ) . "/");

define("ER_PLUGINFULLURL", WP_PLUGIN_URL . ER_PLUGINPATH );

function display_attendees_by_event($atts) {
	extract(shortcode_atts(array('event_id' => 'No Event ID Supplied'), $atts));
	$event_id = "{$event_id}";
    ob_start();
    event_view_export_attendee_list_run($event_id);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
 } 

add_shortcode('EVENT_ATTENDEE_LIST', 'display_attendees_by_event');


function event_view_export_attendee_list_run($event_id){
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$event_id = $event_id;					
						
	$sql = "SELECT * FROM ". $events_detail_tbl . " WHERE id = '".$event_id."'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc ($result))
		{
		$event_id = $row['id'];
		$event_name = $row['event_name'];
		$event_desc = $row['event_desc'];
		echo "<h3>Attendee Listing For: <u>".$event_name."</u></h3>";
		echo htmlspecialchars_decode($event_desc)."<br><br><hr>";
	
	$x=1;					
	$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id' ORDER BY lname ASC";
	$eresult = mysql_query($sql);
	while ($erow = mysql_fetch_assoc ($eresult))
		{
	    $id = $erow['id'];
		$lname = $erow['lname'];
		$fname = $erow['fname'];
        $phone = $erow['phone'];
		$email = $erow['email'];
	
		echo $x. ".) " .$fname." ".$lname." | ".$email;
        if ($phone != ""){echo " | ".$phone."<br>";} else {echo "<br>"; }
		$x++ ;
		}
	}
           ?> <hr /><button style="background-color: lightgreen"
	onclick="window.location='<?php
		echo ER_PLUGINFULLURL . "event_registration_export.php?id=" . $event_id . "&action=excel";
		?>'"
	style="width:80; height: 30">Export Excel</button>
<button style="background-color: lightgreen"
	onclick="window.location='<?php
		echo ER_PLUGINFULLURL . "event_registration_export.php?id=" . $event_id. "&action=csv";
		?>'"
	style="width:80; height: 30">Export CSV</button>
<br>
<hr> <?php
}

switch ($_REQUEST['export']) {
 case "report";
	global $wpdb;
	
	$id= $_REQUEST['id'];
	$events_attendee_tbl = $_REQUEST['atnd'];
	$today = date("Y-m-d_Hi",time()); 
	
	$events_answer_tbl = get_option('events_answer_tbl');
	$events_question_tbl = get_option('events_question_tbl');
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$sql  = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$id'";
	$result = mysql_query($sql);
	list($event_id, $event_name, $event_description, $event_identifier, $event_cost, $is_active) = mysql_fetch_array($result, MYSQL_NUM);
	switch ($_REQUEST['action']) {
		case "excel";
					$st = "";
				$et = "\t";
				$s = $et . $st;
		
				$basic_header = array('Reg ID', 'Last Name', 'First Name', 'Email', 'Address', 
						'City', 'State', 'Zip', 'Phone', 'Payment Method', 'Reg Date');
				$question_sequence = array();


				$questions = $wpdb->get_results("select question, sequence from ".$events_question_tbl." where event_id = '$event_id' order by sequence");
				foreach ($questions as $question) {
					array_push($basic_header, $question->question);
					array_push($question_sequence, $question->sequence);
				}
	
				$participants = $wpdb->get_results("SELECT * from $events_attendee_tbl where event_id = '$event_id'");
				$filename = $event_name."-Attendees_". $today . ".xls";
			
			  header("Content-Disposition: attachment; filename=\"$filename\"");
			  header("Content-Type: application/vnd.ms-excel");
			  header("Pragma: no-cache"); 
			  header("Expires: 0"); 
	
				//echo header
				echo implode($s, $basic_header) . $et . "\r\n";
	
				//echo data
				if ($participants) {
					foreach ($participants as $participant) {
						echo $participant->id
						. $s . $participant->lname
						. $s . $participant->fname
						. $s . $participant->email
						. $s . $participant->address
						. $s . $participant->city
						. $s . $participant->state
						. $s . $participant->zip
						. $s . $participant->phone
						. $s . $participant->payment
						. $s . $participant->date;
						$answers = $wpdb->get_results("select a.answer from ".$events_answer_tbl." a join ".$events_question_tbl." q on   q.id = a.question_id where registration_id = '$participant->id' order by q.sequence");
		
						foreach($answers as $answer) {
							echo $s . $answer->answer;
						}
		
						echo $et . "\r\n";
					}
				} else {
					echo "<tr><td>No participant data has been collected.</td></tr>";
				}
				exit;
		break;
		
		case "payment";
				$st = "";
				$et = "\t";
				$s = $et . $st;

				$basic_header = array('Reg ID', 'Last Name', 'First Name', 'Email', 'Address', 'City', 'State', 'Zip', 'Phone', 'Payment Method', 'Reg Date', 'Pay Status', 'Type of Payment', 'Transaction ID', 'Payment', '# Attendees', 'Date Paid', 'Answers' );
				$question_sequence = array();
				
	
				$participants = $wpdb->get_results("SELECT * from $events_attendee_tbl where event_id = '$event_id'");
				$filename = $event_name."-Payments_". $today . ".xls";
			
			  header("Content-Disposition: attachment; filename=\"$filename\"");
			  header("Content-Type: application/vnd.ms-excel");
			  header("Pragma: no-cache"); 
			  header("Expires: 0"); 
	
				//echo header
				echo implode($s, $basic_header) . $et . "\r\n";
	
				//echo data
				if ($participants) {
					foreach ($participants as $participant) {
						echo $participant->id
						. $s . $participant->lname
						. $s . $participant->fname
						. $s . $participant->email
						. $s . $participant->address
						. $s . $participant->city
						. $s . $participant->state
						. $s . $participant->zip
						. $s . $participant->phone
						. $s . $participant->payment
						. $s . $participant->date
						. $s . $participant->payment_status
						. $s . $participant->txn_type
						. $s . $participant->txn_id
						. $s . $participant->amount_pd
						. $s . $participant->quantity
						. $s . $participant->payment_date
						;
						$answers = $wpdb->get_results("select a.answer from ".$events_answer_tbl." a join ".$events_question_tbl." q on   q.id = a.question_id where registration_id = '$participant->id' order by q.sequence");	
						foreach($answers as $answer) {
							echo $s . $answer->answer;
						}
		
						echo $et . "\r\n";
					}
				} else {
					echo "<tr><td>No participant data has been collected.</td></tr>";
				}
				exit;
		break;
		
		default:
		echo "This Is Not A Valid Selection!";
		break;
	}
	
	default:
	break;
}?>
