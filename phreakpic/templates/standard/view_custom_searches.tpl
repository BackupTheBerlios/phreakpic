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



<!--{section name=id loop=$param}-->
	
	<!--{$param[id].text}-->
	<!--{if ($param[id].type == 'INPUT')}-->
		<input name="returns[]" value="<!--{$param[id].value}-->" ty	pe="text" size="20">	
	<!--{/if}-->
	<!--{if ($param[id].type == 'DROPDOWN')}-->
		
		<select name="returns[]">
		<!--{section name=v loop=$param[id].value}-->
		<!--{if ($rets[id]) == $param[id].value[v]}-->		
			<option selected>
		<!--{else}-->
			<option>
		<!--{/if}-->
		<!--{$param[id].value[v]}--></option>
		<!--{/section}-->
		</select>
		
	<!--{/if}-->
<!--{/section}-->
<!--{if ($param)}-->
<input name="submit" type="submit"  value="Query">
<!--{/if}-->
</form>

<!--{include file="$template_name/view_thumbs.tpl"}-->

<!--{include file="$template_name/footer.tpl"}-->
