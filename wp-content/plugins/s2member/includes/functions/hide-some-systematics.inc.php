<?php
/*
Copyright: © 2009 WebSharks, Inc. ( coded in the USA )
<mailto:support@websharks-inc.com> <http://www.websharks-inc.com/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/
/*
Direct access denial.
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/*
Function that hides some of the systematic use pages.
Attach to: add_filter("posts_where");
*/
if (!function_exists ("ws_plugin__s2member_hide_some_systematics"))
	{
		function ws_plugin__s2member_hide_some_systematics ($where = FALSE)
			{
				global $wpdb; /* Need this to get the table name. */
				/**/
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__s2member_before_hide_some_systematics", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if (is_search ()) /* Here we exclude a few systematic use pages from the search query. */
					{
						$where .= " AND " . $wpdb->posts . ".ID NOT IN ('" . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["membership_options_page"] . "', '" . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["login_welcome_page"] . "', '" . $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["file_download_limit_exceeded_page"] . "')";
						/**/
						eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__s2member_during_hide_some_systematics", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
					}
				/**/
				return apply_filters ("ws_plugin__s2member_hide_some_systematics", $where, get_defined_vars ());
			}
	}
?>