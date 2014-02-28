<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	fn.tsp_reorder_reminders.php
 * @version		1.1.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright ¬© 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Helper functions for addon
 * 
 */

if ( !defined('BOOTSTRAP') )	{ die('Access denied');	}

use Tygh\Registry;
use Tygh\Mailer;
use Tygh\Navigation\LastView;

//
// [Functions - Addon.xml Handlers]
//

/***********
 *
 * Function to uninstall languages
 *
 ***********/
function fn_tspror_uninstall_languages ()
{
	$names = array(
		'tsp_reorder_reminders',
		'tspror_details',
		'tspror_reminders',
		'tspror_reminders_appointments_menu_description',	
		'tspror_reminder_comment',
		'tspror_reminder_completed',
		'tspror_reminder_created',
		'tspror_reminder_date',
		'tspror_reminder_default',
		'tspror_reminder_expire',
		'tspror_reminder_in',
		'tspror_reminder_interval',
		'tspror_reminder_sent',
		'tspror_notification',
		'tspror_notification_msg',
		'tspror_title',
	);
	
	if (!empty($names)) 
	{
		db_query("DELETE FROM ?:language_values WHERE name IN (?a)", $names);
	}//endif
}//end fn_tspror_uninstall_languages

/***********
 *
 * Function to uninstall product fields
 *
 ***********/
function fn_tspror_install_product_fields () 
{	
	$default_option_fields = array(
		'tspror_product_option_reminder_field_id'
	);
	
	foreach ( $default_option_fields as $option_field_key )
	{
		// check to see if the field is already in the table (the global option already added) if it is not
		// then add it
		if ( !fn_tspror_get_product_field_id($option_field_key) )
		{
			if ($option_field_key == 'tspror_product_option_reminder_field_id')
			{
				// Install the global option fields
				$reminder_id = db_query('INSERT INTO ?:product_options ?e', array('company_id' => 1, 'position' => 200, 'option_type' => 'S', 'inventory' => 'N', 'required' => 'N', 'status' => 'A'));

				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_reorder_reminders_product_field_metadata (`key`,`option_id`) VALUES ('$option_field_key',$reminder_id)");
				
				// Install descriptions
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'en', 'option_id' => $reminder_id, 'option_name' => __('tspror_reminder'), 'option_text' => '', 'description' => '', 'comment' => __('tspror_reminder_comment'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'el', 'option_id' => $reminder_id, 'option_name' => __('tspror_reminder'), 'option_text' => '', 'description' => '', 'comment' => __('tspror_reminder_comment'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'es', 'option_id' => $reminder_id, 'option_name' => __('tspror_reminder'), 'option_text' => '', 'description' => '', 'comment' => __('tspror_reminder_comment'), 'inner_hint' => '', 'incorrect_message' => ''));
				db_query('INSERT INTO ?:product_options_descriptions ?e', array('lang_code' => 'fr', 'option_id' => $reminder_id, 'option_name' => __('tspror_reminder'), 'option_text' => '', 'description' => '', 'comment' => __('tspror_reminder_comment'), 'inner_hint' => '', 'incorrect_message' => ''));
				
				// Install option variants
				$var1 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 0, 'option_id' => $reminder_id, 'modifier' => 0.00));
				$var2 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 5, 'option_id' => $reminder_id, 'modifier' => 0.00));
				$var3 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 10, 'option_id' => $reminder_id, 'modifier' => 0.00));
				$var4 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 15, 'option_id' => $reminder_id, 'modifier' => 0.00));
				$var5 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 20, 'option_id' => $reminder_id, 'modifier' => 0.00));
				$var6 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 25, 'option_id' => $reminder_id, 'modifier' => 0.00));
				$var7 = db_query('INSERT INTO ?:product_option_variants ?e', array('position' => 30, 'option_id' => $reminder_id, 'modifier' => 0.00));
				
				// Store the global option fields
				db_query("INSERT INTO ?:addon_tsp_reorder_reminders_product_field_metadata (`key`,`option_id`,`variant_id`) VALUES
				('tspror_product_option_reminder_field_vars',$reminder_id,$var1),
				('tspror_product_option_reminder_field_vars',$reminder_id,$var2),
				('tspror_product_option_reminder_field_vars',$reminder_id,$var3),
				('tspror_product_option_reminder_field_vars',$reminder_id,$var4),
				('tspror_product_option_reminder_field_vars',$reminder_id,$var5),
				('tspror_product_option_reminder_field_vars',$reminder_id,$var6),
				('tspror_product_option_reminder_field_vars',$reminder_id,$var7)");
				
				// Install option variant descriptions
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var1, 'variant_name' => 'No Reminder'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var2, 'variant_name' => '1 Month'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var3, 'variant_name' => '2 Months'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var4, 'variant_name' => '3 Months'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var5, 'variant_name' => '6 Months'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var6, 'variant_name' => '9 Months'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'en', 'variant_id' => $var7, 'variant_name' => '1 Year'));
				
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var1, 'variant_name' => 'Den Ypenthýmisi̱'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var2, 'variant_name' => '1 mí̱nas'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var3, 'variant_name' => '2 mí̱nas'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var4, 'variant_name' => '3 mí̱nas'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var5, 'variant_name' => '6 mí̱nas'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var6, 'variant_name' => '9 mí̱nas'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'el', 'variant_id' => $var7, 'variant_name' => '1 étos'));

				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var1, 'variant_name' => 'ningún recordatorio'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var2, 'variant_name' => '1 mes'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var3, 'variant_name' => '2 mes'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var4, 'variant_name' => '3 mes'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var5, 'variant_name' => '6 mes'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var6, 'variant_name' => '9 mes'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'es', 'variant_id' => $var7, 'variant_name' => '1 año'));

				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var1, 'variant_name' => 'pas de rappel'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var2, 'variant_name' => '1 mois'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var3, 'variant_name' => '2 mois'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var4, 'variant_name' => '3 mois'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var5, 'variant_name' => '6 mois'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var6, 'variant_name' => '9 mois'));
				db_query('INSERT INTO ?:product_option_variants_descriptions ?e', array('lang_code' => 'fr', 'variant_id' => $var7, 'variant_name' => '1 année'));
			}//end elseif
		}//end if
	}//end foreach
}//end fn_tspror_install_product_fields

