<?php
	
global $post;

// Use nonce for verification
echo '<input type="hidden" name="ei_noncename" id="ei_noncename" value="' .wp_create_nonce('ei-n'). '" />';

// get options
$location = $this->get_cfm_location($post->ID);

?>
<table class="cfm_options">
	<tr>
		<td class="label" style="width:70px;"><label for="post_type">Post Type's</label></td>
		<td><input type="text" id="post_type" value="<?php echo $location['post_type']; ?>" name="cmf[location][post_type]" /><p class="description">eg. post, page</p></td>
	</tr>
	<tr>
		<td class="label"><label for="page_slug">Page Slug's</label></td>
		<td><input type="text" id="page_slug" value="<?php echo $location['page_slug']; ?>" name="cmf[location][page_slug]" /><p class="description">eg. home, about-us</p></td>
	</tr>
	<tr>
		<td class="label"><label for="post_id">Post ID's</label></td>
		<td><input type="text" id="post_id" value="<?php echo $location['post_id']; ?>" name="cmf[location][post_id]" /><p class="description">eg. 1, 2, 3</p></td>
	</tr>
	</tr>
	<tr>
		<td class="label"><label for="template_name">Page Template's</label></td>
		<td><input type="text" id="template_name" value="<?php echo $location['page_template']; ?>" name="cmf[location][page_template]" />
		<p class="description">eg. home_page.php</p></td>
	</tr>
</table>
