<!--{config_load file="$template_name/config.cfg"}-->


<!--{if $mode == 'edit_comments'}-->
	to:<br>
	<input type="radio" name="comment_to" value="0">
	<form action="<!--{$current_page}--><!--{$sid}-->" method="post" name="edit_comments">
<!--{/if}-->



<!--{section name=index loop=$comments}-->
<!--{if $comments[index].level == 0}-->
<table border="0" cellpadding="0" cellspacing="0" id="comment_thread_table">
	<tr>
		<td>
<!--{/if}-->
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td>
			<!--{if $mode == 'edit_comments'}-->
			to:<br>
				<input type="radio" name="comment_to" value="<!--{$comments[index].id}-->">
			<!--{/if}-->
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
						<br><!--{$comments[index].comment_has_been_changed}-->
					<!--{/if}-->
					<!--{if $comments[index].editable == true and $hide_controlles == false}-->
						<a href="comment.php?mode=edit_comment&type=<!--{$type}-->&id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">edit</a>
						<a href="javascript:confirmLink('view_<!--{$type}-->.php?mode=del_comment&comment_id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->','<!--{$lang.do_you_really}--> <!--{$lang.delete_comment}-->');">delete</a>
					<!--{/if}-->
					</span>
					<hr />
					<div class="comment_text"><!--{$comments[index].text}--></div> <br>
										
					<!--{if $hide_controlles == false}-->
						
						<a href="comment.php?mode=add&type=<!--{$type}-->&parent_id=<!--{$comments[index].id}-->&cat_id=<!--{$cat_id}-->&content_id=<!--{$content_id}--><!--{$sid}-->">Antworten</a>
						<!--{if $mode == 'edit_comments'}-->
						<br>
							<!--{$lang.move}--> <input type="checkbox" name="comment_move[]" value="<!--{$comments[index].id}-->" >
							
						<!--{/if}-->
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
<!--{if $mode == 'edit_comments'}-->
	<input type="submit" name="edit_comments">
	</form>
<!--{/if}-->


<!--{if $allow_comment_edit == true}-->
	<div align="center"><a href="<!--{$current_page}-->&mode=edit_comments<!--{$sid}-->">Edit Comments</a></div>
<!--{/if}-->
