<?php
require_once('includes/phpbb.inc.php');
require_once('config.inc.php');

define("GENERATE_NAMES", 1);
define("BLANK_NAMES", 0);

// Error Messages from Objekts
define("OP_NOT_PERMITTED", 0);
define("OP_SUCCESSFUL", 1);
define("OP_FAILED", 2);
define("OP_PARENT_ID_INVALID",3);
define("OP_NOT_IN_DB",4);

// Categorie delete modes
define("CDM_REMOVE_CONTENT",0);
define("CDM_MOVE_CONTENT",1);




?>
