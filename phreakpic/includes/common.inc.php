<?php
function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
    } 
$start_time = getmicrotime();
include_once(ROOT_PATH . 'config.inc.php'); //have to be before phpbb.inc.php because some vars are needed
include_once(ROOT_PATH . 'includes/phpbb.inc.php');

if (SQL_LAYER=="mysql")
{
	define("KEY_QUOTE",'`');
}
else
{
	define("KEY_QUOTE",'"');
}

if (!is_file('./languages/'.$userdata['user_lang'].'/lang_main.php'))
{
	$userdata['user_lang'] = $config_vars['default_lang'];
}

if ((!is_dir($userdata['phreakpic_user_template'])) or (!isset($userdata['photo_user_template'])))
{
	$userdata['photo_user_template'] = $config_vars['default_template'];
}

if (!isset($userdata['phreakpic_content_per_page']))
{
	$userdata['content_per_page'] = $config_vars['default_content_per_page'];
}




define("GENERATE_NAMES", 1); //for functions add_dir
define("BLANK_NAMES", 0);

// Error Messages from Objekts
define("OP_SUCCESSFUL", 1);
define("OP_FAILED", 2);
define("OP_PARENT_ID_INVALID",3);
define("OP_NOT_IN_DB",4);
define("OP_NOT_A_FILE",8);
define("OP_CONTENT_NOT_IN_CAT",9);
define("OP_NO_CONTENT", 13);
define("OP_PARTLY_SUCCESSFULL", 14);
define("OP_CONTENT_ALREADY_IN_CAT", 15);
// Not Permitted (NP) constants
define("OP_NP_MISSING_CAT_MOVE", 0); //return value for class functions who checks if the user is allowed to do something. If he is not, the functions returns this constant.
define("OP_NP_MISSING_CAT_ADD", 5);
define("OP_NP_MISSING_EDIT", 6);
define("OP_NP_MISSING_DELETE", 7);
define("OP_NP_MISSING_VIEW", 12);


// Groups and Users
define("OP_USER_ALREADY_IN_GROUP",10);
define("OP_USER_NOT_IN_GROUP",11);

//Class vars
define("NOT_SUPPORTED", 1); //return value for dummy functions in classes. When function will be not implemented in the extended object, this will be returned.

// Categorie delete modes
define("CDM_REMOVE_CONTENT",0);
define("CDM_MOVE_CONTENT",1);



?>
