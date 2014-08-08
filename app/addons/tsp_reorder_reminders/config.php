<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	config.php
 * @version		1.1.7
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Configuration file for addon
 * 
 */

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

use Tygh\Registry;

require_once 'lib/fn.tsp_reorder_reminders.php';

Registry::set('tspror_reminder_statuses_long', array(
		'O' => array(
			'status_id' 	=> 1,
			'status' 		=> 'O',
			'color_status'	=> 'O',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> __("tspror_open"),
			'email_subj' 	=> __("tspror_open_email_subj"),
			'email_header' 	=> __("tspror_open_email_header"),
		),
		'C' => array(
			'status_id' 	=> 4,
			'status' 		=> 'C',
			'color_status'	=> 'P',
			'type' 			=> 'A',
			'is_default' 	=> 'Y',
			'description' 	=> __("tspror_completed"),
			'email_subj' 	=> __("tspror_completed_email_subj"),
			'email_header' 	=> __("tspror_completed_email_header"),
		),
));

Registry::set('tspror_reminder_statuses_short', array(
		'O' => __("tspror_open"),
		'C' => __("tspror_completed")
));

Registry::set('tspror_reminder_status_params', array(
		'color' => array (
				'type' => 'color',
				'label' => 'color'
		),
		'notify' => array (
				'type' => 'checkbox',
				'label' => 'notify_customer',
				'default_value' => 'Y'
		),
));

// Field types: 
// admin_only (hidden on customer side), type [S (selectbox), H(selectbox, hash values),T (textarea),I (input),C (checkbox)], 
// options (single dim array), options_func (function name to call at run-time, use with type H or S), 
// values, (object), value_func (function name to call at run-time, use with any to get the default value)
// title, name (field name), value, width (with of field), class (css), hint, readonly (show text only)
Registry::set('tspror_product_data_field_names', array(
	'tspror_reminder_default' => array(
		'type' => 'S',
		'admin_only' => true,
		'options_func' => 'fn_tspror_copy_option_variants',
		'options_func_args' => array('tspror_reminder'),
	),
	'tspror_reminder' => array(
		'type' => 'S',
		'default_value_metadata_key' => 'tspror_reminder_default',
		'options' => array(
			__("tspror_no_reminder"),
			'1 '.__("tspror_month"),
			'2 '.__("tspror_months"),
			'3 '.__("tspror_months"),
			'6 '.__("tspror_months"),
			'9 '.__("tspror_months"),
			'1 '.__("tspror_year"),
		)
	),
));
		
// Fields necessary for storing global option field ids
Registry::set('tspror_product_option_reminder_field_id', fn_tspror_get_product_field_id('tspror_product_option_reminder_field_id'));

?>