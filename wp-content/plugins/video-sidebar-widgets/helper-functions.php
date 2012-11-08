<?php
/**
* Helper Functions.
*
**/

//Main Video Function
function VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,$admin,$shortcode){

//admin = true to show in widget admin
//admin = false to show in blog sidebar

        $v_autoplay2 = $autoplaysetting;
        $v_id2 = $videoid;
		$v_source = $videosource;		
        $v_width2 = $videowidth;
        $v_height2 = $videoheight;
        //declare empty variable to prevent WordPress debug error
        $flashvar = "";
        $flashvar2 = "";
  
      	$source = $v_source;
        
		//test for source and assign codes accordingly	
		switch ($source) {
		
		case "":
		$value = "";
		$flashvar = "";
		$flashvar2 = "";
		break;		
		
        case 'YouTube':
        $value = "http://www.youtube.com/v/$v_id2&autoplay=$v_autoplay2&loop=0&rel=0";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
		case 'Vimeo':
		$value =  "http://vimeo.com/moogaloop.swf?clip_id=$v_id2&amp;server=vimeo.com&amp;loop=0&amp;fullscreen=1&amp;autoplay=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
		case 'MySpace':
		$value =  "http://mediaservices.myspace.com/services/media/embed.aspx/m=$v_id2,t=1,mt=video,ap=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
		case 'Veoh':
		$value = "http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&";
		$value.= "permalinkId=$v_id2&player=videodetailsembedded&id=anonymous&videoAutoPlay=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
	    case 'Blip':
		$value =  "http://blip.tv/play/$v_id2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
	    case 'WordPress':
		$value =  "http://s0.videopress.com/player.swf?v=1.02";
		$flashvar = "<param name='flashvars' value='$v_id2'>";
		$flashvar2 = 'flashvars="guid='.$v_id2.'"';
        break;
		
		case 'Viddler':
		$value =  "http://www.viddler.com/player/$v_id2";
		if($v_autoplay2=='1'){
		$flashvar = "<param name=\"flashvars\" value=\"autoplay=t\" />\n";
		$flashvar2 = 'flashvars="autoplay=t" ';
		}
        break;
		
		case 'DailyMotion':
		$value =  "http://www.dailymotion.com/swf/$v_id2&autoStart=$v_autoplay2&related=0";
		$flashvar = "";
		$flashvar2 = "";
        break;
				
		
		case 'Revver':
		$value = "http://flash.revver.com/player/1.0/player.swf?mediaId=$v_id2&autoStart=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'Metacafe':
		$id = split('/',$v_id2);
		$value = "http://www.metacafe.com/fplayer/$id[0]/$id[1].swf";
		if($v_autoplay2=='1'){
		$flashvar = "";
		$flashvar2 = 'flashVars="playerVars=showStats=no|autoPlay=yes|"';
		}
		break;
		
		case 'Tudou':
		$value = "$v_id2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'Youku':
		$value = "$v_id2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'cn6':
		$value = "$v_id2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'Google':
		$value = "http://video.google.com/googleplayer.swf?docid=$v_id2&hl=en&fs=true";
		if($v_autoplay2=='1'){
		$flashvar = "";
		$flashvar2 = 'FlashVars="autoPlay=true&playerMode=embedded"';
		}
		break;
		
		case 'Tangle':
		$value = "http://www.tangle.com/flash/swf/flvplayer.swf";
		if($v_autoplay2=='1'){
		$flashvar = "";
		$flashvar2 = "FlashVars=\"viewkey=$v_id2&autoplay=$v_autoplay2\"";
		}else{
		$flashvar = "";
		$flashvar2 = "FlashVars=\"viewkey=$v_id2\"";
		}
		break;
	
		}
		
		if($shortcode=="true"){
		//added in version 2.3
		//return instead of echo video on blog using shortcode
		$vsw_code = "\n<object width=\"$v_width2\" height=\"$v_height2\">\n";
		$vsw_code .= $flashvar;
		$vsw_code .= "<param name=\"allowfullscreen\" value=\"true\" />\n";
		$vsw_code .= "<param name=\"allowscriptaccess\" value=\"always\" />\n";
		$vsw_code .= "<param name=\"movie\" value=\"$value\" />\n";
		$vsw_code .= "<param name=\"wmode\" value=\"transparent\">\n";
		$vsw_code .= "<embed src=\"$value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
		$vsw_code .= "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
		$vsw_code .= $flashvar2;
		$vsw_code .= "width=\"$v_width2\" height=\"$v_height2\">\n";
		$vsw_code .= "</embed>\n";
		$vsw_code .= "</object>\n\n";
		return $vsw_code;
		}
		elseif($admin=="true"){
		// echo video in admin
        echo "\n<object width=\"212\" height=\"172\">\n";
		echo $flashvar;
		echo "<param name=\"allowfullscreen\" value=\"true\" />\n";
		echo "<param name=\"allowscriptaccess\" value=\"always\" />\n";
		echo "<param name=\"movie\" value=\"$value\" />\n";
		echo "<param name=\"wmode\" value=\"transparent\">\n";
		echo "<embed src=\"$value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
		echo "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
		echo $flashvar2;
		echo "width=\"212\" height=\"172\">\n";
		echo "</embed>\n";
		echo "</object>\n\n";

        }else{
		
		// echo video on blog
		echo "\n<object width=\"$v_width2\" height=\"$v_height2\">\n";
		echo $flashvar;
		echo "<param name=\"allowfullscreen\" value=\"true\" />\n";
		echo "<param name=\"allowscriptaccess\" value=\"always\" />\n";
		echo "<param name=\"movie\" value=\"$value\" />\n";
		echo "<param name=\"wmode\" value=\"transparent\">\n";
		echo "<embed src=\"$value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
		echo "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
		echo $flashvar2;
		echo "width=\"$v_width2\" height=\"$v_height2\">\n";
		echo "</embed>\n";
		echo "</object>\n\n";
		}


}//end of function VSWShowVideo

