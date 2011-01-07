<?php
/**
 * Plugin Name: PayPal Framework
 * Plugin URI: http://xavisys.com/2009/09/wordpress-paypal-framework/
 * Description: PayPal integration framework and admin interface as well as IPN listener.  Requires PHP5.
 * Version: 1.0.6
 * Author: Aaron D. Campbell
 * Author URI: http://xavisys.com/
 */

/*  Copyright 2009  Aaron D. Campbell  (email : wp_plugins@xavisys.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * wpPayPalFramework is the class that handles ALL of the plugin functionality.
 * It helps us avoid name collisions
 * http://codex.wordpress.org/Writing_a_Plugin#Avoiding_Function_Name_Collisions
 */
class wpPayPalFramework
{
	/**
	 * @var array Plugin settings
	 */
	private $_settings;

	/**
	 * Static property to hold our singleton instance
	 * @var wpPayPalFramework
	 */
	static $instance = false;

	/**
	 * @var string Name used for options
	 */
	private $_optionsName = 'paypal-framework';

	/**
	 * @var string Name used for options
	 */
	private $_optionsGroup = 'paypal-framework-options';

	/**
	 * @var array Endpoints for sandbox and live
	 */
	private $_endpoint = array(
		'sandbox'	=> 'https://api-3t.sandbox.paypal.com/nvp',
		'live'		=> 'https://api-3t.paypal.com/nvp'
	);

	/**
	 * @var array URLs for sandbox and live
	 */
	private $_url = array(
		'sandbox'	=> 'https://www.sandbox.paypal.com/webscr',
		'live'		=> 'https://www.paypal.com/webscr'
	);

	/**
	 * @access private
	 * @var string Query var for listener to watch for
	 */
	private $_listener_query_var		= 'paypalListener';

	/**
	 * @access private
	 * @var string Value that query var must be for listener to take overs
	 */
	private $_listener_query_var_value	= 'IPN';

	private $_currencies = array(
		'AUD'	=> 'Australian Dollar',
		'CAD'	=> 'Canadian Dollar',
		'CZK'	=> 'Czech Koruna',
		'DKK'	=> 'Danish Krone',
		'EUR'	=> 'Euro',
		'HKD'	=> 'Hong Kong Dollar',
		'HUF'	=> 'Hungarian Forint',
		'ILS'	=> 'Israeli New Sheqel',
		'JPY'	=> 'Japanese Yen',
		'MXN'	=> 'Mexican Peso',
		'NOK'	=> 'Norwegian Krone',
		'NZD'	=> 'New Zealand Dollar',
		'PLN'	=> 'Polish Zloty',
		'GBP'	=> 'Pound Sterling',
		'SGD'	=> 'Singapore Dollar',
		'SEK'	=> 'Swedish Krona',
		'CHF'	=> 'Swiss Franc',
		'USD'	=> 'U.S. Dollar'
	);

