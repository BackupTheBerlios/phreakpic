<script type="text/javascript" language="javascript">
var selected=new Array();
function setCheckboxes(the_form, do_check, boxes)
{

		
    var elts      = document.forms[the_form].elements[boxes];
    var elts_cnt  = elts.length;
    for (var i = 0; i < elts_cnt; i++) {
        elts[i].checked = do_check;
    } // end for

    return true;
}

function markTd(x,y)
{
	document.getElementsByName('td_thumb')[(x*4)+y].bgColor='#ff0000';
}
function unmarkTd(x,y)
{
	document.getElementsByName('td_thumb')[(x*4)+y].bgColor='#FFFFFF';
}

function switchTd(x,y)
{
	if (selected[x][y])
	{
		document.getElementsByName('td_thumb')[(x*4)+y].bgColor='#FFFFFF';
		selected[x][y]=false;
	}
	else
	{
		selected[x][y]=true;
		document.getElementsByName('td_thumb')[(x*4)+y].bgColor='#FFFF00';
	}
	
	
}
</script>

<!--{if $is_content == true}-->
	<!--{if $mode == edit}-->
		<form action="view_cat.php?cat_id=<!--{$cat_id}--><!--{$sid}-->" method="post" name="edit_content" id="edit_content" name="content" onKeyDown="switchTd('1','1')">
	<!--{/if}-->
	<table border="0" align="center" cellspacing="10">
		<!--{section name=thumb_cols loop=$thumbs}-->
		<tr>
			<!--{section name=thumb_cell loop=$thumbs[thumb_cols]}-->
			<td name="td_thumb" onclick="switchTd(<!--{$smarty.section.thumb_cols.index}-->,<!--{$smarty.section.thumb_cell.index}-->)">
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
				<table width="10" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<a name="<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->">
						<td>
							<a href="view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$thumbs[thumb_cols][thumb_cell].content_id}--><!--{$sid}-->">
								<!--{$thumbs[thumb_cols][thumb_cell].html}-->
							</a><br />
							<font size="-1">
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
										<a onDblClick="setCheckboxes('edit_content', !document.edit_content.elements['lock'][<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->].checked,'lock'); return false;"><!--{$lang.lock}--></a>:<input id="lock"  name="lock[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox" <!--{$thumbs[thumb_cols][thumb_cell].locked}-->>
									<!--{/if}-->
									<!--{if  $thumbs[thumb_cols][thumb_cell].allow_delete == true}-->
										<a onDblClick="setCheckboxes('edit_content', !document.edit_content.elements['delete'][<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->].checked,'delete'); return false;"><!--{$lang.delete}--></a>:<input id="delete" name="delete[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
									<!--{/if}-->
									<!--{if  $allow_content_remove == true}-->
										<a onDblClick="setCheckboxes('edit_content', !document.edit_content.elements['unlink'][<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->].checked,'unlink'); return false;"><!--{$lang.unlink}--></a>:<input id="unlink" name="unlink[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
									<!--{/if}-->
									<!--{if  $allow_link == true}-->
										<a onDblClick="setCheckboxes('edit_content', !document.edit_content.elements['link'][<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->].checked,'link'); return false;"><!--{$lang.link}--></a>:<input id="link" name="link[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
											<!--{if  $allow_content_remove == true}-->
												<a onDblClick="setCheckboxes('edit_content', !document.edit_content.elements['move'][<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->].checked,'move'); return false;"><!--{$lang.move}--></a>:<input id="move" name="move[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
											<!--{/if}-->
									<!--{/if}-->
									<!--{if  $$thumbs[thumb_cols][thumb_cell].allow_remove_from_group == true}-->
										<a onDblClick="setCheckboxes('edit_content', !document.edit_content.elements['change_group'][<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->].checked,'change_group'); return false;"><!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->)</a> :<input id="change_group" name="change_group[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
									<!--{/if}-->
								<!--{/if}-->
							</font>
						</td>
						</a>
					</tr>
				</table>
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

