<?php

$pageinfo = array('full_name' => __('Newsletter','meenews'), 'optionname'=>'meenews', 'child'=>false, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "styles.html","INSERTS" => "inserts.html","HEADER" => "header.html","FOOTER" => "footer.html"));

$options = array();


$options[] = array(	"name" => __("Configuration","meenews"),
                        "type" => "startBox"); 


$options[] = array(	"name" => __("Subject General","meenews"),
                        "desc" => __("Default general subject of newsletters","meenews"),
                        "id"   => "default_subject",
			"std"  => "MeeNewsletter Monthly",
                        "size" => 80,
			"type" => "text");

$options[] = array(	"name" => __("Use custom Post Data for thumbnails","meenews"),
                        "desc" => __("This option only works in commercial version","meenews"),
                        "id"   => "use_custom",
                        "std"  => __("Do you use a post Metadata for your post thumbnail? ","meenews"),
			"type" => "inf");



$options[] = array(	"name" => __("Default Email","meenews"),
                        "desc" => __("Set the default Newsletter sender Email.","meenews"),
                        "id"   => "default_email",
			"std"  => "email@email.com",
                        "size" => 80,
			"type" => "text");


$options[] = array(	"name" => __("Choose categories to select post in design newsletter:","meenews"),
                        "desc" => __("Select the categories you want to include in newsletter post selection.","meenews"),
                        "id"   => "cat_newsletter_sel",
                        "std"  => "",
			"type" => "checkbox");

$options[] = array(	"name" => __("Analytics campaigns:","meenews"),
                        "desc" => __("This option only works in commercial version","meenews"),
                        "id"   => "use_custom",
                        "std"  => __("Select true if you use analytics in your page","meenews"),
			"type" => "inf");


$options[] = array(     "type" => "endBox");

$options[] = array(	"name" => __("SMTP Options","meenews"),
                        "type" => "startBox");

                    
$options[] = array(	"name" => __("Send with SMTP?","meenews"),
                        "std" => __("Do you want to use SMTP protocol to send newsletter emails?.","meenews"),
                        "id"   => "want_smtp",
                        "desc" => __("This option only works in commercial version","meenews"),
			"type" => "inf");


$options[] = array(     "type" => "endBox");


$options[] = array(	"name" => __("Mail Messages Configuration","meenews"),
                        "type" => "startBox");

$options[] = array(	"name" => __("Mail confirmation message:","meenews"),
                        "desc" => __("Set the Newsletter registration confirmation message.","meenews"),
                        "id"   => "text_mail_confirmation",
			"std"  => __("We have received a newsletter subscription request at %TITLEBLOG%  %URLBLOG%
                                   in order to complete your subscription must click on the following link:
                                   %CONFIRMATIONURL%
                                   If you do not wish to receive this NewsLetter, apologize and please ignore this email. ","meenews"),
			"type" => "textarea");

$options[] = array(	"name" => __("Subscription delete:","meenews"),
                        "desc" => __("Set the Newsletter unsubscribe message.","meenews"),
                    "id"   => "text_delete_subscription",
			"std"  => __("If you no longer wish to receive this newsletter please click <a href ='%CONFIRMATIONURL%' style='%SPECIALSTYLE%;font-size:9px'> Here </a>
                                   and automatically unsubscribe. ","meenews"),
			"type" => "textarea");

$options[] = array(	"name" => __("End subscription:","meenews"),
                        "desc" => __("Set the Newsletter unsubscribed confirmation message.","meenews"),
                        "id"   => "text_end_subscription",
			"std"  => __("It has been successfully removed to the newsletter %TITLEBLOG%  at:  %URLBLOG%
                                      we apologizes if didn't enjoy this newsletter, we hope to see you soon","meenews"),
			"type" => "textarea");

$options[] = array(	"name" => __("Mail remove confirmation:","meenews"),
                        "desc" => __("Set fish active confirmation message.","meenews"),
                        "id"   => "text_finish_subscription",
			"std"  => __("Congratulations your subscriptios at %TITLEBLOG%  %URLBLOG%
                                   Has been finished correctly.
                                   
                                    ","meenews"),
			"type" => "textarea");


 $options[] = array(	"name" => __("Information About us:","meenews"),
                        "desc" => __("Set soem information about yourself or your company.","meenews"),
                        "id"   => "text_about_us",
			"std"  => __("Hello, this newsletter contains information about my site for any query please send an email to ejemplo@mail.com: ","meenews"),
			"type" => "textarea");
                    
$options[] = array(     "type" => "endBox");


$options_page = new MeenewsManager($options, $pageinfo);
