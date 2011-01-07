<?php if (is_user_logged_in() && current_user_can("access_s2member_level4")){ ?>
	Some premium content for Level 4 Members.
<?php } else if (is_user_logged_in() && current_user_can("access_s2member_level3")){ ?>
	Some premium content for Level 3 Members.
<?php } else if (is_user_logged_in() && current_user_can("access_s2member_level2")){ ?>
	Some premium content for Level 2 Members.
<?php } else if (is_user_logged_in() && current_user_can("access_s2member_level1")){ ?>
	Some premium content for Level 1 Members.
<?php } else if (is_user_logged_in() && current_user_can("access_s2member_level0")){ ?>
	Some content for Free Subscribers.
<?php } else { ?>
	Some public content.
<?php } ?>