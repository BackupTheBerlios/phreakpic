<!--{include file="$template_name/header.tpl"}-->
<br>
<!--{$lang.new_usergroup}-->
<form action="user_in_groups.php?usergroup=<!--{$sel_usergroup}--><!--{$sid}-->" method="POST">
  

<!--{$lang.name}-->: <input type="text" name="name"><br>
<!--{$lang.description}-->: <textarea name="describtion" cols="30" rows="4"></textarea>
<input name="new_usergroup" type="submit" value="<!--{$lang.create}-->"><br>
<br>
<!--{$lang.add_user_to_group}--><br><br>
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
<table>

<!--{section name=id loop=$usergroups}-->
<tr>
	<td>
	<a href="user_in_groups.php?del_usergroup=<!--{$usergroups[id].id}--><!--{$sid}-->"><!--{$lang.delete}--></a>
	</td>
	<td>
	<!--{ if $usergroups[id].id != $sel_usergroup}-->
		<a href="user_in_groups.php?usergroup=<!--{$usergroups[id].id}--><!--{$sid}-->"><!--{$usergroups[id].name}--></a><br>
	<!--{else}-->	
		<b><!--{$usergroups[id].name}--></b><br>
	<!--{/if}-->	
	</td>
</tr>
<!--{/section}-->

</table>
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
<!--{include file="$template_name/footer.tpl"}-->
