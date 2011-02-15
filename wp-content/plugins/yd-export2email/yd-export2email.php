<?php
/**
 * @package YD_Export2email
 * @author Yann Dubois
 * @version 0.3.1
 */

/*
 Plugin Name: YD Export to e-mail Wordpress plugin
 Plugin URI: http://www.yann.com/en/wp-plugins/yd-export2email
 Description: Installs a new button visible to WP editors that can export e-mail compatible HTML code of any blog page. | Funded by <a href="http://www.wellcom.fr">Wellcom</a>.
 Author: Yann Dubois
 Version: 0.3.1
 Author URI: http://www.yann.com/
 */

/**
 * @copyright 2010  Yann Dubois  ( email : yann _at_ abc.fr )
 *
 *  Original development of this plugin was kindly funded by http://www.wellcom.fr
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 Revision 0.3.1:
 - Bugfix: template function now checks user role (thanks to Chris Bourne for noticeing)
 Revision 0.3.0:
 - Added auto-detection of email.php template file
 - Close interaction with WP_theme_switcher plugin
 Revision 0.2.0:
 - Added support of multi-post pages
 - Added support of arbitrary wp-query
 - Function options
 - Can now do newsletters
 - Better image styling
 - Automatic inline styling
 - Timthumb support
 Revision 0.1.0:
 - Original beta release
 */
/**
 *	TODO:
 *  - Test and final release
 */

/** Install or reset plugin defaults **/
function yd_exportemail_reset( $force ) {
	/** Init values **/
	$yd_exportemail_version		= "0.3.1";
	$default_bottomlink		= 'http://www.wellcom.fr/';
	$default_bottomtext		= '<br/><small>[&rarr;visit www.wellcom.fr]</small>';
	$newoption				= 'widget_yd_exportemail';
	$newvalue				= '';
	$prev_options = get_option( $newoption );
	if( ( isset( $force ) && $force ) || !isset( $prev_options['plugin_version'] ) ) {
		// those default options are set-up at plugin first-install or manual reset only
		// they will not be changed when the plugin is just upgraded or deactivated/reactivated
		$newvalue['plugin_version'] = $yd_exportemail_version;
		$newvalue[1]['home_bottomlink'] = $default_bottomlink;
		$newvalue[1]['home_bottomtext'] = $default_bottomtext;
		$newvalue[0]['button_text'] 		= '-&gt;Export2Email';
		$newvalue[0]['role'] 				= 'editor';
		$newvalue[0]['default_template']	= 'Export2Email';
		$newvalue[0]['filters']				= array();
		$newvalue[0]['strip_square']		= 1;
		$newvalue[0]['insert_button']		= 1;
		$newvalue[0]['element_styling']		= 1;
		$newvalue[0]['image_processing']	= 1;
		$newvalue[0]['image_urlencode']		= 1;
		$newvalue[0]['image_timthumb']		= 0;
		$newvalue[0]['email_php']			= 0;
		$newvalue[0]['timthumb_path']		= get_bloginfo('url') . "/" . PLUGINDIR . '/'
					 . dirname( plugin_basename( __FILE__ ) ) . '/'
					 . 'timthumb.php';
		if( $prev_options ) {
			update_option( $newoption, $newvalue );
		} else {
			add_option( $newoption, $newvalue );
		}
	}
}
register_activation_hook(__FILE__, 'yd_exportemail_reset');

/** Create Text Domain For Translations **/
add_action('init', 'yd_exportemail_textdomain');
function yd_exportemail_textdomain() {
	$plugin_dir = basename( dirname(__FILE__) );
	load_plugin_textdomain(
		'yd-export2email',
		PLUGINDIR . '/' . dirname( plugin_basename( __FILE__ ) ),
		dirname( plugin_basename( __FILE__ ) )
	); 
}

