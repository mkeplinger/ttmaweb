(function() {
	tinymce.create('tinymce.plugins.VSW_Shortcode_Plugin', {

		init : function(ed, url) {
			ed.addCommand('mceVSW', function() {
				ed.windowManager.open({
					file : url + '/vsw_dialog.htm',
					width : 550 + parseInt(ed.getLang('vsw.delta_width', 0)),
					height : 230 + parseInt(ed.getLang('vsw.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					some_custom_arg : 'custom arg' // Custom argument
				});
			});


			ed.addButton('vsw', {
				title : 'insert video shortcode',
				cmd : 'mceVSW',
				image : url + '/vsw.gif'
			});


			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('vsw', n.nodeName == 'Video');
			});
		},

		createControl : function(n, cm) {
			return null;
		},

		
		getInfo : function() {
			return {
				longname : 'Video Sidebar Widgets Shortcode Plugin',
				author   :  'Denzel',
				authorurl : 'http://denzeldesigns.com',
				infourl : 'http://denzeldesigns.com',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('vsw', tinymce.plugins.VSW_Shortcode_Plugin);
})();