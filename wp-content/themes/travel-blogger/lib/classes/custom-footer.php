<?php
/**
 * The custom footer image script. Script modified to accomodate custom theme settings.
 * Class mostly written by WordPress
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * The custom footer image class.
 *
 * @since 2.1.0
 * @package WordPress
 * @subpackage Administration
 */
class EXP_Custom_Image_Footer {

	/**
	 * Holds footer link options.
	 *
	 * @var string
	 * @since 3.0.0
	 * @access private
	 */
	var $flinks = '';
	
	/**
	 * Holds the page menu hook.
	 *
	 * @var string
	 * @since 3.1.0
	 * @access private
	 */
	var $page = '';

	/**
	 * PHP4 Constructor - Register administration footer callback.
	 *
	 * @since 2.1.0
	 * @return Custom_Image_footer
	 */
	function EXP_Custom_Image_footer() {
		//What can I say...
	}

	/**
	 * Set up the hooks for the Custom footer admin page.
	 *
	 * @since 2.1.0
	 */
	function init() {
		if ( ! current_user_can('edit_theme_options') )
			return;

		$this->page = $page = add_theme_page(__('Footer' , 'travelblogger'), __('Footer' , 'travelblogger'), 'edit_theme_options', 'esp-custom-footer', array(&$this, 'admin_page'));

		add_action("admin_print_scripts-$page", array(&$this, 'js_includes'));
		add_action("admin_print_styles-$page", array(&$this, 'css_includes'));
		add_action("admin_head-$page", array(&$this, 'help') );
		add_action("admin_head-$page", array(&$this, 'take_action'), 50);
		add_action("admin_head-$page", array(&$this, 'js'), 50);
		
		$this->flinks = get_option('exp_show_footer_links');
	}

	/**
	 * Adds contextual help.
	 *
	 * @since 3.0.0
	 */
	function help() {
		add_contextual_help( $this->page, '<p>' . __( 'You can set a custom image footer for your site. Simply upload the image and crop it, and the new footer will go live immediately.' , 'travelblogger' ) . '</p>' .
		'<p>' . __( 'If you want to discard your custom footer and go back to the default included in your theme, click on the buttons to remove the custom image and restore the original footer image.' , 'travelblogger' ) . '</p>' );
	}

	/**
	 * Get the current step.
	 *
	 * @since 2.6.0
	 *
	 * @return int Current step
	 */
	function step() {
		if ( ! isset( $_GET['step'] ) )
			return 1;

		$step = (int) $_GET['step'];
		if ( $step < 1 || 3 < $step )
			$step = 1;

		return $step;
	}

	/**
	 * Set up the enqueue for the JavaScript files.
	 *
	 * @since 2.1.0
	 */
	function js_includes() {
		$step = $this->step();

		if ( 2 == $step )
			wp_enqueue_script('imgareaselect');
	}

	/**
	 * Set up the enqueue for the CSS files
	 *
	 * @since 2.7
	 */
	function css_includes() {
		$step = $this->step();
		
		wp_enqueue_style( 'exp-theme-styles', get_stylesheet_directory_uri() . '/lib/admin/css/admin.css', 997, '1.3.1', false );
		
		if ( 2 == $step )
			wp_enqueue_style('imgareaselect');
	}

	/**
	 * Execute custom footer modification.
	 *
	 * @since 2.6.0
	 */
	function take_action() {
		if ( ! current_user_can('edit_theme_options') )
			return;

		if ( !isset( $_POST['exp-custom-footer'] ) )
			return;

		$this->footer_updated = true;
		$flinks = $this->flinks;

		if ( isset( $_POST['resetfooter'] ) ) {
			check_admin_referer( 'custom-footer-options', '_wpnonce-custom-footer-options' );
			remove_theme_mod( 'footer_image' );
			return;
		}

		if ( isset( $_POST['removefooter'] ) ) {
			check_admin_referer( 'custom-footer-options', '_wpnonce-custom-footer-options' );
			set_theme_mod( 'footer_image', '' );
			return;
		}
		
		if ( isset( $_POST['hidelinks'] ) ) {
			check_admin_referer( 'custom-footer-options', '_wpnonce-custom-footer-options' );
			if ( $_POST['hidelinks'] == 1 ) {
				$flinks['credit']='show';
			} elseif ( $_POST['hidelinks'] == 0  ) {
				$flinks['credit']= '';
			}
		}
		
		if ( isset( $_POST['hide_add_links'] ) ) {
			check_admin_referer( 'custom-footer-options', '_wpnonce-custom-footer-options' );
			if ( $_POST['hide_add_links'] == 1 ) {
				$flinks['add_credit']='show';
				delete_option('exp_dont_bother');
			} elseif ( $_POST['hide_add_links'] == 0  ) {
				$flinks['add_credit']='';
			}
		}
		
		if ( isset( $_POST['copyright'] ) ) {
			check_admin_referer( 'custom-footer-options', '_wpnonce-custom-footer-options' );
			$flinks['custom_copy']=esc_attr($_POST['copyright']);
		}		
		
		update_option('exp_show_footer_links',$flinks);
		
		$this->flinks = get_option('exp_show_footer_links');
	}

