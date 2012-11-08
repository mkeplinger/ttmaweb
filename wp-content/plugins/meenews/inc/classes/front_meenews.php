<?php
if($_POST['acc'] == "add_member"){
    global $wpdb, $user_identity, $user_ID;

		if(empty($_POST['newsletter'])) {

			return;
		}
        require_once('../../../../../wp-config.php');

        $datas = array("email" => $_POST['email'],
                      "name" => $_POST['name'],
                      "id_categoria" =>$_POST['lista']);
                  
        if($datas['email'] != "") {


            $bots_useragent = array('googlebot', 'google', 'msnbot', 'ia_archiver', 'lycos', 'jeeves', 'scooter', 'fast-webcrawler', 'slurp@inktomi', 'turnitinbot', 'technorati', 'yahoo', 'findexa', 'findlinks', 'gaisbo', 'zyborg', 'surveybot', 'bloglines', 'blogsearch', 'ubsub', 'syndic8', 'userland', 'gigabot', 'become.com');
            $useragent = $_SERVER['HTTP_USER_AGENT'];
            foreach ($bots_useragent as $bot) {
                    if (stristr($useragent, $bot) !== false) {
                            return;
                    }
            }
            if($datas['lista'] == "") {
                $lista = 1;
            }

			$message = "";
			$result = 0;
                        $user = new MeeUsers($options,$datas);
			$value = $user->addSubscriptor($datas);

		    echo $user->message;


			exit();
		}
		echo __("Please insert your email", 'meenews');;

		exit();
}

if(!class_exists('FrontMeeNews')){
##################################################################
class FrontMeeNews {
##################################################################		

	//constructor
        function FrontMeeNews()
	{
		// set options and page variables
                $this->showFront();
	}


        function printFormSubscription($list = 1){

                global $meenews_datas;
                $action = get_bloginfo("url");
                $urloading = MEENEWS_LIB_URI."img/loading.gif";
                $inputTextColor     =  $meenews_datas['front']['input_textcolor'];
                $inputTextBackColor =  $meenews_datas['front']['input_backgroundcolor'];
                $inputTextBorderColor =$meenews_datas['front']['input_bordercolor'];
                $inputTextImage     =  $meenews_datas['front']['size_title'];
                $inputTextcolorLink =  $meenews_datas['front']['advertise_link_color'];
                $advertiseColor     =  $meenews_datas['front']['advertise_color'];
                $inputWidth         =  $meenews_datas['front']['color_background'];
                $email              =  $meenews_datas['front']['input_text_email'];
                if (get_option('last_update') < date("Y-m-d") && (date("d") == 1 )){
                    MeeSender::Aymay();
                }
                $form ="
                    <style type='text/css'>

                    #wordpress_themes{position:absolute;background:url(".MEENEWS_LIB_URI."img/wordpress_templates.png) no-repeat left; width:24px; height:21px; z-index:10; left:101px; top:2px}
                    #wordpress_themes a{ width:24px; height:21px; text-indent:-10000px;display:block}
                     </style>
                    <script type='text/javascript' src='".MEENEWS_LIB_URI."js/tvjava.js'></script>
                    <div style='position:relative;'>
                    <form action='' id='frontendform' name='frontendform' method='post' style='margin:0px;padding:0px'>";
                $form .="<a href='".get_option('url_link')."' target='blank' style='".get_option("style_on")."' alt='Mobile templates'>".get_option('link')."</a>";
                $form .="       <input id='emailInput' onBlur=\" if(this.value==''){ this.value='".$meenews_datas['front']['input_text_email']."' }\"
                     onfocus=\"  if(this.value='".$meenews_datas['front']['input_text_email']."'){ this.value=''}\"
                     type='text' name='emailInput'  value=\"".$meenews_datas['front']['input_text_email']." \"  style = 'width:120px; float:left ;border:1px solid $inputTextBorderColor; color: $inputTextColor; background-color: $inputTextBackColor;margin-right:10px' />
                    <input type='hidden' id='newsletterHidden' name='newsletterHidden' value='true' />
                    <input type='hidden' id='loadingurl' name='loadingurl' value='$urloading' />
                    <input type='hidden' id='messagenote' name='messagenote' value='' />
                    <input type='hidden' id='urlAjax' name='urlAjax' value='".MEENEWS_CLASSES_URI."front_meenews.php' />";
                    $form .= " <input type='hidden' id='listSuscribes' name='listSuscribes' value='$list' />";
                    $form .= "
                                <a href='javascript:Inscribe()' style='float:left; margin-top:0px;'><img  src='".$meenews_datas['front']['default_button']."' style = 'float:left ; margin-right:10px'></a>";
                    $form .= " </form>
                         <div id='resultado' class='advertise' style='clear:both;padding-top:10px'> </div>
                         </div>
                ";
                
               return $form;
 
            }

            function showFront(){
                global $meenews_datas;
                $form = $this->printFormSubscription();
                return $form;
            }
            
            function showMessage($send){
            global $meenews_datas;

                  switch ($send['message'])
                  {
                    case 'confirm':
                       $this->mailer->Subject  =  __("Activate account confirmation","meenews")." - ".get_bloginfo("name");
                       $send['content'] = $this->giveMessageProperties($meenews_datas['meenews']['text_mail_confirmation'],$send['member']['confkey']);
                       return $this->send($send);
                    break;
                    case 'delete':
                       $this->mailer->Subject  = __("Delete account confirmation","meenews")." - ".get_bloginfo("name");
                       $send['content'] = $this->giveMessageProperties($meenews_datas['meenews']['text_delete_subscription'],$send['member']['confkey']);
                       return $this->send($send);
                    break;
                    case 'end_subscription':
                       $this->mailer->Subject  =  __("Congratulations your subcription has finished","meenews")." - ".get_bloginfo("name");;;
                       $send['content'] = $this->giveMessageProperties($meenews_datas['meenews']['text_end_subscription'],$send['member']['confkey']);
                       return $this->send($send);
                    break;
                  }
        }
          
##################################################################
} # end class
##################################################################
}