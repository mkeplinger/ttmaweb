

jQuery(document).ready(function() {

       
    addfunctions();


    jQuery(".add_slide_btn").click( function(){
      
             add_slide();
             return false;
    });
    
});



function addfunctions(){


       jQuery(".delete").click(function(){

            jQuery(this).parents('tr:eq(0)').remove();
            reset_orders();
       });

      // Initialise the table
        jQuery("#sliderTable").tableDnD({

            onDrop: function(table, row) {
           reset_orders();

            }
        });
       jQuery(".slider").hover(function(){

            jQuery(this).find(".actions").css("display","block");

       },
       function () {
            jQuery(this).find(".actions").css("display","none");
            jQuery(this).find(".otherdatas").css("display","none");
       });

       jQuery(".editaction").click( function(){
           jQuery(this).parent().find(".otherdatas").css("display","block");
       });

       jQuery(".closeaction").click( function(){
            jQuery(this).parent().find(".otherdatas").css("display","none");
       });

}

function add_slide(){
       
       jQuery(jQuery(".slider:first").clone()).appendTo(".widefat");


       
       addfunctions()

       reset_all_values();
       
}

function reset_orders(){
    var order = 1;
    jQuery(".slider .order").each(function(){ jQuery(this).val(order); order++ });

}

function reset_all_values(){
    jQuery(".slider:last input").each(function(){ jQuery(this).val("")});
    jQuery(".slider:last .image-column").html("No image");
    reset_orders();
    
}