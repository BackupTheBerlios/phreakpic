<html>
<!--{config_load file="standard.cfg"}-->
<head>
<title><!--{$title|default:""}--></title>
</head>

<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->">
<div align="center">
	<table width="95%" border="0" cellspacing="0" cellpadding="5">
		<tr> 
			<td height="45">
				<!--{if $is_prev_content eq "true"}-->
					<a 
href=view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$prev_thumb.content_id}-->>
						<!--{$prev_thumb.html}-->
					</a><br>
					<!--{$lang.nav_back}-->
				<!--{else}-->
					&nbsp;
				<!--{/if}-->
			</td>
			<td height="45">fehlt: Sitenavigation</td>
			<td height="45"> 
				<div align="right">
					<!--{if $is_next_content eq "true"}-->
						<a 
href=view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$next_thumb.content_id}-->>
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
				<a href="index.php"><!--{$lang.home}--></a> --
				<!--{section name=id loop=$nav_string}-->
					<a href="view_cat.php?cat_id=<!--{$nav_string[id].id}-->">
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
					<a href=view_cat.php?cat_id=<!--{$cat_id}-->>
						<!--{$html}-->
					</a>
				</div>
			</td>
			<td>
			
			
			
				<!--{if $mode == edit}-->
					<form 
action="view_content.php?&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}-->" method="POST">
					
					<!--{if $allow_edit eq 1}-->
						<!--{$lang.name}-->: <input type="text" name="name" 
value="<!--{$name}-->"><br>
						<!--{$lang.place_in_cat}-->: <input type="text" name="place_in_cat" 
value="<!--{$place_in_cat}-->"><br> 
						<!--{$lang.lock}-->: <input type="checkbox" name="lock" 
<!--{$locked}-->><br>
					
					<!--{/if}-->
					
					<!--{if $allow_content_remove eq 1}-->
						<!--{$lang.delete}-->: <input type="checkbox" name="delete"><br>
						
						
							
					<!--{/if}-->
					<!--{if $allow_link eq 1}-->
						<!--{$lang.link}-->: <input type="checkbox" name="link" ><br>
						<!--{if $allow_content_remove eq 1}-->
							<!--{$lang.move}-->: <input type="checkbox" name="move" ><br>
						<!--{/if}-->
					<!--{/if}-->
					<!--{$lang.to_cat}-->: 
					<select name="to_cat">
					<!--{section name=id loop=$add_to_cats}-->
						<option 
value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
					<!--{/section}-->
					</select><br>
					
					<input type="hidden" name="mode" value="commit">
					<input type="submit">  
					</form>
				<!--{else}-->	
					<!--{$lang.name}-->: <!--{$name}--><br>
					<!--{$lang.rating}-->: <!--{$current_rating}--><br>
					<!--{$lang.views}-->: <!--{$views}--><br>
					<a 
href="comment.php?mode=add&type=content&parent_id=0&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}-->"><!--{$lang.add_comment}--></a><br>
					<!--{if ($allow_edit eq 1) or ($allow_content_remove eq 1)}-->
						<a 
href="view_content.php?mode=edit&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}-->"><!--{$lang.edit_content}--></a><br>
					<!--{/if}-->	
				<!--{/if}-->	
				
			</td>
		</tr>
		<tr>
			<!--<td>&nbsp;</td>-->
			<td colspan="3">
				<table width="100%" align="left">
					<tr>
						<td width="20%">
							&nbsp;
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
</body>
</html>
