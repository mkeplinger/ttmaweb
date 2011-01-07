<?php

##Set the number of future days for upcoming events listing##
$future_days = "60";

/****Function to return a prefix which will allow the correct , placement of arguments into the query string. ***/

function evr_permalink_prefix(){
  if (is_home()) { 
    $p_link = get_bloginfo('url'); 
    if ($p_link[strlen($p_link)-1] != '/') { $p_link = $p_link.'/'; }
  } else { 
    $p_link = get_permalink(); 
  }

  if (!(strstr($p_link,'?'))) { $link_part = $p_link.'?'; } else { $link_part = $p_link.'&'; }

  return $link_part;
}

/******** Configure the "Next" link in the calendar  *************/

function evr_next_link($cur_year,$cur_month){
  $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
  $next_year = $cur_year + 1;

  if ($cur_month == 12){
      return '<a href="' . evr_permalink_prefix() . 'month=jan&amp;yr=' . $next_year . '">'.__('Next').' &raquo;</a>';
  }else{
      $next_month = $cur_month + 1;
      $month = $mod_rewrite_months[$next_month];
      return '<a href="' . evr_permalink_prefix() . 'month='.$month.'&amp;yr=' . $cur_year . '">'.__('Next').' &raquo;</a>';
  }
}

/*********  Configure the "Previous" link in the calendar  **************/

function evr_prev_link($cur_year,$cur_month){
  $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
  $last_year = $cur_year - 1;

  if ($cur_month == 1){
      return '<a href="' . evr_permalink_prefix() . 'month=dec&amp;yr='. $last_year .'">&laquo; '.__('Prev').'</a>';
  }else{
      $next_month = $cur_month - 1;
      $month = $mod_rewrite_months[$next_month];
      return '<a href="' . evr_permalink_prefix() . 'month='.$month.'&amp;yr=' . $cur_year . '">&laquo; '.__('Prev').'</a>';
  }
}

/******************************* Display the Calendar in a page **************************/
/*
function evr_calendar_page($content){
	if (preg_match('{EVR_CALENDAR}',$content)){
      $cal_output = evr_display_calendar();
      $content = str_replace('{EVR_CALENDAR}',$cal_output,$content);
  }
  return $content;
}
*/


function evr_calendar_replace($content){
			  if (preg_match('{EVR_CALENDAR}',$content))
			    {
			      	ob_start();
					evr_display_calendar(); //function with main content
					$buffer = ob_get_contents();
					ob_end_clean();
					$content = str_replace('{EVR_CALENDAR}',$buffer,$content);
			    }
			  return $content;
		}


// Function to provide time with WordPress offset, localy replaces time()
function evr_time_offset(){
  return (time()+(3600*(get_option('gmt_offset'))));
}

// Setup comparison functions for building the calendar later
function evr_month_compare($month){
  $current_month = strtolower(date("M", evr_time_offset()));
  if (isset($_GET['yr']) && isset($_GET['month'])){
      if ($month == $_GET['month']){
	  			return ' selected="selected"';
			}
  }
  elseif ($month == $current_month){
      return ' selected="selected"';
  }
}

function evr_year_compare($year){
  $current_year = strtolower(date("Y", evr_time_offset()));
  if (isset($_GET['yr']) && isset($_GET['month'])){
      if ($year == $_GET['yr']){
	  			return ' selected="selected"';
			}
  }
  else if ($year == $current_year){
      return ' selected="selected"';
  }
}


// Function to indicate the number of the day passed, eg. 1st or 2nd Sunday
function evr_np_of_day($date){
  $instance = 0;
  $dom = date('j',strtotime($date));
  if (($dom-7) <= 0) { $instance = 1; }
  else if (($dom-7) > 0 && ($dom-7) <= 7) { $instance = 2; }
  else if (($dom-7) > 7 && ($dom-7) <= 14) { $instance = 3; }
  else if (($dom-7) > 14 && ($dom-7) <= 21) { $instance = 4; }
  else if (($dom-7) > 21 && ($dom-7) < 28) { $instance = 5; }
  return $instance;
}

