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
<tr>
	<td><b><!--{$lang.max_pic_size}--></b></td><td></td>
</tr>
<tr>
	<td><!--{$lang.height}--></td><td><input type="text" name="max_pic_height" value="<!--{$config_vars.max_picture_size.height}-->" size="5"></td>
</tr>
<tr>
	<td><!--{$lang.width}--></td><td><input type="text" name="max_pic_width" value="<!--{$config_vars.max_picture_size.width}-->" size="5"></td>
</tr>
<tr>
	<td><!--{$lang.maxsize}--></td><td><input type="text" name="max_pic_maxsize" value="<!--{$config_vars.max_picture_size.maxsize}-->" size="5"></td>
</tr>






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
<tr>
<td>Selectalbe Content Per Page</td>
<td>
<table>
	<tr>
		<td><input type="text" name="selectable_add_value"></td>
		<td>
			<input type="submit" name="add_selectable" value="-->"><br>
			<input type="submit" name="remove_selectable" value="<--">
		</td>
		<td>
			<select name="selected_selecteable_content_per_page[]" size="5" multiple>
				<!--{section name=selected_index loop=$selectable_content_per_page}-->
					<option value="<!--{$smarty.section.selected_index.index}-->">
						 <!--{$selectable_content_per_page[selected_index]}-->
					</option>
				<!--{/section}-->
			</select>
		
		</tr>
	</tr>
</table>
</td>

</tr>

<tr>

	<td>
	Default Basket Enable
	</td>
	<td>
	<input type="checkbox" name="default_basket_enable" <!--{$basket_enable}-->>
	</td>

</tr>

</table>
<input type="submit" name="submit">
</form>
<!--{include file="$template_name/admin/footer.tpl"}-->
