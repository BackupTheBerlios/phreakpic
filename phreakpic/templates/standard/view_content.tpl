<!--{include file="$template_name/header.tpl"}-->
<script src="templates/<!--{$template_name}-->/functions.js" type="text/javascript" language="javascript">
</script>
<script type="text/javascript" language="javascript">
	document.onkeypress = getkey_content;
	var keyactive = true;
</script>


<div align="center">
	<table width="95%" border="0" cellspacing="0" cellpadding="5">
		<tr> 
			<td height="45">
				<!--{if $is_prev_content eq "true"}-->
					<a name="next_link" href="view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$prev_thumb.content_id}--><!--{$sid}-->">
						<!--{$prev_thumb.html}-->
					</a><br>
					<!--{$lang.nav_back}-->
				<!--{else}-->
					&nbsp;
				<!--{/if}-->
			</td>
			
			<td height="45">&nbsp;</td>
			<td height="45"> 
				<div align="right">
					<!--{if $is_next_content eq "true"}-->
						<a name="prev_link" href="view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$next_thumb.content_id}--><!--{$sid}-->">
							<!--{$next_thumb.html}-->
						</a><br>
						<!--{$lang.nav_next}-->
					<!--{else}-->
						&nbsp;
					<!--{/if}-->
				</div>
			</td>
		</tr>
		<tr> 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr> 
			<td colspan="2">
				<a href="index.php?<!--{$sid}-->"><!--{$lang.home}--></a> --
				<!--{section name=id loop=$nav_string}-->
					<a href="view_cat.php?cat_id=<!--{$nav_string[id].id}--><!--{$sid}-->">
						<!--{$nav_string[id].name}--></a> --
				<!--{/section}-->
				<!--{$name}-->
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>fehlt: Poll</td>
			<td> 
				<div align="center">
				<a name="#pic"></a>
						<table width="1" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="1"><img src="templates/<!--{$template_name}-->/bildumrandung/elo.gif" width="14" height="14" /></td>
								<td width="1"><img src="templates/<!--{$template_name}-->/bildumrandung/lor.gif" width="10" height="14" /></td>
								<td width="<!--{$content_width}-->" background="templates/<!--{$template_name}-->/bildumrandung/bo.gif"></td>
								<td width="1"><img src="templates/<!--{$template_name}-->/bildumrandung/rol.gif" width="10" height="14" /></td>
								<td width="1"><img src="templates/<!--{$template_name}-->/bildumrandung/ero.gif" width="14" height="14" /></td>
							</tr>
							<tr>
								<td>
									<table width="9" height="<!--{$content_height}-->" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/lou.gif" width="14" height="10" /></td>
										</tr>
										<tr>
											<td background="templates/<!--{$template_name}-->/bildumrandung/bl.gif">&nbsp;</td>
										</tr>
										<tr>
											<td height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/luo.gif" width="14" height="10" /></td>
										</tr>
									</table>
								</td>
								<td colspan="3"><a name="thumbs_link" href="<!--{$thumb_link}--><!--{$sid}-->#<!--{$content_id}-->" class="content"><!--{$html}-->  border="0"></a></td>
								<td>
									<table width="9" height="<!--{$content_height}-->" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/rou.gif" width="14" height="10" /></td>
										</tr>
										<tr>
											<td background="templates/<!--{$template_name}-->/bildumrandung/br.gif">&nbsp;</td>
										</tr>
										<tr>
											<td height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/ruo.gif" width="14" height="10" /></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="1" height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/elu.gif" width="14" height="14" /></td>
								<td width="1" height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/lur.gif" width="10" height="14" /></td>
								<td width="<!--{$content_width}-->" height="1" background="templates/<!--{$template_name}-->/bildumrandung/bu.gif"></td>
								<td width="1" height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/rul.gif" width="10" height="14" /></td>
								<td width="1" height="1"><img src="templates/<!--{$template_name}-->/bildumrandung/eru.gif" width="14" height="14" /></td>
							</tr>
						</table>
				</div>
			</td>
			<td>
			
			
			
				<!--{if $mode == edit}-->
					<form 
