{* $Id$ *}

{capture name="section"}

<form name="reminders_search_form" action="{""|fn_url}" method="get">

<table cellpadding="0" cellspacing="0" border="0" class="search-header">
<tr>
	<td class="search-field">
		<label for="elm_user_id">{__("user")}:</label>
		<div class="break">
			<select name="user_id" id="elm_user_id">
				<option value="0" {if !$search.user_id}selected="selected"{/if}> -- </option>
				{html_options options=$user_list selected=$search.user_id}
			</select>
		</div>
	</td>
	<td class="search-field">
		<label for="elm_status">{__("status")}:</label>
		<div class="break">
			<select name="status" id="elm_status">
				<option value=""> -- </option>
				<option value="O" {if $search.status == "O"}selected="selected"{/if}>{__("open")}</option>
				<option value="C" {if $search.status == "C"}selected="selected"{/if}>{__("completed")}</option>
			</select>
		</div>
	</td></td>
	<td class="buttons-container">
		{include file="buttons/search.tpl" but_name="dispatch[reminders.manage]" but_role="submit"}
	</td>
</tr>
</table>
</form>
{/capture}
{include file="common/section.tpl" section_content=$smarty.capture.section}
