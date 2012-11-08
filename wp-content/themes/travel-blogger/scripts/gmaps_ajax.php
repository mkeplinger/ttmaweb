<?php
include_once('../../../../wp-config.php');
include_once('../../../../wp-includes/wp-db.php');
include_once('../../../../wp-includes/link-template.php');

$limit = $_GET['limit'];

function parseToXML($htmlStr)  {
	$xmlStr=str_replace('<','&lt;',$htmlStr); 
	$xmlStr=str_replace('>','&gt;',$xmlStr); 
	$xmlStr=str_replace('"','&quot;',$xmlStr); 
	$xmlStr=str_replace("'",'&#39;',$xmlStr); 
	$xmlStr=str_replace("&",'&amp;',$xmlStr); 
	return $xmlStr; 
}

$query = sprintf("
    SELECT wposts.*,wpostmeta.*
    FROM %s wposts, %s wpostmeta
    WHERE wposts.ID = wpostmeta.post_id 
    AND wpostmeta.meta_key = 'exp_post_geo_address'
    AND wposts.post_status = 'publish' 
    AND wposts.post_type = 'post'
    ORDER BY wposts.post_date DESC
    LIMIT %s
 ",
mysql_real_escape_string($wpdb->posts),
mysql_real_escape_string($wpdb->postmeta),
mysql_real_escape_string($limit)
);
$locations = $wpdb->get_results($query, OBJECT);

if (!$locations) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
foreach ($locations as $location){
	$geo_data = maybe_unserialize($location->meta_value);
	if(!is_array($geo_data))
		continue;
	
	echo '<marker ';
	echo 'lat="' . parseToXML($geo_data['lat']) . '" ';
	echo 'lng="' . parseToXML($geo_data['lng']) . '" ';
	echo 'address="' . parseToXML($geo_data['address']) . '" ';
	echo 'permalink="' . parseToXML(get_permalink($location->ID)) . '" ';
	echo '/>';
}

// End XML file
echo '</markers>';

?>