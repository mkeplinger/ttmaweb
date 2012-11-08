<?php

$pageinfo = array('full_name' => 'Widgets Options', 'optionname'=>'mee_widgets', 'child'=>true, 'filename' => basename(__FILE__),
                  "tpl_needed" => array("home" => "styles.html","INSERTS" => "inserts.html","HEADER" => "header.html","FOOTER" => "footer.html"));

$options = array();

$options[] = array(	"name" => "Mee Maps Widgetr",
                        "type" => "startBox");


$options[] = array(	"name" => "Title",
			"desc" => "Top head Title",
			"id"   => "maps_title",
			"std"  => "LOCATION",
			"size" => 80,
			"type" => "text");

$options[] = array(	"name" => "Google Url Map",
			"desc" => "Paste here url google map of your location",
			"id"   => "maps_google_url",
			"std"  => "http://maps.google.es/maps?q=reliegos&amp;ie=UTF8&amp;hl=es&amp;hq=&amp;hnear=Reliegos,+Santas+Martas,+Le%C3%B3n,+Castilla+y+Le%C3%B3n&amp;t=h&amp;ll=42.473253,-5.357566&amp;spn=0.022158,0.036478&amp;z=14&amp;iwloc=A&amp;output=embed",
			"size" => 80,
			"type" => "text");
                    
$options[] = array(	"name" => "Widget Width",
			"desc" => "Top head Title",
			"id"   => "maps_width",
			"std"  => "309",
			"size" => 10,
			"type" => "text");

$options[] = array(	"name" => "Widget Height",
			"desc" => "Top head Title",
			"id"   => "maps_height",
			"std"  => "200",
			"size" => 10,
			"type" => "text");


$options[] = array(     "type" => "endBox");

$options[] = array(	"name" => "Mee Latest Tweets",
                        "type" => "startBox");


$options[] = array(	"name" => "Title",
			"desc" => "your twitter user account",
			"id"   => "mee_latest_tweets_title",
			"std"  => "LATEST TWEET",
			"size" => 80,
			"type" => "text");

$options[] = array(	"name" => "Twitter account",
			"desc" => "your twitter user account",
			"id"   => "twitter_user",
			"std"  => "meetemplates",
			"size" => 80,
			"type" => "text");


$options[] = array(	"name" => "Tweets to show",
			"desc" => "Number of tweets to show",
			"id"   => "twitter_item",
			"std"  => "1",
			"size" => 10,
			"type" => "text");


$options[] = array(	"name" => "Follow Us Title",
			"desc" => "",
			"id"   => "mee_follow_us_title",
			"std"  => "FOLLOW US",
			"size" => 10,
			"type" => "text");

                    
$options[] = array(	"name" => "Facebook account",
			"desc" => "your Facebook user account",
			"id"   => "facebook_user",
			"std"  => "meetemplates",
			"size" => 80,
			"type" => "text");


$options[] = array(     "type" => "endBox");
 
	
          

$options_page = new MeeManager($options, $pageinfo);
