<?php
##################################################################
class MeenewsManager{
##################################################################

	var $options; 		
	var $pageinfo;		
	var $db_tables;
	var $database_options;	
	var $grouped;		
	var $saved_optionname; 	
        var $tplPath;
        var $message;
        var $jump_path;
        
	//constructor
	function MeenewsManager($options, $pageinfo)
	{
		// set options and page variables
                global $meenews_datas;
		$this->options = $options;
		$this->pageinfo = $pageinfo;
		$this->grouped = false;
		$this->make_data_available();


                $this->tplPath = MEENEWS_TPL_SOURCES;
		
		global $option_pagecount;
		$option_pagecount = isset($option_pagecount) ? $option_pagecount + 1 : 1;

		
		$priority = $option_pagecount;
		if(!$this->pageinfo['child']) $priority = 1;

		add_action('admin_menu', array(&$this, 'add_admin_menu'), $priority);
                add_action('admin_menu', array(&$this, 'putImageScripts'), $priority);
	}




	
	function add_admin_menu()
	{
		$top_level = "Newsletter";

		if(!$this->pageinfo['child'])
		{
                        if ($this->pageinfo['tabName'] != "")$top_level = $this->pageinfo['tabName']." Options";

			add_menu_page($top_level, $top_level, 7, $this->pageinfo['filename'], array(&$this, 'initialize'));
                        
			define('TOP_LEVEL_PLUG', $this->pageinfo['filename']);

		}
		
		else
		{
                        $top = TOP_LEVEL_PLUG;
                        if ($this->pageinfo['tabName'] != "")$top = $this->pageinfo['tabName'];
			add_submenu_page($top, $this->pageinfo['full_name'], $this->pageinfo['full_name'], 7, $this->pageinfo['filename'], array(&$this, 'initialize'));
		}
	}

	function make_data_available()
	{
		global $meenews_datas;

		foreach ($this->options as $option)
		{
			if($option['type'] == 'boxes')
			{
				$this->add_widget($option);
			}

			if($option['std'])
			{
				$meenews_datas_std[$this->pageinfo['optionname']][$option['id']] = $option['std'];
			}
		}

		$this->saved_optionname = 'meenews_'.$this->pageinfo['optionname'];
                
		$meenews_datas[$this->pageinfo['optionname']] = get_option($this->saved_optionname);
                

		$meenews_datas[$this->pageinfo['optionname']] = array_merge((array)$meenews_datas_std[$this->pageinfo['optionname']], (array)$meenews_datas[$this->pageinfo['optionname']]);

		$meenews_datas[$this->pageinfo['optionname']] = $this->htmlspecialchars_deep($meenews_datas[$this->pageinfo['optionname']]);

        }
        ##############################################################
	# START_HIDE
	##############################################################
	function hide_option($values)
	{
                if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];
                
                $option .=  "<div id='".$values['id']."_hide'";

                if ($values['std'] == "false") $option .= "style='display:none'";

                $option .= ">";


