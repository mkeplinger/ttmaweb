<?php

function _livechat_helper_license_created_info()
{
	echo '<div class="updated installed_ok"><p><strong>Your live chat license has been created! Please install the <a href="http://www.livechatinc.com/download/LiveChat/LiveChat.exe">live chat application</a> and start chatting!</strong></p></div>';
}

function _livechat_helper_monitoring_code_info()
{
	if (LIVECHAT_LICENSE_INSTALLED)
	{
		echo '<div class="updated installed_ok"><p><strong>Your live chat monitoring code is installed properly. <a class="help" href="http://www.livechatinc.com/en/support/manual/monitoring_code.htm">(what is a monitoring code?)</a> </strong></p></div>';
	}
}

function _livechat_helper_chat_button_info()
{
	if (LIVECHAT_LICENSE_INSTALLED)
	{
		if (is_active_widget (null, 'livechat_widget', true))
		{
			echo '<div class="updated info installed_ok"><p><strong>Your live chat button is installed properly. <span class="help">(<a href="widgets.php">manage widgets</a> | <a href="http://www.livechatinc.com/en/support/manual/chat_button.htm" target="_blank">what is a chat button?</a>)</span> </strong></p></div>';
		}
		else
		{
			// Check if theme supports Widgets
			if (LIVECHAT_WIDGETS_ENABLED)
			{
				echo '<div class="updated info"><p><strong>To install your live chat button, go to <a href="widgets.php">Widgets</a> page. <a class="help" href="http://www.livechatinc.com/en/support/manual/chat_button.htm" target="_blank">(what is a chat button?)</a> </strong></p></div>';
			}
			else
			{
				echo '<div class="updated info"><p><strong>To install your live chat button, <a href="?page=livechat_chat_button">click here</a>. <a class="help" href="http://www.livechatinc.com/en/support/manual/chat_button.htm" target="_blank">(what is a chat button?)</a> </strong></p></div>';
			}
		}
	}
}

function _livechat_helper_saved_info()
{
	if (isset($_GET['updated']))
	{
		echo '<div class="updated"><p><strong>Settings saved.</strong></p></div>';
	}
}

