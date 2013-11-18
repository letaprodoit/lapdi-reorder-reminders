<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	menu.post.php
 * @version		1.0.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Admin post permissions for menus
 * 
 */

$schema['central']['orders']['items']['tspror_reminders'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'reminders.manage',
    'position' => 250
);

return $schema;

?>