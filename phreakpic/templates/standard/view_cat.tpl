<html>
<!--{config_load file="standard.cfg"}-->
<head>
<title><!--{$title|default:"Titel und so"}--></title>
</head>
<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->">
<a href="index.php"><!--{$lang.home}--></a> --
<!--{section name=id loop=$nav_string}-->
	<!--{if $smarty.section.id.last}-->
		<!--{$nav_string[id].name}-->
	<!--{else}-->
		<a href="view_cat.php?cat_id=<!--{$nav_string[id].id}-->">
		<!--{$nav_string[id].name}--></a> --
	<!--{/if}-->
<!--{/section}-->

<!--{if $number_of_child_cats > 0}-->
	<table width="60%" border="0" cellpadding="5" align="center">
		<tr>
			<td>Name</td>
			<td>Description</td>
			<td>Anzahl</td>
			<td>Bewertung</td>
		</tr>
		<!--{section name=id loop=$number_of_child_cats}-->
			<tr>
				<td><a href="view_cat.php?cat_id=<!--{$child_cat_infos[id].id}-->"><!--{$child_cat_infos[id].name}--></a></td>
				<td><!--{$child_cat_infos[id].description}--></td>
				<td><!--{$child_cat_infos[id].content_amount}--></td>
				<td><!--{$child_cat_infos[id].current_rating}--></td>
			</tr>
		<!--{/section}-->
	</table>
<!--{else}-->
	<p>keine unterkategorien vorhanden</p>
<!--{/if}-->


<!--{if $is_content == true}-->
<!--{if $mode == edit}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}-->" method="post" name="edit_content" id="edit_content">
<!--{/if}-->
	<table border=0 align=center>
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
				<a href=view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->><!--{$thumbs[thumb_cols][thumb_cell].html}--></a><br>
				name: <!--{$thumbs[thumb_cols][thumb_cell].name}--><br>
				Bewertung: <!--{$thumbs[thumb_cols][thumb_cell].current_rating}--><br>
				Views: <!--{$thumbs[thumb_cols][thumb_cell].views}--><br>
				<!--{if $mode == edit}-->
					<!--{if $thumbs[thumb_cols][thumb_cell].allow_edit == true}-->
						Name: <input name="name[]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].name}-->" size="30"><br>
						lock:<input name="lock[<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->]" type="checkbox" value="true">
						delete:<input name="delete[<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->]" type="checkbox" value="true"><br>
						<!--move to cat:
						<select name="move_to_cat">
							 <option value="wert">bezeichnung</option>
						</select><br>-->
						<input name="place_in_array[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
						<input name="content_id[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->">
						
					<!--{else}-->
						keine edit rechte<br>
					<!--{/if}-->
				<!--{/if}-->
			</td>
			<!--{/section}-->
		</tr>
		<!--{/section}-->
	</table>
	<div align="center">
	<!--{if $mode != edit}-->
		<a href="view_cat.php?mode=edit&cat_id=<!--{$cat_id}-->">editieren</a>
		<!--{if $edited == true}-->
			<!--{$lang.cat_edited}-->
		<!--{/if}-->
	<!--{else}-->
		<input name="mode" type="hidden" value="edited">
		<input name="submit" type="submit" id="submit" value="Abschicken">
		</form>
	<!--{/if}-->
	</div>
<!--{else}-->
	In dieser Kategorie gibts noch keine Bilder...
<!--{/if}-->
</body>
</html>