<?php
require_once('./includes/common.inc.php');
require_once('./classes/album_content.inc.php');
require_once('./includes/template.inc.php');

$contents = get_content_of_cat($id);

for ($i = 0; $i < size_of($contents); $i++)
{
	$content_html[] = $contents[$i]->generate_html();
}

$smarty->assign('content',$content_html);
$smarty->display($userdata['photo_user_template'].'/'.__FILE__.'.tpl');
echo (__FILE__);
?>