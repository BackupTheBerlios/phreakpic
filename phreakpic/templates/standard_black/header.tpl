<html>
<!--{config_load file="$template_name/config.cfg"}-->
<head>
	<title><!--{$title_site}--> :: <!--{$title_page}--> - <!--{$title_name"}--></title>
	<link rel="stylesheet" href="templates/<!--{$template_name}-->/design.css" type="text/css" />
</head>
<body bgcolor="<!--{#body_bg_color#}-->" text="<!--{#text_color#}-->" link="<!--{#link_color#}-->" vlink="<!--{#vlink_color#}-->" alink="<!--{#alink_color#}-->" leftmargin="5" topmargin="5" marginwidth="5" marginheight="5">
	<table border="0" cellpadding="10" cellspacing="0" bordercolor="#999999" width="100%" bgcolor="<!--{#body_table_bg_color#}-->" align="center">
		<tr>
			<td>
				<table border="1" align="right" cellspacing="0" cellpadding="10">
					<tr>
						<td>
							::
							<a href="<!--{$phpbb_path}-->index.php?<!--{$sid}-->"><!--{$lang.forum}--></a> ::
							<a href="<!--{$server_name}-->?<!--{$sid}-->"><!--{$lang.website_home}--></a> ::
							<a href="profile.php?<!--{$sid}-->"><!--{$lang.profile}--></a> :: ::
							<!--{if $user_id != -1}-->
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
