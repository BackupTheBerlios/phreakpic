<!--{include file="$template_name/header.tpl"}-->

<b>Custom Searches</b> <br>

<!--{section name=id loop=$searches}-->
<!--{if ($query == $searches[id].id)}-->
	<b>
<!--{/if}-->
<a href=view_custom_searches.php?query=<!--{$searches[id].id}-->><!--{$searches[id].name}--></a><br>
<!--{if ($query == $searches[id].id)}-->
	</b>
<!--{/if}-->
<!--{/section}-->


<form action="view_custom_searches.php?query=<!--{$query}--><!--{$sid}-->" method="post" name="query">


<table border=1>
asd
<!--{section name=x loop=$fields}-->
<tr>
	
	
	<!--{section name=y loop=$fields[x] }-->
	<td>
		<!--{$fields[x][y].descr}-->	
		<!--{if ($fields[x][y].type == 'INPUT')}-->
			<input name="returns[<!--{$fields[x][y].name}-->][]" value="<!--{$fields[x][y].value}-->" type="text" size="20">	
		<!--{/if}-->
		
		<!--{if (($fields[x][y].type == 'DROPDOWN') or ($fields[x][y].type == 'OPERATOR'))}-->

			<select name="returns[<!--{$fields[x][y].name}-->][]">
			<!--{section name=v loop=$fields[x][y].value}-->
			<!--{$fields[x][y].selected}-->
			<!--{if ($smarty.section.v.index == $fields[x][y].selected)}-->		
				<option selected>
			<!--{else}-->
				<option>
			<!--{/if}-->
			<!--{$fields[x][y].value[v]}--></option>
			<!--{/section}-->
			</select>

		<!--{/if}-->
		
	
	</td>
	<!--{/section}-->
</tr>	
<!--{/section}-->


<tr>
<!--{section name=y loop=$fields[0]}-->
	<td>

		<!--{if ($fields[0][y].loop > 0)}-->
			<input type="hidden" name="row" value="<!--{$fields[0][y].loop}-->">
			<input name="add" type="submit"  value="add">
			<input name="remove" type="submit"  value="remove">
		<!--{/if}-->
	
	</td>
<!--{/section}-->
</tr>
</table>

<!--{if ($fields)}-->
<input name="submit" type="submit"  value="Query">
<!--{/if}-->
</form>

<!--{include file="$template_name/view_thumbs.tpl"}-->

<!--{include file="$template_name/footer.tpl"}-->