/***********
 *
 * Function to uninstall product filed metadata
 *
 ***********/
function fn_tspror_uninstall_product_field_metadata () 
{
	if (Registry::get('addons.tsp_reorder_reminders.delete_reminder_data') == 'Y')
	{
		// Get the product options
		$product_options = db_get_fields("SELECT `option_id` FROM ?:addon_tsp_reorder_reminders_product_field_metadata");
		
		if (!empty($product_options) && is_array($product_options))
		{
			// Delete the product options from all tables
			foreach ($product_options as $val)
			{
				db_query("DELETE FROM ?:product_options WHERE `option_id` = ?i", $val);
				db_query("DELETE FROM ?:product_options_descriptions WHERE `option_id` = ?i", $val);
			}//endforeach
		}//endif
		
		// Get the Product options variants
		$product_option_variants = db_get_fields("SELECT `variant_id` FROM ?:addon_tsp_reorder_reminders_product_field_metadata");
		
		if (!empty($product_option_variants) && is_array($product_option_variants))
		{
			// Delete the product options variants from all tables
			foreach ($product_option_variants as $val)
			{
				db_query("DELETE FROM ?:product_option_variants WHERE `variant_id` = ?i", $val);
				db_query("DELETE FROM ?:product_option_variants_descriptions WHERE `variant_id` = ?i", $val);
			}//endforeach
		}//endif
		
		// After all data removed drop the storage table
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_reorder_reminders_product_field_metadata`");
	}//endif
}//end fn_tspror_uninstall_product_field_metadata

/***********
 *
 * Function to uninstall product metadata
 *
 ***********/
function fn_tspror_uninstall_product_metadata() 
{
	if (Registry::get('addons.tsp_reorder_reminders.delete_reminder_data') == 'Y') 
	{
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_reorder_reminders_product_metadata`");
	}//endif
}//end fn_tspror_uninstall_product_metadata

/***********
 *
* Function to uninstall main table
*
***********/
function fn_tspror_uninstall_main()
{
	if (Registry::get('addons.tsp_reorder_reminders.delete_reminder_data') == 'Y')
	{
		db_query("DROP TABLE IF EXISTS `?:addon_tsp_reorder_reminders`");
	}//endif
}//end fn_tspror_uninstall_product_metadata

//
// [Functions - General]
//

