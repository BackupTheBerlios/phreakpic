<!--{include file="$template_name/admin/header.tpl"}-->

<form action="lang.php?<!--{$sid}-->" method="POST">
<table border=1>
<tr><td>Key</td>


<!--{foreach from=$installed_langs item=v}-->
	<td><!--{$v}--></td>
<!--{/foreach}-->
</tr>
<!--{foreach from=$words item=v	 key=k}-->
	<tr>
	<td><!--{$v.code}--></td>

		<!--{foreach from=$installed_langs item=l}-->
		<td><input type="text" name=trans[<!--{$k}-->][<!--{$l}-->] value="<!--{$v[$l]}-->"></td>
		<!--{/foreach}-->

</tr>

<!--{/foreach}-->
</table>
<input name="submit" type="submit" ><br>
</form>

<!--{include file="$template_name/admin/footer.tpl"}-->