	/**
	 * Execute Javascript depending on step.
	 *
	 * @since 2.1.0
	 */
	function js() {
		$step = $this->step();
		if ( 2 == $step )
			$this->js_2();
	}

	/**
	 * Display Javascript based on Step 2.
	 *
	 * @since 2.6.0
	 */
	function js_2() { ?>
		<script type="text/javascript">
		/* <![CDATA[ */
			function onEndCrop( coords ) {
				jQuery( '#x1' ).val(coords.x);
				jQuery( '#y1' ).val(coords.y);
				jQuery( '#width' ).val(coords.w);
				jQuery( '#height' ).val(coords.h);
			}

			jQuery(document).ready(function() {
				var xinit = <?php echo FOOTER_IMAGE_WIDTH; ?>;
				var yinit = <?php echo FOOTER_IMAGE_HEIGHT; ?>;
				var ratio = xinit / yinit;
				var ximg = jQuery('img#upload').width();
				var yimg = jQuery('img#upload').height();

				if ( yimg < yinit || ximg < xinit ) {
					if ( ximg / yimg > ratio ) {
						yinit = yimg;
						xinit = yinit * ratio;
					} else {
						xinit = ximg;
						yinit = xinit / ratio;
					}
				}

				jQuery('img#upload').imgAreaSelect({
					handles: true,
					keys: true,
					aspectRatio: xinit + ':' + yinit,
					show: true,
					x1: 0,
					y1: 0,
					x2: xinit,
					y2: yinit,
					maxHeight: <?php echo FOOTER_IMAGE_HEIGHT; ?>,
					maxWidth: <?php echo FOOTER_IMAGE_WIDTH; ?>,
					onInit: function () {
						jQuery('#width').val(xinit);
						jQuery('#height').val(yinit);
					},
					onSelectChange: function(img, c) {
						jQuery('#x1').val(c.x1);
						jQuery('#y1').val(c.y1);
						jQuery('#width').val(c.width);
						jQuery('#height').val(c.height);
					}
				});
			});
		/* ]]> */
		</script>
<?php
	}