/**
 * Copy options from one field to the other
 *
 * @since 1.0.0
 *
 * @param string $field_name Required - The field whose options we want to copy
 *
 * @return array of options
 */
function fn_tspror_copy_option_variants( $field_name )
{
	$variants = array();
	
	$name = preg_replace("/tspror\_/", '', $field_name);
	$key_name = 'tspror_product_option_'.$name.'_field_id';
	
	$option_id = fn_tspror_get_product_field_id( $key_name );
	
	if (!empty( $option_id ))
	{
		$variants = fn_tspror_get_option_variants( $option_id );
		
	}//end if
	
	return $variants;
}//end fn_tspror_copy_variant_fields

/***********
 *
 * Store the appoint in the database for the first time
 * and if for some reason
 *
 ***********/
function fn_tspror_create_reminder($order_info) 
{
	$user_id = $order_info['user_id'];
	$order_id = $order_info['order_id'];
	
	$order_key = 'products';
	
	// Search through ordered items to find the product that has an reminder
	foreach ($order_info[$order_key] as $item_id => $product)
	{	
		$product_id = $product['product_id'];
		
		$extra = fn_tspror_get_order_details( $order_id, $product_id );
		
		if ( array_key_exists('product_options_value', $extra) )
		{
			$product_options = $extra['product_options_value'];
			
			if (fn_tspror_product_contains_reminder($product_options))
			{			
				$company_id = db_get_field("SELECT company_id FROM ?:products WHERE product_id = ?i", $product_id);
				
				$data = array(
					'company_id'	=> $company_id,
					'order_id' 		=> $order_id,
					'product_id' 	=> $product_id,
					'user_id' 		=> $user_id,
					'date_created' 	=> time(),
				);
							
				db_query("INSERT INTO ?:addon_tsp_reorder_reminders ?e", $data);
			}//endif
		}//endif
	}//endforeach;	
}//end fn_tspror_create_reminder


/***********
 *
 * Delete the reminder
 *
 ***********/
function fn_tspror_delete_reminder($id) 
{	
	db_query("DELETE FROM ?:addon_tsp_reorder_reminders WHERE `id` = ?i", $id);
}//end fn_tspror_delete_reminder

/**
 * Get a option's variants
 *
 * @param int $option_id Required the option's ID
 * @return array list of variants for the option
 */
function fn_tspror_get_option_variants( $option_id )
{
	$variants = array();
	
	$variant_ids = db_get_fields('SELECT `variant_id` FROM ?:product_option_variants WHERE `option_id` = ?i', $option_id);
	
	if ( !empty( $variant_ids ) )
	{
		foreach ( $variant_ids as $variant_id )
		{
			$variants[] = db_get_field('SELECT `variant_name` FROM ?:product_option_variants_descriptions WHERE `variant_id` = ?i', $variant_id);
		}//end foreach
	}//end if
	
	return $variants;
}//end fn_tspror_get_option_variants

/**
 * Get order color
 *
 * @param char $status Required the appointment status
 * @return char the equivalent order status
 */
function fn_tspror_get_order_color_status ( $status )
{
	$statuses = Registry::get('tspror_reminder_statuses_long');
	
	if ( array_key_exists( $status, $statuses ) )
	{
		return $statuses[$status]['color_status'];
	}//end if
}//end fn_tspror_get_order_color_status

/**
 * Get order data
 *
 * @param int $order_id Required the order id
 * @return array statuses list
 */
function fn_tspror_get_order_data( $order_id )
{
	$data = db_get_field("SELECT `data` FROM ?:order_data WHERE `order_id` = ?i AND `type` = ?s", $order_id, 'G');	
	$data = @unserialize($data);
	
	if ( is_array($data) )
	{
		return $data;
	}//end if
	else 
	{
		return array();
	}//end else
}//end fn_tspror_get_order_data

/**
 * Get order details
 *
 * @param int $order_id Required the order id
 * @param int $product_id Required the product id
 * @return array statuses list
 */
function fn_tspror_get_order_details( $order_id, $product_id )
{
	$extra = db_get_field("SELECT `extra` FROM ?:order_details WHERE `order_id` = ?i AND `product_id` = ?i", $order_id, $product_id);
	$extra = @unserialize($extra);
	
	if ( is_array($extra) )
	{
		return $extra;
	}//end if
	else 
	{
		return array();
	}//end else
}//end fn_tspror_get_order_details

