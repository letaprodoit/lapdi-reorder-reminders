<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	install.php
 * @version		1.1.0
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Script to copy addon files to their respective locations
 * 
 */
$target_loc 		= '';
$source_loc			= '.';

$addon_name 		= 'tsp_reorder_reminders';
$addon_dir			= 'app/addons';
$theme_backend_dir	= 'design/backend';
$theme_frontend_dir	= 'design/themes/basic';
$theme_var_dir		= 'var/themes_repository/basic';

if (empty( $target_loc ))
{
	echo "You must specify a target location on line #14 of this script.";
}//end if
elseif (!file_exists( $target_loc ))
{
	echo "The $target_loc specified does not exist on line #14 of this script.";
}//end elseif
else
{
	// Copy files from addons directory to the target addons directory
	shell_exec( " cp -R -v -a $source_loc/$addon_dir/$addon_name $target_loc/$addon_dir/ " );

	// Copy files from theme backend directory to the target backend directory
	shell_exec( " cp -R -v -a $source_loc/$theme_backend_dir/css/addons/$addon_name $target_loc/$theme_backend_dir/css/addons/ " );
	shell_exec( " cp -R -v -a $source_loc/$theme_backend_dir/media/addons/$addon_name $target_loc/$theme_backend_dir/media/addons/ " );
	shell_exec( " cp -R -v -a $source_loc/$theme_backend_dir/templates/addons/$addon_name $target_loc/$theme_backend_dir/templates/addons/ " );
}//end else
?>