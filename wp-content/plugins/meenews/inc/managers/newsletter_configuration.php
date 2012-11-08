<?php

$pageinfo = array('full_name' => __('Newsletter Configuration',"meenews"), 'optionname'=>'newsletter', 'child'=>true, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "styles.html","INSERTS" => "inserts.html","HEADER" => "header.html","FOOTER" => "footer.html"));

$options = array();


$options[] = array(	"name" =>__("Template Selection","meenews"),
                        "type" => "startBox");

$options[] = array(	"name" => __("Select Template","meenews"),
                        "desc" => __("Select the Newsletter template you want to use","meenews"),
                        "id"   => "template_sel",
						"std"  =>  "Suntemplate",
						"type" => "template");

$options[] = array(	"name" => __("template","meenews"),
                        "desc" => __("Template name","meenews"),
                        "id"   => "template_sel",
                        "std"  => "Suntemplate",
			"type" => "text");

$options[] = array(	"name" => __("Default Header Image","meenews"),
                        "desc" => __("Default general header image","meenews"),
                        "id"   => "default_header",
			"std"  => MEENEWS_LIB_URI."img/default_header.jpg",
                        "size" => 80,
			"type" => "image");

$options[] = array(	"name" => __("Do you want to set your own thmbnail size?","meenews"),
                        "desc" => __("Set your height and width in px","meenews"),
                        "id"   => "want_custom_size",
                        "std"  => "false",
                        "type2"=> 'hide_option',
			"type" => "Iswitch");
                    
$options[] = array(	"name" => __("Width Thumbnail","meenews"),
                        "desc" => "",
                        "id"   => "widht_thumbnail",
                        "std"  => "200",
			"type" => "text");

$options[] = array(	"name" => __("Height Thumbnail","meenews"),
                        "desc" => "",
                        "id"   => "height_thumbnail",
                        "std"  => "200",
                        "type2" => "end_hide",
			"type" => "text");

$options[] = array(     "type" => "endBox");

$options[] = array(	"name" => __("Colors Configuration","meenews"),
                        "type" => "startBox");

                    
$options[] = array(	"name" => __("Title Color","meenews"),
                        "desc" => __("Set the color you want to use for Newsletter title.","meenews"),
                        "id"   => "color_title",
                        "std"  => "#646464",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Post text Color","meenews"),
                        "desc" => __("Set the color you want to use for Newsletter post text.","meenews"),
                        "id"   => "color_post",
                        "std"  => "#858585",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Link Color","meenews"),
                        "desc" => __("Set the color you want to use for Newsletter links text.","meenews"),
                        "id"   => "color_link",
                        "std"  => "#646464",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Issue Color","meenews"),
                        "desc" => __("Set the color you want to use issue list in newsletter with 2 columns.","meenews"),
                        "id"   => "color_issue",
                        "std"  => "#646464",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Footer / Head Color Text","meenews"),
                        "desc" => __("Set the color you want to use in header / footer messages.","meenews"),
                        "id"   => "color_special",
                        "std"  => "#fcfcfc",
			"type" => "colorpicker");
                    
$options[] = array(	"name" => __("Body Color","meenews"),
                        "desc" => __("Set the color you want to use for Newsletter body text.","meenews"),
                        "id"   => "color_body",
                        "std"  => "#999999",
			"type" => "colorpicker");
                    
$options[] = array(	"name" => __("Background Newsletter","meenews"),
                        "desc" => __("Set the color you want to use for Newsletter background.","meenews"),
                        "id"   => "color_background",
                        "std"  => "#FFF",
			"type" => "colorpicker");

$options[] = array(	"name" => __("Background Header/Footer","meenews"),
                        "desc" => __("Set the color you want to use for Newsletter header and footer background.","meenews"),
                        "id"   => "color_header",
                        "std"  => "#646464",
			"type" => "colorpicker");

$options[] = array(     "type" => "endBox");


$options[] = array(	"name" => __("Text Sizes configuration","meenews"),
                        "type" => "startBox");
                    
$options[] = array(	"name" => __("Title Size","meenews"),
                        "desc" => __("Set the size you want to use for Newsletter title (pixels).","meenews"),
                        "id"   => "size_title",
                        "std"  => "16",
			"type" => "text");

$options[] = array(	"name" => __("Post text size","meenews"),
                        "desc" => __("Set the size you want to use for Newsletter post text (pixels)","meenews"),
                        "id"   => "size_post",
                        "std"  => "14",
			"type" => "text");

$options[] = array(	"name" => __("Link size","meenews"),
                        "desc" => __("Set the size you want to use for Newsletter links text (pixels)","meenews"),
                        "id"   => "size_link",
                        "std"  => "14",
			"type" => "text");

$options[] = array(	"name" => __("Other text size","meenews"),
                        "desc" => __("Set the size you want to use for all other Newsletter texts (pixels)","meenews"),
                        "id"   => "size_other",
                        "std"  => "14",
			"type" => "text");
                    
$options[] = array(     "type" => "endBox");

$options[] = array(	"name" => __("Newsletter texts","meenews"),
                        "type" => "startBox");

$options[] = array(	"name" => __("No visualize","meenews"),
                        "desc" => __("Set the text for incorrect visualization advice.","meenews"),
                        "id"   => "t_no_visualize",
                        "std"  => __("If you don't visualize this mail correctly please click <a href='%URLNEWSLETTER%'>here</a>","meenews"),
			"type" => "text");

$options[] = array(	"name" => __("Confirmation email sent","meenews"),
                        "desc" => __("Here you can set the succesful subscription message.","meenews"),
                        "id"   => "email_sent",
                        "std"  => __("The confirmation message was sent, check your email","meenews"),
			"type" => "text");

$options[] = array(	"name" => __("Already subscribed","meenews"),
                        "desc" => __("Here you can set the already present message","meenews"),
                        "id"   => "already_subscribed",
                        "std"  => __("This email is already subscribed","meenews"),
			"type" => "text");

$options[] = array(	"name" => __("Data error","meenews"),
                        "desc" => __("Here you can set the data processing error message","meenews"),
                        "id"   => "data_error",
                        "std"  => __("There was an error processing your data, prease try again later","meenews"),
			"type" => "text");

$options[] = array(	"name" => __("Invalid email","meenews"),
                        "desc" => __("Here you can set the invalid email message.","meenews"),
                        "id"   => "invalid_email",
                        "std"  => __("Please insert a valid email address","meenews"),
			"type" => "text");

$options[] = array(     "type" => "endBox");

$options_page = new MeenewsManager($options, $pageinfo);