/***********
 *
 * Get the default interval for the current
 *
 ***********/
function fn_tspror_get_product_metadata( $product_id, $field_name )
{
	return db_get_field("SELECT `value` FROM `?:addon_tsp_reorder_reminders_product_metadata` WHERE `product_id` = ?i AND `field_name` = ?s", $product_id, $field_name ); 
}//end fn_tspror_get_product_metadata

/***********
 *
 * There are 2 fields that are required for any reminder and they are
 * reminder, reminder, this function will get the option keys from
 * the reminders product metadata table
 *
 ***********/
function fn_tspror_get_product_field_id($key)
{
	$field_id = null;
	
	$table = '?:addon_tsp_reorder_reminders_product_field_metadata';
	$table_exists = db_get_row("SHOW TABLES LIKE '$table'");
	
	if ($table_exists)
	{
		$id = db_get_field("SELECT `option_id` FROM `?:addon_tsp_reorder_reminders_product_field_metadata` WHERE `key` = '$key'");
		
		if (!empty($id))
		{
			$field_id = $id;
		}//endif
	}//endif
	
	return $field_id;
}//end fn_tspror_get_product_field_id


/***********
 *
 * Given a products option id and value determine what the product
 * option description and value is
 *
 ***********/
function fn_tspror_get_product_option_info($option_id, $option_value, $value_only = false) 
{
	$desc = db_get_field("SELECT `option_name` FROM ?:product_options_descriptions WHERE `option_id` = ?i", $option_id);
	$val = $option_value;
	
	$option_type = db_get_field("SELECT `option_type` FROM ?:product_options WHERE `option_id` = ?i", $option_id);

	if ($option_type == 'S' && !$value_only)
	{
		$val = db_get_field("SELECT opt_desc.variant_name FROM ?:product_option_variants_descriptions AS opt_desc LEFT JOIN ?:product_option_variants AS opt_var ON opt_desc.variant_id = opt_var.variant_id WHERE opt_var.option_id = ?i AND opt_var.variant_id = ?i", $option_id,$option_value);
	}//endif

	return array($desc,$val);
}//end fn_tspror_get_product_option_info

/***********
 *
 * Get reminder informaiton for display
 *
 ***********/
