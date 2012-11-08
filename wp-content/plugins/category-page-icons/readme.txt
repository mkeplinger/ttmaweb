=== Category and Page Icons ===
Contributors: wpdevelop
Donate link: http://www.wpdevelop.com/
Tags: category icons, page icons, icons, pictures, category, page, pages, sidebar, widgets, widget icons, menu icons
Requires at least: 2.7
Tested up to: 3.0.1
Stable tag: 0.8

Easy add icons to sidebar of categories and pages. All features are flexible and ajax based.
== Description ==

Using this plugin, you can add icons (images) to sidebar of your site or blog into section of categories and pages.


Related Links:

* <a href="http://wpdevelop.com/wp-plugins/category-page-icons/" title="Category and page icons">Plugin Homepage</a>
* <a href="http://wpdevelop.com" title="Custom WordPress Plugins Development">Support and Developments of WordPress Pugins</a>

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload entire `category-page-icons` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The Plugin menu "Icons" will appear at admin menu
4. Configure Settings at the submenu settings page of Icons menu
4. Upload Icons and sign them to pages or categories. If you have at sidebar category or page sections, icons will appear there.

== Frequently Asked Questions ==

= How to add Icons to my page or category section at sidebar? =

Icons automatically appears at the sidebar, after assigning them to the pages or categories at icons admin menu.
You do not need to insert some special code for inserting icons.
Icons automatic inserting also during using these functions: <code>wp_list_pages</code>, <code>wp_list_categories</code>.

= If I am using functions <code>wp_list_pages</code>, <code>wp_list_categories</code> and do not using sidebar, are icons available ? =
Yes

== Screenshots ==

1. screenshot-0.png
2. screenshot-1.png
3. screenshot-2.png
4. screenshot-3.png
5. screenshot-4.png
6. screenshot-5.png

== Changelog ==
= 0.8 =
 * Showing all icons from apload icosn folder, do not apply size icons filter now.
 * Fixing HTTP Error, during upload icons, when icons size smaller, then width and height at settings.

= 0.7 =
 * Fixing compatibility with WordPress 3.0.1 If you have problems of showing icons. Please go to the icons settings page and check this field: �Store uploads of icons in this folder:� its have to be like this wp-content/uploads/icons but not /icons

= 0.6 =
 * Fixing error: Call to undefined function apply_bk_filter() in category-page-icons/menu-compouser.php on line 1360
= 0.5 =
 * New Professional version (include features: Position of icons at top, bottom , right or left side according to titles of pages or categories.)
 * Fixing of issue of not showing progress bar during uploading
 * Fixing compatibility with WordPress 2.9 - 2.9.1
= 0.4 =
 * Fixing of issue "Warning: is_dir() [function.is-dir]: open_basedir restriction in effect. File(/home) is not within the allowed path(s): (...) in .../wp-content/plugins/category-page-icons/menu-compouser.php on line 381"
= 0.3 =
 * Fixing of issue of not showing categories and pages
= 0.2 =
 * Fixing of issue of not showing (sometimes) icons after upload at the page and category section. ( Its was because of uploading smaller images, then sizes setted at the settings page.
= 0.1 =
 * Auto inserting icons into sidebar
 * Icons assigning to Pages
 * Icons assigning to Categories
 * Settings page for configuration icons width,  height, crop option, icons folder and more...
 * Firefox support images showing at selectbar
 * PHP4 support
 * Ajax multiple adding images
 * Ajax deleting images

== Upgrade Notice ==
= 0.8 =
 Showing all icons from apload icosn folder, do not apply size icons filter now.  Fixing HTTP Error, during upload icons, when icons size smaller, then width and height at settings.


== Arbitrary section ==

If you need customisation or support this or any other WordPress plugin, please contact me here www.wpdevelop.com