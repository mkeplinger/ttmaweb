<?php
function moneyFormat($number, $currencySymbol = '', $decPoint = '.', $thousandsSep = ',', $decimals = 2) {
return $currencySymbol . number_format($number, $decimals,
$decPoint, $thousandsSep);
}
require_once "er_payment_functions.php";

function event_process_payments(){
er_plugin_menu();
            function list_attendee_payments() {
            		//Displays attendee information from current active event.
            		global $wpdb;
            		$events_detail_tbl = get_option ( 'events_detail_tbl' );
            		$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
            		$event_id = $_REQUEST ['event_id'];
            		
            		$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$event_id'";
            		$result = mysql_query ( $sql );
            		while ( $row = mysql_fetch_assoc ( $result ) ) {
            			$event_id = $row ['id'];
            			$event_name = $row ['event_name'];
            			$event_desc = $row ['event_desc'];
            			$event_description = $row ['event_desc'];
            			$identifier = $row ['event_identifier'];
            			$event_cost = $row ['event_cost'];
                        $use_coupon = $row['use_coupon'];
                        $coupon_code = $row['coupon_code'];
                        $coupon_code_price = $row['coupon_code_price'];
            			$checks = $row ['allow_checks'];
            			$active = $row ['is_active'];
            		}
            		
            		$sql = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id' ORDER BY lname DESC";
            		$result = mysql_query ( $sql );
            		 ?>
                        <div id="event_reg_theme" class="wrap">
                        <h2>Attendee Payments For <?php echo $event_name;?></h2>
                        <div style="clear:both;"></div><hr /><div style="clear:both;"><br />
            
                        <?php	
                    
                    
            		define ( "EVNT_RGR_PLUGINPATH", "/" . plugin_basename ( dirname ( __FILE__ ) ) . "/" );
            		define ( "EVNT_RGR_PLUGINFULLURL", WP_PLUGIN_URL . EVNT_RGR_PLUGINPATH );
            		$url = EVNT_RGR_PLUGINFULLURL;
            		
            		?>
            <button style="background-color: lightgreen"
            	onclick="window.location='<?php
            		echo $url . "event_registration_export.php?id=" . $event_id . "&action=payment";
            		?>'"
            	style="width:180; height: 30">Export Event Payment List To Excel</button>  
                
                        <table class="widefat">
                        <thead><tr><th>Attendee ID</th><th>Name</th><th>Email</th><th>Balance</th><th>Payments Recieved</th><th>Action</th></tr></thead>
                        <tbody>
            <?php
            		while ( $row = mysql_fetch_assoc ( $result ) ) {
            			$id = $row ['id'];
            			$lname = $row ['lname'];
            			$fname = $row ['fname'];
            			$email = $row ['email'];
                        $num_people = $row['num_people'];
                        $coupon = $row['coupon'];
            			
                       	$payment_table = get_option ('events_payment_transactions_tbl');
            			$payments = $wpdb->get_results ( "SELECT * from $payment_table where payer_id = $id order by payment_date" );
            			if ($payments) {
            				$transactions="";
                            foreach ( $payments as $payment ) {
            				    
            					$transactions .=  $payment->payment_date." - " .$payment->txn_type . " (" . $payment->txn_id . "): ".$payment->mc_gross;
                                $transactions .="| 
                                <a href='admin.php?page=attendee&action=edit&p_id=".$payment->id."&id=".$id."'><b>EDIT</a> | 
                                <a href='admin.php?page=attendee&action=delete&p_id=".$payment->id."&event_id=".
                                $event_id."' ONCLICK=\"return confirm('Are you sure you want to delete the ".moneyFormat($payment->mc_gross)." payment for ".$fname." ".$lname."?')\" >DELETE</a></b><br>";}
                                }
                         else {$transactions =  "No Payments Recieved";}    
                            
                         $sql2= "SELECT SUM(mc_gross) FROM $payment_table WHERE payer_id='$id'";
                    				$result2 = mysql_query($sql2);
                    	
                    				while($row = mysql_fetch_array($result2)){
                    					$total_paid =  $row['SUM(mc_gross)'];
                                       	}
                         
                         if ($use_coupon =="Y" && $event_cost > "0" ) {
                            if ($coupon == $coupon_code) {$discount = $coupon_code_price;
                                                          $has_coupon = "Y";}
                            else {$discount = "0";
                            $has_coupon = "N";}
                            }
                         
                         if ($event_cost >"0" ){$balance = ((($event_cost-$discount)*$num_people)-$total_paid);
                         $balance = moneyFormat($balance);}
                         else if ($event_cost ==""){$balance = "Free Event";}
                         else {$balance = "Free Event";}
                         
                                				                          
                        
                        
            			
            			echo "<tr><td>" . $id . "</td><td align='left'>" . $lname . ", " . $fname . " (".$num_people.") ";
                        if ($has_coupon == "Y"){echo "<font color ='red'>COUPON</font>";}
                        echo "</td><td>" . $email . "</td><td>".$balance."</td><td>" . $transactions . "</td>";
            			echo "<td>";
            			echo "<form name='form' method='post' action='" . $_SERVER ['REQUEST_URI'] . "'>";
            			echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
            			echo "<input type='hidden' name='attendee_pay' value='paynow'>";
            			echo "<input type='hidden' name='action' value='add'>";
            			echo "<input type='hidden' name='id' value='" . $id . "'>";
            			// echo "<INPUT TYPE='SUBMIT' VALUE='ENTER PAYMENT' ONCLICK=\"return confirm('Are you sure you want to enter a payment for 	".$fname." ".$lname."?')\"></form>";
            			echo "<INPUT TYPE='SUBMIT' VALUE='ENTER PAYMENT'></form>";
            			
            			echo "</td></tr>";
            		}
            		echo "</table>";
            }

//function enter_attendee_payments() {
        global $wpdb;
		$events_detail_tbl = get_option ( 'events_detail_tbl' );
		$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
        $events_payment_tbl = get_option ('events_payment_transactions_tbl');
		$event_id = $_REQUEST ['event_id'];
		$today = date ( "m-d-Y" );
		
        $action = $_REQUEST ['action'];	        
switch ($action){
        
        case "view":
      
            list_attendee_payments ();
    
        break;
        
        case "add":

                		$attendee_id = $_REQUEST ['id'];
                        $events_detail_tbl = get_option ( 'events_detail_tbl' );
		                $events_attendee_tbl = get_option ( 'events_attendee_tbl' );
                        $payment_table = get_option ('events_payment_transactions_tbl');
                        
        				$sql = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='$attendee_id'";
        				$result = mysql_query ( $sql );
        				while ( $row = mysql_fetch_assoc ( $result ) ) {
        					$id = $row ['id'];
                            $attendee_id = $row ['id'];
        					$lname = $row ['lname'];
        					$fname = $row ['fname'];
        					$address = $row ['address'];
        					$city = $row ['city'];
        					$state = $row ['state'];
        					$zip = $row ['zip'];
        					$email = $row ['email'];
        					$phone = $row ['phone'];
        					$event_id = $row ['event_id'];
        				    $num_people = $row['num_people'];
                            $coupon = $row['coupon'];
        				}
        				
        			$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$event_id'";
            		$result = mysql_query ( $sql );
            		while ( $row = mysql_fetch_assoc ( $result ) ) {
            			$event_id = $row ['id'];
            			$event_name = $row ['event_name'];
            			$event_desc = $row ['event_desc'];
            			$event_description = $row ['event_desc'];
            			$identifier = $row ['event_identifier'];
            			$event_cost = $row ['event_cost'];
                        $coupon_code = $row['coupon_code'];
                        $use_coupon = $row['use_coupon'];
                        $coupon_code_price = $row['coupon_code_price'];
            			$checks = $row ['allow_checks'];
            			$active = $row ['is_active'];
            		}	   
        			
                    
                   
                    	
     				   $sql2= "SELECT SUM(mc_gross) FROM $payment_table WHERE payer_id='$attendee_id'";
                                     				$result2 = mysql_query($sql2);
                    	
                    				while($row = mysql_fetch_array($result2)){
                    					$total_paid =  $row['SUM(mc_gross)'];
                                       	}
                                              
                         if ($use_coupon =="Y" && $event_cost > "0" ) {
                                if ($coupon == $coupon_code) {$discount = $coupon_code_price;}
                                else {$discount = "0";}
                            }
                         
                         if ($event_cost > "0" ){$balance = ((($event_cost-$discount)*$num_people)-$total_paid);
                         $balance = moneyFormat($balance);}
                         else if ($event_cost ==""){$balance = "Free Event";}
                         else {$balance = "Free Event";}    				
        				
        				?>
         <div id="event_reg_theme" class="wrap">
                        <h2>Attendee Payments For <?php echo $event_name;?></h2>
                        <div style="clear:both;"></div><hr /><div style="clear:both;"><br />   
        <div id="event_regis-col-left">
            <div class="box-mid-head">
                <h2 class="events_reg f-wrench">Enter Attendee Payment for <?php echo $event_name." - ".$fname . " " . $lname." - - -  Balance Due: ".$balance;?></h2>
            </div>
                    <div class="box-mid-body" id="toggle2">
					   <div class="padding">
                        <ul id="event_regis-sortables">
                        <?php echo "<form method='post' action='" . $_SERVER ['REQUEST_URI'] . "'>"; ?>

        				<li>Payment Received Date: <input name="payment_date" size="45"	value="<?php echo $today;?>"></li>
                        <li>Amount Paid: <input name="mc_gross" size="45">
                        <?php echo "    ";?>
                        Payment Type <select name="payment_type">
                                                <option value="full">Full Payment</option>
                                                  <option value="partial">Partial Payment</option>
                                                  <option value="deposit">Deposit</option>
                                                  <option value="donation">Donation</option>
                                                  <option value="modify">Modification</option>
                                                  <option value="cancel">Cancelation</option>
                                                  <option value="refund">Refund</option>  
                                                </select></li>
                        
                        <li>Payment Method: <select name="txn_type">
                                                  <option value="online">Online</option>
                                                  <option value="check">Check</option>
                                                  <option value="cash">Cash</option>
                                                  <option value="credit">Event Credit</option>
                                                </select> 
                        <?php echo "    ";?>
                        Transaction ID: <input name="txn_id" size="45"> </li>
                        
        <hr />
        <br />
        <li>Do you want to send a payment recieved notice to the payer?
        <INPUT TYPE='radio' NAME='send_payment_rec' CHECKED VALUE='send_message'> Yes
        <INPUT TYPE='radio' NAME='send_payment_rec' VALUE='N'>No</li>
        <br />
        <hr />
        <p align="center">
        <?php
        				echo "<input type='hidden' name='id' value='" . $id . "'>";
        				echo "<input type='hidden' name='form_action' value='payment'>";
                        
                        echo "<input type='hidden' name='first_name' value='".$fname."'>";
                        echo "<input type='hidden' name='last_name' value='".$lname."'>";
                        echo "<input type='hidden' name='address_name' value='".$fname." ".$lname."'>";
                        echo "<input type='hidden' name='address_street' value='".$address."'>";
                        echo "<input type='hidden' name='address_city' value='".$city."'>";
                        echo "<input type='hidden' name='address_state' value='".$state."'>";
                        echo "<input type='hidden' name='address_zip' value='".$zip."'>";
                        echo "<input type='hidden' name='address_country' value='".$state."'>";
                        echo "<input type='hidden' name='address_status' value='VALID'>";  
                        echo "<input type='hidden' name='item_name' value='".$event_id."'>";   
                        echo "<input type='hidden' name='item_number' value='".$event_id."'>";  
                        echo "<input type='hidden' name='quantity' value='1'>";        
        				echo "<input type='hidden' name='attendee_pay' value='paynow'>";
        				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
        				echo "<input type='hidden' name='action' value='post'>";
        				?>
        </hr><p><input type="submit" name="Submit" value="POST PAYMENT"></p>
        </form>
        
        </p></ul></div></div></div></div></div>
        <?php
        break;
        
        case "post":
        
                $payer_id = $_REQUEST['id'];
                $event_id = $_REQUEST ['event_id'];
                $first_name = $_REQUEST['first_name'];
                $last_name = $_REQUEST['last_name'];
                $payer_email = $_REQUEST['payer_email'];
                //$payer_email = $_REQUEST ['email'];
                $txn_id = $_REQUEST['txn_id'];
                $payment_type = $_REQUEST['payment_type'];
                //$payment_type = $_REQUEST ['txn_type'];
                $item_name = $_REQUEST['item_name'];
                $item_number = $_REQUEST['item_number'];
                $quantity = $_REQUEST['quantity'];
                
                $payment_amount = $_REQUEST ['amt_pd'];
				$payer_status = $_REQUEST['payer_status'];
                $payment_status = $_REQUEST['payment_status'];
                //$paystatus = $_REQUEST ['paystatus'];
				$txn_type = $_REQUEST['txn_type'];
               // $txn_type = $_REQUEST ['txn_type'];
				$mc_currency = $_REQUEST['mc_currency'];
                $currency_format =$_REQUEST['mc_currency'];
				$memo = $_REQUEST['memo'];
                //$notes = $_REQUEST['memo'];
				$payment_date = $_REQUEST['payment_date'];
                //$payment_date = $_REQUEST ['date_pd'];
                
                
            		
                			if (isset($_REQUEST['mc_gross'])){
                						$amount_pd = $_REQUEST['mc_gross'];
                                       
                					}else{
                						$amount_pd = $_REQUEST['payment_gross'];
                					}
                			$mc_gross=$amount_pd;
                			$address_name = $_REQUEST['address_name'];
                			$address_street = nl2br($_REQUEST['address_street']);
                			$address_city = $_REQUEST['address_city'];
                			$address_state = $_REQUEST['address_state'];
                			$address_zip = $_REQUEST['address_zip'];
                			$address_country = $_REQUEST['address_country'];
                			$address_status = $_REQUEST['address_status'];
                			$payer_business_name = $_REQUEST['payer_business_name'];
                			
                			$pending_reason = $_REQUEST['pending_reason'];
                			$reason_code = $_REQUEST['reason_code'];
                			
                
				
			/*	$sql = "UPDATE " . $events_payment_tbl . " SET paymentstatus = '$paystatus', txn_type = '$txn_type', 
			//						   txn_id = '$txn_id', mc_gross = '$amt_pd', payment_date ='$date_pd' WHERE payer_id ='$id'";
			
            $sql="INSERT into $events_payment_tbl (payer_id, event_id, txn_id, payer_email, first_name, last_name, payment_type, mc_gross, mc_currency, memo, payment_date,) values 
                ('".$payer_id."', '".$event_id."','".$txn_id."','".$payer_email."', '".$first_name."','".$last_name."','".$payment_type."',
                '".$payment_amount."', '".$currency_format."','".$notes."', '".$payment_date."')";
			      echo $sql;                
            	$wpdb->query ( $sql );
				//	Send Payment Recieved Email
				
                */
$qry  = "INSERT INTO ".$events_payment_tbl." VALUES (0, '".$payer_id."', '".$event_id."','".$payment_date."', '".$txn_id."',";
				$qry .= "'".$first_name."', '".$last_name."', '".$payer_email."', '".$payer_status."',";
				$qry .= "'".$payment_type."', '".$memo."', '".$item_name."', '".$item_number."',";
				$qry .= "'".$quantity."', '".$mc_gross."', '".$mc_currency."', '".$address_name."',";
				$qry .= "'".$address_street."', '".$address_city."', '".$address_state."',";
				$qry .= "'".$address_zip."', '".$address_country."', '".$address_status."',";
				$qry .= "'".$payer_business_name."', '".$payment_status."', '".$pending_reason."',";
				$qry .= "'".$reason_code."', '".$txn_type."')";
				
				$wpdb->query( $wpdb->prepare( $qry )) or die(mysql_error());  
                
                     
                

				if ($_REQUEST ['send_payment_rec'] == "send_message") {
					
					$sql = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='$payer_id'";
					$result = mysql_query ( $sql );
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
						$event_id = $row ['event_id'];
                        $coupon= $row['coupon'];
				
					}
					
					$events_organization_tbl = get_option ( 'events_organization_tbl' );
					$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
					// $sql  = "SELECT * FROM wp_events_organization WHERE id='1'"; 
					$result = mysql_query ( $sql );
					while ( $row = mysql_fetch_assoc ( $result ) ) {
						$return_url = $row ['return_url'];
					}
					if($return_url >""){$payment_link = $return_url . "&id=" . $id;}
                    $payment_text = "To make payment or view your payment information go to: " . $payment_link;
					$subject = "Event Payment Received";
					$distro = $email;
                    
					$message = ("***This Is An Automated Response***   Thank You $fname $lname.  We have received a payment in the amount of $ ".$amount_pd." for your event registration.  " . $payment_text);
					
					wp_mail ( $distro, $subject, $message );
					
					echo "<p>Payment Received notification sent to $fname $lname.</p>";
				
				}
				
			
            ?>

            <META HTTP-EQUIV="refresh" content="0;URL=admin.php?page=attendee&action=view&event_id=<?php echo $event_id . "&event_name=" . $event_name;?>">
            <?php
        
        break;
        
        case "edit":

                    $payment_table = get_option ('events_payment_transactions_tbl');
                    $payment_id = $_REQUEST['p_id'];
                    $attendee_id = $_REQUEST ['id'];
                    $sql = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='$attendee_id'";
    				$result = mysql_query ( $sql );
        				while ( $row = mysql_fetch_assoc ( $result ) ) {
        					$id = $row ['id'];
        					$lname = $row ['lname'];
        					$fname = $row ['fname'];
        					$email = $row ['email'];
        					$event_id = $row ['event_id'];
                            $num_people = $row['num_people'];
                            $coupon = $row['coupon'];
        				}
        				
        			$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$event_id'";
            		$result = mysql_query ( $sql );
            		while ( $row = mysql_fetch_assoc ( $result ) ) {
            			$event_id = $row ['id'];
            			$event_name = $row ['event_name'];
            			$event_desc = $row ['event_desc'];
            			$event_description = $row ['event_desc'];
            			$identifier = $row ['event_identifier'];
            			$event_cost = $row ['event_cost'];
                        $use_coupon = $row['use_coupon'];
                        $coupon_code = $row['coupon_code'];
                        $coupon_code_price = $row['coupon_code_price'];
            			$checks = $row ['allow_checks'];
            			$active = $row ['is_active'];
            		}	   
                            
                    $payments = $wpdb->get_results ( "SELECT * from $payment_table where id = '$payment_id'" );
           			if ($payments) {$transactions="";
                            foreach ( $payments as $payment ) {
            				    	$payment_date =  $payment->payment_date;
                                    $payment_type =  $payment->payment_type;
                                    $payment_method = $payment->txn_type;
                                    $payment_txn_id = $payment->txn_id;
                                    $payment_amount = $payment->mc_gross;
                                    $payment_date = $payment->paydate;
                                    
                                    }
                             }
                            
                         $sql2= "SELECT SUM(mc_gross) FROM $payment_table WHERE payer_id='$id'";
                    				$result2 = mysql_query($sql2);
                    	
                    				while($row = mysql_fetch_array($result2)){
                    					$total_paid =  $row['SUM(mc_gross)'];
                                       	}
                         
                        if ($use_coupon =="Y" && $event_cost > "0" ) {
                            if ($coupon == $coupon_code) {$discount = $coupon_code_price;}
                            else { $discount = "0";}
                            }
                         
                         if ($event_cost >"0" ){$balance = ((($event_cost-$discount)*$num_people)-$total_paid);
                         $balance = moneyFormat($balance);}
                         else if ($event_cost ==""){$balance = "Free Event";}
                         else {$balance = "Free Event";}
                         
               				?>
            
        <div id="event_regis-col-left">
            <div class="box-mid-head">
                <h2 class="events_reg f-wrench">Edit Attendee Payment for <?php echo $fname . " " . $lname."              Balance Due: ".$balance;?> </h2>
            </div>
                    <div class="box-mid-body" id="toggle2">
					   <div class="padding">
                        <ul id="event_regis-sortables">
                        <?php echo "<form method='post' action='" . $_SERVER ['REQUEST_URI'] . "'>"; ?>

        				<li>Payment Received Date: <input name="payment_date" size="45"	value="<?php
                            				if ($payment_date != "") {
                            					echo $payment_date;
                            				}
                            				if ($payment_date == "") {
                            					echo $today;
                            				}?>"></li>
                        <li>Amount Paid: <input name="mc_gross" size="45" value="<?php echo $payment_amount;?>">
                        <?php echo "    ";?>
                        Payment Type <select name="payment_type">
                                                  <option value="<?php	echo $payment_type;?>"><?php	echo $payment_type;?></option>
                                                  <option value="full">Full Payment</option>
                                                  <option value="partial">Partial Payment</option>
                                                  <option value="deposit">Deposit</option>
                                                  <option value="donation">Donation</option>
                                                  <option value="modify">Modification</option>
                                                  <option value="cancel">Cancelation</option>
                                                  <option value="refund">Refund</option>  
                                                </select></li>
                        
                        <li>Payment Method: <select name="txn_type">
                                                  <option value="<?php	echo $payment_method;?>"><?php	echo $payment_method;?></option>
                                                  <option value="online">Online</option>
                                                  <option value="check">Check</option>
                                                  <option value="cash">Cash</option>
                                                  <option value="credit">Event Credit</option>
                                                </select> 
                        <?php echo "    ";?>
                        Transaction ID: <input name="txn_id" size="45" value="<?php	echo $payment_txn_id;?>"> </li>
                        
        <hr />
        <li>Do you want to send a payment recieved notice to the payer?
        <INPUT TYPE='radio' NAME='send_payment_rec' CHECKED VALUE='send_message'>
        Yes
        <INPUT TYPE='radio' NAME='send_payment_rec' VALUE='N'>
        No
        </li>
        <?php
        				echo "<input type='hidden' name='id' value='" . $attendee_id . "'>";
                        echo "<input type='hidden' name='p_id' value='" . $payment_id . "'>";
        				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
                        echo "<input type='hidden' name='first_name' value='".$fname."'>";
                        echo "<input type='hidden' name='last_name' value='".$lname."'>";
                        echo "<input type='hidden' name='address_name' value='".$fname." ".$lname."'>";
                        echo "<input type='hidden' name='address_street' value='".$address."'>";
                        echo "<input type='hidden' name='address_city' value='".$city."'>";
                        echo "<input type='hidden' name='address_state' value='".$state."'>";
                        echo "<input type='hidden' name='address_zip' value='".$zip."'>";
                        echo "<input type='hidden' name='address_country' value='".$state."'>";
                        echo "<input type='hidden' name='address_status' value='VALID'>";  
                        echo "<input type='hidden' name='item_name' value='".$event_id."'>";   
                        echo "<input type='hidden' name='item_number' value='".$event_id."'>";  
                        echo "<input type='hidden' name='quantity' value='1'>";        
                        echo "<input type='hidden' name='action' value='update'>";
        				?>
        </hr><p><input type="submit" name="Submit" value="UPDATE PAYMENT"></p>
        </form>
        
        </ul></div></div></div>
        <?php
                   
        
        break;
        
        case "update":
                $payment_table = get_option ('events_payment_transactions_tbl');
                $payment_id = $_REQUEST['p_id'];
                $payment_date = $_REQUEST['payment_date'];
                if (isset($_REQUEST['mc_gross'])){
				    $amount_pd = $_REQUEST['mc_gross'];
                    }else{
				    $amount_pd = $_REQUEST['payment_gross'];
   					}
     			$mc_gross=$amount_pd;
                $payment_type = $_REQUEST['payment_type'];
                $txn_type = $_REQUEST['txn_type'];
                $txn_id = $_REQUEST['txn_id'];
                $memo = $_REQUEST['memo'];
                
                $payer_id = $_REQUEST['id'];
                $event_id = $_REQUEST ['event_id'];
  
				
                
                
                			
                $wpdb->query ( "UPDATE $payment_table set `payment_date` = '$payment_date', `mc_gross` = '$mc_gross',
                `payment_type` = '$payment_type', `txn_type` = '$txn_type', `txn_id` = '$txn_id' where id = $payment_id " );
		
            ?>           
            <div id="message" class="updated fade">
            <p><strong>The Payment has been updated.</strong></p></div>
            <META HTTP-EQUIV='refresh' content='2;
            URL=admin.php?page=attendee&action=view&event_id=<?php echo $event_id;?>'>
            <?php
        break;
        
        case "delete":
        
            global $wpdb;
    		$payment_table = get_option ('events_payment_transactions_tbl');
            $payment_id = $_REQUEST['p_id'];
            $event_id = $_REQUEST['event_id'];
            $wpdb->query ( "DELETE from $payment_table where id = '$payment_id'" );
            ?>           
            <div id="message" class="updated fade">
            <p><strong>The Payment has been deleted.</strong></p></div>
            <META HTTP-EQUIV='refresh' content='2;
            URL=admin.php?page=attendee&action=view&event_id=<?php echo $event_id;?>'>
            <?php
    
        break;
        
        default:
        
     
        
        //query event list with select option
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
            $events_payment_tbl = get_option ('events_payment_transactions_tbl');
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
            
            ?>
            <div id="event_reg_theme" class="wrap">
            <h2>Manage Event Payments</h2>
            <div style="clear:both;"></div><hr /><div style="clear:both;"><br />
            <table class="widefat">
            <thead><tr><th>Event</th><th>Attendees</th><th>Revenue</th></tr></thead>
            <tbody>
            <?php			
			$curdate = date("Y-m-d");
            if ($_REQUEST['events'] == "current"){
    		$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) >= '".$curdate."' ORDER BY date(start_date) ASC";}
    		else if ($_REQUEST['events'] == "expired"){
    		$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) <= '".$curdate."' ORDER BY date(start_date) ASC";}
            else {$sql = "SELECT * FROM ". get_option('events_detail_tbl') ." WHERE date(start_date) >= '".$curdate."' ORDER BY date(start_date) ASC";}
        
        $result = mysql_query ($sql);
			$result = mysql_query ( $sql );
			while ( $row = mysql_fetch_assoc ( $result ) ) {
				$id = $row ['id'];
				$name = $row ['event_name'];
                $desc = $row['event_desc'];
                        $sql2= "SELECT SUM(num_people) FROM " . get_option('events_attendee_tbl') . " WHERE event_id='$id'";
        				$result2 = mysql_query($sql2);
        	
        				while($row = mysql_fetch_array($result2)){
        					$number_attendees =  $row['SUM(num_people)'];
                            $revenue = $row['SUM(amount_pd)'];
        				}
        				if ($number_attendees == "" || $number_attendees == 0){
        					$number_attendees = "0";
        				}
                        
  				      $sql3= "SELECT SUM(mc_gross) FROM $events_payment_tbl WHERE item_number ='$id'";
                      
     				  $result3 = mysql_query($sql3);
                    	 				while($row = mysql_fetch_array($result3)){
                    					$total_revenue =   moneyFormat($row['SUM(mc_gross)']);
                                       	}
                                                 
                                                
                        
                
			    echo "<tr><td><a href='admin.php?page=attendee&action=view&event_id=".$id."&event_name=".$name."'>".
                    $name."</a></td><td>";
           if ($number_attendees > "0" ){echo $number_attendees;}    
           else {echo "<font color='red'>No Attendees</font>";}
            echo "</td><td>".$total_revenue."</td></tr>";
			}
			
			echo "</tbody></table></div>";

        break;
}        
       
}
//}
//Used for return payment url
function event_regis_pay() {
	
	global $wpdb;
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
    $payment_table = get_option ('events_payment_transactions_tbl');
	$currency_format = get_option ( 'currency_format' );
	$id = "";
	$id = $_GET ['id'];
	if ($id == "") {echo "Please check your email for payment information.";} 
	
	else {
			$query = "SELECT * FROM $events_attendee_tbl WHERE id='$id'";
			$result = mysql_query ( $query ) or die ( 'Error : ' . mysql_error () );
			while ( $row = mysql_fetch_assoc ( $result ) ) {
				$attendee_id = $row ['id'];
				$lname = $row ['lname'];
				$fname = $row ['fname'];
				$address = $row ['address'];
				$city = $row ['city'];
				$state = $row ['state'];
				$zip = $row ['zip'];
				$num_people = $row ['num_people'];
				$email = $row ['email'];
				$phone = $row ['phone'];
				$date = $row ['date'];
				$paystatus = $row ['paystatus'];
				$txn_type = $row ['txn_type'];
				$amt_pd = $row ['amount_pd'];
				$date_pd = $row ['paydate'];
				$event_id = $row ['event_id'];
                $coupon = $row['coupon'];
				$attendee_name = $fname . " " . $lname;
				}
			
				$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
				$result = mysql_query ( $sql );
				while ( $row = mysql_fetch_assoc ( $result ) ) {
					$org_id = $row ['id'];
				    $Organization = $row ['organization'];
					$Organization_street1 = $row ['organization_street1'];
					$Organization_street2 = $row ['organization_street2'];
					$Organization_city = $row ['organization_city'];
					$Organization_state = $row ['organization_state'];
					$Organization_zip = $row ['organization_zip'];
					$contact = $row ['contact_email'];
					$registrar = $row ['contact_email'];
                    $payment_vendor = $row['payment_vendor'];
					$payment_vendor_id = $row ['payment_vendor_id'];
                    $txn_key = $row['txn_key'];
					$currency_format = $row ['currency_format'];
					$events_listing_type = $row ['events_listing_type'];
					$return_url = $row ['return_url'];
					$message = $row ['message'];
					$return_url = $row['return_url'];
					$cancel_return = $row['cancel_return'];
					$notify_url = $row['notify_url'];
					$return_method = $row['return_method'];
					$image_url = $row['image_url'];
					$use_sandbox = $row['use_sandbox'];
					if ($currency_format == "USD" || $currency_format == "") {$currency_format = "$";}
				}
		
		//Query Database for Active event and get variable
		

					$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$event_id'";
					$result = mysql_query ( $sql );
					while ( $row = mysql_fetch_assoc ( $result ) ) {
						//$event_id = $row['id'];
						$event_name = $row ['event_name'];
						$event_desc = $row ['event_desc'];
						$event_description = $row ['event_desc'];
						$identifier = $row ['event_identifier'];
						$event_cost = $row ['event_cost'];
						$allow_checks = $row ['allow_checks'];
                        $use_coupon = $row['use_coupon'];
                        $coupon_code = $row['coupon_code'];
                        $coupon_code_price = $row['coupon_code_price'];
						$active = $row ['is_active'];
					}

        //Get information about previous payments
                   $sql= "SELECT SUM(mc_gross) FROM $payment_table WHERE payer_id='$attendee_id'";
                    $result = mysql_query($sql);
                    while($row = mysql_fetch_array($result)){
                    					$total_paid =  $row['SUM(mc_gross)'];
                                       	}
                         
                        if ($use_coupon =="Y" && $event_cost > "0" ) {
                            if ($coupon == $coupon_code) {$discount = $coupon_code_price;
                            
                            }
                            else {$discount = "0";}
                            }
                        $adjusted_price = $event_cost - $discount;
                         
                         if ($event_cost >"0" ){$balance = ((($event_cost-$discount)*$num_people)-$total_paid);
                         $balance = moneyFormat($balance);}
                         else if ($event_cost ==""){$balance = "Free Event";}
                         else {$balance = "Free Event";}
                         
                         
        //Begin Payment Page Display                   
        echo "<p><b>Thank You " . $fname . " " . $lname . " for registering for " . $event_name . "</b></p>";
        if ($total_paid != "") {
                    echo "<p><b><i><font color='red' size='3'>"
                         ."Our records indicate you have paid " 
                         . " <u>" . moneyFormat($total_paid) 
                         . "</u> and have a balance of <u>".$balance."</font></u></i></b></p>";
                    }
                    else {echo "<p><i><font color='red' size='3'>We have not received any payments</font></i></p>";}
                    
        if ($balance != "Free Event") 
            {	
			       if ($allow_checks == "yes") 
                        {
        				echo "<p><b>PLEASE MAKE CHECKS PAYABLE TO: <u>$Organization</u></b></p>"; 
        				echo "<p><b>IN THE AMOUNT OF <u> $balance</u></b></p>";
        				echo "<p>$Organization" . BR;
        				echo $Organization_street1 . " " . $Organization_street2 . BR;
        				echo $Organization_city . ", " . $Organization_state . "   " . $Organization_zip . "</p>";
        				echo "<hr>";
        			     }
			
                    if ($payment_vendor_id != "") 
                            {
                                                        echo "<p>Your can pay online with a credit card through <b><font color = 'blue'>".$payment_vendor."</b></font></p>";
                            echo "<p>The payment will be in the amount of ". $balance." </p>";
                            echo "<br>";
            				//Payment Selection with data hidden - forwards to payment vendor
                            if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}
                            if ($num_people > '1'){$count = $num_people.' people';}
                            $item_name = $event_name . " - "  . $lname.", ".$fname. " (ID ".$attendee_id . ") - ".$count;
                            
                            if ($payment_vendor == "GOOGLE"){
                                er_google_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                            else if ($payment_vendor == "PAYPAL"){
                                er_paypal_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                              
                              else if ($payment_vendor == "MONSTER"){
                                er_monster_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }   
                            else if ($payment_vendor == "AUTHORIZE.NET"){
                                 er_authorize_pay(
                                 $payment_vendor_id, $txn_key,  $currency_format, $item_name, $event_name, $num_people,
                                  $adjusted_price); }
                            else if ($payment_vendor == "CUSTOM"){
                                 er_custom_pay(
                                  $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                            else {echo "<B><h3><font color ='red'>Online Payments Are Currently Not Available</font></h3></b>";}
            				
                            echo "<hr>";}
            				}
                            else{
                $balance = "0";
                echo "<p>This is a free event, however we gladly accpet donations to continue to offer such events in the future.</p>";
                if ($allow_checks == "yes") 
                        {
        				echo "<p>If you would like to donate by a check, please make check payable to:<b><u>$Organization</u></b></p>"; 
        				echo "<br><p>Mail payment to:</p>";
        				echo "<p>$Organization" . BR;
        				echo $Organization_street1 . " " . $Organization_street2 . BR;
        				echo $Organization_city . ", " . $Organization_state . "   " . $Organization_zip . "</p>";
        				echo "<hr>";
        			     }
			
                    if ($payment_vendor != "") 
                            {
                            echo "<p>Your can donate online with a credit card through <b><font color = 'blue'>".$payment_vendor."</b></font></p>";
                            echo "<br>";
            				                            
                            //Payment Selection with data hidden - forwards to payment vendor
                            if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}
                          
                            $num_people = "1";
                            $item_name = "Donation for ".$event_name . " - "  . $lname.", ".$fname. " (ID ".$attendee_id . ")";
                            
                            if ($payment_vendor == "GOOGLE"){
                                echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by Google for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to Google's secure website where you will enter your information and complete the transaction.</p>";
                                er_google_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); 
                                
                                }
                            else if ($payment_vendor == "PAYPAL"){
                                 echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by PayPal for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to PayPal's secure website where you will enter your information and complete the transaction.</p>";
                                er_paypal_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            else if ($payment_vendor == "AUTHORIZE.NET"){
                                 echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by Authorize.Net for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to Authorize.Net's secure website where you will enter your information and complete the transaction.</p>";
                                er_authorize_pay(
                                 $payment_vendor_id,$txn_key,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            else if ($payment_vendor == "MONSTER"){
                                 echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by Monster for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to PayPal's secure website where you will enter your information and complete the transaction.</p>";
                                er_monster_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            else if ($payment_vendor == "CUSTOM"){
                                 er_custom_pay(
                                  $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            else {echo "<B><h3><font color ='red'>Online Payments Are Currently Not Available</font></h3></b>";}
            				
                            echo "<hr>";}
		      }}
}


function events_payment_paypal() {
	//you can load your paypal IPN processing script here
	//change the above if statement to the actual paypal word for this function to work
	echo "PayPal Info Here.\n\n"; // BHC
}

function events_payment_page($event_id) {
	
	global $wpdb;
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_organization_tbl = get_option ( 'events_organization_tbl' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
    $events_payment_tbl = get_option ('events_payment_transactions_tbl');
	$attendee_id = get_option ( 'attendee_id' );
	$attendee_name = get_option ( 'attendee_name' );
	$currency_format = get_option ( 'currency_format' );
    $id = $attendee_id;
    
    
	
	$query = "SELECT * FROM $events_attendee_tbl WHERE id='$id'";
			$result = mysql_query ( $query ) or die ( 'Error : ' . mysql_error () );
			while ( $row = mysql_fetch_assoc ( $result ) ) {
				$attendee_id = $row ['id'];
				$lname = $row ['lname'];
				$fname = $row ['fname'];
				$address = $row ['address'];
				$city = $row ['city'];
				$state = $row ['state'];
				$zip = $row ['zip'];
				$num_people = $row ['num_people'];
				$email = $row ['email'];
				$phone = $row ['phone'];
				$date = $row ['date'];
                $coupon = $row['coupon'];
				$attendee_name = $fname . " " . $lname;
				}
			
				$sql = "SELECT * FROM " . $events_organization_tbl . " WHERE id='1'";
				$result = mysql_query ( $sql );
				while ( $row = mysql_fetch_assoc ( $result ) ) {
					$org_id = $row ['id'];
					$Organization = $row ['organization'];
					$Organization_street1 = $row ['organization_street1'];
					$Organization_street2 = $row ['organization_street2'];
					$Organization_city = $row ['organization_city'];
					$Organization_state = $row ['organization_state'];
					$Organization_zip = $row ['organization_zip'];
					$contact = $row ['contact_email'];
					$registrar = $row ['contact_email'];
                    $payment_vendor = $row['payment_vendor'];
					$payment_vendor_id = $row ['payment_vendor_id'];
					 $txn_key = $row['txn_key'];
                    $donations = $row['accept_donations'];
					$currency_format = $row ['currency_format'];
					$events_listing_type = $row ['events_listing_type'];
					$return_url = $row ['return_url'];
					$message = $row ['message'];
					$return_url = $row['return_url'];
					$cancel_return = $row['cancel_return'];
					$notify_url = $row['notify_url'];
					$return_method = $row['return_method'];
					$image_url = $row['image_url'];
					$use_sandbox = $row['use_sandbox'];
					if ($currency_format == "USD" || $currency_format == "") {$currency_format = "$";}
				}
		
		//Query Database for Active event and get variable
		

					$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$event_id'";
					$result = mysql_query ( $sql );
					while ( $row = mysql_fetch_assoc ( $result ) ) {
						//$event_id = $row['id'];
						$event_name = $row ['event_name'];
						$event_desc = $row ['event_desc'];
						$event_description = $row ['event_desc'];
						$identifier = $row ['event_identifier'];
						$event_cost = $row ['event_cost'];
						$allow_checks = $row ['allow_checks'];
						$active = $row ['is_active'];
                        $use_coupon = $row['use_coupon'];
                        $coupon_code = $row['coupon_code'];
                        $coupon_code_price = $row['coupon_code_price'];
                        $custom_cur = $row ['custom_cur'];
                        
					}
        
        if ($custom_cur !=""){$currency_format = $custom_cur;}
        if ($currency_format == "USD" || $currency_format == "") {$currency_format = "$";}
        //Get information about previous payments
                   $sql= "SELECT SUM(mc_gross) FROM $events_payment_tbl WHERE payer_id='$attendee_id'";
                    $result = mysql_query($sql);
                    while($row = mysql_fetch_array($result)){
                    					$total_paid =  $row['SUM(mc_gross)'];
                                       	}
                         
                    
        //Begin Payment Page Display                   
        echo "<p><b>Thank You " . $fname . " " . $lname . " for registering for " . $event_name . ".</b>";
        
        if ($use_coupon =="Y" && $event_cost > "0" ) {
                            if ($coupon == $coupon_code) {
                                echo " Our records indicate you are paying for ".$num_people. " and have entered a valid coupon code and will receive a discount of ".$custom_cur." ".$coupon_code_price." off each registration fee.</p>";
                                $discount = $coupon_code_price;
                                }
                            else {
                                $discount = "0";}
                            }
                         $adjusted_price = moneyFormat($event_cost - $discount);
                         if ($event_cost >"0" ){$balance = ((($event_cost-$discount)*$num_people)-$total_paid);
                         $balance = moneyFormat($balance);}
                         else if ($event_cost ==""){$balance = "Free Event";}
                         else {$balance = "Free Event";}
        
        
        
        
        if ($amt_pd != "") {
                    echo "<p><b><u><i><font color='red' size='3'>"
                         ."Our records indicate you have paid " 
                         . $currency_format . " " . $amt_pd 
                         . "</font></u></i></b></p>";
                    }
                    
                    
        if ($balance != "Free Event") 
            {	
			       if ($allow_checks == "yes") 
                        {
        				echo "<p>If your are paying by check, please make check payable to:<b><u>$Organization</u></b></p>"; 
        				echo "<p>Payment amount of  <b><u> $balance</u></b></p>";
                        echo "<p>Mail payment to:</p>";
        				echo "<p>$Organization" . BR;
        				echo $Organization_street1 . " " . $Organization_street2 . BR;
        				echo $Organization_city . ", " . $Organization_state . "   " . $Organization_zip . "</p>";
        				echo "<hr>";
        			     }
			
                    if ($payment_vendor != "") 
                            {
                            echo "<p>Your can pay online with a credit card through <b><font color = 'blue'>".$payment_vendor."</b></font></p>";
                            echo "<p>The payment will be in the amount of ".$currency_format." ". $balance." </p>";
                            echo "<br>";
            				if ($balance < $event_cost){$currency_format." ".$payment = "- Partial";}
            				if ($balance == $event_cost){$currency_format." ".$payment = "- Full";}
                            
                            
                            //Payment Selection with data hidden - forwards to payment vendor
                            //if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}
                            
                            if ($num_people > '1'){$count = $num_people.' people';}
                            $item_name = $event_name . " - "  . $lname.", ".$fname. " (ID ".$attendee_id . ") - ".$count;
                            
                            if ($payment_vendor == "GOOGLE"){
                                er_google_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                            else if ($payment_vendor == "PAYPAL"){
                                
                                er_paypal_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                             else if ($payment_vendor == "MONSTER"){
                                er_monster_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); } 
                            else if ($payment_vendor == "AUTHORIZE.NET"){
                                
                                er_authorize_pay(
                                 $payment_vendor_id,$txn_key,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                            else if ($payment_vendor == "CUSTOM"){
                                 er_custom_pay(
                                  $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $adjusted_price); }
                            else {echo "<B><h3><font color ='red'>Online Payments Are Currently Not Available</font></h3></b>";}
            				
                            echo "<hr>";}
		      }
              
              else if ($balance == "Free Event" && $donations =="Yes"){
                $balance = "0";
                echo "<p>This is a free event, however we gladly accpet donations to continue to offer such events in the future.</p>";
                if ($allow_checks == "yes") 
                        {
        				echo "<p>If you would like to donate by a check, please make check payable to:<b><u>$Organization</u></b></p>"; 
        				echo "<br><p>Mail payment to:</p>";
        				echo "<p>$Organization" . BR;
        				echo $Organization_street1 . " " . $Organization_street2 . BR;
        				echo $Organization_city . ", " . $Organization_state . "   " . $Organization_zip . "</p>";
        				echo "<hr>";
        			     }
			
                    if ($payment_vendor != "") 
                            {
                            echo "<p>Your can donate online with a credit card through <b><font color = 'blue'>".$payment_vendor."</b></font></p>";
                            echo "<br>";
            				                            
                            //Payment Selection with data hidden - forwards to payment vendor
                            if ($currency_format == "$" || $currency_format == "") {$currency_format = "USD";}
                          
                            $num_people = "1";
                            $item_name = "Donation for ".$event_name . " - "  . $lname.", ".$fname. " (ID ".$attendee_id . ")";
                            
                            if ($payment_vendor == "GOOGLE"){
                                echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by Google for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to Google's secure website where you will enter your information and complete the transaction.</p>";
                                er_google_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); 
                                
                                }
                            else if ($payment_vendor == "PAYPAL"){
                                 echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by PayPal for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to PayPal's secure website where you will enter your information and complete the transaction.</p>";
                                er_paypal_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            else if ($payment_vendor == "AUTHORIZE.NET"){
                                 echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by Authorize.Net for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to Authorize.Net's secure website where you will enter your information and complete the transaction.</p>";
                                er_authorize_pay(
                                 $payment_vendor_id,$txn_key,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            
                            
                             else if ($payment_vendor == "MONSTER"){
                                echo "<p>The process is safe, simple, and secure and your bank or debit card information is used by Monster for the sole purpose of processing this transaction and never shared with us. To start, enter your donation amount below and click on the Donate button. You will be forwarded to PayPal's secure website where you will enter your information and complete the transaction.</p>";
                                er_monster_pay(
                                $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); } 
                             else if ($payment_vendor == "CUSTOM"){
                                 er_custom_pay(
                                  $payment_vendor_id,$currency_format, $item_name, $event_name, $num_people, $balance); }
                            else {echo "<B><h3><font color ='red'>Online Payments Are Currently Not Available</font></h3></b>";}
            				
                            echo "<hr>";}
              }
}


?>