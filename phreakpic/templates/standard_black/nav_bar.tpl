<table nowrap="nowrap" style="white-space:nowrap" width="40%" bgcolor="<!--{#navbar_bg_color#}-->" border="0" cellpadding="0" id="navbar_table">
	<tr>
		<td>
			<!--{$lang.navbar}--> <a name="nav_link" href="index.php?first_content=0<!--{$sid}-->" <!--{popup text="This link takes you to my page!" fgcolor="black"}-->><!--{$lang.home}--></a><!--{#navbar_seperator#}-->
			
			<!--{section name=id loop=$nav_string}-->
				<!--{if $smarty.section.id.last}-->
					<!--{$nav_string[id].name}-->
					<script type="text/javascript" language="javascript">
						<!--
						catback=<!--{$smarty.section.id.index}-->
						-->
					</script>
				<!--{else}-->
					<a name="nav_link" href="view_cat.php?cat_id=<!--{$nav_string[id].id}-->&first_content=0<!--{$sid}-->">
					<!--{$nav_string[id].name}--></a><!--{#navbar_seperator#}-->
				<!--{/if}-->
			<!--{/section}-->
			
		</td>
	</tr>
</table>
