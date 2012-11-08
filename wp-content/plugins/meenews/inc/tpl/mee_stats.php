<?php
global $meenews_datas;
echo __("<h4>This option only works in commercial version, download it</h4>","meenews")
?>
<p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="custom" value="compra">
        <input type="hidden" name="hosted_button_id" value="6532066">
        <input type="hidden" name="return" value="http://www.wp-newsletter.com/comercial.php?a=thank">
        <input type="hidden" name="cancel_return" value="http://www.wp-newsletter.com/comercial.php?a=cancel">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma r‡pida y segura de pagar en Internet.">
        <img alt="" border="0" src="https://www.paypal.com/es_ES/i/scr/pixel.gif" width="1" height="1">
        </form>
        <p>visit <a href="http://www.wp-newsletter.com">http://www.wp-newsletter.com</a>
        </p>
<?php
echo "<img src='".MEENEWS_URI."inc/img/stats.png' />";
?>
