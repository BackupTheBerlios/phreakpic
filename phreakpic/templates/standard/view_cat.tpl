<!--{include file="header.tpl"}-->
<a href="index.php"<!--{$sid}-->><!--{$lang.home}--></a> --
<!--{section name=id loop=$nav_string}-->
	<!--{if $smarty.section.id.last}-->
		<!--{$nav_string[id].name}-->
	<!--{else}-->
		<a href="view_cat.php?cat_id=<!--{$nav_string[id].id}--><!--{$sid}-->">
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
				<td><a href="view_cat.php?cat_id=<!--{$child_cat_infos[id].id}--><!--{$sid}-->"><!--{$child_cat_infos[id].name}--></a></td>
				<td><!--{$child_cat_infos[id].description}--></td>
				<td><!--{$child_cat_infos[id].content_amount}--></td>
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
	<!--{$lang.name}-->: <input name="cat_name" type="text" size="20">
	<!--{$lang.catgroup}-->: <input name="cat_group" type="text" size="5">
	<!--{$lang.is_serie}-->: <input name="cat_is_serie" type="checkbox"><br>
	<!--{$lang.description}-->: <textarea name="cat_describtion" cols="70" rows="5"></textarea>
	<input name="newcat" type="submit" id="submit" value="<!--{$lang.create}-->"><br>
	</from>
<!--{/if}-->




<!--{if $is_content == true}-->
	<!--{if $mode == edit}-->
		<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="edit_content" id="edit_content">
	<!--{/if}-->
	<table border=1 align=center>
		<!--{section name=thumb_cols loop=$thumbs}-->
		<tr>
			<!--{section name=thumb_cell loop=$thumbs[thumb_cols]}-->
			<td>
				<!--{*Possible fields of this table are: 
					html			the html tag to display the content
					name			the name of it
					current_rating	current rating of the content
					views			guess what?
					width			width of the content
					height			height of the content
					content_id		the id of the content
					
					allow_edit		this is for the edit fields. Don't use this...
				*}-->
				<a href="view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$thumbs[thumb_cols][thumb_cell].content_id}--><!--{$sid}-->"><!--{$thumbs[thumb_cols][thumb_cell].html}--></a><br>
				<!--{$lang.name}-->: <!--{$thumbs[thumb_cols][thumb_cell].name}--><br>
				<!--{$lang.rating}-->: <!--{$thumbs[thumb_cols][thumb_cell].current_rating}--><br>
				<!--{$lang.views}-->: <!--{$thumbs[thumb_cols][thumb_cell].views}--><br>
				<!--{if $mode == edit}-->
					<input name="place_in_array[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
					<input name="content_id[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
					<!--{if $thumbs[thumb_cols][thumb_cell].allow_edit == true}-->
						<!--{$lang.name}-->: <input name="name[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].name}-->" size="20"><br>
						<!--{$lang.place_in_cat}-->: <input name="place_in_cat[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_cat}-->" size="10"><br>
						<!--{$lang.lock}-->:<input name="lock[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox" <!--{$thumbs[thumb_cols][thumb_cell].locked}-->>
						
					<!--{/if}-->
					<!--{if  $thumbs[thumb_cols][thumb_cell].allow_delete == true}-->
						<!--{$lang.delete}-->:<input name="delete[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
					<!--{/if}-->
					<!--{if  $allow_content_remove == true}-->
						<!--{$lang.unlink}-->:<input name="unlink[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
					<!--{/if}-->
					<!--{if  $allow_link == true}-->
							<!--{$lang.link}-->:<input name="link[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
							<!--{if  $allow_content_remove == true}-->
								<!--{$lang.move}-->:<input name="move[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
							<!--{/if}-->
					<!--{/if}-->
					
				<!--{/if}-->
			</td>
			<!--{/section}-->
		</tr>
		<!--{/section}-->
	</table>
	
<!--{else}-->
	<!--{$lang.no_content}-->
<!--{/if}-->




<div align="center">
<!--{if $mode != edit}-->
	<a href="view_cat.php?mode=edit&cat_id=<!--{$cat_id}--><!--{$sid}-->"><!--{$lang.edit}--></a>
	<!--{if $edited == true}-->
		<!--{$lang.cat_edited}-->
	<!--{/if}-->
<!--{else}-->
	<input name="mode" type="hidden" value="edited">

	<!--{if  $allow_link == true}-->		
		<!--{$lang.to_cat}-->
		<select name="to_cat">
		<!--{section name=id loop=$add_to_cats}-->
			<option value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
		<!--{/section}-->
		</select><br>
	<!--{/if}-->
	<input name="submit" type="submit" id="submit" value="<!--{$lang.commit}-->">
	</form>
<!--{/if}-->
<!--{if  ($allow_content_add == true) and ($mode == edit)}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="add_content" id="add_content" enctype="multipart/form-data">
	<!--{$lang.add_content}-->:<br>
	<!--{$lang.file}-->: <INPUT  name="new_content_file" TYPE="file" SIZE="30">
	<!--{$lang.contentgroup}-->: <input name="new_content_group" type="text" size="5">
	<!--{$lang.name}-->: <input name="new_content_name" type="text" size="20">
	<!--{$lang.place_in_cat}-->: <input name="new_content_place_in_cat" type="text" size="5"><br>
	<input name="newcontent" type="submit" id="submit" value="<!--{$lang.add_content}-->">
	</from>
<!--{/if}-->

</div>

<table width="95%" align="center" border="1">
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
<!--{include file="footer.tpl"}-->
