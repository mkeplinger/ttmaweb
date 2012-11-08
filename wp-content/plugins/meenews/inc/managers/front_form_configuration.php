<?php

$pageinfo = array('full_name' => __('Front form Configuration',"meenews"), 'optionname'=>'front', 'child'=>true, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "styles.html","INSERTS" => "inserts.html","HEADER" => "header.html","FOOTER" => "footer.html"));

$options = array();


$options[] = array(	"name" => __("Html subscription form editor","meenews"),
                        "type" => "startBox");


$options[] = array(	"name" => __("Custom HTML front subscription","meenews"),
                        "std" => __("This option only works in commercial version","meenews"),
                        "id"   => "want_smtp",
                        "desc" => __("Do you want modify HTML code of front end subscription form?","meenews"),
			"type" => "inf");
                    
$options[] = array(     "type" => "endBox");

$options[] = array(	"name" => __("Form colors Configuration","meenews"),
                        "type" => "startBox");

                    
$options[] = array(	"name" => __("Input text Color","meenews"),
                        "desc" => __("Do you want call to action in you home page?.","meenews"),
                        "id"   => "input_textcolor",
                        "std"  => "#000",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Input background Color","meenews"),
                        "desc" => __("Do you want call to action in you home page?.","meenews"),
                        "id"   => "input_backgroundcolor",
                        "std"  => "#FFF",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Input border color","meenews"),
                        "desc" => __("Do you want call to action in you home page?.","meenews"),
                        "id"   => "input_bordercolor",
                        "std"  => "#000",
			"type" => "colorpicker");


$options[] = array(	"name" => __("Advertise color","meenews"),
                        "desc" => __("Do you want call to action in you home page?.","meenews"),
                        "id"   => "advertise_color",
                        "std"  => "#000",
			"type" => "colorpicker");


$options[] = array(     "type" => "endBox");


$options[] = array(	"name" => __("Default Front Form Configuration","meenews"),
                        "type" => "startBox");

$options[] = array(	"name" => __("Input Email","meenews"),
                        "desc" => __("This text appears into Email input field","meenews"),
                        "id"   => "input_text_email",
                        "std"  => __("Insert Email","meenews"),
			"type" => "text");

$options[] = array(	"name" => __("Default button","meenews"),
                        "desc" => __("Default image button subscription","meenews"),
                        "id"   => "default_button",
			"std"  => MEENEWS_LIB_URI."img/button_subscription.jpg",
                        "size" => 80,
			"type" => "image");
                    
$options[] = array(     "type" => "endBox");


$options[] = array(	"name" => __("Subscription form configuration","meenews"),
                        "type" => "startBox");


$options[] = array(	"name" => __("Input Name:","meenews"),
                        "desc" => __("If you want that your users inserts her Name","meenews"),
                        "id"   => "want_name",
                        "std" => __("This option only works in commercial version","meenews"),
                        "id"   => "want_smtp",
			"type" => "inf");
                    

$options[] = array(	"name" => __("Input Company:","meenews"),
                        "desc" => __("If you want that your users inserts her Name","meenews"),
                        "id"   => "want_name",
                        "std" => __("This option only works in commercial version","meenews"),
                        "id"   => "want_smtp",
			"type" => "inf");

$options[] = array(	"name" => __("Input Country:","meenews"),
                        "desc" => __("If you want that your users inserts her country","meenews"),
                        "id"   => "want_name",
                        "std" => __("This option only works in commercial version","meenews"),
                        "id"   => "want_smtp",
			"type" => "inf");

$options[] = array(	"name" => __("Input Address:","meenews"),
                        "desc" => __("If you want that your users inserts her address","meenews"),
                        "id"   => "want_name",
                        "std" => __("This option only works in commercial version","meenews"),
                        "id"   => "want_smtp",
			"type" => "inf");
                    
$options[] = array(	"name" => __("Legal Conditions (check):","meenews"),
                        "desc" => __("Display checkbox of legal conditions","meenews"),
                        "id"   => "want_name",
                        "std" => __("This option only works in commercial version","meenews"),
                        "id"   => "want_smtp",
			"type" => "inf");

$options[] = array(     "type" => "endBox");


$options_page = new MeenewsManager($options, $pageinfo);


