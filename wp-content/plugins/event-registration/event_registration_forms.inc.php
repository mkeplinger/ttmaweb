<?php
//Event Registration Subpage 2 - Add/Delete/Edit Events

function event_regis_events() {

	//function to display events
	function display_event_details($all = 0) {
		global $wpdb;
		$events_detail_tbl = get_option ( 'events_detail_tbl' );
		$curdate = date ( "Y-m-d" );
		$sql = "SELECT * FROM " . $events_detail_tbl;
		
		$result = mysql_query ( $sql );
		/* 	echo "<table><tr><td width='60'></td><td><input name='' size='45' value='EVENT NAME'>";
									echo "<input name='' value='EVENT ID'>";
									echo "<input name='' size='22' value='DESCRIPTION/DETAILS'>"; 
								        echo "<input name='' size='10' value='COST'>";
								        echo "<input name='' value='ALLOW CHECKS?'>";
								        echo "<input name='' value='IS ACTIVE?'></td><td></td></tr></table><table>"; */
		echo "<table><tr><b>EVENTS LISTING:</b></tr>";
		
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			
			$event_name = $row ['event_name'];
			$event_desc = $row ['event_desc']; // BHC
			$image = $row ['image_link'];
			$display_desc = $row ['display_desc'];
			$image_link = $row ['image_link'];
			$event_locaion = $row ['event_location'];
			$more_info = $row ['more_info'];
			$header_image = $row ['header_image'];
			$identifier = $row ['event_identifier'];
			$reg_limit = $row ['reg_limit'];
			$start_month = $row ['start_month'];
			$start_day = $row ['start_day'];
			$start_year = $row ['start_year'];
			$end_month = $row ['end_month'];
			$end_day = $row ['end_day'];
			$end_year = $row ['end_year'];
			$start_time = $row ['start_time'];
			$end_time = $row ['end_time'];
			$cost = $row ['event_cost'];
			$multiple = $row ['multiple'];
			$checks = $row ['allow_checks'];
			$active = $row ['is_active'];
			$question1 = $row ['question1'];
			$question2 = $row ['question2'];
			$question3 = $row ['question3'];
			$question4 = $row ['question4'];
			$send_mail = $row ['send_mail'];
			$conf_mail = $row ['conf_mail'];
			$event_location = $row ['event_location'];
			$more_info = $row ['more_info'];
			$custom_cur = $row ['custom_cur'];
			
			echo "<tr><td></td><td valign='top'>";
			echo "<form name='form' method='post' action='";
			request_uri();
			echo "'>";
			echo "<input type='hidden' name='action' value='edit'>";
			echo "<input type='hidden' name='id' value='" . $row ['id'] . "'>";
			//echo "<INPUT TYPE='SUBMIT' VALUE='EDIT' ONCLICK=\"return confirm('Are you sure you want to edit ".$row['event_name']."?')\"></form>";
			echo "<input type='SUBMIT' value='COPY'>";
			echo "</form>";
			echo "<form name='form' method='post' action='";
			request_uri();
			echo "'>";
			echo "<input type='hidden' name='action' value='copy'>";
			echo "<input type='hidden' name='id' value='" . $row ['id'] . "'>";
			echo "<INPUT TYPE='SUBMIT' VALUE='EDIT' ONCLICK=\"return confirm('Are you sure you want to copy the event ".$row['event_name']."?')\"></form>";
			
			
			echo "<input type='SUBMIT' value='EDIT'>";
			echo "</form>";
			echo "<form name='form' method='post' action='";
			request_uri();
			echo "'>";
			echo "<input type='hidden' name='action' value='delete'>";
			echo "<input type='hidden' name='id' value='" . $row ['id'] . "'>";
			echo "<INPUT type='SUBMIT' value='DELETE' ONCLICK=\"return confirm('Are you sure you want to delete " . $row ['event_name'] . "?')\">";
			echo "</form></td>";
			echo "<td valign='center'>";
			if ($image_link != ""){echo "<img src='".$image_link."' width='150' height='112'>";}
			echo "</td>";
			echo "<td valign='top'>";
			echo "<p>Custom Identifier <b><u>" . $identifier . "</u></b></td>";
			echo "<td>Event ID/Name: <b><u>" . $row['id']." - ".$event_name . "</u></b> ";
			echo "Cost:  <b><u>" . $cost . "</u></b></p>";
			echo "<p>Start Date:<b><u>" . $start_month . " " . $start_day . ", " . $start_year . "</u></b>  Start Time:<b><u>" . $start_time . "</u></b>  End Date: <b><u>" . $end_month . " " . $end_day . ", " . $end_year . "</u></b>  End Time:<b><u>" . $end_time . "</u></b></p>";
			
			echo "<p>Event Location  <b><u>" . $event_location . "</u></b>" . BR;
			echo "<p>More Info  <b><u>" . $more_info . "</u></b>" . BR;			
			echo "<p>Registration Limit  <b><u>" . $reg_limit . "</u></b>" . BR;
			echo "Do you want to display the event description on registration page?";
			if ($display_desc == "") {
				echo " <b><i>PLEASE UPDATE THIS EVENT</i></b>" . BR;
			}
			if ($display_desc == "Y") {
				echo "<b> Yes</b>" . BR;
			}
			if ($display_desc == "N") {
				echo "<b> No</b>" . BR;
			}
			
			echo "<p>Description <b><u>" . $event_desc . "</u></b></p>";
			
			echo "<p>Event Thumbnail URL <u>". $image_link. "</u></p>";
			
			echo "<p>Event Header Image URL <u>". $header_image . "</u></p>";
			
			echo "<p>ALLOW PAYMENT FOR MORE THAN ONE PERSON AT A TIME (max # people 5)? ";
            if ($multiple == "") {
				echo " <b><i>PLEASE UPDATE THIS EVENT</i></b>" . BR;
			}
			if ($multiple == "Y") {
				echo "<b> Yes</b>" . BR;
			}
			if ($multiple == "N") {
				echo "<b> No</b>" . BR;
			}
			
			echo "<p>Accept Checks <b><u>" . $checks . "</u></b> Is This Event Active? <b><u>" . $active . "</u></b></p>";
			
			echo "<p>Do you want to send an custom confirmation message for this event?";
			if ($send_mail == "") {
				echo " <b><i>PLEASE UPDATE THIS EVENT</i></b>";
			}
			if ($send_mail == "Y"){
				echo "<b> Yes</b>";
			}
			if ($send_mail == "N") {
				echo "<B>No</b>";
			}
			echo "</p>";
			echo "<p>Custom Confirmation Mail <b><u>" . $conf_mail . "</u></b></p>";
			
			echo "<hr></td></tr>";
			
		/*   echo "<tr><td>";
								        echo "<form name='form' method='post' action='";
										request_uri()
										echo "'>";
										echo "<input type='hidden' name='action' value='edit'>";
										echo "<input type='hidden' name='id' value='".$row['id']."'>";
										echo "<INPUT TYPE='SUBMIT' VALUE='EDIT' ONCLICK=\"return confirm('Are you sure you want to edit ".$row['event_name']."?')\"></form></td>";
								        echo "<td><input name='event_name' size='45' value='".$event_name."'>";
								        echo "<input name='identifier' value='".$identifier."'>";

								        echo "<input name='cost' size='10' value='".$cost."'>";
								        echo "<input name='checks' value='".$checks."'>";
								        echo "<input name='active' value='".$active."'></td><td>";
								        echo "<form name='form' method='post' action='";
										request_uri();
										echo "'>";
										echo "<input type='hidden' name='action' value='delete'>";
										echo "<input type='hidden' name='id' value='".$row['id']."'>";
										echo "<INPUT TYPE='SUBMIT' VALUE='DELETE' ONCLICK=\"return confirm('Are you sure you want to delete ".$row['event_name']."?')\"></form></td></tr>";
								echo "<tr><td></td><td><textarea rows='2' cols='130' name='event_desc' >".$event_desc."</textarea></td></tr>";

*/
		}
		echo "</table>";
	}

	//function to delete event
	function delete_event() {
		global $wpdb;
		$events_detail_tbl = get_option ( 'events_detail_tbl' );
		
		if ($_REQUEST ['action'] == 'delete') {
			$id = $_REQUEST ['id'];
			$sql = "DELETE FROM $events_detail_tbl WHERE id='$id'";
			$wpdb->query ( $sql );
			
			?><META HTTP-EQUIV="refresh"
	content="0;URL=<?php
			request_uri();
			?>"><?php
		}
	}

	//function to edit event
	function edit_event() {
		global $wpdb;
		$events_detail_tbl = get_option ( 'events_detail_tbl' );
		
		$id = $_REQUEST ['id'];
		//Query Database for Active event and get variable
		$sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id =" . $id;
		$result = mysql_query ( $sql );
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			$id = $row ['id'];
			$event_name = $row ['event_name'];
			$event_desc = $row ['event_desc'];
			$image = $row ['image_link'];
			$header_image = $row ['header_image'];
			$display_desc = $row ['display_desc'];
			$event_description = $row ['event_desc'];
			$event_locaion = $row ['event_location'];
			$more_info = $row ['more_info'];
			$identifier = $row ['event_identifier'];
			$start_month = $row ['start_month'];
			$start_day = $row ['start_day'];
			$start_year = $row ['start_year'];
			$end_month = $row ['end_month'];
			$end_day = $row ['end_day'];
			$end_year = $row ['end_year'];
			$start_time = $row ['start_time'];
			$end_time = $row ['end_time'];
			$reg_limit = $row ['reg_limit'];
			$multiple = $row ['multiple'];
			$event_cost = $row ['event_cost'];
			$checks = $row ['allow_checks'];
      //TODO IJ change table structure... 
			//$displaypay = $row ['displaypay'];
			$active = $row ['is_active'];
			$question1 = $row ['question1'];
			$question2 = $row ['question2'];
			$question3 = $row ['question3'];
			$question4 = $row ['question4'];
			$conf_mail = $row ['conf_mail'];
			$send_mail = $row ['send_mail'];
			$event_location = $row ['event_location'];
			$more_info = $row ['more_info'];
			$custom_cur = $row ['custom_cur'];
		}
		
		update_option ( "current_event", $event_name );
		
		?>
