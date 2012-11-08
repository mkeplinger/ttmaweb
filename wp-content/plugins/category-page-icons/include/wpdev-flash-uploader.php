<?php
/**
 * Accepts file uploads from swfupload or other asynchronous upload methods.
 *
 * @package WordPress
 * @subpackage Administration
 */

     /*
     // Init refactoring for home computer
     if ( get_option( 'wpdev_mc_menu_content' ) === false  )      add_option('wpdev_mc_menu_content', get_option('mc_menu_content'));
     if ( get_option( 'wpdev_mc_menu_hints' ) === false  )      add_option('wpdev_mc_menu_hints'  , get_option('mc_menu_hints'));
     if ( get_option( 'wpdev_mc_menu_links' ) === false  )      add_option('wpdev_mc_menu_links'  , get_option('mc_menu_links'));
     if ( get_option( 'wpdev_mc_submenu_id' ) === false  )      add_option('wpdev_mc_submenu_id'  , get_option('mc_submenu_id'));
     if ( get_option( 'wpdev_mc_icon_size_w' ) === false  )      add_option('wpdev_mc_icon_size_w' , get_option( 'icon_size_w' ));
     if ( get_option( 'wpdev_mc_icon_size_h' ) === false  )      add_option('wpdev_mc_icon_size_h' , get_option( 'icon_size_h' ));
     if ( get_option( 'wpdev_mc_icon_crop' ) === false  )      add_option('wpdev_mc_icon_crop'   , get_option( 'icon_crop' ));
     if ( get_option( 'wpdev_mc_icon_dir' ) === false  )      add_option('wpdev_mc_icon_dir'    , get_option( 'icon_dir' ) ); /**/

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Check if files uploaded
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if( count($_FILES)>0 ) {

        require_once( dirname(__FILE__) . '/../../../../wp-load.php' );
        
        // Flash often fails to send cookies with the POST or upload, so we need to pass it in GET or POST instead
        if ( is_ssl() && empty($_COOKIE[SECURE_AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
                $_COOKIE[SECURE_AUTH_COOKIE] = $_REQUEST['auth_cookie'];
        elseif ( empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
                $_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];

        
        @header('Content-Type: text/html; charset=' . get_option('blog_charset'));

        require_once(ABSPATH. 'wp-admin/includes/image.php' ); // Connect image resize functions
 
        function wpdev_make_file_upload( &$file,  $dir , $url ) {

                // The default error handler.
                if (! function_exists( 'wp_handle_upload_error' ) ) { function wp_handle_upload_error( &$file, $message ) {  return array( 'error'=>$message ); } }

                // Courtesy of php.net, the strings that describe the error indicated in $_FILES[{form field}]['error'].
                $upload_error_strings = array( false,
                        __( "The uploaded file exceeds the <code>upload_max_filesize</code> directive in <code>php.ini</code>." ),
                        __( "The uploaded file exceeds the <em>MAX_FILE_SIZE</em> directive that was specified in the HTML form." ),
                        __( "The uploaded file was only partially uploaded." ),
                        __( "No file was uploaded." ),
                        '',
                        __( "Missing a temporary folder." ),
                        __( "Failed to write file to disk." ),
                        __( "File upload stopped by extension." ));

                // A successful upload will pass this test. It makes no sense to override this one.
                if ( $file['error'] > 0 ) return wp_handle_upload_error( $file, $upload_error_strings[$file['error']] );

                // A non-empty file will pass this test.
                if ( !($file['size'] > 0 ) ) return wp_handle_upload_error( $file, __( 'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini.' ));

                // A properly uploaded file will pass this test. There should be no reason to override this one.
                if (! @ is_uploaded_file( $file['tmp_name'] ) ) return wp_handle_upload_error( $file, __( 'Specified file failed upload test.' ));

                // If you override this, you must provide $ext and $type!!!!
                $test_type = true;
                $mimes = false;

                // A correct MIME type will pass this test. Override $mimes or use the upload_mimes filter.
                $wp_filetype = wp_check_filetype( $file['name'], $mimes );
                extract( $wp_filetype );
                if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) ) return wp_handle_upload_error( $file, __( 'File type does not meet security guidelines. Try another.' ));
                if ( !$ext ) $ext = ltrim(strrchr($file['name'], '.'), '.');
                if ( !$type )  $type = $file['type'];

                // A writable uploads dir will pass this test. Again, there's no point overriding this one.
                if ( ! isset( $dir) ) return wp_handle_upload_error( $file, __('Source upload directory is not set') );

                $filename = wp_unique_filename( $dir, $file['name'], null );

                // Move the file to the uploads dir
                $new_file = $dir . "/$filename";
                if ( false === @ move_uploaded_file( $file['tmp_name'], $new_file ) )  return wp_handle_upload_error( $file, sprintf( __('The uploaded file could not be moved to %s.' ), $dir ) );

                // Set correct file permissions
                $stat = stat( dirname( $new_file ));
                $perms = $stat['mode'] & 0000666;
                @ chmod( $new_file, 0766 /*$perms */);

                // Compute the URL
                $url = $url . "/$filename";

                return array( 'file' => $new_file, 'url' => $url, 'type' => $type ) ;
        }
//debuge($_REQUEST);
        ////////////

        $file = wpdev_make_file_upload($_FILES['wpdev-async-upload'],  $_REQUEST['dir_icons'],  $_REQUEST['url_icons']);

	if ( isset($file['error']) ) { echo $file['error'] ; return 0; }

        $url = $file['url'];
	$type = $file['type'];
	$full_path_file = $file['file'];


        $name = $_FILES['wpdev-async-upload']['name'];
	$name_parts = pathinfo($name);
        $ext = $name_parts['extension'];
	$title = trim( substr( $name, 0, -(1 + strlen($ext)) ) );

	$name_parts_real = pathinfo($full_path_file);
	$file_name_only = trim( substr( $name_parts_real['basename'], 0, -(1 + strlen($ext)) ) );
 

        $imagesize_original = getimagesize( $full_path_file );

        //debuge($imagesize_original, '$imagesize_original');
        //debuge($_REQUEST['size_w'], $_REQUEST["size_h"], $_REQUEST["crop"]);

        if ( ( $imagesize_original[0] < $_REQUEST['size_w']) || ( $imagesize_original[1] < $_REQUEST['size_h']) ) {
            $_REQUEST['size_w'] = $imagesize_original[0]-1;
            $_REQUEST["size_h"] = $imagesize_original[1]-1;
        }
         //debuge($_REQUEST['size_w'], $_REQUEST["size_h"], $_REQUEST["crop"]);

        $resized = image_resize( $full_path_file, $_REQUEST['size_w'], $_REQUEST["size_h"], $_REQUEST["crop"] );

        //debuge($resized);

         if (! $resized ) {
            //$_REQUEST['dir_icons'],  $_REQUEST['url_icons']
            $previos_name = $file_name_only. '.'.$ext ; 
            $renamed = $file_name_only. '-'.$_REQUEST['size_w'].'x'.  $_REQUEST["size_h"].'.'.$ext ; 
            if (! copy($_REQUEST['dir_icons'] . '/' . $previos_name, $_REQUEST['dir_icons'] . '/' . $renamed) ) {
                echo '<center><b> Cant rename file ' . $previos_name . ' to ' .  $renamed . '. <br>Its can make problem of showing resized thumb of image. <br>Please upload lager file.</b></center>';
            }
            
         } else {
              @ chmod( $resized, 0766 /*$perms */);
         }


        $result = '<div style="pading:3px;"  id="div'. $file_name_only .'" >';
        if ($resized )
            $result .= '<img style="margin: 0px 10px 8px 5px;" align="absmiddle" src="'.  $_REQUEST['url_icons'].'/'. $file_name_only. '-'.$_REQUEST['size_w'].'x'.  $_REQUEST["size_h"].'.'.$ext  .'" title="'. $title .'" alt="'. $title .'" >';
        else
            $result .= '<img style="margin: 0px 10px 8px 5px;" align="absmiddle" src="'. $url .'" title="'. $title .'" alt="'. $title .'" >';
        
        $result .= $file_name_only . '.' . $ext ;

        $result .= '<input type="hidden" class="filename" value="'.$file_name_only.'.'.$ext .'" >'.
                   '<input type="hidden" class="filedir" value="'.$_REQUEST['dir_icons'] .'" >'.
                   '<input type="hidden" class="fileicon_size" value="-'.$_REQUEST['size_w'] . 'x' . $_REQUEST['size_h'] . '.'.$ext . '" >'.
                   '<div style="float:right;padding:5px 20px;">'.
                    '<a href="#"  class="delete" onclick="//delete_this_file(\''.$file_name_only.'\', \''.$ext.'\');">' . __('Delete'). '</a>' .
                    //      '<a href="#"  onclick="document.getElementById(\'div'.$file_name_only.'\').parentNode.style.display=\'none\';//jQuery(\'#\' +  my_id).fadeOut(700);">' . __('Hide'). '</a>'.
                    '</div>'.
                    '<div class="media-item-error" ></div>'
                    ;

                    
        $result .= '</div>';
        echo $result;
        
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Getting Ajax requests
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $action = $_POST['ajax_action'];
    if ( isset($action) ) {
        //require_once( dirname(__FILE__) . '/../../../../wp-load.php' );
        //@header('Content-Type: text/html; charset=' . get_option('blog_charset'));
        switch ( $action ) :
            case 'delete-image' :

                    // get file path
                    $path = $_POST['file_name_dir'] . '/' . $_POST['file_name_org'];

                    // generate icon path
                    $name_parts = pathinfo($path);
                    $ext = $name_parts['extension'];
                    $file_name_only = trim( substr( $name_parts['basename'], 0, -(1 + strlen($ext)) ) );
                    $path_icon = $_POST['file_name_dir'] . '/' . $file_name_only . $_POST['fileicon_size']  ;

                    if (file_exists($path))  unlink( $path );

                    if (file_exists($path_icon))  unlink( $path_icon );

                    die( 1 );
                    break;

        endswitch;
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MAIN CLASS
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!class_exists('wpdev_flash_uploader')) {
    class wpdev_flash_uploader {

        var $icons_url;
        var $icons_dir;
        var $is_dir_exist;
        var $max_width;
        var $max_height;
        var $is_crop_img;
        var $sentence;

        function wpdev_flash_uploader($sentence,  $is_client = false, $is_admin = true){
            $this->is_dir_exist = false;
            $this->sentence = $sentence;
            $this->print_js();
        }


        // S E T        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // set directory where to upload images
        function set_dir($my_path ){

            $this->icons_dir = $my_path[0];
            $this->icons_url = $my_path[1];
            $this->is_dir_exist = $this->wpdev_mk_dir($this->icons_dir);

        }

        // set max width and height for croping
        function set_sizes($mw, $mh, $img_c = 1){

            $this->max_width = $mw;
            $this->max_height = $mh;
            $this->is_crop_img = $img_c;
        }



        // Main      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Print JS files at head
        function  print_js() {
            wp_print_scripts('swfupload-all');
            wp_print_scripts('swfupload-handlers');
         ?>
            <script type="text/javascript" >  wpdev_flash_uploader_path = '<?php echo '../wp-content/plugins/' . (plugin_basename(dirname(__FILE__))). '/' . basename(__FILE__); ?>'; </script>
            <script type="text/javascript" src="<?php echo plugins_url(plugin_basename(dirname(__FILE__))); ?>/wpdev-flash-uploader.js"></script>
         <?php
        }

        function upload_form(){

            $flash_action_url =  plugins_url( plugin_basename(dirname(__FILE__)) . '/' . basename( __FILE__ ) ) ;

            //$flash_action_url = admin_url('async-upload.php');
            $flash = true;
            ?>
                        <script type="text/javascript"> 
                        //<![CDATA[
                        var uploaderMode = 0;
                        jQuery(document).ready(function($){
                                uploaderMode = getUserSetting('uploader');
                                $('.upload-html-bypass a').click(function(){deleteUserSetting('uploader');uploaderMode=0;wpdev_swfuploadPreLoad();return false;});
                                $('.upload-flash-bypass a').click(function(){setUserSetting('uploader', '1');uploaderMode=1;wpdev_swfuploadPreLoad();return false;});
                        });
                        //]]>/* */
                        </script>
                        <div id="media-upload-notice"></div>
                        <div id="media-upload-error"></div>

                        <script type="text/javascript">
                        //<![CDATA[
                        var swfu;
                        SWFUpload.onload = function() {
                            var settings = {
                                    button_text: '<span class="button"><?php _e('Select Files'); ?></span>',
                                    button_text_style: '.button { text-align: center; font-weight: bold; font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; }',
                                    button_height: "24",
                                    button_width: "132",
                                    button_text_top_padding: 2,
                                    button_image_url: '<?php echo includes_url('images/upload.png'); ?>',
                                    button_placeholder_id: "flash-browse-button",
                                    upload_url : "<?php echo esc_attr( $flash_action_url ); ?>",
                                    flash_url : "<?php echo includes_url('js/swfupload/swfupload.swf'); ?>",
                                    file_post_name: "wpdev-async-upload",
                                    file_types: "<?php echo apply_filters('upload_file_glob', '*.png;*.jpg;*.gif;*.jpeg'); ?>",
                                    post_params : {
                                            "auth_cookie" : "<?php if ( is_ssl() ) echo $_COOKIE[SECURE_AUTH_COOKIE]; else echo $_COOKIE[AUTH_COOKIE]; ?>",
                                            "_wpnonce" : "<?php echo wp_create_nonce('media-form'); ?>",
                                            "short" : "0",
                                            "dir_icons" : "<?php echo str_replace('\\','/',$this->icons_dir) ; ?>",
                                            "url_icons" : "<?php echo str_replace('\\','/',$this->icons_url) ; ?>",
                                            "size_w" : "<?php echo $this->max_width; ?>",
                                            "size_h" : "<?php echo $this->max_height; ?>",
                                            "crop" : "<?php echo $this->is_crop_img; ?>"
                                    },
                                    file_size_limit : "<?php echo wp_max_upload_size(); ?>b",
                                    file_dialog_start_handler : wpdev_fileDialogStart,
                                    file_queued_handler : wpdev_fileQueued,
                                    upload_start_handler : wpdev_uploadStart,
                                    upload_progress_handler : wpdev_uploadProgress,
                                    upload_error_handler : wpdev_uploadError,
                                    upload_success_handler : wpdev_uploadSuccess,
                                    upload_complete_handler : wpdev_uploadComplete,
                                    file_queue_error_handler : wpdev_fileQueueError,
                                    file_dialog_complete_handler : wpdev_fileDialogComplete,
                                    // swfupload_pre_load_handler: swfuploadPreLoad,       // its handler just switch to flash and html upload form
                //                    swfupload_load_failed_handler: swfuploadLoadFailed,
                                    //custom_settings : {
                                    //        degraded_element_id : "html-upload-ui", // id of the element displayed when swfupload is unavailable
                                    //        swfupload_element_id : "flash-upload-ui" // id of the element displayed when swfupload is available
                                    //},
                                 //debug_handler : wpdev_debug_function,
                                 debug: false
                            };
                            swfu = new SWFUpload(settings);
                        };
                        //]]>
                        </script>

                        <div id="flash-upload-ui" style="padding:10px 25px;">
                                <?php _e( $this->sentence ); ?>:
                                <div id="flash-browse-button"></div>
                                <span><input id="cancel-upload" disabled="disabled" onclick="javscript:swfu.cancelQueue();" type="button" value="<?php esc_attr_e('Cancel Upload'); ?>" class="button" /></span>
                        </div>
                        <div id="media-items"> </div>

                        <script type="text/javascript">
                            jQuery(function($){
                                    var preloaded = $(".media-item.preloaded");
                                    if ( preloaded.length > 0 ) {
                                            preloaded.each(function(){wpdev_prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')},'');});
                                    }
                                    wpdev_updateMediaForm();
                                    post_id = 0;
                                    shortform = 1;
                            });
                        </script>
 
        <?php
        }


        // Functions /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Make    Dir    in    cickle
        function wpdev_mk_dir($path, $mode = 0777) {

            if (DIRECTORY_SEPARATOR == '/')
                $path=str_replace('\\','/',$path);
            else
                $path=str_replace('/','\\',$path);

                if ( is_dir($path) || empty($path) ) return true;   // Check if directory already exists
                if ( is_file($path) ) return false;                 // Ensure a file does not already exist with the same name

            $dirs = explode(DIRECTORY_SEPARATOR , $path);
            $count = count($dirs);
            $path = $dirs[0];
            for ($i = 1; $i < $count; ++$i) {
               if ($dirs[$i] !="") {
                    $path .= DIRECTORY_SEPARATOR . $dirs[$i];
                    if ( !is_dir($path) && ( strpos($_SERVER['DOCUMENT_ROOT'],$path)===false ) ) {
                        if (!is_dir($path) && !( mkdir($path, 0777) ) ) {
                            return false;
                        }
                        /*@ chmod( $path, 0777 );*/
                    }
                   }
            }
            return true;
        }

        // Remove dir -- U S E    V E R Y    C R E F U L L Y
        function wpdev_rm_dir($dir) {

           if (DIRECTORY_SEPARATOR == '/')
                $dir=str_replace('\\','/',$dir);
            else
                $dir=str_replace('/','\\',$dir);

            $files = glob( $dir . '*', GLOB_MARK );
            debuge($files);
            foreach( $files as $file ){
                if( is_dir( $file ) )
                    $this->wpdev_rm_dir( $file );
                else
                    unlink( $file );
            }
            rmdir( $dir );
        }

    }
}

?>
