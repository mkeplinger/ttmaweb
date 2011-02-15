<link href="<?php echo $this->plugin_url;?>upload/css/styles.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo $this->plugin_url;?>css/share-buttons.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.js"></script>
<script src="<?php echo $this->plugin_url;?>js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery-ui-1.8.1.min.js"></script> 

<script>
jQuery(document).ready(function($){
	/* grab important elements */
	var sortInput = jQuery('#sort_order');
	//var submit = jQuery('#autoSubmit');
	var messageBox = jQuery('#message-box');
	var list = jQuery('#sortable-list');
	/* create requesting function to avoid duplicate code */
	var request = function() {
		jQuery.ajax({
			beforeSend: function() {
				messageBox.text('Updating the sort order in the database.');
			},
			complete: function() {
				messageBox.text('Database has been updated.');
			},
			data: 'sort_order=' + sortInput[0].value + '&ajax=' + '&do_submit=1&byajax=1', //need [0]?
			type: 'post',
			url: '<?php echo $_SERVER["REQUEST_URI"]; ?>'
		});
	};
	/* worker function */
	var fnSubmit = function(save) {
		var sortOrder = [];
		list.children('div').each(function(){
			sortOrder.push(jQuery(this).data('id'));
		});
		sortInput.val(sortOrder.join(','));
		//console.log(sortInput.val());
		if(save) {
			request();
		}
	};
	/* store values */
	list.children('div').each(function() {
        var span = jQuery(this);
        span.data('id',span.attr('title')).attr('title','');
	});
	/* sortables */
	list.sortable({
		opacity: 0.7,
		update: function() {
            fnSubmit(true);
		}
	});
	list.disableSelection();
	/* ajax form submission */
	jQuery('#dd-form').bind('submit',function(e) {
		if(e) e.preventDefault();
		fnSubmit(true);
	});


        url = "<?php echo $this->plugin_url;?>";
        if($("input[name='vkontakte_like_button_show']").attr('checked')) {
            $("#vkontakte_like_sample_buttons").show();
        } else {
            $("#vkontakte_like_sample_buttons").hide();
        }
        
        if($("input[name='facebook_like_button_show']").attr('checked')) {
            $("#facebook_like_button").show();
        } else {
            $("#facebook_like_button").hide();
        }

        if($("input[name='mailru_like_button_show']").attr('checked')) {
            $("#mailru_like_button").show();
        } else {
            $("#mailru_like_button").hide();
        }
        
        $("input[name='vkontakte_like_button_show']").change(function(){
        if (this.checked) {
		  $("#vkontakte_like_sample_buttons").fadeIn();
        } else {
		  $("#vkontakte_like_sample_buttons").fadeOut();
        }});        
        
        $("input[name='facebook_like_button_show']").change(function(){
        if (this.checked) {
		  $("#facebook_like_button").fadeIn();
        } else {
		  $("#facebook_like_button").fadeOut();
        }});

        $("input[name='mailru_like_button_show']").change(function(){
        if (this.checked) {
		  $("#mailru_like_button").fadeIn();
        } else {
		  $("#mailru_like_button").fadeOut();
        }});

        var buf_one, buf_two, custom_type;
        buf_one = $('#opt_customize_type:checked').val();
        $('.'+buf_one).show();        
        $('input[name=opt_customize_type]:radio').change(function(){
        buf_two=buf_one;
        custom_type=$('#opt_customize_type:checked').val();
        for(i=0;i<=5;i++) {
            $('#'+buf_two+'-'+i).hide();
            $('#'+custom_type+'-'+i).fadeIn();
        }
        buf_one=custom_type;               
        });
 
      
        $("#fb_pic_like").each(function(){
            layout = $("#facebook_like_layout option:selected").val();
            verb = $("#facebook_like_verb option:selected").val();
            color = $("#facebook_like_color option:selected").val();
            url = "<?php echo $this->plugin_url;?>";
            image_url = url+'like/images/facebook/'+layout+'-'+verb+'-'+color+'.png';
            this.src = image_url;
        });
        
        $("#vk_pic_like").each(function(){
            type = $("#vkontakte_like_type:checked").val();
            verb = $("#vkontakte_like_verb option:selected").val();
            url = "<?php echo $this->plugin_url;?>";
            image_url = url+'like/images/vkontakte/'+type+'-'+verb+'.png';
            this.src = image_url;
        });        
        
        $("#facebook_like_layout, #facebook_like_verb, #facebook_like_color").change(function(){
            var src = $("#fb_pic_like").attr('src');
            
            url = "<?php echo $this->plugin_url;?>";
            layout = $("#facebook_like_layout option:selected").val();
            verb = $("#facebook_like_verb option:selected").val();
            color = $("#facebook_like_color option:selected").val();
            image_url = url+'like/images/facebook/'+layout+'-'+verb+'-'+color+'.png';
            var tmp = url+'like/images/facebook/'+layout+'-'+verb+'-'+color+'.png';
            $("#fb_pic_like").fadeOut("slow", function () {
            $("#fb_pic_like").attr("src", tmp);
            });
            $("#fb_pic_like").fadeIn("slow");
            $("#tmp").val(src);

        });
        
        $("#vkontakte_like_verb, #vkontakte_like_type").change(function(){
            var src = $("#vk_pic_like").attr('src');
            
            url = "<?php echo $this->plugin_url;?>";
            type = $('#vkontakte_like_type:checked').val();
            verb = $("#vkontakte_like_verb option:selected").val();
            image_url = url+'like/images/vkontakte/'+type+'-'+verb+'.png';
            var tmp = url+'like/images/vkontakte/'+type+'-'+verb+'.png';
            $("#vk_pic_like").fadeOut("slow", function () {
            $("#vk_pic_like").attr("src", tmp);
            });
            $("#vk_pic_like").fadeIn("slow");
            $("#tmp").val(src);

        });

 });
