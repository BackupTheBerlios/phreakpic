<!--{config_load file="$template_name/config.cfg"}-->
<!--{include file="$template_name/header.tpl"}-->

<!--{include file="$template_name/nav_bar.tpl"}-->



<form action="profile.php?<!--{$sid}-->" method="POST">
<table border="1">
	<tr><td>User Settings</td></tr>
	
	<tr>
	
	<td>
	Basket Enable
	</td>
	<td>
	<input type="checkbox" name="user_basket_enable" <!--{$basket_enable}-->>
	</td>


</table>

<input type="submit" name="submit">
</form>



<!--{include file="$template_name/admin/footer.tpl"}-->