function er_show_calendar(){
    
    ?>
    <style>
    .calendar-table {
    table-layout:fixed;
    border-collapse: collapse;
   	border-spacing:0;
    width:100%;
}
#calendar-table tr td {
	padding: 6px 4px;
  border:1px solid #DEE0C8;
}

.normal-day-heading {
    width:14%;
    height:25px;
    text-align:center;
    font-size:1em;
}
.weekend-heading {
    width:15%;
    height:25px;
    text-align:center;
    font-size:1em;
    color:#000000;
}
.calendar-next, .calendar-prev {
    text-align:center;
}
.calendar-prev a:link, .calendar-next a:link {
    text-align:center;
    text-decoration:none
}
.calendar-prev a:visited, .calendar-next a:visited {
    text-align:center;
    text-decoration:none
}

.calendar-month {
    text-align:center;
    font-weight:bold;
    font-size: 15px;
}

.day-with-date {
    height:60px;
}
.events{
    vertical-align:text-top;
    text-align:left;
    font-size:95%;
}
.no-events {
    text-align:center;
    vertical-align: middle;
    font-size:110%;
}
.day-without-date {
    width:14.2%;
    height:60px;
}
span.weekend {
	
}
.current-day {
    height:60px;
    background-color:#E4E6BF;
}
span.event {
	 	text-align:left;
    font-size:1em;
}

.calendar-date-switcher {
    height:25px;
    text-align:center;
    background-color:#E4E6BF;
}
.calendar-date-switcher form {
    margin:0;
    padding:0;
}
.calendar-date-switcher input {
	
}
.calendar-date-switcher select {

}

