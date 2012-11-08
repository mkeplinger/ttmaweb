<?php

$pageinfo = array('full_name' => __('New Newsletter',"meenews"), 'optionname'=>'newsletter', 'child'=>true, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "editor_newsletter.html","HEADER" => "header.html") );

$options = array();

$options[] = array(	"name" => __("Categories","meenews"),
                        "column" => "sidebar",
                        "type" => "extractSelectedTables");
                    


$options_page = new MeeNewsletter($options, $pageinfo);
