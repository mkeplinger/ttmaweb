<?php
/**
* Widget - Random Video Widget
*
*/

class RandomVideoSidebarWidget extends WP_Widget {

function RandomVideoSidebarWidget() {
$widget_ops = array( 'classname' => 'randomvideosidebar', 'description' => __('A Random Video Widget. Randomly selects 1 of the 5 preset videos for display', 'randomvideosidebar') );
$control_ops = array( 'width' => 705, 'height' => 600, 'id_base' => 'randomvideosidebar' );
$this->WP_Widget( 'randomvideosidebar', __('Random Video Sidebar Widget', 'randomvideosidebar'), $widget_ops, $control_ops );
}


function widget( $args, $instance ) {
extract( $args );

        $RV_title = apply_filters('widget_title', $instance['RV_title'] );
        $RV_width = $instance['RV_width'];
        $RV_height = $instance['RV_height'];
        $RV_autoplay = $instance['RV_autoplay'];
        $RV_id1 = $instance['RV_id1'];
		$RV_source1 = $instance['RV_source1'];
		$RV_cap1 = $instance['RV_cap1'];
		$RV_id2 = $instance['RV_id2'];
		$RV_source2 = $instance['RV_source2'];
		$RV_cap2 = $instance['RV_cap2'];
		$RV_id3 = $instance['RV_id3'];
		$RV_source3 = $instance['RV_source3'];
		$RV_cap3 = $instance['RV_cap3'];
		$RV_id4 = $instance['RV_id4'];
		$RV_source4 = $instance['RV_source4'];
		$RV_cap4 = $instance['RV_cap4'];
		$RV_id5 = $instance['RV_id5'];
		$RV_source5 = $instance['RV_source5'];
		$RV_cap5 = $instance['RV_cap5'];
		
		//define empty variable to prevent WordPress debug error
		$rv_flashvar = "";
		$rv_flashvar2 = "";
							
        echo $before_widget;

        if ( $RV_title )
        echo $before_title . $RV_title . $after_title;
		
		//using rand() to select which video to show 
		
		$selection = rand(1,5); 

        switch($selection){
	
		case 1:
		$Embed_id = $RV_id1;
		$Embed_source = $RV_source1;
		$Embed_cap = $RV_cap1;
		break;
		
		case 2:
		$Embed_id = $RV_id2;
		$Embed_source = $RV_source2;
		$Embed_cap = $RV_cap2;
		break;
		 
		case 3:
		$Embed_id = $RV_id3;
		$Embed_source = $RV_source3;
		$Embed_cap = $RV_cap3;
		break;
		
		case 4:
		$Embed_id = $RV_id4;
		$Embed_source = $RV_source4;
		$Embed_cap = $RV_cap4;
		break;
		
		case 5:
		$Embed_id = $RV_id5;
		$Embed_source = $RV_source5;
		$Embed_cap = $RV_cap5;
		break;
		
		}	
		
		//test for empty $Embed_id and empty $Embed_source. if empty, 
		//assign to same as first video id and source
		
		If(empty($Embed_id)){
		$Embed_id = $RV_id1;
		$Embed_source = $RV_source1;
		$Embed_cap = $RV_cap1;		
		}
				
		$select_source = $Embed_source;
	
		switch ($select_source) {
		
		case "":
		$rv_value = "";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = "";
		break;		
		
        case 'YouTube':
		$rv_value = "http://www.youtube.com/v/$Embed_id&autoplay=$RV_autoplay&loop=0&rel=0";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
        break;
		
		case 'Vimeo':
		$rv_value =  "http://vimeo.com/moogaloop.swf?clip_id=$Embed_id&amp;server=vimeo.com&amp;loop=0&amp;fullscreen=1&amp;autoplay=$RV_autoplay";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
        break;
		
		case 'MySpace':
		$rv_value =  "http://mediaservices.myspace.com/services/media/embed.aspx/m=$Embed_id,t=1,mt=video,ap=$RV_autoplay";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
        break;
		
		case 'Veoh':
		$rv_value = "http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&permalinkId=$Embed_id";
		$rv_value.= "&player=videodetailsembedded&id=anonymous&videoAutoPlay=$RV_autoplay";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
        break;
		
	    case 'Blip':
		$rv_value =  "http://blip.tv/play/$Embed_id";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
        break;
		
        case 'WordPress':
		$rv_value =  "http://s0.videopress.com/player.swf?v=1.02";
		$rv_flashvar = "<param name='flashvars' value='$Embed_id'>";
		$rv_flashvar2 = 'flashvars="guid='.$Embed_id.'"';
		$rv_cap = $Embed_cap;
		break;
		
		case 'Viddler':
		$rv_value =  "http://www.viddler.com/player/$Embed_id";
		if($RV_autoplay=='1'){
		$rv_flashvar = "<param name=\"flashvars\" value=\"autoplay=t\" />\n";
		$rv_flashvar2 = 'flashvars="autoplay=t" ';
		}
		$rv_cap = $Embed_cap;
        break;
		
		case 'DailyMotion':
		$rv_value =  "http://www.dailymotion.com/swf/$Embed_id&autoStart=$RV_autoplay&related=0";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
        break;
				
		
		case 'Revver':
		$rv_value = "http://flash.revver.com/player/1.0/player.swf?mediaId=$Embed_id&autoStart=$RV_autoplay";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
		break;
		
		case 'Metacafe':
		$rid = split('/',$Embed_id);
		$rv_value = "http://www.metacafe.com/fplayer/$rid[0]/$rid[1].swf";
		if($RV_autoplay=='1'){
		$rv_flashvar = "";
		$rv_flashvar2 = 'flashVars="playerVars=showStats=no|autoPlay=yes|"';
		}
		$rv_cap = $Embed_cap;
		break;
		
		case 'Tudou':
		$rv_value = "$Embed_id";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
		break;
		
		case 'Youku':
		$rv_value = "$Embed_id";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
		break;
		
		case 'cn6':
		$rv_value = "$Embed_id";
		$rv_flashvar = "";
		$rv_flashvar2 = "";
		$rv_cap = $Embed_cap;
		break;
		
		case 'Google':
		$rv_value = "http://video.google.com/googleplayer.swf?docid=$Embed_id&hl=en&fs=true";
		if($RV_autoplay=='1'){
		$rv_flashvar = "";
		$rv_flashvar2 = 'FlashVars="autoPlay=true&playerMode=embedded"';
		}
		$rv_cap = $Embed_cap;
		break;
		
		case 'Tangle':
		$rv_value = "http://www.tangle.com/flash/swf/flvplayer.swf";
		if($RV_autoplay=='1'){
		$rv_flashvar = "";
		$rv_flashvar2 = "FlashVars=\"viewkey=$Embed_id&autoplay=$RV_autoplay\"";
		}else{
		$rv_flashvar = "";
		$rv_flashvar2 = "FlashVars=\"viewkey=$Embed_id\"";
		}
		$rv_cap = $Embed_cap;
		break;
		
		
	
		}
		
		
		
        echo "\n<object width=\"$RV_width\" height=\"$RV_height\">\n";
		echo $rv_flashvar;
		echo "<param name=\"allowfullscreen\" value=\"true\" />\n";
		echo "<param name=\"allowscriptaccess\" value=\"always\" />\n";
		echo "<param name=\"movie\" value=\"$rv_value\" />\n";
		echo "<param name=\"wmode\" value=\"transparent\">\n";
		echo "<embed src=\"$rv_value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
		echo "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
		echo $rv_flashvar2;
		echo "width=\"$RV_width\" height=\"$RV_height\">\n";
		echo "</embed>\n";
		echo "</object>\n\n";
		echo "<p class=\"VideoCaption\">$rv_cap</p>";
		
		
        echo $after_widget;
    }


function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['RV_title'] = strip_tags( $new_instance['RV_title'] );
        $instance['RV_width'] = strip_tags( $new_instance['RV_width'] );
        $instance['RV_height'] = strip_tags( $new_instance['RV_height'] );
        $instance['RV_autoplay'] = strip_tags( $new_instance['RV_autoplay'] );
        $instance['RV_id1'] = strip_tags( $new_instance['RV_id1'] );
		$instance['RV_source1'] = strip_tags( $new_instance['RV_source1'] );
		$instance['RV_cap1'] = $new_instance['RV_cap1'];
		$instance['RV_id2'] = strip_tags( $new_instance['RV_id2'] );
		$instance['RV_source2'] = strip_tags( $new_instance['RV_source2'] );
		$instance['RV_cap2'] = $new_instance['RV_cap2'];
		$instance['RV_id3'] = strip_tags( $new_instance['RV_id3'] );
		$instance['RV_source3'] = strip_tags( $new_instance['RV_source3'] );
		$instance['RV_cap3'] = $new_instance['RV_cap3'];
		$instance['RV_id4'] = strip_tags( $new_instance['RV_id4'] );
		$instance['RV_source4'] = strip_tags( $new_instance['RV_source4'] );
		$instance['RV_cap4'] = $new_instance['RV_cap4'];
		$instance['RV_id5'] = strip_tags( $new_instance['RV_id5'] );
		$instance['RV_source5'] = strip_tags( $new_instance['RV_source5'] );
		$instance['RV_cap5'] = $new_instance['RV_cap5'];			
        return $instance;
}


