<?php $options = get_option('absolum');
  if ($options['abs_color_scheme'] == "grey" ):
    
echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/grey/grey.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "black" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/black/black.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "purple" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/purple/purple.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "orange" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/orange/orange.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "red" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/red/red.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "green" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/green/green.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "blue" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/blue/blue.css" type="text/css" media="screen" />';

elseif ($options['abs_color_scheme'] == "gothic" ):

echo '<link rel="stylesheet" href="'; echo get_template_directory_uri(); echo '/images/schemes/gothic.css" type="text/css" media="screen" />';

else:

endif;

 ?>
 
 

 <?php $options = get_option('absolum');  

 if ($options['abs_content_font'] == "arial") { ?>

  <style type="text/css">body, input, textarea {font-family: Arial, Helvetica Neue, Helvetica, sans-serif;}</style>
  
 <?php } if ($options['abs_content_font'] == "segoe") { ?>

  <style type="text/css">body, input, textarea {font-family: "Segoe UI",Calibri,"Myriad Pro",Myriad,"Trebuchet MS",Helvetica,Arial,sans-serif;}</style>
  
  
   <?php } if ($options['abs_content_font'] == "courier") { ?>

  <style type="text/css">body, input, textarea {font-family: "Courier New", Courier, monospace;}</style>
  
   <?php } if ($options['abs_content_font'] == "times") { ?>

  <style type="text/css">body, input, textarea {font-family: "Times New Roman", serif;}</style>
  
    
  
  

 <?php } if ($options['abs_content_font'] == "calibri") { ?>

  <style type="text/css">body, input, textarea {font-family:Calibri,Segoe UI,Myriad Pro,Myriad,Trebuchet MS,Helvetica,Arial,sans-serif;}</style>
  
  
 <?php } if ($options['abs_title_scheme'] == "blue") { ?>
 
   <style type="text/css">#blog-title {background-image: url("<?php echo get_template_directory_uri(); ?>/images/blog-title/blue.png");}
   #site-title a {background:#0d68a2;text-shadow: 0 1px 2px #084166;}
   #site-title a:hover {background:#0d68a2 url("<?php echo get_template_directory_uri(); ?>/images/blog-title/blue-hover.png") center bottom no-repeat;}
   #site-description {color:#b1ccde;}   
   </style>
  
 
   <?php } if ($options['abs_title_scheme'] == "red") { ?>
 
   <style type="text/css">#blog-title {background-image: url("<?php echo get_template_directory_uri(); ?>/images/blog-title/red.png");}
   #site-title a {background:#9f1b1b;text-shadow: 0 1px 2px #5b0f0f;}
   #site-title a:hover {background:#9f1b1b url("<?php echo get_template_directory_uri(); ?>/images/blog-title/red-hover.png") center bottom no-repeat;}
   #site-description {color:#ffd4d4;}   
   </style>
   
   <?php } if ($options['abs_title_scheme'] == "green") { ?>
 
   <style type="text/css">#blog-title {background-image: url("<?php echo get_template_directory_uri(); ?>/images/blog-title/green.png");}
   #site-title a {background:#307b0d;text-shadow: 0 1px 2px #193f07;}
   #site-title a:hover {background:#307b0d url("<?php echo get_template_directory_uri(); ?>/images/blog-title/green-hover.png") center bottom no-repeat;}
   #site-description {color:#cdffb7;}   
   </style>   
   
   <?php } if ($options['abs_title_scheme'] == "silver") { ?>
 
   <style type="text/css">#blog-title {background-image: url("<?php echo get_template_directory_uri(); ?>/images/blog-title/silver.png");}
   #site-title a {color:#333;background:#ddd;text-shadow: 0 1px 2px #eee;}
   #site-title a:hover {background:#ddd url("<?php echo get_template_directory_uri(); ?>/images/blog-title/silver-hover.png") center bottom no-repeat;}
   #site-description {color:#777;}   
   </style>   
   
   <?php } if ($options['abs_title_scheme'] == "brown") { ?>
 
   <style type="text/css">#blog-title {background-image: url("<?php echo get_template_directory_uri(); ?>/images/blog-title/brown.png");}
   #site-title a {background:#614b3a;text-shadow: 0 1px 2px #32271e;}
   #site-title a:hover {background:#614b3a url("<?php echo get_template_directory_uri(); ?>/images/blog-title/brown-hover.png") center bottom no-repeat;}
   #site-description {color:#ffdcc3;}   
   </style>   
   
   <?php } if ($options['abs_back_scheme'] == "green") { ?>
 
   <style type="text/css">
   body {background:url("<?php echo get_template_directory_uri(); ?>/images/background/green.png") repeat-y scroll center top #42bf42;}  
   </style>  
   
   <?php } if ($options['abs_back_scheme'] == "silver") { ?>
 
   <style type="text/css">
   body {background:url("<?php echo get_template_directory_uri(); ?>/images/background/silver.png") repeat-y scroll center top #c1c1c1;}  
   </style>       
   
   <?php } if ($options['abs_back_scheme'] == "black") { ?>
 
   <style type="text/css">
   body {background:url("<?php echo get_template_directory_uri(); ?>/images/background/black.png") repeat-y scroll center top #101010;}  
   </style>  
   
   <?php } if ($options['abs_back_scheme'] == "red") { ?>
 
   <style type="text/css">
   body {background:url("<?php echo get_template_directory_uri(); ?>/images/background/red.png") repeat-y scroll center top #bf4242;}  
   </style>     
   
   <?php } if ($options['abs_back_scheme'] == "yellow") { ?>
 
   <style type="text/css">
   body {background:url("<?php echo get_template_directory_uri(); ?>/images/background/yellow.png") repeat-y scroll center top #bfbd42;}  
   </style>  
                 
   <?php } if ($options['abs_back_scheme'] == "brown") { ?>
 
   <style type="text/css">
   body {background:url("<?php echo get_template_directory_uri(); ?>/images/background/brown.png") repeat-y scroll center top #684e25;}  
   </style>             
  
  <?php } ?> 
  
  
  <?php $options = get_option('absolum'); if ($options['abs_pos_sidebar'] == "left") { ?>

   <style type="text/css"> 
  #content {margin: 0 5px 0 345px;}
  #container {float: right;margin: 0 0 0 -325px;}
  #main {background-position:left top;float: right;}
  #primary, #secondary {float: left;}
  .entry-meta {left:auto;right:0px;background-position:10px bottom;}
</style>  

<?php } if ($options['abs_pos_sidebar'] == "disable") { ?>

   <style type="text/css"> 
  #content {margin: 0 5px;}
  #container {float: right;margin: 0;}
  #main {background:none;}
  .entry-meta {
        width:940px;
}
</style>  

<?php } ?> 

<?php if ($options['abs_header_slider'] == "disable" || $options['abs_header_slider'] == "") { ?>

<?php } else { ?>

<?php if( get_header_image() ) { }  else { ?> 
<style type="text/css"> 
#slide_holder img {-moz-box-shadow:0 1px 8px #888;}
#slide_holder .featured-title a {color:#555;text-shadow:none;}
#slide_holder .featured-title a:hover {color:#333;}
#slide_holder span {color:#333;text-shadow:none;}
</style>  <?php } ?>

<?php } ?>



   <?php $options = get_option('absolum'); 
  if ($options['abs_header_slider'] == "one") { ?>
   
   <style type="text/css"> 
  ul.slides li.slide {
display:block !important; }
</style>    
        
  <?php } ?>