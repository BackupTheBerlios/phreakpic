<!--{include file="$template_name/header.tpl"}-->
<form action="view_<!--{$type}-->.php?cat_id=<!--{$cat_id}--><!--{$oontent_id_string}--><!--{$sid}-->" method="POST">
<table border=1 align="center">
	
		<!--{if $user_id == -1}-->
			<tr><td>
			<!--{$lang.name}--> <input type="text" name="poster_name" value="">
			</td></tr>
		<!--{/if}-->
	
	<tr><td>
		<!--{$lang.topic}--> <input type="text" name="topic" value="<!--{$topic}-->">
	</td></tr>
	<tr><td>
		<!--{$lang.text}-->
	</td></tr>
	<tr><td>
		<textarea name="comment_text" cols="70" rows="10"><!--{$text}--></textarea>
	
	<tr><td>


<input type="submit" value="<!--{$lang.send}-->">   
</td></tr>
<tr><td>
<!--{include file="$template_name/show_comments.tpl" hide_controlles=true}-->
</td></tr>
<!--{if $type == 'content'}-->
<tr><td>
	<!--{$oontent_html}-->>
	</td></tr>
<!--{/if}-->


</table>
<input type="hidden" name="mode" value="<!--{$mode}-->">
<input type="hidden" name="parent_id" value="<!--{$parent_id}-->">


</form>
<!--{include file="$template_name/footer.tpl"}-->
