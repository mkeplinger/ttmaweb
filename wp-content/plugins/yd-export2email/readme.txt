=== YD Export2Email Plugin ===
Contributors: ydubois
Donate link: http://www.yann.com/
Tags: plugin, automatic, admin, administration, Post, posts, email, e-mail, mailing, send, template, mail template, theme, mail theme, newsletter, timthumb
Requires at least: 2.9.1
Tested up to: 2.9.2
Stable tag: trunk

Installs a new button visible to WP editors that can export e-mail compatible XML code of a single post. Uses specific theme for e-mail templating.

== Description ==

= Send any blog page as a html-formatted e-mail using your favorite e-mail client or mass-mailer =

This Wordpress plugin installs a **a new button** visible to WP editors that can export e-mail compatible HTML code of any blog page.

The e-mail uses a specific theme as a template. You can choose between all available themes to create the e-mail content.

Content formating filters can be individually activated or de-activated to ensure proper e-mail formatting.

Generated stripped-down HTML or XML code can be pasted in all major e-mail clients (desktop or web-based) or mass-mailing software.

You can customize your own e-mail template to make sure your content is rendered the way you want in all e-mail readers.

The plugin supports Timthumb to dynamically optimize embedded image sizes in your e-mails.

Base package includes .pot file for translation of the interface, and English and French versions.
The plugin can be used to display text in any Wordpress compatible language and charset.

This plugin has been tested an developed for both WordPress and WordPress MU. It is fully compatible with both.

= Active support =

Drop me a line on my [YD Export2Email plugin support site](http://www.yann.com/en/wp-plugins/yd-export2email "Yann Dubois' Export2Email Plugin for Wordpress") to report bugs, ask for a specific feature or improvement, or just tell me how you're using the plugin.

= Description en Français : =

Ce plug-in Wordpress installe un nouveau bouton qui permet d'exporter le code HTML d'une page dans un format compatible avec les logiciels d'envoi de courrier électronique.

Le courrier électronique utilise un gabarit de mise en page basé sur un thème WordPress.

On peut choisir entre les différents thèmes Wordpress disponibles pour formatter l'e-mail.

Le code XML ou HTML simplifié qui est généré peut être copié dans n'importe quel logiciel de messagerie standard compatible avec l'envoi de courriel au format HTML, ou dans les principaux outils de mass-mailing.

Vous pouvez personnaliser le gabarit de mise en page de vos e-mails pour vous assurer d'un rendu optimal dans tous les clients de messagerie.

La distribution standard inclut le fichier de traduction .pot et les versions française et anglaise.
Le plugin peut fonctionner avec n'importe quelle langue ou jeu de caractères y compris le chinois.

Ce plugin est compatible avec Wordpress et Wordpress MU.

Pour toute aide ou information en français, laissez-moi un commentaire sur le [site de support du plugin YD Export2Email](http://www.yann.com/en/wp-plugins/yd-export2email "Yann Dubois' Export2Email Plugin for Wordpress").

= Funding Credits =

Original development of this plugin has been paid for by [Wellcom.fr](http://www.wellcom.fr "Wellcom"). Please visit their site!

Le développement d'origine de ce plugin a été financé par [Wellcom.fr](http://www.wellcom.fr "Wellcom"). Allez visiter leur site !

= Translation =

If you want to contribute to a translation of this plugin, please drop me a line by e-mail or leave a comment on the [plugin's page](http://www.yann.com/en/wp-plugins/yd-export2email "Yann Dubois' Export2Email Plugin for Wordpress").
You will get credit for your translation in the plugin file and this documentation, as well as a link on this page and on my developers' blog.

== Installation ==

1. Unzip yd-export2email.zip
1. Upload the `yd-export2email` directory and all its contents into the `/wp-content/plugins/` directory of your main WP site
1. To use default e-mail template, **please copy the content of the `./theme` sub-directory into your `/wp-content/themes` directory**.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the option page in your admin interface to configure default settings

For specific installations, some more information might be found on the [YD Export2Email plugin support page](http://www.yann.com/en/wp-plugins/yd-export2email "Yann Dubois' Export2Email plugin for Wordpress")

== Frequently Asked Questions ==

= Where should I ask questions? =

http://www.yann.com/en/wp-plugins/yd-export2email

Use comments.

I will answer only on that page so that all users can benefit from the answer. 
So please come back to see the answer or subscribe to that page's post comments.

= Puis-je poser des questions et avoir des docs en français ? =

Oui, l'auteur est français.
("but alors... you are French?")

= How to use the template function? =

You can activate the "Export2Email" button either by checking the "Insert button at end of content:" checkbox and choosing the proper role access right in the options page, or by insetinf the special `yd_e2m_button()` function anywhere inside your theme file.
Like this for example: `<?php yd_e2m_button() ?>`.
The button will only appear if the user visiting the page is logged-on with the appropriate role (as define in the options page).
The button will only appear on "single post" pages.
The function supports (as yet undocumented) optional arguments to customize the wp_query.

= How do I copy/paste the exported HTML in Mozilla Thunderbird? =

Please look at the screenshots and quick tutorial here: [YD Export2Email plugin support page](http://www.yann.com/en/wp-plugins/yd-export2email "Yann Dubois' Export2Email plugin for Wordpress")

== Screenshots ==

1. The yd-export2email plugin option page
1. The yd-export2email button
1. The code export window
1. Exported code can be inserted into Mozilla Thunderbird
1. Thunderbird's HTML insertion window
1. HTML formated e-mail is ready to be sent
1. What the Wordpress post content may look like when received as a HTML e-mail 

== Revisions ==

* 0.1.0 Original beta version (2010/03)
* 0.2.0 Extended image and newsletter support (2010/04)
* 0.3.0 email.php default tempalte file support (2010/04/22)
* 0.3.1 bugfix: template function now checks role (2010/06/01)

== Changelog ==

= 0.1.0 =
* Original beta version.
= 0.2.0 =
* Now supports multi-post pages
* Now supports arbitrary wp_query (can be passed as function argument too)
* Now can do complex newsletters
* Dynamic in-line css styling of images
* Other automatin in-line css styling (taken from global css declaration at top of template file)
* Now supports Timthumb for automatic image downsizing and optimization
= 0.3.0 =
* Added auto-detection of email.php template file
* Close interaction with WP_theme_switcher plugin
= 0.3.1 =
* Bugfix: template function now checks user role before displaying button

== Upgrade Notice ==

= 0.1.0 =
* Original beta version.
= 0.2.0 =
* No data upgrade necessary. Upgrade as usual by replacing existing plugin files.
* Please check permissions on cache subdirectory if you want to use Timthumb.
= 0.3.0 =
* No data upgrade necessary. Upgrade as usual by replacing existing plugin files.
* See **Changelog** for details.
= 0.3.1 =
* No data upgrade necessary. Upgrade as usual by replacing existing plugin files.
* See **Changelog** for details.

== To Do ==

Test. Final release.


== Did you like it? ==

Drop me a line on http://www.yann.com/en/wp-plugins/yd-export2email

And... *please* rate this plugin --&gt;