<?php
/*
 * TSP Re-Order Reminders for CS-Cart
 *
 * @package		TSP Re-Order Reminders for CS-Cart
 * @filename	notifications.post.php
 * @version		1.1.7
 * @author		Sharron Denice, The Software People, LLC on 2013/02/09
 * @copyright	Copyright © 2014 The Software People, LLC (www.thesoftwarepeople.com). All rights reserved
 * @license		Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported (http://creativecommons.org/licenses/by-nc-nd/3.0/)
 * @brief		Used as a cron to notify users of reminders
 * 
 */

if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	return;
}//endif

use Tygh\Registry;

$debug = false;

$store_lang = (DEFAULT_LANGUAGE != null) ? DEFAULT_LANGUAGE : CART_LANGUAGE;

if ($mode == 'cron')
{
	$cron_password = Registry::get('settings.Security.cron_password');

	// do not allow access if the passwords do not match
	if ((!isset($_REQUEST['cron_password']) || $cron_password != $_REQUEST['cron_password']) && (!empty($cron_password))) 
	{
		die(__('access_denied'));
		exit;
	}//end if
	
	$active_reminders = db_get_hash_array("SELECT * FROM ?:addon_tsp_reorder_reminders WHERE `status` = 'O'",'id');
	$notifications_sent = 0;
	
	$now_timestamp = time();
	$now = fn_date_format($now_timestamp, Registry::get('settings.Appearance.date_format'));

	foreach ( $active_reminders as $id => $reminder )
	{
		extract($reminder);

		$reminder_id = $id;
		
		$extra = fn_tspror_get_order_details($order_id, $product_id);	
		
		if ( array_key_exists('product_options_value', $extra) )
		{
			$product_options = $extra['product_options_value'];
			
			// if the product this contains a reminder 
			if ( fn_tspror_product_contains_reminder($product_options) )
			{
				foreach ( $product_options as $pos => $field )
				{
					$option_id 	= $field['option_id'];
					$value 		= $field['value'];
					
					// if this product's option is the reminder then get its data
					if ($option_id == Registry::get('tspror_product_option_reminder_field_id'))
					{
						$product_settings = db_get_hash_array("SELECT * FROM ?:addon_tsp_reorder_reminders_product_metadata WHERE `product_id` = '$product_id'", 'field_name');

						// Only process reminders that the admin has actively acknowledged by setting any value
						// related to this addon, all products MUST have settings if reminders are to be sent
						if ( !empty( $product_settings ))
						{
							$max_reminders 			= Registry::get('addons.tsp_reorder_reminders.max_reminders');
							$time_between_reminders = Registry::get('addons.tsp_reorder_reminders.time_between_reminders');
								
							// only send additional reminders if the number sent already is less than the max reminders to send
							if ( (int)$reminders_sent < (int)$max_reminders )
							{
								$reminder_created_timestamp = $date_created;
								$reminder_created = fn_date_format($reminder_created_timestamp, Registry::get('settings.Appearance.date_format'));
								
								$last_reminder_timestamp = $date_reminded;
								$last_reminder = fn_date_format($last_reminder_timestamp, Registry::get('settings.Appearance.date_format'));
																		
								$next_reminder_timestamp = strtotime($time_between_reminders, $last_reminder_timestamp);
								$next_reminder = fn_date_format($next_reminder_timestamp, Registry::get('settings.Appearance.date_format'));
								
								list($null, $remind_user_in) = fn_tspror_get_product_option_info($option_id,$value);
								$date_to_remind_timestamp = strtotime($remind_user_in, $reminder_created_timestamp);
								$date_to_remind = fn_date_format($date_to_remind_timestamp, Registry::get('settings.Appearance.date_format'));

								// only send if the user has not chosen reminders AND
								// ((the user has never been reminded AND the time to remind is now) OR
								// (the next reminder time is now)
								if ( $remind_in != __("tspror_no_remind", array(), $store_lang) && 
									((!$date_reminded && ($date_to_remind == $now)) || ( $next_reminder == $now )))
								{
									if ($debug)
									{
										echo "Now: $now".PHP_EOL;
										echo "Created: $reminder_created".PHP_EOL;
										echo "Last: $last_reminder".PHP_EOL;
										echo "Next: $next_reminder ($time_between_reminders)".PHP_EOL;
										echo "Remind On: $date_to_remind ($remind_user_in)".PHP_EOL;
										echo PHP_EOL;
									}//end if
									
									$mail_data = array();
									$mail_data['reminder_interval'] =  $remind_user_in;

									if ( fn_tspror_notify_user( $reminder_id, false, $mail_data ) )
									{
										$reminder_count = (int)$reminders_sent + 1;
										$status = 'O';
										
										// if the max number of times a reminder can be sent is reached
										// close this reminder by setting its status to C
										if ( $reminder_count >= (int)$max_reminders )
										{
											$status = 'C';
										}//end if
										
										// Update the reminder record
										$data = array(
											'status'			=> $status,
											'reminders_sent' 	=> $reminder_count,
											'date_reminded' 	=> $now_timestamp,
											'date_completed'	=> $now_timestamp,
										);
										
										db_query( "UPDATE ?:addon_tsp_reorder_reminders SET ?u WHERE `id` = ?i", $data, $reminder_id );
										$notifications_sent++;
									}//end if
								}//end if
							}//end if;
							else
							{
								// More reminders were sent than allowed close the reminder and don't send any more
								// Update the reminder record
								$data = array(
									'status'			=> 'C',
									'date_completed'	=> $now_timestamp,
								);
								
								db_query( "UPDATE ?:addon_tsp_reorder_reminders SET ?u WHERE `id` = ?i", $data, $reminder_id );
							}//end else
						}//end if
					}//end if
				}//end foreach
			}//end if
		}//end if
	}//end foreach
	
    fn_log_event('requests', 'http', array(
    	'url' => 'index.php?dispatch=notifications.cron',
        'response' => __('tspror_reminders') . " ". __('sent') . ": $notifications_sent".PHP_EOL.
        			__('tspror_reminders') . " ". __('open') . ": ".count($active_reminders),
    ));
	
	exit;
}//end if	

?>