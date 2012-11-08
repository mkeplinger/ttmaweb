if (typeof jQuery == 'undefined') { 
    var headID = document.getElementsByTagName("head")[0];
    var newScript = document.createElement('script');
    newScript.type = 'text/javascript';
    newScript.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js';
    headID.appendChild(newScript);
    jQuery(document).ready(function() {
   // Interceptamos el evento submit
        jQuery("#formcontact").validate();
        jQuery("#mee_submit").click(function() {
            if( jQuery("#formcontact").valid() == true) sendContact();
            return false;
        });
            return false;

});
}else{

jQuery(document).ready(function() {
     // Interceptamos el evento submit
    jQuery("#formcontact").validate();
    jQuery("#mee_submit").click(function() {
        if( jQuery("#formcontact").valid() == true) sendContact();
        return false;
          
    });
        return false;
});
}
function sendContact(){


          var name    = jQuery("#mee_name").val();
          var phone   = jQuery("#mee_phone").val();
          var email   = jQuery("#mee_email").val();
          var comment = jQuery("#mee_comment").val();
          var autor = jQuery("#autor").val();

          

      var url = jQuery("#urlAjax").val();

          jQuery.ajax({
                type: "POST",
                url: url,
                data: "show=SendNewsletter&name="+name+"&author="+autor+"&email="+email+"&phone="+phone+"&comment="+comment+"&emailsend="+jQuery("#emailsend").val(),
                beforeSend: function(objeto){
                    jQuery("#mee_form_result_loading").css("display","block");
                },
                success: function(datos){
                    jQuery("#mee_form_result_loading").css("display","none");
                    jQuery("#mee_form_result").css("display","block");
                    jQuery("#mee_form_result").html(datos);
                    jQuery("#mee_name").val('');
                    jQuery("#mee_phone").val('');
                    jQuery("#mee_email").val('');
                    jQuery("#mee_comment").val('');
                }
          });
     return false;
}
