<?php
/*-------------------------------------------------------------------------------------------------
	MATRIX POST TYPE
	
	this file creates an object which creates functions and attaches them to wordpress actions
-------------------------------------------------------------------------------------------------*/
class Matrix_post_type
{
	var $name;
	var $dir;
	var $plugin_dir;
	var $plugin_path;
	
	/*---------------------------------------------------------------------------------------------
	 * Matrix_post_type Constructor
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 * @param object: wp_matrix to find parent variables.
	 ---------------------------------------------------------------------------------------------*/
	function Matrix_post_type($parent)
	{
		$this->name = $parent->name;					// Plugin Name
		$this->plugin_dir = $parent->dir;				// Plugin directory
		$this->plugin_path = $parent->path;				// Plugin Absolute Path
		$this->dir = plugins_url('',__FILE__);			// This directory
		
		// Set up Actions
		add_action('init', array($this, 'create_custom_post'));
		add_action('admin_head', array($this, 'create_meta_boxes'));
		add_action('save_post', array($this, 'save_post'));
		
		// important: note the priority of 99, the js needs to be placed after tinymce loads
		add_action('admin_print_footer_scripts',array($this, 'my_admin_print_footer_scripts'),99);

		return true;
	}
	
	/*---------------------------------------------------------------------------------------------
	 * Create Custom Post
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function create_custom_post()
	{
		$labels = array(
			'name' => __( 'Custom&nbsp;Field&nbsp;Matrix', 'cfm' ),
			'singular_name' => __( 'Custom Field Matrix', 'cfm' ),
			'search_items' =>  __( 'Search Custom Field Matrixes' , 'cfm' ),
			'all_items' => __( 'All Custom Field Matrixes' , 'cfm' ),
			'parent_item' => __( 'Parent Custom Field Matrix' , 'cfm' ),
			'parent_item_colon' => __( 'Parent Custom Field Matrix:' , 'cfm' ),
			'edit_item' => __( 'Edit Custom Field Matrix' , 'cfm' ),
			'update_item' => __( 'Update Custom Field Matrix' , 'cfm' ),
			'add_new_item' => __( 'Add New Custom Field Matrix' , 'cfm' ),
			'new_item_name' => __( 'New Custom Field Matrix Name' , 'cfm' ),
		); 	
		
		$supports = array(
			'title',
			//'custom-fields'
		);
		
		register_post_type('cf_matrix', array(
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'_builtin' =>  false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array("slug" => "cf_matrix"),
			'query_var' => "cf-matrix",
			'supports' => $supports,
		));
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * Meta Boxes
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function mb_1() 
	{
		include('meta_box_1.php');
	}
	
	function mb_2() 
	{
		include('meta_box_2.php');
	}
	
	function mb_3($post, $args) 
	{
		include('meta_box_3.php');
	}
	
	function mb_5($post, $args) 
	{
		include('meta_box_5.php');
	}

	/*---------------------------------------------------------------------------------------------
	 * Create HTML Input
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function create_input($options)
	{
		$defaults = array(
			'type' 	=> 	'',
			'name'	=>	'',
			'value'	=>	'',
			'options' => array('select_choices' => array()),
		);
		$options = array_merge($defaults, $options);
		
		switch($options['type'])
		{
			
			// Text
			case 'text':
				echo '<input type="text" name="'.$options['name'].'" value="'.$options['value'].'" />';
				break;
			
			// Textarea
			case 'textarea':
				echo '<textarea name="'.$options['name'].'" rows="5">'.$options['value'].'</textarea>';
				break;
				
			// File
			case 'image':
				if($options['value'] != '')
				{
					echo '<a href="#" class="remove_image"></a>';
					echo '<img src="'.$options['value'].'" width="100" height="100"/>';
					echo '<input type="hidden" name="'.$options['name'].'" value="'.$options['value'].'" />';
					echo '<input class="hide" type="file" name="'.$options['name'].'"/>';
				}
				else
				{
					echo '<a href="#" class="remove_image hide"></a>';
					echo '<input type="hidden" name="'.$options['name'].'" value="'.$options['value'].'" />';
					echo '<input type="file" name="'.$options['name'].'"/>';
					
				}

				break;
				
			// Select
			case 'select':
				echo '<select name="'.$options['name'].'">';
				foreach($options['options']['select_choices'] as $choice)
				{
					$selected = '';
					if($choice['value'] == $options['value'])
					{
						$selected = 'selected="yes"';
					}
					echo '<option value="'.$choice['value'].'" '.$selected.' >'.$choice['label'].'</option>';
				}
				echo '</select>';
				break;
				
			// Checkbox
			case 'checkbox';
				echo '<ul class="cfm_checkbox">';
				foreach($options['options']['select_choices'] as $choice)
				{
					$selected = '';
					if($choice['value'] == $options['value'])
					{
						$selected = 'checked="checked"';
					}
					echo '<li><input type="checkbox" name="'.$options['name'].'" value="'.$choice['value'].'" '.$selected.' />'.$choice['label'].'</li>';
				}
				echo '</ul>';
				break;
			
			// Checkbox
			case 'wysiwyg';
				//echo '<div id="poststuff">';
				//echo wp_richedit_pre($options['value']);
				//the_editor(wp_richedit_pre($options['value']),$options['name'],'',true);
				//echo '</div>';
				echo '<div class="cfm_wysiwyg"><textarea name="'.$options['name'].'">';
				echo wp_richedit_pre($options['value']);
				echo '</textarea></div>';
				break;
		}

	}
	
	/*---------------------------------------------------------------------------------------------
	 * WYSIWYG
	 *
	 * @author Elliot Condon
	 * @since 1.0.6
	 * 
	 ---------------------------------------------------------------------------------------------*/

	

function my_admin_print_footer_scripts()
{
	?><script type="text/javascript">
	
		jQuery(function($)
		{
			
		});
	</script><?php
}

