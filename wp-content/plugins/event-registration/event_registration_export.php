<?php


error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
 
/*
define( 'ABSPATH', '../../../' );

if ( file_exists( ABSPATH . 'wp-config.php') ) {

require_once( ABSPATH . 'wp-config.php' );}
*/
	
if ( file_exists( '../../../wp-config.php') ) {

require_once( '../../../wp-config.php'); 
	
	
global $wpdb;

$id= $_REQUEST['id'];
$events_attendee_tbl = $_REQUEST['atnd'];
$today = date("Y-m-d_Hi",time()); 

$events_answer_tbl = get_option('events_answer_tbl');
$events_question_tbl = get_option('events_question_tbl');
$events_detail_tbl = get_option('events_detail_tbl');
$current_event = get_option('current_event');
$events_attendee_tbl = get_option('events_attendee_tbl');
$events_payment_tbl = get_option ('events_payment_transactions_tbl');

$sql  = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$id'";
$result = mysql_query($sql);
list($event_id, $event_name, $event_description, $event_identifier, $event_cost, $allow_checks, $is_active) = mysql_fetch_array($result, MYSQL_NUM);


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
					$answers = $wpdb->get_results("select a.answer from ".$events_answer_tbl." a join ".$events_question_tbl." q on " .
							"q.id = a.question_id where registration_id = '$participant->id' order by q.sequence");
	
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

	case "csv";
			$st = "";
			$et = ",";
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
			//echo header
			header("Content-type: application/x-msdownload"); 
			header("Content-Disposition: attachment; filename=".$event_name."_".$today.".csv"); 
			header("Pragma: no-cache"); 
			header("Expires: 0"); 	
			echo implode($s, $basic_header) . "\r\n";

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
					$answers = $wpdb->get_results("select a.answer from ".$events_answer_tbl." a join ".$events_question_tbl." q on " .
							"q.id = a.question_id where registration_id = '$participant->id' order by q.sequence");
	
					foreach($answers as $answer) {
						echo $s . $answer->answer;
					}
	
					echo "\r\n";
				}
			} else {
				echo "<tr><td>No participant data has been collected.</td></tr>";
			}


		
			$filename = $event_name."-Attendees_".date("Y-m-d_H-i",time());
			print $csv_output;
			exit;
	break;
	
	
	case "payment";
			$st = "";
			$et = "\t";
			$s = $et . $st;
			
			$filename = $event_name."-Payments_". $today . ".xls";
            $participants = $wpdb->get_results("SELECT * from $events_attendee_tbl where event_id = '$event_id' ORDER BY lname DESC");
	
			$basic_header = array('Participant ID', 'Name (Last, First)', 'Email', 'Payment Type', 'Payment Method', 'Payment Amount',  'Transaction ID', 'Date Paid' );
			
		
		  header("Content-Disposition: attachment; filename=\"$filename\"");
		  header("Content-Type: application/vnd.ms-excel");
		  header("Pragma: no-cache"); 
		  header("Expires: 0"); 

			//echo header
			echo implode($s, $basic_header) . $et . "\r\n";

        if ($participants) {
				 foreach ($participants as $participant) 
                 {
    				$participant_id = $participant->id;
                    $first_name =$participant->fname;
                    $last_name =$participant->lname;
                    $name = $last_name.", ".$first_name;
                    $email = $participant->email;
                    $num_people = $participant->num_people;
                  
                    
                    
                  
                     $sql2= "SELECT SUM(mc_gross) FROM $events_payment_tbl WHERE payer_id='$participant_id'";
                    				$result2 = mysql_query($sql2);
                    	
                    				while($row = mysql_fetch_array($result2)){
                    					$total_paid =  $row['SUM(mc_gross)'];
                                       	}
                         
                         if ($cost >"0"){$balance = (($cost*$num_people)-$total_paid);
                         $balance = moneyFormat($balance);}  
                         
                          			
                    //$sql = "SELECT * from $events_payment_tbl WHERE payer_id = '$participant_id' AND item_number = '$event_id'";
        			$payments = $wpdb->get_results("SELECT * from $events_payment_tbl WHERE payer_id = '$participant_id' AND item_number = '$event_id'");
                    //$result = mysql_query($sql);
                    //while ($row = mysql_fetch_assoc ($result))
        			if ($payments) 
                    {
                        foreach ($payments as $payment) 
                     {
     						
                               echo $participant_id
            					. $s . $name
            					. $s . $email
                                . $s . $payment->payment_type
                                . $s . $payment->txn_type
                                . $s . moneyFormat($payment->mc_gross)
                                . $s . $payment->txn_id
                                . $s . $payment->payment_date;
            					echo $et . "\r\n"; 
                                                          
                                }
					}
                    else {
                        echo $participant_id
            					. $s . $name
            					. $s . $email
                                . $s . ""
                                . $s . ""
                                . $s . "No Payments Received"
                                . $s . ""
                                . $s . "";
            					echo $et . "\r\n"; 
                    }
                    
                }
                                        
            }
                              
            
              
              else 
              {
				echo "No Attendees Have Registered";
              }
              
			exit;
	break;
	
	
	default:
	echo "This Is Not A Valid Selection!";
}
}

else {echo "Report Folder configuration is not correct, please email consultant@avdude.com for configuration assistance.";}

?>