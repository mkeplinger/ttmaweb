<?php

/**
 * @author David Fleming
 * @copyright 2010
 */
session_start(); 

$_SESSION['cat_id'] = "";

function create_events_sample_page(){
        	er_plugin_menu();
	 

if(isset($_POST['submit'])){
 
 $create_page=$_REQUEST['create_page'];
         switch ($create_page) {
        	
            case "all_events":
               er_create_all_events_page(); 
            break;
            
            case "category":
               er_create_category_page();
            break;
            
            case "single_event":
                 er_create_single_event_page();
            break;
            
            case "load_samples":
              er_load_samples(); 
            break;
       
            default:
            echo "nothing selected";
            break;
            
            } }else {
   ?>
 <div id="configure_organization_form" class=wrap>
    <h2>Event Registration Samples Configuration</h2>
<div id="event_regis-col-left">


<ul id="event_regis-sortables">

			<li>
				
				<div class="box-mid-head">
					<h2 class="events_reg f-wrench">Sample Events Directions</h2>
				</div>

				<div class="box-mid-body" id="toggle2">
					<div class="padding"> This page allows you to automatically create a sample event, a sample category and sample pages to show you how to work with the shortcodes that enable the pages to show events and registrations.
                    
<?php
       global $wpdb;
       $all_event_id = get_option ( 'all_events_sample_page_id' ); 
       $single_event_id = get_option ( 'single_event_sample_page_id' );
       $category_event_id = get_option ( 'category_sample_page_id' ); 
        $calendar_page_id = get_option ( 'calendar_sample_page_id' );
       
       
       if ($all_event_id != ""){
        $link = get_page_link($all_event_id);
        echo "<br><a href='".$link."'>Sample All Events Listing Page</a><br>";
       }
       if ($single_event_id != ""){
        $link = get_page_link($single_event_id);
        echo "<a href='".$link."'>Sample Single Event Page</a><br>";
       }
       if ($category_event_id != ""){
        $link = get_page_link($category_event_id);
        echo "<a href='".$link."'>Sample Category Events Listing Page</a><br><br>";
       }
       if ($calendar_page_id != ""){
        $link = get_page_link($calendar_page_id);
        echo "<a href='".$link."'>Sample Events Calendar Page</a><br><br>";
       }
       
       ?>
                         
                    
  </div></div></li></ul></div></div>
  <?php
        echo "<div style='float:left; margin-right:20px;'>";
        echo "<form name='form' method='post' action='".request_uri()."&create_page=load_samples'>";
		echo "<input type='hidden' name='create_page' value='load_samples'>";
       	echo "<input  CLASS='button-primary' type='SUBMIT' name='submit' value='LOAD SAMPLE EVENTS'></form></div>";
        
        
            }

}        
        
        
function er_create_all_events_page(){        
        global $wpdb;
        global $post;
        $post_content = addslashes("{EVENTREGIS}");
        $post_title="Sample Page - All Events";
        $post_status ="publish";
        $comment_status="closed";
        $ping_status="closed";
        $post_name="events";
        $post_type="page";
        
        
$sql = array( 
  'comment_status' => $comment_status, // 'closed' means no comments. 
  'ping_status' => $ping_status, //Ping status? 
  'post_content' => $post_content,//[ <the text of the post> ] //The full text of the post. 
  'post_name' => $post_name, // The name (slug) for your post 
  'post_status' => $post_status, //Set the status of the new post. 
  'post_title' => $post_title, //The title of your post. 
  'post_type' => $post_type //Sometimes you want to post a page. 
  );   
 
// Insert the post into the database 
if (wp_insert_post( $sql )){ 
                $sql1 = "SELECT * FROM wp_posts WHERE post_title='".$post_title."'";
                $result = mysql_query ($sql1);
                $page_id = null;    
                while ($row = mysql_fetch_assoc ($result)){
                    $page_id = $row['ID'];
                }echo "<div id='message' class='updated fade'><p><strong>Successful Sample All Events Listing Page Creation</strong></p>";
                  $link = get_page_link($page_id);
                  echo "<a href='".$link."'>Sample All Events Listing</a></div>";
                  
            $option_name = 'all_events_sample_page_id' ;
			$newvalue = $page_id;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);
			  }
              
        $option_name = 'er_link_for_calendar_url';
        $link = get_page_link($page_id);
        $newvalue =  "$link";
		if (get_option ( $option_name )) {
		  	update_option ( $option_name, $newvalue );
		} else {
			$deprecated = ' ';
			$autoload = 'no';
			add_option ( $option_name, $newvalue, $deprecated, $autoload );
		}

                }
                else {	
                echo "<div id='message' class='error'><p><strong>FAILED To Create Page</strong></p></div>";}

} 



