<!--{include file="$template_name/admin/header.tpl"}-->
<form action="sync.php?<!--{$sid}-->" method="post">
	<!--{section name=missmatch loop=$missmatch_array}-->
		<input type="hidden" name="id[<!--{$smarty.section.missmatch.index}-->]" value="<!--{$missmatch_array[missmatch].id}-->">
		<input type="hidden" name="type[<!--{$smarty.section.missmatch.index}-->]" value="<!--{$missmatch_array[missmatch].type}-->">
		
		<!--{if $missmatch_array[missmatch].type == 1}-->	
			<input type="checkbox" name="correct[<!--{$smarty.section.missmatch.index}-->]">
			Content amount error in <!--{$missmatch_array[missmatch].name}--> is <!--{$missmatch_array[missmatch].value}--> shoud be <!--{$missmatch_array[missmatch].should_be}--><br>
		<!--{/if}-->
		<!--{if $missmatch_array[missmatch].type == 2}-->	
			<input type="checkbox" name="correct[<!--{$smarty.section.missmatch.index}-->]">
			Child Content amount error in <!--{$missmatch_array[missmatch].name}--> is <!--{$missmatch_array[missmatch].value}--> shoud be <!--{$missmatch_array[missmatch].should_be}--><br>
		<!--{/if}-->
		<!--{if $missmatch_array[missmatch].type == 3}-->	
			<input type="checkbox" name="correct[<!--{$smarty.section.missmatch.index}-->]">
			Child Comment amount error in <!--{$missmatch_array[missmatch].name}--> is <!--{$missmatch_array[missmatch].value}--> shoud be <!--{$missmatch_array[missmatch].should_be}--><br>
		<!--{/if}-->
		<!--{if $missmatch_array[missmatch].type == 4}-->	
			<input type="checkbox" name="correct[<!--{$smarty.section.missmatch.index}-->]">
			Content Comment amount error in <!--{$missmatch_array[missmatch].name}--> is <!--{$missmatch_array[missmatch].value}--> shoud be <!--{$missmatch_array[missmatch].should_be}--><br>
		<!--{/if}-->


	<!--{/section}-->
	<input type="submit" name="do_correct">
</form>
<!--{include file="$template_name/admin/footer.tpl"}-->