.calnk a:hover span span.event-title {
    padding:0px;
    width:96%;
    border-bottom: #000000 solid 1px;
    text-align:center;
    white-space:nowrap;
    font-weight:bold;
    font-size:1.2em;
}
.calnk a:hover {
     background-position:0 0;
     text-decoration:none;  
     color:#000000;
}
.calnk a:visited {
     text-decoration:none;
     color:#000000;
}
.calnk a {
    text-decoration:none; 
    color:#000000;
}
.calnk a span { 
    display:none; 
}
.calnk a:hover span {	
    color:#333333; 
    display:block;
    position:absolute; 
    margin-top:1px;
    padding:6px;
    max-width:300px;
    min-width:200px;
    *width:expression((this.offsetWidth > 300) ? (this.width = 300 +'px') : ((this.offsetWidth < 200) ? (this.width = 200 +'px') :true)); 
    clear:both;
    z-index:100;
    line-height:1.2em;
}
.calnk .time {
		font-size:0.8em;
}
.page-upcoming-events {
    font-size:95%;
}
.page-todays-events {
    font-size:95%;
}</style>
    <?php
    global $wpdb,$week_no;
    unset($week_no);
    if (get_option('start_of_week') == 0){
				$name_days = array(1=>__('Sun'),__('Mon'),__('Tue'),__('Wed'),__('Thu'),__('Fri'),__('Sat'));
    }
    else{
				$name_days = array(1=>__('Mon'),__('Tue'),__('Wed'),__('Thu'),__('Fri'),__('Sat'),__('Sun'));
    }
    $name_months = array(1=>__('January'),__('February'),__('March'),__('April'),__('May'),__('June'),__('July'),__('August'),__('September'),__('October'),__('November'),__('December'));

    if (empty($_GET['month']) || empty($_GET['yr'])){
        $c_year = date("Y",evr_time_offset());
        $c_month = date("m",evr_time_offset());
        $c_day = date("d",evr_time_offset());
    }

    if ($_GET['yr'] <= 3000 && $_GET['yr'] >= 0 && (int)$_GET['yr'] != 0){
        if ($_GET['month'] == 'jan' || $_GET['month'] == 'feb' || $_GET['month'] == 'mar' || $_GET['month'] == 'apr' || $_GET['month'] == 'may' || $_GET['month'] == 'jun' || $_GET['month'] == 'jul' || $_GET['month'] == 'aug' || $_GET['month'] == 'sept' || $_GET['month'] == 'oct' || $_GET['month'] == 'nov' || $_GET['month'] == 'dec'){

               $c_year = mysql_escape_string($_GET['yr']);
               if ($_GET['month'] == 'jan') { $t_month = 1; }
               else if ($_GET['month'] == 'feb') { $t_month = 2; }
               else if ($_GET['month'] == 'mar') { $t_month = 3; }
               else if ($_GET['month'] == 'apr') { $t_month = 4; }
               else if ($_GET['month'] == 'may') { $t_month = 5; }
               else if ($_GET['month'] == 'jun') { $t_month = 6; }
               else if ($_GET['month'] == 'jul') { $t_month = 7; }
               else if ($_GET['month'] == 'aug') { $t_month = 8; }
               else if ($_GET['month'] == 'sept') { $t_month = 9; }
               else if ($_GET['month'] == 'oct') { $t_month = 10; }
               else if ($_GET['month'] == 'nov') { $t_month = 11; }
               else if ($_GET['month'] == 'dec') { $t_month = 12; }
               $c_month = $t_month;
               $c_day = date("d",evr_time_offset());
        }
        else{
               $c_year = date("Y",evr_time_offset());
               $c_month = date("m",evr_time_offset());
               $c_day = date("d",evr_time_offset());
        }
    }
    else{
        $c_year = date("Y",evr_time_offset());
        $c_month = date("m",evr_time_offset());
        $c_day = date("d",evr_time_offset());
    }

    if (get_option('start_of_week') == 0){
				$first_weekday = date("w",mktime(0,0,0,$c_month,1,$c_year));
        $first_weekday = ($first_weekday==0?1:$first_weekday+1);
    }
    else{
				$first_weekday = date("w",mktime(0,0,0,$c_month,1,$c_year));
				$first_weekday = ($first_weekday==0?7:$first_weekday);
    }

    $days_in_month = date("t", mktime (0,0,0,$c_month,1,$c_year));

    $calendar_body .= '<table class="calendar-table" id="calendar-table" >';
    //$date_switcher = $wpdb->get_var("SELECT config_value FROM ".WP_LIVE_CALENDAR_CONFIG_TABLE." WHERE config_item='display_jump'",0,0);
    $date_switcher="true";
    if ($date_switcher == 'true'){
        
				$calendar_body .= '<tr><td colspan="7" class="calendar-date-switcher"><form method="get" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">';
				$qsa = array();
			
                //parse_str($_SERVER['QUERY_STRING'],$qsa);
                
				foreach ($qsa as $name => $argument){
	    			if ($name != 'month' && $name != 'yr'){
								$calendar_body .= '<input type="hidden" name="'.strip_tags($name).'" value="'.strip_tags($argument).'" />';
	      		}
	  		}
					
                $calendar_body .= ''.__('Month').': <select name="month" style="width:100px;">
            <option value="jan"'.evr_month_compare('jan').'>'.__('January').'</option>
            <option value="feb"'.evr_month_compare('feb').'>'.__('February').'</option>
            <option value="mar"'.evr_month_compare('mar').'>'.__('March').'</option>
            <option value="apr"'.evr_month_compare('apr').'>'.__('April').'</option>
            <option value="may"'.evr_month_compare('may').'>'.__('May').'</option>
            <option value="jun"'.evr_month_compare('jun').'>'.__('June').'</option>
            <option value="jul"'.evr_month_compare('jul').'>'.__('July').'</option> 
            <option value="aug"'.evr_month_compare('aug').'>'.__('August').'</option> 
            <option value="sept"'.evr_month_compare('sept').'>'.__('September').'</option> 
            <option value="oct"'.evr_month_compare('oct').'>'.__('October').'</option> 
            <option value="nov"'.evr_month_compare('nov').'>'.__('November').'</option> 
            <option value="dec"'.evr_month_compare('dec').'>'.__('December').'</option> 
            </select>
            '.__('Year').': <select name="yr" style="width:60px;">';

				$past = 30;
				$future = 30;
				$fut = 1;
				while ($past > 0){
	    			$p .= '<option value="';
	    			$p .= date("Y",evr_time_offset())-$past;
	    			$p .= '"'.evr_year_compare(date("Y",evr_time_offset())-$past).'>';
	    			$p .= date("Y",evr_time_offset())-$past.'</option>';
	    			$past = $past - 1;
	  		}
				while ($fut < $future) {
	    			$f .= '<option value="';
	    			$f .= date("Y",evr_time_offset())+$fut;
	    			$f .= '"'.evr_year_compare(date("Y",evr_time_offset())+$fut).'>';
	    			$f .= date("Y",evr_time_offset())+$fut.'</option>';
	    			$fut = $fut + 1;
	  		} 
				$calendar_body .= $p;
				$calendar_body .= '<option value="'.date("Y",evr_time_offset()).'"'.evr_year_compare(date("Y",evr_time_offset())).'>'.date("Y",evr_time_offset()).'</option>';
				$calendar_body .= $f;
    		$calendar_body .= '</select><input type="submit" value="'.__('Go').'" /></form></td></tr>';
  	
      }
  
      	$calendar_body .= '
                    <tr>
                    <td colspan="2" class="calendar-prev">' . evr_prev_link($c_year,$c_month) . '</td>
                    <td colspan="3" class="calendar-month">'.$name_months[(int)$c_month].' '.$c_year.'</td>
                    <td colspan="2" class="calendar-next">' . evr_next_link($c_year,$c_month) . '</td>
                    </tr>';

    $calendar_body .= '<tr>';
    for ($i=1; $i<=7; $i++) {
				if (get_option('start_of_week') == 0){
	    			$calendar_body .= '<td class="'.($i<7&&$i>1?'normal-day-heading':'weekend-heading').'">'.$name_days[$i].'</td>';
	  		}
				else{
	    			$calendar_body .= '<td class="'.($i<6?'normal-day-heading':'weekend-heading').'">'.$name_days[$i].'</td>';
	  		}
    }
    $calendar_body .= '</tr>';

    for ($i=1; $i<=$days_in_month;){
        $calendar_body .= '<tr>';
        for ($ii=1; $ii<=7; $ii++){
            if ($ii==$first_weekday && $i==1){
								$go = TRUE;
	      		}
            elseif ($i > $days_in_month ) {
								$go = FALSE;
	      		}
            if ($go) {
								if (get_option('start_of_week') == 0){
		    						$grabbed_events = evr_fetch_events($c_year,$c_month,$i);
		    						$no_events_class = '';
		    						if (!count($grabbed_events)){
												$no_events_class = ' no-events';
		      					}
		      					else{
												$no_events_class = ' events';
		      					}
		    						
                                    $calendar_body .= '<td class="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd",evr_time_offset())?'current-day':'day-with-date').$no_events_class.'"><span '.($ii<7&&$ii>1?'':'class="weekend"').'>'.$i++.'</span><span class="event"><br />' . evr_show_events($grabbed_events) . '</span></td>';
		  					
                              }
								else{
								    
		    						$grabbed_events = evr_fetch_events($c_year,$c_month,$i);
		    						$no_events_class = '';
	            			if (!count($grabbed_events)){
												$no_events_class = ' no-events';
		      					}
		      					else{
												$no_events_class = ' events';
		      					}
		    						
                                    $calendar_body .= '<td class="'.(date("Ymd", mktime (0,0,0,$c_month,$i,$c_year))==date("Ymd",evr_time_offset())?'current-day':'day-with-date').$no_events_class.'"><span '.($ii<6?'':'class="weekend"').'>'.$i++.'</span><br/><span class="event">' . evr_show_events($grabbed_events) . '</span></td>';
		  					
                              }
                           
	     			}
            else {
								$calendar_body .= ' <td class="day-without-date">&nbsp;</td>';
	      		}
        }
        
        $calendar_body .= '</tr>';
    }
    
    //$show_cat = $wpdb->get_var("SELECT config_value FROM ".WP_LIVE_CALENDAR_CONFIG_TABLE." WHERE config_item='enable_categories'",0,0);
    $show_cat="false";
    if ($show_cat == 'true'){
				$sql = "SELECT * FROM " . get_option('events_cat_detail_tbl') . " ORDER BY category_name ASC";
				$cat_details = $wpdb->get_results($sql);
				$tr_flag	=	0;
        foreach($cat_details as $cat_detail){
        		$cat_each_count = $wpdb->get_var("SELECT count(id) as has_events FROM " . get_option ( 'events_detail_tbl' ) . " where event_category = '".$cat_detail->id."'",0,0);

        		if($tr_flag%7 == 0) $calendar_body .= '<tr>';
        		//$calendar_body .= '<td colspan="1" style="background-color:'.$cat_detail->category_color.';font-size:0.9em; color:'.$cat_detail->font_color.'; ">'.$cat_detail->category_name.'('.$cat_each_count.')</td>';
        		$calendar_body .= '<td colspan="1" style="background-color:'.$cat_detail->category_color.';font-size:0.8em; color:'.$cat_detail->font_color.'; ">'.$cat_detail->category_name.'</td>';
                if($tr_flag%7 == 6 || $tr_flag == (count($cat_details)-1)) $calendar_body .= '</tr>';
        		$tr_flag	=	$tr_flag+1;
	  		}    
    }
    $calendar_body .= '</table>';
   echo $calendar_body;
    return $calendar_body;
}

