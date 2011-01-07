<?php

/**
 * Event Config Info
 * @author Edge Technology Consulting
 * @copyright 2009
 */

function events_admin_page_footer() {
?>
<div id="event_regis-col-right">
	<div class="box-mid-head">
	<h2 class="events_reg f-wrench">DONATE TODAY</h2>
	</div>
	<div class="box-mid-body" id="toggle2">

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	
	   <p>Do you find <font color="#FF8000"> Event Registration</font> plugin useful?<br>Your contribution furthers the development of features.</p>
    	<input type="hidden" name="cmd" value="_donations">
    	<input type="hidden" name="business" value="buckfleming@comcast.net">
    	<input type="hidden" name="lc" value="US">
    	<input type="hidden" name="no_note" value="1">
    	<input type="hidden" name="no_shipping" value="1">
    	<input type="hidden" name="currency_code" value="USD">
    	<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHosted">
    	<div class="input">Amount: $<input type="text" name="amount" value="25.00" size="5"></div>
    	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="">
    	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    	<br />
        </form>
    </div>
    <div class="box-mid-head">
    <a href="http://www.edgetechweb.com/" >Event Registration Homepage</a>
    </div>
 </div>
<?php
}

function event_config_info(){
	er_plugin_menu();
    
    $current_event = get_option ( 'current_event' );
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );
	$events_organization_tbl = get_option ('events_organization_tbl');
	$events_question_tbl = get_option ('events_question_tbl');
	$events_answer_tbl = get_option( 'events_answer_tbl' );
	
	
	$installed_attendee_ver = get_option( 'events_attendee_tbl_version' );
    $installed_events_detail_ver = get_option( 'events_detail_tbl_version' );
    $installed_organization_ver = get_option( 'events_organization_tbl_version' );
    $installed_question_ver = get_option ('events_question_tbl_version');
    $installed_answer_ver = get_option( 'events_answer_tbl_version' );
    
   ?>
   
<div id="configure_organization_form" class=wrap>
<h2>Event Registration Support</h2>
<?php
    
events_admin_page_footer();	

?>

<div id="event_regis-col-left">
	<div class="box-mid-head">
					<h2 class="events_reg f-wrench">Database Information</h2>
	</div>

				<div class="box-mid-body" id="toggle2">
					<div class="padding">
    <?php
	
	
	
	echo "Events Table Name: ".$events_detail_tbl;
	echo " | ";
		
	echo "Version: ".$installed_events_detail_ver;
	echo "<br>";
		
	echo "Attendee Table Name: ".$events_attendee_tbl;
	echo " | ";
	
	echo "Version: ".$installed_attendee_ver;
	echo "<br>";
			
	echo "Organization Table Name: ".$events_organization_tbl;
	echo " | ";
		
	echo "Version: ".$installed_organization_ver;
	echo "<br>";
		
	echo "Question Table Name: ".$events_question_tbl;
	echo " | ";
		
	echo "Version: ".$installed_question_ver;
	echo "<br>";
		
	echo "Answer Table Name: ".$events_answer_tbl;
	echo " | ";
	echo "Version: ".$installed_answer_ver;	
	echo "<br>";


?>
</div></div>
<div class="box-mid-head">
<h2>General Directions</h2>
</div>
<div class="box-mid-body" id="toggle3">
					<div class="padding">
<?php

$help = EVNT_RGR_PLUGINFULLURL."guide.htm";
echo "<a href='http://edgetechweb.com/wp-content/uploads/EVENTREGIS-USER-GUIDE1.pdf' target='_blank'>USER GUIDE</a>";

?>                   
                    </div>
</div></div>
<?php	
    

}

?>