/** Create custom admin menu page **/
add_action('admin_menu', 'yd_exportemail_menu');
function yd_exportemail_menu() {
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	$role_to_level= array(
	    'subscriber' => 0,
		'contributor' => 1,
		'author' => 2,
		'editor' => 5,
		'administrator' => 8 
	);
	$access = intval( $role_to_level[$options[0]['role']] );
	add_options_page(
		__('YD Export2email Options', 'yd-export2email'), 
		__('YD Export2email', 'yd-export2email'),
		$access,
		__FILE__,
		'yd_exportemail_options'
	);
}

function yd_exportemail_options() {
	require_once('admin.php');
	echo '<div class="wrap">';
	echo '<div style="float:right;">'
	. '<img src="http://www.yann.com/yd-exportemail-v031-logo.gif" alt="YD logo" />'
	. '</div>';
	if( isset( $_GET["do"] ) ) {
		//echo '<p>' . __('Action:', 'yd-export2email') . ' '
		//. __( 'I should now', 'yd-export2email' ) . ' ' . __( $_GET["do"], 'yd-export2email' ) . '.</p>';
		if(	$_GET["do"] == __('Reset widget options', 'yd-export2email') ) {
			yd_exportemail_reset( 'force' );
			echo '<p>' . __('Widget options are reset', 'yd-export2email') . '</p>';
		} elseif(	$_GET["do"] == __('Update widget options', 'yd-export2email') ) {
			yd_exportemail_update_options();
			echo '<p>' . __('Widget options are updated', 'yd-export2email') . '</p>';
		} elseif(	$_GET["do"] == 'export_window' ) {
			yd_exportemail_display_window();
			return;
		}
	} else {
		echo '<p>'
		. '<a href="http://www.yann.com/en/wp-plugins/yd-export2email" target="_blank" title="Plugin FAQ">';
		echo __('Welcome to YD Export2Email Admin Page.', 'yd-export2email')
		. '</a></p>';
	}
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	echo '</div>';
	//---
	echo '<div class="wrap">';
	echo '<form method="get">';
	echo '<hr />';
	//---
	echo '<h3>' . __('YD Export2Email options:', 'yd-export2email') . '</h3>';
	echo '<table>';
	echo '<tr><td>' . __('Button text:', 'yd-export2email') .
		'</td><td><input type="text" name="yd_exportemail-button_text-0" value="' .
		htmlentities( $options[$i]["button_text"] ) . '" size="20" /></td></tr>';
	echo '<tr><td>' . __('Show button for:', 'yd-export2email') .
		'</td><td><select name="yd_exportemail-role-0">';
	wp_dropdown_roles( $options[$i]["role"] );
	echo '</select></td></tr>';
	if ( 0 != count( $themes = get_themes() ) ) {
		echo '<tr><td>' . __('Default template:', 'yd-export2email') .
			'</td><td><select name="yd_exportemail-default_template-0" id="page_template">';
		foreach( $themes as $theme ) {
			echo '<option value="'. $theme['Name'] . '" ';
			if( $options[$i]["default_template"] == $theme['Name'] ) 
				echo ' selected="selected"';
			echo '>' . $theme['Name'] . '</option>';
		}
		echo '</select></td></tr>';
	}
	echo '<tr><td valign="top">' . __('Enable content filters:', 'yd-export2email') .
		'</td><td><ul>';
	//var_dump( $options[$i]["filters"] );
	$a = yd_get_filters( 'the_content' );
	$am = array();
	foreach( $a as $prio ) $am = array_merge( $am, $prio ); 
	foreach( array_keys( $am ) as $filter ) {
		echo '<li><input type="checkbox" name="yd_exportemail-filters-0[]" value="' . $filter . '" ';
		if( is_array( $options[$i]["filters"] ) && in_array( $filter, $options[$i]["filters"] ) ) echo ' checked="checked" ';
		echo '/>';
		echo $filter . '</li>';
	}
	echo '</ul></td></tr>';
	echo '<tr><td>' . __('Strip special [!__] tags:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-strip_square-0" value="1" ';
	if( $options[$i]["strip_square"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Insert button at end of content:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-insert_button-0" value="1" ';
	if( $options[$i]["insert_button"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Auto-apply inline style to all elements:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-element_styling-0" value="1" ';
	if( $options[$i]["element_styling"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Process image styles:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-image_processing-0" value="1" ';
	if( $options[$i]["image_processing"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Url-reencode image src:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-image_urlencode-0" value="1" ';
	if( $options[$i]["image_urlencode"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Reduce images with Timthumb:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-image_timthumb-0" value="1" ';
	if( $options[$i]["image_timthumb"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';	
	echo '<tr><td>' . __('Full URL path to Timthumb:', 'yd-export2email') .
		'</td><td><input type="text" name="yd_exportemail-timthumb_path-0" value="';
	echo htmlentities( $options[$i]["timthumb_path"] ) . '" size="40" ';
	echo ' /></td></tr>';
	echo '<tr><td>' . __('Use email.php as template if exists:', 'yd-export2email') .
		'</td><td><input type="checkbox" name="yd_exportemail-email_php-0" value="1" ';
	if( $options[$i]["email_php"] == 1 ) echo ' checked="checked" ';
	echo ' /></td></tr>';	
	
	//---
	
	echo '</table><hr>';
	echo '<input type="submit" name="do" value="' . __('Update widget options', 'yd-export2email') . '"><br/>';
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';
	echo '</form></div>';
	
	//---
	echo '<div class="wrap"><form method="get">';
	echo '<input type="submit" name="do" value="' . __('Reset widget options', 'yd-export2email') . '"><br/>';
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';
	echo '</form>';
	echo '</div>';
}

/** Update display options of the options admin page **/
function yd_exportemail_update_options(){
	$to_update = Array(
		'button_text',
		'role',
		'default_template',
		'filters',
		'strip_square',
		'insert_button',
		'element_styling',
		'image_processing',
		'image_urlencode',
		'image_timthumb',
		'timthumb_path',
		'email_php'
	);
	yd_update_options_nostrip_array( 'widget_yd_exportemail', 0, $to_update, $_GET, 'yd_exportemail-' );
}

// ============================ Plugin specific functions start here =================

function yd_e2m_insert_button( $content = '' ) {
	$options = get_option( 'widget_yd_exportemail' );
	if( $options[0]['insert_button'] == 1 ) {
		if( is_single() ) {
			if( is_array( $r = yd_get_roles() ) && in_array( $options[0]['role'], $r ) )
				$content .= yd_e2m_button( false );
		}
	}
	return $content;
}
add_filter( 'the_content', 'yd_e2m_insert_button' );

function yd_e2m_button( $echo = true, $wpq = '', $url = '' ) {
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	$html = '';
	if( is_array( $r = yd_get_roles() ) && in_array( $options[0]['role'], $r ) ) {
		global $blog_id;
		global $post;
		global $wp_query;
		if( function_exists( 'get_blog_option' ) ) {
			//this is WP MU
			$blogurl = 	get_blog_option( 1, 'siteurl' );
		} else {
			$blogurl = 	get_option( 'siteurl' );
		}
		$window_url = $blogurl . '/wp-admin/options-general.php?page=yd-export2email/yd-export2email.php&do=export_window';
		if( '' == $wpq ) {
			if( is_single() && isset( $post->ID ) ) {
				$window_url .= '&bid=' . $blog_id;
				$window_url .= '&pid=' . $post->ID;
			} else {
				$window_url .= '&wpq=' . urlencode( build_query( $wp_query->query ) );
			}
		} else {
			$window_url .= '&wpq=' . urlencode( $wpq );
		}
		$window_url .= '&url=' . urlencode( 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );
		$onclick = "window.open('$window_url', 'exp2email','status=0,toolbar=0,width=800,height=600');";
		$style = 'margin: 0 0 5px 0;';
		$html .= '<div class="yde2ed">';
		$html .= '<form id="yde2ef"><input type=button class="yde2eb" value="';
		$html .= $options[0]['button_text'];
		$html .= '" onclick="' . $onclick . '" style="' . $style . '" /></form>';
		$html .= '</div>';
		if( $echo ) echo $html;
	}
	return $html;
}

function yd_get_default_theme_url() {
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	if( $theme = yd_get_theme_by( 'Name', $options[$i]["default_template"] ) ) {
		$url = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $theme['Template Dir'] );
		return $url;
	}
	return 'heu';
}

function yd_get_theme_by( $key, $value ) {
	if ( 0 != count( $themes = get_themes() ) ) {
		foreach( $themes as $theme ) {
			if( $theme[$key] == $value ) {
				return $theme;
			}
		}
	}
	return false;
}

function yd_exportemail_display_window() {
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	
	// 0.3.0: check for email.php in default theme for this page
	if( $options[$i]["email_php"] ) {
		$default_tpl = yd_check_default_email_template( $_GET["url"] );
	} else {
		$default_tpl = false;
	}
	
	echo '<div class="wrap">';
	echo '<h3>' . __('Exported e-mail XML code:', 'yd-export2email') . '</h3>';
	echo '<form>';
	if( isset( $_GET["wpq"] ) ) {
		echo __( 'WP Query', 'yd-export2email' ) . ': ';
		echo '<pre>' . $_GET["wpq"] . '</pre>';
		echo '<input type="hidden" name="wpq" value="' . preg_replace( '/"/', '&quot;', $_GET["wpq"] ) . '" />';
	} else {
		echo '<div>' . __( 'Blog id:', 'yd-export2email') . ' ';
		echo '<input type="text" name="bid" size="3" value="';
		echo ( $bid = intval( $_GET['bid'] ) ) . '" /></div>';
		echo '<div>' . __( 'Post id:', 'yd-export2email') . ' ';
		echo '<input type="text" name="pid" size="3" value="';
		echo ( $pid = intval( $_GET['pid'] ) ) . '" /></div>';
	}
	echo __( 'Page URI', 'yd-export2email' ) . 
		': <a href="' . htmlspecialchars( $_GET['url'] ) . '" target="_blank">' . 
		htmlspecialchars( $_GET['url'] ) . '</a><br/>';
	echo '<input type="hidden" name="url" value="' . preg_replace( '/"/', '&quot;', $_GET["url"] ) . '" />';
	echo '<div>';
	//echo 'tpl: ' . $options[$i]["default_template"];
	if( !$default_tpl ) {
		echo __( 'Template:', 'yd-export2email') . ' ';
		if ( 0 != count( $themes = get_themes() ) ) {
			echo '<select name="page_template" id="page_template">';
			foreach( $themes as $theme ) {
				echo '<option value="'. $theme['Name'] . '" ';
				if( $_GET["page_template"] == $theme['Name'] ) {
					echo ' selected="selected"';
					$my_theme = $theme;
				} elseif ( 	!isset( $_GET["page_template"] ) 
							&& $options[$i]["default_template"] == $theme['Name'] ) {
					echo ' selected="selected"';
					$my_theme = $theme;
				}
				echo '>' . $theme['Name'] . '</option>';
			}
			echo '</select>';
		}
		echo '<input type="submit" value="' . __( 'change', 'yd-export2email' ) . '" />';
	} else {
		$my_theme = $default_tpl;
	}
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '" />';
	echo '<input type="hidden" name="do" value="' . $_GET["do"] . '" />';
	echo '</div>';
	echo '<textarea id="ta" cols="80" rows="15">';
	if( function_exists( 'switch_to_blog' ) ) switch_to_blog( $bid );
	/*echo '<pre>';
	var_dump( $theme );
	echo '</pre>';*/
	global $wp_query;
	global $more;
	global $wp_filter;
	$more = 0;
	if( isset( $_GET["wpq"] ) ) {
		$my_q = $_GET["wpq"];
	} else {
		$my_q = 'p=' . $pid;
	}
	query_posts( $my_q );
	ob_start();
	$old_wp_filter = $wp_filter; // save filters
	remove_all_filters( 'the_content' );
	if( is_array( $options[$i]["filters"] ) ) {
		foreach( $options[$i]["filters"] as $filter ) {
			add_filter( 'the_content', $filter );
		}
	}
	include( $my_theme['Template Files'][0] );
	$wp_filter = $old_wp_filter; // restore filters
	$email = ob_get_contents();
	ob_end_clean();
	$email = yd_process_email_code( $email );
	echo $email;
	if( function_exists( 'restore_current_blog' ) ) restore_current_blog();
	echo '</textarea>';
	echo '<script type="text/javascript">';
	echo 'function selcop() {';
	echo 'document.getElementById("ta").select();';
	echo 'CopiedTxt = document.selection.createRange();';
	echo 'CopiedTxt.execCommand("Copy");';
	echo '}';
	echo 'selcop();';
	echo '</script>';
	echo '<input type="button" value="' . __( 'copy', 'yd-export2email' ) . '" onclick="selcop();" />';
	echo '</form>';
	echo '</div>';
}

function yd_check_default_email_template( $url ) {
	$theme_to_apply = yd_theme_to_apply( $url );
	$theme = yd_get_theme_by( 'Template', $theme_to_apply );
	//var_dump( $theme );
	if( is_array( $theme['Template Files'] ) ) {
		foreach( $theme['Template Files'] as $tpl ) {
			if( preg_match( '/email\.php$/', $tpl ) ) {
				$theme['Template Files'][0] = $tpl;
				return $theme;
			}
		}
	} else {
		return false;
	}
}

function yd_theme_to_apply( $url ) {
	$current_page = $url;

	// note: most of this code will only apply if the wp_theme_switcher plugin is installed.
	// otherwise this will always default to de WP default theme of the main blog.
	
	// this comes from the wp_theme_switcher plugin...
	$current_theme_data = get_theme( get_current_theme() );
	$current_theme = $current_theme_data["Template"];
	$wpts_default = get_option( 'wp_theme_switcher_default' );
	if( empty( $wpts_default ) ) {
		$theme_to_apply = $current_theme ;
	} else {
		$theme_to_apply = $wpts_default;
	}
	$wpts_settings = get_option( 'wp_theme_switcher_settings' );
	if( !$wpts_settings || !is_array( $wpts_settings ) ) {
		$wpts_settings = array();
	} else {
		$wpts_settings = stripslashes_deep( $wpts_settings );
	}
	if( count( $wpts_settings) ) {
		foreach ( $wpts_settings as $theme => $pattern ) {
			if ( !empty( $pattern ) ) {
				if( preg_match( 
						"/" . preg_replace( '|/|', '\\/', $pattern ) . "/i", 
						$current_page
					)
				) {
					$theme_to_apply = $theme;
					break;
				}
			}
		}	
	}
	return $theme_to_apply;
}

function yd_process_email_code( $xml ) {
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	if( $options[$i]["strip_square"] == 1 ) 
		$xml = preg_replace( '/\s*\[\!(.*?)\]\s*/', ' ', $xml ); 
		// remove special block tags
	if( preg_match( '|<style>(.*?)</style>|ims', $xml, $matches ) ) 
		$style = trim( preg_replace( '/\s+/', ' ', $matches[1] ) );
		
	if( $options[$i]["element_styling"] == 1 ) {
		if( preg_match_all( '|\s*(([^{}]+?)\s*{\s*(.*?)\s*})|ims', $style, $matches, PREG_PATTERN_ORDER ) ) {
			// $matches[0] will contain array of full element style definitions (untrimmed)
			// $matches[1] will contain array of full element style definitions (trimmed)
			// $matches[2] will contain array of defined element names
			// $matches[3] will contain array of style definitions strings
			// var_dump( $matches );
			global $stylestring;
			foreach( $matches[2] as $a_id => $el ) {
				//echo "-- $a_id -> $el --\n";
				if( 	preg_match( '/\s/', $el )
					||	preg_match( '/\,/', $el )
					||	preg_match( '/\./', $el )
					||	preg_match( '/\:/', $el )
				) {
					// this is no simple style, I can't deal with it (yet)
					//TODO: deal with complex styles
				} else {
					// simple style defined for a single xml element type
					// apply it to all occurences of that element
					$stylestring = $matches[3][$a_id];
					$xml = preg_replace_callback(
						'|<\s*((' . $el . ')[^>]*?)\s*/?\s*>|ims',
						'yd_el_replace_cb',
						$xml		
					);
				}
			}
		}
	}
	
	//specific image processing (Outlook does not support css image styling at all!)
	if( $options[$i]["image_processing"] == 1 ) {
		$xml = preg_replace_callback(
			'|<\s*((img)[^>]*?)\s*/?\s*>|ims',
			'yd_img_cb',
			$xml		
		);
	}
	
	//url-reencoding of image src (Thunderbird does not support utf-8 characters in src!)
	if( $options[$i]["image_urlencode"] == 1 ) {
		$xml = preg_replace_callback(
			'|<\s*((img)[^>]*?)\s*/?\s*>|ims',
			'yd_img_ue',
			$xml		
		);
	}

	//Timthumb image reduction
	if( $options[$i]["image_timthumb"] == 1 ) {
		$xml = preg_replace_callback(
			'|<\s*((img)[^>]*?)\s*/?\s*>|ims',
			'yd_img_tt',
			$xml		
		);
	}
	
	return $xml;
}

function yd_img_cb( $cb_matches ) {
	//image processing
	$my_width = 0;
	$my_height = 0;
	if( preg_match( '/style\s*=\s*"\s*([^"]+?)\s*"/i', $cb_matches[1], $matches ) ) {
		$style = $matches[1];
		//echo "-- style : $style --\n";
	} else {
		// no style to apply
		return $cb_matches[0];
	}
	//align
	if( preg_match( '/float\s*:\s*left/i', $style ) ) {
		$cb_matches[1] = preg_replace( '/align\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' align="left"';
	}
	if( preg_match( '/float\s*:\s*right/i', $style ) ) {
		$cb_matches[1] = preg_replace( '/align\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' align="right"';
	}
	//width
	if( preg_match( '/width\s*:\s*([0-9]+)\s*px/i', $style, $pxm ) ) {
		$cb_matches[1] = preg_replace( '/width\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' width="' . $pxm[1] . '"';
		$my_width = $pxm[1];
	}
	//height
	if( preg_match( '/height\s*:\s*([0-9]+)\s*px/i', $style, $pxm ) ) {
		$cb_matches[1] = preg_replace( '/height\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' height="' . $pxm[1] . '"';
		$my_height = $pxm[1];
	}
	//vspace
	if( preg_match( '/margin\s*:\s*([0-9]+)\s*px/i', $style, $pxm ) ) {
		$cb_matches[1] = preg_replace( '/vspace\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' vspace="' . $pxm[1] . '"';
	}
	//hspace
	if( preg_match( '/margin\s*:\s*[^\s]+\s+([0-9]+)\s*px/i', $style, $pxm ) ) {
		$cb_matches[1] = preg_replace( '/hspace\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' hspace="' . $pxm[1] . '"';
	}
	//border
	if( preg_match( '/border\s*:\s*([0-9]+)/i', $style, $pxm ) ) {
		$cb_matches[1] = preg_replace( '/border\s*=\s*"[^"]*"/i', '', $cb_matches[1] );
		$cb_matches[1] .= ' border="' . $pxm[1] . '"';
	}
	return '<' . $cb_matches[1] . '>';
}

function yd_img_ue( $cb_matches ) {
	//urlencode image src callback
	if( preg_match( '/src\s*=\s*"([^"]+)"/i', $cb_matches[1], $srm ) ) {
		$cb_matches[1] = str_replace( 
			$srm[1], 
			preg_replace( '|[^0-9a-z_/.:-]|ie', "urlencode( '\\0' )", $srm[1] ), 
			$cb_matches[1] 
		);
	}
	return '<' . $cb_matches[1] . '>';
}

function yd_img_tt( $cb_matches ) {
	//timthumb image callback
	$options = get_option( 'widget_yd_exportemail' );
	$i = 0;
	$timthumb_path = $options[$i]["timthumb_path"];
	$my_width = 0;
	$my_height = 0;
	//width
	if( preg_match( '/width\s*:\s*([0-9]+)\s*px/i', $cb_matches[1], $pxm ) ) {
		$my_width = $pxm[1];
	}
	//height
	if( preg_match( '/height\s*:\s*([0-9]+)\s*px/i', $cb_matches[1], $pxm ) ) {
		$my_height = $pxm[1];
	}
	if( $my_width > 0 && $my_height > 0 ) {
		$cb_matches[1] = preg_replace(
			"/src=\"([^\"]+)\"/ie",
			'"src=\"$timthumb_path?src=".' 
					. "preg_replace( '|^http://[^\/]+|', '', '\\1' )" // strip original domain (timthumb does not do external fetches)
					. '."&h=" . $my_height' 
					. '."&w=" . $my_width'
					. '."&zc=1\""',
			$cb_matches[1]
		);
	}
	return '<' . $cb_matches[1] . '>';
}

function yd_el_replace_cb( $cb_matches ) {
	//echo "-- callback --\n";
	// preg_callback function
	global $stylestring;
	if( preg_match( '/style=/i', $cb_matches[1] ) ) {
		// this element already has an inline style, so skip it w/o change
		return $cb_matches[0];
		//TODO: merge styles
	} else {
		return '<' . $cb_matches[1] . ' style="' . trim( preg_replace( '/\s+/', ' ', $stylestring ) ) . '">';
	}
}

// ============================ Generic YD WP functions ==============================

include( 'yd-wp-lib.inc.php' );

if( !function_exists( 'yd_get_roles' ) ) {
	function yd_get_roles() {
		global $wp_roles;
		$roles = array();
		if( is_object( $wp_roles ) ) {
		foreach ( $wp_roles->role_names as $role => $name ) {
			if ( current_user_can( $role ) )
				$roles[] = $role;
			}
		    return $roles;
		} else {
			return false;
		}
	}
}

if( !function_exists( 'yd_get_filters' ) ) {
	function yd_get_filters( $tag ) {
		global $wp_filter;
		return $wp_filter[$tag];
	}
}

if( !function_exists( 'yd_update_options_nostrip_array' ) ) {
	function yd_update_options_nostrip_array( $option_key, $number, $to_update, $fields, $prefix ) {
		$options = $newoptions = get_option( $option_key );
		/*echo '<pre>';
		echo 'fields: ';
		var_dump( $fields );*/
		foreach( $to_update as $key ) {
			// reset the value
			if( is_array( $newoptions[$number][$key] ) ) {
				$newoptions[$number][$key] = array();
			} else {
				$newoptions[$number][$key] = '';
			}
			/*echo $key . ': ';
			var_dump( $fields[$prefix . $key . '-' . $number] );*/
			if( !is_array( $fields[$prefix . $key . '-' . $number] ) ) {
				$value = html_entity_decode( stripslashes( $fields[$prefix . $key . '-' . $number] ) );
				$newoptions[$number][$key] = $value;
			} else {
				//it's a multi-valued field, make an array...
				if( !is_array( $newoptions[$number][$key] ) )
					$newoptions[$number][$key] = array( $newoptions[$number][$key] );
				foreach( $fields[$prefix . $key . '-' . $number] as $v )
					$newoptions[$number][$key][] = html_entity_decode( stripslashes( $v ) );	
			}
			//echo $key . " = " . $prefix . $key . '-' . $number . " = " . $newoptions[$number][$key] . "<br/>";
		}
		//echo '</pre>';
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option( $option_key, $options );
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

?>