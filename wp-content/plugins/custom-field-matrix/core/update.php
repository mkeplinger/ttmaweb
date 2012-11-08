<?php

$version = get_option('cfm_version','1.0.6');

if(version_compare($version,'1.1') < 0)
{
	// Version is less than 1.1
	$matrixes = get_posts(array(
		'numberposts' 	=> 	-1,
		'post_type'		=>	'cf_matrix'
	));
			
	if($matrixes)
	{
		foreach($matrixes as $matrix)
		{
		
			// re-save locations
			$locations = $this->matrix_post_type->string_to_clean_array(get_post_meta($matrix->ID, 'cf_matrix_location', true));
			if($locations)
			{
				foreach($locations as $key => $value)
				{
					update_post_meta($matrix->ID, '_cfm_location_'.$key, $value);
				}
			}
			delete_post_meta($matrix->ID, 'cf_matrix_location');
			
			
			// re-save options
			$options = $this->matrix_post_type->string_to_clean_array(get_post_meta($matrix->ID, 'cf_matrix_options', true));
			if($options)
			{
				foreach($options as $key => $value)
				{
					update_post_meta($matrix->ID, '_cfm_option_'.$key, $value);
				}
			}
			delete_post_meta($matrix->ID, 'cf_matrix_options');
			
			
			// re-save cols
			$cols = array();
			foreach(get_post_meta($matrix->ID, 'cf_matrix_col', false) as $col)
			{
				$cols[] = $this->matrix_post_type->string_to_clean_array($col);
			} 
			 
			if($cols)
			{
				foreach($cols as $col)
				{
					$col_no = $col['id'];
					unset($col['id']);
					$col['options'] = addslashes(serialize($col['options']));
					foreach($col as $key => $value)
					{
						update_post_meta($matrix->ID, '_cfm_col_'.$col_no.'_'.$key, $value);
					}
					
				}
			}
			delete_post_meta($matrix->ID, 'cf_matrix_col');
		}
	}
	
	$pages = get_posts(array(
		'numberposts' 	=> 	-1,
		'post_type'		=>	get_post_types(array('public' => true))	
	));
	
	if($pages)
	{
		
		//echo '<pre>';
		foreach($pages as $page)
		{
			$this->matrix_post_type->delete_cfm_custom_fields($page->ID);
			// get all old cfm custom fields
		 	$cfm_custom_fields = get_post_custom($page->ID);
		 	
		 	foreach($cfm_custom_fields as $key => $values)
			{
				if(strpos($key, 'cf_matrix_row_') === false)
				{
					continue;
				}
				//print_r($values);
		 		//die;
				$matrix_id = str_replace('cf_matrix_row_','',$key);
				//print_r($values);
				//echo '<br><br>';
				
				// loop through the rows
				foreach($values as $value)
				{
					
					//echo '<br><br>';
					$value = unserialize($value);
		 			$row = $this->matrix_post_type->string_to_clean_array($value);
		 			//print_r($row);
					$row_no = $row['id'];
					unset($row['id']);
					
					foreach($row as $cell_name => $cell)
					{
						update_post_meta($page->ID, '_cfm_'.$matrix_id.'_'.$row_no.'_'.$cell_name, $cell);
					}
					
				}
				
				delete_post_meta($page->ID, $key);
				
			}
			
		}
		//echo '</pre>';
		//die;
	}
	
	update_option('cfm_version', $this->version );
}

/*---------------------------------------------------------------------------------------------
 * msort
 *
 * @author php.net
 * @since 1.0.1
 * 
 ---------------------------------------------------------------------------------------------*/
function msort($array, $id="id") {
    $temp_array = array();
    while(count($array)>0) {
        $lowest_id = 0;
        $index=0;
        foreach ($array as $item) {
            if (isset($item[$id]) && $array[$lowest_id][$id]) {
                if ($item[$id]<$array[$lowest_id][$id]) {
                    $lowest_id = $index;
                }
            }
            $index++;
        }
        $temp_array[] = $array[$lowest_id];
        $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
    }
    return $temp_array;
}
?>