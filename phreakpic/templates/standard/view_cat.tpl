<!--{include file="$template_name/header.tpl"}-->
<script type="text/javascript" language="javascript">
	var cat_amount=<!--{$number_of_child_cats}-->
	var cat_sel=0;
</script>

<a name="nav_link" href="index.php?first_content=0<!--{$sid}-->"><!--{$lang.home}--></a> --
<!--{section name=id loop=$nav_string}-->
	<!--{if $smarty.section.id.last}-->
		<!--{$nav_string[id].name}-->
		<script type="text/javascript" language="javascript">
			catback=<!--{$smarty.section.id.index}-->
		</script>
	<!--{else}-->
		<a name="nav_link" href="view_cat.php?cat_id=<!--{$nav_string[id].id}-->&first_content=0<!--{$sid}-->">
		<!--{$nav_string[id].name}--></a> --
	<!--{/if}-->
<!--{/section}-->

<!--{if $number_of_child_cats > 0}-->
	<table width="60%" border="1" cellpadding="5" align="center">
		<tr>
			<td><!--{$lang.name}--></td>
			<td><!--{$lang.description}--></td>
			<td><!--{$lang.amount}--></td>
			<td><!--{$lang.rating}--></td>
		</tr>
		<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="delete_cat">
		<!--{section name=id loop=$number_of_child_cats}-->
			<tr>
				<td name="td_cat"><a name="cat_link" href="view_cat.php?cat_id=<!--{$child_cat_infos[id].id}-->&first_content=0<!--{$sid}-->"><!--{$child_cat_infos[id].name}--></a></td>
				<td><!--{$child_cat_infos[id].description}--></td>
				<td><!--{$child_cat_infos[id].content_amount}--> (<!--{$child_cat_infos[id].content_child_amount}-->)</td>
				<td><!--{$child_cat_infos[id].current_rating}--></td>
				<!--{if ($allow_cat_remove == 'true') and ($mode == 'edit')}-->	
					<td><input name="cat_delete" type="submit" id="<!--{$child_cat_infos[id].id}-->" value="<!--{$child_cat_infos[id].id}-->"></td>
				<!--{/if}-->
				
			</tr>
		<!--{/section}-->
		</form>
	</table>
<!--{else}-->
	<p><!--{$lang.no_subcategories}--></p>
<!--{/if}-->


<!--{$edit}-->

<!--{if  ($allow_cat_add == true) and ($mode == edit)}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="edit_content" id="edit_content">
	<!--{$lang.add_new_cat}-->
	<!--{$lang.name}-->: <input name="cat_name" type="text" onfocus="keyoff()" onblur="keyon()" size="20">
	<!--{$lang.catgroup}-->: 
	<select name="add_to_catgroup">
		<!--{section name=id loop=$add_to_catgroups}-->
			<option value="<!--{$add_to_catgroups[id].id}-->"><!--{$add_to_catgroups[id].name}--></option>
		<!--{/section}-->
	</select>
	<!--{$lang.is_serie}-->: <input name="cat_is_serie" type="checkbox"><br>
	<!--{$lang.description}-->: <textarea name="cat_describtion" onfocus="keyoff()" onblur="keyon()" cols="70" rows="5"></textarea>
	<input name="newcat" type="submit" id="submit" value="<!--{$lang.create}-->"><br>
	</from>
<!--{/if}-->




<!--{include file="$template_name/view_thumbs.tpl"}-->

<br>

<!--{if $first_content  != 0}-->
	<a name="prev_page" href="view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$first_content_prev}--><!--{$sid}-->">Prev</a>
<!--{/if}-->

<!--{section name=nav_page loop=$cat_nav_links}-->
	<!--{if $cat_nav_links[nav_page] == $first_content}-->
		<b><!--{$smarty.section.nav_page.index}--></b>
	<!--{else}-->
		<a href="view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$cat_nav_links[nav_page]}--><!--{$sid}-->"><!--{$smarty.section.nav_page.index}--></a>
	<!--{/if}-->
<!--{/section}-->

<!--{if $first_content_next != $cat_nav_links[0]}-->
	<a name="next_page" href="view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$first_content_next}--><!--{$sid}-->">Next</a>
<!--{/if}-->





<!--{if  ($allow_content_add == true) and ($mode == edit)}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="add_content" id="add_content" enctype="multipart/form-data">
	<!--{$lang.add_content}-->:<br>
	<!--{$lang.file}-->: <INPUT  name="new_content_file" TYPE="file" SIZE="30">
	<!--{$lang.contentgroup}-->: 
	<select name="new_content_group">
		<!--{section name=id loop=$add_to_contentgroups}-->
			<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
		<!--{/section}-->
	</select>
	<!--{$lang.name}-->: <input name="new_content_name" type="text" size="20">
	<!--{$lang.place_in_cat}-->: <input name="new_content_place_in_cat" type="text" size="5"><br>
	<input name="newcontent" type="submit" id="submit" value="<!--{$lang.add_content}-->">
	</from>
<!--{/if}-->

</div>

<table width="95%" align="center" border="0">
	<tr>
		<td width="20%">
			&nbsp;
		</td>
		<td>
			<!--{*if $comments != false*}-->
				<!--{include file="$template_name/show_comments.tpl" type="cat"}-->
			<!--{*else*}-->
				<a href="comment.php?mode=add&type=cat&parent_id=0&cat_id=<!--{$cat_id}--><!--{$sid}-->"><!--{$lang.add_comment}--></a>
			<!--{*/if*}-->
		</td>
	</tr>
</table>
<!--{include file="$template_name/footer.tpl"}-->
