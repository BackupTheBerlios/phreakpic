<?php
require_once('./includes/common.inc.php');
require_once('./classes/album_content.inc.php');
require_once('./includes/template.inc.php');
$picture = new picture();
$result = $picture->generate_from_id('4');
if ($result != OP_SUCCESSFUL)
{
	message_die(GENERAL_ERROR, "Could not generate content from id", '', __LINE__, __FILE__, $sql);
}


$smarty->assign('file',$picture->get_file());
$smarty->assign('content_size',$content->get_content_size()); //thats the height and width of the object...
$smaery->assing('id',$id);
$smarty->display($userdata['photo_user_template'].'/test_view_pic.php.tpl');

?>