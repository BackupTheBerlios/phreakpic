<script type="text/javascript" language="javascript">
document.onkeypress = getkey;
var px=0;
var py=0;
var cursorColor='#FF0000';
var midfraction = 0.2;
var speedDefault = 10;
var animate=false;
var was_shift=false;
var keyactive = true;
var cursorOnSelectedColor='#FBA52A';
var selectedColor='#FFFF00';
var selected=new Array();
var sx=0;
var sy=0;

<!--{if $mode == edit}-->
	
	
	
	function keyon() { keyactive = true; }
	function keyoff() { keyactive = false; }
	
	
	
	function changeVal(name,val,all)
	{
		i=0;
		while (document.forms['edit_content'].elements[name][i] != undefined)
		{
			if ((selected[i]) || (all))
			{
				document.forms['edit_content'].elements[name][i].value=val;
			}
			i++;
		}
		
	}
	
	
	function changeRadio(name, all)
	{
		i=0;
		while (document.forms['edit_content'].elements[name][i] != undefined)
		{
			if ((selected[i]) || (all))
			{
				
				document.forms['edit_content'].elements[name][i].checked=true;
			}
			i++;
		}
	
	}

	
	function changeCheckbox(name,val,all)
	{
		i=0;
		
		while (document.forms['edit_content'].elements[name][i] != undefined)
		{
			if ((selected[i]) || (all))
			{
				document.forms['edit_content'].elements[name][i].checked=val;
			}
			i++;
		}
		
	}


	function setCheckboxes(the_form, do_check, boxes)
	{
			var elts      = document.forms[the_form].elements[boxes];
			var elts_cnt  = elts.length;
			for (var i = 0; i < elts_cnt; i++) 
			{
					elts[i].checked = do_check;
			} // end for

			return true;
	}


<!--{else}-->
<!--{/if}-->
	
	function follow_link()
	{
		cid=5;
		location.href = document.getElementsByName('link')[(py*4)+px].href;
	}


	function mark()
	{
		switchTd(px,py);
		setCursor(px,py);
	}

	function switchTd(x,y)
	{
		if (was_shift)
		{
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					if (selected[y*<!--{$table_cols}-->+x])
					{
						document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x].bgColor='#FFFFFF';
						selected[y*<!--{$table_cols}-->+x]=false;
					}
					else
					{
						selected[y*<!--{$table_cols}-->+x]=true;
						document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x].bgColor=selectedColor;
					}	
				}
			}
			
		}
		else
		{
			if (selected[y*<!--{$table_cols}-->+x])
			{
				document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x].bgColor='#FFFFFF';
				selected[y*<!--{$table_cols}-->+x]=false;
			}
			else
			{
				selected[y*<!--{$table_cols}-->+x]=true;
				document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x].bgColor=selectedColor;
			}
		}
	}


	function setCursor(x,y)
	{
		if (document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x] == undefined)
		{
			return;
		}
		
		if (selected[y*<!--{$table_cols}-->+x])
		{
			document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x].bgColor=cursorOnSelectedColor;
		}
		else
		{
			document.getElementsByName('td_thumb')[(y*<!--{$table_cols}-->)+x].bgColor=cursorColor;;
		}
	}
	
	function unsetCursor(x,y)
	{
		i=0;
		if (was_shift)
		{
			while (document.getElementsByName('td_thumb')[i] != undefined)
			{
				if (selected[i])
				{
					document.getElementsByName('td_thumb')[i].bgColor=selectedColor;
				}
				else
				{
					document.getElementsByName('td_thumb')[i].bgColor='#FFFFFF';
				}	
				i++;
			}
		}
		else
		{
			if (selected[y*<!--{$table_cols}-->+x])
			{
				document.getElementsByName('td_thumb')[y*<!--{$table_cols}-->+x].bgColor=selectedColor;
			}
			else
			{
				document.getElementsByName('td_thumb')[y*<!--{$table_cols}-->+x].bgColor='#FFFFFF';
			}
		}
		
	}


function winheight() {
  return document.all ? document.body.clientHeight : window.innerHeight;
}

function scrollpos() {
  return document.all ? document.body.scrollTop : window.pageYOffset;
}

function animateScrollToDest() {
  scrollPosition = scrollpos();
  dist = Math.abs(scrollPosition - dest)
  if ((dist <= 1) || (speed <= 1) || (dist < speed)) {
    // turns out that the screwy algorithm below doesn't
    // scroll quite to dest. but it's close enough for now.
    return;
  } else if (scrollPosition < dest) {
    delta = speed;
  } else {
    delta = -speed;
  }
  if (dist < 300) {
    speed = speedDefault - (speedDefault * (300 - dist) / 300.0);
  }
  window.scrollBy(0, delta);
  if (scrollpos() - scrollPosition == 0) {
    // haven't necessarily reached destination, but can't scroll anymore
    // ie, reached top or bottom of page
    return;
  }
  setTimeout("animateScrollToDest()", 10);
}




