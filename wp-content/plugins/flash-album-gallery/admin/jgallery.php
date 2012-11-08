<?php global $wpdb, $post;
$flag_options = get_option ('flag_options'); 
$siteurl = get_option ('siteurl');
$isCrawler = flagGetUserNow($_SERVER['HTTP_USER_AGENT']); // check if is a crowler
extract($altColors);
?>
<?php $bg = ($wmode == 'window')? '#'.$Background : 'transparent'; ?>
<style type="text/css">
.flashalbum { clear: both; }
<?php if(!$isCrawler) { ?>
.flag_alternate { display: none; }
.flag_alternate .flagcatlinks { padding: 7px 3px; margin:0 0 3px; background-color: #292929; }
.flag_alternate .flagcatlinks a.flagcat { padding: 4px 10px; margin: 2px 0; border: none; border-width: 1px; border-color: #ffffff; border-style: solid dotted; font: 14px Tahoma; text-decoration: none; background: none; color: #ffffff; background-color: #292929; white-space: nowrap; border-top-left-radius: 8px; border-top-right-radius: 8px; margin-right: -1px; }
.flag_alternate .flagcatlinks a.flagcat:hover { text-decoration: none; background: none; }
.flag_alternate .flagcatlinks a.active, .flag_alternate .flagcatlinks a.flagcat:hover { color: #ffffff; background-color: #737373; outline: none; }
.flag_alternate .flagcatlinks a.flagcat:first-child {  }
.flag_alternate .flagcategory { display: none; font-size: 0; line-height: 0; }
<?php } else { ?>
.flag_alternate .flagCatMeta h4 { padding: 4px 10px; margin: 7px 0; border: none; font: 14px Tahoma; text-decoration: none; background:#292929 none; color: #ffffff; }
.flag_alternate .flagCatMeta p { font-size: 12px; }
<?php } ?>
.flag_alternate { background-color: <?php echo $bg; ?>; margin: 7px 0; }
.flag_alternate .flagcategory { width: 100%; height: auto; position: relative; text-align: center; padding-bottom: 4px; }
.flag_alternate .flagcategory a.flag_pic_alt { display: inline-block; margin: 1px 0 0 1px; padding: 0; height: 100px; width: 115px; line-height: 96px; position:relative; z-index: 2; text-align: center; z-index:99; cursor:pointer; background-color: #ffffff; border: 2px solid #ffffff; text-decoration: none; background-image: url(<?php echo FLAG_URLPATH; ?>admin/images/loadingAnimation.gif); background-repeat: no-repeat; background-position: 50% 50%; font-size: 8px; color: #ffffff; }
.flag_alternate .flagcategory a.flag_pic_alt > .flag_pic_desc { display: none; padding: 4px; line-height: 140%; font-size: 12px; }
.flag_alternate .flagcategory a.flag_pic_alt > .flag_pic_desc * { display: none; line-height: 140%; font-size: 12px !important; }
.flag_alternate .flagcategory a.flag_pic_alt:hover { background-color: #ffffff; border: 2px solid #4a4a4a; color: #4a4a4a; text-decoration: none; z-index: 3; }
.flag_alternate .flagcategory a.flag_pic_alt.current, .flag_alternate .flagcategory a.flag_pic_alt.last { border-color: #4a4a4a; }
.flag_alternate .flagcategory a.flag_pic_alt > img { vertical-align: middle; display:inline-block; position: static; margin: 0 auto; padding: 0; border: none; height: 100px !important; width: 115px !important; max-width: 115px; min-width: 115px; }

<?php if($BarsBG) {
	$bgBar = ($wmode == 'window')? '#'.$BarsBG : 'transparent';
	if(!$isCrawler){
?>
#fancybox-title-over .title { color: #<?php echo $TitleColor; ?>; }
#fancybox-title-over .descr { color: #<?php echo $DescrColor; ?>; }
.flag_alternate .flagcatlinks { background-color: #<?php echo $BarsBG; ?>; }
.flag_alternate .flagcatlinks a.flagcat { border-color: #<?php echo $CatColor; ?>; color: #<?php echo $CatColor; ?>; background-color: #<?php echo $CatBGColor; ?>; }
.flag_alternate .flagcatlinks a.flagcat:hover { border-color: #<?php echo $CatColor; ?>; }
.flag_alternate .flagcatlinks a.active, .flag_alternate .flagcatlinks a.flagcat:hover { color: #<?php echo $CatColorOver; ?>; background-color: #<?php echo $CatBGColorOver; ?>; }
	<?php } ?>
.flag_alternate .flagcategory a.flag_pic_alt { background-color: #<?php echo $ThumbBG; ?>; border: 2px solid #<?php echo $ThumbBG; ?>; color: #<?php echo $ThumbBG; ?>; }
.flag_alternate .flagcategory a.flag_pic_alt:hover { background-color: #<?php echo $ThumbBG; ?>; border: 2px solid #<?php echo $ThumbLoaderColor; ?>; color: #<?php echo $ThumbLoaderColor; ?>; }
.flag_alternate .flagcategory a.flag_pic_alt.current, .flag_alternate .flagcategory a.flag_pic_alt.last { border-color: #<?php echo $ThumbLoaderColor; ?>; }
<?php }; ?>
<?php if($altColors['FullWindow'] && !$isCrawler){ ?>
.flag_alternate a.backlink { float: right; display: block; padding: 2px 5px; border: 1px solid #000; border-radius: 1px; background: #000; color: #fff; text-decoration: none; outline: none; font-size: 12px; font-family: Verdana; font-weight: bold; }
.flag_alternate a.backlink:hover { text-decoration: underline; }
<?php } ?>
</style>
<?php if(!$isCrawler){ ?>
	<meta content="width=device-width, initial-scale=1.0;" name="viewport" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="stylesheet" href="<?php echo plugins_url('/admin/js/photoswipe/photoswipe.css', dirname(__FILE__)); ?>" type="text/css" />
	<script type="text/javascript" src="<?php echo plugins_url('/admin/js/photoswipe/klass.min.js', dirname(__FILE__)); ?>"></script>
	<script type="text/javascript" src="<?php echo plugins_url('/admin/js/photoswipe/code.photoswipe.jquery-3.0.4.min.js', dirname(__FILE__)); ?>"></script>
	<script type="text/javascript">var ExtendVar=false;</script>
<?php } ?>
<div id="<?php echo $skinID; ?>_jq" class="flag_alternate">
		<div class="flagcatlinks"><?php
			if($altColors['FullWindow'] && !$isCrawler){
				$flag_custom = get_post_custom($post->ID);
				$backlink = $flag_custom["mb_button_link"][0];
				if(!$backlink || $backlink == 'http://'){ $backlink = $_SERVER["HTTP_REFERER"]; }
				if($backlink){
					echo '<a class="backlink" href="'.$backlink.'">'.$flag_custom["mb_button"][0].'</a>';
				}
			}
		?></div>
<?php
$gID = explode( '_', $galleryID ); // get the gallery id
if ( is_user_logged_in() ) $exclude_clause = '';
else $exclude_clause = ' AND exclude<>1 ';
foreach ( $gID as $galID ) {
	$galID = (int) $galID;
	if ( $galID == 0) {
		$thepictures = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->flaggallery AS t INNER JOIN $wpdb->flagpictures AS tt ON t.gid = tt.galleryid WHERE 1=1 {$exclude_clause} ORDER BY tt.{$flag_options['galSort']} {$flag_options['galSortDir']} ");
	} else {
		$thepictures = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->flaggallery AS t INNER JOIN $wpdb->flagpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '{$galID}' {$exclude_clause} ORDER BY tt.{$flag_options['galSort']} {$flag_options['galSortDir']} ");
	}
	$captions = '';
?>
	<?php if (is_array ($thepictures) && count($thepictures)){ ?>
		<div class="flagCatMeta">
			<h4><?php echo stripslashes($thepictures[0]->title);?></h4>
			<p><?php echo htmlspecialchars_decode(stripslashes($thepictures[0]->galdesc));?></p>
		</div>
		<div class="flagcategory" id="gid_<?php echo $galID.'_'.$skinID; ?>">
			<?php $n = count($thepictures);
				$var = floor($n/5);
				if($var==0 || $var > 4) $var=4;
				$split = ceil($n/$var);
				$j=0;
		if ($isCrawler){
			foreach ($thepictures as $picture) { ?><a style="display:block; overflow: hidden; height: auto; width: auto; margin-bottom: 10px; background-color: #eeeeee; background-position: 22px 44px; text-align: left;" class="i<?php echo $j++; ?> flag_pic_alt" href="<?php echo $siteurl.'/'.$picture->path.'/'.$picture->filename; ?>" id="flag_pic_<?php echo $picture->pid; ?>" rel="gid_<?php echo $galID.'_'.$skinID; ?>"><img style="float:left; margin-right: 10px;" title="<?php echo strip_tags(stripslashes($picture->alttext)); ?>" alt="<?php echo strip_tags(stripslashes($picture->alttext)); ?>" src="<?php echo $siteurl.'/'.$picture->path.'/thumbs/thumbs_'.$picture->filename; ?>" width="115" height="100" /><span style="display: block; overflow: hidden; text-decoration: none; color: #000; font-weight: normal;" class="flag_pic_desc" id="flag_desc_<?php echo $picture->pid; ?>"><strong><?php echo strip_tags(stripslashes($picture->alttext)); ?></strong><br /><?php echo strip_tags(stripslashes($picture->description),'<b><u><i><span>'); ?></span></a><?php
			}
		} else {
			foreach ($thepictures as $picture) { ?><a class="i<?php echo $j++; ?> flag_pic_alt" href="<?php echo $siteurl.'/'.$picture->path.'/'.$picture->filename; ?>" id="flag_pic_<?php echo $picture->pid; ?>" rel="gid_<?php echo $galID.'_'.$skinID; ?>" title="<?php echo strip_tags(stripslashes($picture->alttext)); ?>">[img src=<?php echo $siteurl.'/'.$picture->path.'/thumbs/thumbs_'.$picture->filename; ?>]<span class="flag_pic_desc" id="flag_desc_<?php echo $picture->pid; ?>"><strong><?php echo htmlspecialchars(stripslashes($picture->alttext)); ?></strong><br /><span><?php echo htmlspecialchars(stripslashes($picture->description)); ?></span></span></a><?php
			}
		} ?>
		</div>
	<?php } ?>
<?php } ?>
</div>
