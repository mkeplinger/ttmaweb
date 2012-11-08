<?php
global $meenews_datas;

$idnewsletter = $_GET['idnews'];
$from = $meenews_datas['meenews']['default_email'];
$subject =  $meenews_datas['meenews']['default_subject'];
$newsletter_datas = MeeNewsletter::extractNewsletter($idnewsletter);
$title = $newsletter_datas->title;

 ?>
 <script type="text/javascript">
        jQuery(document).ready(function() {
                    jQuery('#senderForm').submit(function() {
                  // Enviamos el formulario usando AJAX

                        showButtons();
                        return false;
                    });
         });

        function showButtons(){

                var url = <?php echo  "'".MEENEWS_AJAX_FILE."'" ?>;
                 jQuery( function($) {
                     var lista = $("#listSuscribes option:selected").val();
                      var range = $("#sendMode option:selected").val();
                     var url = <?php echo  "'".MEENEWS_AJAX_FILE."'" ?>;
                       $.ajax({
                            type: "POST",
                            url: url,
                            data: "show=GButtons&lista="+lista+"&range="+range+"&idnewsletter="+$('#idnewsletter').val(),
                            beforeSend: function(objeto){
                                      $("#showsender").html('<img src=\'<?php echo MEENEWS_LIB_URI; ?>img/loading.gif\'>');
                                },
                            success: function(datos){
                              $("#showsender").html('<?php  echo __('The buttons has been created ok','meenews'); ?>');
                              $('#senders').html(datos);
                           }
                       });
                });
          }
          function sendmail(to,until,list,id,test){
                if (to == null){
                    to = 'all';
                }
                 jQuery( function($) {
                     var idnewsletter = $('#idnewsletter').val();
                     var url = <?php echo  "'".MEENEWS_AJAX_FILE."'" ?>;
                       $.ajax({
                            type: "POST",
                            url: url,
                            data: "show=Send&idnewsletter="+idnewsletter+"&to="+to+"&until="+until+"&lista="+list+"&from="+$('#from').val()+"&title="+$('#title').val()+"&subject="+$('#subject').val()+"&test="+test,
                            beforeSend: function(objeto){
                                     $("#showsender").html('<img src=\'<?php echo MEENEWS_LIB_URI; ?>img/loading.gif\'>');
                                },
                           success: function(datos){
                              var resultados = datos.split("|");
                              $("#showsender").html('<?php  echo __('The newsletter has been send complete','meenews'); ?>');
                              $('#result'+id).css('display','block')
                              $('#ok'+id).html(resultados[0]);
                              $('#wrong'+id).html(resultados[1]);
                              $('#test'+id).html(resultados[2]);
                           }
                       });
                });
          }

</script>
<div class="wrap">
			<h2><?php echo __("Sends Manager", 'meenews'); ?></h2>
			<form id="senderForm" name="senderForm" action="?page=managernewsletter.php" enctype="multipart/form-data" method="post">
				<table class="widefat">
					<tbody>

						<tr>
							<td colspan="2"><hr /></td>
						</tr>
                        <tr>
							<th scope="row" style="width:6em;text-align:left;vertical-align:top;"><?php echo __("From (name)", 'meenews'); ?></th>
							<td>
								<input type="text" style="width:250px;" name="title" id="title" value="<?php echo $subject; ?>" /><br />
							</td>
				  	    </tr>
						<tr>
							<th style="text-align:left;vertical-align:top;" scope="row"><label style="vertical-align:top;" for="letterFrom"><?php echo __("From (email):", 'meenews'); ?></label></th>
							<td>
								<input type="text" style="width:250px;" name="from" id="from" value="<?php echo $from; ?>" /><br />
							</td>
						</tr>
						<tr>
							<th style="text-align:left;vertical-align:top;" scope="row"><label style="vertical-align:top;" for="letterSubject"><?php echo __("Subject:", 'meenews'); ?></label></th>
							<td>
								<input type="text" style="width:500px;" name="subject" id="subject" value="<?php echo $title; ?>" /><br />
							</td>
						</tr>
                        <tr>
							<th style="text-align:left;vertical-align:top;" scope="row"><label style="vertical-align:top;" for="letterSubject"><?php echo __("List to send:", 'meenews'); ?></label></th>
							<td>
								<?php echo MeeUsers::getComboListSuscribes('',true); ?>
							</td>
						</tr>
                        <tr>
							<th style="text-align:left;vertical-align:top;" scope="row"><label style="vertical-align:top;" for="letterSubject"><?php echo __("Choose send mode:", 'meenews'); ?></label></th>
							<td>
                                                            <p style="background:red;color:white;padding:2px;padding-left:10px"><?php echo __("Send Mode only works in commercial version","meenews")?></p>
								<p><select name='' id ='sendMode' >
                                  <option value='null'><?php echo __("All at once", 'meenews'); ?></option>
                                  <option value='10'><?php echo __("10 in 10", 'meenews'); ?></option>
                                  <option value='50'><?php echo __("50 in 50", 'meenews'); ?></option>
                                  <option value='100'><?php echo __("100 in 100", 'meenews'); ?></option>
                                  <option value='200'><?php echo __("200 in 200", 'meenews'); ?></option>
                                  <option value='500'><?php echo __("500 in 500", 'meenews'); ?></option>
                                   <option value='1000'><?php echo __("1000 in 1000", 'meenews'); ?></option>
                                 </select></p>
                                 <p style="background:red;color:white;padding:2px;padding-left:10px"><?php echo __("Cron Jobs only works in commercial version","meenews")?></p>
                                 <p><?php echo __("Atention: If you want to use to send newsletter CRON JOBS <br> Datas for cronjobs:<br>Put this line into your cron job panel: <strong>php -q ","meenews").MEENEWS."sendcron.php"; ?></strong></p>
							</td>
						</tr>

					</tbody>

				</table>
				<div class="submit">
                                        <input type="hidden" name="sendMode" value="all">
					<input name="send" type="submit" value="<?php echo __("Generate Senders", 'meenews'); ?>" style="float:left;margin-left:10px" /><div style="float:left;margin-left:10px" id="showsender"></div>
                    <input name="idnewsletter" id="idnewsletter" type="hidden" value="<?php echo $idnewsletter; ?>" />
				</div>
			</form>
                <div id="senders" style="width:100%; border:1px solid silver;padding:10px">
                    
                </div>
		</div>
