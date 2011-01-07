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
Function for determing whether $user has a built-in WP Role.
One of: (administrator|editor|author|contributor).
By default, this excludes the "subscriber" role.
*/
if (!function_exists ("ws_plugin__s2member_user_has_wp_role"))
	{
		function ws_plugin__s2member_user_has_wp_role ($user = FALSE, $exclude = array ("subscriber"))
			{
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__s2member_before_user_has_wp_role", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				if (!is_object ($user) || !is_array ($user->roles) || empty ($user->roles))
					return apply_filters ("ws_plugin__s2member_user_has_wp_role", false, get_defined_vars ());
				/**/
				$exclude = (array)$exclude; /* Force array on the exclusions. */
				/**/
				if (in_array ("administrator", $exclude) || !in_array ("administrator", $user->roles))
					if (in_array ("editor", $exclude) || !in_array ("editor", $user->roles))
						if (in_array ("author", $exclude) || !in_array ("author", $user->roles))
							if (in_array ("contributor", $exclude) || !in_array ("contributor", $user->roles))
								if (in_array ("subscriber", $exclude) || !in_array ("subscriber", $user->roles))
									return apply_filters ("ws_plugin__s2member_user_has_wp_role", false, get_defined_vars ());
				/**/
				return apply_filters ("ws_plugin__s2member_user_has_wp_role", true, get_defined_vars ());
			}
	}
?>