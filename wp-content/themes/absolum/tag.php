<?php
/**
 * The template for displaying Tag Archive pages.
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

				<h1 class="page-title"><?php
					printf( __( 'Tag Archives: %s', 'absolum' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				?></h1>

<?php

 get_template_part( 'loop', 'tag' );
?>
			</div><!-- #content -->
		</div><!-- #container -->


<?php get_footer(); ?>
