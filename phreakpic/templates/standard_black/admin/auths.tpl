<!--{include file="$template_name/admin/header.tpl"}-->
<form action="auths.php?usergroup=<!--{$sel_usergroup}-->&group=<!--{$sel_group}--><!--{$sid}-->" method="POST">
<table border=1>
<tr>
<td>
	<!--{$lang.usergroups}-->
</td>
<td>
	<!--{$lang.perms}-->
</td>
<td>
	<!--{$group_name}-->
</td>
</tr>
<tr>
<td>

<!--{section name=id loop=$usergroups}-->
	<!--{ if $usergroups[id].id != $sel_usergroup}-->
		<a href="auths.php?usergroup=<!--{$usergroups[id].id}-->&group=<!--{$sel_group}--><!--{$sid}-->"><!--{$usergroups[id].name}--></a><br>
	<!--{else}-->	
		<b><!--{$usergroups[id].name}--></b><br>
	<!--{/if}-->	
<!--{/section}-->

</td>
<td>

<!--{if $auth_exists == true}-->
<!--{$lang.view}-->: <input type="checkbox" name="view" <!--{$view_checked}-->><br>
<!--{$lang.delete}-->: <input type="checkbox" name="delete" <!--{$delete_checked}-->><br>
<!--{$lang.edit}-->: <input type="checkbox" name="edit" <!--{$edit_checked}-->><br>
<!--{$lang.comment_edit}-->: <input type="checkbox" name="comment_edit" <!--{$comment_edit_checked}-->><br> 
<!--{$lang.add_to_group}-->: <input type="checkbox" name="add_to_group" <!--{$add_to_group_checked}-->><br> 
<!--{$lang.remove_from_group}-->: <input type="checkbox" name="remove_from_group" <!--{$remove_from_group_checked}-->><br> 
<!--{if $type=="cat"}-->
	<!--{$lang.cat_add}--> <input type="checkbox" name="cat_add" <!--{$cat_add_checked}-->><br>
	<!--{$lang.cat_remove}--> <input type="checkbox" name="cat_remove" <!--{$cat_remove_checked}-->><br>
	<!--{$lang.content_add}--> <input type="checkbox" name="content_add" <!--{$content_add_checked}-->><br>
	<!--{$lang.content_remove}--> <input type="checkbox" name="content_remove" <!--{$content_remove_checked}-->><br>
<!--{/if}-->
<input name="delete_auth" type="submit" value="<!--{$lang.delete_auth}-->"><br>
<!--{else}-->	
<input name="new_auth" type="submit" value="<!--{$lang.create_auth}-->"><br>
<!--{/if}-->

	
</td>
<td>

<!--{section name=id loop=$groups}-->
	
	<!--{ if $groups[id].id != $sel_group}-->
		<a href="auths.php?usergroup=<!--{$sel_usergroup}-->&group=<!--{$groups[id].id}--><!--{$sid}-->"><!--{$groups[id].name}--></a>
	<!--{else}-->	
		<b><!--{$groups[id].name}--></b>
	<!--{/if}-->	
	<br>
<!--{/section}-->

</td>
</tr>
</table>
<input name="change_auth" type="submit" value="<!--{$lang.commit}-->"><br><br>

</form>

<!--{include file="$template_name/admin/footer.tpl"}-->
