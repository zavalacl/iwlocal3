<?php
	require('config.php');
	require("authenticate.php");
	require("functions/stewards_reports.php");
		
	if(! empty($_POST['cid'])){
		$category_id    = (int) $_POST['cid'];
		$subcategory_id = (int) $_POST['sid'];
		$counter        = (int) $_POST['counter'];
		$selected_item  = isset($_POST['selected_item']) ? (int) $_POST['selected_item'] : null;
		$json           = ($_POST['json']) ? true : false;
		
		// Return as JSON-formatted string?
		if($json){
		
			$items = getWorkDescriptionItems($category_id, $subcategory_id);
			if($items > 0){
				
				$output  = '{';
				
				foreach($items as $item){
					$output .= '"' . $item['item_id'] . '": "' . htmlentities($item['item'], ENT_QUOTES) . '", ';
				}
				
				$output = rtrim($output, ", ");
				$output .= '}';
				
				echo $output;
				exit;
			} else {
				echo 0;
				exit;
			}
		
		
		} else {
		
			// If this requires a description, show a text box...
			$subcategory_info = getWorkDescriptionItem($subcategory_id);
			if($subcategory_info['describe']){
				echo '<input type="text" name="wd' . $category_id . 'describe' . '_' . $counter . '" id="wd' . $category_id . 'describe' . '_' . $counter . '" value="Describe" onchange="$(\'#wdv_describe_' . $counter . '\').val(this.value);" />';
				exit;
				
			// ... Otherwise, show sub-categories, if applicable.
			} else {
			
				$items = getWorkDescriptionItems($category_id, $subcategory_id);
				if($items > 0){
					$output = '<select 
						name="wd' . $category_id . $subcategory_id . '_' . $counter . 
						'"id="wd' . $category_id . $subcategory_id . '_' . $counter .  
						'"onchange="' . 
							'$(\'#wdc' . $category_id . $subcategory_id . '_' . $counter . '\').empty();' .
							'getWorkDescriptionItems(' . $category_id . ', this.value, \'wdc' . $category_id . $subcategory_id . '_' . $counter . '\', ' . $counter . ');' .
							'if(this.value != \'other\'){ $(\'#wdv_' . $counter . '\').val(this.value); $(\'#wdv_other_' . $counter . '\').val(\'\');' .
						'}">';
					
					$output .= '<option value="">Select One</option>';
					
					foreach($items as $item){
						$output .= '<option value="' . $item['item_id'] . '"' . ($selected_item && $selected_item == $item['item_id'] ? ' selected="selected"' : '') . '>' . htmlentities($item['item'], ENT_QUOTES) . '</option>';
					}
					
					$output .= '<option value="other">Other</option>';
					$output .= '</select> ';
					$output .= '<div id="wdc' . $category_id . $subcategory_id . '_' . $counter . '"></div> ';
					
					echo $output;
					exit;
				} else {
					echo 0;
					exit;
				}
			}
		}
	}
