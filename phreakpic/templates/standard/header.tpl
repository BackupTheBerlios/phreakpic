<html>
<!--{config_load file="$template_name/config.cfg"}-->
<head>
<title><!--{$title|default:"KEIN TITLE ANGEGEBEN!"}--></title>
</head>
<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->">
	<table border="0" cellpadding="10" cellspacing="0" bordercolor="#999999" width="100%" bgcolor="<!--{#head_table_bg_color#}-->" align="center">
		<tr>
			<td>
				<table border="1" align="right" cellspacing="0" cellpadding="10">
					<tr>
						<td>
							::
							<a href="<!--{$phpbb_path}-->index.php?<!--{$sid}-->"><!--{$lang.forum}--></a> ::
							<a href="<!--{$server_name}-->?<!--{$sid}-->"><!--{$lang.website_home}--></a> ::
							<!--{if $username != 'Anonymous'}-->
								<a href="<!--{$phpbb_path}-->login.php?logout=true<!--{$sid}-->"><!--{$lang.logout}--> [<!--{$username}-->]</a>
							<!--{else}-->
								<a href="<!--{$phpbb_path}-->login.php?redirect=<!--{$redirect}--><!--{$sid}-->"><!--{$lang.login}--></a>
							<!--{/if}-->
							::
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
