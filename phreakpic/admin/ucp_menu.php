<?php
define ("ROOT_PATH",'../');
include_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . 'classes/album_content.inc.php');
include_once(ROOT_PATH . 'includes/template.inc.php');


$smarty->display($userdata['photo_user_template'].'/admin/ucp_menu.tpl');
?>
