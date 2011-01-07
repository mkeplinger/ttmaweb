=== Event Registration ===
Contributors: David Fleming
Donate link: http://www.edgetechweb.com/
Tags:   event, events, event registration, event management,events managment, events registration, event calendar, calendar, class,events calendar, events, event, class registration, class schedule, classes
Requires at least: 2.0.2
Tested up to: 3.0.2
Stable tag: 5.43

This plugin is designed to allow you to take online registrations for events and classes. Supports Paypal, Google Pay, MonsterPay or Authorize.net online payment sites for online collection of event fees.

== Description ==

This wordpress plugin is designed to run on a Wordpress website and provide registration events, classes, or parties. 
It allows you to capture the registering persons contact information and any additional infromation you request to a database and provides an association to an events database. 
It provides the ability to send the register to either a Paypal, Google Pay, Monster Pay,  or Authorize.net online payment site for online collection of event fees.
Additionally it allows support for checks and cash payments.  
Optional Captcha field on registration form.
Detailed payment management system to track and record event payments.  
Reporting features provide a export list(s) of events, attendees, payments in excel or csv.  
Events can be created in an Excel spreadsheet and uploaded via the event upload tool.  
Dashboard widget allows for quick reference to events from the dashboard.  
Inline menu navigation allows for ease of use.

If you like the plugin and find it useful, please donate.  
Your donations help me keep it going and improve it.  
You can donate and find online information at http://edgetechweb.com/

Also if you could rate the plugin that would also be helpful.

= Support =

Thanks for all your suggestions and feedback.  I have begun setting up a dedicated site for the plugin www.edgetechweb.com primarily for support issues.  There is a page with installation directions  

Documentation is here http://edgetechweb.com/wp-content/uploads/EVENTREGIS-USER-GUIDE1.pdf  

Please continue to email questions or comments to consultant@avdude.com.  

If you like the plugin and find it useful, your donations would also help me keep it going and improve it.  You can donate and find online information at http://edgetechweb.com/

== Change Log ==
= 5.42 =
Fixed error related to cp_day function in calendar

= 5.41 =
Fixed error related to np_of_day function in calendar

= 5.39 =
added submission key to form submission to stop invalid registrations/spam from posting
Changed Zip on from to reflect Postal/Zip for my international friends :)

= 5.36 =

Resolved image address on category color selector

= 5.35 =
Resolved Calendar yearly rollover
Resolved Calendar permalinks
Changed Calendar format
Modified category table for calendar coloring
Added style to Calendar format
Added hyperlink registration to Calendar Days with events
Added day spaning for events on calendar with multiday events
Added event color coding for events on calendar
Added new shortcode for event listing for 60 day outlook  `{EVR_UPCOMING}`

= 5.32 =
added calendar widget for sidebars
added hyperlink(s) to sidebard event listing widget for registration
added captcha on/off button in Organization Tab
Cleaned up code in Organization Admin File

= 5.31 =
Fixed error on Google Pay
Added Captcha to Registration Form
Fixed hyperlink in Calendar for Day Of Events to link title to registration page.
Fixed Calendar bug on showing active events in December

= 5.30 =
Error in SVN uploading of version 5.24
= 5.25 =
= 5.24 =
Fixed date sorting issue in sidebar widget
Fixed date sorting issue in dashboard widget
= 5.23 =
Fixed issue with Date sorting on all event listings
 

= 5.20 =
Fixed issue for custom currency to allow different currency formats per event
Fixed issue where code displaying on registration confirmation page
Fixed Currency display issue on events listing page
Fixed mail send issue to use Organization name/email for outbound confirmations
Fixed issue where Donations button was showing when Organization Section said no to accepting donations.
Changed function name dateDiff - conflict with myadmanager plugin.
Added ability to send mail to participants from within the plugin.
Resolved default month display with Calendar
Resolved Year Rollover Issue in Calendar
Added MonsterPay as payment gateway option

= 5.11 =
Resolved shortcode displaying wrong on event page for single event shortcode.
Resolved broken links when using images on event listings.
Resolved broken month links in calendar.
Resolved missing images on Calendar.
Fixed events to display for current day autoomatically on Calendar.
Resolved broken links on event listings in Calendar.

= 5.10=  
Fixed issue with Calendar
Resolved Authorize.net validation issue
Resolved database upgrade from 4.0 issue
Resolved extra numbers at bottom of the Month select/option drop-down combo box
Resolved misspelling for Event Calendar shortcode


= 5.0 =
Revised look and usability of admin panels by CSS
Fixed bug in CSV import file
Revised admin accounting features
Modified registration form to provide ability to use or not use default fields
Added dashboard widget
Added calendar
Added Sample Creation for getting started
Fixed issue where text would only appear below registration form.
Fixed other minor bugs.
      
. . . See changelog.txt for more changes


== Upgrading ==

If you are upgrading from a version prior to 5.0, I have made some major changes to the database and depending on your configuration, it may or may not upgrade the first attempt.  I recommend activating and deactivating the new version several times.
You can look under the support tab and if it does not say 5.xx then you need to activate and reactivate the upgrade.  Please email me if this does not resolve your issues.  consultant@avdude.com



== Installation ==

1. After unzipping, upload everything in the `Events Registration` folder to your `/wp-content/plugins/` directory (preserving directory structure).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Event Registration Menu and Configure Organization and enter your company info - note you will need a paypal id if you plan on accepting paypal payments
4. Go to the Event Setup and create a new event, make sure you select 'make active'.
5. Create a new page (not post) on your site. Put `{EVENTREGIS}` in it on a line by itself.
6. Note: if you are upgradings from a previous version please backup your data prior to upgrade.

If you would like to put a specific event on a page use `[Event_Registration_Single id="1"]` where 1 is the event id number.  Make sure that you have display all events active in the Configure Organization Tab.  Yes you can still use `{EVENTREGIS}` at the same time!

All done. 

If you like the plugin and find it useful, your donations would also help me keep it going and improve it.  You can donate and find online information at http://edgetechweb.com/

= License =

This plugin is provided "as is" and without any warranty or expectation of function. I'll probably try to help you if you ask nicely, but I can't promise anything. You are welcome to use this plugin and modify it however you want, as long as you give credit where it is due. 

Please feel free to email me your changes and modifications and I will gladly try to incorporate them in.

== Screenshots ==

www.edgetechweb.com

== Frequently Asked Questions ==

Q: Do you do custom modifications?
A: Yes, for a resonable fee.

Q: Why does email sent by the plugin say wordpress@yourdomain.com?
A: This is a default wordpress thing.  There is a great little plugin that resolves that issue. http://wordpress.org/extend/plugins/mail-from/ 


