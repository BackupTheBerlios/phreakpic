<!--{include file="$template_name/admin/header.tpl"}-->
<br>
<!--{$lang.add_user_to_group}--><br><br>
<form action="user_in_groups.php?usergroup=<!--{$sel_usergroup}--><!--{$sid}--><!--{$sid}-->" method="POST">
<table border=1>
<tr>


<td>
	<!--{$lang.usergroups}-->
</td>


<td>
<!--{$lang.users_not_in_group}-->
</td>

<td>
</td>

<td>
<!--{$lang.users_in_group}-->
</td>

</tr>
<tr>
<td>


<!--{section name=id loop=$usergroups}-->


	


	<!--{ if $usergroups[id].id != $sel_usergroup}-->
		<a href="user_in_groups.php?usergroup=<!--{$usergroups[id].id}--><!--{$sid}-->"><!--{$usergroups[id].name}--></a>
	<!--{else}-->	
		<b><!--{$usergroups[id].name}--></b>
	<!--{/if}-->	

	<br>
<!--{/section}-->


</td>
<td>




<select multiple name="add_users[]">
<!--{foreach from=$users key=id item=username}-->

	<!--{if $user_in_group[$id] == false}-->
		<option value="<!--{$id}-->"><!--{$username}--></option>
	<!--{/if}-->
<!--{/foreach}-->
</select>
</td>
<td>
<input type="submit" name="add" value="-->"><br>
<input type="submit" name="remove" value="<--">
</td>
<td>
<select multiple name="remove_users[]">;
<!--{foreach from=$users key=id item=username}-->
	<!--{if $user_in_group[$id] == true}-->
		<option value="<!--{$id}-->"><!--{$username}--></option>
	<!--{/if}-->
<!--{/foreach}-->
</select>
</td>
</tr>
</table>
</form>
<!--{include file="$template_name/admin/footer.tpl"}-->