function form($instance) {
$instance = wp_parse_args( (array) $instance, array( 'RV_title' => '', 'RV_width' => '', 'RV_height' => '', 'RV_autoplay' => '','RV_id1' => '','RV_source1' => '','RV_cap1' => '', 'RV_id2' => '','RV_source2' => '','RV_cap2' => '', 'RV_id3' => '','RV_source3' => '','RV_cap3' => '', 'RV_id4' => '','RV_source4' => '','RV_cap4' => '', 'RV_id5' => '','RV_source5' => '','RV_cap5' => '') );

        $instance['RV_title'] = strip_tags( $instance['RV_title'] );
        $instance['RV_width'] = strip_tags( $instance['RV_width'] );
        $instance['RV_height'] = strip_tags( $instance['RV_height'] );
        $instance['RV_autoplay'] = strip_tags( $instance['RV_autoplay'] );
        $instance['RV_id1'] = strip_tags( $instance['RV_id1'] );
		$instance['RV_source1'] = strip_tags( $instance['RV_source1'] );
		$instance['RV_cap1'] = $instance['RV_cap1'];
		$instance['RV_id2'] = strip_tags( $instance['RV_id2'] );
		$instance['RV_source2'] = strip_tags( $instance['RV_source2'] );
		$instance['RV_cap2'] = $instance['RV_cap2'];
		$instance['RV_id3'] = strip_tags( $instance['RV_id3'] );
		$instance['RV_source3'] = strip_tags( $instance['RV_source3'] );
		$instance['RV_cap3'] = $instance['RV_cap3'];
		$instance['RV_id4'] = strip_tags( $instance['RV_id4'] );
		$instance['RV_source4'] = strip_tags( $instance['RV_source4'] );
		$instance['RV_cap4'] = $instance['RV_cap4'];
		$instance['RV_id5'] = strip_tags( $instance['RV_id5'] );
		$instance['RV_source5'] = strip_tags( $instance['RV_source5'] );
		$instance['RV_cap5'] = $instance['RV_cap5'];			


?>
<div style="width:220px;height:350px;float:left;margin:0px 15px 20px 5px">
<h2>General Settings</h2>
<!--Title -->        
<p>
<label for="<?php echo $this->get_field_id('RV_title'); ?>">Widget Title:</label> 
<input class="widefat" id="<?php echo $this->get_field_id('RV_title'); ?>" name="<?php echo $this->get_field_name('RV_title'); ?>" type="text" value="<?php echo $instance['RV_title']; ?>" />
</p>

<!--Width -->
<p>
<label for="<?php echo $this->get_field_id('RV_width'); ?>">Video Width: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_width'); ?>" name="<?php echo $this->get_field_name('RV_width'); ?>" type="text" value="<?php echo $instance['RV_width']; ?>" />
</p>

<!--Height -->
<p>
<label for="<?php echo $this->get_field_id('RV_height'); ?>">Video Height: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_height'); ?>" name="<?php echo $this->get_field_name('RV_height'); ?>" type="text" value="<?php echo $instance['RV_height']; ?>" />
</p>

<!--auto play -->
<p>
<label for="<?php echo $this->get_field_id( 'RV_autoplay' ); ?>">Auto Play:</label> 
<select id="<?php echo $this->get_field_id( 'RV_autoplay' );?>" name="<?php echo $this->get_field_name( 'RV_autoplay' );?>" class="widefat" style="width:100%;">';
<option value='1' <?php  if($instance['RV_autoplay'] == '1'){echo 'selected="selected"';}?>>Yes</option>
<option value='0' <?php  if($instance['RV_autoplay'] == '0'){echo 'selected="selected"';}?>>No</option>
</select>
</p>
<p>Please fill up settings before clicking on save to display video.</p>
</div>

<div style="width:220px;height:350px;float:left;margin:0px 15px 20px 0px">
<!--first video setting -->
<h2>Video 1</h2>
<?php
//show video in Random Video Widget Admin
				$autoplaysetting = '0';
				$videoid = $instance['RV_id1'];
				$videosource = $instance['RV_source1']; 
				$videowidth = "";
				$videoheight = "";
				//$admin = true // to show video in admin
				
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'true','false');
?>
<p>
<label for="<?php echo $this->get_field_id( 'RV_source1' ); ?>">Select Video 1 Source:</label> 
<select id="<?php echo $this->get_field_id( 'RV_source1' );?>" name="<?php echo $this->get_field_name( 'RV_source1' );?>" class="widefat" style="width:100%;">

				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
				
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($instance['RV_source1'] == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id('RV_id1'); ?>">Video 1 ID: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_id1'); ?>" name="<?php echo $this->get_field_name('RV_id1'); ?>" type="text" value="<?php echo $instance['RV_id1']; ?>" /></p>

<p>
<label for="<?php echo $this->get_field_id('RV_cap1'); ?>">Video Caption: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_cap1'); ?>" name="<?php echo $this->get_field_name('RV_cap1'); ?>" type="text" value="<?php echo $instance['RV_cap1']; ?>" /></p>

</div>
<div style="width:220px;height:350px;float:left;margin:0px 15px 20px 0px">

<!--second video setting -->
<h2>Video 2</h2>
<?php
//show video in Random Video Widget Admin
				$autoplaysetting = '0';
				$videoid = $instance['RV_id2'];
				$videosource = $instance['RV_source2']; 
				$videowidth = "";
				$videoheight = "";
				//$admin = true // to show video in admin
				
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'true','false');
?>
<p>
<label for="<?php echo $this->get_field_id( 'RV_source2' ); ?>">Select Video 2 Source:</label> 
<select id="<?php echo $this->get_field_id( 'RV_source2' );?>" name="<?php echo $this->get_field_name( 'RV_source2' );?>" class="widefat" style="width:100%;">

				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
				
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($instance['RV_source2'] == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>


</select>
</p>

<p>
<label for="<?php echo $this->get_field_id('RV_id2'); ?>">Video 2 ID: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_id2'); ?>" name="<?php echo $this->get_field_name('RV_id2'); ?>" type="text" value="<?php echo $instance['RV_id2']; ?>" /></p>

<p>
<label for="<?php echo $this->get_field_id('RV_cap2'); ?>">Video Caption: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_cap2'); ?>" name="<?php echo $this->get_field_name('RV_cap2'); ?>" type="text" value="<?php echo $instance['RV_cap2']; ?>" /></p>

</div>
<div style="width:220px;height:350px;float:left;margin:0px 15px 20px 0px">

<!--third video setting -->
<h2>Video 3</h2>
<?php
//show video in Random Video Widget Admin
				$autoplaysetting = '0';
				$videoid = $instance['RV_id3'];
				$videosource = $instance['RV_source3']; 
				$videowidth = "";
				$videoheight = "";
				//$admin = true // to show video in admin
				
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'true','false');
?>
<p>
<label for="<?php echo $this->get_field_id( 'RV_source3' ); ?>">Select Video 3 Source:</label> 
<select id="<?php echo $this->get_field_id( 'RV_source3' );?>" name="<?php echo $this->get_field_name( 'RV_source3' );?>" class="widefat" style="width:100%;">

				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
				
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($instance['RV_source3'] == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id('RV_id3'); ?>">Video 3 ID: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_id3'); ?>" name="<?php echo $this->get_field_name('RV_id3'); ?>" type="text" value="<?php echo $instance['RV_id3']; ?>" /></p>

<p>
<label for="<?php echo $this->get_field_id('RV_cap3'); ?>">Video Caption: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_cap3'); ?>" name="<?php echo $this->get_field_name('RV_cap3'); ?>" type="text" value="<?php echo $instance['RV_cap3']; ?>" /></p>


</div>
<div style="width:220px;height:350px;float:left;margin:0px 15px 20px 5px">

<!--fourth video setting -->
<h2>Video 4</h2>
<?php
//show video in Random Video Widget Admin
				$autoplaysetting = '0';
				$videoid = $instance['RV_id4'];
				$videosource = $instance['RV_source4']; 
				$videowidth = "";
				$videoheight = "";
				//$admin = true // to show video in admin
				
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'true','false');
?>
<p>
<label for="<?php echo $this->get_field_id( 'RV_source4' ); ?>">Select Video 4 Source:</label> 
<select id="<?php echo $this->get_field_id( 'RV_source4' );?>" name="<?php echo $this->get_field_name( 'RV_source4' );?>" class="widefat" style="width:100%;">
				
				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
				
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($instance['RV_source4'] == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id('RV_id4'); ?>">Video 4 ID: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_id4'); ?>" name="<?php echo $this->get_field_name('RV_id4'); ?>" type="text" value="<?php echo $instance['RV_id4']; ?>" /></p>

<p>
<label for="<?php echo $this->get_field_id('RV_cap4'); ?>">Video Caption: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_cap4'); ?>" name="<?php echo $this->get_field_name('RV_cap4'); ?>" type="text" value="<?php echo $instance['RV_cap4']; ?>" /></p>

</div>
<div style="width:220px;height:350px;float:left;margin:0px 15px 20px 0px">

<!--fifth video setting -->
<h2>Video 5</h2>
<?php
//show video in Random Video Widget Admin
				$autoplaysetting = '0';
				$videoid = $instance['RV_id5'];
				$videosource = $instance['RV_source5']; 
				$videowidth = "";
				$videoheight = "";
				//$admin = true // to show video in admin
				
				VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,'true','false');