/************************    Display the events  *********************************/

function evr_show_events($events){
  usort($events, "evr_time_cmp");
  foreach($events as $event){
      $output .= evr_show_event($event).'<br />';
  }
  return $output;
}

function evr_show_event($event){
    
  global $wpdb;
                                    
$show_cat="true";
  if ($show_cat == 'true'){
      $cat_array = unserialize($event->category_id);
      $cat_id = $cat_array[0];
      $sql = "SELECT * FROM " . get_option('events_cat_detail_tbl') . " WHERE id=".$cat_id;
      $cat_details = $wpdb->get_row($sql);
      if ($cat_details !=""){ $style = "background-color:".stripslashes($cat_details->category_color)." ; color:".stripslashes($cat_details->font_color)." ;";
      } else { $style = "background-color:#F6F79B;color:"."#000000"." ;";
      }
      
  }
  else{
      $sql = "SELECT * FROM " . get_option('events_cat_detail_tbl') . " WHERE id=1";
      $cat_details = $wpdb->get_row($sql);
      //$style = "background-color:".stripslashes($cat_details->category_color).";color:".stripslashes($cat_details->font_color)." ;";
      $style = "background-color:#F6F79B;color:"."#000000"." ;";
  }
  $header_details .=  '<span class="event-title" style="color:'."#000000".'">'.stripslashes($event->event_name);
  $header_details .=  '</span><br/>';
  if ($event->event_time != "00:00:00"){
      $header_details .= '<span class="time"><strong>'.__('Time').':</strong> ' . date(get_option('time_format'), strtotime(stripslashes($event->start_time))) . ' - '. date(get_option('time_format'), strtotime(stripslashes($event->end_time))) . ', '.__('In ').''.stripslashes($event->event_location).'</span><br />';
  }
  if ($event->event_link != '') { 
  		$linky = stripslashes($event->more_info); 
  }
  else { 
  		//$linky = '#';
        $calendar_url = get_option ( 'er_link_for_calendar_url');
$arr_params = array ('regevent_action'=>'register', 'event_id' => $event->id, 'name_of_event'=> stripslashes($event->event_name));


$linky =  add_query_arg($arr_params, $calendar_url);
       // $linky = get_option('siteurl')."/?page_id=".$company_options['evr_page_id']."&action=register&event_id=".$event->id;   
  }

  $details = '<span class="calnk"><a href="'.$linky.'" style="'.$style.'" target="_blank">' . stripslashes($event->event_name) . '<span style="'.$style.'">' . $header_details . '<br/>' . stripslashes($event->event_desc) . '</span></a></span>';

  return $details;
}