                return $option;

	}
        ##############################################################
	# END_HIDE
	##############################################################
	function end_hide($values)
	{
                $option .=  "</div>";


                return $option;

	}
	function htmlspecialchars_deep($mixed, $quote_style = ENT_QUOTES, $charset = 'UTF-8')
	{
	    if (is_array($mixed) || is_object($mixed))
	    {
	        foreach($mixed as $key => $value)
	        {
	            $mixed[$key] = $this->htmlspecialchars_deep($value, $quote_style, $charset);
	        }
	    }
	    elseif (is_string($mixed))
	    {
	        $mixed = htmlspecialchars_decode($mixed, $quote_style);
	    }

	    return $mixed;
	}

        function getTpl(){
                
                $this->tpl = new fastTPL($this->tplPath);
		$this->tpl->define($this->pageinfo['tpl_needed']);
		$this->tpl->assign("STYLE", file_get_contents(MEENEWS_STYLES));
                $this->tpl->assign("JAVAS", $this->getMeJavas());

                $this->tpl->assign("PAGE_NAME", $this->pageinfo['full_name']);

                if ($this->message != ""){
                    $this->message = "<div id='akismet-warning' class='updated fade'><p>".$this->message."</p></div> ";
                    $this->tpl->assign("MESSAGE", $this->message);
                }
                $this->tpl->assign("INPUTS", $this->getMeInputs());
                $this->tpl->assign("SIDEBAR", $this->getMeSidebar());
                $this->tpl->assign("MEDIA", $this->getMediaLibrary());
		$this->tpl->assign("FOOTER_TXT", __("Please click the help button in the top right corner of this page for links to the documentation and support options.", 'anyfont'));
                $this->putImageScripts();
                    
                $this->parsesTpls($this->pageinfo['tpl_needed']);
		$this->page_html =  $this->tpl->fetchParsed("home");
                
                
        }

        function parsesTpls($parses){

            foreach ($parses as  $parse => $page){
                $this->tpl->assign($parse, $this->tpl->fetchParsed($parse));
            }
        }
        function getMeJavas(){

            $java = ' <script src="'.MEENEWS_TPL_SOURCES_URI.'jquery.iphone-switch.js" type="text/javascript"></script>';
			 $java  .= '<link rel="stylesheet" media="screen" type="text/css" href="'.MEENEWS_URI.'inc/colorpicker/css/colorpicker.css" />';
			 $java  .= '<link rel="stylesheet" media="screen" type="text/css" href="'.MEENEWS_URI.'inc/colorpicker/css/layout.css" />';
			 $java  .= '<script type="text/javascript" src="'.MEENEWS_URI.'inc/colorpicker/js/colorpicker.js"></script>';
			 $java  .= '<script type="text/javascript" src="'.MEENEWS_URI.'inc/colorpicker/js/eye.js"></script>';

            return $java;
        }

        function putImageScripts(){

            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_register_script('my-upload', MEENEWS_MANAGERS_URI.'js/customPanel.js', array('jquery','media-upload','thickbox'));
            wp_enqueue_script('my-upload');
            wp_enqueue_style('thickbox');

        }
        function getMeInputs(){
           foreach ($this->options as $option)
		{

                    if ($option['column'] != "sidebar"){
			if (method_exists($this, $option['type']))
			{
				$campos .= $this->$option['type']($option);
			}
                        if (method_exists($this, $option['type2']))
			{
				$campos .= $this->$option['type2']($option);
			}
                    }
		}

                return $campos;
        }

        function getMeSidebar(){
           foreach ($this->options as $option)
		{

                    if ($option['column'] == "sidebar"){
			if (method_exists($this, $option['type']))
			{
				$campos .= $this->$option['type']($option);
			}
                        if (method_exists($this, $option['type2']))
			{
				$campos .= $this->$option['type2']($option);
			}
                    }
		}

                return $campos;
        }
	function initialize()
	{
		$this->get_save_options();

		$this->getTpl();

                print($this->page_html);

               
	}




	function get_save_options()
	{
		$options = $newoptions  = get_option($this->saved_optionname);


		if ( $_POST['save_mee_panel'] )
		{

                    $this->message = "Data Saved";
			foreach ($_POST as $key => $value)
			{

                                if (!is_array($value)){

                                    $value = stripslashes($value);
                                    $newoptions[$key] = htmlspecialchars($value, ENT_QUOTES,"UTF-8");
                                }else{
                                    $newoptions[$key] = $value;
                                }
                                if ($key == 'template_sel'){
                                       $file =  MEENEWS_TEMPLATE.$value."/style.css";
                                       $newoptions['defaultOptions'] = MeeNewsletter::defaultThemeData($file);
                                }
                                if ($key == 'want_modify_html'){
                                    if ($value == 'true'){
                                        $paso_html = true;
                                    }else{
                                        $paso_html = false;
                                        
                                    }
                                }
                                  if ($key == 'html_front'){
                                    if (!$paso_html ){
                                        $newoptions[$key] = FrontMeeNews::printFormSubscription();
                                    }
                                }

				if (preg_match("/(\w+)(hidden)$/", $key, $result))
				{
					$loops = $newoptions[$key];
					$newoptions[$key] = 0;
					$final =  $result[1].'final';
					$newoptions[$final] = "";
					for($i = 0; $i < $loops; $i++)
					{
						$name = $result[1].$i;
						$newoptions[$name] = stripslashes($_POST[$name]);
						if($newoptions[$name] != "")
						{
							$newoptions[$key]++;

							$newoptions[$final] .= $newoptions[$name];
							if($i+2 < $loops)
							{
								$newoptions[$final] .=", ";
							}
						}
					}
					$newoptions[$key]++;
				}

				if (preg_match("/^(matrix_)(page_)(\w+_)(\d+)/", $key, $result))
				{
					$final_field_matrix = $result[1].$result[3].'final';
				}
                              
			}


			unset($newoptions[$final_field_matrix]);

			$save_matrix_count = 0;
			foreach ($newoptions as $key => $value)
			{
				if($save_matrix_count < $_POST['super_matrix_count'])
				{
					if (preg_match("/^(matrix_)(page_)(\w+_)(\d+)/", $key, $result))
					{
						foreach ($newoptions as $key2 => $value2)
						{
							if (preg_match("/^(matrix_)(".$result[3].")(".$result[4].")_final/", $key2, $result2))
							{
								$newoptions[$final_field_matrix][$value] = $value2;
								$save_matrix_count++;
							}
						}
					}
				}
			}
		}




		if ( $options != $newoptions )
		{
			$options = $newoptions;
			update_option($this->saved_optionname, $options);
		}

		if($options)
		{
			foreach ($options as $key => $value)
			{
				$options[$key] = empty($options[$key]) ? false : $options[$key];
			}
		}

		$this->database_options = $options;
	}


	function add_widget($values)
	{
		for ($i = 1; $i <= $values['count']; $i++)
		{
			if ( function_exists('register_sidebar') )
				register_sidebar(array(
				'name' => $values['widget'].' '.$i,
				'before_widget' => '<div id="%1$s" class="box_small box box'.$i.' widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
				));
		}
	}

        function GetWpCategories()
        {
                        global $wpdb;


                        if( $wpdb->terms != '' )
                        {
                                $sql = "SELECT t.term_id AS cat_ID, t.name AS cat_name FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'category' ORDER BY t.name";
                        }
                        else
                        {
                                $sql = "SELECT cat_ID, cat_name FROM $wpdb->categories ORDER BY cat_name";
                        }

                        $results = $wpdb->get_results($sql);
                        if (!isset($results))
                                $results = array();
                        return $results;
        }

        function getMediaLibrary($startImages = 0, $media = 20){
            global $wpdb, $post;
            $total_objects = $wpdb->get_var( "SELECT COUNT( * ) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent < 1" );
            $start = $startImages;
            $media_per_page = $media;
            $objects = $wpdb->get_results( $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM $wpdb->posts WHERE post_type = 'attachment' AND post_status != 'trash' AND post_parent < 1 LIMIT %d, %d", $start, $media_per_page ) );

            foreach ( $objects as $post ) {

                $post_owner = ( get_current_user_id() == $post->post_author ? 'self' : 'other' );
                $att_title = _draft_or_post_title();

		if ( $thumb = wp_get_attachment_image( $post->ID, array(80, 60), true ) ) $mediaObject[]['thumb'] = $thumb;

		get_edit_post_link( $post->ID, true );

                $mediaObject[]['thumb_url'] = wp_get_attachment_image_src( $post->ID, array(80, 60), 1 );

		if ( preg_match( '/^.*?\.(\w+)$/', get_attached_file( $post->ID ), $matches ) )
			$mediaObject[]['type'] =  esc_html( strtoupper( $matches[1] ) );
		else
			$mediaObject[]['type'] =  strtoupper( str_replace( 'image/', '', get_post_mime_type() ) );

		$mediaObject[]['view'] = '<a href="' . get_permalink($post->ID) . '" title="' . esc_attr(sprintf(__('View &#8220;%s&#8221;'), $title)) . '" rel="permalink">' . __('View') . '</a>';
            }
        }


	##############################################################
	# TITLE
	##############################################################
	function title($values)
	{
		//$outsource = '<h3>'.$values['name'].'</h3>';
		if (isset($values['desc']))$outsource .=  '<div class="box_info">'.$values['desc'].'</div>';

                return $outsource;
        }

        function inf($values){
            if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];

	        $option .=  '<div class="item">';
                $option .=  "<label for='menu_style'>".$values['name']."</label>";
				if ($values['desc'] != ""){
					$option .= '<div class="desc">
									<input type="text" DISABLED size="'.$values['size'].'" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'" />
									<span style="color:red;background-color:black">'.$values['desc'].'</span>
								</div>';
				}
				$option .=  '</div>';

                return $option;
        }
        ##############################################################
	# START BOX IN WRAP
	##############################################################
        function startBox($values){


          if ($values['column'] == "sidebar"){
                $outsource = '<div id="tagsdiv-post_tag" class="postbox " >
                              <div class="handlediv" title="Click to toggle"><br /></div><h3 class="hndle"><span>'.$values['name'].'</span></h3>
                              <div class="inside">';

          }else{

            if ($values['class'] == "")$values['class'] = " fullwidth left";
            $outsource = '<fieldset class="'.$values['class'].'">
                          <legend><span>'.$values['name'].'<span></legend>
                          <div class="anyfont-settings-content">';
            if ($values['desc'] != "")
             $outsource .= '<p style="clear:both"><img src="'.MEENEWS_TPL_SOURCES_URI.'icons/48x48/information.jpg" width="28" height="28" style="float:left; margin-right:20px;padding-bottom:20px">'.$values['desc'].'</p>
                          <p style="border-bottom:1px solid silver;width:95%"></p><br>';

             $outsource = '<div id="'.$id.'" class="meta-box-sortables">
                                <div id="postexcerpt" class="postbox" >
                                    <div class="handlediv" title="'. __("Click here to Open / Close box ", 'meenews').'"><br /></div><h3 class="hndle"><span>'.$values['name'].'</span></h3>
                                    <div class="inside">';
          }
            return $outsource;

        }
        ##############################################################
	# START TOOL MANAGER WITH SIDEBAR
	##############################################################
         function startSideTool($values){

          $sidebar = '
            <div id="tagsdiv-post_tag" class="postbox " >
              <div class="handlediv" title="Click to toggle"><br /></div><h3 class="hndle"><span>'.$values['name'].'</span></h3>
              <div class="inside">';

          return $sidebar;
        }
        ##############################################################
	# END TOOL MANAGER WITH SIDEBAR
	##############################################################
        function endSideTool(){
            $sidebar = '</div></div>';

            return $sidebar;
        }
         ##############################################################
	# START BOX IN WRAP
	##############################################################
        function endBox($values){

            if ($values['column'] == "sidebar"){

                $outsource = "</div></div>";
            }else{
                if ($values['class'] == "")$values['class'] = "";
                $outsource = '</div>
                              </fieldset>';

                $outsource = '</div></div>';
            }
            return $outsource;
        }
	##############################################################
	# TITLE_INSIDE
	##############################################################
	function title_inside($values)
	{
		echo '<tr valign="top" '.$this->table_bg_class.'>';
		echo '<td colspan="2" scope="row"><h3>'.$values['name'].'</h3>';
		if (isset($values['desc'])) echo '<p>'.$values['desc'].'</p>';
		if ($values['id']) echo '<input type="hidden" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'"/>';
		echo '</td></tr>';
	}

	##############################################################
	# TEXTAREA
	##############################################################

        function textarea($values)
	{
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];

        $option .=  '<div class="item">';
		$option .=  "<label for='menu_style'>".$values['name']."</label>";
		$option .=  '<textarea name="'.$values['id'].'" cols="60" rows="7" id="'.$values['id'].'" style="width: 80%; font-size: 12px;" class="code">';
		$option .=  $values['std'].'</textarea>';
		$option .=  '</div>';

        return $option;
	}
     ##############################################################
	# ISWITCH
	##############################################################

	function colorPicker($values){

		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];




			$option .= "<script type='text/javascript'>
			var initLayout".$values['id']." = function() {

				jQuery('#colorSelector".$values['id']."').ColorPicker({
					color: '".$values['std']."',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#colorSelector".$values['id']." div').css('backgroundColor', '#' + hex);
                                                jQuery('#".$values['id']."').val('#' + hex);
					}
				});
			};

			EYE.register(initLayout".$values['id'].", 'init');
									</script>";

			$option .=  '<div class="item">';
			$option .=  "<label for='menu_style'>".$values['name']."</label>";
			$option .=  "<div id='colorSelector".$values['id']."' class='colorSelector'><div style='background-color: ".$values['std']."'></div></div>";
			$option .=  '&nbsp;&nbsp;<input type="text" style="width:100px" name="'.$values['id'].'" id="'.$values['id'].'" value="'.$values['std'].'"></div>';

		return $option;
	}
        ##############################################################
	# ISWITCH
	##############################################################

        function Iswitch($values)
	{
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];


                ( $values['std'] == 'true' )?$state = 'on' : $state = 'off';

				$option .=  '<div class="item">';
                $option .=  "<label for='menu_style'>".$values['name']."</label>";
				if ($values['desc'] != ""){
                	$option .=  "<div class='desc'><div id='s".$values['id']."' class='botonmove'></div><span>".$values['desc']."</span></div>";
				}else{
					$option .=  "<div class='item' id='s".$values['id']."'></div>";
				}
				$option .=  '</div>';

                $option .=  " <script type='text/javascript'>

                                jQuery('#s".$values['id']."').iphoneSwitch('".$state."',
                                 function() {
                                        jQuery('#".$values['id']."').val('true');
                                        ";
                                if($values["type2"]== "hide_option"){
                                      $option .=  "jQuery('#".$values['id']."_hide').css('display','block');";
                                }
               $option .=  "    },
                                  function() {
                                        jQuery('#".$values['id']."').val('false');";
                                if($values["type2"]== "hide_option"){
                                      $option .=  "jQuery('#".$values['id']."_hide').css('display','none');";
                                }
               $option .=  "    },

                                  {
                                    switch_on_container_path: '".MEENEWS_TPL_SOURCES_URI."iphone_switch_container_off.png',
                                    switch_off_container_path: '".MEENEWS_TPL_SOURCES_URI."iphone_switch_container_on.png',
                                    switch_path: '".MEENEWS_TPL_SOURCES_URI."iphone_switch.png',
                                  });
                              </script>";

		$option .=  '<input type="hidden" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'"/><br>';


                return $option;
	}

        ##############################################################
	# ISWITCH IMAGE
	##############################################################

        function IswitchImage($values)
	{
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];


                ( $values['std'] == 'true' )?$state = 'on' : $state = 'off';
                //$option .=  "<label for='menu_style'>".$values['name']."</label>";
                $option .=  "<div class='left' id='Im".$values['id']."'></div>";

                $option .=  " <script type='text/javascript'>

                                jQuery('#Im".$values['id']."').socialSwitch('".$state."','".$values['img']."',
                                 function() {
                                        jQuery('#".$values['id']."').val('true');
                                  },
                                  function() {
                                        jQuery('#".$values['id']."').val('false');
                                  },
                                  {
                                    switch_on_container_path: '".MEENEWS_TPL_SOURCES_URI."iphone_switch_container_off.png',
                                    switch_off_container_path: '".MEENEWS_TPL_SOURCES_URI."iphone_switch_container_on.png',
                                    switch_path: '".MEENEWS_TPL_SOURCES_URII."icons/48x48/',
                                  });
                              </script>";

		$option .=  '<input type="hidden" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'"/>';


                if ($values['finalswitch'] == 'true')$option .="<div style ='clear:both'></div>";
                return $option;
	}

	##############################################################
	# TEXT
	##############################################################
	function text($values)
	{
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];

				$option .=  '<div class="item">';
                $option .=  "<label for='menu_style'>".$values['name']."</label>";
				if ($values['desc'] != ""){
					$option .= '<div class="desc">
									<input type="text" size="'.$values['size'].'" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'" />
									<span>'.$values['desc'].'</span>
								</div>';
				}else{
					$option .=  '<input type="text" size="'.$values['size'].'" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'" />';
				}
				$option .=  '</div>';

                return $option;

	}

        function text_field($values)
	{
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];

                $option .=  '<div class="item">';
				$option .=  "<label for='menu_style'>".$values['name']."</label>";

                if ($values['std']){

                  $option .=  '<input type="text" DISABLED size="'.$values['size'].'" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'"/>';

                }
				
				$option .=  '</div>';

                return $option;

	}

        function image_conf($values)
        {
            if(isset($this->database_options['CImageSel'.$values['id']])) $values['std'] = $this->database_options['CImageSel'.$values['id']];
            if(isset($this->database_options['CImageWidth'.$values['id']])) $values['width'] = $this->database_options['CImageWidth'.$values['id']];
            if(isset($this->database_options['CImageHeight'.$values['id']])) $values['height'] = $this->database_options['CImageHeight'.$values['id']];

            $option .= "<div id='customImage'>";

            $option .= "<div class='typeImage'>";
            $option .= "<div class='cuadrado tipo1'>Panoramic</div>";
            $option .=  "<input type='radio' name='CImageSel".$values['id']."' id='CImageSel".$values['id']."' value='1' ";
            if ($values['std'] == "1") $option .= "checked=checked";
            $option .= " >";
            $option .= "</div>";

            $option .= "<div class='typeImage'>";
            $option .= "<div class='cuadrado tipo2'>Square</div>";
            $option .=  "<input type='radio' name='CImageSel".$values['id']."' id='CImageSel".$values['id']."' value='2' ";
            if ($values['std'] == "2") $option .= "checked=checked";
            $option .= " >";
            $option .= "</div>";

            $option .= "<div class='typeImage'>";
            $option .= "<div class='cuadrado tipo3'>Rectangle</div>";

            $option .=  "<input type='radio' name='CImageSel".$values['id']."' id='CImageSel".$values['id']."' value='3' ";
            if ($values['std'] == "3") $option .= "checked=checked";
            $option .= " >";
            $option .= "</div>";

            $option .= "<div class='typeImage'>";
            $option .= "<strong>Custom</strong>";
            $option .=  '<p>';
            $option .=  '<input type="text" size="6" DISABLED value="448" id="CImageWidth'.$values['id'].'" name="CImageWidth'.$values['id'].'" class ="inputWidth"/> Width';
            $option .=  '<input type="text" size="6" value="'.$values['height'].'" id="CImageHeight'.$values['id'].'" name="CImageHeight'.$values['id'].'" class ="inputHeight" /> Height';
            $option .=  '</p>';
            $option .=  "<input type='radio' name='CImageSel".$values['id']."' id='CImageSel".$values['id']."' value='4' ";
            if ($values['std'] == "4") $option .= "checked=checked";
            $option .= " >";
            $option .= "</div>";
            $option .= "</div>";


            return $option;
        }
		
	##############################################################
	# CHECKBOX
	##############################################################
	function checkbox($values)
	{
          //if(!isset($this->database_options[$values['id']]) && isset($values['std'])) $this->database_options[$values['std']] = $values['std'];

          $paso = true;
          $cats = get_categories('title_li=&orderby=name&hide_empty=0');
		  
		  $check .=  '<div class="item">';
          $check .=  "<label for='menu_style'>".$values['name']."</label>";
          $count = 0;
          $val = $this->database_options[$values['id']];
		  
		  $check .= '<div class="info">';

          foreach ($cats as $cat) :

			$title = $cat->cat_name;
			$id = $cat->cat_ID;

			if($val[$id] == true){

				$checked = 'checked = "checked"';
			}

			$check .= '<input class="kcheck" type="checkbox" value = "'.$id.'" name="'.$values['id'].'['.$id.']" id="'.$values['id'].'['.$id.']"   '.$checked.' />&nbsp;'.$title.'<br />';
			$paso = false;
			$checked = '';
					
          endforeach;
		  
		  $check .= '</div>';
		  
		  $check .= '</div>';
		  
          return $check;

	}

        ##############################################################
	# CHECK IMAGE
	##############################################################
	function checkimage($values)
	{
		if(isset($this->database_options[$values['id']]) && $this->database_options[$values['id']] == true) $checked = 'checked = "checked"';

                echo '<tr valign="top" '.$this->table_bg_class.'>';
		echo '<th scope="row" width="200px">'.$values['name'].'</th>';
		echo '<td><input class="kcheck" type="checkbox" name="'.$values['id'].'" id="'.$values['id'].'" value="true"  '.$checked.' />';
		echo '<label for="'.$values['id'].'">'.$values['desc'].'</label><br/>';
	    echo '<br/></td>';
		echo '</tr>';
	}



	##############################################################
	# IMAGE
	##############################################################

        function image($values){
            if(!isset($this->database_options[$values['id']]) && isset($values['std'])) $this->database_options[$values['id']] = $values['std'];

            $options .=  "<label for='menu_style'>".$values['name']."</label>";
            //$options =  '<div style="float:right;width:80px; height:80px;overflow:hidden;border:1px solid silver"><img id="img_result_'.$option['id'].'" src="'.$values['std'].'"></div>';

            $options .= '<input class="upload_image" id="upload_image'.$values['id'].'" type="text" size="36" name="'.$values['id'].'" value="'.$this->database_options[$values['id']].'" />
                         <input id="'.$values['id'].'" class="upload_image_button" type="button" value="Upload Image" /><br><br>
                    ';

           //$options .= "</div>";

           return $options;
        }


	##############################################################
	# DROPDOWN
	##############################################################

        function dropdown($values)
	{
		if(!isset($this->database_options[$values['id']]) && isset($values['std'])) $this->database_options[$values['id']] = $values['std'];

            $options .= '<div class="item">';
			$options .=  "<label for='menu_style'>".$values['name']."</label>";

			if($values['subtype'] == 'page')
			{
				$select = 'Select page';
				$entries = get_pages('title_li=&orderby=name');
			}
			else if($values['subtype'] == 'cat')
			{
				$select = 'Select category';
				$entries = get_categories('title_li=&orderby=name&hide_empty=0');
			}
			else
			{
				$select = 'Select...';
				$entries = $values['subtype'];
			}

			$options .='<select class="postform" id="'. $values['id'] .'" name="'. $values['id'] .'"> ';
			$options .= '<option value="">'.$select .'</option>  ';

			foreach ($entries as $key => $entry)
			{
				if($values['subtype'] == 'page')
				{
					$id = $entry->ID;
					$title = $entry->post_title;
				}
				else if($values['subtype'] == 'cat')
				{
					$id = $entry->term_id;
					$title = $entry->name;
				}
				else
				{
					$id = $entry;
					$title = $key;
				}

				if ($this->database_options[$values['id']] == $id )
				{
					$selected = "selected='selected'";
				}
				else
				{
					$selected = "";
				}

				$options .= "<option $selected value='". $id."'>". $title."</option>";
			}

			$options .= '</select>';
			
			$options .= '</div>';

			return $options;
        }

 function dropImages($values){

            if(!isset($this->database_options[$values['id']]) && isset($values['std'])) $this->database_options[$values['id']] = $values['std'];

	    $entries = array("none", "1.png","2.png","3.png","4.png","5.png","6.png","7.png","8.png");

            $options .= "
            <p> <label>".$values['name']."</label><br><ul style='overflow:hidden;'>";

            foreach ($entries as $key => $entry){
                    $options .= "<li style='float:left; overflow:hidden; text-align:center;'>";
                      if ($entry != 'none'){
                          $options .= "<img src='".MEE_URI."img/icons/".$entry."' rel='".$entry."' width='30' height='30' class='selectImage'>";
                      }else{
                          $options .= "none<br>";
                      }

                      $options .= "<br>

                       <input type='radio' name='".$values['id']."' id='".$values['id']."' value='".$entry."' ";
                       if ($values['std'] == $entry) $options .= "checked=checked";
                     $options .= " >
                      </li>
                     ";
                   // $options .= "<li ><img src='".MEE_URI."img/icons/".$entry."' rel='".$entry."' width='20' height='20' style='float:left' class='selectImage'></li>";
            }

            $options .="</ul></p>";

            return $options;
        }
         function template($values){
			global $meenews_datas;
              if(!isset($this->database_options[$values['id']]) && isset($values['std'])) $this->database_options[$values['id']] = $values['std'];
              
            $themes =$this->get_themes_newsletters();
            $theme_names = array_keys($themes);
            $content = '<ul class="templates">';
        foreach ( (array) $theme_names as $theme_name ) {
            $imagen = MEENEWS_URI."templates/".$themes[$theme_name]['Screenshot'];
			$imagen2 = MEENEWS_URI."templates/".$theme_name."/screenshot2.jpg";
            $content .= '<li><a href="javascript:changeTheme(\''.$theme_name.'\')" rel="'.$imagen2.'" title="'.$theme_name.'" class="preview"><img src="'.$imagen.'"></a></li>';
        }

        $content .='</ul>';
        
        $content .="<script type='text/javascript'>";
			$content .= "function changeTheme(id){	
				jQuery('#".$values['id']."').val(id);	
			}";
			
			$content .= 'jQuery(".templates li img").click(function() {
				jQuery(".templates li img").removeClass("active");
				jQuery(this).addClass("active");
			});';
					 
			$content .= 'this.imagePreview = function(){	
		 
			xOffset = 10;
			yOffset = 30;
		 
			jQuery("a.preview").hover(function(e){
				this.t = this.title;
				this.title = "";	
				var c = (this.t != "") ? "<br/>" + this.t : "";
				jQuery("body").append("<p id=\'preview\'><img src=\'"+ this.rel +"\' alt=\'\' />"+ c +"</p>");								 
				jQuery("#preview")
					.css("top",(e.pageY - xOffset) + "px")
					.css("left",(e.pageX + yOffset) + "px")
					.fadeIn("fast");						
			},
			function(){
				this.title = this.t;	
				jQuery("#preview").remove();
			});	
			jQuery("a.preview").mousemove(function(e){
				jQuery("#preview")
					.css("top",(e.pageY - xOffset) + "px")
					.css("left",(e.pageX + yOffset) + "px");
			});			
			};
			 
			jQuery(document).ready(function(){
				imagePreview();
			});';
						 
			$content .="</script>";
			
     	return $content;
        }


        function get_themes_newsletters() {

	$themes = array();
	$wp_broken_themes = array();
	$theme_loc = $theme_root = MEENEWS."templates/";
	// Files in wp-content/themes directory and one subdir down
        if ( '/' != WP_CONTENT_DIR ) // don't want to replace all forward slashes, see Trac #4541
		$theme_loc = str_replace(WP_CONTENT_DIR, '', $theme_root);

	$themes_dir = @ opendir($theme_root);
	if ( !$themes_dir )
		return false;

	while ( ($theme_dir = readdir($themes_dir)) !== false ) {
		if ( is_dir($theme_root . '/' . $theme_dir) && is_readable($theme_root . '/' . $theme_dir) ) {
			if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
				continue;
			$stylish_dir = @ opendir($theme_root . '/' . $theme_dir);
			$found_stylesheet = false;
			while ( ($theme_file = readdir($stylish_dir)) !== false ) {
				if ( $theme_file == 'template.html' ) {
					$theme_files[] = $theme_dir . '/' . $theme_file;
                    $found_stylesheet = true;
					break;
				}
			}
			@closedir($stylish_dir);
			if ( !$found_stylesheet ) { // look for themes in that dir
				$subdir = "$theme_root/$theme_dir";
				$subdir_name = $theme_dir;
				$theme_subdir = @ opendir( $subdir );
				while ( ($theme_dir = readdir($theme_subdir)) !== false ) {

					if ( is_dir( $subdir . '/' . $theme_dir) && is_readable($subdir . '/' . $theme_dir) ) {
						if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
							continue;
						$stylish_dir = @ opendir($subdir . '/' . $theme_dir);
						$found_stylesheet = false;
						while ( ($theme_file = readdir($stylish_dir)) !== false ) {
							if ( $theme_file == 'template.html' ) {
								$theme_files[] = $subdir_name . '/' . $theme_dir . '/' . $theme_file;
								$found_stylesheet = true;
								break;
							}
						}
						@closedir($stylish_dir);
					}
				}
				@closedir($theme_subdir);
				$wp_broken_themes[$theme_dir] = array('Name' => $theme_dir, 'Title' => $theme_dir, 'Description' => __('Template not found.','meenews'));
			}
		}
	}
	if ( is_dir( $theme_dir ) )
		@closedir( $theme_dir );

	if ( !$themes_dir || !$theme_files )
		return $themes;

	sort($theme_files);

	foreach ( (array) $theme_files as $theme_file ) {
		if ( !is_readable("$theme_root/$theme_file") ) {
			$wp_broken_themes[$theme_file] = array('Name' => $theme_file, 'Title' => $theme_file, 'Description' => __('File not readable.','meenews'));
			continue;

		}

		$theme_data = get_theme_data("$theme_root/$theme_file");

		$name        = $theme_data['Name'];
		$title       = $theme_data['Title'];
		$description = wptexturize($theme_data['Description']);
		$version     = $theme_data['Version'];
		$author      = $theme_data['Author'];
		$template    = $theme_data['Template'];
		$stylesheet  = dirname($theme_file);
		$screenshot = false;
		foreach ( array('png', 'gif', 'jpg', 'jpeg') as $ext ) {
			if (file_exists("$theme_root/$stylesheet/screenshot.$ext")) {
				$screenshot = "screenshot.$ext";
				break;
			}
		}
        $screenshot = "$stylesheet/".$screenshot;

		if ( empty($name) ) {
			$name = dirname($theme_file);
			$title = $name;
		}

		if ( empty($template) ) {
			if ( file_exists(dirname("$theme_root/$theme_file/index.php")) )
				$template = dirname($theme_file);
			else
				continue;
		}

		$template = trim($template);

		// Check for theme name collision.  This occurs if a theme is copied to
		// a new theme directory and the theme header is not updated.  Whichever
		// theme is first keeps the name.  Subsequent themes get a suffix applied.
		// The Default and Classic themes always trump their pretenders.
		if ( isset($themes[$name]) ) {
			if ( ('WordPress Default' == $name || 'WordPress Classic' == $name) &&
					 ('default' == $stylesheet || 'classic' == $stylesheet) ) {
				// If another theme has claimed to be one of our default themes, move
				// them aside.
				$suffix = $themes[$name]['Stylesheet'];
				$new_name = "$name/$suffix";
				$themes[$new_name] = $themes[$name];
				$themes[$new_name]['Name'] = $new_name;
			} else {
				$name = "$name/$stylesheet";
			}
		}

		$themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template, 'Stylesheet' => $stylesheet, 'Template Files' => $theme_file, 'Stylesheet Files' => $stylesheet_files, 'Template Dir' => $template_dir, 'Stylesheet Dir' => $stylesheet_dir, 'Status' => $theme_data['Status'], 'Screenshot' => $screenshot, 'Tags' => $theme_data['Tags']);


    }

	// Resolve theme dependencies.
	$theme_names = array_keys($themes);

	foreach ( (array) $theme_names as $theme_name ) {
        $themes[$theme_name]['Parent Theme'] = '';
		if ( $themes[$theme_name]['Stylesheet'] != $themes[$theme_name]['Template'] ) {
			foreach ( (array) $theme_names as $parent_theme_name ) {
				if ( ($themes[$parent_theme_name]['Stylesheet'] == $themes[$parent_theme_name]['Template']) && ($themes[$parent_theme_name]['Template'] == $themes[$theme_name]['Template']) ) {
					$themes[$theme_name]['Parent Theme'] = $themes[$parent_theme_name]['Name'];
					break;
				}
			}
		}
	}

	$wp_themes = $themes;
	return $themes;
        }
##################################################################
} # end class
##################################################################