</script>
<div>
    <div class="wrap">
        <div style="margin-right: 160px;">
            <h2><?php _e('Share Buttons Settings', $this->plugin_domain) ?></h2>
            <div id="container">
                <br />
                <fieldset class="fieldset_image">
                    <legend><?php _e('Upload picture for your site-logo', $this->plugin_domain) ?></legend>
                    <div id="left_col">
                        <form action="<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.php" method="post" name="sleeker" id="sleeker" enctype="multipart/form-data">
                            <input type="hidden" name="maxSize" value="7291456" />
                            <input type="hidden" name="maxW" value="150" />
                            <input type="hidden" name="fullPath" value="<?php echo $this->plugin_url;?>upload/uploads/" />
                            <input type="hidden" name="relPath" value="<?php echo dirname(__FILE__);?>/upload/uploads/" />
                            <input type="hidden" name="colorR" value="255" />
                            <input type="hidden" name="colorG" value="255" />
                            <input type="hidden" name="colorB" value="255" />
                            <input type="hidden" name="maxH" value="150" />
                            <input type="hidden" name="filename" value="filename" />
                            <input type="file"  size="40" id="file_input" name="filename" onchange="ajaxUpload(this.form,'<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.php?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=<?php echo $this->plugin_url;?>upload/uploads/&amp;relPath=../uploads/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'<?php echo $this->plugin_url;?>upload/images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'upload/images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;" />
                        </form>
                        <div style="width: 300px;"><small><?php _e('Files must be <b>.jpg, .gif, .png</b> extension, the desired size of <b>100x100 pixels</b>.',$this->plugin_domain);?></small></div>
                    </div>
                    <div id="right_col">
                        <?php if(file_exists(dirname(__FILE__).'/upload/uploads/logo.png')) { ?>
                            <div id="upload_area"><img src="<?php echo $this->plugin_url;?>upload/uploads/logo.png" /></div>
                        <?php } else { ?>
                            <div id="upload_area"><img src="<?php echo $this->plugin_url;?>images/other/trans.png" width="150px" height="150px"/></div>
                        <?php } ?>
                    </div>
                    <div class="clear"></div>
                    <br />
