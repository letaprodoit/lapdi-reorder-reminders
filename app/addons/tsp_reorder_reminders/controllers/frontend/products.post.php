<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	products.post.php
 * @version		2.0.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Products post hook for customer area
 * 
 */

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	return;
}//endif

use Tygh\Registry;

$product_id = $_REQUEST['product_id'];
$params = $_REQUEST;

// View Reminder Products: Show reminder information
if ($mode == 'view' && !empty($product_id))
{
	// Get current product data
	$product_metadata = db_get_hash_array("SELECT * FROM ?:addon_tsp_reorder_reminders_product_metadata WHERE `product_id` = $product_id", 'field_name');
	
	// If the product has metadata to display then display it
	if (!empty($product_metadata))
	{
		Registry::get('view')->assign('tspror_has_data', true); // don't show blank fields in customer area
		
		$product_data = fn_get_product_data($product_id, $auth, DESCR_SL, '', true, true, true, true, false, true, false);
		
		if (!empty($product_data))
		{
			$field_names = Registry::get('tspror_product_data_field_names');
		
			$product_addon_fields = array();
		
			foreach ($field_names as $field_name => $fdata)
			{
				$value = "";
					
				if ($fdata['admin_only'])
				{
					continue; // skip admin only fields
				}//endif
		
				// only display fields that have data
				if (array_key_exists($field_name, $product_metadata))
				{
					$value = $product_metadata[$field_name]['value'];
		
					if (!empty($fdata['options_func']))
					{
						$fdata['options'] = call_user_func_array($fdata['options_func'],$fdata['options_func_args']);
					}//endif
		
					if (!empty($fdata['value_func']))
					{
						$value = call_user_func($fdata['value_func']);
					}//endif
		
					if (!empty($fdata['default_value_metadata_key']))
					{
						if ( array_key_exists( $fdata['default_value_metadata_key'], $product_metadata ) )
						{
							$value = $product_metadata[$fdata['default_value_metadata_key']];
						}//end if
					}//endif
		
					if ($fdata['type'] == 'T')
					{
						$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
					}//endif
		
					$product_addon_fields[] = array(
							'title' => __($field_name),
							'name' => $field_name,
							'value' => $value,
							'icon' => $fdata['icon'],
							'width' => $fdata['width'],
							'class' => $fdata['class'],
							'type' => $fdata['type'],
							'hint' => $fdata['hint'],
							'options' => $fdata['options'],
							'readonly' => true //Customer will always view this as readonly
					);
				}//endif
		
			}//endforeach;
		
			Registry::get('view')->assign('tspror_product_addon_fields', $product_addon_fields);
		
		}//endif		
	}//endif
}//endif
?>