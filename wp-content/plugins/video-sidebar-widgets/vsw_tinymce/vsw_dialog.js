var VSWDialog = {
	init : function() {
	},

	insert : function() {
		// Insert the contents from the input into the document
		var VSW_ShortCode = '[vsw id="'+document.forms[0].vsw_id.value+'" source="'+document.forms[0].vsw_source.value+'"';
		    VSW_ShortCode +=' width="'+document.forms[0].vsw_width.value+'" height="'+document.forms[0].vsw_height.value+'"';
		    VSW_ShortCode +=' autoplay="'+document.forms[0].vsw_autoplay.value+'"]';
		tinyMCEPopup.editor.execCommand('mceInsertRawHTML', false, VSW_ShortCode);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(VSWDialog.init, VSWDialog);

/*** MODEL Shortcode

[vsw id="123456" source="vimeo" width="400" height="300" autoplay="no"]

***/