<!--		    <div class="body_other"><?php _e('You may using <b>logo.png</b> only, no parsing images, go to the "<b>Other Settings</b>" and click checkbox');?></div>-->
                </fieldset>
            </div>            
        
            <fieldset class="fieldset_sort">
                <legend><?php _e('Sort buttons', $this->plugin_domain) ?></legend>
                <div class="body_other">
                <?php	
		    global $wpdb;
                    $query = "SELECT option_id, option_name FROM $wpdb->options WHERE";
                    for($i=0;$i<count($this->social_name)-1;$i++) {
                        $query.= " option_name='".$this->social_name[$i]."' or";
                    }
                    $query.=" option_name='".$this->social_name[$i]."' ORDER BY option_value ASC";
                    $result = mysql_query($query) or die(mysql_error().': '.$query);
                    if(mysql_num_rows($result)) {
                ?>
                        
                <?php 
                    $original_count	=	array();
                    $original		=	array();
		    $new_year 		= 	array();
                    $soft_rect		=	array();
                    $soft_round		=	array();
                    $glossy_rect	=	array();
                    $glossy_round	=	array();

                    for($i=0; $i<sizeof($this->social_name); $i++) {

                        $original[get_option($this->social_name[$i])]		='<img src="'.$this->plugin_url.'images/social/original/'.$this->social_name[$i].'.png" />';
                        $original_count[get_option($this->social_name[$i])]	='<img src="'.$this->plugin_url.'images/social/original_count/'.$this->social_name[$i].'.png" />';
                        $new_year[get_option($this->social_name[$i])]		='<img src="'.$this->plugin_url.'images/social/new_year/'.$this->social_name[$i].'.png" />';
                        $classic[get_option($this->social_name[$i])]		='<img src="'.$this->plugin_url.'images/social/classic/'.$this->social_name[$i].'.png" />';
                        $soft_rect[get_option($this->social_name[$i])]		='<img src="'.$this->plugin_url.'images/social/soft_rect/'.$this->social_name[$i].'.png" />';
                        $soft_round[get_option($this->social_name[$i])]		='<img src="'.$this->plugin_url.'images/social/soft_round/'.$this->social_name[$i].'.png" />';
                        $glossy_rect[get_option($this->social_name[$i])]	='<img src="'.$this->plugin_url.'images/social/glossy_rect/'.$this->social_name[$i].'.png" />';
                        $glossy_round[get_option($this->social_name[$i])]	='<img src="'.$this->plugin_url.'images/social/glossy_round/'.$this->social_name[$i].'.png" />';

                        ksort($original);
                        ksort($original_count);
			ksort($new_year);
			ksort($classic);
                        ksort($soft_rect);
                        ksort($soft_round);
                        ksort($glossy_rect);
                        ksort($glossy_round);
                    }

                    $order = array();
                    $j=0;

                    ?>
                    <div id="sortable-list">
                    <?php
                    while($item = mysql_fetch_assoc($result)) {
                        $j++;
                        echo '<div title="'.$item['option_id'].'" id="'.$item['option_id'].'">';
                                echo '<div id="original-'.$j.'" class="original">'.$original[$j].'</div>';
                                echo '<div id="original_count-'.$j.'" class="original_count">'.$original_count[$j].'</div>';
                                echo '<div id="new_year-'.$j.'" class="new_year">'.$new_year[$j].'</div>';
                                echo '<div id="classic-'.$j.'" class="classic">'.$classic[$j].'</div>';
                                echo '<div id="soft_rect-'.$j.'" class="soft_rect">'.$soft_rect[$j].'</div>';
                                echo '<div id="soft_round-'.$j.'" class="soft_round">'.$soft_round[$j].'</div>';
                                echo '<div id="glossy_rect-'.$j.'" class="glossy_rect">'.$glossy_rect[$j].'</div>';
                                echo '<div id="glossy_round-'.$j.'" class="glossy_round">'.$glossy_round[$j].'</div>';
                        echo '</div>';            
                        $order[] = $item['option_id'];
                    }
                ?>
                        <br />
                        <div style="clear: both;"></div>
                        <br />
                        </div>
                        <div style="clear: both;"></div>
			<br />
                        <div class="note"><?php _e('Please drag and drop buttons for sorting');?></div>
                        <br />
                        <input type="hidden" name="sort_order" id="sort_order" value="<?php echo implode(',',$order); ?>" />
                <?php } else { ?>
                    <p><?php _e('Sorry!  There are no items in the system.');?></p>
                <?php } ?>
