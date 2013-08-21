{if $fields}
<div id="{$target_name}" class="in collapse">
    <fieldset>        
        {foreach from=$fields item="field"}
        <div class="control-group">
            {if !$field.readonly}
                <label class="control-label" for="product_{$field.name}">{$field.title}:</label>
                <div class="controls form-field {if $field.type == 'D'}clearfix{/if}">
                    {if $field.type == 'I' || $field.type == 'U'}
                        <input type="hidden" name="product_data[{$field.name}]" value="" />
                        <input id="product_{$field.name}" type="text" name="product_data[{$field.name}]" {if $field.width}style="width: {$field.width};"{/if} value="{$field.value}" class="valign input-text {if $field.hint}cm-hint{/if}" title="{$field.hint}"/>
                    {elseif $field.type == 'S'}
                        <select id="product_{$field.name}" name="product_data[{$field.name}]">
                        <option value="">{__("please_select_one")}</option>
                            {foreach from=$field.options item="option"}
                                <option value="{$option}" {if $field.value == $option}selected="selected"{/if}>{$option}</option>
                            {/foreach}
                        </select>
                    {elseif $field.type == 'H'}
                        <select id="product_{$field.name}" name="product_data[{$field.name}]">
                        <option value="">{__("please_select_one")}</option>
                            {foreach from=$field.options key=k item=v name="option"}
                                <option value="{$k}" {if $field.value == $k}selected="selected"{/if}>{$v}</option>
                            {/foreach}
                        </select>
                    {elseif $field.type == 'T'}
                        <textarea id="product_{$field.name}" name="product_data[{$field.name}]" class="input-textarea-long" style="height: 90px;">{$field.value|unescape}</textarea>
                    {elseif $field.type == 'C'}
                        <input type="hidden" name="product_data[{$field.name}]" value="" />
                        <input type="checkbox" name="product_data[{$field.name}]" id="product_{$field.name}" value="Y" {if $field.value == "Y"}checked="checked"{/if} class="checkbox" />
                    {elseif $field.type == 'D'}
                        {include file="common/calendar.tpl" date_id="product_`$field.name`" date_name="product_data[`$field.name`]" start_year=$settings.Company.company_start_year date_val=$field.value}
                    {/if}
                </div>
            {else}
                {if $field.type == 'U'}
                    {assign var="url" value=$field.value}
                    {if !$url}
                        {assign var="url" value="#"}
                    {/if}
                    
                    {if $field.icon}
                        <div class="controls {$field.class} field_type_{$field.type}" style="cursor: pointer;" onclick="window.location='{$url|unescape}'">&nbsp;</div>
                    {else}
                        <label class="control-label" for="product_{$field.name}">{$field.title}:</label>
                        <div class="controls form-field {if $field.type == 'D'}clearfix{/if} product-list-field">
                            <span class="{$field.class} field_type_{$field.type}"><a href="{$url|unescape}">{$field.title|unescape}</a></span>
                        </div>
                    {/if}
                {else}
                    <label class="control-label" for="product_{$field.name}">{$field.title}:</label>
                    <div class="controls form-field {if $field.type == 'D'}clearfix{/if} product-list-field">
                        <span class="{$field.class} field_type_{$field.type}">{$field.value|unescape}</span>
                    </div>
                {/if}
            {/if}       
        </div>
        {/foreach}
    </fieldset>
</div>
{/if}