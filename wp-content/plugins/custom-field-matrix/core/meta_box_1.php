<?php

global $post;
echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';
echo '<link rel="stylesheet" media="all" type="text/css" href="'.$this->plugin_dir.'/css/cf_matrix_edit.css" />';

// hide default wp stuff
?>
<style type="text/css">
	#edit-slug-box {display: none;}
	#submitdiv #minor-publishing {display: none;}
	#message a {display: none;}
</style>
<?php


// get matrix cols
$cols = $this->get_cfm_cols($post->ID);

// if empty, load defaults
if(empty($cols))
{
	$cols = array(
		array('id'=>1, 'label'=>'Image','name'=>'url','type'=>'image','options'=>array()),
		array('id'=>2, 'label'=>'Alt Text','name'=>'alt','type'=>'text','options'=>array()),
		array('id'=>3, 'label'=>'Caption','name'=>'caption','type'=>'textarea','options'=>array()),
	);
}

?>
	
	<input type="hidden" name="cmf_page" value="create" />
	<div class="padding_wrapper">
	<table class="cfm">
		<tbody>
		<tr class="label">
			<td class="left cfm" style="width:70px;">Col Label</td>
			<?php foreach($cols as $col):  ?>
				<td class="heading ">
					<?php $this->create_input(array(
						'type' => 'text',
						'name' => 'cmf[cols]['.$col['id'].'][label]',
						'value' => $col['label']
					)); ?>
				</td>
			<?php endforeach; ?>
		</tr>
		<tr class="name ">
			<td class="left ">Col Name</td>
			<?php foreach($cols as $col):  ?>
				<td class="">
					<?php $this->create_input(array(
						'type' => 'text',
						'name' => 'cmf[cols]['.$col['id'].'][name]',
						'value' => $col['name']
					)); ?>
				</td>
			<?php endforeach; ?>
		</tr>
		<tr class="type ">
			<td class="left ">Cell Type</td>
			<?php foreach($cols as $col):  ?>
				<td class="">
					<?php $this->create_input(array(
						'type' => 'select',
						'name' => 'cmf[cols]['.$col['id'].'][type]',
						'value' => $col['type'], 
						'options' => array('select_choices' => $this->get_cell_types())
					)); ?>
				</td>
			<?php endforeach; ?>
		</tr>
		<tr class="options ">
			<td class="left ">Cell Options</td>
			<?php foreach($cols as $col):  ?>
				<td class="">
					<div class="cell_option" id="text">
						<table class="">
						<tr class="">
							<td class="">
								no options available
							</td>
						</tr>
					</table>
					</div>
					<div class="cell_option" id="textarea">
						<table class="">
						<tr class="">
							<td class="">
								no options available
							</td>
						</tr>
					</table>
					</div>
					<div class="cell_option" id="image">
						<table class="">
						<tr class="">
							<td class="">
								no options available
							</td>
						</tr>
					</table>
					</div>
					<div class="cell_option" id="select_page">
						<table class="">
						<tr class="">
							<td class="">
								no options available
							</td>
						</tr>
					</table>
					</div>
					<div class="cell_option" id="true_false">
					<table class="">
						<tr class="">
							<td class="">
								no options available
							</td>
						</tr>
					</table>
					</div>
					<div class="cell_option" id="select">
					<table class="">
						<tr class="">
							<td class="">
								Choices
							</td>
							<td class="">
								<?php if($col['options']['select_choices'] == ''){$col['options']['select_choices'] = "option 1\noption 2\noption 3";} ?>
								<?php $this->create_input(array(
									'type' => 'textarea',
									'name' => 'cmf[cols]['.$col['id'].'][options][select_choices]',
									'value' => $col['options']['select_choices'],
								)); ?>
							</td>
						</tr>
					</table>
					</div>
					<div class="cell_option" id="wysiwyg">
					<table class="">
						<tr class="">
							<td class="">
								no options available
							</td>
						</tr>
					</table>
					</div>
				</td>
			<?php endforeach; ?>
		</tr>
		<tr class="delete ">
			<td class="left " style="border-bottom:0 none;"><!-- Delete Col --></td>
			<?php foreach($cols as $col):  ?>
				<td class="bottom ">
					<a href="#" class="remove_button"></a>
				</th>
			<?php endforeach; ?>
		</tr>
		</tbody>
	</table>
	
	<a href="#" class="add_button"></a>
	</div>
	
	<script type="text/javascript">
		
		jQuery(document).ready(function($){
			
			var cols_no = <?php echo count($cols); ?>;
			var table = $('#cfm_mb1 table.cfm');

			$('a.add_button').unbind("click").click(function()
			{
				cols_no++;
				
				//table -> tbody -> tr
				table.children().children().each(function()
				{
					var td = $(this).children('td:last-child').clone(true);
					
					// update names of input, textarea and all other elements that have name
					td.find('[name]').each(function()
					{
						var name = $(this).attr('name').replace('[cols]['+(cols_no-1)+']','[cols]['+(cols_no)+']');
						$(this).attr('name', name);
						$(this).val('');
					});
					
					// set options to text
					td.find('.cell_option').removeClass('open');
					td.find('.cell_option#text').addClass('open');
					
					td.appendTo($(this));
					
				})
				
				return false;
			});
			
			$('a.remove_button').unbind("click").click(function(){
				if(table.find('tr:first td').size() < 3) {return false;}
				
				var index = parseInt($(this).parent('td').index()+1);
				table.find('tr').each(function(){
					$(this).find('td:nth-child('+index+')').remove();
				})
				return false;
			});
			
			// open options for each col
			table.find('tr.type td select').change(function()
			{
				
				var selected = $(this).val();
				var index = parseInt($(this).parent('td').index()+1);
				
				table.find('tr.options td:nth-child('+index+') .cell_option').removeClass('open');
				table.find('tr.options td:nth-child('+index+') .cell_option#'+selected).addClass('open');

			});
			
			table.find('tr.type td select').each(function(){
				$(this).trigger('change');
			});
			
		});

	</script>
    