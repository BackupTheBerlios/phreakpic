<!--{include file="$template_name/admin/header.tpl"}-->

<!--{if $sel_group_id == -1}-->
	<form action="groups.php?sel_group_id=<!--{$sel_group_id}-->&done<!--{$sid}-->#form" method="POST">
	<table border=1 align="center">
		<tr><td><!--{$lang.name}--></td><td><input type="text" name="name" value="<!--{$sel_group.name}-->"></td></tr>
		<tr><td><!--{$lang.description}--></td><td><textarea name="description" cols="30" rows="4"><!--{$sel_group.description}--></textarea></td></tr>
		<tr><td><input name="create" type="submit" value="<!--{$lang.create}-->"></td></tr>
	</table>
	</form>
<!--{else}-->
<table border=1 align="center">
	<tr>
		<td><!--{$group_name}--></td>
		<td><!--{$lang.description}--></td>
	</tr>



	<!--{section name=id loop=$groups}-->
	<tr>
		<td>
		<!--{ if $groups[id].id != $sel_group_id}-->
			<a href="groups.php?sel_group_id=<!--{$groups[id].id}--><!--{$sid}-->#form"><!--{$groups[id].name}--></a><br>
		<!--{else}-->	
			<b><!--{$groups[id].name}--></b>
		<!--{/if}-->	
		</td>
		<td><!--{$groups[id].description}--></td>
		<!--{ if $groups[id].id == $sel_group_id}-->

			<td>
				<a name="form">
				<form action="groups.php?sel_group_id=<!--{$sel_group_id}--><!--{$sid}-->#form" method="POST">
					<table border=1 align="center">
						<tr><td><!--{$lang.name}--></td><td><input type="text" name="name" value="<!--{$sel_group.name}-->"></td></tr>
						<tr><td><!--{$lang.description}--></td><td><textarea name="description" cols="30" rows="4"><!--{$sel_group.description}--></textarea></td></tr>
						<tr><td><input name="change" type="submit" value="<!--{$lang.commit}-->"></td>
								<td><input name="delete" type="submit" value="<!--{$lang.delete}-->"></td></tr>
					</table>
				</form>
				</a>
			</td>
		<!--{/if}-->	
		
	</tr>
	<!--{/section}-->
</table>
<!--{/if}-->

<form action="groups.php?sel_group_id=-1<!--{$sid}-->#form" method="POST">
<input type="submit" value="<!--{$new_group}-->">
</form>

<!--{include file="$template_name/admin/footer.tpl"}-->