	/**
	 * This is our constructor, which is private to force the use of
	 * getInstance() to make this a Singleton
	 *
	 * @return wpPayPalFramework
	 */
	private function __construct() {
		$this->_getSettings();
		$this->_fixDebugEmails();

		/**
		 * Add filters and actions
		 */
		add_action( 'admin_init', array($this,'registerOptions') );
		add_action( 'admin_menu', array($this,'adminMenu') );
		add_action( 'template_redirect', array( $this, 'listener' ));
		add_filter( 'query_vars', array( $this, 'addPaypalListenerVar' ));
		register_activation_hook( __FILE__, array( $this, 'activatePlugin' ) );

		if ($this->_settings['legacy_support'] == 'on') {
			add_action( 'init', 'paypalFramework_legacy_function' );
		}
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return wpPayPalFramework
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function activatePlugin() {
		update_option( $this->_optionsName, $this->_settings);
	}

	private function _getSettings() {
		if (empty($this->_settings)) {
			$this->_settings = get_option( $this->_optionsName );
		}
		if ( !is_array( $this->_settings ) ) {
			$this->_settings = array();
		}
		$defaults = array(
			'sandbox'			=> 'sandbox',
			'username-sandbox'	=> '',
			'password-sandbox'	=> '',
			'signature-sandbox'	=> '',
			'username-live'		=> '',
			'password-live'		=> '',
			'signature-live'	=> '',
			'version'			=> '58.0',
			'currency'			=> 'USD',
			'debugging'			=> 'on',
			'debugging_email'	=> '',
			'legacy_support'	=> 'off',
		);
		$this->_settings = wp_parse_args($this->_settings, $defaults);
	}

	public function getSetting( $settingName, $default = false ) {
		if (empty($this->_settings)) {
			$this->_getSettings();
		}
		if ( isset($this->_settings[$settingName]) ) {
			return $this->_settings[$settingName];
		} else {
			return $default;
		}
	}

	public function registerOptions() {
		/**
		 * @todo Remove once this supports only 2.7+
		 */
		if ( function_exists('register_setting') ) {
			register_setting( $this->_optionsGroup, $this->_optionsName );
		}
	}

	public function adminMenu() {
		add_options_page(__('PayPal Settings'), __('PayPal'), 'manage_options', 'PayPalFramework', array($this, 'options'));
	}

	/**
	 * This is used to display the options page for this plugin
	 */
	public function options() {
?>
		<style type="text/css">
			#wp_paypal_framework table tr th a {
				cursor:help;
			}
			.large-text{width:99%;}
			.regular-text{width:25em;}
		</style>
		<div class="wrap">
			<h2><?php _e('PayPal Options') ?></h2>
			<form action="options.php" method="post" id="wp_paypal_framework">
<?php
		/**
		 * @todo Use only settings_fields() once this supports only 2.7+
		 */

		if ( function_exists('settings_fields') ) {
			settings_fields( $this->_optionsGroup );
		} else {
			wp_nonce_field('update-options');
?>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="<?php echo $this->_optionsName; ?>" />
<?php
		}
?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_username-live">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_username-live').toggle(); return false;">
									<?php _e('PayPal Live API Username:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[username-live]" value="<?php echo attribute_escape($this->_settings['username-live']); ?>" id="<?php echo $this->_optionsName; ?>_username-live" class="regular-text code" />
							<ol id="pp_username-live" style="display:none; list-style-type:decimal;">
								<li>
									<?php echo sprintf(__('You must have a PayPal business account.  If you do not have one, <a href="%s">sign up for one</a>.'), 'https://www.paypal.com/us/mrb/pal=TJ287296FD8KW'); ?>
								</li>
								<li>
									<?php echo sprintf(__('You must have a PayPal Website Payment Pro.  If you do not have one, <a href="%s">sign up for it</a>.'), 'https://www.paypal.com/us/cgi-bin/webscr?cmd=_wp-pro-overview'); ?>
								</li>
								<li>
									<?php echo sprintf(__('If you will be doing any recurring payments, you must have PayPal\'s Direct Payments Recurring Payments.  If you do not have it set up, please <a href="%s">set it up</a>.'), 'https://www.paypal.com/cgi-bin/webscr?cmd=xpt/cps/general/DPRPLaunch-outside'); ?>
								</li>
								<li>
									<?php echo ('Lastly, you need to generate new API Credentials: In your PayPal account go to "My Account" -> "Profile" -> "Request API credentials" -> "PayPal API" -> "Set up PayPal API credentials and permissions".  If asked, you want to request an "API signature" not a certificate.  All the data that you are given should easily fit in this form.'); ?>
								</li>
							</ol>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_password-live">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_password-live').toggle(); return false;">
									<?php _e('PayPal Live API Password:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[password-live]" value="<?php echo attribute_escape($this->_settings['password-live']); ?>" id="<?php echo $this->_optionsName; ?>_password-live" class="regular-text code" />
							<ol id="pp_password-live" style="display:none; list-style-type:decimal;">
								<li>
									<?php echo sprintf(__('You must have a PayPal business account.  If you do not have one, <a href="%s">sign up for one</a>.'), 'https://www.paypal.com/us/mrb/pal=TJ287296FD8KW'); ?>
								</li>
								<li>
									<?php echo sprintf(__('You must have a PayPal Website Payment Pro.  If you do not have one, <a href="%s">sign up for it</a>.'), 'https://www.paypal.com/us/cgi-bin/webscr?cmd=_wp-pro-overview'); ?>
								</li>
								<li>
									<?php echo sprintf(__('If you will be doing any recurring payments, you must have PayPal\'s Direct Payments Recurring Payments.  If you do not have it set up, please <a href="%s">set it up</a>.'), 'https://www.paypal.com/cgi-bin/webscr?cmd=xpt/cps/general/DPRPLaunch-outside'); ?>
								</li>
								<li>
									<?php echo ('Lastly, you need to generate new API Credentials: In your PayPal account go to "My Account" -> "Profile" -> "Request API credentials" -> "PayPal API" -> "Set up PayPal API credentials and permissions".  If asked, you want to request an "API signature" not a certificate.  All the data that you are given should easily fit in this form.'); ?>
								</li>
							</ol>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_signature-live">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_signature-live').toggle(); return false;">
									<?php _e('PayPal Live API Signature:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[signature-live]" value="<?php echo attribute_escape($this->_settings['signature-live']); ?>" id="<?php echo $this->_optionsName; ?>_signature-live" class="regular-text code" />
							<ol id="pp_signature-live" style="display:none; list-style-type:decimal;">
								<li>
									<?php echo sprintf(__('You must have a PayPal business account.  If you do not have one, <a href="%s">sign up for one</a>.'), 'https://www.paypal.com/us/mrb/pal=TJ287296FD8KW'); ?>
								</li>
								<li>
									<?php echo sprintf(__('You must have a PayPal Website Payment Pro.  If you do not have one, <a href="%s">sign up for it</a>.'), 'https://www.paypal.com/us/cgi-bin/webscr?cmd=_wp-pro-overview'); ?>
								</li>
								<li>
									<?php echo sprintf(__('If you will be doing any recurring payments, you must have PayPal\'s Direct Payments Recurring Payments.  If you do not have it set up, please <a href="%s">set it up</a>.'), 'https://www.paypal.com/cgi-bin/webscr?cmd=xpt/cps/general/DPRPLaunch-outside'); ?>
								</li>
								<li>
									<?php echo ('Lastly, you need to generate new API Credentials: In your PayPal account go to "My Account" -> "Profile" -> "Request API credentials" -> "PayPal API" -> "Set up PayPal API credentials and permissions".  If asked, you want to request an "API signature" not a certificate.  All the data that you are given should easily fit in this form.'); ?>
								</li>
							</ol>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_username-sandbox">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_username-sandbox').toggle(); return false;">
									<?php _e('PayPal Sandbox API Username:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[username-sandbox]" value="<?php echo attribute_escape($this->_settings['username-sandbox']); ?>" id="<?php echo $this->_optionsName; ?>_username-sandbox" class="regular-text code" />
							<p id="pp_username-sandbox" style="display:none;">
								<?php echo sprintf(__('You must have a <a href="%s">PayPal sandbox account</a>.'), 'https://developer.paypal.com/'); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_password-sandbox">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_password-sandbox').toggle(); return false;">
									<?php _e('PayPal Sandbox API Password:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[password-sandbox]" value="<?php echo attribute_escape($this->_settings['password-sandbox']); ?>" id="<?php echo $this->_optionsName; ?>_password-sandbox" class="regular-text code" />
							<p id="pp_password-sandbox" style="display:none;">
								<?php echo sprintf(__('You must have a <a href="%s">PayPal sandbox account</a>.'), 'https://developer.paypal.com/'); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_signature-sandbox">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_signature-sandbox').toggle(); return false;">
									<?php _e('PayPal Sandbox API Signature:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[signature-sandbox]" value="<?php echo attribute_escape($this->_settings['signature-sandbox']); ?>" id="<?php echo $this->_optionsName; ?>_signature-sandbox" class="regular-text code" />
							<p id="pp_signature-sandbox" style="display:none;">
								<?php echo sprintf(__('You must have a <a href="%s">PayPal sandbox account</a>.'), 'https://developer.paypal.com/'); ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('PayPal Sandbox or Live:') ?>
						</th>
						<td>
							<input type="radio" name="<?php echo $this->_optionsName; ?>[sandbox]" value="live" id="<?php echo $this->_optionsName; ?>_sandbox-live"<?php checked('live', $this->_settings['sandbox']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_sandbox-live"><?php _e('Live'); ?></label><br />
							<input type="radio" name="<?php echo $this->_optionsName; ?>[sandbox]" value="sandbox" id="<?php echo $this->_optionsName; ?>_sandbox-sandbox"<?php checked('sandbox', $this->_settings['sandbox']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_sandbox-sandbox"><?php _e('Use Sandbox (for testing only)'); ?></label><br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_currency">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_currency').toggle(); return false;">
									<?php _e('Default Currency:') ?>
								</a>
							</label>
						</th>
						<td>
							<select id="<?php echo $this->_optionsName; ?>_currency" class="postform" name="<?php echo $this->_optionsName; ?>[currency]">
								<option value=''>Please Choose Default Currency</option>
<?php	foreach ( $this->_currencies as $code => $currency ) { ?>
								<option value='<?php echo attribute_escape($code); ?>'<?php selected($code, $this->_settings['currency']); ?>><?php _e($currency); ?></option>
<?php	} ?>
							</select>
							<small id="pp_currency" style="display:none;">
								This is just the default currency for if one isn't specified.
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_version">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_version').toggle(); return false;">
									<?php _e('PayPal API version:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[version]" value="<?php echo attribute_escape($this->_settings['version']); ?>" id="<?php echo $this->_optionsName; ?>_version" class="small-text" />
							<small id="pp_version" style="display:none;">
								This is the default version to use if one isn't
								specified.  It is usually safe to set this to
								the <a href="http://developer.paypal-portal.com/pdn/board/message?board.id=nvp&thread.id=4475">most recent version</a>.
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_debugging').toggle(); return false;">
								<?php _e('Debugging Mode:') ?>
							</a>
						</th>
						<td>
							<input type="radio" name="<?php echo $this->_optionsName; ?>[debugging]" value="on" id="<?php echo $this->_optionsName; ?>_debugging-on"<?php checked('on', $this->_settings['debugging']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_debugging-on"><?php _e('On'); ?></label><br />
							<input type="radio" name="<?php echo $this->_optionsName; ?>[debugging]" value="off" id="<?php echo $this->_optionsName; ?>_debugging-off"<?php checked('off', $this->_settings['debugging']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_debugging-off"><?php _e('Off'); ?></label><br />
							<small id="pp_debugging" style="display:none;">
								If this is on, debugging messages will be sent
								to the E-Mail address set below.
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $this->_optionsName; ?>_debugging_email">
								<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_debugging_email').toggle(); return false;">
									<?php _e('Debugging E-Mail:') ?>
								</a>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->_optionsName; ?>[debugging_email]" value="<?php echo attribute_escape($this->_settings['debugging_email']); ?>" id="<?php echo $this->_optionsName; ?>_version" class="regular-text" />
							<small id="pp_debugging_email" style="display:none;">
								This is a comma separated list of E-Mail
								addresses that will receive the debug messages.
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_legacy_support').toggle(); return false;">
								<?php _e('Legacy hash_call() support:') ?>
							</a>
						</th>
						<td>
							<input type="radio" name="<?php echo $this->_optionsName; ?>[legacy_support]" value="on" id="<?php echo $this->_optionsName; ?>_legacy_support-on"<?php checked('on', $this->_settings['legacy_support']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_legacy_support-on"><?php _e('On'); ?></label><br />
							<input type="radio" name="<?php echo $this->_optionsName; ?>[legacy_support]" value="off" id="<?php echo $this->_optionsName; ?>_legacy_support-off"<?php checked('off', $this->_settings['legacy_support']); ?> />
							<label for="<?php echo $this->_optionsName; ?>_legacy_support-off"><?php _e('Off'); ?></label><br />
							<small id="pp_legacy_support" style="display:none;">
								The new function for seding NVP API calls to
								PayPal if hashCall().  If your scripts still use
								the old hash_call() and you don't want to update
								them, enable this.  <em>This could conflict with
								an existing hash_call function if you have it
								defined elsewhere.</em>
							</small>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<a title="<?php _e('Click for Help!'); ?>" href="#" onclick="jQuery('#pp_listener_url').toggle(); return false;">
								<?php _e('PayPal IPN Listener URL:') ?>
							</a>
						</th>
						<td>
							<?php echo get_bloginfo('url').'/?'.$this->_listener_query_var.'='.urlencode($this->_listener_query_var_value); ?>
							<div id="pp_listener_url" style="display:none;">
								<p><?php _e('To set this in your PayPal account:'); ?></p>
								<ol style="list-style-type:decimal;">
									<li>
										<?php _e('Click <strong>Profile</strong> on the <strong>My Account</strong> tab.'); ?>
									</li>
									<li>
										<?php _e('Click <strong>Instant Payment Notification Preferences</strong> in the Selling Preferences column.'); ?>
									</li>
									<li>
										<?php _e("Click <strong>Edit IPN Settings</strong> to specify your listener's URL and activate the listener."); ?>
									</li>
									<li>
										<?php _e('Copy/Paste the URL shown above into the Notification URL field.'); ?>
									</li>
									<li>
										<?php _e('Click Receive IPN messages (Enabled) to enable your listener.'); ?>
									</li>
									<li>
										<?php _e('Click <strong>Save</strong>.'); ?>
									</li>
									<li>
										<?php _e("You're Done!  If you want, you can click <strong>Back to Profile Summary</strong> to return to the Profile after activating your listener."); ?>
									</li>
								</ol>
							</div>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;'); ?>" />
				</p>
			</form>
		</div>
<?php
	}

	/**
	 * This function creates a name value pair (nvp) string from a given array,
	 * object, or string.  It also makes sure that all "names" in the nvp are
	 * all caps (which PayPal requires) and that anything that's not specified
	 * uses the defaults
	 *
	 * @param array|object|string $req Request to format
	 *
	 * @return string NVP string
	 */
	private function _prepRequest($req) {
		$defaults = array(
			'VERSION'		=> $this->_settings['version'],
			'PWD'			=> $this->_settings["password-{$this->_settings['sandbox']}"],
			'USER'			=> $this->_settings["username-{$this->_settings['sandbox']}"],
			'SIGNATURE'		=> $this->_settings["signature-{$this->_settings['sandbox']}"],
			'CURRENCYCODE'	=> $this->_settings['currency'],
		);
		return wp_parse_args( $req, $defaults );
	}

	/**
	 * Convert an associative array into an NVP string
	 *
	 * @param array Associative array to create NVP string from
	 * @param string[optional] Used to separate arguments (defaults to &)
	 *
	 * @return string NVP string
	 */
	public function makeNVP( $reqArray, $sep = '&' ) {
		if ( !is_array($reqArray) ) {
			return $reqArray;
		}
		return http_build_query( $reqArray, '', $sep );
	}

	/**
	 * hashCall: Function to perform the API call to PayPal using API signature
	 * @param string|array $args Parameters needed for call
	 *
	 * @return array On success return associtive array containing the response from the server.
	 */
	public function hashCall( $args ) {
		$params = array(
			'body'		=> $this->_prepRequest($args),
			'sslverify' => false,
			'timeout' 	=> 30,
		);

		/**
		 * @todo Use only wp_remote_post() once we only support 2.7+
		 */
		// Send the request
		if ( !function_exists('wp_remote_post') ) {
			require_once('http.php');
		}
		$resp = wp_remote_post( $this->_endpoint[$this->_settings['sandbox']], $params );

		// If the response was valid, decode it and return it.  Otherwise return a WP_Error
		if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 ) {
			// Used for debugging.
			if ( $this->_settings['debugging'] == 'on' && !empty($this->_settings['debugging_email']) ) {
				$request = $this->_sanitizeRequest($params['body']);
				wp_mail($this->_settings['debugging_email'], 'PayPal Framework - hashCall sent successfully', "Request:\r\n".print_r($request, true)."\r\n\r\nResponse:\r\n".print_r(wp_parse_args($resp['body']), true));
			}
			return wp_parse_args($resp['body']);
		} else {
			if ( $this->_settings['debugging'] == 'on' && !empty($this->_settings['debugging_email']) ) {
				$request = $this->_sanitizeRequest($params['body']);
				wp_mail($this->_settings['debugging_email'], 'PayPal Framework - hashCall failed', "Request:\r\n".print_r($request, true)."\r\n\r\nResponse:\r\n".print_r($resp, true));
			}
			if ( !is_wp_error($resp) ) {
				$resp = new WP_Error('http_request_failed', $resp['response']['message'], $resp['response']);
			}
			return $resp;
		}
	}

	private function _sanitizeRequest($request) {
		/**
		 * If this is a live request, hide sensitive data in the debug
		 * E-Mails we send
		 */
		if ( $this->_settings['sandbox'] != 'sandbox' ) {
			if ( !empty( $request['ACCT'] ) ) {
				$request['ACCT']	= str_repeat('*', strlen($request['ACCT'])-4) . substr($request['ACCT'], -4);
			}
			if ( !empty( $request['EXPDATE'] ) ) {
				$request['EXPDATE']	= str_repeat('*', strlen($request['EXPDATE']));
			}
			if ( !empty( $request['CVV2'] ) ) {
				$request['CVV2']	= str_repeat('*', strlen($request['CVV2']));
			}
		}
		return $request;
	}

	/**
	 * Used to direct the user to the Express Checkout
	 *
	 * @param string|array $args Parameters needed for call.  *token is REQUIRED*
	 */
	public function sendToExpressCheckout($args) {
		$args['cmd'] = '_express-checkout';
		$nvpString = $this->makeNVP($args);
		wp_redirect($this->_url[$this->_settings['sandbox']] . "?{$nvpString}");
		exit;
	}

	/**
	 * This is our listener.  If the proper query var is set correctly it will
	 * attempt to handle the response.
	 */
	public function listener() {
		// Check that the query var is set and is the correct value.
		if (get_query_var( $this->_listener_query_var ) == $this->_listener_query_var_value) {
			$_POST = stripslashes_deep($_POST);
			// Try to validate the response to make sure it's from PayPal
			if ($this->_validateMessage()) {
				// If the message validated, process it.
				$this->_processMessage();
			}
			// Stop WordPress entirely
			exit;
		}
	}

	/**
	 * Get the PayPal URL based on current setting for sandbox vs live
	 */
	public function getUrl() {
		return $this->_url[$this->_settings['sandbox']];
	}

	public function _fixDebugEmails() {
		$this->_settings['debugging_email'] = preg_split('/\s*,\s*/', $this->_settings['debugging_email']);
		$this->_settings['debugging_email'] = array_filter($this->_settings['debugging_email'], 'is_email');
		$this->_settings['debugging_email'] = implode(',', $this->_settings['debugging_email']);
	}

	/**
	 * Validate the message by checking with PayPal to make sure they really
	 * sent it
	 */
	private function _validateMessage() {
		// Set the command that is used to validate the message
		$_POST['cmd'] = "_notify-validate";

		// We need to send the message back to PayPal just as we received it
		$params = array(
			'body' => $_POST
		);

		/**
		 * @todo Use only wp_remote_post() once we only support 2.7+
		 */
		// Send the request
		if ( !function_exists('wp_remote_post') ) {
			require_once('http.php');
		}
		$resp = wp_remote_post( $this->_url[$this->_settings['sandbox']], $params );

		// Put the $_POST data back to how it was so we can pass it to the action
		unset($_POST['cmd']);

		// If the response was valid, check to see if the request was valid
		if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 && (strcmp( $resp['body'], "VERIFIED") == 0)) {
			// Used for debugging.
			if ( $this->_settings['debugging'] == 'on' && !empty($this->_settings['debugging_email']) ) {
				wp_mail($this->_settings['debugging_email'], 'IPN Listener Test - Validation Succeeded', "URL:\r\n".print_r($this->_url[$this->_settings['sandbox']], true)."\r\n\r\nOptions:\r\n".print_r($this->_settings, true)."\r\n\r\nResponse:\r\n".print_r($resp, true)."\r\n\r\nPost:\r\n".print_r($_POST, true));
			}
			return true;
		} else {
			// If we can't validate the message, assume it's bad
			// Used for debugging.
			if ( $this->_settings['debugging'] == 'on' && !empty($this->_settings['debugging_email']) ) {
				wp_mail($this->_settings['debugging_email'], 'IPN Listener Test - Validation Failed', "URL:\r\n".print_r($this->_url[$this->_settings['sandbox']], true)."\r\n\r\nOptions:\r\n".print_r($this->_settings, true)."\r\n\r\nResponse:\r\n".print_r($resp, true)."\r\n\r\nPost:\r\n".print_r($_POST, true));
			}

			return false;
		}
	}

	/**
	 * Add our query var to the list of query vars
	 */
	public function addPaypalListenerVar($public_query_vars) {
		$public_query_vars[] = $this->_listener_query_var;
		return $public_query_vars;
	}

	/**
	 * Throw an action based off the transaction type of the message
	 */
	private function _processMessage() {
		do_action("paypal-ipn", $_POST);
		if ( !empty($_POST['txn_type']) ) {
			$specificAction = " and paypal-{$_POST['txn_type']}";
			do_action("paypal-{$_POST['txn_type']}", $_POST);
		}

		// Used for debugging.
		if ( $this->_settings['debugging'] == 'on' && !empty($this->_settings['debugging_email']) ) {
			wp_mail($this->_settings['debugging_email'], 'IPN Listener Test - _processMessage()', "Actions thrown: paypal-ipn{$specificAction}\r\n\r\nPassed to action:\r\n".print_r($_POST, true));
		}
	}
}

/**
 * Helper functions
 */
function hashCall ($args) {
	$wpPayPalFramework = wpPayPalFramework::getInstance();
	return $wpPayPalFramework->hashCall($args);
}

function paypalFramework_legacy_function() {
	//Only load if the function doesn't already exist
	if ( !function_exists('hash_call') ) {
		/**
		 * Support the old method of using hash_call
		 */
		function hash_call($methodName, $nvpStr) {
			_deprecated_function(__FUNCTION__, '0.1', 'wpPayPalFramework::hashCall()');
			$nvpStr = wp_parse_args( $nvpStr );
			$nvpStr['METHOD'] = $methodName;
			$nvpStr = array_map('urldecode', $nvpStr);
			$wpPayPalFramework = wpPayPalFramework::getInstance();
			return $wpPayPalFramework->hashCall($nvpStr);
		}
	}
}

// Instantiate our class
$wpPayPalFramework = wpPayPalFramework::getInstance();
