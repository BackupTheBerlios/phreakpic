<!--{include file="$template_name/admin/header.tpl"}-->

<form action="config.php?<!--{$sid}-->" method="POST">
<table border="1">
<tr><td>Design Settings</td></tr>
<tr><td><!--{$lang.thumb_table_cols}--></td><td><input type="text" name="thumb_table_cols" value="<!--{$config_vars.thumb_table_cols}-->"></td></tr>
<tr><td><!--{$lang.content_per_page}--></td><td><input type="text" name="default_content_per_page" value="<!--{$config_vars.default_content_per_page}-->"></td></tr>
<tr><td><!--{$lang.default_template}--></td><td>
<select name="default_template">
<!--{section name=template loop=$installed_templates}-->
	<!--{if $config_vars.default_template == $installed_templates[template]}-->
		<option selected><!--{$installed_templates[template]}--></option>
	<!--{else}-->
		<option><!--{$installed_templates[template]}--></option>
	<!--{/if}-->
<!--{/section}-->
</select></td></tr>
<tr><td><!--{$lang.default_language}--></td><td>
<select name="default_lang">
<!--{section name=language loop=$installed_language}-->
	<!--{if $config_vars.default_lang == $installed_language[language]}-->
		<option selected><!--{$installed_language[language]}--></option>
	<!--{else}-->
		<option><!--{$installed_language[language]}--></option>
	<!--{/if}-->
<!--{/section}-->
</select></td></tr>

</table>
<input type="submit" name="submit">
</form>
<!--{include file="$template_name/admin/footer.tpl"}-->