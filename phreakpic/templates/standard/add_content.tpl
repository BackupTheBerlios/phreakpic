<script type="text/javascript" language="javascript">
var pics = new Array()
var picsize = <!--{$thumbsize}-->;
function setCheckboxes(the_form, do_check, boxes)
{
    var elts      = document.forms[the_form].elements[boxes];
    var elts_cnt  = elts.length;

    for (var i = 0; i < elts_cnt; i++) {
        elts[i].checked = do_check;
    } // end for

    return true;
}

function setPreview(url)
{
//		document.getElementById('preview_td').width; 
	
	document.images['preview'].src = url;
	document.images['preview'].width = document.getElementById('preview_td').offsetWidth-4; 
    
	return true;
}

function thumbsOn()
{
	for(var i=0; i < <!--{$files_size}-->;i++)
	{
		document.images[pics[i]].src=pics[i];
		document.images[pics[i]].height=picsize;
	}
	document.content.thumbs.value=true;
}

function thumbsOff()
{
	for(var i=0; i < <!--{$files_size}-->;i++)
	{
		document.images[pics[i]].src='';
		document.images[pics[i]].height=0;
	}
	document.content.thumbs.value=false;
}


function move(id)
{
	document.content.id.value=id;
	document.content.thumbsize.value=picsize;
	
	
	return true;
}



</script>



<!--{include file="$template_name/header.tpl"}-->


<form action="add_content.php?dir=<!--{$dir}-->&<!--{$sid}-->" method="post" name="content">
<input type="hidden" name="id">
<input type="hidden" name="thumbs">
<input type="hidden" name="thumbsize"> 


<table border=1 width="100%" name="outer_table">
	<tr>
		<td valign="top" width="50%">
						
<a href="add_content.php?dir=<!--{$dir}-->&checkall=1<!--{$sid}-->" onclick="setCheckboxes('content', true,'content_to_add[]'); return false;">
            <!--{$lang.check_all}--></a> /
<a href="add_content.php?dir=<!--{$dir}--><!--{$sid}-->" onclick="setCheckboxes('content', false,'content_to_add[]'); return false;">
            <!--{$lang.uncheck_all}--></a>
						Thumbs
		<a onclick="thumbsOn()">On</a>
		<a onclick="thumbsOff()">Off</a>
		<a ondblclick="picsize+=10	;thumbsOn()" onclick="picsize+=10	;thumbsOn()">larger</a>
		<a ondblclick="picsize-=10	;thumbsOn()" onclick="picsize-=10	;thumbsOn()">smaler</a>

			<table border=1 width="100%">   
				<!--{section name=id loop=$files}-->
				
				<script type="text/javascript" language="javascript">
					pics[<!--{$smarty.section.id.index}-->]='<!--{$files[id].url}-->'
				</script>
				
				<tr>
					<td width=10>
						<input name="content_to_add[]" type="checkbox" value="<!--{$files[id].url}-->"><br>
					</td>
					<td>
						<a href=<!--{$files[id].url}--> target="_blank" onmouseover="setPreview('<!--{$files[id].url}-->')" onmouseout="document.images['preview'].width='150' "><!--{$files[id].name}--></a>
					</td>
					<td>
						<!--{$files[id].size[0]}--> x <!--{$files[id].size[1]}-->
					</td>
					<td>
						<!--{$files[id].filesize}--> KB
						
					</td>
					<td>
						<!--{if !($smarty.section.id.first)}-->
							<input type="submit" name="moveup" value="up" onclick="move(<!--{$smarty.section.id.index}-->)">

						<!--{/if}-->
						</td>
					
					<td>
						<!--{if !($smarty.section.id.last)}-->
							<input type="submit" name="movedown" value="down" onclick="move(<!--{$smarty.section.id.index}-->)">

						<!--{/if}-->
					</td>
					<td>
					<img src="" name="<!--{$files[id].url}-->" height=0 onclick="setPreview('<!--{$files[id].url}-->')">
					</td>


					
				</tr>
				<!--{/section}-->
				</table>
				
				
				
			
		</td>

		<td id="preview_td" width="50%" valign="top">
			<img src=''   name="preview" width="100%">
		</td>

	</tr>
</table>


    
<br>

<!--{$lang.contentgroup}-->
<select name="new_content_group">
	<!--{section name=id loop=$add_to_contentgroups}-->
		<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
	<!--{/section}-->
</select>
<br>

<!--{$lang.to_cat}-->
<select name="new_content_cat">
	<!--{section name=id loop=$add_to_cats}-->
		<option value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
	<!--{/section}-->
</select>
<br>


<input name="add_content" type="submit" value="<!--{$lang.add_selected}-->">
</form>


<!--{$thumbs}-->
<!--{if ($thumbs=='true')}-->
<script type="text/javascript" language="javascript">
thumbsOn();
</script>
<!--{/if}-->


<!--{include file="$template_name/footer.tpl"}-->
