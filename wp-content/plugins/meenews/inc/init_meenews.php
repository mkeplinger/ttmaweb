<?php
##########################################################################################
# CONFIG
##########################################################################################
define('MEENEWSPANEL_VERSION', '1.0');

global $meenews_datas;

##########################################################################################
# AUTOLOADER
##########################################################################################

foreach ($Plug_autoload as $path => $includes)
{
	if($includes)
	{
		foreach ($includes as $include)
		{
			switch($path)
			{
			case 'classes':
			include_once(MEENEWS_CLASSES.$include.'.php');
			break;

			case 'managers':
			include_once(MEENEWS_MANAGERS.$include.'.php');
			break;

			case 'tpl':
			include_once(MEENEWS_TPL.$include.'.php');
			break;

                        case 'tools':
			include_once(MEETOOLS.$include.'.php');
			break;

                        case 'custompost':
			include_once(MEECUSTOMS.$include.'.php');
			break;

			case 'option_pages':
			include_once(KFWOPTIONS.$include.'.php');
			break;

			case 'templatefiles':
			include_once(TEMPLATEPATH.'/'.$include.'.php');
			break;
			}
		}

	}
}
?>
