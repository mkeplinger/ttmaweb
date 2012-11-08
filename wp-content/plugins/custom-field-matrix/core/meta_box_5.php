<?php
	
global $post;

// Use nonce for verification
echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';

// get options
$options = $this->get_cfm_options($post->ID);
?>
<table class="cfm_options">
	<tr>
		<td class="label" style="width:70px;"><label for="row_limit">Row Limit</label></td>
		<td>
			<input type="text" value="<?php echo $options['row_limit']; ?>" name="cmf[options][row_limit]" /><p class="description">eg. 5</p>
		</td>
	</tr>
</table>
