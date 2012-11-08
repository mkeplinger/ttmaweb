<?php 
/*---------------------------------------------------------------------------------------------
 * api.php
 *
 * @version 1.0.6
 ---------------------------------------------------------------------------------------------*/
 
/*---------------------------------------------------------------------------------------------
 * Matrix_object
 *
 * @author Elliot Condon
 * @since 1.0.0
 * 
 ---------------------------------------------------------------------------------------------*/
class Matrix_object
{
    function Matrix_object($variables)
    {
    	foreach($variables as $key => $value)
    	{
    		$this->$key = $value;
    	}
    }
    
}

/*---------------------------------------------------------------------------------------------
 * get_cfm
 *
 * @author Elliot Condon
 * @since 1.0.6
 * 
 ---------------------------------------------------------------------------------------------*/
function get_cfm($cfm_title, $post_id = null)
{
	return get_cf_matrix($cfm_title, $post_id);
}


/*---------------------------------------------------------------------------------------------
 * get_cf_matrix
 *
 * @author Elliot Condon
 * @since 1.0.0
 * 
 ---------------------------------------------------------------------------------------------*/
function get_cf_matrix($cfm_title, $post_id = null)
{
	global $cf_matrix;
	global $wpdb;
	global $post;
	
	if(!$post_id)
	{
		$post_id = $post->ID;
	}
	
	$matrix_objects = array();
    $matrix_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='cf_matrix'", $cfm_title ));
    
    // if no ID, return NULL
    if(!$matrix_id){ return NULL; }
    
    $cols = $cf_matrix->matrix_post_type->get_cfm_cols($matrix_id);
    $rows = $cf_matrix->matrix_post_type->get_cfm_rows($matrix_id, $post_id);
	
	//print_r($cols);
	//print_r($rows);	
	
	if($rows)
	{
		foreach($rows as $row)
		{
			$variables = array();
			foreach($cols as $col)
			{
				if($col['type'] == 'textarea')
				{
					// if textarea, ln2br for formatting
					$variables[$col['name']] = nl2br($row[$col['name']]);
				}
				elseif($col['type'] == 'select_page')
				{
					$variables[$col['name']] = get_permalink($row[$col['name']]);
				}
				elseif($col['type'] == 'true_false')
				{
					if($row[$col['name']] == 'true')
					{
						$variables[$col['name']] = true;
					}
					else
					{
						$variables[$col['name']] = false;	
					}

				}
				elseif($col['type'] == 'wysiwyg')
				{
					$variables[$col['name']] = apply_filters('the_content',$row[$col['name']]); 
				}
				else
				{
					// if text, image, just return the value
					$variables[$col['name']] = $row[$col['name']];
				}

			}
			$matrix_objects[] = new Matrix_object($variables);
			
		}
		return $matrix_objects;
		
	}
	else
	{
		return null;
	}    
}

?>