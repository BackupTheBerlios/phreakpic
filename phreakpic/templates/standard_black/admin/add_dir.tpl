<!--{include file="admin/header.tpl"}-->
Add_dir


New Contents Contentgroup:
<select name="new_content_group">
	<!--{section name=id loop=$add_to_contentgroups}-->
		<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
	<!--{/section}-->
</select>
<br>

New Cats Catgroup: 
<select name="add_to_catgroup">
	<!--{section name=id loop=$add_to_catgroups}-->
		<option value="<!--{$add_to_catgroups[id].id}-->"><!--{$add_to_catgroups[id].name}--></option>
	<!--{/section}-->
</select>
<br>

Parsed/cat_id:

<!--{include file="admin/footer.tpl"}-->
