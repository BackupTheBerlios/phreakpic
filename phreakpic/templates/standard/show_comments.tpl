<!--{section name=index loop=$comments}-->
<!--{if $comments[index].level == 0}-->
<table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<td>
<!--{/if}-->
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td>
			<!--{section name=level loop=$comments[index].level}-->
				&nbsp; &nbsp; &nbsp; &nbsp;
			<!--{/section}-->
			&nbsp;
		</td>
		<td <!--{if $comments[index].level == 0}-->bgcolor="#cccccc" width="100%"
			>
		<!--{else}-->
			>
			<hr>
		<!--{/if}-->
			<!--{if $comments[index].new == true}-->
				new
			<!--{/if}-->
			
			<b><!--{$comments[index].topic}--></b>
			<!--{$comments[index].username}--> <!--{$comments[index].poster_name}--> <!--{$lang.wrote_at}--> <!--{$comments[index].creation_date}-->&nbsp;
			<!--{if $comments[index].last_changed_date > 0}-->
				Beitrag wurde <!--{$comments[index].changed_count}--> mal ge�ndert, zuletzt am <!--{$comments[index].last_changed_date}-->
			<!--{/if}-->
			<!--{if $comments[index].editable == true}-->
				<a href="comment.php?mode=edit_comment&type=<!--{$type}-->&id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">edit</a>
				<a href="view_<!--{$type}-->.php?mode=del_comment&comment_id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">delete</a>
			<!--{/if}-->
			
		</td>
	</tr>
		<td>&nbsp;</td>
		<td><!--{$comments[index].text}--> <br> <a href="comment.php?mode=add&type=<!--{$type}-->&parent_id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">Antworten</a></td>
</table>
<!--{if $comments[index.index_next].level == 0}-->
		</td>
	</tr>
</table>
<br>
<!--{/if}-->
<!--{/section}-->
