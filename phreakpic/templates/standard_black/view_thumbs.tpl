<!--{config_load file="$template_name/config.cfg"}-->
<script type="text/javascript" language="javascript">
table_cols = <!--{$table_cols|default:0}-->
</script>
<script src="templates/<!--{$template_name}-->/functions.js" type="text/javascript" language="javascript">
</script>
<script type="text/javascript" language="javascript">
	document.onkeypress = getkey_cat;
	var px=0;
	var py=0;
	var cursorColor='<!--{#cursorColor#}-->'
	var midfraction = 0.2;
	var speedDefault = 10;
	var animate=false;
	var was_shift=false;
	var keyactive = true;
	var backGroundColor='<!--{#table_bg_color#}-->'
	var cursorOnSelectedColor='<!--{#cursorOnSelectedColor#}-->'
	var selectedColor='<!--{#selectedColor#}-->'
	var selected=new Array();
	var sx=0;
	var sy=0;
</script>

<!--{if $is_content == true}-->
	<!--{if $mode == edit}-->
		<form action="view_cat.php?cat_id=<!--{$cat_id}-->&content_per_page=<!--{$content_per_page}--><!--{$sid}-->" method="post" name="edit_content" id="edit_content" name="edit_content" >
	<!--{/if}-->
	<table border="0" align="center" cellspacing="10" cellpadding="10">
		<!--{section name=thumb_cols loop=$thumbs}-->
		<tr bgcolor="<!--{#table_bg_color#}-->" valign="bottom">
			<!--{section name=thumb_cell loop=$thumbs[thumb_cols]}-->
			<td id="td_thumb" width="<!--{$thumb_size}-->" height="<!--{$thumb_size}-->" name="td_thumb" onclick="was_shift=false; switchTd(<!--{$smarty.section.thumb_cell.index}-->,<!--{$smarty.section.thumb_cols.index}-->)">
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
<!--				<table border="0" cellspacing="0" cellpadding="5" bgcolor="333333">
					<tr>-->
						<a name="<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->">
						<!--<td width="<!--{$thumb_size}-->" bgcolor="333333">-->
							<a name="link" href="view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$thumbs[thumb_cols][thumb_cell].content_id}--><!--{$sid}-->#pic"><!--{$thumbs[thumb_cols][thumb_cell].html}--></a><br />
						<!--</td>
						<td>-->
							<span class="genmed">
								<!--{$lang.name}-->: <!--{$thumbs[thumb_cols][thumb_cell].name|truncate:15:"...":true}--><br>
								<!--{$lang.rating}-->: <!--{$thumbs[thumb_cols][thumb_cell].current_rating}--><br>
								<!--{$lang.views}-->: <!--{$thumbs[thumb_cols][thumb_cell].views}--><br>
								<!--{if $mode == edit}-->
									<input name="place_in_array[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
									<input name="content_id[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
									<!--{if $thumbs[thumb_cols][thumb_cell].allow_edit == true}-->
										<!--{$lang.rotate}-->: 
										<!--{$lang.rotate_free}-->: <input type="radio" id="rotate_free" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="free" checked><input type="text" id="rotate" name="rotate"><br>
										<!--{$lang.rotate_left}--> <input type="radio" id="rotate_left" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="-90">
										<!--{$lang.rotate_180}--> <input type="radio" id="rotate_180" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="180">
										<!--{$lang.rotate_right}--> <input type="radio" id="rotate_right" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="90"><br>
										<!--{$lang.name}-->: <input name="name[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].name}-->" size="10" onfocus="keyoff()" onblur="keyon()"><br>
										<!--{$lang.place_in_cat}-->: <input name="place_in_cat[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_cat}-->" size="2" onfocus="keyoff()" onblur="keyon()"><br>
										<!--{$lang.lock}-->:<input id="lock"  name="lock[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox" <!--{$thumbs[thumb_cols][thumb_cell].locked}-->>
									<!--{/if}-->
									<!--{if  $thumbs[thumb_cols][thumb_cell].allow_delete == true}-->
										<!--{$lang.delete}-->:<input id="delete" name="delete[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
									<!--{/if}-->
									<!--{if  $allow_content_remove == true}-->
										<!--{$lang.unlink}-->:<input id="unlink" name="unlink[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
									<!--{/if}-->
									<!--{if  $allow_link == true}-->
										<!--{$lang.link}-->:<input id="link" name="link[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
											<!--{if  $allow_content_remove == true}-->
												<!--{$lang.move}-->:<input id="move" name="move[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
											<!--{/if}-->
									<!--{/if}-->
									<!--{if  $thumbs[thumb_cols][thumb_cell].allow_remove_from_group == true}-->
										<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->): <input id="change_group" name="change_group[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
									<!--{/if}-->-->
								<!--{/if}-->
							</span>
						</td>
						</a>
					<!--</tr>
				</table>-->
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
	<a href="<!--{$thumb_link}-->&mode=edit&content_per_page=<!--{$content_per_page}--><!--{$sid}-->"><!--{$lang.edit}--></a>
	<!--{if $edited == true}-->
		<!--{$lang.cat_edited}-->
	<!--{/if}-->
