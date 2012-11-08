<?php  $data=unserialize(base64_decode($_REQUEST['data'])); ?>
jQuery(function($){
    /*snippet for activation of the name field*/
    $('.needInfo').mouseover(function(){
        $(this).validationEngine('showPrompt', $(this).attr('alt'), 'pass');
    });
    $('.needInfo').mouseout(function(){
        $(this).validationEngine('hidePrompt');
    });
    
 
});

// Load the Visualization API and the piechart package.
          google.load('visualization', '1.0', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.setOnLoadCallback(drawChart);

          // Callback that creates and populates a data table, 
          // instantiates the pie chart, passes in the data and
          // draws it.
          function drawChart() {

              // Create the data table.
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Topping');
              data.addColumn('number', 'Slices');
              data.addRows([
                <?php  foreach($data['stats'] as $stats){ if (!is_string($stats['name']) OR preg_match('|[^a-z0-9#_. -]|i',$stats['name']) !== 0 ) $stats['name']="Default"; echo "['".$stats['name']."',   ".(int)$stats['number']."],"; } ?>
              ]);

              // Set chart options
              var options = {'title':'<?php  if (!is_string($stats['title']) OR preg_match('|[^a-z0-9#_. -]|i',$stats['title']) !== 0 ) $stats['title']="Default"; echo $data['title'];?>',
                             'width':400,
                             'height':200,
                             'backgroundColor':'transparent'};

              // Instantiate and draw our chart, passing in some options.
              var chart = new google.visualization.PieChart(document.getElementById('statscontainer'));
              chart.draw(data, options);
            }