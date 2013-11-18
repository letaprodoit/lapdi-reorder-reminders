<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	init.php
 * @version		1.0.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Hook registrations for addon
 * 
 */


if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

fn_register_hooks(
	'change_order_status',
	'finish_payment',
	'delete_order',
	'delete_product_post',
	'get_order_info',
	'get_product_options_post',
	'update_product_post'
);

?>