function _livechat_settings()
{
?>
<div class="wrap">
<h2>Live chat software for Wordpress</h2>

<?php
if (get_option('livechat_license_created_flag') == '1')
{
	delete_option('livechat_license_created_flag');
	_livechat_helper_license_created_info();
	_livechat_helper_monitoring_code_info();
	_livechat_helper_chat_button_info();
}
else
{
	_livechat_helper_monitoring_code_info();
	_livechat_helper_chat_button_info();
	_livechat_helper_saved_info();
}
?>

<div class="metabox-holder">
	<div class="postbox">
		<h3>Do you already have a live chat account?</h3>
		<div class="postbox_content">
		<ul id="choice_account">
		<li><input type="radio" name="choice_account" id="choice_account_1" checked="checked"> <label for="choice_account_1">Yes, I already have a live chat account</label></li>
		<li><input type="radio" name="choice_account" id="choice_account_0"> <label for="choice_account_0">No, I want to create one</label></li>
		</ul>
		</div>
	</div>
</div>

<!-- Already have an account -->
<div class="metabox-holder" id="livechat_already_have" style="display:none">

	<?php if (LIVECHAT_LICENSE_INSTALLED): ?>
	<div class="postbox">
	<h3>Download application</h3>
	<div class="postbox_content">
	<p>Download the live chat application and start chatting with your customers!</p>
	<p><a href="http://www.livechatinc.com/download/LiveChat/LiveChat.exe" class="awesome blue">Download application</a></p>
	</div>
	</div>
	<?php endif; ?>

	<div class="postbox">
	<form method="post" action="options.php">
	<?php settings_fields('livechat_license_information'); ?>

		<h3>Live chat account</h3>
		<div class="postbox_content">
		<table class="form-table">
		<tr>
		<th scope="row"><label for="livechat_license_number">My license number is:</label></th>
		<td><input type="text" name="livechat_license_number" id="livechat_license_number" value="<?php echo get_option('livechat_license_number'); ?>" /><?php if (LIVECHAT_LICENSE_INSTALLED == false): ?> <span class="explanation">You will find your license number in the <a href="?page=livechat_control_panel">Control Panel</a>.</span><?php endif; ?></td>
		</tr>

		<?php if (LIVECHAT_LICENSE_INSTALLED): ?>
		<?php
		$lang = get_option('livechat_lang');
		if (empty($lang)) $lang = 'en';
		?>
		<tr>
		<th scope="row"><label for="livechat_lang">Language:</label></th>
		<td><input type="text" name="livechat_lang" id="livechat_lang" value="<?php echo $lang; ?>" /> <a class="help" href="http://www.livechatinc.com/en/support/documentation/customizing_web_agent_.htm" target="_blank">supported languages list</a> <span class="explanation"><strong>en</strong> for English (default)</span></td>
		</tr>

		<?php
		$groups = get_option('livechat_groups');
		if (empty($groups)) $groups = '0';
		?>
		<tr>
		<th scope="row"><label for="livechat_groups">Skill:</label></th>
		<td><input type="text" name="livechat_groups" id="livechat_groups" value="<?php echo $groups; ?>" /> <a class="help" href="http://www.livechatinc.com/en/resources/tutorials/skills_based_routing/" target="_blank">what is that?</a> <span class="explanation"><strong>0</strong> for default skill (recommended)</span></td>
		</tr>

		<?php
		$params = get_option('livechat_params');
		?>
		<tr>
		<th scope="row"><label for="livechat_params">Params:</label></th>
		<td><input type="text" name="livechat_params" id="livechat_params" value="<?php echo $params; ?>" /> <a class="help" href="http://www.livechatinc.com/en/support/documentation/customizing_web_agent_.htm" target="_blank">advanced help</a></td>
		</tr>
		<?php else: ?>
		<?php endif; ?>
		</table>

		<p class="submit">
		<input type="hidden" name="livechat_license_created_flag" value="0" id="livechat_license_created_flag">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

		</div>
	</form>
	</div>
</div>

<!-- New account form -->
<div class="metabox-holder" id="livechat_new_account" style="display:none">
	<div class="postbox">
	<form method="post" action="options.php">
		<h3>Create new live chat account</h3>
		<div class="postbox_content">

		<?php settings_fields('livechat_license_created'); ?>
		<p>Fields marked with <span class="asterisk">*</span> are required.</p>
		<table class="form-table">
		<tr>
		<th scope="row"><label for="livechat_account_first_name"><span class="asterisk">*</span>First name:</label></th>
		<td><input type="text" name="livechat_account_first_name" id="livechat_account_first_name" maxlength="30" /></td>
		</tr>
		<tr>
		<th scope="row"><label for="livechat_account_last_name"><span class="asterisk">*</span>Last name:</label></th>
		<td><input type="text" name="livechat_account_last_name" id="livechat_account_last_name" maxlength="30" /></td>
		</tr>
		<tr>
		<th scope="row"><label for="livechat_account_email"><span class="asterisk">*</span>E-mail:</label></th>
		<td><input type="text" name="livechat_account_email" id="livechat_account_email" maxlength="70" /></td>
		</tr>
		<tr>
		<th scope="row"><label for="livechat_account_company">Company name:</label></th>
		<td><input type="text" name="livechat_account_company" id="livechat_account_company" maxlength="70" /></td>
		</tr>
		<tr>
		<th scope="row"><label for="livechat_account_phone">Phone number:</label></th>
		<td><input type="text" name="livechat_account_phone" id="livechat_account_phone" maxlength="70" /></td>
		</tr>
		<tr>
		<th scope="row"><label for="livechat_account_website">Website:</label></th>
		<td><input type="text" name="livechat_account_website" id="livechat_account_website" maxlength="70" value="<?php echo bloginfo('url'); ?>" /></td>
		</tr>
		<tr>
		<th scope="row"><label><span class="asterisk">*</span>Confirmation code:</label></th>
		<td>
		<!-- ReCaptcha -->
		<input type="hidden" name="recaptcha_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
		<script type="text/javascript" src="https://api-secure.recaptcha.net/challenge?k=6LcqygsAAAAAAF5zKDVwo5Mfvc4DarPQIcc_lxTn"></script>
		<noscript><iframe src="https://api-secure.recaptcha.net/noscript?k=6LcqygsAAAAAAF5zKDVwo5Mfvc4DarPQIcc_lxTn" height="300" width="500" frameborder="0"></iframe><br>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea><input type="hidden" name="recaptcha_response_field" value="manual_challenge"></noscript>
		<!-- end ReCaptcha -->
		</td>
		</tr>
		</table>

		<p id="ajax_message"></p>

		<table class="form-table">
		<tr>
		<td class="submit">
			<input type="hidden" name="livechat_account_timezone" value="US/Pacific" id="livechat_account_timezone">
			<input type="submit" value="Create account" id="submit" class="button-primary">
		</td>
		</tr>
		</table>

		</div>
	</form>
	</div>
</div>




<?php
}