function  er_create_single_event_page($cat_id){
   global $display_desc, $header_image, $more_info, $end_month, $end_day, $end_year, $end_date, $end_time, $reg_limit, $custom_cur, $reg_form_defaults, $allow_checks, $send_mail, $is_active, $conf_mail, $use_coupon, $coupon_code, $use_percentage;   //PPAY

//Load Sample Event Into Event Database 
        global $wpdb;
        $events_detail_tbl = get_option ( 'events_detail_tbl' );    
        $event_name = "Sample Event";
        $event_identifier= "SAMPLE";
        $event_desc="This is a sample event";
        $event_location = "Some Venue";
        $start_date = "2010-12-31";
        $start_month = "Dec";
        $start_day = "31";
        $start_year = "2010";
        $start_time = "17:00";
        $event_cost = "0.00";
       $coupon_code_price="0.00";   
        $image_link="";
        $cat_id_pre = "".$_SESSION['cat_id']."";
        $multiple = "Y";
        $category_id = array($cat_id_pre);
        $category_id = serialize($category_id);
      
        $sql=array('event_name'=>$event_name, 'event_desc'=>$event_desc, 'event_location'=>$event_location, 'display_desc'=>$display_desc, 
'image_link'=>$image_link, 'header_image'=>$header_image,'event_identifier'=>$event_identifier,  'more_info'=>$more_info, 
'start_month'=>$start_month, 'start_day'=>$start_day, 'start_year'=>$start_year, 'start_time'=>$start_time, 'start_date'=>$start_date,
'end_month'=>$end_month, 'end_day'=>$end_day,'end_year'=>$end_year, 'end_date'=>$end_date, 'end_time'=>$end_time, 'reg_limit'=>$reg_limit,
'event_cost'=>$event_cost,'custom_cur'=>$custom_cur, 'multiple'=>$multiple, 'reg_form_defaults'=>$reg_form_defaults, 'allow_checks'=>$allow_checks, 'send_mail'=>$send_mail,'is_active'=>$is_active, 'conf_mail'=>$conf_mail, 'use_coupon'=>$use_coupon, 'coupon_code'=>$coupon_code, 'category_id'=>$category_id, 'use_percentage'=>$use_percentage); 
                  
		
                $sql_data = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
	
	           if ($wpdb->insert( get_option('events_detail_tbl'), $sql, $sql_data )){
                echo '<div id="message" class="updated fade"><p><strong>The event has been added.</strong></p></div>';}
                else { 
                echo '<div id="message" class="error"><p><strong>There was an error in your submission, please try again. 
                The event was not saved!';
                print mysql_error(); 
                }
	        $event_id = mysql_insert_id();   

//Create Sample Single Event Page
      
       //Sample Page Content        
        $post_content = addslashes('[Event_Registration_Single event_id="'.$event_id.'"]');
        $post_title="Sample Page - Single Event";
        $post_status ="publish";
        $comment_status="closed";
        $ping_status="closed";
        $post_name="sample single event";
        $post_type="page";
        
        
$post = array( 
  'comment_status' => $comment_status, // 'closed' means no comments. 
  'ping_status' => $ping_status, //Ping status? 
  'post_content' => $post_content,//[ <the text of the post> ] //The full text of the post. 
  'post_name' => $post_name, // The name (slug) for your post 
  'post_status' => $post_status, //Set the status of the new post. 
  'post_title' => $post_title, //The title of your post. 
  'post_type' => $post_type //Sometimes you want to post a page. 
  );   
 
// Insert the post into the database 
if (wp_insert_post( $post )){; 
           
        $sql1 = "SELECT * FROM wp_posts WHERE post_title='".$post_title."'";
        $result = mysql_query ($sql1);
        $page_id = null;    
        while ($row = mysql_fetch_assoc ($result)){
                    $page_id = $row['ID'];
                    }
        echo "<div id='message' class='updated fade'><p><strong>Successful Sample Single Event Page Creation</strong></p>";
        $link = get_page_link($page_id);
        echo "<a href='".$link."'>Sample Single Event Page</a></div>";
                  
        $option_name = 'single_event_sample_page_id' ;
			$newvalue = $page_id;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);}
                    
                   } else { 
                echo '<div id="message" class="error"><p><strong>There was an error in your submission, please try again. 
                The sample page was not created!';
                print mysql_error(); 
                }


}

