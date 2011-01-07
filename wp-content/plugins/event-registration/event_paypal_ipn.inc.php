<?php

function event_paypal_txn(){
//you can load your paypal IPN processing script here
/*	$id="";
	$id=$_REQUEST['id'];
	if ($id ==""){echo "ID not supplied.";}
	else{
	
				global $wpdb;
						$events_detail_tbl = get_option('events_detail_tbl');
						$events_attendee_tbl = get_option('events_attendee_tbl');
						$event_id = $_REQUEST['event_id'];
						$today = date("m-d-Y");
		
						if ( $_REQUEST['form_action'] == 'payment' ){
		
								if ( $_REQUEST['attendee_action'] == 'post_payment' ){
									
										    $id = $_REQUEST['id'];
											$payment_status = $_REQUEST['payment_status'];
											$txn_type = $_REQUEST['txn_type'];
											$txn_id = $_REQUEST['txn_id'];
											$amount_pd = $_REQUEST['payment_gross'];
											$quantity = $_REQUEST['quantity'];
											$payment_date = $_REQUEST['payment_date'];
				
								   	$sql="UPDATE ". $events_attendee_tbl . " SET payment_status = '$payment_status', txn_type = '$txn_type', txn_id = '$txn_id', amount_pd = '$amount_pd', quantity = '$quantity', payment_date ='$payment_date' WHERE id ='$id'";
									$wpdb->query($sql);
									
									//Email Confirmation to Attendee
									$query  = "SELECT * FROM $events_attendee_tbl WHERE id='$id'";
									$result = mysql_query($query) or die('Error : ' . mysql_error());
									while ($row = mysql_fetch_assoc ($result))
										{
											$email = $row['email'];
											$fname = $row['fname'];
											$lname = $row['lname'];
										}
									
									$query  = "SELECT * FROM $events_detail_tbl WHERE id='$event_id'";
									$result = mysql_query($query) or die('Error : ' . mysql_error());
									while ($row = mysql_fetch_assoc ($result))
										{
											$event_name = $row['event_name'];
										}
															
									$subject = "Event Payment Received";
									$message=("***This Is An Automated Response*** \r\n\nThank You ".$fname." ".$lname.".  We have just  received a payment in the amount of $".$amount_pd." for your registration to ".$event_name.".");
									wp_mail($email, $subject, $message); 
								}
						}
	}
}
*/

?>