	/*---------------------------------------------------------------------------------------------
	 * Create Meta Boxes (on the fly)
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	
	function create_meta_boxes() 
	{
		// shows hidden custom fields
		//echo "<style type='text/css'>#postcustom .hidden { display: table-row; }</style>\n";
		echo '<link rel="stylesheet" media="all" type="text/css" href="'.$this->plugin_dir.'/css/cf_matrix_edit.css" />';
		
		global $post;

		if(get_post_type($post) == 'cf_matrix')
		{
			
			add_meta_box('cfm_mb1', 'Matrix Columns', array($this, 'mb_1'), 'cf_matrix', 'normal', 'high');
			if(!$this->cfm_user())
			{
				add_meta_box('cfm_mb5', 'Matrix Options', array($this, 'mb_5'), 'cf_matrix', 'normal', 'high');
			}
			add_meta_box('cfm_mb2', 'Assign to edit screens', array($this, 'mb_2'), 'cf_matrix', 'normal', 'high');
		}
		else
		{
			$matrixes = get_posts(array(
				'numberposts' 	=> 	-1,
				'post_type'		=>	'cf_matrix'
			));
			
			if($matrixes)
			{
				foreach($matrixes as $matrix)
				{
					$add_box = false;
					
					// get options of matrix
					$location = $this->get_cfm_location($matrix->ID);
					//print_r($location);
					
					// post type
					if($location['post_type'] != '')
					{
						$post_types = explode(',',str_replace(' ','',$location['post_type']));
						if(in_array(get_post_type($post), $post_types)) {$add_box = true; }
					}
					
					// page slug
					if($location['page_slug'] != '')
					{
						$page_slugs = explode(',',str_replace(' ','',$location['page_slug']));
						if(in_array($post->post_name, $page_slugs)) {$add_box = true; }
					}
					
					// post ID
					if($location['post_id'] != '')
					{
						$post_ids = explode(',',str_replace(' ','',$location['post_id']));
						if(in_array($post->ID, $post_ids)) {$add_box = true; }
					}
					
					// page template
					if($location['page_template'] != '')
					{
						$page_template = explode(',',str_replace(' ','',$location['page_template']));
						if(in_array(get_post_meta($post->ID,'_wp_page_template',true), $page_template)) {$add_box = true;}
					}
					
					if($add_box == true)
					{
						
						add_meta_box('cfm_mb3_'.$matrix->ID, 'CF Matrix: '.get_the_title($matrix->ID), array($this, 'mb_3'), get_post_type($post), 'normal', 'high', array('matrix' => $matrix));
					}
				}
				
			}
		}


		
	} 
	

	/*---------------------------------------------------------------------------------------------
	 * Save Post
	 *
	 * @author Elliot Condon
	 * @since 1.0.0
	 * 
	 ---------------------------------------------------------------------------------------------*/
	function save_post($post_id) 
	{
		// verify this with nonce because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['ei_noncename'], 'ei-n')) return $post_id;
	
		// do not save if this is an auto save routine
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		
		global $flag;
		if ($flag != 0) return $post_id;
		
		if(wp_is_post_revision($post_id)) $post_id = wp_is_post_revision($post_id);
		
		if($_POST['cmf_page'] == 'edit')
		{
			// delete _cfm custom fields
			$this->delete_cfm_custom_fields($post_id);
			
			foreach($_POST['cmf'] as $matrix_id => $matrix)
			{
				// images
				if(!empty($_FILES['cmf']['name'][$matrix_id]['rows']))
				{
					foreach ($_FILES['cmf']['name'][$matrix_id]['rows'] as $key => $row) 
					{
						// looping through matrix columns. $key = input name, $names = array of images names
						foreach($row as $col_name => $value)
						{
							$image = array(
								'name'		=>	$_FILES['cmf']['name'][$matrix_id]['rows'][$key][$col_name],
								'type' 		=>  $_FILES['cmf']['type'][$matrix_id]['rows'][$key][$col_name],
								'tmp_name' 	=> 	$_FILES['cmf']['tmp_name'][$matrix_id]['rows'][$key][$col_name],
								'error'		=> 	$_FILES['cmf']['error'][$matrix_id]['rows'][$key][$col_name],
								'size' 		=> 	$_FILES['cmf']['size'][$matrix_id]['rows'][$key][$col_name],
							);
							
							if($image['name'] == '')
							{
								if($matrix['rows'][$key][$col_name] == 'remove')
								{
									$matrix['rows'][$key][$col_name] = '';
								}
							}
							else
							{
								$override = array('test_form' => false);
		       					$file = wp_handle_upload( $image, $override );
		       					$matrix['rows'][$key][$col_name] = $file['url'];
							}
							
							
						}//foreach($row as $col_name => $value)
						
					} // foreach ($_FILES['cf_matrix']['name'][$matrix_id]['rows'] as $key => $row) 

				} // if(isset($_FILES['cf_matrix']))
				
				
				// loop through each column and save it
				$row_no = 0;
				foreach($matrix['rows'] as $row)
				{
					$row_no++;
					foreach($row as $col_name => $value)
					{
						update_post_meta($post_id, '_cfm_'.$matrix_id.'_'.$row_no.'_'.$col_name, $value);
					}					
				}

			}
			
		}
		elseif($_POST['cmf_page'] == 'create')
		{
			// delete _cfm custom fields
			$this->delete_cfm_custom_fields($post_id);
			
			// loop through each column and save it
			$i = 0;

			foreach($_POST['cmf']['cols'] as $col)
			{
				// increase counter
				$i++;
				
				add_post_meta($post_id, '_cfm_col_'.$i.'_label', $col['label']);
				add_post_meta($post_id, '_cfm_col_'.$i.'_name', $col['name']);
				add_post_meta($post_id, '_cfm_col_'.$i.'_type', $col['type']);
				add_post_meta($post_id, '_cfm_col_'.$i.'_options', addslashes(serialize($col['options'])));
				
			}
			
			// update location
			if($_POST['cmf']['location'])
			{
				foreach($_POST['cmf']['location'] as $key => $value)
				{
					update_post_meta($post_id, '_cfm_location_'.$key, $value);
				}
			}
			
			
			// update options
			if($_POST['cmf']['options'])
			{
				foreach($_POST['cmf']['options'] as $key => $value)
				{
					update_post_meta($post_id, '_cfm_option_'.$key, $value);
				}
			}
			

			
		}
		
		$flag = 1;
		
	}
	
    
    /*---------------------------------------------------------------------------------------------
	 * Select Page List
	 *
	 * @author Elliot Condon
	 * @since 1.0.3
	 * 
	 ---------------------------------------------------------------------------------------------*/
    function select_page_list()
    {
    	$post_types = get_post_types(array('public' => true));
		foreach($post_types as $key => $value)
		{
			if($value == 'attachment')
			{
				unset($post_types[$key]);
			}
		}

		$posts = get_posts(array(
			'numberposts' 	=> 	-1,
			'post_type'		=>	$post_types,
			'orderby'		=>	'title',
			'order'			=>	'ASC'
		));
		
		$results = array();
		if($posts)
		{
			foreach($posts as $post)
			{
				$title = get_the_title($post->ID);
				
				if(strlen($title) > 33)
				{
					$title = substr($title,0,30).'...';
				}
				
				$results[] = array('value' => $post->ID, 'label' => $title.' ('.get_post_type($post->ID).')');
			}			
		}
		else
		{
			$results[] = null;
		}
		
							
    	return $results;
    }
    
