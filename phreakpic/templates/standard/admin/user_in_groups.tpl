<!--{include file="header.tpl"}-->
Create New User Group<br>

<form action="user_in_groups.php?usergroup=<!--{$sel_usergroup}-->" method="POST">
  

Name: <input type="text" name="name"><br>
Describtion: <textarea name="describtion" cols="30" rows="4"></textarea>
<input name="new_usergroup" type="submit" value="Create"><br>

Add Users to User Group<br><br>
<table border=1>
<tr>
<td>
<!--{section name=id loop=$usergroups}-->
	<a href="user_in_groups.php?del_usergroup=<!--{$usergroups[id].id}-->">del</a>
	<!--{ if $usergroups[id].id != $sel_usergroup}-->
		<a href="user_in_groups.php?usergroup=<!--{$usergroups[id].id}-->"><!--{$usergroups[id].name}--></a><br>
	<!--{else}-->	
		<b><!--{$usergroups[id].name}--></b><br>
	<!--{/if}-->	
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
<!--{include file="footer.tpl"}-->