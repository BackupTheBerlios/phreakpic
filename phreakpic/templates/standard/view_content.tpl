<html><!--{config_load file="standard.cfg"}--><head><title><!--{$title|default:""}--></title></head><body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->"><div align="center">	<table width="95%" border="0" cellspacing="0" cellpadding="5">		<tr> 			<td height="45">				<!--{if $is_prev_content eq "true"}-->					<a href=view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$prev_thumb.content_id}-->>						<!--{$prev_thumb.html}-->					</a><br>					Zur&uuml;ck 				<!--{else}-->					&nbsp;				<!--{/if}-->			</td>			<td height="45">fehlt: Sitenavigation</td>			<td height="45"> 				<div align="right">					<!--{if $is_next_content eq "true"}-->						<a href=view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$next_thumb.content_id}-->>							<!--{$next_thumb.html}-->						</a><br>						Weiter 					<!--{else}-->						&nbsp;					<!--{/if}-->				</div>			</td>		</tr>		<tr> 			<td>&nbsp;</td>			<td>&nbsp;</td>			<td>&nbsp;</td>		</tr>		<tr> 			<td colspan="2">				<a href="index.php"><!--{$lang.home}--></a> --				<!--{section name=id loop=$nav_string}-->					<a href="view_cat.php?cat_id=<!--{$nav_string[id].id}-->">						<!--{$nav_string[id].name}--></a> --				<!--{/section}-->				<!--{$name}-->			</td>			<td>&nbsp;</td>		</tr>		<tr>			<td>&nbsp;</td>			<td>&nbsp;</td>			<td>&nbsp;</td>		</tr>		<tr>			<td>fehlt: Poll</td>			<td> 				<div align="center">					<a href=view_cat.php?cat_id=<!--{$cat_id}-->>						<!--{$html}-->					</a>				</div>			</td>			<td>				Name: <!--{$name}--><br>				Bewertung: <!--{$current_rating}--><br>				Views: <!--{$views}--><br>				<a href="comment.php?mode=add_comment&content_id=<!--{$content_id}-->"><!--{$lang.add_comment}--><a><br>			</td>		</tr>		<tr>			<!--<td>&nbsp;</td>-->			<td colspan="3">				<table width="100%" align="left">					<tr>						<td width="20%">							&nbsp;						</td>						<td>							<!--{section name=index loop=$comments}-->								<!--{section name=level loop=$comments[index].level}-->									&nbsp; &nbsp; &nbsp; &nbsp;								<!--{/section}-->								<!--{if $comments[index].level == 0}-->									<div align="left">										---------------------------------<br>									</div>								<!--{/if}-->								<!--{$comments[index].text}--><br>							<!--{/section}-->						</td>					</tr>				</table>			</td>		</tr>	</table></div></body></html>