<?php
require_once('./includes/common.inc.php');
require_once('./classes/album_content.inc.php');
require_once('./includes/template.inc.php');

$picture = new picture();
$bla = $picture->generate_from_id('4');

$file = $picture->get_file();

$smarty->assign('file',$file);
$smarty->display($userdata['photo_user_template'].'/test_view_pic.php.tpl');
//echo ($userdata['photo_user_template'] . "<br> $PHP_SELF");
?>