=== PayPal Framework ===
Contributors: aaroncampbell
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal%40xavisys%2ecom&item_name=PayPal%20Framework&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: paypal
Requires at least: 2.6
Tested up to: 3.1
Stable tag: 1.0.6

PayPal integration framework and admin interface as well as IPN listener.  Requires PHP5.

== Description ==

This plugins gives you a great framework to use for integrating with PayPal.
It stores both live and sandbox API credentials and allows you to switch back
and forth easily.  All NVP API calls are passed through the framework and
default values such as API version, API credentials, and even currency code are
added to the request based on settings from the admin panel.

It also has a built in IPN listener that validates messages as coming from
PayPal then throws WordPress actions based on messages received.  For example it
throws "paypal-recurring_payment_profile_cancel" when someone cancels a
recurring payment that they had set up with you.  It passes along all the info
that PayPal sent to the action, so it's simple to create other plugins that use
this one.

Requires PHP5.

You may also be interested in WordPress tips and tricks at <a href="http://wpinformer.com">WordPress Informer</a> or gerneral <a href="http://webdevnews.net">Web Developer News</a>

== Installation ==

1. Verify that you have PHP5, which is required for this plugin.
1. Upload the whole `paypal-framework` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I send a request to PayPal? =

To send a request to PayPal, simply build the request as an associative array and pass it to the hashCall helper function like this:
<code>
$ppParams = array(
	'METHOD'			=> 'doDirectPayment',
	'PAYMENTACTION'		=> 'Sale',
	'IPADDRESS'			=> '123.123.123.123',
	'AMT'				=> '222.22',
	'DESC'				=> 'some product',
	'CREDITCARDTYPE'	=> 'VISA',
	'ACCT'				=> '4111111111111111',
	'EXPDATE'			=> '112011',
	'CVV2'				=> '123',
	'FIRSTNAME'			=> 'Aaron',
	'LASTNAME'			=> 'Campbell',
	'EMAIL'				=> 'pptest@xavisys.com',
	'STREET'			=> '123 some pl',
	'STREET2'			=> '',
	'CITY'				=> 'San Diego',
	'STATE'				=> 'CA',
	'ZIP'				=> '92101',
	'COUNTRYCODE'		=> 'US',
	'INVNUM'			=> '12345',
);

$response = hashCall($ppParams);
</code>

== Changelog ==

= 1.0.6 =
* Fixed a bug that throws a warning for certain requests when in debugging mode.  Props Ken Bass <kbass@kenbass.com>

= 1.0.5 =
* Fixed a bug introduced in 1.0.4 that affected certain debug messages when not using the sandbox

= 1.0.4 =
* Debug E-Mails for live requests now get an obfuscated credit card number (ACCT) as well as EXPDATE and CVV2
* The IPN listener only throws a transaction-specific action if a txt_type is given in the message

= 1.0.3 =
* IPN Message validations now work even if there are apostophes (slashes are stripped)
* You can now have multiple debug E-Mail addresses (comma separated)

= 1.0.2 =
* Added a / to the IPN URL so that PayPal doesn't complain that it's invalid
* Fixed a couple debug messages to send the proper URL used
* Added a more general "paypal-ipn" action that can be used to catch and process all IPN message
* Moved add_action calls inside the __construct

= 1.0.1 =
* Added sendToExpressCheckout method for sending users to PayPal to finish up Express Checkout Payments
* Changed hashCall to use the WordPress WP_Http class
* Changed makeNVP to a public method
* Updated makeNVP to use http_build_query
* Switched to using wp_remote_post rather than specifying POST and using wp_remote_request

= 1.0.0 =
* Original version released to wordpress.org repository
