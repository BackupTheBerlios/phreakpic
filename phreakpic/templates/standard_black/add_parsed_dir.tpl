<!--{include file="$template_name/header.tpl"}-->
<form action="add_parsed_dir.php?dir=<!--{$dir}-->&<!--{$sid}-->" method="post" name="content">

Add <!--{$amount.dirs}--> Direcotires and <!--{$amount.files}--> Files to<br>

<!--{$lang.contentgroup}-->
<select name="new_content_group">
	<!--{section name=id loop=$add_to_contentgroups}-->
		<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
	<!--{/section}-->
</select>
<br>

<!--{$lang.catgroup}-->
<select name="new_cat_group">
	<!--{section name=id loop=$add_to_catgroups}-->
		<option value="<!--{$add_to_catgroups[id].id}-->"><!--{$add_to_catgroups[id].name}--></option>
	<!--{/section}-->
</select>
<br>

<!--{$lang.to_cat}-->
<select name="parent_cat_id">
	<!--{section name=id loop=$add_to_cats}-->
		<option value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
	<!--{/section}-->
</select>
<br>
<input name="add_content" type="submit" value="<!--{$lang.add_selected}-->">

</form>

<!--{include file="$template_name/footer.tpl"}-->
