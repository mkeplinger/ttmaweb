<?php session_start();
/*
Plugin Name: PXS Mail Form
Plugin URI: http://www.phrixus.co.uk/pxsmail/
Description: Creates a mail form with multi part verification, various messages and an auto redirect on successful send. Originally based on the contact form by Ryan Duff. Enter <strong>{mailform}</strong> where you want the form. Recent updates include referrer checks, exploit management and the ability to run with the Markdown plugin. New feature for this release allows the sender to CC themselves.
Author: Shane Marriott
Author URI: http://www.phrixus.co.uk
Version: 2.6
*/ 

/*This function embeds the Contact Form submenu under the Options tab.*/

function pxs_admin_menu() {
    if (function_exists('add_options_page')) {
add_options_page('options-general.php', 'PXS-Mail', 8, basename(__FILE__), 'pxs_options_subpanel');
    }
 }

function ValidateEmail($e,$v=-1) {
    global $verbose;
    /*
    Return codes:
    0: appears to be a valid email
    1: didn't match pattern of a valid email
    */
    if ($v==-1) { $v=$verbose; }
    if (!preg_match("/^[a-z0-9.+-_]+@([a-z0-9-]+(.[a-z0-9-]+)+)$/i", $e, $grab)) {
        return 1;
    }
    return 0;
}
 // Function written to create arrays for the recipient information if more than one is required
function pxs_multimail($info){
    $testlist = explode(";", $info);
    foreach ($testlist as $index=>$items) {
    $testlist[$index] = explode (",", $items);
        }
	return $testlist;
}

