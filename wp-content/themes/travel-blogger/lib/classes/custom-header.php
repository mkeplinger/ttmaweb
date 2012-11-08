<?php
/**
 * The custom header image script. Script modified to accomodate custom theme settings.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * The custom header image class.
 *
 */
class EXP_Custom_Image_Header extends Custom_Image_Header {

	/**
	 * Execute custom header modification.
	 *
	 * @since 2.6.0
	 */
	function take_action() {
		if ( ! current_user_can('edit_theme_options') )
			return;

		if ( !isset( $_POST['exp-custom-header'] ) )
			return;

		$this->header_updated = true;

		if ( isset( $_POST['resetheader'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
			remove_theme_mod( 'header_image' );
			return;
		}

		if ( isset( $_POST['resettext'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
			remove_theme_mod('header_textcolor');
			return;
		}

		if ( isset( $_POST['removeheader'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
			set_theme_mod( 'header_image', '' );
			return;
		}

		if ( isset( $_POST['text-color'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
			$_POST['text-color'] = str_replace( '#', '', $_POST['text-color'] );
			if ( 'blank' == $_POST['text-color'] ) {
				set_theme_mod( 'header_textcolor', 'blank' );
			} else {
				$color = preg_replace('/[^0-9a-fA-F]/', '', $_POST['text-color']);
				if ( strlen($color) == 6 || strlen($color) == 3 )
					set_theme_mod('header_textcolor', $color);
			}
		}
		
		if ( isset( $_POST['hidetext'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
			if ( $_POST['hidetext'] == 1 ) {
				update_option('exp_show_header_text','blank');
			} elseif ( $_POST['hidetext'] == 0  ) {
				delete_option('exp_show_header_text');
			}
		}
				
		if ( isset( $_POST['featured_area'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
				$featured = $_POST['featured_area'];
				update_option('exp_featured_area',$featured);
		}

		if ( isset( $_POST['default-header'] ) ) {
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
			if ( 'random-default-image' == $_POST['default-header'] ) {
				set_theme_mod( 'header_image', 'random-default-image' );
			} elseif ( 'random-uploaded-image' == $_POST['default-header'] ) {
				set_theme_mod( 'header_image', 'random-uploaded-image' );
			} else {
				$this->process_default_headers();
				$uploaded = get_uploaded_header_images();
				if ( isset( $uploaded[$_POST['default-header']] ) )
					set_theme_mod( 'header_image', esc_url( $uploaded[$_POST['default-header']]['url'] ) );
				elseif ( isset( $this->default_headers[$_POST['default-header']] ) )
					set_theme_mod( 'header_image', esc_url( $this->default_headers[$_POST['default-header']]['url'] ) );
			}
		}
	}

	/**
	 * Display first step of custom header image page.
	 *
	 * @since 2.1.0
	 */
	function step_1() {
		$this->process_default_headers();
?>

<div class="wrap">
<?php screen_icon(); ?>
<h2>Custom Header</h2>

<?php if ( ! empty( $this->header_updated ) ) { ?>
<div id="message" class="updated">
<p><?php printf( __( 'Header updated. <a href="%s">Visit your site</a> to see how it looks.' , 'travelblogger' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>

<h3>Header Image</h3>
<table class="form-table">
<tbody>


	<tr valign="top">
	<th scope="row"><?php _( 'Preview' ); ?></th>
	<td >
		<?php if ( $this->admin_image_div_callback ) {
		  call_user_func( $this->admin_image_div_callback );
		} else {
		?>
		<div id="headimg" style="max-width:<?php echo HEADER_IMAGE_WIDTH; ?>px;height:<?php echo HEADER_IMAGE_HEIGHT; ?>px;background-image:url(<?php esc_url ( header_image() ) ?>);">
			<?php
			if ( 'blank' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) || '' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) || ! $this->header_text() )
				$style = ' style="display:none;"';
			else
				$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
			?>
			<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		</div>
		<?php } ?>
	</td>
	</tr>

	<?php if ( current_theme_supports( 'custom-header-uploads' ) ) : ?>
	<tr valign="top">
	<th scope="row"><?php _( 'Upload Image' , 'travelblogger' ); ?></th>
	<td>
		<p><?php _( 'You can upload a custom header image to be shown at the top of your site instead of the default one. On the next screen you will be able to crop the image.' ); ?><br />
		<?php printf( __( 'Images of exactly <strong>%1$d &times; %2$d pixels</strong> will be used as-is.' , 'travelblogger' ), HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT ); ?></p>
		<form enctype="multipart/form-data" id="upload-form" method="post" action="<?php echo esc_attr( add_query_arg( 'step', 2 ) ) ?>">
		<p>
			<label for="upload"><?php _( 'Choose an image from your computer:' ); ?></label><br />
			<input type="file" id="upload" name="import" />
			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field( 'custom-header-upload', '_wpnonce-custom-header-upload' ) ?>
			<?php submit_button( __( 'Upload' , 'travelblogger' ), 'button', 'submit', false ); ?>
		</p>
		</form>
	</td>
	</tr>
	<?php endif; ?>
</tbody>
</table>

<form method="post" action="<?php echo esc_attr( add_query_arg( 'step', 1 ) ) ?>">
<table class="form-table">
<tbody>
		<?php if ( get_uploaded_header_images() ) : ?>
	<tr valign="top">
	<th scope="row"><?php _( 'Uploaded Images' , 'travelblogger' ); ?></th>
	<td>
		<p><?php _( 'You can choose one of your previously uploaded headers, or show a random one.' ) ?></p>
		<?php
			$this->show_header_selector( 'uploaded' );
		?>
	</td>
	</tr>
		<?php endif;
	
	if ( ! empty( $this->default_headers ) ) : ?>
<tr valign="top">
<th scope="row">Default Images</th>
<td>
	<?php //Because 3.2 changed method name, need to check for older and newer versions ?>
	<?php if(WP_VERSION < 3.2) { ?>
		<p>If you don&lsquo;t want to upload your own image, you can use one of these cool headers.</p>
		<?php
			$this->show_default_header_selector();
	} else {
		if ( current_theme_supports( 'custom-header-uploads' ) ) : ?>
			<p><?php _( 'If you don&lsquo;t want to upload your own image, you can use one of these cool headers, or show a random one.' ) ?></p>
		<?php else: ?>
			<p><?php _( 'You can use one of these cool headers or show a random one on each page.' ) ?></p>
		<?php endif;
			$this->show_header_selector( 'default' );
	} ?>
</td>
</tr>
	<?php endif;

	if ( get_header_image() ) : ?>
<tr valign="top">
<th scope="row">Remove Image</th>
<td>
	<p>This will remove the header image. You will not be able to restore any customizations.</p>
	<input type="submit" class="button" name="removeheader" value="<?php echo esc_attr( 'Remove Header Image' ); ?>" />
</td>
</tr>
	<?php endif;

	if ( defined( 'HEADER_IMAGE' ) ) : ?>
<tr valign="top">
<th scope="row">Reset Image</th>
<td>
	<p>This will restore the original header image. You will not be able to restore any customizations.</p>
	<input type="submit" class="button" name="resetheader" value="<?php echo esc_attr( 'Restore Original Header Image' ); ?>" />
</td>
</tr>
	<?php endif; ?>
</tbody>
</table>

	<?php if ( $this->header_text() ) : ?>
<h3>Header Text</h3>
<table class="form-table">
<tbody>
<tr valign="top" class="hide-if-no-js">
<th scope="row">Display Text</th>
<td>
	<p>
	<?php $hidetext = get_option('exp_show_header_text'); ?>
	<label><input type="radio" value="1" name="hidetext" id="hidetext"<?php checked( ( !empty( $hidetext ) )  ? true : false ); ?> /> No</label>
	<label><input type="radio" value="0" name="hidetext" id="showtext"<?php checked( ( empty( $hidetext ) ) ? true : false ); ?> /> Yes</label>
	</p>
</td>
</tr>

</tbody>
</table>
	<?php endif; ?>
	
<h3>Header Featured Area</h3>
<p>This theme allows you a great deal of control over how to display and use the "Featured Area" located at the top of your blog pages. This area is designed to feature a single post on the home page, category pages, or on all pages of your site - you can choose which. You can also feature one unique post on your home page, and separate posts on each of your category pages. You can set which post to display on your home page or category pages, use the "Feature Post" controls while you are editing a Post.</p>

	<table class="form-table">
	<tbody>
	<tr valign="top">
	<td>
		<p>
		<?php $featured_area = get_option('exp_featured_area'); ?>
		<label><input type="radio" value="show" name="featured_area" id="featured_area"<?php checked( ( $featured_area == 'show' )  ? true : false ); ?> /> Show Featured Area on Home &amp; Category Pages Only</label><br/>
		<label><input type="radio" value="showall" name="featured_area" id="featured_area"<?php checked( ( $featured_area == 'showall' ) ? true : false ); ?> /> Show Featured Area on All Pages</label><br/>
		<label><input type="radio" value="noshow" name="featured_area" id="featured_area"<?php checked( ( $featured_area == 'noshow' )  ? true : false ); ?> /> Hide Featured Area on All Pages</label><br/>
		</p>
	</td>
	</tr>
	</tbody>
	</table>
<div class="clear">&nbsp;</div>

<?php 
	do_action( 'custom_header_options' );
	wp_nonce_field( 'custom-header-options', '_wpnonce-custom-header-options' );
?>
<p class="submit"><input type="submit" class="button-primary" name="save-header-options" value="<?php echo esc_attr( 'Save Changes' ); ?>" /></p>
<input type="hidden" name="exp-custom-header" value="" />

<div id="color-picker" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
</form>
</div>

<?php }


}
?>