/**
* Added in Version 2.3
* Shortcode to echo out video
* Usage [vsw id="123456" source="vimeo" width="400" height="300" autoplay="no"]
**/

function vsw_show_video($atts, $content = null) {
	extract(shortcode_atts(array(
	    "id" => ' ',
		"source" => ' ',
		"width" => ' ',
		"height" => ' ',
		"autoplay" => ' ',
	), $atts));
	
return vsw_show_video_class($id,$source,$width,$height,$autoplay);
}

add_shortcode("vsw", "vsw_show_video");

//function to be used in shortcode or directly in theme
function vsw_show_video_class($id,$source,$width,$height,$autoplay){

        $vsw_id = $id;
		$vsw_width = $width;
		$vsw_height = $height;
		

		//convert string of source to lowercase
        $source = strtolower($source);

        //should have used all lowercase in previous functions
		//now have to switch it.
		switch ($source) {
				
		case "":
		$vsw_source = "";
		break;
		
		case 'youtube':
		$vsw_source = 'YouTube';
		break;
		
		case 'vimeo':
		$vsw_source = 'Vimeo';
        break;
		
		case 'myspace':
		$vsw_source = 'MySpace';
        break;
		
		case 'veoh':
		$vsw_source = 'Veoh';
        break;
		
	    case 'bliptv':
		$vsw_source = 'Blip';
        break;
		
	    case 'wordpress':
		$vsw_source = 'WordPress';
        break;
		
		case 'viddler':
		$vsw_source = 'Viddler';
        break;
		
		case 'dailymotion':
		$vsw_source = 'DailyMotion';
        break;
				
		
		case 'revver':
		$vsw_source = 'Revver';
		break;
		
		case 'metacafe':
		$vsw_source = 'Metacafe';
		break;
		
		case 'tudou':
		$vsw_source = 'Tudou';
		break;
		
		case 'youku':
		$vsw_source = 'Youku';
		break;
		
		case 'cn6':
		$vsw_source = 'cn6';
		break;
		
		case 'google':
		$vsw_source = 'Google';
		break;
		
		case 'tangle':
		$vsw_source = 'Tangle';
		break; 
		
		}
		
		//string to lowercase
		$autoplay = strtolower($autoplay);
		
		//switch autoplay yes or no to 1 or 0
		switch ($autoplay) {
		
		case "":
		$vsw_autoplay = 0;
		break;
		
		case 'no':
		$vsw_autoplay = 0;
		break;
		
		case 'yes':
		$vsw_autoplay = 1;
		break;
		
		}
			
	
$vsw_code = VSWShowVideo($vsw_source,$vsw_id,$vsw_autoplay,$vsw_width,$vsw_height,'false','true');

return $vsw_code;
}
?>