/*Wrapper function which calls the form.*/
function pxs_callback( $content )
{
	$div_error = '<p>&nbsp;</p>';
    $secure = 0;
    $check = 0;
    
    $pxs_mmt = get_option('pxs_email');
    if (strpos($pxs_mmt, ";") > 0) {
        $pxs_mm = 1;
        $listing = pxs_multimail($pxs_mmt);
     }
    // This section sets the subject for the message based on options and information set in the form
    
    if (get_option('pxs_user_subject') != 1){
            $subject = get_option('pxs_subject');
            $show_subject = 0;
            } else {
            $show_subject = 1;
            $subject = '';
            if (empty($_POST['your_subject'])) {
            $subject = get_option('pxs_subject');
            $subject = stripslashes($subject);
            $subject_mm = '';
            } else {
            $subject = $_POST['your_subject'];
            $subject = stripslashes($subject);
            $subject_mm =  ': '.$subject;
            }
           }

    if(!(empty($_POST['email']))){
        $pxs_mail = ValidateEmail($_POST['email'],$v=-1);   
    }

    if(!(empty($_POST['your_name']) || empty($_POST['email']) || empty($_POST['msg']) || empty($_POST['pxscheck'])) && $pxs_mail == 0) {
    
        $pxs_redirect = get_option('pxs_redirect_loc');
        $pxs_redirect = stripslashes($pxs_redirect);
        $pxs_time = get_option('pxs_redirect_time');

        if ($pxs_redirect){ 
            $success_redirect = '<meta http-equiv="refresh" content="'.$pxs_time.';URL='.$pxs_redirect.'">';
            $hack_redirect = '<meta http-equiv="refresh" content="0;URL='.get_option('siteurl').'">';
        }
        if ($_POST['pxscheck'] !== $_SESSION["pxscheck"]) {
			echo $hack_redirect; 
            exit();
        }

    
    if ($pxs_mm == 1) {
            $pxs_mmr = $_POST['recipient'];
            $recipient = $listing[$pxs_mmr]['0'];
            if ($recipient == 'all') {
                $bodyCount = count($listing)-2;
                $bodyStart = 0;
                $recipient = '';
                do {
                    $recipient .= $listing[$bodyStart]['0'] . ','; 
                    $bodyStart = $bodyStart + 1;
                } while ($bodyCount >= $bodyStart);
            }

            $subject = $listing[$pxs_mmr]['2'].$subject_mm;
            } else {
            $recipient = get_option('pxs_email');
            }


        
        $success_msg = get_option('pxs_success_msg');
        $success_msg = stripslashes($success_msg);
        
         // Hack prevention code: Stop spammers exploiting the plugin. Will try to kill the script if it detects an attack but will also safely proceed if nothing is actually detected by stripping out what it ought to have found earlier!!.
            
        if (preg_match("/(\r|\n)/", $_POST['your_name'])!==0) {
            echo $hack_redirect; 
            exit();
        }
        $name = stripslashes($_POST['your_name']); 
		
        if (preg_match("/(\r|\n)/", $_POST['email'])!==0){
            echo $hack_redirect; 
            exit();
        }
        $email = stripslashes($_POST['email']); 
        
        if (preg_match("/(\r|\n)/", $_POST['blog'])!==0){
            echo $hack_redirect; 
            exit();
        }
        $blog = $_POST['blog']; 

        if (preg_match("/(\r|\n)/", $_POST['your_subject'])!==0) {
            echo $hack_redirect; 
            exit();
        }
        $blog_chars = get_option('blog_charset');

        $headers = '';
        $headers = "From: $name <$email>\n";  // your email client will show the person's email address like normal
        $headers .= "Content-Type: text/plain; charset=$blog_chars\n"; // sets the mime type
    
        $fullmsg = "$name wrote:\n";
        $fullmsg .= $_POST['msg'] . "\n\n";
        $fullmsg .= "Website: " . $blog . "\n";
        $fullmsg .= "IP: " . getip();
    
        mail($recipient, $subject, stripslashes($fullmsg), $headers);
        if ($_POST['ccme'] == 1){
            mail($email, $subject, stripslashes($fullmsg), $headers);
        }
        $results = '<div style="font-weight: bold;">' . $success_msg . '</div><br />' . $success_redirect;
        echo $results;
		session_destroy();
    }
    else
    {
		
        if ('process' == $_POST['stage']) {
			$_SESSION["pxscheck"] = md5(rand());
            $pxs_m_error = '';
            if($pxs_mail == 1) {$pxs_m_error_1 = '.y_email {border: 1px solid #FF0000;color: #FF0000;}';
                                $error_msg_2 = get_option('pxs_error_msg_2');
                                $error_msg_2 = stripslashes($error_msg_2);}

            if ($pxs_mail == 2) {$pxs_m_error_1 = '.y_email {border: 1px solid #FF0000;}';
                                $error_msg_2 = get_option('pxs_error_msg_3');
                                $error_msg_2 = stripslashes($error_msg_2);}
                                                
            if(empty($_POST['your_name']) || empty($_POST['email']) || empty($_POST['msg'])) {
                                $error_msg = get_option('pxs_error_msg');
                                $error_msg = stripslashes($error_msg);}

            if(empty($_POST['your_name'])) {$pxs_m_error_2 = '.y_name {border: 1px solid #FF0000;}';}
            if(empty($_POST['email'])) {$pxs_m_error_3 = '.y_email {border: 1px solid #FF0000;}';}
            if(empty($_POST['msg'])) {$pxs_m_error_4 = '.y_msg {border: 1px solid #FF0000;}';}
            if($pxs_m_error_1 && $pxs_m_error_3){$pxs_error_1 = '';}
            $div_error = '<style type="text/css">.y_error {border: 1px solid #FF0000;}' .$pxs_m_error_1 . $pxs_m_error_2 . $pxs_m_error_3 . $pxs_m_error_4 . '</style><div style="font-weight: bold;">' . $error_msg . '<br />' . $error_msg_2 . '<br /></div>';
               
        }
		
        if ($pxs_mm == 1){
        $select = '<dt>Send to: </dt><dd><select name="recipient" size="1">';
        $body = $listing;
        $bodyCount = count($listing)-1;
        $bodyStart = 0;
        do {
        $select .= '<option value="' . $bodyStart . '">' . $body[$bodyStart]['1'] . '</option>'; 
        $bodyStart = $bodyStart + 1;
        } while ($bodyCount >= $bodyStart);
        $select .= '</select></dd>';
		$select = stripslashes($select);
        } else {
            $select = '';
        }
		
        if (get_option('pxs_cc_myself') == 1) {
        $pxs_ccme = '&nbsp;&nbsp;CC Yourself <input type="checkbox" name="ccme" value="1" />';
    } else {
        $pxs_ccme = '';
    }
        if ($show_subject == 1){
        $subject_box = '<dt>Subject:</dt><dd><input type="text" name="your_subject" size="30" maxlength="50" value="' . $subject . '" /></dd>';
        } else {
        $subject_box = '';
        }
		if (!isset($_SESSION["pxscheck"])) {
   $_SESSION["pxscheck"] = md5(rand());
	}
        $form = '<div>
        ' . $div_error . '
        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" name="form"><dl>
        '. $select .'
        <dt>Your Name:</dt>
        <dd><input class="y_name" type="text" name="your_name" size="30" maxlength="50" value="' . $_POST['your_name'] . '" /> (required)</dd>
        <dt>Your Email: </dt>
        <dd><input class="y_email" type="text" name="email" size="30" maxlength="50" value="' . $_POST['email'] . '" /> (required)</dd>
        <dt>Your Website: </dt>
        <dd><input type="text" name="blog" size="30" maxlength="100" value="' . $_POST['blog'] . '" /></dd>
        '. $subject_box .'
        <dt>Your Message: </dt>
        <dd><textarea class="y_msg" name="msg" cols="35" rows="8" >' . $_POST['msg'] . '</textarea></dd>
        <dd>
        <input type="submit" name="Submit" value="Submit" />'.$pxs_ccme.'
        <input type="hidden" name="stage" value="process" />
        <input type="hidden" name="pxscheck" value="'. $_SESSION["pxscheck"] .'" />
        </dd></dl>
        </form>
        </div>
        <div style="clear:both; height:1px;">&nbsp;</div>';
        return preg_replace('|{mailform}|', $form, $content);
   }
  
}

