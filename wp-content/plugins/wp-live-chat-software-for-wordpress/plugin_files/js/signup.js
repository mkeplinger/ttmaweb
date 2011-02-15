$(document).ready(function()
{
	var timezones = {
		'Pacific/Kwajalein': '(GMT-12:00) International Date Line West',
		'Pacific/Samoa': '(GMT-11:00) Midway Island, Samoa',
		'US/Hawaii': '(GMT-10:00) Hawaii',
		'US/Alaska': '(GMT-09:00) Alaska',
		'US/Pacific': '(GMT-08:00) Pacific Time (US & Canada)',
		'America/Tijuana': '(GMT-08:00) Tijuana, Baja California',
		'US/Arizona': '(GMT-07:00) Arizona',
		'America/Chihuahua': '(GMT-07:00) Chihuahua, La Paz, Mazatlan',
		'US/Mountain': '(GMT-07:00) Mountain Time (US & Canada)',
		'America/Chicago': '(GMT-06:00) Central America',
		'US/Central': '(GMT-06:00) Central Time (US & Canada)',
		'America/Mexico_City': '(GMT-06:00) Guadalajara, Mexico City, Monterrey',
		'Canada/Saskatchewan': '(GMT-06:00) Saskatchewan',
		'America/Bogota': '(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
		'US/Eastern': '(GMT-05:00) Eastern Time (US & Canada)',
		'US/East-Indiana': '(GMT-05:00) Indiana (East)',
		'America/Caracas': '(GMT-04:30) Caracas',
		'Canada/Atlantic': '(GMT-04:00) Atlantic Time (Canada)',
		'America/La_Paz': '(GMT-04:00) La Paz',
		'America/Manaus': '(GMT-04:00) Manaus',
		'America/Santiago': '(GMT-04:00) Santiago',
		'Canada/Newfoundland': '(GMT-03:30) Newfoundland',
		'America/Sao_Paulo': '(GMT-03:00) Brasilia',
		'America/Buenos_Aires': '(GMT-03:00) Buenos Aires',
		'America/Buenos_Aires': '(GMT-03:00) Georgetown',
		'America/Godthab': '(GMT-03:00) Greenland',
		'America/Montevideo': '(GMT-03:00) Montevideo',
		'Atlantic/South_Georgia': '(GMT-02:00) Mid-Atlantic',
		'Atlantic/Azores': '(GMT-01:00) Azores',
		'Atlantic/Cape_Verde': '(GMT-01:00) Cape Verde Is.',
		'Europe/London': '(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London',
		'Africa/Casablanca': '(GMT) Casablanca',
		'Atlantic/Reykjavik': '(GMT) Monrovia, Reykjavik',
		'Europe/Berlin': '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
		'Europe/Prague': '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
		'Europe/Paris': '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris',
		'Europe/Warsaw': '(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb',
		'Africa/Lagos': '(GMT+01:00) West Central Africa',
		'Asia/Amman': '(GMT+02:00) Amman',
		'Europe/Athens': '(GMT+02:00) Athens, Bucharest, Istanbul',
		'Asia/Beirut': '(GMT+02:00) Beirut',
		'Africa/Cairo': '(GMT+02:00) Cairo',
		'Africa/Harare': '(GMT+02:00) Harare, Pretoria',
		'Europe/Helsinki': '(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius',
		'Asia/Jerusalem': '(GMT+02:00) Jerusalem',
		'Europe/Minsk': '(GMT+02:00) Minsk',
		'Africa/Windhoek': '(GMT+02:00) Windhoek',
		'Asia/Baghdad': '(GMT+03:00) Baghdad',
		'Asia/Kuwait': '(GMT+03:00) Kuwait, Riyadh',
		'Europe/Moscow': '(GMT+03:00) Moscow, St. Petersburg, Volgograd',
		'Africa/Nairobi': '(GMT+03:00) Nairobi',
		'Asia/Tbilisi': '(GMT+03:00) Tbilisi',
		'Asia/Tehran': '(GMT+03:30) Tehran',
		'Asia/Muscat': '(GMT+04:00) Abu Dhabi, Muscat',
		'Asia/Baku': '(GMT+04:00) Baku',
		'Indian/Mauritius': '(GMT+04:00) Port Louis',
		'Asia/Yerevan': '(GMT+04:00) Yerevan',
		'Asia/Kabul': '(GMT+04:30) Kabul',
		'Asia/Yekaterinburg': '(GMT+05:00) Ekaterinburg',
		'Asia/Karachi': '(GMT+05:00) Islamabad, Karachi',
		'Asia/Tashkent': '(GMT+05:00) Tashkent',
		'Asia/Calcutta': '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
		'Asia/Calcutta': '(GMT+05:30) Sri Jayawardenepura',
		'Asia/Katmandu': '(GMT+05:45) Kathmandu',
		'Asia/Novosibirsk': '(GMT+06:00) Almaty, Novosibirsk',
		'Asia/Dhaka': '(GMT+06:00) Astana, Dhaka',
		'Asia/Rangoon': '(GMT+06:30) Yangon (Rangoon)',
		'Asia/Bangkok': '(GMT+07:00) Bangkok, Hanoi, Jakarta',
		'Asia/Krasnoyarsk': '(GMT+07:00) Krasnoyarsk',
		'Asia/Hong_Kong': '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
		'Asia/Irkutsk': '(GMT+08:00) Irkutsk, Ulaan Bataar',
		'Asia/Kuala_Lumpur': '(GMT+08:00) Kuala Lumpur, Singapore',
		'Australia/Perth': '(GMT+08:00) Perth',
		'Asia/Taipei': '(GMT+08:00) Taipei',
		'Asia/Tokyo': '(GMT+09:00) Osaka, Sapporo, Tokyo',
		'Asia/Seoul': '(GMT+09:00) Seoul',
		'Asia/Yakutsk': '(GMT+09:00) Yakutsk',
		'Australia/Adelaide': '(GMT+09:30) Adelaide',
		'Australia/Darwin': '(GMT+09:30) Darwin',
		'Australia/Brisbane': '(GMT+10:00) Brisbane',
		'Australia/Canberra': '(GMT+10:00) Canberra, Melbourne, Sydney',
		'Pacific/Guam': '(GMT+10:00) Guam, Port Moresby',
		'Australia/Hobart': '(GMT+10:00) Hobart',
		'Asia/Vladivostok': '(GMT+10:00) Vladivostok',
		'Asia/Magadan': '(GMT+11:00) Magadan, Solomon Is., New Caledonia',
		'Pacific/Auckland': '(GMT+12:00) Auckland, Wellington',
		'Pacific/Fiji': '(GMT+12:00) Fiji, Kamchatka, Marshall Is.',
		'Pacific/Tongatapu': '(GMT+13:00) Nuku\'alofa'
	};

	var date = new Date((new Date()).getFullYear(), 0, 1, 0, 0, 0, 0);
	var dateGMTString = date.toGMTString();
	var date2 = new Date(dateGMTString.substring(0, dateGMTString.lastIndexOf(" ")-1));
	var GMT = ((date - date2) / (1000 * 60 * 60)).toString();

	// Add leading 0
	if (/^\-?(\d)$/.test(GMT)) GMT = GMT.replace(/\d/, function(match, value) { return '0'+match; });

	// Select timezone based on GMT
	var re;

	if (GMT == '00') {
		re = new RegExp('\(GMT'+GMT+':00\)');
	} else if (parseInt(GMT) > 0) {
		re = new RegExp('\(GMT\\+'+GMT+':00\)');
	} else {
		re = new RegExp('\(GMT'+GMT+':00\)');
	}

	for (var i in timezones)
	{
		if (re.exec(timezones[i]))
		{
			$('input[name=livechat_account_timezone]').val(i);
			break;
		}
	};

	var validate = function(use_alert)
	{
		if ($('#livechat_account_first_name').val().length < 1)
		{
			if (use_alert) alert ('Please enter your first name.');
			return false;
		}

		if ($('#livechat_account_first_name').val().length < 1)
		{
			if (use_alert) alert ('Please enter your last name.');
			return false;
		}

		if (/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i.test($('#livechat_account_email').val()) == false)
		{
			if (use_alert) alert ('Please enter a valid email address.');
			return false;
		}

		return true;
	}

	$('#livechat_new_account form').submit(function()
	{
		if (validate(true))
		{
			$('#ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');

			var create_licence = function()
			{
				$('#ajax_message').removeClass('message').addClass('wait').html('Creating new licence&hellip;');

				var url = 'http://www.livechatinc.com/en/signup/';
				url += '?account_first_name='+encodeURIComponent($('#livechat_account_first_name').val());
				url += '&account_last_name='+encodeURIComponent($('#livechat_account_last_name').val());
				url += '&account_email='+encodeURIComponent($('#livechat_account_email').val());
				url += '&account_company='+encodeURIComponent($('#livechat_account_company').val());
				url += '&account_phone='+encodeURIComponent($('#livechat_account_phone').val());
				url += '&account_website='+encodeURIComponent($('#livechat_account_website').val());
				url += '&account_timezone='+encodeURIComponent($('#livechat_account_timezone').val());
				url += '&action=wordpress_signup';
				url += '&recaptcha_ip='+$('input[name=recaptcha_ip]').val();
				url += '&recaptcha_challenge_field='+$('input[name=recaptcha_challenge_field]').val();
				url += '&recaptcha_response_field='+$('input[name=recaptcha_response_field]').val();
				url += '&jsoncallback=?';

				$.getJSON(url,
				function(data)
				{
					data = parseInt(data.response);
					if (data == -1)
					{
						// Wrong captcha
						$('#ajax_message').html('Confirmation code is incorrect. Please try again.').addClass('message').removeClass('wait');
						return false;
					}
					if (data == 0)
					{
						$('#ajax_message').html('Could not create licence. Please try again later.').addClass('message').removeClass('wait');
						return false;
					}

					// Save new licence number
					$('#livechat_license_number').val(data);
					$('#livechat_license_created_flag').val('1');
					$('#livechat_already_have form')
						.unbind('submit')
						.submit();
				});
			}

			// Check if email address is available
			$.getJSON('http://www.livechatinc.com/php/licence_info.php?email='+$('#livechat_new_account form input[name=livechat_account_email]').val()+'&jsoncallback=?',
			function(response)
			{
				if (response.response == 'true')
				{
					create_licence();
				}
				else if (response.response == 'false')
				{
					$('#ajax_message').removeClass('wait').addClass('message').html('This email address is already in use. Please choose another e-mail address.');
				}
				else
				{
					$('#ajax_message').removeClass('wait').addClass('message').html('Could not create licence. Please try again later.');
				}
			});
		}
		return false;
	});
});