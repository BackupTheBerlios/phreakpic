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
						<!--{$lang.rotate}-->: 
						<!--{$lang.rotate_free}-->: <input type="radio" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="free" checked><input type="text" name="rotate"><br> 
						<!--{$lang.rotate_left}--> <input type="radio" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="-90">
						<!--{$lang.rotate_180}--> <input type="radio" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="180">
						<!--{$lang.rotate_right}--> <input type="radio" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="90"><br>
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
					<!--{if  $$thumbs[thumb_cols][thumb_cell].allow_remove_from_group == true}-->
				
						<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->) :<input name="change_group[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
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
	<a href="<!--{$thumb_link}-->&mode=edit<!--{$sid}-->"><!--{$lang.edit}--></a>
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
		
		<!--{$lang.to_group}-->
		<select name="to_contengroup">
		<!--{section name=id loop=$add_to_contentgroups}-->
			<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
		<!--{/section}-->
		</select><br>
		
		
	<!--{/if}-->
	<input name="submit" type="submit" id="submit" value="<!--{$lang.commit}-->">
	</form>
<!--{/if}-->