/*Can't use WP's function here, so lets use our own*/
function getip()
{
    if (isset($_SERVER)) 
    {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) 
        {
            $ip_addr = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } 
        elseif (isset($_SERVER["HTTP_CLIENT_IP"])) 
        {
            $ip_addr = $_SERVER["HTTP_CLIENT_IP"];
        } 
        else 
        {       
            $ip_addr = $_SERVER["REMOTE_ADDR"];
        }
    } 
    else 
    {
        if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) 
        {
            $ip_addr = getenv( 'HTTP_X_FORWARDED_FOR' );
        } 
        elseif ( getenv( 'HTTP_CLIENT_IP' ) ) 
        {
            $ip_addr = getenv( 'HTTP_CLIENT_IP' );
        } 
        else 
        {
            $ip_addr = getenv( 'REMOTE_ADDR' );
        }
    }
return $ip_addr;
}


/*CSS Styling*/
function pxs_css()
    {
            ?>
<style type="text/css" media="screen">
	
    form dl, dl.labels {
      margin:0em;
      font-size:100%;
      margin-top:0.5em
    }
    form dt, dl.labels dt {
      float:left;
      
      width:10em;
      margin-bottom:0.8em;
      color:#555;
      text-align:right;
      font-weight:normal;
      position:relative /*IEWin hack*/
    }
    form dd, dl.labels dd {
      margin-left:10.5em;
      margin-bottom:0.8em;
      font-size:100%;
      font-style:normal;
      padding-left:0.5em
    }
    form dd.submit, dl.labels dd.submit  {
      margin-top:2em
    }
  </style>
<?php

}

