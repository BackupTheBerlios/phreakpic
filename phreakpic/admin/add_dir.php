<?php
define(ROOT_PATH,'../');
require_once(ROOT_PATH . 'includes/common.inc.php');
include_once(ROOT_PATH . './languages/'.$userdata['user_lang'].'/lang_main.php');
require_once(ROOT_PATH . 'includes/template.inc.php');
include_once (ROOT_PATH . 'modules/pic_managment/interface.inc.php');

// get catgroups where add_to_group is allowed
$add_to_contentgroups = get_contentgroups_data_where_perm('id,name','add_to_group');
$smarty->assign('add_to_contentgroups',$add_to_contentgroups);

$add_to_catgroups = get_catgroups_data_where_perm('id,name','add_to_group');
$smarty->assign('add_to_catgroups',$add_to_catgroups);



$smarty->display($userdata['photo_user_template'].'/admin/add_dir.tpl');

?>