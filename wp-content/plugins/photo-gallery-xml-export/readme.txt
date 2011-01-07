=== Photo Gallery XML Export ===
Contributors: Laura Gentry
Tags: photo, gallery, xml, export, flash, images, plugin
Tested up to: 2.8.4
Version: 1.2
Stable tag: trunk

The plugin generates an XML feed from your Wordpress posts using the Excerpt field, Permalink and five custom fields of your choosing. 

== Description ==

The plugin generates an XML feed from your Wordpress posts using the Excerpt field, Permalink and five custom fields of your choosing. 

It could have several uses, but it is optimized to feed data to Flash photo galleries with info from the Title and Excerpt fields as well as each post's Permalink.

I've also added the ability to include up to five custom fields (good for thumbnails) and the option of limiting to just one category. 


== Installation ==

1. Unzip and upload files to your plugins directory.
2. Activate.
3. Choose your settings in the "Photo Gallery XML Export" options page of in Wordpress admin area. 
4. To view your custom XML file, add "?feed=galleryxml" to the end of your feed URL or "/galleryxml" to the end of your blog URL, depending on how your site's feeds are set up. 

== Screenshots ==

1. Screenshot of the options page.
2. An example of the generated XML after placing "?feed=galleryxml" at the end of my blog's feed URL.


== Issues ==

1) The XML declaration is hard-coded to be: ?xml version="1.0" encoding="UTF-8"? // I don't have any plans to change that unless I get requests for other declarations.

2) You can name most XML elements however you see fit, but right now the parent element is hard-coded to "images" and each blog post parent element is hard-coded to "pic." I'm hoping to make that customizable in the future.

3) If excerpt field is selected to show but is not populated, the caption pulls from the blog post. This is actually the way Wordpress core is supposed to work, but not entirely ideal. 


== Frequently Asked Questions ==

None so far. Ask away: its.the.general@gmail.com