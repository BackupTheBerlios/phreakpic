<!--{config_load file="$template_name/config.cfg"}-->
<html>
<head>

<script src="templates/<!--{$template_name}-->/functions.js" type="text/javascript" language="javascript">
</script>
<script type="text/javascript" language="javascript">
document.onkeypress = getkey_default;
var keyactive = true;
<!--{if $is_error}-->
error_window = window.open("error_report.php","aerror_window");
	//error_window.focus();

	//error_window.close();
<!--{/if}-->
</script>

<!--{$meta}-->


	<title><!--{$title_site}--> :: <!--{$title_page}--> - <!--{$title_name}--></title>
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
							<a href="<!--{$phpbb_path}-->index.php?<!--{$sid}-->" target="_parent"><!--{$lang.forum}--></a> ::
							<a href="<!--{$server_name}-->?<!--{$sid}-->" target="_parent"><!--{$lang.website_home}--></a> ::
							
							<a href="<!--{$root_path}-->view_custom_searches.php?<!--{$sid}-->" target="_parent"><!--{$lang.search}--></a> ::
							<!--{if $user_id != -1}-->
							<a href="<!--{$root_path}-->admin/ucp.php?<!--{$sid}-->" target="_parent"><!--{$lang.ucp}--></a> ::
								<a href="<!--{$phpbb_path}-->login.php?logout=true<!--{$sid}-->"><!--{$lang.logout}--> [<!--{$username}-->]</a>
							<!--{else}-->
								
								<a href="<!--{$phpbb_path}-->login.php?redirect=<!--{$redirect}--><!--{$sid}-->" target="_parent"><!--{$lang.login}--></a>
							<!--{/if}-->
							::
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
