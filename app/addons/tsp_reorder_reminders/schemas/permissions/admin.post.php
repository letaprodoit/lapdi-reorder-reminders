<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	admin.post.php
 * @version		1.1.5
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Admin post permissions for menus
 * 
 */

if (!empty($schema))
{
	$schema['reminders'] = array (
			'permissions' => 'manage_reminders',
	);
	
	$schema['tools']['modes']['update_status']['param_permissions']['table']['addon_tsp_reorder_reminders'] = 'manage_reminders';
	
	return $schema;
}

?>