<?php

//Template System
//absolute path to smarty
define("SMARTY_DIR","/home/httpd/htdocs/smarty/");

//relative path from phreakpic to phpBB2 (if the URL is "http://www.blabla.com/com/phpBB2/" and phreakpic is at "http://www.blabla.com/com/phreakpic/" then PHPBB_DIR will be "../phpBB2/")
//Don't forget the / at end!
define("PHPBB_PATH","../phpBB2/");

//relative path from phpBB2 to phreakpic see above
define("PHREAKPIC_PATH","../phreakpic/");

//link for the home button
define("SERVER_NAME","http://192.168.101.2");


$config_vars = array
(
	//Database
	'table_prefix' => 'photo_',

	// path to where the content should be stored
	'content_path_prefix' => 'content',

	//Picture stuff
	// size of thumbs (for generating)
	'thumb_size' =>
	array
	(
		// if set thumb is percent as big as the original picture
	//	'percent' => '30',
		// if set height will be exactly this value (if the width not set the apsectio ratio will be keept)
	//	'height' => '130',
		// if set width will be exactly this value
	//	'width' => '100'
		// if set the longer size will become this value
		'maxsize' => '130'
	),

	// ID of the cat where to put pictures that are no longer linked in any cat
	'deleted_content_cat' => 1,

	// ID of the root categorie
	'root_categorie' => 0,

	// Umask of new created directories
	'dir_mask' => 0775,
  
	//view_cat.php the Colums of the table, where we can see the thumbnails
	'thumb_table_cols' => 4,

	// template used if not setted by user
	'default_template' => 'standard',

	// language used if not setted by user
	'default_lang' => 'german',
	
	'default_upload_dir' => 'upload',
	
	// the ids of the usergroups in which every user is automaicly
	'default_usergroup_ids' => Array(1),
	
	
);
?>
