if (!$) $ = jQuery;
$(document).ready(function()
{
	var show_form = function()
	{
		if ($('#choice_account_0').is(':checked'))
		{
			$('#livechat_already_have').hide();
			$('#livechat_new_account').show();
		}
		else if ($('#choice_account_1').is(':checked'))
		{
			$('#livechat_new_account').hide();
			$('#livechat_already_have').show();
		}
	}

	show_form();
	$('#choice_account input').click(show_form);

	// Control Panel iframe height
	var cp = $('#control_panel');
	if (cp.length)
	{
		var cp_resize = function()
		{
			var cp_height = window.innerHeight ? window.innerHeight : $(window).height();
			cp_height -= $('#wphead').height();
			cp_height -= $('#updated-nag').height();
			cp_height -= $('#control_panel + p').height();
			cp_height -= $('#footer').height();
			cp_height -= 70;

			cp.attr('height', cp_height);
		}
		cp_resize();
		$(window).resize(cp_resize);
	}

});