if (typeof jQuery == 'undefined') { 
    var headID = document.getElementsByTagName("head")[0];
    var newScript = document.createElement('script');
    newScript.type = 'text/javascript';
    newScript.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js';
    headID.appendChild(newScript);
    jQuery(document).ready(function() {
   // Interceptamos el evento submit
    jQuery('#frontendform').submit(function() {
  // Enviamos el formulario usando AJAX
        Inscribe();
        return false;
    });
});
}else{

jQuery(document).ready(function() {
     // Interceptamos el evento submit
    jQuery('#frontendform').submit(function() {
  // Enviamos el formulario usando AJAX
        Inscribe();
        return false;

    });
});
}
function Inscribe(){
      var url = jQuery("#urlAjax").val()+"frontconfiguration.php";
      var messagenote = jQuery("#messagenote").val();
      var paso = true;

      if (jQuery("#directionInput").length > 0) {
          var directionInput = jQuery("#directionInput").val();
      }else{
          var directionInput = "";
      }
      if (jQuery("#companyInput").length > 0) {
          var companyInput = jQuery("#companyInput").val();
      }else{
          var companyInput = "";
      }
      if (jQuery("#countryInput").length > 0) {
          var countryInput = jQuery("#countryInput").val();
      }else{
          var countryInput = "";
      }
      if (jQuery("#nameInput").length > 0) {
          var nameInput = jQuery("#nameInput").val();
      }else{
          var nameInput = "";
      }
      if (jQuery("#legalnote").length > 0) {
          if (jQuery("#legalnote").attr('checked')){
              
          }else{
              alert(messagenote);
              paso = false;
          }
      }else{
          var enterpriseInput = "";
      }
      
      if (paso == false){
          
      }else{
          var url = jQuery("#urlAjax").val();

          jQuery.ajax({
                type: "POST",
                url: url,
                data: "show=SaveIns&company="+companyInput+"&country="+countryInput+"&direction="+directionInput+"&name="+nameInput+"&email="+jQuery("#emailInput").val()+"&newsletter="+jQuery("#newsletterHidden").val()+"&lista="+jQuery("#listSuscribes").val(),
                beforeSend: function(objeto){
                    jQuery("#resultado").html('<img  src="'+jQuery("#loadingurl").val()+'" >');
                },
                success: function(datos){
                 
                    jQuery("#resultado").html(datos);
                }
          });
     }
}
