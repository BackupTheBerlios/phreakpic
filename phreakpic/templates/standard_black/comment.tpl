<!--{include file="$template_name/header.tpl"}-->
<form action="view_<!--{$type}-->.php?cat_id=<!--{$cat_id}--><!--{$oontent_id_string}--><!--{$sid}-->" method="POST">
	<table border="0" align="center">
	
	<!--{if ($user_id == -1) or (($mode == 'edit_comment') and ($user_level=='admin'))}-->
		<tr>
			<td>
				<!--{$lang.name}-->
			</td>
			<td>
				<input type="text" name="poster_name" value="<!--{$poster_name}-->">
			</td>
		</tr>
	<!--{/if}-->
	
	
	<!--{if ($mode == 'edit_comment') and ($user_level=='admin')}-->
		<tr>
			<td>
				<!--{$lang.user_id}-->
			</td>
			<td>
				<select name="user_id">
					<!--{section name=user loop=$users_data}-->
						<!--{if $users_data[user].user_id == $user_id}-->
							<option selected value="<!--{$users_data[user].user_id}-->"><!--{$users_data[user].username}--></option>
						<!--{else}-->
							<option value="<!--{$users_data[user].user_id}-->"><!--{$users_data[user].username}--></option>
						<!--{/if}-->
					<!--{/section}-->
				</select>
				
			</td>
		</tr>
	
	<!--{/if}-->

		<tr>
			<td>
				<!--{$lang.topic}-->
			</td>
			<td>
				<input type="text" name="topic" value="<!--{$topic}-->">
			</td>
		</tr>
		<tr>
			<td>
				<!--{$lang.text}-->
			</td>
			<td>
				<textarea name="comment_text" cols="70" rows="10"><!--{$text}--></textarea>
				<br>
				<input type="submit" value="<!--{$lang.send}-->">
				<input type="hidden" name="mode" value="<!--{$mode}-->">
				<input type="hidden" name="parent_id" value="<!--{$parent_id}-->">
			</form>
		</td>
	</tr>
</table>

<p>&nbsp;</p>

<table border="0" align="center">
	<tr>
		<td>
			<!--{include file="$template_name/show_comments.tpl" hide_controlles=true}-->
		</td>
	</tr>
</table>

<table border="0" align="center">
	<!--{if $type == 'content'}-->
		<tr>
			<td>
				<!--{$oontent_html}-->>
			</td>
		</tr>
	<!--{/if}-->
</table>
<!--{include file="$template_name/footer.tpl"}-->
