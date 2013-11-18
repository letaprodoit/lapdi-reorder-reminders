<?php
/*
 * TSP Appointments for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	reminders.php
 * @version		1.0.1
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2013 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Reminders dispatch for addon
 * 
 */

define('DEBUG', false);


if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

use Tygh\Registry;

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	fn_trusted_vars('reminder_data', 'reminders', 'delete');
	$suffix = '';
	if ($mode == 'm_delete') 
	{
		if (!empty($_REQUEST['reminder_ids'])) 
		{
			foreach ($_REQUEST['reminder_ids'] as $id) 
			{
				fn_tspror_delete_reminder($id);
			}//endforeach
		}//endif

		$suffix = '.manage';
	} 
	elseif ($mode == 'update')
	{
		$reminder_id = $_POST['reminder_id'];
		$data = $_POST['reminder_data'];
	
		if (!empty($data['status']))
		{
			fn_tspror_update_reminder_status($reminder_id,$data['status']);
		}//endif
		
		$suffix = ".update?reminder_id=$reminder_id";
	}//endif

	return array(CONTROLLER_STATUS_OK, "reminders$suffix");
}//endif

if ($mode == 'manage') 
{
	list($reminders, $search) = fn_tspror_get_reminders($_REQUEST,0,false,false,true);
	
	$statuses = Registry::get('tspror_reminder_statuses_long');
		
	// Loop through and set the color (style) status for each reminder
	// Styles are taken from order status colors see tspror_reminder_statuses_long
	foreach ( $reminders as $id => $reminder )
	{
		if ( array_key_exists($reminder['status'], $statuses) )
		{
			$reminders[$id]['color_status'] = $statuses[$reminder['status']]['color_status'];
		}//end if
	}// foreach
		
	Registry::get('view')->assign('simple_statuses', Registry::get('tspror_reminder_statuses_short'));
	Registry::get('view')->assign('statuses', $statuses);
	Registry::get('view')->assign('status_params', Registry::get('tspror_reminder_status_params'));
	
	Registry::get('view')->assign('reminders', $reminders);
	Registry::get('view')->assign('search', $search);

	list($user_list) = fn_get_users(array('status' => 'A'), $auth);

	$_user_list = array();
	foreach ($user_list as $item) 
	{
		$_user_list[$item['user_id']] = "{$item['lastname']}, {$item['firstname']} (Email: {$item['email']})";
	}//endforeach

	Registry::get('view')->assign('user_list', $_user_list);

}//endif
elseif ($mode == 'update' && !empty($_REQUEST['reminder_id'])) 
{
	$id 						= $_REQUEST['reminder_id'];	
	list($reminders, $search)	= fn_tspror_get_reminders($_REQUEST,0,true,true,true); //will only return one
	$reminder 					= $reminders[$id];
	
	if (empty($reminder)) 
	{
		return array(CONTROLLER_STATUS_NO_PAGE);	
	}//endif
		
	// [Breadcrumbs]
	fn_add_breadcrumb(__('tspror_reminders'), "reminders.manage.reset_view");
	fn_add_breadcrumb(__('search_results'), "reminders.manage.last_view");
	// [/Breadcrumbs]
	
	Registry::get('view')->assign('reminder_statuses', Registry::get('tspror_reminder_statuses_short'));
	Registry::get('view')->assign('reminder', $reminder);
	Registry::get('view')->assign('search', $search);
	
}//endelseif
elseif ($mode == 'update_status' && !empty($_REQUEST['id'])) 
{

	$status = $_REQUEST['status'];
	$reminder_id = $_REQUEST['id'];
	
	if (!empty($status))
	{	
		if (fn_tspror_update_reminder_status($reminder_id,$status))
		{
			Registry::get('ajax')->assign('return_status', $status);
			Registry::get('ajax')->assign('color', fn_get_status_param_value(fn_tspror_get_order_color_status($status), 'color'));
	
			fn_set_notification('N', __('notice'), __('status_changed'));
		}//endif
	}//endif
			
    if (empty($_REQUEST['return_url'])) 
    {
        exit;
    }//endif 
    else 
    {
        return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['return_url']);
    }//end else
	
}//endelseif
elseif ($mode == 'delete') 
{
	if (!empty($_REQUEST['reminder_id'])) 
	{
		fn_tspror_delete_reminder($_REQUEST['reminder_id']);
	}//endif

	return array(CONTROLLER_STATUS_REDIRECT, "reminders.manage");
	
}//endelseif
?>