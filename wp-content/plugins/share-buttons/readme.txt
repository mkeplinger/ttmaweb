=== Social Share Buttons for WordPress ===
Contributors: Loskutnikov Artem (artlosk)
Donate link: http://artlosk.com/donate/
Tags: social, network, jquery, share buttons, social buttons, twitter, facebook, vkontakte, odnoklassniki, mail.ru
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 2.2

The Share buttons, it is plugin for social networks. The plugin supports 5 social networking.

== Description ==

The Share buttons, it is plugin for social networks. The plugin supports 5 social networking. 
There are Vkontakte, Odnoklassniki.ru, Mail.ru russian social buttons and there are Facebook, Twitter english social buttons. 
Settings include show/hide buttons, sort buttons, position buttons and e.t.c.
This plugin is written using AJAX and Jquery. The plugin supports 2 languages: English and Russian

[FAQ](http://artlosk.com/2010/12/social-share-buttons-ver-2-0/), but on russian language

== Languages ==

* English - default
* Russina(ru_RU)

== Installation ==

1. Unzip the file
2. Upload it to wp-content/plugins
3. Activate it from the plugins section
4. Go to the FTP "wp-content/plugins/share_buttons/upload/ and change chmod folder "/UPLOADS/". Your logo will store in this folder.
NOTE:
or upload via FTP manually

== Screenshots ==
1. Screenshot 1: Box for upload your logo
2. Screenshot 2: Sorting Share Buttons via "Drag and Drop" method
3. Screenshot 3: Style buttons (01,02 - original, 03-08 my style)
4. Screenshot 4: Enable/Disbale Share Buttons
5. Screenshot 5: Position Share Buttons
6. Screenshot 6: Other Settings
7. Screenshot 7: Like Buttons (Vkontakte)
8. Screenshot 8: Like Buttons (Facebook)
9. Screenshot 9: Like Buttons (Mail.ru)

== History URL ==

Third post about plugin ver 2.0   : http://artlosk.com/2010/12/social-share-buttons-ver-2-0/

Second post about plugin ver. 1.2 : http://artlosk.com/2010/11/social-share-buttons-ver-1-2/

First post about plugin ver. 1.0  : http://artlosk.com/2010/10/social-share-buttons/

== Changelog ==

[2.2]

Fixed output "Like button for Vkontakte" when displayed in loop of posts. 
Now the button's container with a unique ID, for example &lt;div&gt; id='vk_like_$post->ID'>&lt;/div&gt;

[2.1]

Fixed URL for page

[2.0]

- Fixed upload file "logo.png"
- Fixed output description and title
- Fixed Facebook button with count
- Optimized plugin
- Change interface
- Change structure folder, files and php code
- Add fieldset "Sorting buttons"
- Add 6 pack icons
- Add Mail.ru button "Like"
- Add Share Buttons for Frontend(Home page)

[1.2.2]

Fixed get url for twitter, mailru buttons.
"$url = get_permalink($post->ID);"

[1.2.1]
Fixed output <meta>. Output without html.

if upload logo is failed, please, change chmod folder "uploads" and chmod file "logo.png" to upload logo for stie
or upload via FTP manually (this script while in the process)

[1.2]
 - Previously, the plugin inscribe title and description, which was introduced Platinum SEO Pack plugin and like it. Now the plugin takes from post and cuts 300 characters to description. <meta name="description" content="$description" />
 - Optimized scripts social networking.

[1.1]
Fixed upload logo

[1.0]
Plugin release.

== Feature ==
- Add many share buttons
- Create special site for this plugin