jQuery(document).ready(function () {
	jQuery('.layout').click( function($) {
		jQuery('.layout').removeClass('checked');
		jQuery('.radio-layout').removeAttr('checked','checked')
		jQuery(this).toggleClass('checked');
		jQuery(this).next('.radio-layout').attr('checked','checked');
	})
	jQuery('.colors').click( function($) {
		jQuery('.colors').removeClass('checked');
		jQuery('.radio-colors').removeAttr('checked','checked')
		jQuery(this).toggleClass('checked');
		jQuery(this).next('.radio-colors').attr('checked','checked');
	})
});