<?php
/**
 * The template for displaying the footer.
 */
?> 

 <?php $options = get_option('absolum'); if ($options['abs_pos_sidebar'] == "disable") { ?>

<?php } else { get_sidebar(); 

 } ?>

	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">

<?php	get_sidebar( 'footer' ); ?>

		</div><!-- #colophon -->
	</div><!-- #footer -->
  
</div><!-- #wrapper -->

<div id="footer-bottom">
<?php
$credits = '<div id="site-info"><a href="'. home_url() .'">'. get_bloginfo( 'name' ) .'</a></div><div id="site-generator"><a href="http://theme4press.com/absolum/">Absolum</a> theme by Theme4Press&nbsp;&nbsp;&bull;&nbsp;&nbsp;Powered by <a rel="generator" title="Semantic Personal Publishing Platform" href="http://wordpress.org">WordPress</a></div>';
echo apply_filters( 'absolum_credits', (string) $credits );
absolum_footer_hook(); ?> 
</div>
<?php wp_footer(); ?>    
</body>
</html>
