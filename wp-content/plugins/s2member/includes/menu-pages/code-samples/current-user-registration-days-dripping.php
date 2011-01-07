<?php if(S2MEMBER_CURRENT_USER_IS_LOGGED_IN_AS_MEMBER){ ?>

	This is some content that will be displayed to all Members.
	
	<?php if(S2MEMBER_CURRENT_USER_REGISTRATION_DAYS >= 30){ ?>
		Drip content to Members who are more than 30 days old.
	<?php } ?>
	
	<?php if(S2MEMBER_CURRENT_USER_REGISTRATION_DAYS >= 60){ ?>
		Drip content to Members who are more than 60 days old.
	<?php } ?>
	
	<?php if(S2MEMBER_CURRENT_USER_REGISTRATION_DAYS >= 90){ ?>
		Drip content to Members who are more than 90 days old.
	<?php } ?>

<?php } ?>