function evr_fetch_events($y,$m,$d){
    global $wpdb,$tod_no,$cal_no;
    $arr_events = array();
    $date = $y . '-' . $m . '-' . $d; 

        
    $events = $wpdb->get_results("SELECT * FROM " . get_option ( 'events_detail_tbl' ). " WHERE str_to_date(start_date, '%Y-%m-%e') <= str_to_date('$date', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('$date', '%Y-%m-%e') ORDER BY id");
    if (!empty($events)){
         foreach($events as $event){
	   					array_push($arr_events, $event);
         }
    }
 
  	return $arr_events;
}

function evr_time_cmp($a, $b){
  if ($a->event_time == $b->event_time) {
    return 0;
  }
  return ($a->event_time < $b->event_time) ? -1 : 1;
}

function evr_upcoming_events(){
  global $wpdb, $future_days;
   
  $day_count = 1;
  
  while ($day_count < $future_days+1){
	 		list($y,$m,$d) = split("-",date("Y-m-d",mktime($day_count*24,0,0,date("m",evr_time_offset()),date("d",evr_time_offset()),date("Y",evr_time_offset()))));
	 		$events = evr_fetch_events($y,$m,$d);
	 		usort($events, "evr_time_cmp");
	 		if (count($events) != 0) {
	 				$output .= '<li>'.date_i18n(get_option('date_format'),mktime($day_count*24,0,0,date("m",evr_time_offset()),date("d",evr_time_offset()),date("Y",evr_time_offset())));
	 				foreach($events as $event){
	   				if ($event->event_time == '00:00:00') {
	 							$time_string = ' '.__('all day');
	   				} else {
	 						$time_string = ' '.__('Between').' '.date(get_option('time_format'), strtotime(stripslashes($event->start_time))).' - '.date(get_option('time_format'), strtotime(stripslashes($event->end_time)));
	   				}
                    $calendar_url = get_option ( 'er_link_for_calendar_url');
                    $arr_params = array ('regevent_action'=>'register', 'event_id' => $event->id, 'name_of_event'=> stripslashes($event->event_name));
                    $linky =  add_query_arg($arr_params, $calendar_url);
          	$output .= '<ul><li><a href="'.$linky.'" >'.stripslashes($event->event_name).' ('.$time_string.')</a>';
          	$output .= '<br />'.stripslashes($event->event_desc).'</li>';
	 					$output .= '</ul>';	 				}
                    $output .= '</li>';
	 		}
	 		$day_count = $day_count+1;
	 }
   if ($output == ''){
  		$output .=''.__('No event till now!').'</ul>';
   }
	 $visual = '<ul>';
	 $visual .= $output;
	 $visual .= '</ul>';
	 return $visual;
}


function evr_upcoming_event_list($content){
	global $wpdb; 
  $display = "true";

  if (preg_match('{EVR_UPCOMING}',$content)){
  	  if ($display == 'true'){
      		$cal_output = '<span class="page-upcoming-events"><B>Upcoming Events:</B><br />'.evr_upcoming_events().'</span>';
      		$content = str_replace('{EVR_UPCOMING}',$cal_output,$content);      	
  		}else{
  			 $content = str_replace('{EVR_UPCOMING}','',$content); 
  		}
  }
  return $content;
}

?>