<!--{include file="$template_name/admin/header.tpl"}-->
<a href="../?<!--{$sid}-->" target="_parent">Start</a><br>
<br>
<a href="config.php?<!--{$sid}-->" target="site"><!--{$lang.config}--></a><br>
<a href="../add_content.php?<!--{$sid}-->" target="site"><!--{$lang.add_content}--></a><br>
<br>
<a href="groups.php?<!--{$sid}-->&type=usergroup" target="site"><!--{$lang.usergroups}--></a><br>
<a href="user_in_groups.php?<!--{$sid}-->" target="site"><!--{$lang.users_in_group}--></a><br>
<br>
<a href="groups.php?<!--{$sid}-->&type=group" target="site"><!--{$lang.groups}--></a><br>
<br>
<a href="auths.php?<!--{$sid}-->&type=content" target="site"><!--{$lang.content_auth}--></a><br>
<a href="auths.php?<!--{$sid}-->&type=cat" target="site"><!--{$lang.cat_auth}--></a><br>

<a href="auths.php?<!--{$sid}-->&type=usergroup" target="site"><!--{$lang.usergroup_auth}--></a><br>
<br>
<a href="groups.php?<!--{$sid}-->&type=content_meta_field" target="site"><!--{$lang.meta_fields}--></a><br>
<br>
<a href="sync.php?<!--{$sid}-->" target="site"><!--{$lang.sync}--></a><br>
<br>
<!--{include file="$template_name/admin/footer.tpl"}-->
