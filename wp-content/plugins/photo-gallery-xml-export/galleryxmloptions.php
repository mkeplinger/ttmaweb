<? 
$options = get_gallery_xml_options();
extract($options);
?>

<div class="wrap">
  <h2>Photo Gallery XML Export Options</h2>
  To view your custom XML file, add "?feed=galleryxml" to the end of your feed or blog URL, depending on how your site's feeds are set up.
  <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
	<?php settings_fields('gallery_xml_group'); ?>
    <table class="form-table">
      <tr valign="top">
        <th colspan="3" scope="row"><div align="left"><br />
<br />
<strong>Check these boxes if you want to include the blog title or excerpt in the XML<br />
          --------------------------------------------------------------------------------------------<br />
        </strong></div></th>
      </tr>
      <tr valign="top">
        <th scope="row"></th>
        <td><strong>XML tag name</strong> <br/>
        (will display like this: <br />        &lt;XMLtagname&gt;content here&lt;/XMLtagname&gt;)</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <th scope="row">Title
          <?php
        $checkbox1 = get_option('checkbox1');
         if($checkbox1 == 1) {
         	echo("\n<input type=\"checkbox\" name=\"checkbox1\" value=\"1\" checked=\"checked\" />\n");
        } else {
			echo("\n<input type=\"checkbox\" name=\"checkbox1\" value=\"1\" />\n");
        }
        ?></th>
        <td><label>
          <input type="text" name="tagname6" id="tagname6" value="<?php echo get_option('tagname6'); ?>" />
        </label></td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <th scope="row">Excerpt
          <?php
        $checkbox2 = get_option('checkbox2');
         if($checkbox2 == 1) {
         	echo("\n<input type=\"checkbox\" name=\"checkbox2\" value=\"1\" checked=\"checkbox2\" />\n");
        } else {
			echo("\n<input type=\"checkbox\" name=\"checkbox2\" value=\"1\" />\n");
        }
        ?></th>
        <td><label>
          <input type="text" name="tagname7" id="tagname7" value="<?php echo get_option('tagname7'); ?>" />
        </label></td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <th scope="row">Permalink
        <?php
        $checkbox3 = get_option('checkbox3');
         if($checkbox3 == 1) {
         	echo("\n<input type=\"checkbox\" name=\"checkbox3\" value=\"1\" checked=\"checkbox3\" />\n");
        } else {
			echo("\n<input type=\"checkbox\" name=\"checkbox3\" value=\"1\" />\n");
        }
        ?></th>
        <td><input type="text" name="tagname8" id="tagname8" value="<?php echo get_option('tagname8'); ?>" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <th colspan="3" scope="row"><div align="left"><strong>Add any custom fields from your blog posts to include in the XML</strong> <br />
        --------------------------------------------------------------------------------------------</div></th>
      </tr>
      <tr valign="top">
        <th scope="row"></th>
        <td><strong>XML tag name</strong> <br/>
(will display like this: <br />
&lt;XMLtagname&gt;content here&lt;/XMLtagname&gt;)</td>
        <td><strong>Wordpress custom field name</strong><br />
Populate these in the excerpt field of your posts</td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="customfield1">Custom field 1</label>        </th>
        <td>
          <input type="text" name="tagname1" id="tagname1" value="<?php echo get_option('tagname1'); ?>" /></td>
          <td><input type="text" name="customfield1" id="customfield1" value="<?php echo get_option('customfield1'); ?>" />        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="customfield2">Custom field 2</label>        </th>
        <td>
          <input type="text" name="tagname2" id="tagname2" value="<?php echo get_option('tagname2'); ?>" /></td>
          <td><input type="text" name="customfield2" id="customfield2" value="<?php echo get_option('customfield2'); ?>" />        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="customfield3">Custom field 3</label>        </th>
        <td>
          <input type="text" name="tagname3" id="tagname3" value="<?php echo get_option('tagname3'); ?>" /></td>
          <td><input type="text" name="customfield3" id="customfield3" value="<?php echo get_option('customfield3'); ?>" />        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="tagname4">Custom field 4</label></th>
        <td><input type="text" name="tagname4" id="tagname4" value="<?php echo get_option('tagname4'); ?>" /></td>
        <td><input type="text" name="customfield4" id="customfield4" value="<?php echo get_option('customfield4'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="tagname5">Custom field 5</label></th>
        <td><input type="text" name="tagname5" id="tagname5" value="<?php echo get_option('tagname5'); ?>" /></td>
        <td><input type="text" name="customfield5" id="customfield5" value="<?php echo get_option('customfield5'); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">&nbsp;</th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <th colspan="3" scope="row"><div align="left"><strong>More options</strong> <br />
--------------------------------------------------------------------------------------------</div></th>
      </tr>
      <tr valign="top">
        <th scope="row">How many posts to display?<br />
          Defaults to 10</th>
        <td><input type="text" name="numberposts" id="numberposts" value="<?php echo get_option('numberposts'); ?>" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <th scope="row">Restrict XML to one category:<br />
          Write category name</th>
        <td><input type="text" name="categoryname" id="categoryname" value="<?php echo get_option('categoryname'); ?>" /></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <p class="submit">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="customfield1, customfield2, customfield3, tagname1, tagname2, tagname3, numberposts" />
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
</div>