function er_create_category_page(){
        global $wpdb;
                $category_name= htmlentities2("Sample Events");
                $category_identifier = htmlentities2("SMPL-1");
                $category_desc= htmlentities2("Category for sample events - delete after initial orientation"); 
                $display_category_desc="Y";
                $sql=array('category_name'=>$category_name, 'category_identifier'=>$category_identifier, 'category_desc'=>$category_desc, 'display_desc'=>$display_category_desc); 
                $sql_data = array('%s','%s','%s','%s');
                if ($wpdb->insert( get_option('events_cat_detail_tbl'), $sql, $sql_data )){
                    $cat_id = mysql_insert_id();
                    $_SESSION['cat_id'] = $cat_id;
                    ?>
                	<div id="message" class="updated fade"><p><strong>The category 
                    <?php echo htmlentities2($_REQUEST['category_name']);?> has been added.</strong></p></div>
                <?php }else { ?>
                	<div id="message" class="error"><p><strong>The category 
                    <?php echo htmlentities2($_REQUEST['category_name']);?> was not saved. <?php print mysql_error() ?>.</strong></p></div>
                <?php
                }
             
      
      
      
      
       //Sample Page Content        
        $comment_status="closed";
        $ping_status="closed";
        $post_content = addslashes('[EVENT_REGIS_CATEGORY event_category_id="SMPL-1"]');
        $post_name="sample category event page";
        $post_status ="publish";
        $post_title="Sample Page - Event Category Listing";
        $post_type="page";
        
        
$post = array( 'comment_status' => $comment_status, 'ping_status' => $ping_status, 'post_content' => $post_content, 
  'post_name' => $post_name, 'post_status' => $post_status, 'post_title' => $post_title, 'post_type' => $post_type);   
 
// Insert the post into the database 
if (wp_insert_post( $post )){; 
  
        $sql1 = "SELECT * FROM wp_posts WHERE post_title='".$post_title."'";
        $result = mysql_query ($sql1);
        $page_id = null;    //PPAY
        while ($row = mysql_fetch_assoc ($result)){
                    $page_id = $row['ID'];
                    }
        echo "<div id='message' class='updated fade'><p><strong>Successful Sample Category Event Page Creation</strong></p>";
        $link = get_page_link($page_id);
        echo "<a href='".$link."'>Sample Category Event Page</a></div>";
                  
        $option_name = 'category_sample_page_id' ;
			$newvalue = $page_id;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);}
                    
                   } else { 
                echo '<div id="message" class="error"><p><strong>There was an error in your submission, please try again. 
                The sample page was not created!';
                print mysql_error(); 
                }


}   


function er_load_samples(){
                   er_create_category_page();
                   er_create_events_calendar_page();
                   er_create_all_events_page();
                   er_create_single_event_page($cat_id);    //PPAY should $cat_id be declared global?
                   ?>
<META HTTP-EQUIV="refresh" content="0;URL=admin.php?page=sample">
<?php
                    
}   

function er_create_events_calendar_page(){        
        global $wpdb;
        global $post;

     
       //Sample Page Content        

        $post_content = addslashes("[Event_Registration_Calendar]"); 
        $post_title="Sample Page - Events Calendar";
        $post_status ="publish";
        $comment_status="closed";
        $ping_status="closed";
        $post_name="events";
        $post_type="page";
        
        
$sql = array( 
  'comment_status' => $comment_status, // 'closed' means no comments. 
  'ping_status' => $ping_status, //Ping status? 
  'post_content' => $post_content,//[ <the text of the post> ] //The full text of the post. 
  'post_name' => $post_name, // The name (slug) for your post 
  'post_status' => $post_status, //Set the status of the new post. 
  'post_title' => $post_title, //The title of your post. 
  'post_type' => $post_type //Sometimes you want to post a page. 
  );   
 
// Insert the post into the database 
if (wp_insert_post( $sql )){ 
                $sql1 = "SELECT * FROM wp_posts WHERE post_title='".$post_title."'";
                $result = mysql_query ($sql1);
                $page_id = null;  
                while ($row = mysql_fetch_assoc ($result)){
                    $page_id = $row['ID'];
                }echo "<div id='message' class='updated fade'><p><strong>Successful Sample Events Calendar Page Creation</strong></p>";
                  $link = get_page_link($page_id);
                  echo "<a href='".$link."'>Sample Events Calendar</a></div>";
                  
            $option_name = 'calendar_sample_page_id' ;
			$newvalue = $page_id;
			  if ( get_option($option_name) ) {
				    update_option($option_name, $newvalue);
				  } else {
				    $deprecated=' ';
				    $autoload='no';
				    add_option($option_name, $newvalue, $deprecated, $autoload);
			  }
                }
                else {	
                echo "<div id='message' class='error'><p><strong>FAILED To Create Page</strong></p></div>";}

} 

  
?>