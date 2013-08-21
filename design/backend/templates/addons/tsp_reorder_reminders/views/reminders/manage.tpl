{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_reminders_form">

{include file="common/pagination.tpl"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}

{if $reminders}
<table width="100%" class="table table-middle">
<thead>
<tr>
    <th  class="left">
        {include file="common/check_items.tpl" check_statuses=$simple_statuses}
    </th>
    <th width="32%"><a class="cm-ajax" href="{"`$c_url`&sort_by=reminder_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspror_details")}{if $search.sort_by == "reminder_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="12%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="12%"><a class="cm-ajax" href="{"`$c_url`&sort_by=user&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("customer")}{if $search.sort_by == "user"}{$c_icon nofilter}{/if}</a></th>
    <th width="11%"><a class="cm-ajax" href="{"`$c_url`&sort_by=reminders_sent&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspror_reminder_sent")}{if $search.sort_by == "reminders_sent"}{$c_icon nofilter}{/if}</a></th>
    <th width="11%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date_created&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspror_reminder_created")}{if $search.sort_by == "date_created"}{$c_icon nofilter}{/if}</a></th>
    <th width="11%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date_reminded&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspror_reminder_date")}{if $search.sort_by == "date_reminded"}{$c_icon nofilter}{/if}</a></th>
    <th width="11%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date_completed&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspror_reminder_completed")}{if $search.sort_by == "date_completed"}{$c_icon nofilter}{/if}</a></th>
</tr>
</thead>

{foreach from=$reminders key="id" item="reminder"}
<tr class="cm-row-status-{$reminder.color_status|lower}">
    <td class="left">
        <input type="checkbox" name="reminder_ids[]" value="{$reminder.id}" class="cm-item cm-item-status-{$reminder.status|lower}" /></td>
    <td>
        <a href="{"reminders.update?reminder_id=`$reminder.id`"|fn_url}">
        <span style="color:black;"><strong>{__("order")} #{$reminder.order_id}:</strong></span> Reminder to re-order <strong>{$reminder.product.product}</strong> in <strong>{$reminder.remind_in}</strong>
        </a>
        {include file="views/companies/components/company_name.tpl" object=$reminder}
    </td>
    <td>
        {assign var="this_url" value=$config.current_url|escape:"url"}
        {include file="common/select_popup.tpl" suffix="o" id=$reminder.id status=$reminder.status items_status=$simple_statuses update_controller="reminders" notify=false status_target_id="`$rev`" extra="&return_url=`$this_url`" statuses=$statuses btn_meta="btn btn-info o-status-`$reminder.color_status` btn-small"|lower}
    </td>
    <td>{if $reminder.user_id}<a href="{"profiles.update?user_id=`$reminder.user_id`"|fn_url}">{/if}{$reminder.lastname} {$reminder.firstname}{if $reminder.user_id}</a>{/if}</td>
    <td class="center">{$reminder.reminders_sent}</td>
    <td>{if $reminder.date_created}{$reminder.date_created|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
    <td>{if $reminder.date_reminded}{$reminder.date_reminded|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
    <td>{if $reminder.date_completed}{$reminder.date_completed|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
</tr>
{/foreach}
</table>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl"}
</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $reminders}
            <li>{btn type="delete_selected" dispatch="dispatch[reminders.m_delete]" form="manage_reminders_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{capture name="sidebar"}
    {capture name="content_sidebar"}
        <ul class="nav nav-list">
            <li><a href="{"addons.manage#grouptsp_reorder_reminders"|fn_url}"><i class="icon-cog"></i>{__("tspror_title")} {__("settings")}</a></li>
        </ul>
    {/capture}
    {include file="common/sidebox.tpl" content=$smarty.capture.content_sidebar title=__("settings")}
{/capture}

{include file="common/mainbox.tpl" title=__("tspror_title") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}