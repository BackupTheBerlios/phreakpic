<?php

//Template System
//absolute path to smarty
define("SMARTY_DIR","/home/httpd/htdocs/smarty/");

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
	'default_lang' => 'german'
);
?>
