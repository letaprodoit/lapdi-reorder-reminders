{* $Id$ *}
                                                   
{include file="common/letter_header.tpl"}

{__("dear")} {if $reminder.user.firstname}{$reminder.user.firstname}{else}{$reminder.user.user_type|fn_get_user_type_description|lower|escape}{/if},<br><br>

{assign var="order_url" value="orders.details?order_id=`$reminder.order.order_id`"|fn_url:'C':'http':'&'}

{__("tspror_notification_msg")|replace:"[reminder_interval]":"<strong>{$reminder_interval}</strong>"|replace:"[reorder_this_order]":"<strong>{__('re_order')}</strong>"|replace:"[product_name]":"<strong>`$reminder.product.product`</strong>"|replace:"[order_url]":"<a href='`$order_url`'><strong>`$reminder.order.order_id`</strong></a>"|replace:"[reminder_info]":"<br><br>`$reminder.info`"|html_entity_decode:$smarty.const.ENT_QUOTES:"UTF-8"}<br><br>

{include file="common/letter_footer.tpl"}