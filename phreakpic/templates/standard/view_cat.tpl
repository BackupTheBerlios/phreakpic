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
	<table width="60%" border="1" cellpadding="5" align="center">
		<tr>
			<td><!--{$lang.name}--></td>
			<td>Description</td>
			<td>Anzahl</td>
			<td>Bewertung</td>
		</tr>
		<form action="view_cat.php?cat_id=<!--{$cat_id}-->" method="post" name="delete_cat">
		<!--{section name=id loop=$number_of_child_cats}-->
			<tr>
				<td><a href="view_cat.php?cat_id=<!--{$child_cat_infos[id].id}-->"><!--{$child_cat_infos[id].name}--></a></td>
				<td><!--{$child_cat_infos[id].description}--></td>
				<td><!--{$child_cat_infos[id].content_amount}--></td>
				<td><!--{$child_cat_infos[id].current_rating}--></td>
				<!--{if ($allow_cat_remove == 'true') and ($mode == 'edit')}-->	
					<td><input name="cat_delete" type="submit" id="<!--{$child_cat_infos[id].id}-->" value="<!--{$child_cat_infos[id].id}-->"></td>
				<!--{/if}-->
				
			</tr>
		<!--{/section}-->
		</form>
	</table>
<!--{else}-->
	<p>keine unterkategorien vorhanden</p>
<!--{/if}-->


<!--{$edit}-->

<!--{if  ($allow_cat_add == true) and ($mode == edit)}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}-->" method="post" name="edit_content" id="edit_content">
	Add New Cat:
	Name: <input name="cat_name" type="text" size="20">
	catgroup: <input name="cat_group" type="text" size="5">
	Is Serie: <input name="cat_is_serie" type="checkbox"><br>
	Describtion: <textarea name="cat_describtion" cols="70" rows="5"></textarea>
	<input name="newcat" type="submit" id="submit" value="Create">
	</from>
<!--{/if}-->




<!--{if $is_content == true}-->
	<!--{if $mode == edit}-->
		<form action="view_cat.php?cat_id=<!--{$cat_id}-->" method="post" name="edit_content" id="edit_content">
	<!--{/if}-->
	<table border=1 align=center>
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
					<input name="place_in_array[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
					<input name="content_id[]" type="hidden" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->">
					<!--{if $thumbs[thumb_cols][thumb_cell].allow_edit == true}-->
						Name: <input name="name[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].name}-->" size="20"><br>
						Place in Cat: <input name="place_in_cat[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="text" value="<!--{$thumbs[thumb_cols][thumb_cell].place_in_cat}-->" size="10"><br>
						lock:<input name="lock[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox" <!--{$thumbs[thumb_cols][thumb_cell].locked}-->>
						
					<!--{/if}-->
					<!--{if  $thumbs[thumb_cols][thumb_cell].allow_delete == true}-->
						real delete:<input name="delete[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
					<!--{/if}-->
					<!--{if  $allow_content_remove == true}-->
						remove from cat:<input name="unlink[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
					<!--{/if}-->
					<!--{if  $allow_link == true}-->
							link:<input name="link[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox">
							<!--{if  $allow_content_remove == true}-->
								move:<input name="move[<!--{$thumbs[thumb_cols][thumb_cell].place_in_array}-->]" type="checkbox"><br>
							<!--{/if}-->
					<!--{/if}-->
					
				<!--{/if}-->
			</td>
			<!--{/section}-->
		</tr>
		<!--{/section}-->
	</table>
	
<!--{else}-->
	In dieser Kategorie gibts noch keine Bilder...
<!--{/if}-->




<div align="center">
<!--{if $mode != edit}-->
	<a href="view_cat.php?mode=edit&cat_id=<!--{$cat_id}-->">editieren</a>
	<!--{if $edited == true}-->
		<!--{$lang.cat_edited}-->
	<!--{/if}-->
<!--{else}-->
	<input name="mode" type="hidden" value="edited">

	<!--{if  $allow_link == true}-->		
		to Cat 
		<select name="to_cat">
		<!--{section name=id loop=$add_to_cats}-->
			<option value="<!--{$add_to_cats[id].id}-->"><!--{$add_to_cats[id].name}--></option>
		<!--{/section}-->
		</select><br>
	<!--{/if}-->
	<input name="submit" type="submit" id="submit" value="Abschicken">
	</form>
<!--{/if}-->
<!--{if  ($allow_content_add == true) and ($mode == edit)}-->
	<form action="view_cat.php?cat_id=<!--{$cat_id}-->" method="post" name="add_content" id="add_content" enctype="multipart/form-data">
	Add Content:
	File: <INPUT  name="new_content_file" TYPE="file" SIZE="30">
	contentgroup: <input name="new_content_group" type="text" size="5">
	Name: <input name="new_content_name" type="text" size="20">
	Place In cat: <input name="new_content_place_in_cat" type="text" size="5">
	<input name="newcontent" type="submit" id="submit" value="Add Content">
	</from>
<!--{/if}-->

</div>

<table width="95%" align="center" border="1">
	<tr>
		<td width="20%">
			&nbsp;
		</td>
		<td>
			<!--{*if $comments != false*}-->
				<!--{include file="$template_name/show_comments.tpl" type="cat"}-->
			<!--{*else*}-->
				<a href="comment.php?mode=add&type=cat&parent_id=0&cat_id=<!--{$cat_id}-->"><!--{$lang.add_comment}--></a>
			<!--{*/if*}-->
		</td>
	</tr>
</table>
</body>
</html>