<?php
/* on form submission */
if(isset($_POST['do_submit']))  {
    /* split the value of the sortation */
	$ids = explode(',',$_POST['sort_order']);
	/* run the update query for each id */
	foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			$query = "UPDATE $wpdb->options SET option_value = ".($index + 1)." WHERE option_id = ".$id;
			$result = mysql_query($query) or die(mysql_error().': '.$query);
		}
	}
	
	/* now what? */
	if($_POST['byajax']) { die(); } else { $message = 'Sortation has been saved.'; }
}

?>
</div>            
</fieldset>  
            <form method="post" action="options.php">
            <?php settings_fields( 'sb-settings-group' ); ?>
            <fieldset class="fieldset_position">
                <legend><?php _e('Choose the display style for your social buttons', $this->plugin_domain);?></legend>
                <div id="customize_type" class="body_other">
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="original_count" <?php echo (get_option('opt_customize_type') == 'original_count' ? 'checked' : ''); ?> />01. Original Buttons with counter</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="original" <?php echo (get_option('opt_customize_type') == 'original' ? 'checked' : ''); ?> />02. Original Buttons without counter</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="new_year" <?php echo (get_option('opt_customize_type') == 'new_year' ? 'checked' : ''); ?> />03. New-Year Icons</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="classic" <?php echo (get_option('opt_customize_type') == 'classic' ? 'checked' : ''); ?> />04. Classic Icons</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="soft_rect" <?php echo (get_option('opt_customize_type') == 'soft_rect' ? 'checked' : ''); ?> />05. Rectangle Soft Icons</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="soft_round" <?php echo (get_option('opt_customize_type') == 'soft_round' ? 'checked' : ''); ?> />06. Round Soft Icons</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="glossy_rect" <?php echo (get_option('opt_customize_type') == 'glossy_rect' ? 'checked' : ''); ?> />07. Rectangle Glossy Icons</div>
                	<div><input type="radio" name="opt_customize_type" id="opt_customize_type" value="glossy_round" <?php echo (get_option('opt_customize_type') == 'glossy_round' ? 'checked' : ''); ?> />08. Round Glossy Icons</div>
                </div>
                

            </fieldset>
            <br />

            <fieldset class="fieldset_position">
                <legend><?php _e('Enable/Disable Share buttons', $this->plugin_domain);?></legend>
			<div class="body_other">
                            <label for="vkontakte_button_show">
                                <input name="vkontakte_button_show" type="checkbox" id="vkontakte_button_show" value="1" <?php checked(TRUE, $this->vkontakte_button_show); ?> />
                                <?php _e('Vkontakte', $this->plugin_domain) ?>
                            </label>
                            <label for="mailru_button_show">
                                <input name="mailru_button_show" type="checkbox" id="mailru_button_show" value="1" <?php checked(TRUE, $this->mailru_button_show); ?> />
                                <?php _e('Mail.ru', $this->plugin_domain) ?>
                            </label>
                            <label for="facebook_button_show">
                                <input name="facebook_button_show" type="checkbox" id="facebook_button_show" value="1" <?php checked(TRUE, $this->facebook_button_show); ?> />
                                <?php _e('Facebook', $this->plugin_domain) ?>
                            </label>
                            <label for="odnoklassniki_button_show">
                                <input name="odnoklassniki_button_show" type="checkbox" id="odnoklassniki_button_show" value="1" <?php checked(TRUE, $this->odnoklassniki_button_show); ?> />
                                <?php _e('Odnoklassniki', $this->plugin_domain) ?>
                            </label>
                            <label for="twitter_button_show">
                                <input name="twitter_button_show" type="checkbox" id="twitter_button_show" value="1" <?php checked(TRUE, $this->twitter_button_show); ?> />
                                <?php _e('Twitter', $this->plugin_domain) ?>
                            </label>
				<br />
				<div class="note"><?php _e('But will have to sort <b>ALL</b> the buttons');?></div>
			</div>
            </fieldset>
            <br />

            <div class="button_submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', $this->plugin_domain) ?>" />
            </div>

            <fieldset class="fieldset_position">
                <legend><?php _e('Position Share Buttons', $this->plugin_domain);?></legend>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><label for="share_buttons_position"><?php _e('Button horizontal position', $this->plugin_domain) ?></label></th>
                        <td>
                            <select name="share_buttons_position" id="share_buttons_position" value="<?php echo $sb_pos; ?>">
                                <option <?php if($sb_pos == 'right') echo("selected=\"selected\""); ?> value="right"><?php _e('Right', $this->plugin_domain) ?></option>
                                <option <?php if($sb_pos == 'left') echo("selected=\"selected\""); ?> value="left"><?php _e('Left', $this->plugin_domain) ?></option>
                            </select>
                            <span class="description"><?php _e('Select which side you want to display the button: right or left', $this->plugin_domain) ?></p></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><label for="share_buttons_vposition"><?php _e('Button vertical position', $this->plugin_domain) ?></label></th>
                        <td>
                            <select name="share_buttons_vposition" id="share_buttons_vposition" value="<?php echo $sb_vpos; ?>">
                                <option <?php if($sb_vpos == 'top') echo("selected=\"selected\""); ?> value="top"><?php _e('On top of post', $this->plugin_domain) ?></option>
                                <option <?php if($sb_vpos == 'bottom') echo("selected=\"selected\""); ?> value="bottom"><?php _e('On bottom of post', $this->plugin_domain) ?></option>
                            </select>
                            <span class="description"><?php _e('Sets up before or after post/page button are shown', $this->plugin_domain) ?></p></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('The Button displays on', $this->plugin_domain) ?></th>
                        <td>
                            <label for="share_buttons_show_on_posts">
                                <input name="share_buttons_show_on_posts" type="checkbox" id="share_buttons_show_on_posts" value="1" <?php checked(TRUE, $this->show_on_post); ?> />
                                <?php _e('Posts', $this->plugin_domain) ?>
                            </label>
                            <br />
                            <label for="share_buttons_show_on_pages">
                                <input name="share_buttons_show_on_pages" type="checkbox" id="share_buttons_show_on_pages" value="1" <?php checked(TRUE, $this->show_on_page); ?> />
                                <?php _e('Pages', $this->plugin_domain) ?>
                            </label>
                            <br />
                            <label for="share_buttons_show_on_home">
                                <input name="share_buttons_show_on_home" type="checkbox" id="share_buttons_show_on_home" value="1" <?php checked(TRUE, $this->show_on_home); ?> />
                                <?php _e('Home', $this->plugin_domain) ?>
                            </label>
                            <br />
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><label for="share_buttons_exclude"><?php _e('Exclude pages and posts with IDs', $this->plugin_domain) ?></label></th>
                        <td>
                            <input type="text" name="share_buttons_exclude" value="<?php echo esc_attr($this->exclude); ?>" class="regular-text" />
                            <span class="description"><?php _e('Specify IDs of pages and posts which should stay without buttons (separated by commas, eg <code>4, 8, 15, 16, 23, 42</code>)', $this->plugin_domain) ?></span>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <br />
            <fieldset class="fieldset_position">
                <legend><?php _e('Other Settings', $this->plugin_domain);?></legend>
                <div class="body_other">
                        <div><?php _e('Twitter via', $this->plugin_domain); ?>&nbsp;<span class="description">(<?php _e('Your Nickname without "@"', $this->plugin_domain);?>)</span></div>
                        <div><input type="text" name="twitter_via" value="<?php echo esc_attr($this->twitter_via);?>" class="regular-text" /></div>
