<!--{include file="$template_name/header.tpl"}-->
<!--{config_load file="$template_name/config.cfg"}-->

<table border=1>


<!--{foreach from=$error_array item=error}-->
<!--{if $error.level == E_NOTICE}-->
<tr bgcolor="<!--{#E_NOTICE_COLOR#}-->">
<!--{elseif $error.level == E_WARNING}-->
<tr bgcolor="<!--{#E_WARNING_COLOR#}-->">
<!--{elseif $error.level == E_ERROR}-->
<tr bgcolor="<!--{#E_ERROR_COLOR#}-->">
<!--{/if}-->
<td>
<!--{$error.type}-->


<!--{if $error.type == 'SQL_ERROR'}-->
	<br /><br /><!--{$error.sql_error.code}-->  <!--{$error.sql_error.message}--> <br>


SQL: <!--{$error.sql}--></br /><br />
<!--{/if}-->
Line : <!--{$error.line}--> File : <!--{$error.file}-->


</td>
</tr>
<!--{/foreach}-->
</table>


<!--{include file="$template_name/footer.tpl"}-->