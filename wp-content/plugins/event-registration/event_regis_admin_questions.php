<?php

/**
 * @author Edge Technology Consulting
 * @copyright 2009
 */

//This runs the Additional Questions Admin Page

function event_form_config() {
    er_plugin_menu();
	
	global $events_lang;
	$form_question_build = $_REQUEST ['form_question_build'];
    $action = $_REQUEST['action'];
    
//	switch ($form_question_build) {
    switch ($action){
		case "write" :
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
				    $sql = "SELECT * FROM " . $events_detail_tbl . " WHERE id =" . $event_id;
		$result = mysql_query ($sql);
		while ($row = mysql_fetch_assoc ($result)){
                    $reg_form_defaults = unserialize($row['reg_form_defaults']);
                                        if ($reg_form_defaults !=""){
                        if (in_array("Address", $reg_form_defaults)) {$inc_address = "Y";}
                        if (in_array("City", $reg_form_defaults)) {$inc_city = "Y";}
                        if (in_array("State", $reg_form_defaults)) {$inc_state = "Y";}
                        if (in_array("Zip", $reg_form_defaults)) {$inc_zip = "Y";}
                        if (in_array("Phone", $reg_form_defaults)) {$inc_phone = "Y";}
                        }
                        }
  
?>
<div id="configure_questions_form" class=wrap>
<br /> 
  <h2>Event Questions Configuration - <font color="blue"><?php echo $event_name;?></font></h2>
  <div style="clear:both;"></div>
   <div style="float:left; margin-left:20px;">
        <button style="font-size:90%; background-color: #41A317; color: #FFF; font-weight: bolder; width:180; height: 20;" 
        onclick="window.location='<?php	echo "admin.php?page=events&action=get_details&event_id=". $event_id;	?>'" >VIEW EVENT DETAILS</button>
        </div><br />
               <p><font color="#4C7D7E"> Default Registration Information (Name and Email Required): 
    <b><i>Name, Email 
    <?php if ($inc_address == "Y"){echo ", Address";}?>
    <?php if ($inc_city == "Y"){echo ", City";}?>
    <?php if ($inc_state == "Y"){echo ", State";}?>
    <?php if ($inc_zip == "Y"){echo ", Zip";}?>
    <?php if ($inc_phone == "Y"){echo ", Phone #";}?>
    </b></font></p>
 <table class="widefat">
                <thead>
                <tr><th width="10">REQUIRED</th><th>QUESTION TYPE</th><th>QUESTION</th><th>SELECTIONS</th><th>ACTION</th></tr>
                </thead>
                <tbody>
<?php
        

			
			$questions = $wpdb->get_results ( "SELECT * from $events_question_tbl where event_id = $event_id order by sequence" );
			if ($questions) {
				foreach ( $questions as $question ) {
					echo "<tr><td>";
                    
					if ($question->required == "N") {
						echo '';
					}
					if ($question->required == "Y") {
						echo '<strong><font color="red">REQUIRED</font></stong>';
					}
                    
				echo "</td><td>" .$question->question_type."</td><td><p>" .$question->question . " </td><td>" . $question->response . "</td>";
                    
                    echo "<td><a href='admin.php?page=form&action=edit&question_id=".$question->id."&event_id=".$event_id."'>Edit </a> |  ";
                    echo "<a href='admin.php?page=form&action=delete&question_id=".$question->id."&event_id=".$event_id."' 
                    ONCLICK=\"return confirm('Are you sure you want to delete the question: ".$question->question."')\" > Delete</a>";
                    echo "</td></tr>";
                    
               			
				}
			}
			
			echo "</table><hr />";
            ?>
<ul id="event_regis-sortables">

			<li>
				<div class="box-mid-head">
					<h2 class="events_reg f-wrench">Add Additional Questions</h2>
				</div>


                <div class="box-mid-body" id="toggle2">
                <div class="padding">
                    
<form name="newquestion" method="post" action="admin.php?page=form"><input type="hidden" name="event_id" value="<?php echo $event_id;?>" />
	<table width="100%" cellspacing="2" cellpadding="5">
	<tr valign="top">
	<th width="33%" scope="row">Question:</th>
	<td><input name="question" type="text" id="question" size="100" value="" /></td>
	</tr>
	
	<tr valign="top">
	<th width="33%" scope="row">Type:</th>
	<td><select name="question_type" id="question_type">
				<option value="TEXT">Text</option>
				<option value="TEXTAREA">Text Area</option>
				<option value="SINGLE">Single</option>
				<option value="MULTIPLE">Multiple</option>
				<option value="DROPDOWN">Drop Down</option>
	</select></td>
	</tr>
	
	<tr valign="top">
	<th width="33%" scope="row">Selections:</th>
	<td><input name="values" type="text" id="values" size="50" value="" /></td>
	</tr>
	
	<tr valign="top">
	<th width="33%" scope="row">Required:</th>
	<td><input name="required" type="checkbox" id="required" /></td>
	</tr>
	
	</table>
    <?php echo $events_lang ['addQuestionDesc'];?>
				
	<?php		echo "<p><form name='form' method='post' action='admin.php?page=form'>";
				echo "<input type='hidden' name='form_question_build' value='post_new_question'>";
				echo "<input type='hidden' name='event_name' value='" . $event_name . "'>";
                echo "<input type='hidden' name='action' value='post_new_question'>";
				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
	?>	
	
	<p><input type="submit" name="Submit" value="ADD QUESTION" /></p>
</form>
					
              </li>  </ul></div>				</div></div>
<?php
			break;
		
		case "edit" :
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
			$question_id = $_REQUEST ['question_id'];
			
			$questions = $wpdb->get_results ( "SELECT * from $events_question_tbl where id = $question_id" );
			
			if ($questions) {
				foreach ( $questions as $question ) {
					echo $events_lang ['editQuestionDesc'];
					?>
<form name="newquestion" method="post"
	action="admin.php?page=form"><input type="hidden"
	name="action" value="post_edit" /> <input type="hidden"
	name="event_id" value="<?php
					echo $event_id;
					?>" /> <input type="hidden" name="question_id"
	value="<?php
					echo $question->id;
					?>" />

<table width="100%" cellspacing="2" cellpadding="5">
	<tr valign="top">
		<th width="33%" scope="row">Question:</th>
		<td><input name="question" type="text" id="question" size="50"
			value="<?php
					echo $question->question;
					?>" /></td>
	</tr>
	<tr valign="top">
		<th width="33%" scope="row">Type:</th>
		<td><select name="question_type" id="question_type">
			<option value="<?php
					echo $question->question_type;
					?>"><?php
					echo $question->question_type;
					?></option>
			<option value="TEXT">Text</option>
			<option value="TEXTAREA">Text Area</option>
			<option value="SINGLE">Single</option>
			<option value="MULTIPLE">Multiple</option>
			<option value="DROPDOWN">Drop Down</option>
		</select></td>
	</tr>
	<tr valign="top">
		<th width="33%" scope="row">Values:</th>
		<td><input name="values" type="text" id="values" size="50"
			value="<?php
					echo $question->response;
					?>" /></td>
	</tr>
	<tr valign="top">
		<th width="33%" scope="row">Required:</th>
		<td>
			
			<?php
					if ($question->required == "N") {
						echo '<input name="required" type="checkbox" id="required" />';
					}
					if ($question->required == "Y") {
						echo '<input name="required" type="checkbox" id="required" CHECKED />';
					}
				}
			}
			?>
			</td>
	</tr>
</table>
<p><input type="submit" name="Submit" value="UPDATE QUESTION" /></p>
</form>
<?php
			break;
		
		case "post_new_question" :
			
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
			
			$question = $_POST ['question'];
			$question_type = $_POST ['question_type'];
			$values = $_POST ['values'];
			$required = $_POST ['required'] ? 'Y' : 'N';
			$sequence = $wpdb->get_var ( "SELECT max(sequence) FROM $events_question_tbl where event_id = '$event_id'" ) + 1;
			
			$wpdb->query ( "INSERT INTO $events_question_tbl (`event_id`, `sequence`, `question_type`, `question`, `response`, `required`)" . " values('$event_id', '$sequence', '$question_type', '$question', '$values', '$required')" );
			

?>
<META HTTP-EQUIV="refresh" content="0;URL=admin.php?page=form&action=write&event_id=<?php echo $event_id;?>">
<?php			
		break;
		
		case "post_edit" :
			
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
			$question_text = $_POST ['question'];
			
			$question_id = $_POST ['question_id'];
			$question_type = $_POST ['question_type'];
			$values = $_POST ['values'];
			$required = $_POST ['required'] ? 'Y' : 'N';
			
			$wpdb->query ( "UPDATE $events_question_tbl set `question_type` = '$question_type', `question` = '$question_text', " . " `response` = '$values', `required` = '$required' where id = $question_id " );
		
?>

<META HTTP-EQUIV="refresh" content="0;URL=admin.php?page=form&action=write&event_id=<?php echo $event_id . "&event_name=" . $event_name;?>">

<?php
		break;
		
		case "delete" :
			
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
			$question_id = $_REQUEST ['question_id'];
			
			$wpdb->query ( "DELETE from $events_question_tbl where id = '$question_id'" );
 ?>           
            <div id="message" class="updated fade"><p><strong>The Question has been deleted.</strong></p></div>
<META HTTP-EQUIV='refresh' content='2;URL=admin.php?page=form&action=write&event_id=<?php echo event_id;?>'>




<?php
		break;
		
		default :
			//query event list with select option
			global $wpdb;
			$events_detail_tbl = get_option ( 'events_detail_tbl' );
			$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
			$events_question_tbl = get_option ( 'events_question_tbl' );
			$event_id = $_REQUEST ['event_id'];
			$event_name = $_REQUEST ['event_name'];
            
            ?>
            <div id="event_reg_theme" class="wrap">
            <h2>Manage Event Questions</h2>
            <div style="clear:both;"></div><hr /><div style="clear:both;">
            <table class="widefat">
            <thead><tr><th>Event</th><th>Description</th><th>Custom Questions</th></tr></thead>
            <tbody>
            <?php			
			echo $events_lang ['selectEvent']."<br>";
			
			$sql = "SELECT * FROM " . $events_detail_tbl;
			$result = mysql_query ( $sql );
			while ( $row = mysql_fetch_assoc ( $result ) ) {
				$id = $row ['id'];
				$name = $row ['event_name'];
                $desc = $row['event_desc'];
			    echo "<tr><td><a href='admin.php?page=form&action=write&event_id=".$id."&event_name=".$name."'>".
                    $name."</a></td><td>".$event_desc."</td><td>";
                 $questions = $wpdb->get_results ( "SELECT * from $events_question_tbl where event_id = $id order by sequence" );
			     if ($questions) {
			        
				foreach ( $questions as $question ) { 
				     echo "<font size='1'>".$question->question."</font></br>";
                }}
           else {echo "<font color='red'>No Custom Questions</font>";}
        echo "</td></tr>";
			}
			
			echo "</tbody></table></div>";


			
			if (isset ( $event_id ) && $event_id > 0) { //added isset to hide button if event has not been selected
				echo "<p><form name='form' method='post' action='admin.php?page=form'>";
				echo "<input type='hidden' name='form_question_build' value='write_question'>";
				echo "<input type='hidden' name='event_name' value='" . $event_name . "'>";
				echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
				echo "<input type='SUBMIT' style='background-color:lightgreen'value='ADD QUESTIONS TO " . $event_name . "'></form></p>";
			}
			
			break;
	}

}

?>