<!-- in develop			<br />
			<label for="opt_parsing_images">
				<input name="opt_parsing_images" type="checkbox" id="opt_parsing_images" value="1" <?php checked(TRUE, $this->parsing_images); ?> />
				<?php _e("Use only <b>logo.png</b>, no parsing images", $this->plugin_domain); ?>
			</label>-->
                </div>
            </fieldset>

            <fieldset class="fieldset_social">
                <legend><?php _e('Like Buttons', $this->plugin_domain) ?></legend>
		<div class="body_other">
			<div class="note"><?php _e('<b>Like buttons</b> output <b>ONLY</b> bottom page and <b>ONLY</b> in column!');?></div>
		</div>
<!-- Vkontakte.ru Like Button -->

                <div class="head_social">
                    <input name="vkontakte_like_button_show" type="checkbox" id="vkontakte_like_show" value="1" <?php checked(TRUE, $this->vkontakte_like_show); ?> />
                    <?php _e('Show Vkontakte.ru Like Button', $this->plugin_domain) ?>
                </div>

                <div class="body_social" id="vkontakte_like_sample_buttons">

                    <div>
                        <img name="vk_pic_like" id="vk_pic_like" src="<?php echo $this->plugin_url;?>/like/images/vkontakte/full-like.png" />
                    </div>
                    <br />
                    <div>
                        <div><?php _e('<b>API ID:</b>', $this->plugin_domain); ?>&nbsp;<span><?php _e('You can get your <b>"api_id"</b> on this <b><a href="http://vkontakte.ru/apps.php?act=add&site=1">link</a></b>',$this->plugin_domain);?></span></div>
                        <div><input type="text" name="vkontakte_like_api" value="<?php echo esc_attr($this->vkontakte_like_api);?>" class="regular-text" />&nbsp;<span style="color: red;"><?php _e('<b>Required Field</b>', $this->plugin_domain);?></span></div>
                    </div>
                    <br />                   
                    <div>
                        <div style="float: left; width:25px;"><input type="radio" name="vkontakte_like_type" id="vkontakte_like_type" value="full" <?php echo (get_option('vkontakte_like_type') == 'full' ? 'checked' : ''); ?> /></div>
                        <div style="float: left;"><?php _e('Button with textable counter', $this->plugin_domain);?></div>
                        <div style="clear: both;"></div>
                    </div>
                    <div>
                        <div style="float: left; width:25px;"><input type="radio" name="vkontakte_like_type" id="vkontakte_like_type" value="button" <?php echo (get_option('vkontakte_like_type') == 'button' ? 'checked' : ''); ?> /></div>
                        <div style="float: left;"><?php _e('Button with mini counter',$this->plugin_domain);?></div>
                        <div style="clear: both;"></div>
                    </div>
                    <div>
                        <div style="float: left; width:25px;"><input type="radio" name="vkontakte_like_type" id="vkontakte_like_type" value="mini" <?php echo (get_option('vkontakte_like_type') == 'mini' ? 'checked' : ''); ?> /></div>
                        <div style="float: left;"><?php _e('Mini button',$this->plugin_domain);?></div>
                        <div style="clear: both;"></div>
                    </div>
                    <div>
                        <div style="float: left; width:25px;"><input type="radio" name="vkontakte_like_type" id="vkontakte_like_type" value="vertical" <?php echo (get_option('vkontakte_like_type') == 'vertical' ? 'checked' : ''); ?> /></div>
                        <div style="float: left;"><?php _e('Mini button and counter bottom',$this->plugin_domain);?></div>
                        <div style="clear: both;"></div>
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Verb to display', $this->plugin_domain) ?></div>
                        <div>
                            <select name="vkontakte_like_verb" id="vkontakte_like_verb" value="<?php echo $vk_like_verb; ?>">
                                <option <?php if($vk_like_verb == 'like') echo("selected=\"selected\""); ?> value="like"><?php _e('Like', $this->plugin_domain) ?></option>
                                <option <?php if($vk_like_verb == 'recommend') echo("selected=\"selected\""); ?> value="recommend"><?php _e('Interesting', $this->plugin_domain) ?></option>
                            </select>           
                        </div>    
                    </div>
                </div>
                <br />
                
		<div class="head_social">
                    <input name="facebook_like_button_show" type="checkbox" id="facebook_like_show" value="1" <?php checked(TRUE, $this->facebook_like_show); ?> />
                    <?php _e('Show Facebook Like Button', $this->plugin_domain) ?>
                </div>
                <br />
