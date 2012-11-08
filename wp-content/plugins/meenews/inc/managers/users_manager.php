<?php

$pageinfo = array('full_name' => 'List and Users', 'optionname'=>'users', 'child'=>true, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "withsidebar.html","HEADER" => "header.html","FOOTER" => "footer.html") );

$options = array();


$options[] = array(	"name" => "Add new list",
                        "column" => "sidebar",
                        "type" => "startBox");

$options[] = array(	"name" => "Mail confirmation message:",
                        "column" => "sidebar",
			"type" => "listform");

$options[] = array(     "type" => "endBox",
                        "column" => "sidebar");

$options[] = array(	"name" => "Add Subscriber",
                        "column" => "sidebar",
                        "type" => "startBox");

$options[] = array(	"name" => "Mail confirmation message:",
                        "column" => "sidebar",
			"type" => "formsubscriber");

$options[] = array(     "type" => "endBox",
                        "column" => "sidebar");

$options[] = array(	"name" => "Import CSV",
                        "column" => "sidebar",
                        "type" => "startBox");

$options[] = array(	"name" => "Mail confirmation message:",
                        "column" => "sidebar",
			"type" => "formimport");

$options[] = array(     "type" => "endBox",
                        "column" => "sidebar");

$options[] = array(	"name" => "Export CSV",
                        "column" => "sidebar",
                        "type" => "startBox");

$options[] = array(	"name" => "Mail confirmation message:",
                        "column" => "sidebar",
			"type" => "formexport");

$options[] = array(     "type" => "endBox",
                        "column" => "sidebar");

$options[] = array(	"name" => "Input Name:",
                        "desc" => "Display name input in subscription form",
                        "id"   => "want_name",
                        "std"  => "true",
			"type" => "printList");





$options_page = new MeeUsers($options, $pageinfo);


