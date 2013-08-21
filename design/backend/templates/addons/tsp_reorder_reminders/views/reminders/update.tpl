{if $reminder}
    {assign var="id" value=$reminder.id}
{else}
    {assign var="id" value=0}
{/if}

{capture name="sidebar"}
    {capture name="content_sidebar"}
        <ul class="nav nav-list">
            <li><a href="{"addons.manage#grouptsp_reorder_reminders"|fn_url}"><i class="icon-cog"></i>{__("tspror_title")} {__("settings")}</a></li>
        </ul>
    {/capture}
    {include file="common/sidebox.tpl" content=$smarty.capture.content_sidebar title=__("settings")}
{/capture}

{capture name="mainbox"}

<div id="tspror_reminders">
    <form action="{""|fn_url}" method="post" name="reminder_update_form" class="form-horizontal form-edit ">
    <input type="hidden" name="reminder_id" value="{$id}" />
    <input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
       
    {capture name="tabsbox"}
    <div id="content_general">
        <fieldset>
            <div class="control-group">
                <label for="reminder_status" class="control-label cm-required">{__("tspror_reminder_status")}:</label>
                <div class="controls">
                    <select name="reminder_data[status]" id="reminder_status">
                        {foreach from=$reminder_statuses item="status" key="status_key"}
                            <option value="{$status_key}" {if $status_key == $reminder.status}selected="selected"{/if}>{$status}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <hr>
            <div class="control-group">
                <label class="control-label">{__("user")}:</label>
                <div class="controls">
                    <a href="{"profiles.update?user_id=`$user_id`"|fn_url}">{$reminder.user.firstname} {$reminder.user.lastname}</a>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("email")}:</label>
                <div class="controls">
                    {$reminder.user.email}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">{__("order")}:</label>
                <div class="controls">
                    <a href="{"orders.details?order_id=`$reminder.order_id`"|fn_url}">#{$reminder.order_id}</a>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("product")}:</label>
                <div class="controls">
                    <a href="{"products.update?product_id=`$reminder.product_id`"|fn_url}">{$reminder.product.product} (Product Code: {$reminder.product.product_code})</a>
                 </div>
            </div>
             
            <div class="control-group">
                <label class="control-label">{__("tspror_reminder_sent")}:</label>
                 <div class="controls">
                    {$reminder.reminders_sent}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("tspror_reminder_in")}:</label>
                 <div class="controls">
                    {$reminder.remind_in} {if $reminder.remind_date}[{$reminder.remind_date|date_format:$settings.Appearance.date_format}]{/if}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">{__("tspror_reminder_created")}:</label>
                 <div class="controls">
                    {if $reminder.date_created}{$reminder.date_created|date_format:$settings.Appearance.date_format}{/if}
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("tspror_reminder_date")}:</label>
                 <div class="controls">
                    {if $reminder.date_reminded}{$reminder.date_reminded|date_format:$settings.Appearance.date_format}{/if}
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("tspror_reminder_reordered")}:</label>
                 <div class="controls">
                   {if $reminder.date_reordered} {$reminder.date_reordered|date_format:$settings.Appearance.date_format}{/if}
                </div>
            </div>
           
            <div class="control-group">
                <label class="control-label">{__("tspror_reminder_completed")}:</label>
                 <div class="controls">
                    {if $reminder.date_completed}{$reminder.date_completed|date_format:$settings.Appearance.date_format}{/if}
                </div>
            </div>
            
        </fieldset>
    </div>
    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true}
    </form>
</div>
{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[reminders.update]" but_role="submit-link" but_target_form="reminder_update_form" save=$id}
{/capture}

{if !$id}
    {assign var="title" value="{__("new")}  {__("tspror_reminders")}"}
{else}
    {assign var="title" value="{__("tspror_reminder_editing")} #`$reminder.id` {__("for")} `$reminder.user.lastname`, `$reminder.user.firstname`"}
{/if}

{/capture}

{include file="common/mainbox.tpl" title=$title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons select_languages=true}
