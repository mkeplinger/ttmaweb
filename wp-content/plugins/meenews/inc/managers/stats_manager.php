<?php

$pageinfo = array('full_name' => __('Statistics',"meenews"), 'optionname'=>'stats', 'child'=>true, 'filename' => basename(__FILE__),
"tpl_needed" => array("home" => "blanc.html","HEADER" => "header.html") );

$options = array();

$options_page = new MeeStats($options, $pageinfo);