<!-- Facebook.com Like Button -->
                <div class="body_social" id="facebook_like_button">
                    <div>
                        <img name="fb_pic_like" id="fb_pic_like" src="<?php echo $this->plugin_url;?>/like/images/facebook/standart-like-light.png" />
                    </div>
                    <br />
                    <div><?php _e('Layout style', $this->plugin_domain) ?>&nbsp;<span class="description">(<?php _e('Determines the size and amount of social context next to the button', $this->plugin_domain) ?>)</span></div>
                    <div>
                        <select name="facebook_like_layout" id="facebook_like_layout" value="<?php echo $fb_like_layout; ?>">
                            <option <?php if($fb_like_layout == 'standart') echo("selected=\"selected\""); ?> value="standart"><?php _e('standart', $this->plugin_domain) ?></option>
                            <option <?php if($fb_like_layout == 'button_count') echo("selected=\"selected\""); ?> value="button_count"><?php _e('button_count', $this->plugin_domain) ?></option>
                            <option <?php if($fb_like_layout == 'box_count') echo("selected=\"selected\""); ?> value="box_count"><?php _e('box_count', $this->plugin_domain) ?></option>
                        </select>           
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Show Faces');?>&nbsp;<span class="description">(<?php _e('Show profile pictures below the button');?>)</span></div>
                        <div>
                            <input name="facebook_like_faces" type="checkbox" id="facebook_like_faces" value="1" <?php checked(TRUE, $this->facebook_like_faces); if($this->facebook_like_faces==TRUE && $this->facebook_like_height<70) { update_option('facebook_like_height',70); } ?> />
                            <?php _e('Show faces', $this->plugin_domain) ?>
                        </div>    
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Width', $this->plugin_domain); ?>&nbsp;<span class="description">(<?php _e('The width of the plugin, in pixels', $this->plugin_domain);?>)</span></div>
                        <div><input type="text" name="facebook_like_width" value="<?php echo esc_attr($this->facebook_like_width);?>" class="regular-text" /></div>
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Height', $this->plugin_domain); ?>&nbsp;<span class="description">(<?php _e('The height of the plugin, in pixels', $this->plugin_domain);?>)</span></div>
                        <div><input type="text" name="facebook_like_height" value="<?php echo esc_attr($this->facebook_like_height);?>" class="regular-text" /></div>
                    </div>
                    <br />    
                    <div>
                        <div><?php _e('Verb to display', $this->plugin_domain) ?>&nbsp;<span class="description">(<?php _e('The verb to display in the button. Currently only Like and Recommend are supported', $this->plugin_domain) ?>)</span></div>
                        <div>
                            <select name="facebook_like_verb" id="facebook_like_verb" value="<?php echo $fb_like_verb; ?>">
                                <option <?php if($fb_like_verb == 'like') echo("selected=\"selected\""); ?> value="like"><?php _e('like', $this->plugin_domain) ?></option>
                                <option <?php if($fb_like_verb == 'recommend') echo("selected=\"selected\""); ?> value="recommend"><?php _e('recommend', $this->plugin_domain) ?></option>
                            </select>           
                        </div>    
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Color scheme', $this->plugin_domain) ?>&nbsp;<span class="description">(<?php _e('The color scheme of the plugin', $this->plugin_domain) ?>)</span></div>
                        <div>
                            <select name="facebook_like_color" id="facebook_like_color" value="<?php echo $fb_like_color; ?>">
                                <option <?php if($fb_like_color == 'light') echo("selected=\"selected\""); ?> value="light"><?php _e('light', $this->plugin_domain) ?></option>
                                <option <?php if($fb_like_color == 'dark') echo("selected=\"selected\""); ?> value="dark"><?php _e('dark', $this->plugin_domain) ?></option>
                            </select>            
                        </div>
                    </div>
                </div>
		<br/>

		<div class="head_social">
                    <input name="mailru_like_button_show" type="checkbox" id="mailru_like_show" value="1" <?php checked(TRUE, $this->mailru_like_show); ?> />
                    <?php _e('Show Mailru Like Button', $this->plugin_domain) ?>
                </div>
                <br />
