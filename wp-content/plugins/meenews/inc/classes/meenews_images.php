<?php
##################################################################
function MeeNewsImages($datas) {
##################################################################

        $defaults = array(
                        'url'           => '',
                        'lightbox'      => '',
                        'title'         => '',
                        'id'            => '',
                        'link_url'      => '',
                        'efect_type'    => 'resize',
                        'height'        => null,
                        'width'         => null,
                        'want_resize'   => GD_INSTALL,
                        'alt'         => '',
                        'gdfalse'    => '',
                        'zc' => 1,
                        'class'         => '',
                        'uri_only'      => false,
                        'domain'        => false,
                        'putdimensions' => false
                        );


       if($datas['style'] != '') $image_style= ' style="'.$datas['width'].'" ';
        $datas = array_merge((array)$defaults,(array)$datas);
        $video = false;
        // Palabra que queremos buscar
        $palabra = "http://www.youtube.com";
        if(eregi($palabra,$datas['url'])) {
            $video = true;
        }
        $palabra = "http://vimeo.com/";
        if(eregi($palabra,$datas['url'])) {
            $video = true;
        }
        $palabra = "http://movies.apple.com/";
        if(eregi($palabra,$datas['url'])) {
            $video = true;
        }
        $palabra = "http://www.adobe.com/products/flashplayer/include/";
        if(eregi($palabra,$datas['url'])) {
            $video = true;
        }

        if ($video  == true){
             $video_url = $datas['url'] ;
             $datas['url'] = get_post_meta(get_the_ID(), "medium", true);
        }
        
      if($datas['url'] == "")return false;
      //start image
      //


      if ($datas['putdimensions'] == 'true'){
        $image_start = "<img width='".$datas['width']."' height='".$datas['height']."' src='";
      }else{
        $image_start = "<img src='";
      }
      $image_end = "/>";
      $image_alt ="'";
      if  ($datas['class'] != "") $image_class = " class = '".$datas['class']."' ";
      if  ($datas['alt'] != "") $image_alt = "' alt = '".$datas['alt']."' ";
      if  ($datas['title'] != "") $image_title = "title = '".$datas['title']."' ";
      
      if  ($datas['id'] != "") $image_id = "id = '".$datas['id']."' ";

      
      if  ($datas['link_url'] != "") $image_link = "<a href='".$datas['link_url']."' ".$image_title." > ";
      if  ($datas['lightbox'] != "" ) $image_link = "<a href='".$datas['url']."' ".$image_title." rel='lightbox[".$option['lightbox']."]' > ";
      if  ($datas['lightbox'] != "" && $video == true) $image_link = "<a href='".$video_url."' ".$image_title." class='video' rel='lightbox[".$option['lightbox']."]' > ";
      if  ($datas['lightbox'] != "" && $datas['gdfalse'] != "") $image_link = "<a href='".$datas['gdfalse']."' ".$image_title."  rel='lightbox[".$option['lightbox']."]' > ";
      if  ($datas['vid'] != "") $image_link = "<a href='".$datas['url']."' ".$image_title." rel='lightbox[".$option['lightbox']."]' > ";

      if  (($datas['link_url'] != "") || ($datas['lightbox'] != "")) $image_end_link = "</a>";


      if ($datas['width'] != null){
          $image_width  = " width='".$datas['width']."'";
          $image_height = " height='".$datas['height']."'";
      }else{
          return $image_link.$image_start.$datas['url'].$image_alt.$image_class.$image_style.$image_id.$image_width.$image_height.$image_end.$image_end_link;
      }
      


	  
      if($datas['width'] != '') $image_width = '&amp;w='.$datas['width'];
      if($datas['height'] != '') $image_height = '&amp;h='.$datas['height'];
      if($datas['zc'] != '')    $image_efect .= '&amp;zc='.$datas['zc'];


      $url_image = parse_url($datas['url']);
      $datas['url'] =  $url_image['path'];

      $url = MEENEWS_CLASSES_URI."timthumb.php?src=".$datas['url'].$image_width.$image_height.$image_efect;

      return $image_link.$image_start.$url.$image_alt.$image_class.$image_style.$image_id.$image_end.$image_end_link;

       
     
##################################################################
} # end Meeimages
##################################################################
