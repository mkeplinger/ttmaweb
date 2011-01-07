<?php

/**
 * @author David Fleming
 * @copyright 2010
 * Content functions for shortcodes for Events Registraion Plugin for Wordpress
 */


//Filters
// Function to show all events on page using {EVENTREGIS}



function event_regis_insert($content) {
	if (preg_match ( '{EVENTREGIS}', $content )) { 
    ob_start();
    event_regis_run($event_single_ID);
    $buffer = ob_get_contents();
    ob_end_clean();
    $content = str_replace ( '{EVENTREGIS}', $buffer, $content );
   	}
	return $content;
}

//Function for sidebar widget to list upcoming events
function er_widget_insert($content) {
	if (preg_match ( '{ER_WIDGET}', $content )) {
	   ob_start();
        events_regis_widget ();
        $buffer = ob_get_contents();
        ob_end_clean();
		$content = str_replace ( '{ER_WIDGET}', $buffer, $content );
	}
	return $content;
}

// Function for page for return payment link
function event_regis_pay_insert($content) {
	if (preg_match ( '{EVENTREGPAY}', $content )) {
    ob_start();
    event_regis_pay ();
    $buffer = ob_get_contents();
    ob_end_clean();
		$content = str_replace ( '{EVENTREGPAY}', $buffer , $content );
	}
	return $content;
}

//Function for Paypal IPN processing script on Page - use that page URL as the IPN pagelink
function event_pay_txn_insert($content)
		{
			  if (preg_match('{EVENTPAYPALTXN}',$content))
			    {
                    ob_start();
                    event_paypal_txn();
                    $buffer = ob_get_contents();
                    ob_end_clean();
                 
			      $content = str_replace('{EVENTPAYPALTXN}',$buffer,$content);
			    }
			  return $content;
		}



//Shortcodes

//Enable the ability to use single event call for a page

function display_single_event($atts) {
	extract(shortcode_atts(array('event_id' => 'No ID Supplied'), $atts));
	$id = "{$event_id}";
	//register_attendees($single_event_id);
    ob_start();
    event_regis_run($id);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}



// [EVENT_REGIS_CATEGORY event_category_id="your_category_identifier"]
function display_events_by_category($atts, $content=null) {
	extract(shortcode_atts(array('event_category_id' => 'No Category ID Supplied'), $atts));
	$event_category_id = "{$event_category_id}";
    $event_id = $_REQUEST['event_id'];
    ob_start();
    if ($event_id !=""){
         $id=$event_id;
         event_regis_run($id);
    } else { 
	display_all_events($event_category_id);}
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}



function er_calendar_display() {
	ob_start();
    er_show_calendar();
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

?>