function fn_tspror_get_reminders($params, $items_per_page = 0, $store_user = false, $store_order = false, $store_product = false)
{
    // Init filter
    $params = LastView::instance()->update('reminders', $params);

    $default_params = array (
        'page' => 1,
        'items_per_page' => $items_per_page,
    	'company' => Registry::get('runtime.company_id'),
    );

    $params = array_merge($default_params, $params);

	// Set default values to input params
	$params['page'] = empty($params['page']) ? 1 : $params['page']; // default page is 1

	// Define fields that should be retrieved
	$fields = array (
		'?:addon_tsp_reorder_reminders.*',
		'?:users.user_id',
		'?:users.firstname',
		'?:users.lastname',
		'?:users.email'
	);

	// Define sort fields
	$sortings = array (
		'user_id' 			=> "?:users.user_id",
		'user'				=> "?:users.lastname",
		'email' 			=> '?:users.email',
		'id' 				=> "?:addon_tsp_reorder_reminders.id",
		'reminder_id' 		=> "?:addon_tsp_reorder_reminders.id",
		'reminders_sent'	=> "?:addon_tsp_reorder_reminders.reminders_sent",
		'date_reminded' 	=> "?:addon_tsp_reorder_reminders.date_reminded",
		'date_created' 		=> "?:addon_tsp_reorder_reminders.date_created",
		'date_completed' 	=> "?:addon_tsp_reorder_reminders.date_completed",
		'status' 			=> "?:addon_tsp_reorder_reminders.status",
	);

	$directions = array (
		'asc' => 'asc',
		'desc' => 'desc'
	);

	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) 
	{
		$params['sort_order'] = 'desc';
	}//endif

	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) 
	{
		$params['sort_by'] = 'reminder_id';
	}//endif

	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']] . ', ', $sortings[$params['sort_by']]) : $sortings[$params['sort_by']]) . " " . $directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	$join = $condition = '';

	if (!empty($params['company'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_reorder_reminders.company_id = ?i", $params['company']);
	}//endif

	if (!empty($params['email'])) 
	{
		$condition .= db_quote(" AND ?:users.email LIKE ?l", "%{$params['email']}%");
	}//endif

	if (!empty($params['user_id'])) 
	{
		$condition .= db_quote(" AND ?:users.user_id = ?i", $params['user_id']);
	}//endif

	if (!empty($params['reminder_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_reorder_reminders.id = ?i", $params['reminder_id']);
	}//endif

	if (!empty($params['id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_reorder_reminders.id = ?i", $params['id']);
	}//endif
	elseif (!empty($params['reminder_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_reorder_reminders.id = ?i", $params['reminder_id']);
	}//endif
	
	if (!empty($params['period']) && $params['period'] != 'A') 
	{
		list($params['time_from'], $params['time_to']) = fn_create_periods($params);

		$condition .= db_quote(" AND (?:addon_tsp_reorder_reminders.date_created >= ?i AND ?:addon_tsp_reorder_reminders.date_created <= ?i)", $params['time_from'], $params['time_to']);
	}//endif

	if (!empty($params['status'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_reorder_reminders.status = ?s", $params['status']);
	}//endif

	if (!empty($params['order_id'])) 
	{
		$condition .= db_quote(" AND ?:addon_tsp_reorder_reminders.order_id = ?i", $params['order_id']);
	}//endif

	if (empty($items_per_page)) 
	{
		$items_per_page = Registry::get('settings.Appearance.admin_elements_per_page');
	}//endif

	$total = db_get_field("SELECT COUNT(*) FROM ?:addon_tsp_reorder_reminders LEFT JOIN ?:users ON ?:addon_tsp_reorder_reminders.user_id = ?:users.user_id WHERE 1 $condition");
	$limit = db_paginate($params['page'], $total, $items_per_page);

	$reminders = db_get_hash_array("SELECT " . implode(', ', $fields) . " FROM ?:addon_tsp_reorder_reminders LEFT JOIN ?:users ON ?:addon_tsp_reorder_reminders.user_id = ?:users.user_id WHERE 1 $condition ORDER BY $sorting $limit", 'id');
	
	foreach ( $reminders as $reminder )
	{
		$id = $reminder['id'];
		
		if ( $store_user )
		{
			$reminders[$id]['user'] = fn_get_user_info($reminder['user_id']);
		}//endif
		if ( $store_product )
		{
			$reminders[$id]['product'] = fn_get_product_data($reminder['product_id'],$auth,CART_LANGUAGE,'product,product_code');
		}//end elseif
		if ( $store_order )
		{
			$reminders[$id]['order'] = fn_get_order_info($reminder['order_id']);
		}//end elseif
		
		// Determine when the user wanted to be reminded
		$extra = fn_tspror_get_order_details($reminder['order_id'], $reminder['product_id']);
		if ( array_key_exists('product_options_value', $extra) )
		{
			$product_options = $extra['product_options_value'];
			foreach ( $product_options as $pos => $field )
			{
				$option_id 	= $field['option_id'];
				$value 		= $field['value'];
				
				// if this product's option is the reminder then get its data
				if ($option_id == Registry::get('tspror_product_option_reminder_field_id'))
				{
					list($null, $reminders[$id]['remind_in']) 	= fn_tspror_get_product_option_info($option_id,$value);
					$date_to_remind_timestamp 					= strtotime($reminders[$id]['remind_in'], $reminders[$id]['date_created']);
					$reminders[$id]['remind_date'] 				= $date_to_remind_timestamp;
				}//end if
			}//endforeach
		}//end if
	}//end foreach
	
	LastView::instance()->processResults('reminders', $reminders, $params, $items_per_page);

	return array($reminders, $params);
}//end fn_tspror_get_reminders

/***********
 *
 * Function to notify user of reminder change
 * FIXME: Change this function to notify user of reorder reminder
 ***********/
function fn_tspror_notify_user($id, $notify_staff = false, $data = array())
{
	$notified = false;
	
	$reminder = db_get_row("SELECT * FROM ?:addon_tsp_reorder_reminders WHERE id = ?i", $id);
	
	// if the reminder exists
	if (!empty( $reminder ))
	{
		if (!empty($reminder['user_id']))
		{
			$reminder['user'] = fn_get_user_info($reminder['user_id']);
		}//endif
		
		if (!empty($reminder['order_id']))
		{
			$reminder['order'] = fn_get_order_info($reminder['order_id']);
			
			$product_found = false;
			
			// store product information as well
			foreach ($reminder['order'] as $key => $item)
			{
				if ($key == 'products')
				{
					$products = $item;
					
					foreach ($products as $item_number => $product)
					{
						if ($product['product_id'] == $reminder['product_id'])
						{
							$reminder['product'] = $product;
							$product_found = true;
							break; //once found stop looping
						}//end if
					}//end if
					
					if ($product_found)
					{
						break; //once found stop looping
					}//end if
				}//end if
			}//end foreach;
		}//endif

		$data['reminder'] 		= $reminder;
		$data['profile_fields'] = fn_get_profile_fields('I', '', CART_LANGUAGE);
		
		// Send a copy to the customer	
		Mailer::sendMail(array(
	    	'to' 		=> $reminder['user']['email'],
	        'from' 		=> 'default_company_orders_department',
	        'reply_to' 	=> Registry::get('settings.Company.company_orders_department'),
	        'data' 		=> $data,
	        'tpl' 		=> 'addons/tsp_reorder_reminders/reminder_notification.tpl',
	         ), 'A', Registry::get('settings.Appearance.backend_default_language'));
	
		if ( $notify_staff )
		{
			// Send a copy to the staff
			Mailer::sendMail(array(
			'to' 		=> Registry::get('settings.Company.company_orders_department'),
			'from' 		=> 'default_company_orders_department',
			'reply_to' 	=> Registry::get('settings.Company.company_orders_department'),
			'data' 		=> $data,
			'tpl'		=> 'addons/tsp_reorder_reminders/reminder_notification.tpl',
			), 'A', Registry::get('settings.Appearance.backend_default_language'));
		}//end if
		
		$notified = true;
	}
	
	return $notified;
}//end fn_tspror_notify_user

/***********
 *
 * check product options to check to see if it is an reminder
 * reminders all contain a date, time and location
 *
 ***********/
function fn_tspror_product_contains_reminder(&$product_options)
{
	$contains_reminder = false;
	// required fields
	$reminder_found = false;
	
	foreach ($product_options as $pos => $field)
	{	
		$option_id = $field['option_id'];
		
		if ($option_id == Registry::get('tspror_product_option_reminder_field_id'))
		{
			$reminder_found = true;
		}//endif
	}//endforeach;
	
	if ($reminder_found)
	{
		$contains_reminder = true;
	}//endif
	
	return $contains_reminder;
}//end fn_tspror_product_contains_reminder

/***********
 *
 * Function to update reminder status
 *
 ***********/
function fn_tspror_update_reminder_status($id, $status)
{
	$updated = false;
	
	// get current status
	$current_status = db_get_field("SELECT `status` FROM ?:addon_tsp_reorder_reminders WHERE `id` = ?i", $id);
	
	// update the status only if its changed
	if ( $current_status != $status )
	{
		db_query("UPDATE ?:addon_tsp_reorder_reminders SET `status` = ?s WHERE `id` = ?i", $status, $id);
		
		// If the reminder is completed then change the date completd
		// if its not completed then null out the date completed
		if ($status == 'C')
		{
			db_query("UPDATE ?:addon_tsp_reorder_reminders SET `date_completed` = ?i WHERE `id` = ?i", time(), $id);
		}//endif
		else
		{
			db_query("UPDATE ?:addon_tsp_reorder_reminders SET `date_completed` = ?i WHERE `id` = ?i", $null, $id);
		}//endelse
		
		$updated = true;
	}//end if
	
	return $updated;
}//end fn_tspror_update_reminder_status

/***********
 *
 * Function to update product metadata
 *
 ***********/
function fn_tspror_update_product_metadata($product_id, $field_name, $value) 
{			
	if (!empty($value))
	{
		$data = array(
			'product_id' => $product_id, 
			'field_name' => $field_name,
			'value' => trim($value)
		);
		db_query("REPLACE INTO ?:addon_tsp_reorder_reminders_product_metadata ?e", $data);
	}//endif
	else
	{
		// Don't store a bunch of null values in the database, if a field has no value
		// simply delete it from the table
		db_query("DELETE FROM ?:addon_tsp_reorder_reminders_product_metadata WHERE `product_id` = ?i AND `field_name` = ?s", $product_id, $field_name);
	}//endif
}//end fn_tspror_update_product_metadata
?>