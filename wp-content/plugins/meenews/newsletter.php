<?php
require_once('../../../wp-config.php');
global $meenews_datas;

//limpiamos el array $_POST
$_GET=LimpiarArray($_GET);

$del = $_GET["del"];
$add = $_GET["add"];
$delConf = $_POST['delconf'];
$idnews = $_GET["news"];
$content = "";
$content .= "<h1>" . get_bloginfo("name") . " - Newsletter</h1>\n";

if($del != ""){
        $members = MeeUsers::isConfirmation($del);
	if( $members){
		if($delConf != ""){
			if($delConf == __("Yes", 'meenews')){
                                 MeeUsers::removeSusbscriptorList($members->id);
                                 $sender = new MeeSender();
				 
                                 $member['email'] = $members->email;
                                 $send = array("member" => $member,"message"=>"deleted");
                                 $sender->sendMessage($send);
				 $content .= "<div class='success'>".__("The e-mail ", 'meenews')."<b>".$members->email."</b>". __("has been deleted correctly", 'meenews')." </div>\n";
			}else{
				$content .= "<div class='success'>".__("Operation cancel", 'meenews')."</div>\n";
			}
		}else{

			$content .= "<h2>".__("Remove subscription confirmation ", 'meenews')."</h2>\n";
			$content .= "<div style='padding:10px;'><p>".__("Press yes  if you wish to delete your subscription to ", 'meenews')." \"" . get_bloginfo("name") . "\"". __(" Newsletter", 'meenews')."?</p>\n";
			$content .= "<form action=\"\" method=\"post\">\n";
			$content .= "	<input class=\"button\" type=\"submit\" name=\"delconf\" value='".__("Yes", 'meenews')."'/>\n";
			$content .= "	<input class=\"button\" type=\"submit\" name=\"delconf\" value='".__("No", 'meenews')."'/>\n";
			$content .= "</form></div>\n";
		}
	}else{
		$content .= "<div class=\"errorTitle\">".__("Confirmation number is wrong", 'meenews')."</div>\n";
		$content .= "<p>".__("Be sure that you pressed on the mail link ", 'meenews')."</p>\n";
	}
        showHtmlContent($content);
}elseif($add != ""){

	$member_data =  MeeUsers::isConfirmation($add);

	if( $member_data){
		Meeusers::activateSubscriptor($member_data->id);
		$content .= "<div class=\"success\">".__("The e-mail ", 'meenews')." <b>".$member_data->email."</b>". __(" has been activated correctly", 'meenews')."</div>\n";
                $sender = new MeeSender();
                $member['email'] = $member_data->email;
                $send = array("member" => $member,"message"=>"actived");
                $sender->sendMessage($send);

	}else{
		$content .= "<div class=\"errorTitle\">".__("Confirmation number is wrong", 'meenews')."</div>\n";
		$content .= "<p>".__("Be sure that you pressed on the mail link", 'meenews')."</p>\n";
	}

        showHtmlContent($content);
}else if($_GET['show']!=""){
                $newsletter = MeeNewsletter::extractNewsletter($_GET['show']);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                <title><?php echo $newsletter->title; ?></title>
                <?php echo $style; ?>
                </head>

                <body>
<?php
                echo $newsletter->newsletter;
                
}



  function showHtmlContent($content){
                  global $meenews_datas;

                  $headImage =         $meenews_datas['newsletter']['default_header'];
                  $colorH1 =           $meenews_datas['newsletter']['color_title'];
                  $colorTexto =        $meenews_datas['newsletter']['color_post'];
                  $colorSpecial =      $meenews_datas['newsletter']['color_post'];
                  $colorLink =         $meenews_datas['newsletter']['color_link'];
                  $sizeH1 =            $meenews_datas['newsletter']['size_title'];
                  $sizeTexto =         $meenews_datas['newsletter']['size_post'];
                  $sizeSpecial =       $meenews_datas['newsletter']['size_other'];
                  $sizeLink =          $meenews_datas['newsletter']['size_link'];
                  $colorBackground =   $meenews_datas['newsletter']['color_background'];
                  $colorBody =         $meenews_datas['newsletter']['color_body'];
                  $colorheadfoot =     $meenews_datas['newsletter']['color_header'];

                   $style = "<style type='text/css'>
                                    .newsletter .separador, .separador{width:100%; clear:both; border-bottom:$sizeSeparator dotted $colorSeparator;height:2px;margin:5px 0 5px 0;}
                                    body{font-family: arial; font-size:$sizeTexto; text-align:justify; background-color:$colorBody;color:$colorTexto;}
                                    table.newsletter a{color:#$colorLink;text-decoration:none;font-size:$sizeLink; }
                                    table.newsletter h1{font-family: arial;clear:both; color:#$colorH1; font-size:$sizeH1; padding:0px; font-weight:bold;margin-bottom:8px;}
                                    table.newsletter {table-layout:fixed;background-color:$colorBackground}
                                    table.newsletter ul, .listanews ul{margin-left:25px; font-style:italic; text-align:left;color:$colorTexto;}
                                    table.newsletter p {font-family:arial; font-size:$sizeTexto; text-align:justify; width:auto; color:$colorTexto}
                                    table.newsletter a {font-size: $sizeLink; color:$colorLink; text-decoration:none; font-weight:bold}
                                    .footernews, .headernews, .listanews{background-color:#$colorH1; color:$colorBackground}
                                    .headernews {font-size:17px; width:100%; height:30x; text-align: center}
                                    .principal{margin:0 auto}
                                    </style>
                                    ";
                 $estilos = "style='table-layout:fixed;background-color:$colorBackground;margin:0 auto'";
                ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                <title>Newsletter</title>
                <?php echo $style; ?>
                </head>

                <body>
                <table width="600" border="0" cellpadding="0" cellspacing="0" <?php echo $estilos; ?> class="newsletter">
                  <tr>
                    <td colspan="2"><img src='<?php echo $headImage; ?>'></td>
                  </tr>
                  <tr>
                    <td colspan="2"><div style='background-color:#<?php echo $colorH1; ?>; color:<?php echo $colorBackground; ?>;width:100%; height:16px; text-align: center;padding-top:12px'><?php bloginfo('home'); ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="padding:15px"><br><br><?php echo $content; ?><br><br></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="padding:15px"><a href="<?php bloginfo('home'); ?>"
                                                                title="<?php bloginfo('name'); ?>">&laquo; <?php echo get_bloginfo("name"); ?></a></td>
                  </tr>
                </table>
                </body>
                </html>
                <?php
      }