<form method="post"
	action="<?php
		request_uri();
		?>"
	<p>EVENT ID - NAME: <?php echo $id;?> - <input name="event" size="100" value ="<?php
		echo $event_name;
		?>">      
	</p>
	<p>CUSTOM IDENTIFIER FOR EVENT (used for online payment reference)<input name="ident" value ="<?php
		echo $identifier;
		?>">
	</p>
	<p>
	EVENT DESCRIPTION: <textarea rows='2' cols='125' name='desc' ><?php
		echo $event_desc;
		?></textarea></p>
	<p>
		<?php
		displaySelectionBox ( $start_month, $start_day, $start_year, $end_month, $end_day, $end_year );
		?>
		</p>
	<p>Do you want to display the event description on registration page? 
		<?php
		if ($display_desc == "") {
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
		}
		?>
		</p>
		<p>Thumbnail Image URL (shows on event listing) display size 150 x112 <input name="image_link" size="45" value="<?php echo $image;?>"></p>
		<p>Event Header Image URL (shows on registration page) width should be 450 <input name="header_image" size="45" value="<?php echo $header_image; ?>"></p>
	<p>
	
	<p>ALLOW PAYMENT FOR MORE THAN ONE PERSON AT A TIME (max # people 5)?
			<?php
		if ($multiple == "") {
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
		}
		?>
		</p>
	
	<p> EVENT LOCATION <b><input name="event_location" size="25" value ="<?php echo $event_location;?>"></p>
	<p> MORE INFO <b><input name="more_info" size="25" value ="<?php echo $more_info;?>"></p>
	<p>	ATTENDEE LIMIT (leave blank for unlimited)  <input name="reg_limit" size="10" value ="<?php	echo $reg_limit;?>"></p>
	<p>COST FOR EVENT (leave blank for free events, enter 2 place decimal i.e. 7.00) <input name="cost" size="10" value ="<?php	echo $event_cost;?>"></p>
  <?php
  /* TODO IJ add... 
  <p>Do you want to display payment information?
    <select name="displaypay"><option>yes</option><option>no</option></select>
    </p>
*/
    ?>
	<p>WILL YOU ACCEPT CHECKS? 
		<select name="checks">
		<?php 
		if ($checks == "yes"){echo "<option>yes</option>";}
		if ($checks == "no") {echo "<option>no</option>";}
		?>
		<option>yes</option>
		<option>no</option>
		</select>
		</p>
	<p>DO YOU WANT THIS EVENT TO BE THE ACTIVE EVENT? 
		<select name="is_active">
		<?php 
		if ($active == "yes"){echo "<option>yes</option>";}
		if ($active == "no") {echo "<option>no</option>";}
		?>
		<option>yes</option>
		<option>no</option>
		</select>
		</p>

<p>DO YOU WANT TO SEND A CUSTOM CONFIRMATION EMAIL? 
		<?php
		if ($send_mail == "") {
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
		}
		?>
		</p>
<p>CUSTOM CONFIRMATION EMAIL FOR THIS EVENT: <br />
<textarea rows='4' cols='125' name='conf_mail'><?php
		echo $conf_mail;
		?></textarea></p>
                                    

        <?php
		echo "<input type='hidden' name='action' value='update'>";
		?>
        <?php
		echo "<input type='hidden' name='id' value='" . $id . "'>";
		?>
		<p><input type="submit" name="Submit" value="UPDATE EVENT"></p>
</form>
<?php
	}

	// Adds an Event or Function to the Event Database
	function add_event_funct_to_db() {
		global $wpdb;
		$events_detail_tbl = get_option ( 'events_detail_tbl' );
		
		if (isset ( $_POST ['Submit'] )) {
			if ($_REQUEST ['action'] == 'add') {
				$event_name = $_REQUEST ['event'];
				$event_identifier = $_REQUEST ['ident'];
				$event_desc = $_REQUEST ['desc'];
				$image = $_REQUEST ['image_link'];
				$header_image = $_REQUEST ['header_image'];
				$display_desc = $_REQUEST ['display_desc'];
				$event_locaion = $_REQUEST ['event_location'];
				$more_info = $_REQUEST ['more_info'];
				$reg_limit = $_REQUEST ['reg_limit'];
				$event_cost = $_REQUEST ['cost'];
				$multiple = $_REQUEST ['multiple'];
				$allow_checks = $_REQUEST ['checks'];
				$is_active = $_REQUEST ['is_active'];
				$start_month = $_REQUEST ['start_month'];
				$start_day = $_REQUEST ['start_day'];
				$start_year = $_REQUEST ['start_year'];
				$end_month = $_REQUEST ['end_month'];
				$end_day = $_REQUEST ['end_day'];
				$end_year = $_REQUEST ['end_year'];
				$start_time = $_REQUEST ['start_time'];
				$end_time = $_REQUEST ['end_time'];
				$question1 = $_REQUEST ['quest1'];
				$question2 = $_REQUEST ['quest2'];
				$question3 = $_REQUEST ['quest3'];
				$question4 = $_REQUEST ['quest4'];
				$conf_mail = $_REQUEST ['conf_mail'];
				$send_mail = $_REQUEST ['send_mail'];
				$event_location = $_REQUEST ['event_location'];
				$more_info = $_REQUEST ['more_info'];
				$custom_cur = $_REQUEST ['custom_cur'];
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
				
				//When the posted record is set to active, this checks records and deactivates them to set the current record as active
				update_option ( "current_event", $event_name );
				
				if ($is_active == "yes") {
					$sql = "UPDATE " . $events_detail_tbl . " SET is_active = 'no' WHERE is_active='$is_active'";
					$wpdb->query ( $sql );
					;
				}
				
				//Post the new event into the database
				

				$sql = "INSERT INTO " . $events_detail_tbl . " (event_name, event_desc, display_desc, image_link, header_image, event_identifier, event_location, more_info, start_month, start_day, start_year, start_time, start_date, end_month, end_day, end_year, end_time, end_date, reg_limit, event_cost, multiple, allow_checks, send_mail, is_active, question1, question2, question3, question4, conf_mail) VALUES('$event_name', '$event_desc', '$display_desc', '$image', '$header_image', '$event_identifier', '$event_location',
 '$more_info','$start_month', '$start_day', '$start_year', '$start_time', '$start_date','$end_month', '$end_day', '$end_year', '$end_time', '$end_date', '$reg_limit', '$event_cost', '$multiple','$allow_checks', '$send_mail', '$is_active', '$question1', '$question2', '$question3', '$question4', '$conf_mail')";
				
		
			
				$wpdb->query ( $sql );
				
			echo "<meta http-equiv='refresh' content='0'>";
			}
		
		}
		if ($_REQUEST ['action'] == 'update') {
			$id = $_REQUEST ['id'];
			$event_name = $_REQUEST ['event'];
			$ident = $_REQUEST ['ident'];
			$desc = $_REQUEST ['desc'];
			$display_desc = $_REQUEST ['display_desc'];
			$event_locaion = $_REQUEST ['event_location'];
			$more_info = $_REQUEST ['more_info'];
			$image = $_REQUEST ['image_link'];
			$header_image = $_REQUEST ['header_image'];
			$reg_limit = $_REQUEST ['reg_limit'];
			$cost = $_REQUEST ['cost'];
			$multiple = $_REQUEST ['multiple'];
			$accept_checks = $_REQUEST ['checks'];
			$is_active = $_REQUEST ['is_active'];
			$start_month = $_REQUEST ['start_month'];
			$start_day = $_REQUEST ['start_day'];
			$start_year = $_REQUEST ['start_year'];
			$end_month = $_REQUEST ['end_month'];
			$end_day = $_REQUEST ['end_day'];
			$end_year = $_REQUEST ['end_year'];
			$start_time = $_REQUEST ['start_time'];
			$end_time = $_REQUEST ['end_time'];
			$quest1 = $_REQUEST ['quest1'];
			$quest2 = $_REQUEST ['quest2'];
			$quest3 = $_REQUEST ['quest3'];
			$quest4 = $_REQUEST ['quest4'];
			$conf_mail = $_REQUEST ['conf_mail'];
			$send_mail = $_REQUEST ['send_mail'];
			$event_location = $_REQUEST ['event_location'];
			$more_info = $_REQUEST ['more_info'];
			$custom_cur = $_REQUEST ['custom_cur'];
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

			
			//When the posted record is set to active, this checks records and deactivates them to set the current record as active
			update_option ( "current_event", $event_name );
			
			if ($is_active == "yes") {
				$sql = "UPDATE " . $events_detail_tbl . " SET is_active = 'no' WHERE is_active='$is_active'";
				$wpdb->query ( $sql );
				;
			}
			
			//Post the new event into the database
			

			/* BHC */			
			$sql = "UPDATE $events_detail_tbl SET event_name='$event_name', event_identifier='$ident', image_link='$image',
					header_image='$header_image',	reg_limit='$reg_limit',event_desc='$desc', display_desc='$display_desc',
					event_location='$event_location', more_info='$more_info', 
					send_mail='$send_mail',	event_cost='$cost', multiple='$multiple', allow_checks='$accept_checks', 
					is_active='$is_active', start_month='$start_month', start_day='$start_day', start_year='$start_year', 
					start_date='$start_date', end_month='$end_month', end_day='$end_day', end_year='$end_year',
					end_date='$end_date', start_time='$start_time', end_time='$end_time', question1='$quest1', question2='$quest2',
					question3='$quest3', question4='$quest4', conf_mail='$conf_mail'  WHERE id = $id";
			
			$wpdb->query ( $sql );
			
			echo "<meta http-equiv='refresh' content='0'>";
		
		} 

		else {
			?>
<form method="post"
	action="<?php
			request_uri();
			?>"
	<p>EVENT NAME: <input name="event" size="100"></p>
	<p>ID FOR EVENT (used for online payment reference)
	<input name="ident"></p>
	<p>EVENT DESCRIPTION: <textarea rows='2' cols='125' name='desc' ></textarea></p>
	<p>Do you want to display the event description on registration page? 
	<?php
			echo "<INPUT TYPE='radio' NAME='display_desc' CHECKED VALUE='Y'>Yes";
			echo "<INPUT TYPE='radio' NAME='display_desc' VALUE='N'>No";
			?>
			</p>	
		<p>Thumbnail Image URL (shows on event listing) display size 150 x112 <input name="image_link" size="45"></p>
		<p>Event Header Image URL (shows on registration page) width should be 450 <input name="header_image" size="45"></p>
	<p>EVENT LOCATION <input name="event_location" size="25"></p>
	<p>MORE INFO (hyperlink to another page or site) <input name="more_info" size="25"></p>	
	<p>ATTENDEE LIMIT (leave blank for unlimited attendees) <input name="reg_limit" size="15"></p>
	<p>COST FOR EVENT (leave blank for free events, enter 2 place decimal i.e. 7.00) 
	<input name="cost" size="10"></p>
	<p>WILL YOU ACCEPT CHECKS? <select name="checks"><option>yes</option><option>no</option></select></p>
    <p>ALLOW PAYMENT FOR MORE THAN ONE PERSON AT A TIME (max # people 5)?
			<?php
  			echo " <INPUT TYPE='radio' NAME='multiple' CHECKED VALUE='Y'>Yes";
			echo " <INPUT TYPE='radio' NAME='multiple' VALUE='N'>No";
    		?>
		</p>



	<?php
			displaySelectionBox ();
			?>
<p>DO YOU WANT THIS EVENT TO BE THE ACTIVE EVENT? 
<select name="is_active"><option>yes</option><option>no</option></select></p>
	<p>DO YOU WANT TO SEND A CUSTOM CONFIRMATION EMAIL?  <?php
			echo "<INPUT TYPE='radio' NAME='send_mail' CHECKED VALUE='Y'>Yes";
			echo "<INPUT TYPE='radio' NAME='send_mail' VALUE='N'>No";
			?>
			</p>
	<p>CUSTOM CONFIRMATION EMAIL FOR THIS EVENT: <br />
			<textarea rows='4' cols='125' name='conf_mail' ></textarea></p>

            <?php
			echo "<input type='hidden' name='action' value='add'>";
			?>
			<p><input type="submit" name="Submit" value="ADD EVENT"></p></form>
<?php
		}
	
	}
	
	//Display Options
	

	if ($_REQUEST ['action'] == 'delete') {
		delete_event ();
	}
	if ($_REQUEST ['action'] == 'edit') {
		edit_event ();
	}
	
	Echo "<h2>ADD AN EVENT OR FUNCTION TO THE DATABASE</h2>";
	
	add_event_funct_to_db ();
	
	Echo "<hr />";
	
	display_event_details ();

}
?>
