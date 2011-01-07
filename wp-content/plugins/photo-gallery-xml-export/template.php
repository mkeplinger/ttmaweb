<?php

/* Photo gallery XML export template */

// sets header for XML document
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";

// removes formatting from the excerpt field 
remove_filter('the_excerpt', 'wpautop');

// only pull the latest XX # of posts, set in options page
$numberposts = get_option('numberposts');
$categoryname = get_option('categoryname'); 
query_posts($query_string . "&category_name=" . $categoryname . "&showposts=" . $numberposts);
?>

<images>

<?php 
// start wordpress loop
if ( have_posts() ): while ( have_posts() ): the_post(); ?>

  <pic>
	
    <?php
	// display the title if checked in options page
        $checkbox1 = get_option('checkbox1');
		$tagname6 = get_option('tagname6');
         if($checkbox1 == 1) : 
		 echo "<" . $tagname6 . ">";
		 {
	?><?php the_title(); ?><?php 
		echo "</" . $tagname6 . ">"; } else : { ?>        
    <?php } endif; ?>
    
    
    
    
    <?php
	// display the excerpt if checked in options page
		$checkbox2 = get_option('checkbox2');
		$tagname7 = get_option('tagname7');
         if($checkbox2 == 1) : 
		 echo "<" . $tagname7 . ">";
		 {
	?><?php the_excerpt(); ?><?php 
		echo "</" . $tagname7 . ">"; } else : { ?> 
    <?php } endif; ?>
    
    
    
        <?php
	// display the excerpt if checked in options page
		$checkbox3 = get_option('checkbox3');
		$tagname8 = get_option('tagname8');
         if($checkbox3 == 1) : 
		 echo "<" . $tagname8 . ">";
		 {
	?><?php the_permalink(); ?><?php 
		echo "</" . $tagname8 . ">"; } else : { ?> 
    <?php } endif; ?>
    
    
    
    
	<?php 
	// grabs the custom fields and tag names set on the options page, assigns them as variables, then prints them
	$elementname1 = get_option('tagname1');
	$elementname2 = get_option('tagname2');
	$elementname3 = get_option('tagname3');
	$elementname4 = get_option('tagname4');
	$elementname5 = get_option('tagname5');
	$customelement1 = get_option('customfield1');
	$customelement2 = get_option('customfield2');
	$customelement3 = get_option('customfield3');
	$customelement4 = get_option('customfield4');
	$customelement4 = get_option('customfield4');
	if (get_post_custom_values($customelement1)) :
	foreach (get_post_custom_values($customelement1) as $customfield1) {
		echo "<" . $elementname1 . ">" . $customfield1 . "</" . $elementname1 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement2)) :
	foreach (get_post_custom_values($customelement2) as $customfield2) {
		echo "<" . $elementname2 .">" . $customfield2 . "</" . $elementname2 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement3)) :
	foreach (get_post_custom_values($customelement3) as $customfield3) {
		echo "<" . $elementname3 .">" . $customfield3 . "</" . $elementname3 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement4)) :
	foreach (get_post_custom_values($customelement4) as $customfield4) {
		echo "<" . $elementname4 .">" . $customfield4 . "</" . $elementname4 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement5)) :
	foreach (get_post_custom_values($customelement5) as $customfield5) {
		echo "<" . $elementname5 .">" . $customfield5 . "</" . $elementname5 . ">"; }
	else :
		echo '';
	endif;
	?>
	
    </pic>
<?php endwhile; endif; ?>

</images>