function scrollToMidpage(name, index , margin, animate) {
  windowHeight = winheight();
  scrollPosition = scrollpos();
  objectPosition = document.getElementsByName(name)[index].offsetTop+document.getElementsByName(name)[index].offsetHeight;
  if ((objectPosition > scrollPosition + windowHeight - margin) || (objectPosition < scrollPosition)) 
	{
		
    dest = objectPosition - (windowHeight * midfraction);
    if (animate) {
      speed = speedDefault;
      animateScrollToDest();
    } else {
      window.scrollTo(0, dest);
    }
  }
}

function up(shift)
{
	if (py>0)
	{
		if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			py=py-1;
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{		
			unsetCursor(px,py)
			was_shift=false;
			py=py-1;
			setCursor(px,py);
		}
		
		
	}
	scrollToMidpage('td_thumb', (py*4)+px , 500, animate);
}

function down(shift)
{
	if (document.getElementsByName('td_thumb')[((py+1)*4)+px] != undefined)
	{
		if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			py=py+1;
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{
			unsetCursor(px,py)
			was_shift=false;
			py=py+1;
			setCursor(px,py);
		}
		
	}
	scrollToMidpage('td_thumb', (py*4)+px , 500, animate);

}

function left(shift)
{
	if (px>0)
	{
	if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			px=px-1;
			
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{
			unsetCursor(px,py)
			was_shift=false;
			px=px-1;
			setCursor(px,py);
		}
	}
}

function right(shift)
{
	if (((px+1) < <!--{$table_cols}-->) && (document.getElementsByName('td_thumb')[(py*4)+px+1] != undefined))	
	{
		if (shift)
		{
			unsetCursor(px,py)
			if (!was_shift)
			{
				was_shift=true;
				sx=px;
				sy=py;
			}
			px=px+1;
			for (y=Math.min(sy,py);y<=Math.max(py,sy);y++)
			{
				for (x=Math.min(sx,px);x<=Math.max(px,sx);x++)
				{
					setCursor(x,y);	
				}
			}
		}
		else
		{
			unsetCursor(px,py)
			was_shift=false;
			px=px+1;
			setCursor(px,py);
		}
	}
}


function getkey(e) 
{
	if (!keyactive) return true;
	if (e == null) 
	{ 
		kcode = event.keyCode;
	} else 
	{ // mozilla
		if (e.altKey || e.ctrlKey) 
		{
			// moz doesn't override ctrl keys,
			// eg, Ctrl-N won't bypass this function to open new window
			return true;
		}
		kcode = e.which;
	}
	key = String.fromCharCode(kcode);
	// allow some keys to work w/o triggering prekey

//	if (allowkeys.indexOf(key) == -1) {
//		if (xprekey()) return true;
//	}
	
	switch(key) 
	{
		case "w": up(false); return false;
		case "s": down(false); return false;
		case "a": left(false); return false;
		case "d": right(false); return false;
		
		case "W": up(true); return false;
		case "S": down(true); return false;
		case "A": left(true); return false;
		case "D": right(true); return false;
		case " ": mark(); return false;
	}
	if (kcode==13)
	{
		follow_link();
	}
	
	
	return true;
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
			<td name="td_thumb" onclick="switchTd(<!--{$smarty.section.thumb_cell.index}-->,<!--{$smarty.section.thumb_cols.index}-->)">
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
				<a name="link" href="view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$thumbs[thumb_cols][thumb_cell].content_id}--><!--{$sid}-->"><!--{$thumbs[thumb_cols][thumb_cell].html}--></a><br>
				<font size="-1">
				<!--{$lang.name}-->: <!--{$thumbs[thumb_cols][thumb_cell].name}--><br>
				<!--{$lang.rating}-->: <!--{$thumbs[thumb_cols][thumb_cell].current_rating}--><br>
				<!--{$lang.views}-->: <!--{$thumbs[thumb_cols][thumb_cell].views}--><br>
				<!--{if $mode == edit}-->
					<input name="place_in_array[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
					<input name="content_id[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">

					<!--{if $thumbs[thumb_cols][thumb_cell].allow_edit == true}-->
						<!--{$lang.rotate}-->: 
						<!--{$lang.rotate_free}-->: <input type="radio" id="rotate_free" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="free" checked><input type="text" onfocus="keyoff()" onblur="keyon()" id="rotate" name="rotate[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]"><br> 
						<!--{$lang.rotate_left}--> <input type="radio" id="rotate_left" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="-90">
						<!--{$lang.rotate_180}--> <input type="radio" id="rotate_180" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="180">
						<!--{$lang.rotate_right}--> <input type="radio" id="rotate_right" name="rotate_mode[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" value="90"><br>
						<!--{$lang.name}-->: <input name="name[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" onfocus="keyoff()" onblur="keyon()" value="<!--{$thumbs[thumb_cols][thumb_cell].name}-->" size="20"><br>
						<!--{$lang.place_in_cat}-->: <input id="place_in_cat" name="place_in_cat[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" onfocus="keyoff()" onblur="keyon()" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_cat}-->" size="10"><br>
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
					<!--{if  $$thumbs[thumb_cols][thumb_cell].allow_remove_from_group == true}-->

						<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->):<input id="change_group" name="change_group[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
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
<!--{$lang.rotate_free}-->: <input type="radio" name="sel_rotate_mode" value="free" onClick="changeRadio('rotate_free',false)" checked><input type="text" onfocus="keyoff()" onblur="keyon()" name="sel_rotate" onkeyup="changeVal('rotate',document.getElementsByName('sel_rotate')[0].value,false)"><br> 
	<!--{$lang.rotate_left}--> <input type="radio" name="sel_rotate_mode" value="-90" onClick="changeRadio('rotate_left',false)" >
	<!--{$lang.rotate_180}--> <input type="radio" name="sel_rotate_mode" value="180" onClick="changeRadio('rotate_180',false)">
	<!--{$lang.rotate_right}--> <input type="radio" name="sel_rotate_mode" value="90" onClick="changeRadio('rotate_right',false)"><br>
	<!--{$lang.place_in_cat}-->: <input name="sel_place_in_cat" type="text" onfocus="keyoff()" onblur="keyon()"  size="10" onkeyup="changeVal('place_in_cat',document.getElementsByName('sel_place_in_cat')[0].value,false)"><br>
	<!--{$lang.lock}-->:<input name="sel_lock" type="checkbox" onClick="changeCheckbox('lock',document.getElementsByName('sel_lock')[0].checked,false)">
	<!--{$lang.delete}-->:<input name="sel_delete" type="checkbox" onClick="changeCheckbox('delete',document.getElementsByName('sel_delete')[0].checked,false)"><br>
	<!--{$lang.unlink}-->:<input name="sel_unlink" type="checkbox" onClick="changeCheckbox('unlink',document.getElementsByName('sel_unlink')[0].checked,false)"><br>
	
	<!--{$lang.link}-->:<input name="sel_link" type="checkbox" onClick="changeCheckbox('link',document.getElementsByName('sel_link')[0].checked,false)">
	
	<!--{$lang.move}-->:<input name="sel_move" type="checkbox" onClick="changeCheckbox('move',document.getElementsByName('sel_move')[0].checked,false)"><br>
	<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->):<input name="sel_change_group" type="checkbox" onClick="changeCheckbox('change_group',document.getElementsByName('sel_change_group')[0].checked,false)">
	