action="view_content.php?&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->" method="POST">
					
					<!--{if $edit_info.allow_edit == true}-->
						<!--{$lang.name}-->: <input type="text" onfocus="keyoff()" onblur="keyon()" name="name" value="<!--{$name}-->"><br>
						<!--{$lang.place_in_cat}-->: <input type="text" onfocus="keyoff()" onblur="keyon()" name="place_in_cat" value="<!--{$edit_info.place_in_cat}-->"><br> 
						<!--{$lang.lock}-->: <input type="checkbox" name="lock" <!--{$edit_info.locked}-->><br>
						<!--{$lang.rotate}-->: 
						<!--{$lang.rotate_free}-->: <input type="radio" name="rotate_mode" value="free" checked><input type="text" onfocus="keyoff()" onblur="keyon()" name="rotate"><br> 
						<!--{$lang.rotate_left}--> <input type="radio" name="rotate_mode" value="-90">
						<!--{$lang.rotate_180}--> <input type="radio" name="rotate_mode" value="180">
						<!--{$lang.rotate_right}--> <input type="radio" name="rotate_mode" value="90"><br>
					
					<!--{/if}-->
					
					<!--{if $allow_content_remove == true}-->
						<!--{$lang.unlink}-->: <input type="checkbox" name="unlink"><br>
						
						
							
					<!--{/if}-->
					<!--{if $edit_info.allow_delete == true}-->
						<!--{$lang.delete}-->: <input type="checkbox" name="delete" ><br>
					<!--{/if}-->
					<!--{if $allow_link == true}-->
						<!--{$lang.link}-->: <input type="checkbox" name="link" ><br>
						<!--{if $allow_content_remove == true}-->
							<!--{$lang.move}-->: <input type="checkbox" name="move" ><br>
						<!--{/if}-->
					<!--{/if}-->
					<!--{$lang.to_cat}-->: 
					<select name="to_cat">
					<!--{section name=id loop=$add_to_cats}-->
						<option value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
					<!--{/section}-->
					</select><br>
					<!--{if $edit_info.allow_remove_from_group == true}-->
					<!--{$lang.change_group}--><input type="checkbox" name="change_group" > to 
					<select name="to_contentgroup">
					<!--{section name=id loop=$add_to_contentgroups}-->
						<!--{if $contentgroup == $add_to_contentgroups[id].id}-->
						<option selected value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
						<!--{else}-->
						<option value="<!--{$add_to_contentgroups[id].id}-->"><!--{$add_to_contentgroups[id].name}--></option>
						<!--{/if}-->
					<!--{/section}-->
					</select><br>
					<br>
					<!--{/if}-->
					
					
					<input type="hidden" name="mode" value="commit">
					<input type="submit">  
					</form>
				<!--{else}-->	
					<!--{$lang.name}-->: <!--{$name}--><br>
					<!--{$lang.rating}-->: <!--{$current_rating}--><br>
					<!--{$lang.views}-->: <!--{$views}--><br>
					<a href="comment.php?mode=add&type=content&parent_id=0&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->"><!--{$lang.add_comment}--></a><br>
					<!--{if ($edit_info.allow_edit == true) or (allow_content_remove == true)}-->
						<a href="view_content.php?mode=edit&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->"><!--{$lang.edit_content}--></a><br>
					<!--{/if}-->	
				<!--{/if}-->	
				
			</td>
		</tr>
		<tr>
			<!--<td>&nbsp;</td>-->
			<td colspan="3">
				<table width="100%" align="left" border="0">
					<tr>
						<td width="20%">&nbsp;
							
						</td>
						<td>
							<!--{include file="$template_name/show_comments.tpl" type="content"}-->
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<!--{include file="$template_name/footer.tpl"}-->
