<!--{include file="$template_name/admin/header.tpl"}-->

<form action="config.php?<!--{$sid}-->" method="POST">
<table border="1">
<tr><td>Design Settings</td></tr>
<tr><td><!--{$lang.thumb_table_cols}--></td><td><input type="text" name="thumb_table_cols" value="<!--{$config_vars.thumb_table_cols}-->"></td></tr>
<tr><td><!--{$lang.content_per_page}--></td><td><input type="text" name="default_content_per_page" value="<!--{$config_vars.default_content_per_page}-->"></td></tr>
<tr><td><!--{$lang.default_template}--></td><td>
<select name="default_template">
<!--{section name=template loop=$installed_templates}-->
	<!--{if $config_vars.default_template == $installed_templates[template]}-->
		<option selected><!--{$installed_templates[template]}--></option>
	<!--{else}-->
		<option><!--{$installed_templates[template]}--></option>
	<!--{/if}-->
<!--{/section}-->
</select></td></tr>
<tr><td><!--{$lang.default_language}--></td><td>
<select name="default_lang">
<!--{section name=language loop=$installed_language}-->
	<!--{if $config_vars.default_lang == $installed_language[language]}-->
		<option selected><!--{$installed_language[language]}--></option>
	<!--{else}-->
		<option><!--{$installed_language[language]}--></option>
	<!--{/if}-->
	

<!--{/section}-->
</select></td></tr>


<tr><td>default usergroups</td>

<td>
	<table>
	<tr><td>
	<select name="selected_not_default_usergroups[]" size="5" multiple>
	<!--{section name=group_id loop=$not_default_usergroup_ids}-->
		<option value="<!--{$not_default_usergroup_ids[group_id].id}-->">
			<!--{$not_default_usergroup_ids[group_id].name}-->
		</option>
	<!--{/section}-->
	</select>
	</td>
	<td>
	<input type="submit" name="add_default_usergroup" value="-->"><br>
	<input type="submit" name="remove_default_usergroup" value="<--">
	</td>
	<td>
	<select name="selected_default_usergroups[]" size="5" multiple>
	
	<!--{section name=group_id loop=$default_usergroup_ids}-->
		<option value="<!--{$default_usergroup_ids[group_id].id}-->">
			<!--{$default_usergroup_ids[group_id].name}-->
		</option>
	<!--{/section}-->
	
	</select>
	
	</td></tr>
	</table>
</tr>

<tr><td>registered users usergroups</td>

<td>
	<table>
	<tr><td>
	<select name="selected_not_registered_users_usergroups[]" size="5" multiple>
	<!--{section name=group_id loop=$not_registered_users_usergroup_ids}-->
		<option value="<!--{$not_registered_users_usergroup_ids[group_id].id}-->">
			<!--{$not_registered_users_usergroup_ids[group_id].name}-->
		</option>
	<!--{/section}-->
	</select>
	</td>
	<td>
	<input type="submit" name="add_registered_users_usergroup" value="-->"><br>
	<input type="submit" name="remove_registered_users_usergroup" value="<--">
	</td>
	<td>
	<select name="selected_registered_users_usergroups[]" size="5" multiple>
	
	<!--{section name=group_id loop=$registered_users_usergroup_ids}-->
		<option value="<!--{$registered_users_usergroup_ids[group_id].id}-->">
			<!--{$registered_users_usergroup_ids[group_id].name}-->
		</option>
	<!--{/section}-->
	
	</select>
	
	</td></tr>
	</table>
</tr>



</table>
<input type="submit" name="submit">
</form>
<!--{include file="$template_name/admin/footer.tpl"}-->