</td>
<td>
	<!--{$lang.rotate}-->: 
	<!--{$lang.rotate_free}-->: <input type="radio" name="all_rotate_mode" value="free" onClick="changeRadio('rotate_free',true)" checked><input type="text" onfocus="keyoff()" onblur="keyon()" name="all_rotate" onkeyup="changeVal('rotate',document.getElementsByName('all_rotate')[0].value,true)"><br> 
	<!--{$lang.rotate_left}--> <input type="radio" name="all_rotate_mode" value="-90" onClick="changeRadio('rotate_left',true)" >
	<!--{$lang.rotate_180}--> <input type="radio" name="all_rotate_mode" value="180" onClick="changeRadio('rotate_180',true)">
	<!--{$lang.rotate_right}--> <input type="radio" name="all_rotate_mode" value="90" onClick="changeRadio('rotate_right',true)"><br>
	<!--{$lang.place_in_cat}-->: <input name="all_place_in_cat" type="text" onfocus="keyoff()" onblur="keyon()"  size="10" onkeyup="changeVal('place_in_cat',document.getElementsByName('all_place_in_cat')[0].value,true)"><br>
	<!--{$lang.lock}-->:<input name="all_lock" type="checkbox" onClick="changeCheckbox('lock',document.getElementsByName('all_lock')[0].checked,true)">
	<!--{$lang.delete}-->:<input name="all_delete" type="checkbox" onClick="changeCheckbox('delete',document.getElementsByName('all_delete')[0].checked,true)"><br>
	<!--{$lang.unlink}-->:<input name="all_unlink" type="checkbox" onClick="changeCheckbox('unlink',document.getElementsByName('all_unlink')[0].checked,true)"><br>
	
	<!--{$lang.link}-->:<input name="all_link" type="checkbox" onClick="changeCheckbox('link',document.getElementsByName('all_link')[0].checked,true)">
	
	<!--{$lang.move}-->:<input name="all_move" type="checkbox" onClick="changeCheckbox('move',document.getElementsByName('all_move')[0].checked,true)"><br>
	<!--{$lang.change_group}--> (<!--{$thumbs[thumb_cols][thumb_cell].contentgroup_name}-->):<input name="all_change_group" type="checkbox" onClick="changeCheckbox('change_group',document.getElementsByName('all_change_group')[0].checked,true)">

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
		<select name="to_contengroup">
		<!--{section name=id loop=$add_to_contentgroups}-->
			<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
		<!--{/section}-->
		</select><br>
		
		
	<!--{/if}-->
	<input name="submit" type="submit" id="submit" value="<!--{$lang.commit}-->">
	</form>
<!--{/if}-->

