<?php
/**
 *
 * This is a free Wordpress Theme for Authors written by BackMyBook.com
 * to help authors interested in self-publishing to market themselves, 
 * their books and create a personal brand. 
 *
 * For information on marketing for authors visit: http://travelblogger.com
 *
 * Copyright (C) 2010  BackMyBook.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Author Contact: info@travelblogger.com
 * Free Wordpress Theme for Authors URL: http://travelblogger.com/free-wordpress-theme-authors
 *
 *
 * @package WordPress
 * @subpackage TravelBlogger
 * @since TravelBlogger Theme 1.0
 */

// Define directory constants
define('EXP_LIB', TEMPLATEPATH . '/lib');
define('EXP_ADMIN', EXP_LIB . '/admin');
define('EXP_FUNCTIONS', EXP_LIB . '/functions');
define('EXP_CLASSES', EXP_LIB . '/classes');

// Launch Theme within WordPress
require_once(EXP_FUNCTIONS . '/launch.php');

// Load theme functions
require_once(EXP_FUNCTIONS . '/layout.php');
require_once(EXP_FUNCTIONS . '/components.php');
require_once(EXP_FUNCTIONS . '/widgets.php');

if(is_admin()) {
	// Adds options to Appearance tab in admin area
	require_once(EXP_ADMIN .'/opt_theme_layout.php');
	require_once(EXP_ADMIN .'/opt_colors_fonts.php');
	require_once(EXP_ADMIN .'/opt_social_media.php');
	// Loads classes
	require_once(ABSPATH . 'wp-admin/custom-header.php');
	require_once(ABSPATH . 'wp-admin/custom-background.php');
	require_once(EXP_CLASSES . '/custom-background.php');
	require_once(EXP_CLASSES . '/custom-header.php');
	require_once(EXP_CLASSES . '/custom-footer.php');
	// Adds admin only functions
	require_once(EXP_FUNCTIONS .'/admin.php');
}