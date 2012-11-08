<?php

$matrix = $args['args']['matrix'];

// get location
$location = $this->get_cfm_location($matrix->ID);
$options = $this->get_cfm_options($matrix->ID);

// Use nonce for verification
echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';



// get cols from matrix
$cols = $this->get_cfm_cols($matrix->ID);


// initiate rows
$rows = $this->get_cfm_rows($matrix->ID, $post->ID);

// if empty, load defaults
if(empty($rows))
{
	$rows = array(
		array('name'=>'')
	);
}

?>
<input type="hidden" name="cmf_page" value="edit" />
<table class="cfm">
	<thead>
		<tr class="">
			<td class="heading " style="width:16px;"><!-- blank --></td>
			<?php foreach($cols as $col): ?>
				<td class="heading  <?php echo $col['type']; ?>"><?php echo $col['label']; ?></td>
			<?php endforeach; ?>
			<td class="heading " style="width:16px;"><!-- blank --></td>
		</tr>
	</thead>
	<?php if($rows): $row_counter = 0; ?>
		<tbody>
		<?php foreach($rows as $row): $row_counter++; ?>
			<tr class="">
				<td class="left row_order "><?php echo $row_counter; ?></td>
				<?php if($cols): ?>
				<?php foreach($cols as $col): ?>
					<td class=" <?php echo $col['type']; ?>">
					<?php
						if($col['type'] == 'select_page')
						{
							$col['options']['select_choices'] = $this->select_page_list();
							$col['type'] = 'select';
						}
						elseif($col['type'] == 'true_false')
						{
							$col['options']['select_choices'] = array(
								array('value' => 'true', 'label' => '')
							);
							$col['type'] = 'checkbox';
						}
						elseif($col['type'] == 'select')
						{
							$array = array();
							foreach(explode("\n",$col['options']['select_choices']) as $choice)
							{
								$array[] = array('value' => trim($choice), 'label' => trim($choice));
							}
							$col['options']['select_choices'] = $array;
						}
						
						$this->create_input(array(
							'type' => $col['type'],
							'name' => 'cmf['.$matrix->ID.'][rows]['.$row_counter.']['.$col['name'].']',
							'value' => $row[$col['name']],
							'options' => $col['options'],
						)); 
					?>
					</td>
				<?php endforeach; ?>
				<?php endif; ?>
				<td class="col_remove ">
					<a href="#" class="remove_button"></a>
				</td>
			</tr>
		<?php endforeach; ?>
		
		</tbody>
	<?php endif; ?>
</table>

<a id="cfm_add_row" class="button-primary">+ add row</a>
<div class="clear"></div>


<script type="text/javascript">

jQuery(document).ready(function($){
	
	<?php if($options['row_limit']): ?>
	var row_limit = <?php echo intval($options['row_limit']); ?>;
	<?php else: ?>
	var row_limit = 99;
	<?php endif; ?>
	
	var rows_no = <?php echo count($rows); ?>;
	var post_box = $('#cfm_mb3_<?php echo $matrix->ID ?>');
	var table = post_box.find('table.cfm');
	
	post_box.find('a#cfm_add_row').unbind("click").click(function(){
		if(rows_no >= row_limit)
		{
			alert('Row limit reached!');
			return false;
		}
		rows_no++;
		
		// clone last tr
		var tr = table.children('tbody').children('tr:last').clone(true);
		var call_tiny = false;
		var id;
		
		if(tr.find('.cfm_wysiwyg'))
		{
			//alert(rows_no);
			var td = tr.find('.cfm_wysiwyg');
			var name = td.find('textarea:first').attr('name');
			
			id = 'cfm_wysiwyg_'+rows_no;
			td.html('<div class="customEditor"><textarea name="'+name+'" id="'+id+'"></textarea></div>');
			call_tiny = true;
			
		}
		
		// set last tr to blank data
		tr.find('img').remove();
		tr.find('a.remove_image').hide();
		tr.find('input[type=file]').removeClass('hide');
		
		// update names of input, textarea and all other elements that have name
		tr.find('[name]').each(function()
		{
			var name = $(this).attr('name').replace('[rows]['+(rows_no-1)+']','[rows]['+(rows_no)+']');
			$(this).attr('name', name);
			$(this).val('');
			$(this).attr('checked','');
			$(this).attr('selected','');
		});
		
		// append to table
		tr.appendTo(table);
		
		// update order numbers
		update_order_numbers();
		
		if(call_tiny)
		{
			tinyMCE.execCommand('mceAddControl', false, id);
		}
		return false;
	});
	
	table.find('a.remove_button').unbind("click").click(function(){
		rows_no--;
		if(table.children('tbody').children('tr').size() < 2) {return false;}
		// find index of button's parent tr and remove that tr
		var index = parseInt($(this).parents('tr').index()+1);
		table.children('tbody').children('tr:nth-child('+index+')').remove();
		
		// update order numbers
		update_order_numbers();
		return false;
	});
	
	function update_order_numbers(){
		table.find('td.left').each(function(i){
			$(this).html(i+1);
		});
	}
	
	// Return a helper with preserved width of cells
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
	
	table.find('tbody').sortable({
		helper: fixHelper,
		update: function(event, ui){update_order_numbers();},
		handle: 'td.row_order'
	});
	
	table.find('a.remove_image').unbind("click").click(function(){
		$(this).parents('td').find('input[type=hidden]').val('remove');
		$(this).parents('td').find('img').hide();
		$(this).parents('td').find('input[type=file]').show();
		$(this).hide();
		
		return false;
	});

	$('form#post').attr('enctype','multipart/form-data');

	var settings = {
	    mode: "specific_textareas",
	    width: "100%",
	    theme: "advanced",
	    skin: "wp_theme",
	    theme_advanced_buttons1: "bold,italic,strikethrough,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,spellchecker,|,formatselect,|,code",
	    theme_advanced_buttons2: "",
	    theme_advanced_buttons3: "",
	    theme_advanced_buttons4: "",
	    language: "en",
	    spellchecker_languages: "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv",
	    theme_advanced_toolbar_location: "top",
	    theme_advanced_toolbar_align: "left",
	    theme_advanced_statusbar_location: "bottom",
	    theme_advanced_resizing: "1",
	    theme_advanced_resize_horizontal: "",
	    dialog_type: "modal",
	    relative_urls: "",
	    remove_script_host: "",
	    convert_urls: "",
	    apply_source_formatting: "",
	    remove_linebreaks: "1",
	    gecko_spellcheck: "1",
	    entities: "38,amp,60,lt,62,gt",
	    accessibility_focus: "1",
	    tabfocus_elements: "major-publishing-actions",
	    media_strict: "",
	    paste_remove_styles: "1",
	    paste_remove_spans: "1",
	    paste_strip_class_attributes: "all",
	    wpeditimage_disable_captions: "",
	    plugins: "safari,inlinepopups,spellchecker,paste,wordpress,tabfocus"
	};
	
	// add code to tinymce
	tinyMCE.settings.theme_advanced_buttons2 = tinyMCE.settings.theme_advanced_buttons2+',code';
	
	table.find('.cfm_wysiwyg textarea').each(function(i)
	{
		// make i start from 1 to match row number
		var id = 'cfm_wysiwyg_'+(i+1);
		$(this).attr('id',id);
	
		tinyMCE.execCommand('mceAddControl', false, id);
	
	});
});
	
	

</script>