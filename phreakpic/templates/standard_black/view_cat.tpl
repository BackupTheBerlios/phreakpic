<!--{include file="$template_name/header.tpl"}-->
<!--{config_load file="$template_name/config.cfg"}-->
<script type="text/javascript" language="javascript">
	<!--
	var cat_amount=<!--{$number_of_child_cats}-->
	var cat_sel=0;
	
	function change_content_per_page()
	{
		location.href = "view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$first_content}--><!--{$sid}-->"   + "&content_per_page=" + document.getElementsByName('selected_content_per_page')[0].value;
	}
	-->
</script>

<!--{include file="$template_name/nav_bar.tpl"}-->

<br>

<!--{if $number_of_child_cats > 0}-->
	<table width="100%" border="0" cellpadding="4">
		<tr bgcolor="<!--{#table_head_bg_color#}-->" class="genmed" id="cat_table_head">
			<td width="25%"><!--{$lang.name}--></td>
			<td width="75%"><!--{$lang.description}--></td>
			<td><!--{$lang.amount}--></td>
			<td><!--{$lang.rating}--></td>
			<td><!--{$lang.comments_amount}--></td>
			<!--{if $mode == 'edit'}-->
				<td><!--{$lang.catgroup}--></td>
				<td><!--{$lang.delete}--></td>
				
			<!--{/if}-->
			
		</tr>
		<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="edit_cat">
		<!--{section name=id loop=$number_of_child_cats}-->
			<tr bgcolor="<!--{#table_bg_color#}-->">
				
				
				<!--{if ($mode == 'edit') and ($child_cat_infos[id].edit == true)}-->
					<td id="td_cat" name="td_cat">
						<input name="cat_name[]" type="text" value="<!--{$child_cat_infos[id].name}-->">
					</td>
					<td>
						<input name="cat_description[]" type="text" value="<!--{$child_cat_infos[id].description}-->">
					</td>
					<td><!--{$child_cat_infos[id].content_amount}--> (<!--{$child_cat_infos[id].content_child_amount}-->)</td>
					<td><!--{$child_cat_infos[id].current_rating}--></td>
					<td><!--{$child_cat_infos[id].comments_amount}--></td>
					<td>
					
					<!--{if ($child_cat_infos[id].remove_from_group == 'true')}-->	
					
						<select name="cat_catgroup[]">
							<!--{section name=cat_id loop=$add_to_catgroups}-->
							<!--{if $child_cat_infos[id].catgroup_id == $add_to_catgroups[cat_id].id}-->
								<option selected value="<!--{$add_to_catgroups[cat_id].id}-->"><!--{$add_to_catgroups[cat_id].name}--></option>
							<!--{else}-->
								<option value="<!--{$add_to_catgroups[cat_id].id}-->"><!--{$add_to_catgroups[cat_id].name}--></option>
							<!--{/if}-->
							<!--{/section}-->
						</select><input type="checkbox" name="cat_apply_recursive[<!--{$smarty.section.id.index}-->]">
					<!--{/if}-->
					</td>
					
					<!--{if ($allow_cat_remove == 'true')}-->	
						<td><input name="cat_delete[<!--{$smarty.section.id.index}-->]" type="checkbox"></td>
					<!--{/if}-->
				<!--{else}-->
					<td id="td_cat" name="td_cat"><a name="cat_link" href="view_cat.php?cat_id=<!--{$child_cat_infos[id].id}-->&first_content=0<!--{$sid}-->"><!--{$child_cat_infos[id].name}--></a></td>
					<td><!--{$child_cat_infos[id].description}--></td>
					<td><!--{$child_cat_infos[id].content_amount}-->&nbsp;(<!--{$child_cat_infos[id].content_child_amount}-->)</td>
					<td><!--{$child_cat_infos[id].current_rating}--></td>
					<td><!--{$child_cat_infos[id].comments_amount}--></td>
				<!--{/if}-->
				
				
			</tr>
		<!--{/section}-->
		
		
	</table>
	<!--{if $mode == 'edit'}-->
	<input type="submit" name="edit_cat" >
	<!--{/if}-->
	</form>
<!--{else}-->
	<p><!--{$lang.no_subcategories}--></p>
<!--{/if}-->


<!--{if  ($allow_cat_add == true) and ($mode == edit)}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post">
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
	</form>
<!--{/if}-->

<p>&nbsp;</p>


<!--{include file="$template_name/view_thumbs.tpl"}-->

<br>

<!--{if $first_content  != 0}-->
	<a name="prev_page" href="view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$first_content_prev}--><!--{$sid}-->"><!--{$lang.nav_back}--></a>
<!--{/if}-->

<!--{section name=nav_page loop=$cat_nav_links}-->
	<!--{if $cat_nav_links[nav_page] == $first_content}-->
		<b><!--{$smarty.section.nav_page.iteration}--></b>
	<!--{else}-->
		
		<a href="view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$cat_nav_links[nav_page]}--><!--{$sid}-->"><!--{$smarty.section.nav_page.iteration}--></a>
	<!--{/if}-->
<!--{/section}-->

<!--{if $first_content_next != $cat_nav_links[0]}-->
	<a name="next_page" href="view_cat.php?cat_id=<!--{$cat_id}-->&first_content=<!--{$first_content_next}--><!--{$sid}-->"><!--{$lang.nav_next}--></a>
<!--{/if}-->


<br>

<!--{if $selectable_content_per_page}-->
	<!--{$lang.content_per_page}-->:
	<select onChange="change_content_per_page()" name="selected_content_per_page">
		<!--{section name=id loop=$selectable_content_per_page}-->
			<!--{if  $selectable_content_per_page[id].amount == $content_per_page}-->
				<option selected value="<!--{$selectable_content_per_page[id].amount}-->"><!--{$selectable_content_per_page[id].text}--></option>
			<!--{else}-->
				<option value="<!--{$selectable_content_per_page[id].amount}-->"><!--{$selectable_content_per_page[id].text}--></option>
			<!--{/if}-->
		<!--{/section}-->
	</select>
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
	</form>
<!--{/if}-->

</div>

<table width="95%" align="center" border="0">
	<tr>
		<td width="20%">&nbsp;
			
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
