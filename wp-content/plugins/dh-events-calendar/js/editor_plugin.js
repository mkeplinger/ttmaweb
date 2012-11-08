// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('dhec');

	tinymce.create('tinymce.plugins.dhec', {
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('mcedhec', function() {
                            ed.execCommand('mceInsertContent', false, '[dhecdatepicker]');
			});

			// Register example button
			ed.addButton('dhec', {
				title : 'Dhec',
				cmd : 'mcedhec',
				image : url + '/dhec.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('dhec', n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'dhec',
					author 	  : 'Diego Hincapie',
					authorurl : 'http://diegojesushincapie.wordpress.com',
					infourl   : 'http://diegojesushincapie.wordpress.com',
					version   : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('dhec', tinymce.plugins.dhec);
})();