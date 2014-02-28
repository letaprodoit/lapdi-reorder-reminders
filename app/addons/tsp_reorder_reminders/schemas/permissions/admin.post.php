<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	admin.post.php
 * @version		1.1.2
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Admin post permissions for menus
 * 
 */
$schema = array();

$schema['reminders'] = array (
    'permissions' => array ('POST' => 'manage_reminders')
);

return $schema;

?>