?>
<p>
<label for="<?php echo $this->get_field_id( 'RV_source5' ); ?>">Select Video 5 Source:</label> 
<select id="<?php echo $this->get_field_id( 'RV_source5' );?>" name="<?php echo $this->get_field_name( 'RV_source5' );?>" class="widefat" style="width:100%;">
				
				<?php
				$network = array('YouTube','Vimeo','MySpace','Veoh','Blip','WordPress','Viddler','DailyMotion','Revver','Metacafe','Tudou','Youku','cn6','Google');
				
				foreach($network as $net){
				
				echo "<option value='$net'";		
				if($instance['RV_source5'] == $net){
				echo 'selected="selected"';
				}
				echo" >$net</option>";
				
				}
				
				?>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id('RV_id5'); ?>">Video 5 ID: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_id5'); ?>" name="<?php echo $this->get_field_name('RV_id5'); ?>" type="text" value="<?php echo $instance['RV_id5']; ?>" /></p>

<p>
<label for="<?php echo $this->get_field_id('RV_cap5'); ?>">Video Caption: </label>
<input class="widefat" id="<?php echo $this->get_field_id('RV_cap5'); ?>" name="<?php echo $this->get_field_name('RV_cap5'); ?>" type="text" value="<?php echo $instance['RV_cap5']; ?>" /></p>

</div>
<p style="clear:both"></p>


        <?php

    }

}
?>