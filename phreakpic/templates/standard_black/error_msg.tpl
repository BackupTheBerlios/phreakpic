<!--{include file="$template_name/header.tpl"}-->
<table align="center" border=1>
	<tr>
		<th class="thHead" height="25"><b><!--{$error_info.type}--></b></th>
	</tr>
	<tr>
		<td class="row1"><table width="100%" cellspacing="0" cellpadding="1" border="0">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center"><!--{$error_info.text}--><br /><br />
				<!--{if $error_info.debug}-->
					<b><u>DEBUG MODE</u></b><br /><br />
					<!--{if $error_info.type == 'SQL_ERROR'}-->
						<br /><br />SQL Error : <!--{$error_info.sql_error.code}--> <!--{$error_info.sql_error.message}--> <br>
					<!--{/if}-->
					
					<!--{$error_info.sql}--></br /><br />
					Line : <!--{$error_info.line}--><br />File : <!--{$error_info.file}-->
					<!--{/if}-->
				</td>
				
			</tr>
			<tr>
				<!--{if $error_info.type != 'INFORMATION'}-->
				<td align="center"><br>The error has been reported to the admin, to help you can write what you where trying to do<br>
				<form action="error_send.php?error_id=<!--{$error_info.id}--><!--{$sid}-->" method="POST">
					<textarea cols="70" rows="10" name="comment"></textarea><br>
					<input type="submit" name="send">
				
				
				</form>
				</td>
				<!--{/if}-->
			</tr>
		</table></td>
	</tr>


</table>

<!--{include file="$template_name/footer.tpl"}-->
