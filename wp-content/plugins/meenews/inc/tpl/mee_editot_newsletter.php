<?php
global $meenews_datas;
$default = $meenews_datas['newsletter']['defaultOptions'];

$issue_style = $default['issue'].";color:".$meenews_datas['newsletter']['color_issue'];
?>
<script type="text/javascript">
        var ventana_ancho = jQuery(window).width();
        var ventana_alto = jQuery(window).height();
        var post_sel = "";
        var id_post_sel = 0;
        var idpost = 0;
        jQuery(document).ready(function() {
            jQuery("#add_post").click(function(){
                               insertDesign()
             });

             jQuery("form").submit(function() {
                   jQuery("#content_box").val(jQuery("#editorcontainer").html());
             });

        });
               function showPosted(idposta,all){
               idpost = idposta;
               jQuery( function($) {
                   var url = '<?php echo  MEENEWS_AJAX_FILE ?>';
                   var urlblog =  <?php echo  "'". $url ."'" ?>;
                    jQuery("#designbox").css({
                            'display':'block',
                            'height':ventana_alto
                    });
                    jQuery("#designs_content").html('<?php echo  __("<p>Waiting Datas ... <img src=\'".MEENEWS_LIB_URI."img/loading.gif\'></p>")?>');
                    jQuery(".selDesign").click(function(){
                            jQuery(".postDesign").css("display", "none");
                            jQuery("#"+$(this).attr("rel")).css("display", "block");
                            post_sel = jQuery("#"+$(this).attr("rel")).html();
                    });
                    $.ajax({
                            type: "POST",
                            url: url,
                            data: "show=Post&id="+idpost+"&showall="+all,
                            success: function(datos){
                                jQuery("#designs_content").html(datos);
                                post_sel = jQuery("#only_text").html();
                            }
                    });
               });

               }

               function insertDesign(){
                             jQuery( function($) {
                                $("#Inc"+idpost).html('<a sytle="color:#b4d338" class="posted" rel="'+idpost+'" href="javascript:QuitPosted('+idpost+')" ><?php echo _e("Del", 'meenews'); ?></a>');
                                $("#editorcontainer").find("#finalTabla").append(post_sel);
                                var titulo = $("#editorcontainer").find(".postadded"+idpost).attr('alt');
                                var permalink = $("#editorcontainer").find(".postadded"+idpost).attr('rel');
                                
                                $("#editorcontainer").find(".listaissue").append("<li id='issue"+idpost+"'><a style=\"<?php echo $issue_style;?>\" href='"+permalink+"'>"+titulo+"</a></li>");
                                jQuery("#designbox").css({'display':'none'});
                              });
               }
               function QuitPosted(idpost){
                       jQuery( function($) {
                                   $("#editorcontainer").find(".postadded"+idpost).remove();
                                   $("#editorcontainer").find("#issue"+idpost).remove();
                                   $("#Inc"+idpost).html('<a class="delete" href="javascript:showPosted('+idpost+')" ><?php echo _e("Add", 'meenews'); ?></a>');
                         });

               }

               function changepostday(id){
                jQuery( function($) {
                     var url = <?php echo  "'".$urs."'" ?>;

                       $.ajax({
                            type: "POST",
                            url: url,
                            data: "show=changeColumn&id="+id,
                            beforeSend: function(objeto){
                                     $("#colCat_"+id+" .inside").html('<img  src="<?php  echo plugins_url('meenews/images/ajax-loader2.gif'); ?>" >');
                                },
                            success: function(datos){
                                $("#colCat_"+id+" .inside").html(datos);

                          }
                    });
                 });
               }
                function changepostlast(id){
                jQuery( function($) {
                     var url = <?php echo  "'".$urs."'" ?>;

                       $.ajax({
                            type: "POST",
                            url: url,
                            data: "show=changeAll&id="+id,
                            beforeSend: function(objeto){
                                     $("#colCat_"+id+" .inside").html('<img  src="<?php  echo plugins_url('meenews/images/ajax-loader2.gif'); ?>" >');
                                },
                            success: function(datos){
                                $("#colCat_"+id+" .inside").html(datos);

                          }
                    });
                 });
               }


		</script>
<?php
global $_GET;
$id_newsletter = $_GET['idnews'];

if($id_newsletter != null){
            $newsletter_datas = MeeNewsletter::extractNewsletter($id_newsletter);
            $newsletter = $newsletter_datas->newsletter;
            $title = $newsletter_datas->title;
            $slug = $newsletter_datas->slug;
}else{
            $newsletter .=  MeeNewsletter::createNewsletter();
}

?>

<?php if($id_newsletter != null){ ?>
<input type="hidden" id="acc" name="acc" value="edit_newsletter">
<input type="hidden" id="id_newsletter" name="id_newsletter" value="<?php echo $id_newsletter; ?>">
<?php }else{ ?>
<input type="hidden" id="acc" name="acc" value="new_newsletter">
<?php }?>

<div id="post-body" >
    <div id="post-body-content" class="has-sidebar-content">
        <input name="idnews" value="<?php echo $id_newsletter; ?>" type="hidden">
               <div id="titlediv">
                    <div id="titlewrap">
                            <label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title"><?php echo __("Enter title here","meenews"); ?></label>
                            <input type="text" name="news_title" size="30" tabindex="1" value="<?php echo $title; ?>" id="title" autocomplete="off" />
                    </div>
              </div>
        <br>
        <div id="content" style="overflow:hidden;height:auto">
            <div id='editorcontainer' style=""><?php echo $newsletter; ?></div>
        </div>
        <textarea id="content_box" name="content" style="display:none"></textarea>
    </div>

       </div>
</form>
    </div>

<div id="designbox">

    <div class="box">

    <a href="#" id="closedesignbox"><?php echo __("Close","meenews"); ?></a>

    <h3><?php echo __("Select entry design","meenews"); ?></h3>

    <ul class="types">
    <li class="type1"><a href="#" class="selDesign" rel="only_text"><?php echo __("Text","meenews"); ?></a></li>
    <li class="type2"><a href="#" class="selDesign" rel="img_text_design"><?php echo __("Image + Text","meenews"); ?></a></li>
    <li class="type3"><a href="#" class="selDesign" rel="text_img_design"><?php echo __("Text + Image","meenews"); ?></a></li>
    <li class="type4"><a href="#" class="selDesign" rel="img_text_separate"><?php echo __("Image / Text","meenews"); ?></a></li>
    </ul>

    <div class="content corner" id="designs_content">
    </div> <!--/ content -->

    <div class="footerbox">
    <a href="#" class="corner" id="cancel"><?php echo __("Cancel","meenews"); ?></a> <a href="#" class="corner black" id="add_post"><?php echo __("ADD Newsletter","meenews"); ?> &raquo;</a>
    </div>

    </div> <!--/ box-->

</div> <!--/ designbox -->