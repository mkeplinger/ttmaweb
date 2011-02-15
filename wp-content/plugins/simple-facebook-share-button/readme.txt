=== Simple Facebook Share Button ===
Contributors: ethitter
Donate link: http://www.ethitter.com/plugins/simple-facebook-share-button/
Tags: share, facebook, social media, button
Requires at least: 2.7
Tested up to: 3.0.4
Stable tag: 2.0.1

Painlessly add a Facebook Share button to your posts and/or pages. Supports all button styles, manual or automatic inclusion, and shortcode.

== Description ==

Painlessly add a Facebook Share button to your posts and pages.

Features include:
*Supports all five button types, including custom button text;
*Supports placing button above or below content;
*Button can be shown on either posts (including excerpts), pages, or both;
*Includes compatibility modes for seamless theme integration;
*Allows user to override default padding options (defaults provided in admin interface);
*Button can be added via shortcode;
*Function can be added manually.

== Installation ==

1. Unzip simple-fb-share-button.zip and upload entire simple-fb-share-button folder to wp-content/plugins/.
2. Activate plugin through the Wordpress Plugins menu.
3. Set display, button, and placement options from Settings > Simple FB Share Button.
4. To manually include the Share button, see the Help page in the plugin's Settings page.

== Frequently Asked Questions ==

= After upgrading to version 2.0, the button no longer appears on my site. How can I fix this? =

The most common reason for this problem lies with your theme's footer.php file. If `wp_footer()` does not appear somewhere in footer.php, my plugin cannot add the scripts needed to render the Share button. If your theme is missing `wp_footer()`, add the following code just before the `</body>` tag: `<?php wp_footer(); ?>`.

= How Do I Manually Add Share Buttons? =

After activating the plugin, go to Settings > Simple FB Share Button and select "Plugin Help" at the top of the screen.

= Why Does My Blog's Tagline Appear Instead of a Summary of My Content? =

The Facebook Sharer uses the `<meta name="description" content="description">` tag to gather information about the content you are sharing. Some templates set the description tag to be your tagline. If you are using a plugin that creates a meta description tag from your content, such as HeadSpace or All In One SEO, you should edit your template's header.php file to remove the `<meta name="description" content="<?php bloginfo('description'); ?>">` tag.

= What are the Theme Compatibility Modes? =

In certain themes, the button may overlap other theme elements, such as comment buttons or other plugins. The plugin includes three compatibility modes to help resolve these problems with minimal effort user effort. Know that in some cases, these modes will not resolve button placement problems. In that case, a custom CSS style may be needed.

Mode 1 disables the CSS float property (a setting that allows the button to, as the name implies, float adjacent to your blog's content) and relies on the alignment of the button wrapper to place the button.

Mode 2 prevents other elements from floating adjacent to the button (using the CSS clear property).

Mode 3 combines the functions of Modes 1 and 2.

== Changelog ==

= 2.0.1 =
*Fix bug in button type setting.

= 2.0 =
*Rewrite entire plugin.
*Add option to display button on post excerpts.

= 1.0.5 =
*Fixed problem where direct implementation may fail to render correct button.

= 1.0.4 =
*Plugin pages moved to ethitter.com

= 1.0.3 =
*Fixed FB.Loader error caused by some versions of Internet Explorer.

= 1.0.1 =
*Corrected Subversion error that omitted buttons from version 1.0.

= 1.0 =
*Added shortcode;
*Removed SFBSB_do() function and replaced with SFBSB_direct();
*Added built-in help page;
*Reformatted settings page for better navigation;
*Removed redundant button style.

== Upgrade Notice ==

= 2.0.1 =
Fix bug in button type setting.

= 2.0 =
Plugin is entirely rewritten including options storage. Also added option to display button on post excerpts.

= 1.0.5 =
Fixed problem where direct implementation may fail to render correct button.

= 1.0.4 =
Plugins pages moved to ethitter.com

= 1.0.3 =
Resolved FB.Loader error exhibited by certain versions of Internet Explorer.

= 1.0.1 =

Buttons will appear in options screen and help pane.

= 1.0 =

Removed redundant button style; added new, more flexible function for direct implementation; added shortcode; reformatted settings page.