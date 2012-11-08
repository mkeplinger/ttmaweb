<?php

$pageinfo = array('full_name' => 'Manage Newsletter', 'optionname'=>'manager', 'child'=>true, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "blanc.html") );

$options = array();

$options[] = array(	"name" => "Categories",
                        "type" => "managersListNewsletter");
                    

$options_page = new MeeNewsletter($options, $pageinfo);