<!--{else}-->
<table border=1>
<tr>
<td>
For all selected Change
</td>
<td>
For all Change
</td>
</tr>
<tr>
<td>
	<!--{$lang.rotate}-->: 
<!--{$lang.rotate_free}-->: <input type="radio" name="sel_rotate_mode" value="free" onclick="changeRadio('rotate_free',false)" checked><input type="text" onfocus="keyoff()" onblur="keyon()" name="sel_rotate" onkeyup="changeVal('rotate',document.getElementsByName('sel_rotate')[0].value,false)"><br> 
	<!--{$lang.rotate_left}--> <input type="radio" name="sel_rotate_mode" value="-90" onclick="changeRadio('rotate_left',false)" >
	<!--{$lang.rotate_180}--> <input type="radio" name="sel_rotate_mode" value="180" onclick="changeRadio('rotate_180',false)">
	<!--{$lang.rotate_right}--> <input type="radio" name="sel_rotate_mode" value="90" onclick="changeRadio('rotate_right',false)"><br>
	<!--{$lang.place_in_cat}-->: <input name="sel_place_in_cat" type="text" onfocus="keyoff()" onblur="keyon()"  size="10" onkeyup="changeVal('place_in_cat',document.getElementsByName('sel_place_in_cat')[0].value,false)"><br>
	<!--{$lang.lock}-->:<input name="sel_lock" type="checkbox" onclick="changeCheckbox('lock',document.getElementsByName('sel_lock')[0].checked,false)">
	<!--{$lang.delete}-->:<input name="sel_delete" type="checkbox" onclick="changeCheckbox('delete',document.getElementsByName('sel_delete')[0].checked,false)"><br>
	<!--{$lang.unlink}-->:<input name="sel_unlink" type="checkbox" onclick="changeCheckbox('unlink',document.getElementsByName('sel_unlink')[0].checked,false)"><br>
	
	<!--{$lang.link}-->:<input name="sel_link" type="checkbox" onclick="changeCheckbox('link',document.getElementsByName('sel_link')[0].checked,false)">
	
	<!--{$lang.move}-->:<input name="sel_move" type="checkbox" onclick="changeCheckbox('move',document.getElementsByName('sel_move')[0].checked,false)"><br>
	<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->):<input name="sel_change_group" type="checkbox" onclick="changeCheckbox('change_group',document.getElementsByName('sel_change_group')[0].checked,false)">
	

</td>
<td>
	<!--{$lang.rotate}-->: 
	<!--{$lang.rotate_free}-->: <input type="radio" name="all_rotate_mode" value="free" onclick="changeRadio('rotate_free',true)" checked><input type="text" onfocus="keyoff()" onblur="keyon()" name="all_rotate" onkeyup="changeVal('rotate',document.getElementsByName('all_rotate')[0].value,true)"><br> 
	<!--{$lang.rotate_left}--> <input type="radio" name="all_rotate_mode" value="-90" onclick="changeRadio('rotate_left',true)" >
	<!--{$lang.rotate_180}--> <input type="radio" name="all_rotate_mode" value="180" onclick="changeRadio('rotate_180',true)">
	<!--{$lang.rotate_right}--> <input type="radio" name="all_rotate_mode" value="90" onclick="changeRadio('rotate_right',true)"><br>
	<!--{$lang.place_in_cat}-->: <input name="all_place_in_cat" type="text" onfocus="keyoff()" onblur="keyon()"  size="10" onkeyup="changeVal('place_in_cat',document.getElementsByName('all_place_in_cat')[0].value,true)"><br>
	<!--{$lang.lock}-->:<input name="all_lock" type="checkbox" onclick="changeCheckbox('lock',document.getElementsByName('all_lock')[0].checked,true)">
	<!--{$lang.delete}-->:<input name="all_delete" type="checkbox" onclick="changeCheckbox('delete',document.getElementsByName('all_delete')[0].checked,true)"><br>
	<!--{$lang.unlink}-->:<input name="all_unlink" type="checkbox" onclick="changeCheckbox('unlink',document.getElementsByName('all_unlink')[0].checked,true)"><br>
	
	<!--{$lang.link}-->:<input name="all_link" type="checkbox" onclick="changeCheckbox('link',document.getElementsByName('all_link')[0].checked,true)">
	
	<!--{$lang.move}-->:<input name="all_move" type="checkbox" onclick="changeCheckbox('move',document.getElementsByName('all_move')[0].checked,true)"><br>
	<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->):<input name="all_change_group" type="checkbox" onclick="changeCheckbox('change_group',document.getElementsByName('all_change_group')[0].checked,true)">

</td>

</tr>
</table>


	<input name="mode" type="hidden" value="edited">

	<!--{if  $allow_link == true}-->		
		<!--{$lang.to_cat}-->
		<select name="to_cat">
		<!--{section name=id loop=$add_to_cats}-->
			<option value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
		<!--{/section}-->
		</select><br>
		
		<!--{$lang.to_group}-->
		<select name="to_contentgroup">
		<!--{section name=id loop=$add_to_contentgroups}-->
			<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
		<!--{/section}-->
		</select><br>
		
		
	<!--{/if}-->
	<input name="submit" type="submit" id="submit" value="<!--{$lang.commit}-->">
	</form>
<!--{/if}-->

