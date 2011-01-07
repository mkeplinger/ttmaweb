<?php

/**
 * @author Edge Technology Consulting
 * @copyright 2009
 */

 
function events_import(){

 er_plugin_menu();   
 
?>
<div id="event_reg_theme" class="wrap">
<h2>Import Events</h2>
<ul id="event_regis-sortables">

			<li>
				<div class="box-mid-head">
					<h2 class="events_reg f-wrench">Importing Events Directions</h2>
				</div>

				<div class="box-mid-body" id="toggle2">
					<div class="padding">

<p>This page is for importing your events from a comma seperated file (CSV) directly into the the events database.  The limitation of this upload is that it does not support the extra questions, only the core event configuration. Please remember if your field has any commas you must include quotation marks on each side of the field. Do not use double or single quotation marks in any of your fields.  Please do not input any data in the columns question1 - question4.</p>
<ol><ul>
<li>Please use Y where you want to say Yes and N where you want No. (Two exceptions 'is_active' & 'allow_checks' should be lower case full word - yes or no </li>
<li>Months should be 3 letter abbreviations, started with capital (Jan, Feb, Mar, etc.).</li>   
<li>Custom Currency codes should be 3 digit all caps (USD, AUD, etc.) </li>
<li>Dates should be formatted YYYY-MM-DD (2009-07-04).  </li>
<li>Event cost should be 0 if free.
<li>Time should be military  hours:minutes witout leading zeros ( 8:00,  17:00, 23:59).</li> 
</ul></ol>
<p><b>A template file <a href="<?php echo ER_PLUGINFULLURL.'events.csv';?>">here</a> that I recommend you download and use.  It is very easy to work with it in excel, just remember to save it as a csv and not excel sheet.</b></p>

<p><i>One final note, you will see that the header row, fist column has a 0 while other rows have a 1.  This tells the upload to ignore rows that have the 0 identifier and only use rows with the 1.</i></p>
</div></div></li></ul>
<ul id="event_regis-sortables">

			<li>
				<div class="box-mid-head">
					<h2 class="events_reg f-wrench">Events Upload Tool</h2>
				</div>

				<div class="box-mid-body" id="toggle2">
					<div class="padding">
<?php	

uploader();
load_events_to_db();
?>
</div></div></li></ul></div>
<?php
}
define("ER_UPLOADURL", WP_CONTENT_DIR . '/uploads/' );

function uploader($num_of_uploads=1, $file_types_array=array("csv"), $max_file_size=1048576, $upload_dir= ER_UPLOADURL){ 
  if(!is_numeric($max_file_size)){ 
    $max_file_size = 1048576; 
  } 
  if(!isset($_POST["submitted"])){ 
    $form = "<form action='".$PHP_SELF."' method='post' enctype='multipart/form-data'>Upload files:<br /><input type='hidden' name='submitted' value='TRUE' id='".time()."'><input type='hidden' name='MAX_FILE_SIZE' value='".$max_file_size."'>"; 
    for($x=0;$x<$num_of_uploads;$x++){ 
      $form .= "<input type='file' name='file[]'><font color='red'>*</font><br />"; 
    } 
    $form .= "<br><input type='submit' value='Upload File & Add Event(s)'><br /><font color='red'>*</font>Maximum file length (minus extension) is 15 characters. Anything over that will be cut to only 15 characters. Valid file type(s): "; 
    for($x=0;$x<count($file_types_array);$x++){ 
      if($x<count($file_types_array)-1){ 
        $form .= $file_types_array[$x].", "; 
      }else{ 
        $form .= $file_types_array[$x]."."; 
      } 
    } 
    $form .= "</form>"; 
    echo($form); 
  }else{ 
    foreach($_FILES["file"]["error"] as $key => $value){ 
      if($_FILES["file"]["name"][$key]!=""){ 
        if($value==UPLOAD_ERR_OK){ 
          $origfilename = $_FILES["file"]["name"][$key]; 
          $filename = explode(".", $_FILES["file"]["name"][$key]); 
          $filenameext = $filename[count($filename)-1]; 
          unset($filename[count($filename)-1]); 
          $filename = implode(".", $filename); 
          $filename = substr($filename, 0, 15).".".$filenameext; 
          $file_ext_allow = FALSE; 
          for($x=0;$x<count($file_types_array);$x++){ 
            if($filenameext==$file_types_array[$x]){ 
              $file_ext_allow = TRUE; 
            } 
          } 
          if($file_ext_allow){ 
            if($_FILES["file"]["size"][$key]<$max_file_size){ 
              if(move_uploaded_file($_FILES["file"]["tmp_name"][$key], $upload_dir.$filename)){ 
                echo("<div id='message' class='updated fade'><p><strong>File uploaded successfully. - ".$filename."</strong></p></div>"); 
              }else{ 
                echo("<div id='message' class='error'><p><strong>".$origfilename." was not successfully uploaded</strong></p></div>"); 
              } 
            }else{ 
              echo("<div id='message' class='error'><p><strong>".$origfilename." was too big, not uploaded</strong></p></div>"); 
            } 
          }else{ 
            echo("<div id='message' class='error'><p><strong>".$origfilename." had an invalid file extension, not uploaded</strong></p></div>");           } 
        }else{ 
          echo("<div id='message' class='error'><p><strong>".$origfilename." was not successfully uploaded</strong></p></div>"); 
        } 
      } 
    } 
  } 
} 
/*
uploader([int num_uploads [, arr file_types [, int file_size [, str upload_dir ]]]]); 

num_uploads = Number of uploads to handle at once. 

file_types = An array of all the file types you wish to use. The default is txt only. 

file_size = The maximum file size of EACH file. A non-number will results in using the default 1mb filesize. 

upload_dir = The directory to upload to, make sure this ends with a / 
*/ 

function load_events_to_db(){


global $wpdb,$events_lang;
	$events_detail_tbl = get_option ( 'events_detail_tbl' );
	$curdate = date ( "Y-m-j" );
	$month = date ('M');
	$day = date('j');
$year = date('Y');

$fieldseparator = ",";
$lineseparator = "\n";
$csvfile = ER_UPLOADURL."events.csv";

 

  function getCSVValues($string, $separator=",")
    {
        $elements = explode($separator, $string);
        
        for ($i = 0; $i < count($elements); $i++) 
        {
            $nquotes = substr_count($elements[$i], '"');
            
            if ($nquotes %2 == 1)
            {
                for ($j = $i+1; $j < count($elements); $j++) 
                {
                    if (substr_count($elements[$j], '"') > 0) 
                    {
                        // Put the quoted string's pieces back together again
                        array_splice($elements, $i, $j-$i+1,
                        implode($separator, array_slice($elements, $i, $j-$i+1)));
                        break;
                    }
                }
            }
            
            if ($nquotes > 0) 
            {
                // Remove first and last quotes, then merge pairs of quotes
                $qstr =& $elements[$i];
                $qstr = substr_replace($qstr, '', strpos($qstr, '"'), 1);
                $qstr = substr_replace($qstr, '', strrpos($qstr, '"'), 1);
                $qstr = str_replace('""', '"', $qstr);
            }
        }
        
        return $elements;
    }
    
if(!file_exists($csvfile)) {
	echo "<div id='message' class='error'><p><strong>Import Error: File not found. Make sure you specified the correct path.</strong></p></div>";
	exit;
}

$file = fopen($csvfile,"r");

if(!$file) {
	echo "<div id='message' class='error'><p><strong>Import Error: Error opening data file.</strong></p></div>";
	exit;
}

$size = filesize($csvfile);

if(!$size) {
	echo "<div id='message' class='error'><p><strong>Import Error: File is empty.</strong></p></div>";
	exit;
}
   

    
    $file = file_get_contents($csvfile);
    $file = str_replace("'","\'",$file);
    $dataStrings = explode("\r", $file);
    
    $i = 0;
    foreach ( $dataStrings as $data ){
	++$i; 

    for ( $j = 0; $j < $i; ++$j )
    
        $strings = getCSVValues( $dataStrings[$j] );
        
      //echo "valid is :'".$valid."'";
    if (array_key_exists('2', $strings)) {
    //echo "The  element is in the array";
	$skip = $strings[0];
	
	if ($skip >= "1"){
	
		$sql = "INSERT INTO " . $events_detail_tbl . " (event_name, event_desc, event_location, display_desc, image_link, header_image, event_identifier, more_info, 				start_month, start_day, start_year, start_time, start_date, end_month, end_day, end_year, end_date, end_time, reg_limit, event_cost, custom_cur, multiple,
			 allow_checks,send_mail, is_active, conf_mail) VALUES('$strings[1]', '$strings[2]', '$strings[3]', '$strings[4]', '$strings[5]', '$strings[6]', '$strings[7]',
				'$strings[8]','$strings[9]', '$strings[10]', '$strings[11]', '$strings[12]', '$strings[13]','$strings[14]', '$strings[15]',
			'$strings[16]', '$strings[17]', '$strings[18]', '$strings[19]', '$strings[20]', '$strings[21]','$strings[22]', '$strings[23]', '$strings[24]', '$strings[25]',				'$strings[30]')";
				
       		
			$wpdb->query ( $sql );
            
            print mysql_error();
		
		}}

        

        
 }   


unlink($csvfile);
if(!file_exists($csvfile)) {
	echo "<div id='message' class='updated fade'><p><strong>Successful Import: Upload file has been deleted.</strong></p></div>";

}
$tot_records = $i - "2";
echo "<div id='message' class='updated fade'><p><strong>Successful Import: Added a total of $tot_records events to the database.</strong></p></div>";

}


?>