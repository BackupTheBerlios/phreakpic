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
				Bewertung: <!--{$thumbs[thumb_cols][thumb_cell].current_rating}--><br>
				Views: <!--{$thumbs[thumb_cols][thumb_cell].views}--><br>
			</td>
			<!--{/section}-->
		</tr>
		<!--{/section}-->
	</table>
<!--{else}-->
In dieser Kategorie gibts noch keine Bilder...
<!--{/if}-->
</body>
</html>