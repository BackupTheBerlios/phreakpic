<!--{config_load file="$template_name/config.cfg"}-->
<!--{section name=index loop=$comments}-->
<!--{if $comments[index].level == 0}-->
<table border="0" cellpadding="0" cellspacing="0" id="comment_thread_table">
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
		<td <!--{if $comments[index].level == 0}--> bgcolor="<!--{#table_head_bg_color#}-->" width="100%">
		<!--{else}-->
			>
			<!--<hr class="comment_hr">-->
		<!--{/if}-->
		
		<table border="0">
			<tr>
				<td valign="top">
					<b><!--{$comments[index].username}--><!--{$comments[index].poster_name}--></b>
					<!--{if $comments[index].new == true}-->
						<span class="gentiny"><!--{$lang.new_post}--></span>
					<!--{/if}-->	
					<br>
					<!--{$comments[index].avatar}-->
				</td>
				<td>
					<span class="gensmall">
					<!--{$lang.wrote_at}--> <!--{$comments[index].creation_date}-->
					&nbsp&nbsp&nbsp&nbsp <!--{$lang.topic}-->: <b><!--{$comments[index].topic}--></b> 
					<!--{if $comments[index].changed_count > 0}-->
						<br>Beitrag wurde <!--{$comments[index].changed_count}--> mal geändert, zuletzt am <!--{$comments[index].last_changed_date}-->
					<!--{/if}-->
					<!--{if $comments[index].editable == true and $hide_controlles == false}-->
						<a href="comment.php?mode=edit_comment&type=<!--{$type}-->&id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">edit</a>
						<a href="view_<!--{$type}-->.php?mode=del_comment&comment_id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">delete</a>
					<!--{/if}-->
					</span>
					<hr />
					<div class="comment_text"><!--{$comments[index].text}--></div> <br>
					<!--{if $hide_controlles == false}-->
						<a href="comment.php?mode=add&type=<!--{$type}-->&parent_id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">Antworten</a>
					<!--{/if}-->
				</td>
			</tr>
		</table>	
</table>
<!--{if $comments[index.index_next].level == 0}-->
		</td>
	</tr>
</table>
<br>
<!--{/if}-->
<!--{/section}-->
