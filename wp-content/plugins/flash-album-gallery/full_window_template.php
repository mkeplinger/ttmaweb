<?php
preg_match('|^(.*?/)(wp-content)/|i', str_replace('\\', '/', __FILE__), $_m);
require_once( $_m[1] . 'wp-load.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(''); ?></title>
<link href="<?php echo plugins_url('/flash-album-gallery/admin/js/jquery.fancybox-1.3.4.css'); ?>" rel="stylesheet" type="text/css" />
<style type="text/css">
html, body { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; }
div#page, .flashalbum { width: 100%; height: 100%; position: relative; z-index: 1; }
a#backlink { position: absolute; z-index: 10; display: block; padding: 2px 5px; border: 1px solid #000; border-radius: 1px; background: #000; color: #fff; text-decoration: none; outline: none; font-size: 12px; font-family: Verdana; font-weight: bold; }
a#backlink:hover { text-decoration: none; }
a.topright { right: 30px; top: 30px; }
.flag_alternate { margin: 0 !important; }
</style>
<script language="JavaScript" src="<?php echo plugins_url('/flash-album-gallery/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo plugins_url('/flash-album-gallery/admin/js/swfobject.js'); ?>" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo plugins_url('/flash-album-gallery/admin/js/swfaddress.js'); ?>" type="text/javascript"></script>
</head>
<body>
<div id="page">
<?php
global $post;
$flag_custom = get_post_custom($post->ID);
$scode = $flag_custom["mb_scode"][0];
echo do_shortcode($scode);
?>
</div>
<script language="JavaScript" src="<?php echo plugins_url('/flash-album-gallery/admin/js/jquery.fancybox-1.3.4.pack.js'); ?>" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo plugins_url('/flash-album-gallery/admin/js/flagscroll.js'); ?>" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo plugins_url('/flash-album-gallery/admin/js/script.js'); ?>" type="text/javascript"></script>
</body>
</html>