<?php
require_once('includes/phpbb.inc.php');
require_once('config.inc.php');

define("GENERATE_NAMES", 1); //for functions add_dir
define("BLANK_NAMES", 0);

// Error Messages from Objekts
define("OP_SUCCESSFUL", 1);
define("OP_FAILED", 2);
define("OP_PARENT_ID_INVALID",3);
define("OP_NOT_IN_DB",4);
define("OP_NOT_A_FILE",8);
define("OP_CONTENT_NOT_IN_CAT",9);
// Not Permitted (NP) constants
define("OP_NP_MISSING_CAT_MOVE", 0); //return value for class functions who checks if the user is allowed to do something. If he is not, the functions returns this constant.
define("OP_NP_MISSING_CAT_ADD", 5);
define("OP_NP_MISSING_EDIT", 6);
define("OP_NP_MISSING_DELETE", 7);


//Class vars
define("NOT_SUPPORTED", 1); //return value for dummy functions in classes. When function will be not implemented in the extended object, this will be returned.

// Categorie delete modes
define("CDM_REMOVE_CONTENT",0);
define("CDM_MOVE_CONTENT",1);

//Template System
define("SMARTY_DIR","../smarty/");
?>
