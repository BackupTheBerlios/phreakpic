<!--{include file="header.tpl"}-->
<form action="auths.php?usergroup=<!--{$sel_usergroup}-->&group=<!--{$sel_group}-->" method="POST">
<table border=1>
<tr><td>

<!--{section name=id loop=$usergroups}-->
	<!--{ if $usergroups[id].id != $sel_usergroup}-->
		<a href="auths.php?usergroup=<!--{$usergroups[id].id}-->&group=<!--{$sel_group}-->"><!--{$usergroups[id].name}--></a><br>
	<!--{else}-->	
		<b><!--{$usergroups[id].name}--></b><br>
	<!--{/if}-->	
<!--{/section}-->

</td>
<td>

<!--{if $auth_exists == true}-->
view: <input type="checkbox" name="view" <!--{$view_checked}-->><br>
delete: <input type="checkbox" name="delete" <!--{$delete_checked}-->><br>
edit: <input type="checkbox" name="edit" <!--{$edit_checked}-->><br>
comment_edit: <input type="checkbox" name="comment_edit" <!--{$comment_edit_checked}-->><br> 
<input name="delete_auth" type="submit" value="Delete Auth"><br>
<!--{else}-->	
<input name="new_auth" type="submit" value="Create Auth"><br>
<!--{/if}-->


</td>
<td>
<!--{section name=id loop=$groups}-->
	<a href="auths.php?del_group=<!--{$groups[id].id}-->">del</a>
	<!--{ if $groups[id].id != $sel_group}-->
		<a href="auths.php?usergroup=<!--{$sel_usergroup}-->&group=<!--{$groups[id].id}-->"><!--{$groups[id].name}--></a><br>
	<!--{else}-->	
		<b><!--{$groups[id].name}--></b><br>
	<!--{/if}-->	
<!--{/section}-->
</td>
</tr>
</table>
<input name="change_auth" type="submit" value="Submit"><br>
Create New Group:<br>
Name: <input type="text" name="name"><br>
Describtion: <textarea name="describtion" cols="30" rows="4"></textarea>
<input name="new_group" type="submit" value="Create"><br>

</form>

<!--{include file="footer.tpl"}-->
