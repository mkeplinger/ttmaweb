jQuery(document).ready(function($){
	
	$('.wrap').wrapInner('<div class="cfm_col_left" />');
	$('.wrap').wrapInner('<div class="cfm_cols" />');
	//$('.cfm_col_left div.clear').remove();
	$('.cfm_col_right').removeClass('hidden').prependTo('.cfm_cols');
	
});

