<?php

function _livechat_monitoring_code($license_number, $lang, $groups, $params)
{
	if (LIVECHAT_LICENSE_INSTALLED == false) return;
?>

<!-- Begin LiveChat track tag. See also www.livechatinc.com -->
<script type="text/javascript">
(function() {
var livechat_params = '<?php echo $params; ?>';

var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
var lc_src = ('https:' == document.location.protocol ? 'https://' : 'http://');
lc_src += 'chat.livechatinc.net/licence/<?php echo $license_number; ?>/script.cgi?lang=<?php echo $lang; ?>&groups=<?php echo $groups; ?>';
lc_src += ((livechat_params == '') ? '' : '&params='+encodeURIComponent(encodeURIComponent(livechat_params)));
lc.src = lc_src;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>
<!-- End LiveChat track tag. See also www.livechatinc.com -->
<?php
}