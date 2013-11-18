<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	func.php
 * @version		1.0.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook implementations for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;
use Tygh\Mailer;
use Tygh\Navigation\LastView;

require_once 'lib/fn.tsp_reorder_reminders.php';

//---------------
// HOOKS
//---------------


/**
 * Change order status
 *
 * @since 1.0.0
 *
 * @param string $status_to New order status (one char)
 * @param string $status_from Old order status (one char)
 * @param array $order_info Array with order information
 * @param array $force_notification Array with notification rules
 * @param array $order_statuses Array of order statuses.
 * @param boolean $place_order Place order or not
 * @return boolean
 *
 * @return none
 */
function fn_tsp_reorder_reminders_change_order_status( $status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order )
{
	$order_id = $order_info['order_id'];	
	$reminder_id = db_get_field("SELECT `id` FROM ?:addon_tsp_reorder_reminders WHERE `order_id` = ?i", $order_id);
	
	// If the reminder has not been created and the admin is changing the status
	if (!$reminder_id && $status_to == 'P' )
	{
		// If the user purchased a product and wants to be reminded to reorder
		fn_tspror_create_reminder($order_info);
	}//end if
}//end fn_tsp_reorder_reminders_change_order_status

/**
 * Finish payment
 *
 * @since 1.0.0
 *
 * @param int $order_id Required - The Order ID
 * @param array $pp_response Required - Response from the payment processor
 * @param bool $force_notification Required - Force email notifications
 *
 * @return none
 */
function fn_tsp_reorder_reminders_finish_payment($order_id, $pp_response, $force_notification)
{
	$order_info = fn_get_order_info($order_id);
	
	// FIXME: What is the admin want's to override payment other than a test payment
	if (($order_info['payment_info']['order_status'] == 'P'))
	{
		// If the user purchased a product and wants to be reminded to reorder
		fn_tspror_create_reminder($order_info);
	}//endif
}//end fn_tsp_reorder_reminders_finish_payment

/**
 * Since orders are directly related to reminders if an order is deleted, delete
 * the reminder as well
 *
 * @since 1.0.0
 *
 * @param int $order_id Required - The Order ID
 *
 * @return none
 */
function fn_tsp_reorder_reminders_delete_order($order_id)
{
	$reminder_id = db_get_field("SELECT `id` FROM ?:addon_tsp_reorder_reminders WHERE `order_id` = ?i", $order_id);
	
	fn_tspror_delete_reminder($reminder_id);
}//end fn_tsp_reorder_reminders_delete_order

/**
 * Delete product metadata
 *
 * @since 1.0.0
 *
 * @param int $product_id Required - The Product ID
 *
 * @return none
 */
function fn_tsp_reorder_reminders_delete_product_post($product_id)
{
	db_query("DELETE FROM ?:addon_tsp_reorder_reminders_product_metadata WHERE `product_id` = ?i", $product_id);
}//end fn_tsp_reorder_reminders_delete_product_post

/**
 * Function to update order_info with reminder information
 *
 * @since 1.0.0
 *
 * @param array $order_info Required (Ref) - Order information
 * @param array $additional_data Required (Ref) - Additional order information
 *
 * @return none
 */
function fn_tsp_reorder_reminders_get_order_info(&$order_info, &$additional_data)
{
	$key = 'products';
	
	if (array_key_exists( $key, $order_info ))
	{
		foreach ($order_info[$key] as $k => $v)
		{
			$product_id = $v['product_id'];
			$product_metadata = db_get_hash_array("SELECT * FROM ?:addon_tsp_reorder_reminders_product_metadata WHERE `product_id` = $product_id", 'field_name');
		
			$product_reminder = array();
		
			// If the product has reminder data store it in the order
			if (!empty($product_metadata))
			{
				$field_names = Registry::get('tspror_product_data_field_names');
					
				foreach ($field_names as $field_name => $fdata)
				{
					$value = "";
		
					// only display fields that have data
					if (array_key_exists($field_name, $product_metadata))
					{
						$value = $product_metadata[$field_name]['value'];
						if ($fdata['type'] == 'T')
						{
							$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
						}//endif
		
						$product_reminder[] = array(
								'title' => __($field_name),
								'value' => $value
						);
					}//endif
						
				}//endforeach;
					
				
				$order_info[$key][$k]['extra']['product_reminder'] = $product_reminder;
			}//endif
		}//endforeach;
	}//end if
}//end fn_tsp_reorder_reminders_get_order_info

/**
 * Changes request params before product options selecting
 *
 * @param array  $product_ids     Product ids
 * @param string $lang_code       Language code
 * @param bool   $only_selectable This flag forces to retreive the options with the certain types (default: select, radio or checkbox)
 * @param bool   $inventory       Get only options with the inventory tracking
 * @param bool   $only_avail      Get only available options
 * @param array  $options         The resulting array of the retrieved options
*/
function fn_tsp_reorder_reminders_get_product_options_post($product_ids, $lang_code, $only_selectable, $inventory, $only_avail, &$options)
{
	if ( is_array($product_ids) )
	{
		foreach ($product_ids as $product_id)
		{
			$product_addon_field_names = Registry::get('tspror_product_data_field_names');
		
			// Loop through the product addon fields and see if this product has it as an option
			foreach ( $product_addon_field_names as $key => $data )
			{
				$name = preg_replace("/tspror\_/", '', $key);
				$option_id = fn_tspror_get_product_field_id('tspror_product_option_'.$name.'_field_id');
					
				// if this product has it as an option then begin processing
				if ( array_key_exists($option_id, $options[$product_id]) )
				{
					// If the option has a default value assigned by the admin set it
					if ( array_key_exists('default_value_metadata_key', $data))
					{
						$default_data_key = $data['default_value_metadata_key'];
						$default_value = fn_tspror_get_product_metadata( $product_id, $default_data_key );
							
						if (!empty( $default_value ))
						{
							// if the option is a select then set the default value to the variant id
							if ( $data['type'] == 'S')
							{
								foreach ( $options[$product_id][$option_id]['variants'] as $variant_id => $variant_data )
								{
									// if this variant has the default value then set the option value to the variant id
									if ( $variant_data['variant_name'] == $default_value )
									{
										$options[$product_id][$option_id]['value'] = $variant_data['variant_id'];
									}//end if
								}//end foreach
							}//end if
							elseif ( $data['type'] == 'T' )
							{
								$options[$product_id][$option_id]['value'] = html_entity_decode($default_value, ENT_QUOTES, 'UTF-8');
							}//endif
							else
							{
								$options[$product_id][$option_id]['value'] = $default_value;
							}//end else
						}//end if
					}//end if
				}//end if
			}//end foreach
		}//end foreach
	}//end if
}//end fn_tsp_reorder_reminders_get_product_options

/**
 * Function to update the product metadata
 *
 * @since 1.0.0
 *
 * @param array $product_data Required (Ref) - Product information
 * @param int $product_id Required - Product ID
 * @param string $lang_code Required - The language code for the product
 * @param bool $create Required - Boolean to create or update product
 *
 * @return none
 */
function fn_tsp_reorder_reminders_update_product_post(&$product_data, $product_id, $lang_code, $create){

	if (!empty($product_id) && !empty($product_data))
	{
		$field_names = Registry::get('tspror_product_data_field_names');
		
		foreach ($field_names as $field_name => $fdata)
		{
		
			if (array_key_exists($field_name, $product_data))
			{
				$value = $product_data[$field_name];
				fn_tspror_update_product_metadata($product_id, $field_name, $value);
			}//endif		
		}//endforeach;

	}//endif
}//end fn_tsp_reorder_reminders_update_product_post
?>