	/**
	 * Display first step of custom footer image page.
	 *
	 * @since 2.1.0
	 */
	function step_1() {
?>

<div class="wrap appearance_page_custom-header" id="custom-footer">
<?php screen_icon(); ?>
<h2>Custom Footer</h2>

<?php if ( ! empty( $this->footer_updated ) ) { ?>
<div id="message" class="updated">
<p><?php printf( __( 'Footer updated. <a href="%s">Visit your site</a> to see how it looks.' , 'travelblogger' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>

<h3>Footer Image</h3>
<table class="form-table">
<tbody>


<tr valign="top">
<th scope="row">Preview</th>
<td >
	<div id="headimg" style="max-width:<?php echo FOOTER_IMAGE_WIDTH; ?>px;height:<?php echo FOOTER_IMAGE_HEIGHT; ?>px;background-image:url(<?php esc_url ( footer_image() ) ?>);"></div>
</td>
</tr>

<tr valign="top">
<th scope="row">Upload Image</th>
<td>
	<p>You can upload a custom footer image to be shown at the bottom of your site instead of the default one. On the next screen you will be able to crop the image.<br/>
	<?php printf( __( 'Images of exactly <strong>%1$d &times; %2$d pixels</strong> will be used as-is.' , 'travelblogger' ), FOOTER_IMAGE_WIDTH, FOOTER_IMAGE_HEIGHT ); ?></p>
	<form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo esc_attr( add_query_arg( 'step', 2 ) ) ?>">
	<p>
		<label for="upload">Choose an image from your computer:</label><br />
		<input type="file" id="upload" name="import" />
		<input type="hidden" name="action" value="save" />
		<?php wp_nonce_field( 'custom-footer-upload', '_wpnonce-custom-footer-upload' ) ?>
			<input type="hidden" name="exp-custom-footer" value="" />
		<input type="submit" class="button" name="upload-footer" value="<?php echo esc_attr( 'Upload' ); ?>" />
	</p>
	</form>
</td>
</tr>
</tbody>
</table>

<form method="post" action="<?php echo esc_attr( add_query_arg( 'step', 1 ) ) ?>">
<table class="form-table">
<tbody>
	<?php

	if ( get_footer_image() ) : ?>
<tr valign="top">
<th scope="row">Remove Image</th>
<td>
	<p>This will remove the footer image. You will not be able to restore any customizations.</p>
	<input type="submit" class="button" name="removefooter" value="<?php echo esc_attr( 'Remove footer Image' ); ?>" />
</td>
</tr>
	<?php endif;

	if ( defined( 'FOOTER_IMAGE' ) ) : ?>
<tr valign="top">
<th scope="row">Reset Image</th>
<td>
	<p>This will restore the original footer image. You will not be able to restore any customizations.</p>
	<input type="submit" class="button" name="resetfooter" value="<?php echo esc_attr( 'Restore Original footer Image' ); ?>" />
</td>
</tr>
	<?php endif; ?>
</tbody>
</table>

<h3>Footer Links</h3>
<table class="form-table">
<tbody>
	<tr valign="top">
		<th scope="row"><label for="copyright">Edit Copyright text</label></th>
		<td><input id="copyright" class="regular-text" type="text" value="<?php echo esc_attr(stripslashes($this->flinks['custom_copy'])) ?>" name="copyright"/>
		<br/><span class="description">Enter custom copyright text. To use defualt, simply leave this field blank.</span></td>
	</tr>
	<tr valign="top">
	<th scope="row">Display Sponsored Links</th>
	<td>
		<p>
		<label><input type="radio" value="1" name="hide_add_links" id="hidelinks"<?php checked( ( !empty( $this->flinks['add_credit'] ) )  ? true : false ); ?> /> Yes</label>
		<label><input type="radio" value="0" name="hide_add_links" id="showlinks"<?php checked( ( empty( $this->flinks['add_credit'] ) ) ? true : false ); ?> /> No</label>
		</p>
		<p>If you like this theme, please consider activating these sponsored links as this will greatly help our ability to continue to support this free theme. These links only appear on a small number of pages, and are placed in an unobtrusive area of the footer. Thank you</p>
	</td>
	</tr>
</tbody>
</table>
<?php wp_nonce_field( 'custom-footer-options', '_wpnonce-custom-footer-options' ); ?>
<p class="submit"><input type="submit" class="button-primary" name="save-footer-options" value="<?php echo esc_attr( 'Save Changes' ); ?>" /></p>
<input type="hidden" name="exp-custom-footer" value="" />
</form>
</div>

<?php }

	/**
	 * Display second step of custom footer image page.
	 *
	 * @since 2.1.0
	 */
	function step_2() {
		check_admin_referer('custom-footer-upload', '_wpnonce-custom-footer-upload');
		
		if ( empty($_FILES) || !isset($_POST['exp-custom-footer']))
			return;
		
		$overrides = array('test_form' => false);
		$file = wp_handle_upload($_FILES['import'], $overrides);

		if ( isset($file['error']) )
			wp_die( $file['error'],  __( 'Image Upload Error' , 'travelblogger' ) );

		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);

		// Construct the object array
		$object = array(
		'post_title' => $filename,
		'post_content' => $url,
		'post_mime_type' => $type,
		'guid' => $url);

		// Save the data
		$id = wp_insert_attachment($object, $file);

		list($width, $height, $type, $attr) = getimagesize( $file );

		if ( $width == FOOTER_IMAGE_WIDTH && $height == FOOTER_IMAGE_HEIGHT ) {
			// Add the meta-data
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );

			set_theme_mod('footer_image', esc_url($url));
			do_action('wp_create_file_in_uploads', $file, $id); // For replication
			return $this->finished();
		} elseif ( $width > FOOTER_IMAGE_WIDTH ) {
			$oitar = $width / FOOTER_IMAGE_WIDTH;
			$image = wp_crop_image($file, 0, 0, $width, $height, FOOTER_IMAGE_WIDTH, $height / $oitar, false, str_replace(basename($file), 'midsize-'.basename($file), $file));
			if ( is_wp_error( $image ) )
				wp_die( __( 'Image could not be processed.  Please go back and try again.' , 'travelblogger' ), __( 'Image Processing Error' , 'travelblogger' ) );

			$image = apply_filters('wp_create_file_in_uploads', $image, $id); // For replication

			$url = str_replace(basename($url), basename($image), $url);
			$width = $width / $oitar;
			$height = $height / $oitar;
		} else {
			$oitar = 1;
		}
		?>

<div class="wrap">
<h2>Crop footer Image</h2>

<form method="post" action="<?php echo esc_attr(add_query_arg('step', 3)); ?>">
	<p class="hide-if-no-js">Choose the part of the image you want to use as your footer.</p>
	<p class="hide-if-js"><strong>You need Javascript to choose a part of the image.</strong></p>

	<div id="crop_image" style="position: relative">
		<img src="<?php echo esc_url( $url ); ?>" id="upload" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
	</div>

	<p class="submit">
	<input type="hidden" name="x1" id="x1" value="0"/>
	<input type="hidden" name="y1" id="y1" value="0"/>
	<input type="hidden" name="width" id="width" value="<?php echo esc_attr( $width ); ?>"/>
	<input type="hidden" name="height" id="height" value="<?php echo esc_attr( $height ); ?>"/>
	<input type="hidden" name="attachment_id" id="attachment_id" value="<?php echo esc_attr( $id ); ?>" />
	<input type="hidden" name="oitar" id="oitar" value="<?php echo esc_attr( $oitar ); ?>" />
	<?php wp_nonce_field( 'custom-footer-crop-image' ) ?>
	<input type="submit" class="button-primary" value="<?php echo esc_attr( 'Crop and Publish' ); ?>" />
	</p>
	<input type="hidden" name="exp-custom-footer" value="" />
</form>
</div>
		<?php
	}

	/**
	 * Display third step of custom footer image page.
	 *
	 * @since 2.1.0
	 */
	function step_3() {
		check_admin_referer('custom-footer-crop-image');
		if ( $_POST['oitar'] > 1 ) {
			$_POST['x1'] = $_POST['x1'] * $_POST['oitar'];
			$_POST['y1'] = $_POST['y1'] * $_POST['oitar'];
			$_POST['width'] = $_POST['width'] * $_POST['oitar'];
			$_POST['height'] = $_POST['height'] * $_POST['oitar'];
		}

		$original = get_attached_file( $_POST['attachment_id'] );

		$cropped = wp_crop_image($_POST['attachment_id'], $_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height'], FOOTER_IMAGE_WIDTH, FOOTER_IMAGE_HEIGHT);
		if ( is_wp_error( $cropped ) )
			wp_die( __( 'Image could not be processed.  Please go back and try again.' , 'travelblogger' ), __( 'Image Processing Error' , 'travelblogger' ) );

		$cropped = apply_filters('wp_create_file_in_uploads', $cropped, $_POST['attachment_id']); // For replication

		$parent = get_post($_POST['attachment_id']);
		$parent_url = $parent->guid;
		$url = str_replace(basename($parent_url), basename($cropped), $parent_url);

		// Construct the object array
		$object = array(
			'ID' => $_POST['attachment_id'],
			'post_title' => basename($cropped),
			'post_content' => $url,
			'post_mime_type' => 'image/jpeg',
			'guid' => $url
		);

		// Update the attachment
		wp_insert_attachment($object, $cropped);
		wp_update_attachment_metadata( $_POST['attachment_id'], wp_generate_attachment_metadata( $_POST['attachment_id'], $cropped ) );

		set_theme_mod('footer_image', $url);

		// cleanup
		$medium = str_replace(basename($original), 'midsize-'.basename($original), $original);
		@unlink( apply_filters( 'wp_delete_file', $medium ) );
		@unlink( apply_filters( 'wp_delete_file', $original ) );

		return $this->finished();
	}

	/**
	 * Display last step of custom footer image page.
	 *
	 * @since 2.1.0
	 */
	function finished() {
		$this->footer_updated = true;
		$this->step_1();
	}

	/**
	 * Display the page based on the current step.
	 *
	 * @since 2.1.0
	 */
	function admin_page() {
		if ( ! current_user_can('edit_theme_options') )
			wp_die(__('You do not have permission to customize footers.' , 'travelblogger'));
		$step = $this->step();
		if ( 1 == $step )
			$this->step_1();
		elseif ( 2 == $step )
			$this->step_2();
		elseif ( 3 == $step )
			$this->step_3();
	}

}
?>