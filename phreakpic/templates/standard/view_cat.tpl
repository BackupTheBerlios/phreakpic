<html>
<!--{config_load file="standard.cfg"}-->
<head>
<title><!--{$title|default:"Titel und so"}--></title>
</head>
<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->">
<!--{if $number_of_child_cats > 0}-->
<table width="90%" border="0" cellpadding="5">
	<tr>
		<td>Name</td>
		<td>Description</td>
		<td>Anzahl</td>
		<td>Bewertung</td>
	</tr>
	<!--{section name=id loop=$number_of_child_cats}-->
	<tr>
		<td><!--{$child_cat_infos[id].name}--></td>
		<td><!--{$child_cat_infos[id].description}--></td>
		<td><!--{$child_cat_infos[id].content_amount}--></td>
		<td><!--{$child_cat_infos[id].current_rating}--></td>
	</tr>
	<!--{sectionelse}-->
	keine unterkategorien vorhanden
	<!--{/section}-->
</table>
<!--{else}-->
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
					id				the id of the content
				*}-->
				<a href=view_content.php?cat_id=<!--{$cat_id}-->&content_id=<!--{$thumbs[thumb_cols][thumb_cell].content_id}-->><!--{$thumbs[thumb_cols][thumb_cell].html}--></a><br>
				name: <!--{$thumbs[thumb_cols][thumb_cell].name}--><br>
				Beschreibung: <!--{$thumbs[thumb_cols][thumb_cell].current_rating}--><br>
				Views: <!--{$thumbs[thumb_cols][thumb_cell].views}--><br>
			</td>
			<!--{/section}-->
		</tr>
		<!--{/section}-->
	</table>
	
</body>
</html>