    /*---------------------------------------------------------------------------------------------
	 * get_cell_types
	 *
	 * @author Elliot Condon
	 * @since 1.0.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
    function get_cell_types()
	{
		$cfm_user = "94c4u263y4o5e46473a443w2g4r4q423y263g21624c4s464n333k443y2f4j2z5d4r4l4q343j4z22363o3e49494k5s5s373s443y2g4a2v394t4s4g2e4u49453q2c454z2w24386s4d41313b22664c4m4e4i2d4l46333d4q326d4x4q474065484e243r44384z2a2j424f594d4g2530673b463d2p3l5t4k424r4d4f5c27305o5e48473a443w2g4r4q423y263g21624c4s464n333k443z2f4j2o5l44484l5f2z2k47313c4c2k484h5l5u5i283p44374q253e2i3l474m56482u292n4d4d4b4g2g425o563u203h533q2z205r4c4v263h216h5u5t4e4g25316y2w2t233i2p4k5d454a4l4c5k424d4b4r383y563w2z2q3h41434e4k4o313r45374e4s3o3i4k4h4s3z205x4";
		
		$cfm_admin = "44g42373x4n5235423e573z2d456y563t2f4i2t474j4n454d2e4j4e4s243g2o47456s4d203s4z28413s3j4a484j5g4r323w57313d4o335d4o416i263z4g403p223g5y264y2x4p423v2f4i2p424l4m4k5d2h4q47323c4e2168416t4a4x5j5g5i2z206630343h2e41454k5c4q3z2p4430303r3w384p4t424x584j5h283z4n5237423e573z2d456y563t2f4i2t474j4n454d2e4j4e4v243g2r4r406h4q4q4g4b2e4m4g40373g2q554j584p5j283y5c4a463a28514u3j3y3v3b3d2e4t4p5y2x25354037463u4k473u2d4d24684l4r4j5e2g4m4b42373p3n5s59474m5i263p453v2y2d2r514i554i4g233l4f4x2t2z2j2e3s544p5c4h2t2m3e3q5f4i5j28386n5d403z2i563x243y4l4y2w2d4a22614i4r494c2g4r413q2y2z2p3m4n5j4e494y534x544k5e4j2e4x5d413y2r3k48484c4e4a223y5y284y2g2m3d4g4s5i223u4i4z28423j553z24356n4f4w2b4j2v4g5q506d4a2g4r453v2v203a2n4x5l4l5z3c474g4l4m5i223k473u2d4d2u584b4b4q5e2g4m4b4y2z2d4o335q4l4m5g2u2e2h334c4m4k5a2f4t4f443y213i563t2z205k4d4w2g4h2v474q5p4m5d2c4s47394c4p3r454t5d4a4q4h213j41384y2q3c4748494e4r373m4z253z2p3b306m4p4a4t5r4v5d2c42525";
		
		$key = get_option('cfm_pro_key');
		
		try {
			if(!$key)
			{
				throw new Exception('admin: no key');
			}
			
			if(!$this->decode($cfm_admin,$key))
			{
				throw new Exception("admin: couldn't decode");
			}
			
			if(!is_array(unserialize($this->decode($cfm_admin,$key))))
			{
				throw new Exception('admin: not array');
			}
			
			return $this->string_to_clean_array($this->decode($cfm_admin,$key));
		} catch(Exception $e)
		{
			$key = "q5v35l3vn2";
			
			try {
				if(!$key)
				{
					throw new Exception('user: no key');
				}
				
				if(!$this->decode($cfm_user,$key))
				{
					throw new Exception("user: couldn't decode");
				}
				
				if(!is_array(unserialize($this->decode($cfm_user,$key))))
				{
					throw new Exception('user: not array');
				}
				
				return $this->string_to_clean_array($this->decode($cfm_user,$key));
	
			} catch(Exception $e)
			{
				return array('error' => $e->getMessage());
				//return $e->getMessage();
			}
		}
		
		

	}
	
	
	
	
	function decode($string,$key) {
	    $key = sha1($key);
	    $strLen = strlen($string);
	    $keyLen = strlen($key);
	    for ($i = 0; $i < $strLen; $i+=2) {
	        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
	        if ($j == $keyLen) { $j = 0; }
	        $ordKey = ord(substr($key,$j,1));
	        $j++;
	        $hash .= chr($ordStr - $ordKey);
	    }
	    return $hash;
	}
	
	
	function cfm_user()
	{
		if(count($this->get_cell_types())>4)
		{
			return false;
		}
		else
		{
			return true;
		}
		 
	}
	
	
	/*---------------------------------------------------------------------------------------------
	 * string_to_clean_array
	 *
	 * @author Elliot Condon
	 * @since 1.0.4
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function string_to_clean_array($string)
	 {
	 	if(!is_array(unserialize($string)))
	 	{
	 		return array();
	 	}
	 	
	 	$array = unserialize($string);
	 	
		foreach($array as $key => $value)
		{
			if(is_array($value)) // options is an array, so unserialize it and strip slashes
			{
				$child_array = array();
				foreach($value as $child_key => $child_value)
				{
					$child_array[$child_key] = stripslashes($child_value);
				}
				$value[$key] = $child_array;
			}
			else // everythis else is a simple string.
			{
				$array[$key] = stripslashes($value);
			}	
		}
		return $array;
	 }
	 
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_cfm_rows
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_cfm_rows($matrix_id, $post_id)
	 {
	 	$rows = array();
	 	$cols = $this->get_cfm_cols($matrix_id);
	 	
	 	// get all _cfm custom fields
	 	$cfm_custom_fields = get_post_custom($post_id);
	 	foreach($cfm_custom_fields as $key => $values)
		{
			if(strpos($key, '_cfm_'.$matrix_id) === false)
			{
				unset($cfm_custom_fields[$key]);
			}
		}
	 	
	 	for($row_no = 1; $row_no <= 99; $row_no++)
		{
			$row = array();
			foreach($cols as $col)
			{
				$key = '_cfm_'.$matrix_id.'_'.$row_no.'_'.$col['name'];
				if(empty($cfm_custom_fields[$key][0])){continue;}
				
				$row[$col['name']] = $cfm_custom_fields[$key][0];
			}
			
			if(empty($row)){continue;}
			
			$rows[$row_no] = $row;
		}
		
	 	return $rows;
	 }
	 
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_cfm_cols
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_cfm_cols($matrix_id)
	 {
	 	$cols = array();
	 	for($i = 1; $i < 99; $i++)
		{
			if(get_post_meta($matrix_id, '_cfm_col_'.$i.'_label', true))
			{
				$cols[] = array(
					'id'		=> 	$i,
					'label'		=>	get_post_meta($matrix_id, '_cfm_col_'.$i.'_label', true),
					'name'		=>	get_post_meta($matrix_id, '_cfm_col_'.$i.'_name', true),
					'type'		=>	get_post_meta($matrix_id, '_cfm_col_'.$i.'_type', true),
					'options'	=> 	$this->string_to_clean_array(
										get_post_meta($matrix_id, '_cfm_col_'.$i.'_options', true)
									),
				);
			}
			else
			{
				// data doesnt exist, break loop
				break;
			}
		}
		
		return $cols;
	 }
	 
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_cfm_location
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_cfm_location($matrix_id)
	 {
	 	$location = array(
			'post_type'	=>	get_post_meta($matrix_id, '_cfm_location_post_type', true),	
			'page_slug'	=>	get_post_meta($matrix_id, '_cfm_location_page_slug', true),
			'post_id'	=>	get_post_meta($matrix_id, '_cfm_location_post_id', true),
			'page_template'	=>	get_post_meta($matrix_id, '_cfm_location_page_template', true),
		);
		return $location;
	 }
	 
	 
	 /*---------------------------------------------------------------------------------------------
	 * get_cfm_options
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function get_cfm_options($matrix_id)
	 {
	 	$options = array(
			'row_limit'	=>	get_post_meta($matrix_id, '_cfm_option_row_limit', true),	
		);
		return $options;
	 }
	 
	 /*---------------------------------------------------------------------------------------------
	 * delete_cfm_custom_fields
	 *
	 * @author Elliot Condon
	 * @since 1.1
	 * 
	 ---------------------------------------------------------------------------------------------*/
	 function delete_cfm_custom_fields($post_id)
	 {
	 	
		foreach(get_post_custom($post_id) as $key => $values)
		{
			if(strpos($key, '_cfm') !== false)
			{
				// this custom field needs to be deleted!
				delete_post_meta($post_id, $key);
			}
		}
	 }
	 
	 
	 
}

?>