<!-- Mail.ru Like Button -->
                <div class="body_social" id="mailru_like_button">
                    <div>
                        <img name="mr_pic_like" id="mr_pic_like" src="<?php echo $this->plugin_url;?>/like/images/mailru/mailru_like.png" />
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Show Faces');?>&nbsp;<span class="description">(<?php _e('Show profile pictures below the button');?>)</span></div>
                        <div>
                            <input name="mailru_like_faces" type="checkbox" id="mailru_like_faces" value="1" <?php checked(TRUE, $this->mailru_like_faces); ?> />
                            <?php _e('Show faces', $this->plugin_domain) ?>
                        </div>    
                    </div>
                    <br />
                    <div>
                        <div><?php _e('Show Text');?>&nbsp;<span class="description">(<?php _e('Show text Name who recommed this post');?>)</span></div>
                        <div>
                            <input name="mailru_like_show_text" type="checkbox" id="mailru_like_show_text" value="1" <?php checked(TRUE, $this->mailru_like_show_text); if($this->mailru_like_show_text==FALSE && $this->mailru_like_width>90) { update_option('mailru_like_width',90); } else if($this->mailru_like_show_text==TRUE && $this->mailru_like_width<550) { update_option('mailru_like_width',550); } ?> />
                            <?php _e('Show Text', $this->plugin_domain) ?>
                        </div>    
                    </div>
		    <br />
                    <div>
                        <div><?php _e('Width', $this->plugin_domain); ?>&nbsp;<span class="description">(<?php _e('The width of the plugin, in pixels', $this->plugin_domain);?>)</span></div>
                        <div><input type="text" name="mailru_like_width" value="<?php echo esc_attr($this->mailru_like_width);?>" class="regular-text" /></div>
                    </div>
                    <br />    
<!--                    <div>
                        <div><?php _e('Verb to display', $this->plugin_domain) ?>&nbsp;<span class="description">(<?php _e('The verb to display in the button. Currently only Like and Recommend are supported', $this->plugin_domain) ?>)</span></div>
                        <div>
                            <select name="mailru_like_verb" id="mailru_like_verb" value="<?php echo $mr_like_verb; ?>">
                                <option <?php if($mr_like_verb == 'like') echo("selected=\"selected\""); ?> value="like"><?php _e('Like', $this->plugin_domain) ?></option>
                                <option <?php if($mr_like_verb == 'recommend') echo("selected=\"selected\""); ?> value="recommend"><?php _e('Recommend', $this->plugin_domain) ?></option>
                            </select>           
                        </div>    
                    </div>-->
                </div>                


            </fieldset>
            <br />

            <div class="button_submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', $this->plugin_domain) ?>" />
            </div>
            </form>
        </div>
    </div>
</div>