function pxs_options_subpanel() {

/*Lets add some default options if they don't exist*/
add_option('pxs_email', get_option('admin_email'));
add_option('pxs_subject', 'From Website');
add_option('pxs_success_msg', 'Thanks, your email has been sent!');
add_option('pxs_error_msg', 'Please fill in the required fields');
add_option('pxs_error_msg_2', 'You have not entered a valid email address');
add_option('pxs_error_msg_3', 'That destination does not appear to exist');
add_option('pxs_redirect_loc', get_option('siteurl'));
add_option('pxs_redirect_time', '3');
add_option('pxs_css_inject', '1');
add_option('pxs_cc_myself', '1');
add_option('pxs_user_subject', '1');

/*check form submission and update options*/
if (isset($_POST['stage']) && ('process' == $_POST['stage']) && (!empty($_POST['pxs_email'])) && (!empty($_POST['pxs_subject'])) && (!empty($_POST['pxs_success_msg'])) && (!empty($_POST['pxs_error_msg'])))
{
$pxs_email = $_POST['pxs_email'];
$pxs_subject = $_POST['pxs_subject'];
$pxs_success_msg = $_POST['pxs_success_msg'];
$pxs_error_msg = $_POST['pxs_error_msg'];
$pxs_error_msg_2 = $_POST['pxs_error_msg_2'];
$pxs_error_msg_3 = $_POST['pxs_error_msg_3'];
$pxs_redirect_loc = $_POST['pxs_redirect_loc'];
$pxs_redirect_time = $_POST['pxs_redirect_time'];
$pxs_css_inject = $_POST['pxs_css_inject'];
$pxs_cc_myself = $_POST['pxs_cc_myself'];
$pxs_user_subject = $_POST['pxs_user_subject'];

update_option('pxs_email', $pxs_email);
update_option('pxs_subject', $pxs_subject);
update_option('pxs_success_msg', $pxs_success_msg);
update_option('pxs_error_msg', $pxs_error_msg);
update_option('pxs_error_msg_2', $pxs_error_msg_2);
update_option('pxs_error_msg_3', $pxs_error_msg_3);
update_option('pxs_redirect_loc', $pxs_redirect_loc);
update_option('pxs_redirect_time', $pxs_redirect_time);
update_option('pxs_css_inject', $pxs_css_inject);
update_option('pxs_cc_myself', $pxs_cc_myself);
update_option('pxs_user_subject', $pxs_user_subject);
}

/*Get options for form fields*/
$pxs_email = get_option('pxs_email');
$pxs_subject = get_option('pxs_subject');
$pxs_success_msg = get_option('pxs_success_msg');
$pxs_error_msg = get_option('pxs_error_msg');
$pxs_error_msg_2 = get_option('pxs_error_msg_2');
$pxs_error_msg_3 = get_option('pxs_error_msg_3');
$pxs_redirect_loc = get_option('pxs_redirect_loc');
$pxs_redirect_time = get_option('pxs_redirect_time');
$pxs_css_inject = get_option('pxs_css_inject');
$pxs_cc_myself = get_option('pxs_cc_myself');
$pxs_user_subject = get_option('pxs_user_subject');
$pxs_email = stripslashes($pxs_email);
$pxs_subject = stripslashes($pxs_subject);
$pxs_success_msg = stripslashes($pxs_success_msg);
$pxs_error_msg = stripslashes($pxs_error_msg);
$pxs_error_msg_2 = stripslashes($pxs_error_msg_2);
$pxs_error_msg_3 = stripslashes($pxs_error_msg_3);
$pxs_redirect_loc = stripslashes($pxs_redirect_loc);
?>

<div class="wrap"> 
  <h2><?php _e('Contact Form Options') ?></h2> 
  <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=pxsmail.php&updated=true">
    <input type="hidden" name="stage" value="process" />
    <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
      <tr valign="top"> 
        <th scope="row"><?php _e('E-mail Address:') ?></th> 
        <td><input name="pxs_email" type="text" id="pxs_email" value="<?php echo $pxs_email; ?>" size="40" />
        <br />
<?php _e('Enter a single email address for the contact form to send to only one person. If you wish you use the Multi Mail functions, you can enter any number of addresses. The system is quite configurable so please read the instructions at <a href="http://phrixus.co.uk/pxsmail">http://phrixus.co.uk/pxsmail</a> ') ?></td> 
      </tr> 
      <tr valign="top"> 
        <th scope="row"><?php _e('Subject:') ?></th> 
        <td><input name="pxs_subject" type="text" id="pxs_subject" value="<?php echo $pxs_subject; ?>" size="50" />
        <br />
<?php _e('This will be the subject of the email.') ?></td>
      </tr> 
      <tr valign="top"> 
            <th scope="row"><?php _e('User Subject') ?></th>
            <td><input type="checkbox" name="pxs_user_subject" id="pxs_user_subject" value="1" <?php checked('1', get_settings('pxs_user_subject')); ?>>
            <?php _e('Checking this option allows the sender to enter their own subject. If the subject field is left blank. Your subject from above will be used instead.') ?></td>
          </tr> 
       <tr valign="top"> 
            <th scope="row"><?php _e('Redirect - Successful Send:') ?></th> 
            <td>After <input name="pxs_redirect_time" id="pxs_redirect_time" style="width: 20px;" value="<?php echo $pxs_redirect_time; ?>">seconds, automatically redirect to <input name="pxs_redirect_loc" id="pxs_redirect_loc" style="width: 40%;" value="<?php echo $pxs_redirect_loc; ?>">
            <br />
    <?php _e('If you do not wish to redirect the user, leave the destination box blank.') ?></td> 
          </tr> 
          <tr valign="top"> 
            <th scope="row"><?php _e('CSS Insertion') ?></th> 
            <td><input type="checkbox" name="pxs_css_inject" id="pxs_css_inject" value="1" <?php checked('1', get_settings('pxs_css_inject')); ?>>
            <?php _e('If you do not wish PXSmail to insert its own CSS, uncheck this box. You can copy the css from the \'pxsmail.php\' file and add it to your own stylesheet.') ?></td>
          </tr> 
          <tr valign="top"> 
            <th scope="row"><?php _e('CC Sender') ?></th> 
            <td><input type="checkbox" name="pxs_cc_myself" id="pxs_cc_myself" value="1" <?php checked('1', get_settings('pxs_cc_myself')); ?>>
            <?php _e('Checking this option allows the sender to send a copy of the email to their email address by ticking a \'CC Myself\' box. The message will be sent independently and as such will not reveal your email to them') ?></td>
          </tr> 
     </table> 

    <fieldset class="options">
        <legend><?php _e('Messages') ?></legend>
        <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
          <tr valign="top"> 
            <th scope="row"><?php _e('Success Message:') ?></th> 
            <td><textarea name="pxs_success_msg" id="pxs_success_msg" style="width: 80%;" rows="4" cols="50"><?php echo $pxs_success_msg; ?></textarea>
            <br />
    <?php _e('When the form is sucessfully submitted, this is the message the user will see.') ?></td> 
          </tr> 
          
          <tr valign="top"> 
            <th scope="row"><?php _e('Error Message - Empty Fields:') ?></th> 
            <td><textarea name="pxs_error_msg" id="pxs_error_msg" style="width: 80%;" rows="4" cols="50"><?php echo $pxs_error_msg; ?></textarea>
            <br />
    <?php _e('If the user skips a required field, this is the message he will see.') ?> <br />
    </td>
          </tr> 

           <tr valign="top"> 
            <th scope="row"><?php _e('Error Message - Email Format:') ?></th> 
            <td><textarea name="pxs_error_msg_2" id="pxs_error_msg_2" style="width: 80%;" rows="4" cols="50"><?php echo $pxs_error_msg_2; ?></textarea>
            <br />
    <?php _e('If the user has entered all fields but the email address in not in the correct format this message will show.') ?> <br />
    </td>
          </tr> 

           <tr valign="top"> 
            <th scope="row"><?php _e('Error Message - Domain not found:') ?></th> 
            <td><textarea name="pxs_error_msg_3" id="pxs_error_msg_3" style="width: 80%;" rows="4" cols="50"><?php echo $pxs_error_msg_3; ?></textarea>
            <br />
    <?php _e('If the user has entered all fields but the domain of the email address cannot be traced, this message will show.') ?> <br /><br />
    <?php _e('You can apply CSS to these texts by wrapping in <code>&lt;p style="[your CSS here]"&gt; &lt;/p&gt;</code>.') ?><br />
    <?php _e('ie. <code>&lt;p style="color:red;"&gt;Please fill in the required fields.&lt;/p&gt;</code>.') ?></td>
          </tr> 
         </table>   
    </fieldset>
    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Options') ?> &raquo;" />
    </p>
  </form> 
</div>


<?php

}




/*Action calls for all functions*/
//$pxs_inject = get_option('pxs_css_inject');
remove_filter('the_content', 'Markdown', 6);
add_action('admin_menu', 'pxs_admin_menu');
if (get_option('pxs_css_inject') == 1) {
    add_filter('wp_head', 'pxs_css', 2);
}
add_filter('the_content', 'pxs_callback', 1);
?>