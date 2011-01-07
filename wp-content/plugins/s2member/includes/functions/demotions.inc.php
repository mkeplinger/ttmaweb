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
Forces a specific Role to demote to; whenever a Member is demoted in one way or another.
Use by PayPal® IPN routines, and also by the Auto-EOT system.
*/
if (!function_exists ("ws_plugin__s2member_force_demotion_role"))
	{
		function ws_plugin__s2member_force_demotion_role ($demotion_role = FALSE)
			{
				do_action ("ws_plugin__s2member_before_force_demotion_role", get_defined_vars ());
				/**/
				return apply_filters ("ws_plugin__s2member_force_demotion_role", ($demotion_role = "subscriber"), get_defined_vars ());
			}
	}
?>